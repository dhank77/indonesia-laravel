<?php

namespace Hitech\IndonesiaLaravel\Models;

use Hitech\IndonesiaLaravel\Supports\IndonesiaConfig;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Village extends Model
{
    protected IndonesiaConfig $config;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->config = app(IndonesiaConfig::class);

        $this->table = $this->config->tableName('kelurahan');

        $this->searchableColumns = [
            $this->config->code,
            $this->config->name,
            'district.' . $this->config->name,
            'district.city.' . $this->config->name,
            'district.city.province.' . $this->config->name,
        ];
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(
            'Hitech\\IndonesiaLaravel\\Models\\District',
            $this->config->districtCode,
            $this->config->code
        );
    }

    public function getDistrictNameAttribute()
    {
        return $this->district?->{$this->config->name};
    }

    public function getCityNameAttribute()
    {
        return $this->district?->city?->{$this->config->name};
    }

    public function getProvinceNameAttribute()
    {
        return $this->district?->city?->province?->{$this->config->name};
    }
}
