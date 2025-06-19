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
        $data = array_map(function ($arr) use ($now) {
            return $arr + ['created_at' => $now, 'updated_at' => $now];
        }, $data);

        $collection = collect($data);
        foreach ($collection->chunk(50) as $chunk) {
            $tableName = config('indonesia.table_prefix') . (config('indonesia.pattern') === 'ID' ? 'kabupaten' : 'cities');
            DB::table($tableName)->insertOrIgnore($chunk->toArray());
        }
    }
}
