<?php

namespace Hitech\IndonesiaLaravel\Supports;

readonly class IndonesiaConfig
{
    public function __construct(
        public mixed  $pattern,
        public mixed  $tablePrefix,
        public string $provinceCode,
        public string $cityCode,
        public string $districtCode,
        public string $villageCode,
        public string $code,
        public string $name
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