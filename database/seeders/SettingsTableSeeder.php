<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'company_name' => 'PoloGain',
            'email' => '',
            'mobile' => '',
            'address_enabled' => true,
            'address_line_1' => 'Akshya Nagar 1st Block 1st Cross',
            'address_line_2' => 'Rammurthy nagar',
            'country' => Country::whereName('India')->first()?->name,
            'state' => State::whereName('Gujarat')->first()?->name,
            'city' => City::whereName('Vadodara')->first()?->name,
            'pincode' => '767067',
            'admin_roles' => false,
            'sms_enabled' => false,
            'email_enabled' => true,
            'tds_percent' => 5,
            'admin_charge_percent' => 5,
            'transaction_password' => true,
            'primary_color' => '#1496fc',
            'primary_color_hover' => '#0bc8fc',
            'payment_gateway' => false,
            'is_dark' => false,
            'is_bordered_theme' => true,
            'is_ecommerce' => false,
            'logo_changes' => false,
            'about_us' => "PoloGain is more than just an investment opportunity; it's a value-driven staking community. Our foundation rests on trust, transparency, and integrity. We create enduring relationships with our community members, fostering an environment where your financial aspirations can flourish.",
            'terms' => 'Please read these conditions carefully before using the PoloGain website. By using the PoloGain website, you signify your agreement to be bound by these conditions.In addition, when you use any current or future PoloGain service (eg. Wish List or Marketplace) (\"PoloGain Service\"), you will also be subject to the terms, guidelines and conditions applicable to that PoloGain Service. (\"Terms\"). If these Conditions of Use are inconsistent with such Terms, the Terms will control.These \"Conditions of Use\" constitute an electronic record within the meaning of the applicable law. This electronic record is generated by a computer system and does not require any physical or digital signatures.',
            'privacy_policy' => '',
            'social_link' => true,
            'facebook_url' => '',
            'instagram_url' => '',
            'youtube_url' => '',
            'twitter_url' => '',
            'telegram_url' => '',
            'telegram_group_url' => '',
            'zoom_url' => '',
            'coin_name' => 'PoloGain',
            'coin_symbol' => 'PoloGain',
            'coin_price' => 0.1,
            'decimal' => 6,
            'network_provider' => 'BEP-20 Chain',
            'processed_block' => null,
            'timer_date' => '2023/12/21 00:00:00',
            'timer_start_date' => '1 Dec 2023',
            'timer_end_time' => '21 Dec 2023',
            'min_staking' => 100,
            'min_withdrawal_limit' => 10,
            'service_charge' => 5,
            'dailyIncomePercentage' => 1,
            'capping_days' => 900,
            'min_matching' => 10,
        ];

        foreach ($settings as $settingKey => $settingValue) {
            settings([$settingKey => $settingValue]);
        }
    }
}
