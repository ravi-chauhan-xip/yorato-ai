<?php

use App\Jobs\UpgradeDatabase;
use App\Models\StakeCoin;
use Illuminate\Support\Facades\Artisan;

Artisan::command('docs', function () {
    $this->call('clear-compiled');
    $this->call('ide-helper:generate', [
        '-H' => true,
    ]);
    $this->call('ide-helper:models', [
        '-W' => true,
        '-R' => true,
    ]);
    $this->call('ide-helper:meta');
})->describe('Generate Laravel IDE-Helper Docs');

Artisan::command('upgrade', function () {
    UpgradeDatabase::dispatch();
});

Artisan::command('daily', function () {

    \App\Models\StakeCoin::whereHas('member.user', function ($q) {
        return $q->whereIn('wallet_address', [
            '0xDfd028c3C63A964CBD5683a9e59d4507B3993a0b',
        ]);
    })->whereDate('created_at', '2024-07-27')
        ->update([
            'status' => StakeCoin::STATUS_FINISH,
        ]);

    \App\Models\StakeCoin::whereHas('member.user', function ($q) {
        return $q->whereIn('wallet_address', [
            '0x58713f00D80512E02423ff74B7B84fF142E0Fb12',
        ]);
    })->whereDate('created_at', '2024-07-16')
        ->where('amount', '=', 35)
        ->update([
            'status' => StakeCoin::STATUS_FINISH,
        ]);

    \App\Models\StakeCoin::whereHas('member.user', function ($q) {
        return $q->whereIn('wallet_address', [
            '0x46bd544ff5b81710e0916e69f2c368636ef5a3db',
        ]);
    })->whereDate('created_at', '2024-07-15')
        ->where('amount', '=', 522)
        ->update([
            'status' => StakeCoin::STATUS_FINISH,
        ]);
});
