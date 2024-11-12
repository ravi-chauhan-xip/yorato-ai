<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function getState(Request $request, $countryIdOrName): JsonResponse
    {
        $states = State::where('country_id', $countryIdOrName)
            ->orWhereHas('country', function (Builder|Country $query) use ($countryIdOrName) {
                return $query->whereName($countryIdOrName);
            })
            ->get();

        return response()->json($states);
    }

    public function getCity(Request $request, $stateIdOrName): JsonResponse
    {
        $cities = City::where('state_id', $stateIdOrName)
            ->orWhereHas('state', function (Builder|State $query) use ($stateIdOrName) {
                return $query->whereName($stateIdOrName);
            })->get();

        return response()->json($cities);
    }
}
