<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ServiceReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $services;

    public function __construct($services)
    {
        $this->services = $services;
    }

    public function collection()
    {
        return $this->services;
    }

    public function headings(): array
    {
        return [
            'Kode service', 'Pelanggan', 'Jenis Layanan', 'Barang',
            'Teknisi', 'Status', 'Biaya Jasa', 'Biaya Sparepart', 'Total', 'Tanggal Dibuat',
        ];
    }

    public function map($service): array
    {
        return [
            $service->service_code,
            $service->customer->name ?? '-',
            $service->service_type,
            $service->item_name,
            $service->technician->name ?? '-',
            $service->status,
            $service->service_fee,
            $service->sparepart_cost,
            $service->total_cost,
            $service->created_at->format('d-m-Y'),
        ];
    }
}