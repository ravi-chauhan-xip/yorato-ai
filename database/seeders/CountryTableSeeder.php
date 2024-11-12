<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Seeder;
use MenaraSolutions\Geographer\Earth;
use Throwable;

class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws Throwable
     */
    public function run(): void
    {
        $earth = new Earth;
        $countries = $earth->getCountries();

        /** @var \MenaraSolutions\Geographer\Country $country */
        foreach ($countries as $country) {
            $createCountry = false;

            $states = $country->getStates();

            if ($states->count() > 0) {
                /** @var State $state */
                foreach ($states as $state) {
                    if ($state->getCities()->count() > 0) {
                        $createCountry = true;

                        break;
                    }
                }
            }
            $countryName = $country['name'];
            $countryCode = $country['phonePrefix'];

            if ($createCountry && $countryCode && is_numeric($countryCode)) {

                $regExDetail = '/^[1-9][0-9]{7,11}$/';

                if ($countryCode === '91') {
                    $regExDetail = '/^[6789][0-9]{9}$/';
                }

                Country::updateOrCreate([
                    'name' => $countryName,
                    'code' => $countryCode,
                    'regex' => $regExDetail,
                ]);
            }
        }
    }
}
