<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWebSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('city_id')->nullable()->constrained();
            $table->foreignId('state_id')->nullable()->constrained();
            $table->foreignId('country_id')->nullable()->constrained();
            $table->longText('about_us')->nullable();
            $table->longText('vision')->nullable();
            $table->longText('founder_message')->nullable();
            $table->longText('terms')->nullable();
            $table->longText('return_policy')->nullable();
            $table->longText('privacy_policy')->nullable();
            $table->longText('shipping_policy')->nullable();
            $table->string('company_name');
            $table->string('gst_no')->nullable();
            $table->longText('address_line_1')->nullable();
            $table->longText('address_line_2')->nullable();
            $table->string('pincode')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('zoom_url')->nullable();
            $table->string('grievance_name')->nullable();
            $table->string('grievance_mobile')->nullable();
            $table->string('grievance_email')->nullable();
            $table->string('nodal_name')->nullable();
            $table->string('nodal_mobile')->nullable();
            $table->string('nodal_email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('web_settings');
    }
}
