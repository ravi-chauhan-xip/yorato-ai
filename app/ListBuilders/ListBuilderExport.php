<?php

namespace App\ListBuilders;

use App\Traits\FilterExportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ListBuilderExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
{
    use FilterExportable;

    public function map($model): array
    {
        return $this->export->list_builder::exportRow($model);
    }

    /**
     * {@inheritDoc}
     */
    public function headings(): array
    {
        return $this->export->list_builder::exportHeadings();
    }
}
