<?php

namespace App\Exports\Sheets;

use App\Models\HasilPrediksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PrediksiSheet implements FromCollection, WithTitle, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        $latestRun = HasilPrediksi::orderBy('created_at', 'desc')->first();
        if (!$latestRun) return collect();

        $prediksi = HasilPrediksi::where('run_id', $latestRun->run_id)->orderBy('tahun_prediksi', 'asc')->get();
        
        $prediksi->transform(function ($item) {
            $ketJuta = $item->nilai_prediksi / 1000;
            if ($ketJuta >= 0.50) {
                $item->status_dinamis = 'AMAN';
            } elseif ($ketJuta < 0.50 && $ketJuta >= 0.20) {
                $item->status_dinamis = 'HATI-HATI';
            } elseif ($ketJuta < 0.20 && $ketJuta >= -0.45) {
                $item->status_dinamis = 'DARURAT';
            } else {
                $item->status_dinamis = 'HATI-HATI';
            }
            return $item;
        });

        return $prediksi;
    }

    public function title(): string
    {
        return 'Proyeksi AI';
    }

    public function headings(): array
    {
        return [
            ['PROYEKSI KETERSEDIAAN BERAS (3 TAHUN KEDEPAN)'],
            ['Tahun Proyeksi', 'Estimasi Ketersediaan (Juta Ton)', 'Status Kondisi Proyeksi']
        ];
    }

    public function map($data): array
    {
        return [
            $data->tahun_prediksi,
            (float) ($data->nilai_prediksi / 1000),
            $data->status_dinamis,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Merge title row
        $sheet->mergeCells('A1:C1');

        $highestRow = $sheet->getHighestRow();

        $sheet->getStyle('A1:C2')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF1E3A5F']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A1')->getFont()->setSize(14);
        
        if ($highestRow >= 3) {
            $sheet->getStyle('A1:C' . $highestRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF94A3B8'],
                    ],
                ],
            ]);
            
            // Format column B as number with thousand separator
            $sheet->getStyle('B3:B' . $highestRow)->getNumberFormat()->setFormatCode('#,##0.00');
            
            // Center align Year and Status columns
            $sheet->getStyle('A3:A' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C3:C' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        return [];
    }
}
