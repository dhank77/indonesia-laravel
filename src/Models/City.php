<?php

namespace IndonesiaLaravel\Models;

use Illuminate\Support\Facades\App;

class City extends Model
{
    protected $table = 'cities';

    protected $searchableColumns = ['code', 'name', 'province.name'];

    public function province()
    {
        return $this->belongsTo('IndonesiaLaravel\Models\Province', 'province_code', 'code');
    }

    public function districts()
    {
        return $this->hasMany('IndonesiaLaravel\Models\District', 'city_code', 'code');
    }

    public function villages()
    {
        return $this->hasManyThrough(
            'IndonesiaLaravel\Models\Village',
            'IndonesiaLaravel\Models\District',
            'city_code',
            'district_code',
            'code',
            'code'
        );
    }

    public function getProvinceNameAttribute()
    {
        return $this->province->name;
    }

    public function getLogoPathAttribute()
    {
        $folder = 'indonesia-logo/';
        $id = $this->getAttributeValue('id');
        $arr_glob = glob(App::publicPath().'/'.$folder.$id.'.*');

        if (count($arr_glob) == 1) {
            $logo_name = basename($arr_glob[0]);

            return url($folder.$logo_name);
        }
    }
}
