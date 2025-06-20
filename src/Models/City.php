<?php

namespace Hitech\IndonesiaLaravel\Models;

use Hitech\IndonesiaLaravel\Supports\IndonesiaConfig;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class City extends Model
{
    protected IndonesiaConfig $config;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->config = app(IndonesiaConfig::class);

        $this->table = $this->config->tableName('kabupaten');

        $this->searchableColumns = [
            $this->config->code,
            $this->config->name,
            'province.' . $this->config->name,
        ];
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(
            'Hitech\\IndonesiaLaravel\\Models\\Province',
            $this->config->provinceCode,
            $this->config->code
        );
    }

    public function districts(): HasMany
    {
        return $this->hasMany(
            'Hitech\\IndonesiaLaravel\\Models\\District',
            $this->config->cityCode,
            $this->config->code
        );
    }

    public function villages(): HasManyThrough
    {
        return $this->hasManyThrough(
            'Hitech\\IndonesiaLaravel\\Models\\Village',
            'Hitech\\IndonesiaLaravel\\Models\\District',
            $this->config->cityCode,
            $this->config->districtCode,
            $this->config->code,
            $this->config->code
        );
    }

    public function getProvinceNameAttribute()
    {
        return $this->province?->{$this->config->name};
    }

    public function getLogoPathAttribute()
    {
        $folder = 'indonesia-logo/';
        $id = $this->getAttributeValue('id');
        $arr_glob = glob(public_path() . '/' . $folder . $id . '.*');

        if (count($arr_glob) === 1) {
            $logo_name = basename($arr_glob[0]);

            return url($folder . $logo_name);
        }
    }
}
