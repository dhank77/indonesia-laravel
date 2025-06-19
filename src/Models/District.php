<?php

namespace Hitech\IndonesiaLaravel\Models;

class District extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('indonesia.table_prefix') . (config('indonesia.pattern') === 'ID' ? 'kecamatan' : 'districts');

        $codeColumn = config('indonesia.pattern') === 'ID' ? 'kode' : 'code';
        $nameColumn = config('indonesia.pattern') === 'ID' ? 'nama' : 'name';
        $cityNameColumn = config('indonesia.pattern') === 'ID' ? 'city.nama' : 'city.name';

        $this->searchableColumns = [$codeColumn, $nameColumn, $cityNameColumn];
    }

    public function city()
    {
        $codeColumn = config('indonesia.pattern') === 'ID' ? 'kode' : 'code';
        $cityCodeColumn = config('indonesia.pattern') === 'ID' ? 'kabupaten_kode' : 'city_code';

        return $this->belongsTo('Hitech\\IndonesiaLaravel\\Models\\City', $cityCodeColumn, $codeColumn);
    }

    public function villages()
    {
        $codeColumn = config('indonesia.pattern') === 'ID' ? 'kode' : 'code';
        $districtCodeColumn = config('indonesia.pattern') === 'ID' ? 'kecamatan_kode' : 'district_code';

        return $this->hasMany('Hitech\\IndonesiaLaravel\\Models\\Village', $districtCodeColumn, $codeColumn);
    }

    public function getCityNameAttribute()
    {
        $nameColumn = config('indonesia.pattern') === 'ID' ? 'nama' : 'name';

        return $this->city->{$nameColumn};
    }

    public function getProvinceNameAttribute()
    {
        $nameColumn = config('indonesia.pattern') === 'ID' ? 'nama' : 'name';

        return $this->city->province->{$nameColumn};
    }
}
