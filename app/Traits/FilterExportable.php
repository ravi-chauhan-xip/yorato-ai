<?php

namespace App\Traits;

use App\Models\Export;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Throwable;

trait FilterExportable
{
    use Exportable;

    private Export $export;

    public function __construct(Export $export)
    {
        $this->export = $export;
    }

    /**
     * {@inheritDoc}
     */
    public function query(): Builder
    {
        $request = (new Request)->replace($this->export->request_input);

        return $this->export->list_builder::query($this->export->extras, $request);
    }

    public function failed(Throwable $exception): void
    {
        $this->export->update([
            'status' => Export::STATUS_FAILED,
        ]);
    }
}
