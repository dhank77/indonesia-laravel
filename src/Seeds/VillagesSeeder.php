<?php

namespace Hitech\IndonesiaLaravel\Seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VillagesSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $csv = new CsvtoArray;
        $file = __DIR__ . '/../../resources/csv/villages.csv';
        if (config('indonesia.pattern') === 'ID') {
            $header = ['kode', 'kode_kecamatan', 'nama'];
        } else {
            $header = ['code', 'district_code', 'name'];
        }
        $data = $csv->csv_to_array($file, $header);

        if (config('indonesia.data_location.only.type') === 'village') {
            $results = array_filter($data, function ($item) {
                $code = config('indonesia.pattern') === 'ID' ? 'kode' : 'code';
                return $item[$code] === config('indonesia.data_location.only.code');
            });
            $results = reset($results) + ['created_at' => $now, 'updated_at' => $now];
        } else {
            $results = [];
            foreach ($data as $arr) {
                $districtCode = config('indonesia.pattern') === 'ID' ? 'kode_kecamatan' : 'district_code';
                $code = config('indonesia.pattern') === 'ID' ? 'kode' : 'code';
                $onlyCode = config('indonesia.data_location.only.code');
                $type = config('indonesia.data_location.only.type');

                if ($type === 'province' && substr($arr[$code], 0, 2) !== $onlyCode) continue;
                if ($type === 'city' && substr($arr[$code], 0, 4) !== $onlyCode) continue;
                if ($type === 'district' && substr($arr[$code], 0, 6) !== $onlyCode) continue;

                if (config('indonesia.data_location.district') === false) {
                    unset($arr[$districtCode]);
                }

                $results[] = $arr + ['created_at' => $now, 'updated_at' => $now];
            }
        }

        $collection = collect($results);
        foreach ($collection->chunk(50) as $chunk) {
            $tableName = config('indonesia.table_prefix') . (config('indonesia.pattern') === 'ID' ? 'kelurahan' : 'villages');
            DB::table($tableName)->insertOrIgnore($chunk->toArray());
        }
    }
}
