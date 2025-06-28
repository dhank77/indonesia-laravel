<?php

namespace Hitech\IndonesiaLaravel\Seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $Csv = new CsvtoArray;
        $file = __DIR__ . '/../../resources/csv/cities.csv';
        if (config('indonesia.pattern') === 'ID') {
            $header = ['kode', 'kode_provinsi', 'nama'];
        } else {
            $header = ['code', 'province_code', 'name'];
        }
        $data = $Csv->csv_to_array($file, $header);

        if (config('indonesia.data_location.only.type') === 'city') {
            $results = array_filter($data, function ($item) {
                $code = config('indonesia.pattern') === 'ID' ? 'kode' : 'code';
                return $item[$code] === config('indonesia.data_location.only.code');
            }); 
            $results = reset($results) + ['created_at' => $now, 'updated_at' => $now];
        } else {
            $results = [];
            foreach ($data as $arr) {
                $provinceCode = config('indonesia.pattern') === 'ID' ? 'kode_provinsi' : 'province_code';
                $code = config('indonesia.pattern') === 'ID' ? 'kode' : 'code';
                $onlyCode = config('indonesia.data_location.only.code');
                $type = config('indonesia.data_location.only.type');

                if ($type === 'province' && substr($arr[$code], 0, 2) !== $onlyCode) continue;

                if (config('indonesia.data_location.province') === false) {
                    unset($arr[$provinceCode]);
                }

                $results[] = $arr + ['created_at' => $now, 'updated_at' => $now];
            }
        }

        $collection = collect($results);
        foreach ($collection->chunk(50) as $chunk) {
            $tableName = config('indonesia.table_prefix') . (config('indonesia.pattern') === 'ID' ? 'kabupaten' : 'cities');
            DB::table($tableName)->insertOrIgnore($chunk->toArray());
        }
    }
}
