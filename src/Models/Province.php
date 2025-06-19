<?php

namespace IndonesiaLaravel\Models;

use Illuminate\Support\Facades\App;

class Province extends Model
{
    protected $table = 'provinces';

    public function cities()
    {
        return $this->hasMany('IndonesiaLaravel\Models\City', 'province_code', 'code');
    }

    public function districts()
    {
        return $this->hasManyThrough(
            'IndonesiaLaravel\Models\District',
            'IndonesiaLaravel\Models\City',
            'province_code',
            'city_code',
            'code',
            'code'
        );
    }

    public function getLogoPathAttribute()
    {
        $folder = 'indonesia-logo/';
        $id = $this->getAttributeValue('id');
        $arr_glob = glob(App::publicPath().'/'.$folder.$id.'.*');

        if (count($arr_glob) == 1) {
            $logo_name = basename($arr_glob[0]);

            return App::url($folder.$logo_name);
        }
    }
}
