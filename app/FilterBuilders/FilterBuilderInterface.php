<?php

namespace App\FilterBuilders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

interface FilterBuilderInterface
{
    public static function query(?array $extras = [], ?Request $request = null): Builder;

    public static function build(?array $input = null, ?array $extras = []): Builder;
}
