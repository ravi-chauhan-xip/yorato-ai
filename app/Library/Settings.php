<?php

namespace App\Library;

use App\Models\Setting;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class Settings
{
    private array $items;

    public function __construct()
    {
        $this->cacheItems();
    }

    private function cacheItems(): void
    {
        $this->items = Setting::all([
            'key', 'value',
        ])->mapWithKeys(function ($setting) {
            return [$setting['key'] => $setting['value']];
        })->toArray();
    }

    /**
     * @return mixed|null
     */
    public function get(string $key, $default = null)
    {
        if (array_key_exists($key, $this->items)) {
            $value = $this->items[$key];
        } elseif ($setting = Setting::where('key', $key)->first()) {
            $this->items[$key] = $setting->value;

            $value = $setting->value;
        }

        if (isset($value)) {
            return $value;
        }

        return $default;
    }

    /**
     * @param  array|string  $key
     * @param  null|string|int|array  $value
     */
    public function set($key, $value = null): void
    {
        $keys = is_array($key) ? $key : [$key => $value];

        foreach ($keys as $key => $value) {
            $this->items[$key] = $value;

            if ($setting = Setting::where('key', $key)->first()) {
                $setting->value = $value;
                $setting->save();
            } else {
                Setting::create([
                    'key' => $key,
                    'value' => $value,
                ]);
            }
        }
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function attachMedia(string $key, string $filePath, string $fileName, bool $deleteOldFiles = false): void
    {
        if (! $setting = $this->model($key)) {
            settings([$key => $key]);

            $setting = $this->model($key);
        }

        if ($deleteOldFiles) {
            $setting->clearMediaCollection($key);
        }

        $setting->addMediaFromDisk($filePath)
            ->usingFileName($fileName)
            ->toMediaCollection($key);
    }

    public function removeMedia(string $key): void
    {
        if ($setting = $this->model($key)) {
            $setting->clearMediaCollection($key);

            $setting->delete();
        }
    }

    public function model(string $key): ?Setting
    {
        return Setting::where('key', $key)->first();
    }

    public function getFileUrl($key, string $default = ''): string
    {
        if ($setting = $this->model($key)) {
            return $setting->getFirstMediaUrl($key);
        } else {
            return $default;
        }
    }

    public function refresh()
    {
        $this->cacheItems();
    }
}
