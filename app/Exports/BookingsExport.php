<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BookingsExport implements WithMultipleSheets
{
    protected $bookings;
    protected $stats;

    public function __construct($bookings, $stats)
    {
        $this->bookings = $bookings;
        $this->stats = $stats;
    }

    public function sheets(): array
    {
        return [
            new BookingsSheet($this->bookings),
            new BookingsSummarySheet($this->stats),
        ];
    }
}

class BookingsSheet implements FromCollection, WithHeadings, WithStyles, WithEvents, WithTitle
{
    protected $bookings;

    public function __construct($bookings)
    {
        $this->bookings = $bookings;
    }

    public function collection()
    {
        // Map booking data only (header rows will be added separately)
        return $this->bookings->map(function ($booking, $index) {
            // Handle service_date - it's cast as date in the model
            $serviceDate = 'N/A';
            if ($booking->service_date) {
                try {
                    $serviceDate = $booking->service_date->format('M d, Y');
                } catch (\Exception $e) {
                    $serviceDate = 'N/A';
                }
            }
            
            return [
                $index + 1,
                $booking->id,
                $booking->user->name ?? 'N/A',
                $booking->service->name ?? 'N/A',
                $booking->priest->name ?? 'Not Assigned',
                $serviceDate,
                $booking->service_time ?? 'N/A',
                ucfirst($booking->status),
                $booking->payment ? ucfirst($booking->payment->payment_status) : 'No Payment',
                $booking->payment ? '₱' . number_format($booking->payment->total_fee, 2) : '₱0.00',
                $booking->created_at->format('M d, Y g:i A'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Booking ID',
            'User Name',
            'Service',
            'Priest',
            'Booking Date',
            'Booking Time',
            'Status',
            'Payment Status',
            'Total Fee',
            'Created At',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style column header row (Row 1 initially, will become Row 4 after insert)
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0d5c2f'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Center all data cells and add borders (starting from row 2, will shift to row 5 after insert)
        $lastRow = $this->bookings->count() + 1;
        $sheet->getStyle('A2:K' . $lastRow)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // Set header row height
        $sheet->getRowDimension(1)->setRowHeight(35);
        
        // Set data row heights (increased height)
        for ($row = 2; $row <= $lastRow; $row++) {
            $sheet->getRowDimension($row)->setRowHeight(30);
        }

        // Apply status colors
        foreach ($this->bookings as $index => $booking) {
            $row = $index + 2; // +2 because of header row (0-indexed)
            
            // Status column (H)
            $statusColor = $this->getStatusColor($booking->status);
            $sheet->getStyle('H' . $row)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $statusColor],
                ],
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => $this->getStatusTextColor($booking->status)],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]);

            // Payment Status column (I)
            $paymentStatus = $booking->payment ? $booking->payment->payment_status : 'no_payment';
            $paymentColor = $this->getPaymentStatusColor($paymentStatus);
            $sheet->getStyle('I' . $row)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $paymentColor],
                ],
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => $this->getPaymentStatusTextColor($paymentStatus)],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]);
        }

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Insert 3 rows at the top
                $sheet->insertNewRowBefore(1, 3);
                
                // Add title row (Row 1)
                $sheet->setCellValue('A1', 'SANTA MARTA PARISH - BOOKINGS REPORT');
                $sheet->mergeCells('A1:K1');
                
                // Add generated date (Row 2)
                $sheet->setCellValue('A2', 'Generated: ' . now()->format('M d, Y g:i A'));
                $sheet->mergeCells('A2:K2');
                
                // Row 3 is empty
                
                // Style title row
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '0d5c2f'],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                
                // Style generated date row
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => [
                        'size' => 10,
                        'italic' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                
                // Set row heights
                $sheet->getRowDimension(1)->setRowHeight(35);
                $sheet->getRowDimension(2)->setRowHeight(20);
                $sheet->getRowDimension(3)->setRowHeight(10);
                
                // Auto-size all columns with padding
                foreach (range('A', 'K') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
                
                // Add extra padding by adjusting auto-sized widths
                foreach (range('A', 'K') as $column) {
                    $currentWidth = $sheet->getColumnDimension($column)->getWidth();
                    $sheet->getColumnDimension($column)->setWidth($currentWidth + 3);
                }
            },
        ];
    }

    public function title(): string
    {
        return 'Bookings List';
    }

    private function getStatusColor($status)
    {
        return match($status) {
            'pending' => 'FEF3C7',        // Yellow
            'acknowledged' => 'DBEAFE',   // Blue
            'payment_hold' => 'FED7AA',   // Orange
            'approved' => 'D1FAE5',       // Green
            'completed' => 'D1FAE5',      // Green
            'rejected' => 'FEE2E2',       // Red
            'cancelled' => 'F3F4F6',      // Gray
            default => 'FFFFFF',
        };
    }

    private function getStatusTextColor($status)
    {
        return match($status) {
            'pending' => '92400E',
            'acknowledged' => '1E40AF',
            'payment_hold' => 'C2410C',
            'approved' => '065F46',
            'completed' => '065F46',
            'rejected' => '991B1B',
            'cancelled' => '4B5563',
            default => '000000',
        };
    }

    private function getPaymentStatusColor($status)
    {
        return match($status) {
            'pending' => 'FEF3C7',
            'paid' => 'DBEAFE',
            'verified' => 'D1FAE5',
            'rejected' => 'FEE2E2',
            default => 'F3F4F6',
        };
    }

    private function getPaymentStatusTextColor($status)
    {
        return match($status) {
            'pending' => '92400E',
            'paid' => '1E40AF',
            'verified' => '065F46',
            'rejected' => '991B1B',
            default => '4B5563',
        };
    }
}

