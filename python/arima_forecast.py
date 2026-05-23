import sys
import json
import warnings
import os
import pandas as pd
import numpy as np
from statsmodels.tsa.statespace.sarimax import SARIMAX
from sklearn.metrics import mean_squared_error

# Matikan warning
warnings.filterwarnings("ignore")

def main():
    try:
        base_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
        input_path = os.path.join(base_dir, 'storage', 'app', 'arima_input.json')
        
        with open(input_path, 'r') as f:
            data = json.load(f)

        df = pd.DataFrame(data)
        
        # PERBAIKAN: Paksa konversi kolom ketersediaan_ton dari String ke Float (Angka)
        df['ketersediaan_ton'] = pd.to_numeric(df['ketersediaan_ton'], errors='coerce')
        
        # 1. Konversi Tahun & Bulan menjadi Datetime Index
        df['periode'] = pd.to_datetime(df['tahun'].astype(str) + '-' + df['bulan'].astype(str) + '-01')
        df = df.sort_values('periode')
        df.set_index('periode', inplace=True)

        # 2. DATA IMPUTATION (Penanganan Data Hilang)
        # Resample data ke frekuensi awal bulan (Month Start / MS)
        df = df.resample('MS').asfreq()

        # Gunakan Interpolasi Linear untuk mengisi nilai NaN
        df['ketersediaan_ton'] = df['ketersediaan_ton'].interpolate(method='linear')

        series = df['ketersediaan_ton'].values

        if len(series) < 12:
            raise ValueError("Data historis terlalu sedikit (minimal 12 bulan) untuk melakukan prediksi SARIMA.")

        p = int(sys.argv[sys.argv.index('--p') + 1])
        d = int(sys.argv[sys.argv.index('--d') + 1])
        q = int(sys.argv[sys.argv.index('--q') + 1])

        # 3. Fit Model SARIMA
        model = SARIMAX(series, order=(p, d, q), seasonal_order=(1, 0, 1, 12), enforce_stationarity=False, enforce_invertibility=False)
        fitted = model.fit(disp=False)

        # 4. Forecast 12 Bulan ke Depan
        forecast_steps = 12
        forecast_result = fitted.get_forecast(steps=forecast_steps)
        predictions = forecast_result.predicted_mean
        conf_int = forecast_result.conf_int(alpha=0.05)

        # 5. Evaluasi Akurasi 
        val_size = min(12, max(1, len(series) // 4))
        actual = series[-val_size:]
        fitted_vals = fitted.fittedvalues[-val_size:]
        
        mape = np.mean(np.abs((actual - fitted_vals) / actual)) * 100
        rmse = np.sqrt(mean_squared_error(actual, fitted_vals))

        # 6. Format Output
        last_date = df.index[-1]
        hasil_prediksi = []

        for i in range(forecast_steps):
            next_date = last_date + pd.DateOffset(months=i+1)
            hasil_prediksi.append({
                "tahun": next_date.year,
                "bulan": next_date.month,
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
        
        print(json.dumps(output))

    except Exception as e:
        error_output = {
            "status": "error",
            "message": str(e)
        }
        print(json.dumps(error_output))

if __name__ == "__main__":
    main()