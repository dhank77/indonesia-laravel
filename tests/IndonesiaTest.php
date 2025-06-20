<?php

namespace Tests;

use Hitech\IndonesiaLaravel\Models\City;
use Hitech\IndonesiaLaravel\Models\District;
use Hitech\IndonesiaLaravel\Models\Province;
use Hitech\IndonesiaLaravel\Models\Village;
use Hitech\IndonesiaLaravel\Services\IndonesiaService;
use Hitech\IndonesiaLaravel\Supports\IndonesiaConfig;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class IndonesiaTest extends TestCase
{
    private IndonesiaService $service;
    private IndonesiaConfig $config;

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = app(IndonesiaConfig::class);
        $this->service = app(IndonesiaService::class);
    }

    #[Test]
    public function it_can_search_province_by_name()
    {
        $result = Province::search('Jawa')->get();
        $this->assertNotEmpty($result);
    }

    #[Test]
    public function it_can_search_city_by_name()
    {
        $result = City::search('Bandung')->get();
        $this->assertNotEmpty($result);
    }

    #[Test]
    public function it_can_search_district_by_name()
    {
        $result = District::search('Candisari')->get();
        $this->assertNotEmpty($result);
    }

    #[Test]
    public function it_can_search_village_by_name()
    {
        $result = Village::search('Cipaganti')->get();
        $this->assertNotEmpty($result);
    }

    #[Test]
    public function it_can_fetch_all_provinces()
    {
        $result = Province::all();
        $this->assertNotEmpty($result);
    }

    #[Test]
    public function it_can_fetch_all_cities()
    {
        $result = City::all();
        $this->assertNotEmpty($result);
    }

    #[Test]
    public function it_can_fetch_all_districts()
    {
        $result = District::all();
        $this->assertNotEmpty($result);
    }

    #[Test]
    public function it_can_fetch_all_villages()
    {
        $result = Village::all();
        $this->assertNotEmpty($result);
    }

    #[Test]
    public function it_can_paginate_provinces()
    {
        $service = new IndonesiaService(app(IndonesiaConfig::class));
        $result = $service->paginateProvinces(10);
        $this->assertNotEmpty($result);
    }

    #[Test]
    public function it_can_paginate_cities()
    {
        $service = new IndonesiaService(app(IndonesiaConfig::class));
        $result = $service->paginateCities(10);
        $this->assertNotEmpty($result);
    }

    #[Test]
    public function it_can_paginate_districts()
    {
        $service = new IndonesiaService(app(IndonesiaConfig::class));
        $result = $service->paginateDistricts(10);
        $this->assertNotEmpty($result);
    }

    #[Test]
    public function it_can_paginate_villages()
    {
        $service = new IndonesiaService(app(IndonesiaConfig::class));
        $result = $service->paginateVillages(10);
        $this->assertNotEmpty($result);
    }

    #[Test]
    public function it_can_find_province_with_cities()
    {
        $service = new IndonesiaService(app(IndonesiaConfig::class));
        $province = Province::first();
        $result = $service->findProvince($province->getKey(), ['cities']);
        $this->assertNotNull($result);
        $this->assertNotEmpty($result->cities);
    }

    #[Test]
    public function it_can_find_city_with_districts()
    {
        $service = new IndonesiaService(app(IndonesiaConfig::class));
        $city = City::first();
        $result = $service->findCity($city->getKey(), ['districts']);
        $this->assertNotNull($result);
        $this->assertNotEmpty($result->districts);
    }

    #[Test]
    public function it_can_find_district_with_villages()
    {
        $service = new IndonesiaService(app(IndonesiaConfig::class));
        $district = District::first();
        $result = $service->findDistrict($district->getKey(), ['villages']);
        $this->assertNotNull($result);
        $this->assertNotEmpty($result->villages);
    }

    #[Test]
    public function it_can_find_village_with_district_city_province()
    {
        $service = new IndonesiaService(app(IndonesiaConfig::class));
        $village = Village::first();
        $result = $service->findVillage($village->getKey(), ['district', 'city', 'province']);
        $this->assertNotNull($result);
        $this->assertNotNull($result->district);
        $this->assertNotNull($result->district->city);
        $this->assertNotNull($result->district->city->province);
    }

    #[Test]
    public function it_can_find_cities_by_province_code()
    {
        $province = Province::first();
        $result = $this->service->findCitiesByProvinceCode($province->{$this->config->code});
        $this->assertNotEmpty($result);
    }

    #[Test]
    public function it_can_find_districts_by_city_code()
    {
        $city = City::first();
        $result = $this->service->findDistrictsByCityCode($city->{$this->config->code});
        $this->assertNotEmpty($result);
    }

    #[Test]
    public function it_can_find_villages_by_district_code()
    {
        $district = District::first();
        $result = $this->service->findVillagesByDistrictCode($district->{$this->config->code});
        $this->assertNotEmpty($result);
    }
}
