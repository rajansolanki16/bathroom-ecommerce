<?php

namespace App\Exports;

use App\Models\StoreVisit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StoreVisitsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = StoreVisit::with(['vendor', 'salesman']);

        // Apply user role filters
        if (!empty($this->filters['user'])) {
            $user = $this->filters['user'];
            if ($user->hasRole('salesman')) {
                $query->where('salesman_id', $user->id);
            } elseif ($user->hasRole('vendor')) {
                $query->where('vendor_id', $user->id);
            }
        }

        // Apply salesman filter
        if (!empty($this->filters['salesman_id'])) {
            $query->where('salesman_id', $this->filters['salesman_id']);
        }

        // Apply vendor filter
        if (!empty($this->filters['vendor_id'])) {
            $query->where('vendor_id', $this->filters['vendor_id']);
        }

        // Apply date filters
        if (!empty($this->filters['from_date'])) {
            $query->whereDate('created_at', '>=', $this->filters['from_date']);
        }

        if (!empty($this->filters['to_date'])) {
            $query->whereDate('created_at', '<=', $this->filters['to_date']);
        }
        
        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Salesman Name',
            'Vendor Name',
            'Purpose',
            'Notes',
            'Feedback',
            'Outcome',
            'Rating',
            'Follow Up Required',
            'Next Follow Up Date',
            'Location Address',
            'Visit Date',
        ];
    }

    public function map($visit): array
    {
        return [
            $visit->id,
            $visit->salesman->name ?? '-',
            $visit->vendor->name ?? '-',
            $visit->purpose ?? '-',
            $visit->notes ?? '-',
            $visit->feedback ?? '-',
            $visit->outcome ?? '-',
            $visit->rating ?? '-',
            $visit->follow_up_required ? 'Yes' : 'No',
            $visit->next_follow_up_date ? $visit->next_follow_up_date->format('d M Y') : '-',
            $visit->location_address ?? '-',
            $visit->created_at->format('d M Y, h:i A'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4CAF50']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ]
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 20,
            'C' => 20,
            'D' => 25,
            'E' => 25,
            'F' => 30,
            'G' => 20,
            'H' => 15,
            'I' => 20,
            'J' => 20,
            'K' => 30,
            'L' => 25, 
        ];
    }

}