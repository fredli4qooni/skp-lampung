import sys
import json
import warnings
import os
import pandas as pd
import numpy as np
from statsmodels.tsa.arima.model import ARIMA
from sklearn.metrics import mean_squared_error

# Matikan warning agar output JSON ke PHP tidak rusak
warnings.filterwarnings("ignore")

def main():
    try:
        # 1. Baca data input menggunakan Absolute Path
        # Mendapatkan lokasi root direktori (naik 1 folder dari folder 'python')
        base_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
        input_path = os.path.join(base_dir, 'storage', 'app', 'arima_input.json')
        
        with open(input_path, 'r') as f:
            data = json.load(f)

        df = pd.DataFrame(data)
        
        # Pastikan data diurutkan berdasarkan tahun
        df = df.sort_values('tahun')
        series = df['ketersediaan_ton'].astype(float).values
        tahun_terakhir = int(df['tahun'].max())

        # Minimal data untuk ARIMA biasanya 3-5 tahun
        if len(series) < 3:
            raise ValueError("Data historis terlalu sedikit (minimal 3 tahun) untuk melakukan prediksi ARIMA.")

        # 2. Ambil parameter (p, d, q) dari argumen command line
        p = int(sys.argv[sys.argv.index('--p') + 1])
        d = int(sys.argv[sys.argv.index('--d') + 1])
        q = int(sys.argv[sys.argv.index('--q') + 1])

        # 3. Fit Model ARIMA
        model = ARIMA(series, order=(p, d, q))
        fitted = model.fit()

        # 4. Forecast 3 Tahun ke Depan [cite: 244-245]
        forecast_steps = 3
        forecast_result = fitted.get_forecast(steps=forecast_steps)
        predictions = forecast_result.predicted_mean
        conf_int = forecast_result.conf_int(alpha=0.05) # Confidence interval 95%

        # 5. Evaluasi Akurasi (MAPE & RMSE)
        val_size = min(5, max(1, len(series) // 2))
        actual = series[-val_size:]
        fitted_vals = fitted.fittedvalues[-val_size:]
        
        mape = np.mean(np.abs((actual - fitted_vals) / actual)) * 100
        rmse = np.sqrt(mean_squared_error(actual, fitted_vals))

        # 6. Format Output
        hasil_prediksi = []
        for i in range(forecast_steps):
            hasil_prediksi.append({
                "tahun": tahun_terakhir + i + 1,
                "nilai": round(float(predictions[i]), 2),
                "lower_bound": round(float(conf_int[i][0]), 2),
                "upper_bound": round(float(conf_int[i][1]), 2)
            })

        output = {
            "status": "success",
            "predictions": hasil_prediksi,
            "mape": round(float(mape), 4),
            "rmse": round(float(rmse), 4),
            "params": {"p": p, "d": d, "q": q}
        }
        
        # Cetak output JSON
        print(json.dumps(output))

    except Exception as e:
        error_output = {
            "status": "error",
            "message": str(e)
        }
        print(json.dumps(error_output))

if __name__ == "__main__":
    main()