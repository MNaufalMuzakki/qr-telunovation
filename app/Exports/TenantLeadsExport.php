<?php

namespace App\Exports;

use App\Models\Visit;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TenantLeadsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected int $tenantId;
    protected int $rowNumber = 0;

    public function __construct(int $tenantId)
    {
        $this->tenantId = $tenantId;
    }

    public function query()
    {
        return Visit::query()
            ->where('tenant_id', $this->tenantId)
            ->with('visitor')
            ->latest('scanned_at');
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Pengunjung',
            'Email',
            'No. Telepon / WhatsApp',
            'Waktu Scan',
        ];
    }

    /**
     * @param Visit $visit
     */
    public function map($visit): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $visit->visitor->name ?? '-',
            $visit->visitor->email ?? '-',
            $visit->visitor->phone ?? '-',
            $visit->scanned_at ? $visit->scanned_at->format('Y-m-d H:i:s') : '-',
        ];
    }
}
