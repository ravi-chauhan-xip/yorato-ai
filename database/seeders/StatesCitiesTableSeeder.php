<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use DB;
use Illuminate\Database\Seeder;
use MenaraSolutions\Geographer\Earth;
use Throwable;

class StatesCitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     * @throws Throwable
     */
    public function run()
    {
        DB::transaction(function () {
            $earth = new Earth;
            $countries = Country::get();
            foreach ($countries as $country) {
                $c = $earth->findOne(['name' => $country->name]);
                $states = $c->getStates();
                if (count($states) > 0) {
                    foreach ($states as $state) {
                        $cities = $state->getCities();
                        if (count($cities) > 0) {
                            $stateData = State::create([
                                'name' => $state->toArray()['name'],
                                'country_id' => $country->id,
                            ]);
                            foreach ($cities as $city) {
                                City::create([
                                    'name' => $city->toArray()['name'],
                                    'state_id' => $stateData->id,
                                ]);
                            }
                        }
                    }
                }
            }
        }, 5);
    }
}
