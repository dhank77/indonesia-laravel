<?php

namespace Hitech\IndonesiaLaravel\Seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class ProvincesSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $csv = new CsvtoArray;
        $file = __DIR__ . '/../../resources/csv/provinces.csv';
        if (config('indonesia.pattern') === 'ID') {
            $header = ['kode', 'nama'];
        } else {
            $header = ['code', 'name'];
        }
        $data = $csv->csv_to_array($file, $header);
        $data = array_map(function ($arr) use ($now) {
            return $arr + ['created_at' => $now, 'updated_at' => $now];
        }, $data);

        $tableName = config('indonesia.table_prefix') . (config('indonesia.pattern') === 'ID' ? 'provinsi' : 'provinces');
        DB::table($tableName)->insertOrIgnore($data);
    }
}
