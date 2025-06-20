<?php

namespace Hitech\IndonesiaLaravel\Supports;

class IndonesiaConfig
{
    public function __construct(
        public readonly mixed  $pattern,
        public readonly mixed  $tablePrefix,
        public readonly string $provinceCode,
        public readonly string $cityCode,
        public readonly string $districtCode,
        public readonly string $villageCode,
        public readonly string $code,
        public readonly string $name
    )
    {
    }

    public function tableName(string $base): string
    {
        if ($this->pattern === 'ID') {
            return $this->tablePrefix . "_" . $base;
        }

        return $this->tablePrefix . match ($base) {
                'provinsi' => 'provinces',
                'kabupaten' => 'cities',
                'kecamatan' => 'districts',
                'kelurahan' => 'villages',
                default => $base
            };
    }
}