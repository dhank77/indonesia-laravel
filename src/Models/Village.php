<?php

namespace Hitech\IndonesiaLaravel\Models;

class Village extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('indonesia.table_prefix') . (config('indonesia.pattern') === 'ID' ? 'kelurahan' : 'villages');

        $codeColumn = config('indonesia.pattern') === 'ID' ? 'kode' : 'code';
        $nameColumn = config('indonesia.pattern') === 'ID' ? 'nama' : 'name';
        $districtNameColumn = config('indonesia.pattern') === 'ID' ? 'district.nama' : 'district.name';

        $this->searchableColumns = [$codeColumn, $nameColumn, $districtNameColumn];
    }

    public function district()
    {
        $codeColumn = config('indonesia.pattern') === 'ID' ? 'kode' : 'code';
        $districtCodeColumn = config('indonesia.pattern') === 'ID' ? 'kecamatan_kode' : 'district_code';

        return $this->belongsTo('Hitech\\IndonesiaLaravel\\Models\\District', $districtCodeColumn, $codeColumn);
    }

    public function getDistrictNameAttribute()
    {
        $nameColumn = config('indonesia.pattern') === 'ID' ? 'nama' : 'name';

        return $this->district->{$nameColumn};
    }

    public function getCityNameAttribute()
    {
        $nameColumn = config('indonesia.pattern') === 'ID' ? 'nama' : 'name';

        return $this->district->city->{$nameColumn};
    }

    public function getProvinceNameAttribute()
    {
        $nameColumn = config('indonesia.pattern') === 'ID' ? 'nama' : 'name';

        return $this->district->city->province->{$nameColumn};
    }
}
