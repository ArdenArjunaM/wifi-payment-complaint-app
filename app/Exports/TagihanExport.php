<?php

namespace App\Exports;

use App\Models\Tagihan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TagihanExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithColumnWidths, WithMapping
{
    private $rowNumber = 0;

    public function collection()
    {
        return Tagihan::with(['user', 'paketWifi', 'statusTagihan', 'pengajuan'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'ID Tagihan',
            'Nama Pelanggan',
            'Paket WiFi',
            'Jumlah Tagihan',
            'Status',
            'Tanggal Dibuat',
            'Jatuh Tempo',
            'Keterangan'
        ];
    }

    public function map($tagihan): array
    {
        $this->rowNumber++;
        
        return [
            $this->rowNumber,
            'TG-' . str_pad($tagihan->id, 4, '0', STR_PAD_LEFT),
            $tagihan->user->nama ?? $tagihan->user->name ?? 'N/A',
            $tagihan->paketWifi->nama_paket ?? 'N/A',
            'Rp ' . number_format($tagihan->jumlah_tagihan ?? $tagihan->total ?? 0, 0, ',', '.'),
            $tagihan->statusTagihan->status ?? 'N/A',
            $tagihan->created_at ? $tagihan->created_at->format('d/m/Y') : 'N/A',
            $tagihan->jatuh_tempo ? date('d/m/Y', strtotime($tagihan->jatuh_tempo)) : 'N/A',
            $tagihan->keterangan ?? ($tagihan->pengajuan ? 'Ada Pengajuan' : 'Normal')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // Header Style
        $sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '366092'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // All data borders
        $sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // Center align untuk kolom No, ID, Status, Tanggal
        $sheet->getStyle('A:A')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B:B')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F:F')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G:G')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H:H')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Right align untuk kolom Jumlah Tagihan
        $sheet->getStyle('E:E')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 12,  // ID Tagihan
            'C' => 25,  // Nama Pelanggan
            'D' => 20,  // Paket WiFi
            'E' => 18,  // Jumlah Tagihan
            'F' => 15,  // Status
            'G' => 15,  // Tanggal Dibuat
            'H' => 15,  // Jatuh Tempo
            'I' => 20,  // Keterangan
        ];
    }

    public function title(): string
    {
        return 'Data Tagihan ' . date('d-m-Y');
    }
}