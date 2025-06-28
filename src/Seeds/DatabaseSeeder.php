<?php

namespace Hitech\IndonesiaLaravel\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Hitech\IndonesiaLaravel\Models\City;
use Hitech\IndonesiaLaravel\Models\District;
use Hitech\IndonesiaLaravel\Models\Province;
use Hitech\IndonesiaLaravel\Models\Village;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->reset();

        if (config('indonesia.province.enabled')) {
            $this->call(ProvincesSeeder::class);
        }
        if (config('indonesia.city.enabled')) {
            $this->call(CitiesSeeder::class);
        }
        if (config('indonesia.district.enabled')) {
            $this->call(DistrictsSeeder::class);
        }
        if (config('indonesia.village.enabled')) {
            $this->call(VillagesSeeder::class);
        }
    }

    public function reset()
    {
        Schema::disableForeignKeyConstraints();

        if (config('indonesia.village.enabled')) {
            Village::truncate();
        }
        if (config('indonesia.district.enabled')) {
            District::truncate();
        }
        if (config('indonesia.city.enabled')) {
            City::truncate();
        }
        if (config('indonesia.province.enabled')) {
            Province::truncate();
        }

        Schema::disableForeignKeyConstraints();
    }
}
