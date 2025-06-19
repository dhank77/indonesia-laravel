<?php

namespace Hitech\IndonesiaLaravel\Seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
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
        $data = array_map(function ($arr) use ($now) {
            return $arr + ['created_at' => $now, 'updated_at' => $now];
        }, $data);

        $collection = collect($data);
        foreach ($collection->chunk(50) as $chunk) {
            $tableName = config('indonesia.table_prefix') . (config('indonesia.pattern') === 'ID' ? 'kelurahan' : 'villages');
            DB::table($tableName)->insertOrIgnore($chunk->toArray());
        }
    }
}
