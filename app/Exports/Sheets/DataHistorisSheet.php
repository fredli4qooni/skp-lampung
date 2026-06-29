<?php

namespace App\Exports\Sheets;

use App\Models\DataBeras;
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

class DataHistorisSheet implements FromCollection, WithTitle, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    private $namaBulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    public function collection()
    {
        return DataBeras::orderBy('tahun', 'asc')->orderBy('bulan', 'asc')->get();
    }

    public function title(): string
    {
        return 'Riwayat Aktual';
    }

    public function headings(): array
    {
        return [
            ['LAPORAN KETERSEDIAAN BERAS (AKTUAL)'],
            ['Tahun', 'Bulan', 'Produksi (Ton)', 'Impor (Ton)', 'Konsumsi (Ton)', 'Ketersediaan Bersih (Ton)']
        ];
    }

    public function map($data): array
    {
        return [
            $data->tahun,
            $this->namaBulan[$data->bulan] ?? $data->bulan,
            (float) $data->produksi_ton,
            (float) $data->impor_ton,
            (float) $data->konsumsi_ton,
            (float) $data->ketersediaan_ton,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Merge title row
        $sheet->mergeCells('A1:F1');

        $highestRow = $sheet->getHighestRow();

        $sheet->getStyle('A1:F2')->applyFromArray([
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
        
        $sheet->getStyle('A1:F' . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF94A3B8'],
                ],
            ],
        ]);
        
        // Format columns as numbers with thousand separator
        $sheet->getStyle('C3:F' . $highestRow)->getNumberFormat()->setFormatCode('#,##0.00');
        
        // Center align Year and Month columns
        $sheet->getStyle('A3:B' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }
}
