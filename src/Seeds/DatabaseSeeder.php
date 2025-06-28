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

        if (config('indonesia.data_location.province')) {
            $this->call(ProvincesSeeder::class);
        }
        if (config('indonesia.data_location.city')) {
            $this->call(CitiesSeeder::class);
        }
        if (config('indonesia.data_location.district')) {
            $this->call(DistrictsSeeder::class);
        }
        if (config('indonesia.data_location.village')) {
            $this->call(VillagesSeeder::class);
        }
    }

    public function reset()
    {
        Schema::disableForeignKeyConstraints();

        if (config('indonesia.data_location.village')) {
            Village::truncate();
        }
        if (config('indonesia.data_location.district')) {
            District::truncate();
        }
        if (config('indonesia.data_location.city')) {
            City::truncate();
        }
        if (config('indonesia.data_location.province')) {
            Province::truncate();
        }

        Schema::disableForeignKeyConstraints();
    }
}
