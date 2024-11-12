<?php

namespace App\Jobs;

use App\Models\Export;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateStatusOfCompletedExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Export $export;

    public function __construct(Export $export)
    {
        $this->export = $export;
    }

    public function handle(): void
    {
        $this->export->update([
            'status' => Export::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);
    }
}
