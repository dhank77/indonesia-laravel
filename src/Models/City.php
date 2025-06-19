<?php

namespace Hitech\IndonesiaLaravel\Models;

class City extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('indonesia.table_prefix') . (config('indonesia.pattern') === 'ID' ? 'kabupaten' : 'cities');

        $codeColumn = config('indonesia.pattern') === 'ID' ? 'kode' : 'code';
        $nameColumn = config('indonesia.pattern') === 'ID' ? 'nama' : 'name';
        $provinceNameColumn = config('indonesia.pattern') === 'ID' ? 'province.nama' : 'province.name';

        $this->searchableColumns = [$codeColumn, $nameColumn, $provinceNameColumn];
    }

    public function province()
    {
        $codeColumn = config('indonesia.pattern') === 'ID' ? 'kode' : 'code';
        $provinceCodeColumn = config('indonesia.pattern') === 'ID' ? 'provinsi_kode' : 'province_code';

        return $this->belongsTo('Hitech\\IndonesiaLaravel\\Models\\Province', $provinceCodeColumn, $codeColumn);
    }

    public function districts()
    {
        $codeColumn = config('indonesia.pattern') === 'ID' ? 'kode' : 'code';
        $cityCodeColumn = config('indonesia.pattern') === 'ID' ? 'kabupaten_kode' : 'city_code';

        return $this->hasMany('Hitech\\IndonesiaLaravel\\Models\\District', $cityCodeColumn, $codeColumn);
    }

    public function villages()
    {
        $codeColumn = config('indonesia.pattern') === 'ID' ? 'kode' : 'code';
        $cityCodeColumn = config('indonesia.pattern') === 'ID' ? 'kabupaten_kode' : 'city_code';
        $districtCodeColumn = config('indonesia.pattern') === 'ID' ? 'kecamatan_kode' : 'district_code';

        return $this->hasManyThrough(
            'Hitech\\IndonesiaLaravel\\Models\\Village',
            'Hitech\\IndonesiaLaravel\\Models\\District',
            $cityCodeColumn,
            $districtCodeColumn,
            $codeColumn,
            $codeColumn
        );
    }

    public function getProvinceNameAttribute()
    {
        $nameColumn = config('indonesia.pattern') === 'ID' ? 'nama' : 'name';

        return $this->province->{$nameColumn};
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
