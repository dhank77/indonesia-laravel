<?php

namespace Hitech\IndonesiaLaravel\Seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
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
        if (config('indonesia.data_location.only.type') === 'province') {
            $results = array_filter($data, function ($item) {
                $code = config('indonesia.pattern') === 'ID' ? 'kode' : 'code';
                return $item[$code] === config('indonesia.data_location.only.code');
            });
            $results = reset($results) + ['created_at' => $now, 'updated_at' => $now];
        } else {
            $results = array_map(function ($arr) use ($now) {
                return $arr + ['created_at' => $now, 'updated_at' => $now];
            }, $data);
        }

        $tableName = config('indonesia.table_prefix') . (config('indonesia.pattern') === 'ID' ? 'provinsi' : 'provinces');
        DB::table($tableName)->insertOrIgnore($results);
    }
}
