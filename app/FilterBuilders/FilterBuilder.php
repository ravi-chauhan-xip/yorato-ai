<?php

namespace App\FilterBuilders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class FilterBuilder implements FilterBuilderInterface
{
    public static function build(?array $extras = [], ?array $input = null): Builder
    {
        if ($input) {
            $request = (new Request)->replace($input);
        } else {
            $request = request();
        }

        return static::query($request, $extras);
    }
}
