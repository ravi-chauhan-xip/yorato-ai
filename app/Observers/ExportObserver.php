<?php

namespace App\Observers;

use App\Models\Export;
use Storage;

class ExportObserver
{
    public function deleting(Export $export)
    {
        Storage::delete($export->file_path);
    }
}
