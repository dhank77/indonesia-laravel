<?php

namespace Hitech\IndonesiaLaravel\Seeds;

use Hitech\IndonesiaLaravel\Models\City;
use Hitech\IndonesiaLaravel\Models\District;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
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

        $this->call(ProvincesSeeder::class);
        $this->call(CitiesSeeder::class);
        $this->call(DistrictsSeeder::class);
        $this->call(VillagesSeeder::class);
    }

    public function reset()
    {
        Schema::disableForeignKeyConstraints();

        Village::truncate();
        District::truncate();
        City::truncate();
        Province::truncate();

        Schema::disableForeignKeyConstraints();
    }
}
