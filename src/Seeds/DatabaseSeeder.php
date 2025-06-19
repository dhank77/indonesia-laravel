<?php

namespace Hitech\IndonesiaLaravel\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Hitech\IndonesiaLaravel\Models\Kabupaten;
use Hitech\IndonesiaLaravel\Models\Kecamatan;
use Hitech\IndonesiaLaravel\Models\Kelurahan;
use Hitech\IndonesiaLaravel\Models\Provinsi;

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

        Kelurahan::truncate();
        Kecamatan::truncate();
        Kabupaten::truncate();
        Provinsi::truncate();

        Schema::disableForeignKeyConstraints();
    }
}
