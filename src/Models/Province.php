<?php

namespace Hitech\IndonesiaLaravel\Models;

use Hitech\IndonesiaLaravel\Supports\IndonesiaConfig;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Province extends Model
{
    protected IndonesiaConfig $config;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->config = app(IndonesiaConfig::class);

        $this->table = $this->config->tableName('provinsi');

        $codeColumn = $this->config->code;
        $nameColumn = $this->config->name;

        $cityName = 'cities.' . $nameColumn;
        $districtName = 'cities.districts.' . $nameColumn;

        $this->searchableColumns = [
            $codeColumn,
            $nameColumn,
            $cityName,
            $districtName,
        ];
    }

    public function cities(): HasMany
    {
        return $this->hasMany(
            'Hitech\\IndonesiaLaravel\\Models\\City',
            $this->config->provinceCode,
            $this->config->code
        );
    }

    public function districts(): HasManyThrough
    {
        return $this->hasManyThrough(
            'Hitech\\IndonesiaLaravel\\Models\\District',
            'Hitech\\IndonesiaLaravel\\Models\\City',
            $this->config->provinceCode, // FK di City pointing ke Province
            $this->config->cityCode,     // FK di District pointing ke City
            $this->config->code,         // PK Province
            $this->config->code          // PK City
        );
    }

    public function getLogoPathAttribute()
    {
        $folder = 'indonesia-logo/';
        $id = $this->getAttributeValue('id');
        $arr_glob = glob(public_path($folder . $id . '.*'));

        if (count($arr_glob) === 1) {
            return url($folder . basename($arr_glob[0]));
        }
    }
}
