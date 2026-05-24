<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DataBeras;
use App\Models\HasilPrediksi;
use App\Models\KonfigurasiSistem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class JalankanArima extends Command
{
    protected $signature = 'arima:forecast 
                            {--p= : AR order}
                            {--d= : Differencing order}
                            {--q= : MA order}';

    protected $description = 'Menjalankan prediksi ketersediaan beras menggunakan model ARIMA via Python';

    public function handle()
    {
        $this->info('Memulai proses prediksi ARIMA...');

        $p = $this->option('p') ?? KonfigurasiSistem::where('key', 'arima_default_p')->value('value') ?? 1;
        $d = $this->option('d') ?? KonfigurasiSistem::where('key', 'arima_default_d')->value('value') ?? 1;
        $q = $this->option('q') ?? KonfigurasiSistem::where('key', 'arima_default_q')->value('value') ?? 1;

        $this->info("Menggunakan parameter ARIMA: p=$p, d=$d, q=$q");

        $data = DataBeras::orderBy('tahun', 'asc')->get();
        if ($data->count() < 3) {
            $this->error('Data historis tidak mencukupi. Minimal butuh 3 tahun data.');
            return 1;
        }

        $inputPath = storage_path('app/arima_input.json');
        
        if (!file_exists(storage_path('app'))) {
            mkdir(storage_path('app'), 0775, true);
        }
        
        file_put_contents($inputPath, $data->toJson());

        $pythonPath = base_path('venv/Scripts/python.exe'); 
        if (!file_exists($pythonPath)) {
            $pythonPath = base_path('venv/bin/python');
        }

        $scriptPath = base_path('python/arima_forecast.py');

        $this->info('Mengeksekusi Python script... (Tunggu sebentar, komputasi ML memakan waktu beberapa detik)');
        
        $env = [
            'SYSTEMROOT' => getenv('SYSTEMROOT') ?: 'C:\\Windows',
            'PATH' => getenv('PATH')
        ];
        $process = new Process([$pythonPath, $scriptPath, '--p', $p, '--d', $d, '--q', $q], null, $env);
        $process->setTimeout(120);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->error('Gagal menjalankan script Python:');
            $this->error($process->getErrorOutput());
            return 1;
        }

        $output = $process->getOutput();
        $hasil = json_decode($output, true);

        if (!$hasil) {
            $this->error('Format output JSON dari Python tidak valid atau kosong.');
            $this->error('Output mentah: ' . $output);
            return 1;
        }

        if (isset($hasil['status']) && $hasil['status'] === 'error') {
            $this->error('Error dari Python: ' . $hasil['message']);
            return 1;
        }

        $thresholdAman = (float) KonfigurasiSistem::where('key', 'threshold_aman')->value('value') ?? 100000;
        $thresholdWaspada = (float) KonfigurasiSistem::where('key', 'threshold_waspada')->value('value') ?? 50000;

        $runId = Str::uuid()->toString();
        $now = now();

        foreach ($hasil['predictions'] as $pred) {
            $nilaiPrediksi = $pred['nilai'];

            if ($nilaiPrediksi >= 1200) {
                $status = 'aman';
            } elseif ($nilaiPrediksi >= 800 && $nilaiPrediksi < 1200) {
                $status = 'waspada';
            } else {
                $status = 'darurat';
            }

            HasilPrediksi::create([
                'run_id' => $runId,
                'tahun_prediksi' => $pred['tahun'],
                'nilai_prediksi' => $nilaiPrediksi,
                'lower_bound' => $pred['lower_bound'],
                'upper_bound' => $pred['upper_bound'],
                'parameter_p' => $p,
                'parameter_d' => $d,
                'parameter_q' => $q,
                'mape' => $hasil['mape'],
                'rmse' => $hasil['rmse'],
                'status_kondisi' => $status,
                'created_at' => $now,
            ]);
        }

        $this->info('Prediksi ARIMA berhasil dijalankan dan disimpan ke database!');
        $this->info("MAPE: {$hasil['mape']}%, RMSE: {$hasil['rmse']}");
        return 0;
    }
}