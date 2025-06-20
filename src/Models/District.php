<?php

namespace Hitech\IndonesiaLaravel\Models;

use Hitech\IndonesiaLaravel\Supports\IndonesiaConfig;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    protected IndonesiaConfig $config;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->config = app(IndonesiaConfig::class);

        $this->table = $this->config->tableName('kecamatan');

        $this->searchableColumns = [
            $this->config->code,
            $this->config->name,
            'city.' . $this->config->name,
            'city.province.' . $this->config->name,
        ];
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(
            'Hitech\\IndonesiaLaravel\\Models\\City',
            $this->config->cityCode,
            $this->config->code
        );
    }

    public function villages(): HasMany
    {
        return $this->hasMany(
            'Hitech\\IndonesiaLaravel\\Models\\Village',
            $this->config->districtCode,
            $this->config->code
        );
    }

    public function getCityNameAttribute()
    {
        return $this->city?->{$this->config->name};
    }

    public function getProvinceNameAttribute()
    {
        return $this->city?->province?->{$this->config->name};
    }
}
