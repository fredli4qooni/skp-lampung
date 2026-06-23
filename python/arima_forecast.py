import sys
import json
import warnings
import os
import pandas as pd
import numpy as np
import pmdarima as pm
from sklearn.metrics import mean_squared_error

warnings.filterwarnings("ignore")

def main():
    try:
        base_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
        input_path = os.path.join(base_dir, 'storage', 'app', 'arima_input.json')
        
        with open(input_path, 'r') as f:
            data = json.load(f)

        df = pd.DataFrame(data)
        
        # 1. konversi ke tipe Angka (Float)
        df['ketersediaan_ton'] = pd.to_numeric(df['ketersediaan_ton'], errors='coerce')
        
        # 2. Konversi Tahun & Bulan menjadi Datetime Index
        df['periode'] = pd.to_datetime(df['tahun'].astype(str) + '-' + df['bulan'].astype(str) + '-01')
        df = df.sort_values('periode')
        df.set_index('periode', inplace=True)

        # 3. (Imputasi)
        # Resample ke frekuensi bulanan (MS = Month Start) lalu interpolasi garis lurus
        df_monthly = df.resample('MS').asfreq()
        df_monthly['ketersediaan_ton'] = df_monthly['ketersediaan_ton'].interpolate(method='linear')

        # 4. Agregasi Tahunan (Total per Tahun)
        # Resample ke tahunan (YS = Year Start) dengan fungsi penjumlahan (sum)
        df_yearly = df_monthly['ketersediaan_ton'].resample('YS').sum()

        series = df_yearly.values

        # Butuh minimal 3 tahun data untuk melihat tren dasar ARIMA
        if len(series) < 3:
            raise ValueError("Data historis setelah diagregasi terlalu sedikit (minimal 3 tahun).")

        # 5. Fit Model Auto ARIMA
        auto_model = pm.auto_arima(series, 
                                   start_p=1, max_p=1, 
                                   d=1, max_d=1,
                                   start_q=1, max_q=1, 
                                   seasonal=False, 
                                   stepwise=True, 
                                   suppress_warnings=True)
                                   
        p, d, q = auto_model.order

        # 6. Forecast 3 Tahun Kedepan
        forecast_steps = 3
        predictions, conf_int = auto_model.predict(n_periods=forecast_steps, return_conf_int=True, alpha=0.05)

        # 7. Evaluasi Akurasi pada data tahunan
        val_size = min(3, max(1, len(series) // 3))
        actual = series[-val_size:]
        fitted_vals = auto_model.predict_in_sample()[-val_size:]
        
        mape = np.mean(np.abs((actual - fitted_vals) / actual)) * 100
        rmse = np.sqrt(mean_squared_error(actual, fitted_vals))

        # 8. Format Output JSON
        last_year = df_yearly.index[-1].year
        hasil_prediksi = []

        predictions_list = list(predictions)
        conf_int_list = list(conf_int)

        for i in range(forecast_steps):
            next_year = last_year + i + 1
            hasil_prediksi.append({
                "tahun": next_year,
                "nilai": round(float(predictions_list[i]), 2),
                "lower_bound": round(float(conf_int_list[i][0]), 2),
                "upper_bound": round(float(conf_int_list[i][1]), 2)
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