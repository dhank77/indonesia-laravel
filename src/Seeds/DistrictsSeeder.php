<?php

namespace Hitech\IndonesiaLaravel\Seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictsSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $csv = new CsvtoArray;
        $file = __DIR__ . '/../../resources/csv/districts.csv';
        if (config('indonesia.pattern') === 'ID') {
            $header = ['kode', 'kode_kabupaten', 'nama'];
        } else {
            $header = ['code', 'city_code', 'name'];
        }
        $data = $csv->csv_to_array($file, $header);

        if (config('indonesia.data_location.only.type') === 'district') {
            $results = array_filter($data, function ($item) {
                $code = config('indonesia.pattern') === 'ID' ? 'kode' : 'code';
                return $item[$code] === config('indonesia.data_location.only.code');
            }); 
            $results = reset($results) + ['created_at' => $now, 'updated_at' => $now];
        } else {
            $results = [];
            foreach ($data as $arr) {
                $cityCode = config('indonesia.pattern') === 'ID' ? 'kode_kabupaten' : 'city_code';
                $code = config('indonesia.pattern') === 'ID' ? 'kode' : 'code';
                $onlyCode = config('indonesia.data_location.only.code');
                $type = config('indonesia.data_location.only.type');

                if ($type === 'province' && substr($arr[$code], 0, 2) !== $onlyCode) continue;
                if ($type === 'city' && substr($arr[$code], 0, 4) !== $onlyCode) continue;

                if (config('indonesia.data_location.city') === false) {
                    unset($arr[$cityCode]);
                }

                $results[] = $arr + ['created_at' => $now, 'updated_at' => $now];
            }
        }

        $collection = collect($results);
        foreach ($collection->chunk(50) as $chunk) {
            $tableName = config('indonesia.table_prefix') . (config('indonesia.pattern') === 'ID' ? 'kecamatan' : 'districts');
            DB::table($tableName)->insertOrIgnore($chunk->toArray());
        }
    }
}
