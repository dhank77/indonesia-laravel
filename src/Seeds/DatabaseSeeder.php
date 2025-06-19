<?php

namespace IndonesiaLaravel\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use IndonesiaLaravel\Models\Kabupaten;
use IndonesiaLaravel\Models\Kecamatan;
use IndonesiaLaravel\Models\Kelurahan;
use IndonesiaLaravel\Models\Provinsi;

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
