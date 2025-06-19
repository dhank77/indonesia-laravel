<?php

namespace Hitech\IndonesiaLaravel\Services;

use Illuminate\Database\Eloquent\Collection;
use Hitech\IndonesiaLaravel\Models\City;
use Hitech\IndonesiaLaravel\Models\District;
use Hitech\IndonesiaLaravel\Models\Province;
use Hitech\IndonesiaLaravel\Models\Village;

class IndonesiaService
{
    protected $search;
    protected $provinceCodeName;
    protected $cityCodeName;
    protected $districtCodeName;
    protected $villageCodeName;

    public function __construct() {
        $this->provinceCodeName = config('indonesia.pattern') === 'ID' ? 'kode_provinsi' : 'province_code';
        $this->cityCodeName = config('indonesia.pattern') === 'ID' ? 'kode_kabupaten' : 'city_code';
        $this->districtCodeName = config('indonesia.pattern') === 'ID' ? 'kode_kecamatan' : 'district_code';
        $this->villageCodeName = config('indonesia.pattern') === 'ID' ? 'kode_desa' : 'village_code';
    }

    public function search($location)
    {
        $this->search = strtoupper($location);

        return $this;
    }

    public function all()
    {
        $result = collect([]);

        if ($this->search) {
            $provinces = Province::search($this->search)->get();
            $cities = City::search($this->search)->get();
            $districts = District::search($this->search)->get();
            $villages = Village::search($this->search)->get();
            $result->push($provinces);
            $result->push($cities);
            $result->push($districts);
            $result->push($villages);
        }

        return $result->collapse();
    }

    public function allProvinces()
    {
        if ($this->search) {
            return Province::search($this->search)->get();
        }

        return Province::all();
    }

    public function paginateProvinces($numRows = 15)
    {
        if ($this->search) {
            return Province::search($this->search)->paginate();
        }

        return Province::paginate($numRows);
    }

    public function allCities()
    {
        if ($this->search) {
            return City::search($this->search)->get();
        }

        return City::all();
    }

    public function paginateCities($numRows = 15)
    {
        if ($this->search) {
            return City::search($this->search)->paginate();
        }

        return City::paginate($numRows);
    }

    public function allDistricts()
    {
        if ($this->search) {
            return District::search($this->search)->get();
        }

        return District::all();
    }

    public function paginateDistricts($numRows = 15)
    {
        if ($this->search) {
            return District::search($this->search)->paginate();
        }

        return District::paginate($numRows);
    }

    public function allVillages()
    {
        if ($this->search) {
            return Village::search($this->search)->get();
        }

        return Village::all();
    }

    public function paginateVillages($numRows = 15)
    {
        if ($this->search) {
            return Village::search($this->search)->paginate();
        }

        return Village::paginate($numRows);
    }

    public function findProvince($provinceId, $with = null)
    {
        $with = (array) $with;

        if ($with) {
            $withVillages = array_search('villages', $with);

            if ($withVillages !== false) {
                unset($with[$withVillages]);

                $province = Province::with($with)->find($provinceId);

                $province = $this->loadRelation($province, 'cities.districts.villages');
            } else {
                $province = Province::with($with)->find($provinceId);
            }

            return $province;
        }

        return Province::find($provinceId);
    }

    public function findCity($cityId, $with = null)
    {
        $with = (array) $with;

        if ($with) {
            return City::with($with)->find($cityId);
        }

        return City::find($cityId);
    }

    public function findDistrict($districtId, $with = null)
    {
        $with = (array) $with;

        if ($with) {
            $withProvince = array_search('province', $with);

            if ($withProvince !== false) {
                unset($with[$withProvince]);

                $district = District::with($with)->find($districtId);

                $district = $this->loadRelation($district, 'city.province', true);
            } else {
                $district = District::with($with)->find($districtId);
            }

            return $district;
        }

        return District::find($districtId);
    }

    public function findVillage($villageId, $with = null)
    {
        $with = (array) $with;

        if ($with) {
            $withCity = array_search('city', $with);
            $withProvince = array_search('province', $with);

            if ($withCity !== false && $withProvince !== false) {
                unset($with[$withCity]);
                unset($with[$withProvince]);

                $village = Village::with($with)->find($villageId);

                $village = $this->loadRelation($village, 'district.city', true);

                $village = $this->loadRelation($village, 'district.city.province', true);
            } elseif ($withCity !== false) {
                unset($with[$withCity]);

                $village = Village::with($with)->find($villageId);

                $village = $this->loadRelation($village, 'district.city', true);
            } elseif ($withProvince !== false) {
                unset($with[$withProvince]);

                $village = Village::with($with)->find($villageId);

                $village = $this->loadRelation($village, 'district.city.province', true);
            } else {
                $village = Village::with($with)->find($villageId);
            }

            return $village;
        }

        return Village::find($villageId);
    }

    public function findCitiesByProvinceCode(?string $provinceCode = ''): Collection
    {
        return City::where($this->provinceCodeName, $provinceCode)->get();
    }

    public function findDistrictsByCityCode(?string $cityCode = ''): Collection
    {
        return District::where($this->cityCodeName, $cityCode)->get();
    }

    public function findVillagesByDistrictCode(?string $districtCode = ''): Collection
    {
        return Village::where($this->districtCodeName, $districtCode)->get();
    }

    private function loadRelation($object, $relation, $belongsTo = false)
    {
        $exploded = explode('.', $relation);
        $targetRelationName = end($exploded);

        // We need to clone it first because $object->load() below will call related relation.
        // I don't know why
        $newObject = clone $object;

        // https://softonsofa.com/laravel-querying-any-level-far-relations-with-simple-trick/
        // because Eloquent hasManyThrough cannot get through more than one deep relationship
        $object->load([$relation => function ($q) use (&$createdValue, $belongsTo) {
            if ($belongsTo) {
                $createdValue = $q->first();
            } else {
                $createdValue = $q->get()->unique();
            }
        }]);

        $newObject[$targetRelationName] = $createdValue;

        return $newObject;
    }
}
