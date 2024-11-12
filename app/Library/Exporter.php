<?php

namespace App\Library;

use App\Jobs\UpdateStatusOfCompletedExport;
use App\Models\Export;
use App\Traits\FilterExportable;
use Auth;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Excel;

class Exporter
{
    const EXTENSION_XLSX = 1;

    const EXTENSION_CSV = 2;

    const EXTENSIONS = [
        self::EXTENSION_XLSX => 'XLSX',
        self::EXTENSION_CSV => 'CSV',
    ];

    private string $exportableTrait = FilterExportable::class;

    private ?string $filePrefix;

    private int $extension = self::EXTENSION_XLSX;

    private ?Export $export;

    private Carbon $timestamp;

    private array $extras = [];

    /**
     * @throws Exception
     */
    public function __construct(private string $exportClass, private string $listBuilderClass)
    {
        $this->checkExportClass();

        $this->timestamp = now();
    }

    /**
     * @throws Exception
     */
    public static function create(string $exportClass, string $listBuilderClass): static
    {
        return new static($exportClass, $listBuilderClass);
    }

    public function usingFilePrefix(string $filePrefix): static
    {
        $this->filePrefix = $filePrefix;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function usingExtension(int $extension): static
    {
        if (! in_array($this->extension, array_keys(self::EXTENSIONS))) {
            throw new Exception("Extension $this->extension is not supported.");
        }

        $this->extension = $extension;

        return $this;
    }

    public function queue(): void
    {
        $this->createExport()
            ->dispatchJob();
    }

    public function redirect(?string $route = null, ?array $with = []): RedirectResponse
    {
        $this->queue();

        $redirect = redirect();

        if ($route) {
            $redirect = $redirect->route($route);
        } else {
            $redirect = $redirect->back();
        }

        return $redirect->with(array_merge([
            'success-export' => 'Your export has been queued. Please check the Export Module for status.',
        ], $with));
    }

    /**
     * @throws Exception
     */
    private function checkExportClass()
    {
        if (! method_exists($this->exportClass, 'queue')) {
            throw new Exception("$this->exportClass is not queueable.");
        }

        if (! in_array($this->exportableTrait, class_uses($this->exportClass))) {
            throw new Exception("$this->exportClass does not use $this->exportableTrait Trait.");
        }
    }

    private function dispatchJob()
    {
        (new $this->exportClass($this->export))
            ->queue(
                filePath: $this->filePath(),
                writerType: $this->writerType(),
                disk: 'public'
            )->chain([
                new UpdateStatusOfCompletedExport($this->export),
            ]);
    }

    private function writerType(): ?string
    {
        if ($this->extension === self::EXTENSION_XLSX) {
            return Excel::XLSX;
        } elseif ($this->extension === self::EXTENSION_CSV) {
            return Excel::CSV;
        }

        return null;
    }

    private function createExport(): static
    {
        $user = null;
        $admin = null;
        if (Auth::user()->hasRole('admin')) {
            $admin = Auth::user();
        } elseif (Auth::user()->hasRole('member')) {
            $user = Auth::user();
        }
        $this->export = Export::create([
            'admin_id' => $admin?->id,
            'user_id' => $user?->id,
            'request_input' => request()->all(),
            'list_builder' => $this->listBuilderClass,
            'extras' => $this->extras,
            'file_name' => $this->fileName(),
            'file_path' => $this->filePath(),
        ]);

        return $this;
    }

    private function filePath(): string
    {
        return "exports/{$this->fileName()}";
    }

    private function fileName(): string
    {
        $fileName = $this->timestamp->format('Y-m-d-H-i-s').$this->fileExtension();

        if ($filePrefix = request('export_prefix', $this->filePrefix)) {
            $fileName = $filePrefix.'-'.$fileName;
        }

        return $fileName;
    }

    private function fileExtension(): string
    {
        if ($this->extension === self::EXTENSION_XLSX) {
            return '.xlsx';
        } elseif ($this->extension === self::EXTENSION_CSV) {
            return '.csv';
        }

        return '';
    }

    public function withExtras(array $extras = []): static
    {
        $this->extras = $extras;

        return $this;
    }
}
