<?php

namespace Hitech\IndonesiaLaravel\Models;

class Province extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('indonesia.table_prefix') . (config('indonesia.pattern') === 'ID' ? 'provinsi' : 'provinces');

        $codeColumn = config('indonesia.pattern') === 'ID' ? 'kode' : 'code';
        $nameColumn = config('indonesia.pattern') === 'ID' ? 'nama' : 'name';

        $this->searchableColumns = [$codeColumn, $nameColumn];
    }

    public function cities()
    {
        return $this->hasMany('Hitech\\IndonesiaLaravel\\Models\\City', 'province_code', 'code');
    }

    public function districts()
    {
        $codeColumn = config('indonesia.pattern') === 'ID' ? 'kode' : 'code';
        $provinceCodeColumn = config('indonesia.pattern') === 'ID' ? 'provinsi_kode' : 'province_code';
        $cityCodeColumn = config('indonesia.pattern') === 'ID' ? 'kabupaten_kode' : 'city_code';

        return $this->hasManyThrough(
            'Hitech\\IndonesiaLaravel\\Models\\District',
            'Hitech\\IndonesiaLaravel\\Models\\City',
            $provinceCodeColumn,
            $cityCodeColumn,
            $codeColumn,
            $codeColumn
        );
    }

    public function getLogoPathAttribute()
    {
        $folder = 'indonesia-logo/';
        $id = $this->getAttributeValue('id');
        $arr_glob = glob(public_path() . '/' . $folder . $id . '.*');

        if (count($arr_glob) == 1) {
            $logo_name = basename($arr_glob[0]);

            return url($folder . $logo_name);
        }
    }
}