class BookingsSummarySheet implements FromCollection, WithHeadings, WithStyles, WithEvents, WithTitle
{
    protected $stats;

    public function __construct($stats)
    {
        $this->stats = $stats;
    }

    public function collection()
    {
        return collect([
            ['BOOKINGS SUMMARY', ''],
            ['Generated: ' . now()->format('M d, Y g:i A'), ''],
            ['', ''],
            ['Status', 'Count'],
            ['Total Bookings', $this->stats['total'] ?? 0],
            ['Pending', $this->stats['pending'] ?? 0],
            ['Acknowledged', $this->stats['acknowledged'] ?? 0],
            ['Payment Hold', $this->stats['payment_hold'] ?? 0],
            ['Approved', $this->stats['approved'] ?? 0],
            ['Completed', $this->stats['completed'] ?? 0],
            ['Rejected', $this->stats['rejected'] ?? 0],
            ['Cancelled', $this->stats['cancelled'] ?? 0],
        ]);
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        // Title row (Row 1)
        $sheet->mergeCells('A1:B1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0d5c2f'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Generated date row (Row 2)
        $sheet->mergeCells('A2:B2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'size' => 10,
                'italic' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Column header row (Row 4)
        $sheet->getStyle('A4:B4')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0d5c2f'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Total row (Row 5)
        $sheet->getStyle('A5:B5')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E5E7EB'],
            ],
        ]);

        // All cells - center and add borders
        $sheet->getStyle('A4:B12')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Set row heights
        $sheet->getRowDimension(1)->setRowHeight(35);
        $sheet->getRowDimension(2)->setRowHeight(20);
        $sheet->getRowDimension(3)->setRowHeight(10);
        for ($row = 4; $row <= 12; $row++) {
            $sheet->getRowDimension($row)->setRowHeight(30);
        }

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Auto-size columns with padding
                foreach (range('A', 'B') as $column) {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setAutoSize(true);
                }
                
                foreach (range('A', 'B') as $column) {
                    $currentWidth = $event->sheet->getDelegate()->getColumnDimension($column)->getWidth();
                    $event->sheet->getDelegate()->getColumnDimension($column)->setWidth($currentWidth + 3);
                }
            },
        ];
    }

    public function title(): string
    {
        return 'Summary';
    }
}
