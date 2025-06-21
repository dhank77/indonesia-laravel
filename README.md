
# 🇮🇩 Indonesia Laravel

<div align="center">

![Indonesia Laravel](https://img.shields.io/badge/Indonesia-Laravel-red?style=for-the-badge&logo=laravel)
![PHP Version](https://img.shields.io/badge/PHP-^8.1-blue?style=for-the-badge&logo=php)
![Laravel](https://img.shields.io/badge/Laravel-^12.19-orange?style=for-the-badge&logo=laravel)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**Package Laravel untuk data wilayah Indonesia terlengkap dan terbaru**

*Data terbaru 2025 sesuai kemendagri No. 300.2.2-2138 Tahun 2025, data daerah-daerah di Indonesia termasuk 38 provinsi, kabupaten/kota, kecamatan, dan desa/kelurahan*

</div>

## ✨ Fitur Utama

- 🗺️ **Data Lengkap**: 38 Provinsi, 514+ Kabupaten/Kota, 7000+ Kecamatan, 83000+ Desa/Kelurahan
- 🌐 **Multi-Language**: Mendukung Bahasa Indonesia (ID) dan English (EN)
- 🔍 **Pencarian Cerdas**: Fitur search yang powerful untuk semua level wilayah
- 📄 **Pagination**: Built-in pagination untuk performa optimal
- 🔗 **Relasi Eloquent**: Relationship yang lengkap antar model
- ⚙️ **Konfigurasi Fleksibel**: Customizable table prefix dan pattern
- 🚀 **Easy Setup**: Instalasi dan setup yang mudah dengan Artisan command

## 📦 Instalasi

### 1. Install via Composer

```bash
composer require hitech/indonesia-laravel
```

### 2. Publish Konfigurasi dan Migration

```bash
# Publish semua file
php artisan vendor:publish --provider="Hitech\IndonesiaLaravel\Providers\IndonesiaLaravelServiceProvider"

# Atau publish secara terpisah
php artisan vendor:publish --tag=config
php artisan vendor:publish --tag=migrations
```

### 3. Jalankan Migration

```bash
php artisan migrate
```

### 4. Seed Database

```bash
php artisan indonesia:seed
```

## ⚙️ Konfigurasi

Setelah publish, edit file `config/indonesia.php`:

```php
return [
    // Prefix untuk nama tabel
    'table_prefix' => 'indonesia_',
    
    // Pattern bahasa: 'ID' untuk Indonesia, 'EN' untuk English
    'pattern' => 'ID',
    
    // Enable/disable setiap modul (cooming soon)
    'province' => ['enabled' => true],
    'city' => ['enabled' => true],
    'district' => ['enabled' => true],
    'village' => ['enabled' => true],
];
```

## 🚀 Penggunaan

### Service Class (Recommended)

```php
use Hitech\IndonesiaLaravel\Services\IndonesiaService;

$indonesia = new IndonesiaService();

// Pencarian di semua wilayah
$results = $indonesia->search('Jakarta')->all();

// Mendapatkan semua provinsi
$provinces = $indonesia->allProvinces();

// Pencarian provinsi dengan pagination
$provinces = $indonesia->search('Jawa')->paginateProvinces(10);

// Mendapatkan provinsi dengan relasi
$province = $indonesia->findProvince(1, ['cities', 'villages']);

// Mendapatkan kota berdasarkan provinsi
$cities = $indonesia->findCitiesByProvince(1);

// Mendapatkan kecamatan berdasarkan kota
$districts = $indonesia->findDistrictsByCity(1);

// Mendapatkan desa berdasarkan kecamatan
$villages = $indonesia->findVillagesByDistrict(1);
```

### Direct Model Usage

```php
use Hitech\IndonesiaLaravel\Models\Province;
use Hitech\IndonesiaLaravel\Models\City;
use Hitech\IndonesiaLaravel\Models\District;
use Hitech\IndonesiaLaravel\Models\Village;

// Mendapatkan semua provinsi
$provinces = Province::all();

// Pencarian provinsi
$provinces = Province::search('Jawa')->get();

// Provinsi dengan kota-kotanya
$province = Province::with('cities')->find(1);

// Kota dengan kecamatan dan desa
$city = City::with('districts.villages')->find(1);
```

### Contoh Relasi

```php
// Mendapatkan provinsi dari sebuah desa
$village = Village::with('district.city.province')->find(1);
$provinceName = $village->district->city->province->name;

// Mendapatkan semua desa dalam sebuah provinsi
$province = Province::with('cities.districts.villages')->find(1);
foreach ($province->cities as $city) {
    foreach ($city->districts as $district) {
        foreach ($district->villages as $village) {
            echo $village->name;
        }
    }
}
```

## 📊 Struktur Database

### Tabel yang Dibuat

- `indonesia_provinces` - Data provinsi
- `indonesia_cities` - Data kabupaten/kota
- `indonesia_districts` - Data kecamatan
- `indonesia_villages` - Data desa/kelurahan

### Kolom Dinamis Berdasarkan Pattern

**Pattern 'ID' (Bahasa Indonesia):**
- `kode_provinsi`, `nama_provinsi`
- `kode_kabupaten`, `nama_kabupaten`
- `kode_kecamatan`, `nama_kecamatan`
- `kode_desa`, `nama_desa`

**Pattern 'EN' (English):**
- `province_code`, `province_name`
- `city_code`, `city_name`
- `district_code`, `district_name`
- `village_code`, `village_name`

## 🔍 Fitur Pencarian

```php
$indonesia = new IndonesiaService();

// Pencarian di semua level wilayah
$results = $indonesia->search('Bandung')->all();

// Pencarian spesifik per level
$provinces = $indonesia->search('Jawa')->allProvinces();
$cities = $indonesia->search('Jakarta')->allCities();
$districts = $indonesia->search('Kemang')->allDistricts();
$villages = $indonesia->search('Cipete')->allVillages();
```

## 📄 Pagination

```php
$indonesia = new IndonesiaService();

// Pagination dengan pencarian
$provinces = $indonesia->search('Jawa')->paginateProvinces(15);
$cities = $indonesia->search('Jakarta')->paginateCities(20);
$districts = $indonesia->paginateDistricts(25);
$villages = $indonesia->paginateVillages(30);
```

## 🛠️ Artisan Commands

```bash
# Seed database dengan data Indonesia
php artisan indonesia:seed

# Publish konfigurasi
php artisan vendor:publish --tag=config

# Publish migrations
php artisan vendor:publish --tag=migrations
```

## ✅ Menjalankan Test

Package ini menggunakan **PHPUnit** bersama **Orchestra Testbench** agar bisa diuji seperti dalam konteks aplikasi Laravel.

### Siapkan konfigurasi test di `phpunit.xml`
```xml
<php>
    <env name="DB_CONNECTION" value="pgsql"/>
    <env name="DB_HOST" value="localhost"/>
    <env name="DB_PORT" value="5432"/>
    <env name="DB_DATABASE" value="indo"/>
    <env name="DB_USERNAME" value="user"/>
    <env name="DB_PASSWORD" value="pw"/>
</php>
```

### Jalankan test dengan PHPUnit

```bash
vendor/bin/phpunit
```
Atau jika sudah install global PHPUnit:
```bash
phpunit
```



## 📋 Requirements

- PHP ^8.1
- Laravel ^12.19
- Illuminate Support ^12.19
- Illuminate Console ^12.19
- Illuminate Database ^12.19

## 🤝 Contributing

Kontribusi sangat diterima! Silakan:

1. Fork repository ini
2. Buat branch fitur baru (`git checkout -b feature/amazing-feature`)
3. Commit perubahan (`git commit -m 'Add amazing feature'`)
4. Push ke branch (`git push origin feature/amazing-feature`)
5. Buat Pull Request

## 📝 License

Project ini menggunakan [MIT License](LICENSE).

## 👨‍💻 Author

**dhank77**
- Email: d41113512@gmail.com
**SyahrulBhudiF**
- Github: https://github.com/SyahrulBhudiF

## 🙏 Acknowledgments

- Data wilayah Indonesia dari berbagai sumber resmi
- Laravel Community
- Semua kontributor yang telah membantu

---

<div align="center">

**Dibuat dengan ❤️ untuk Indonesia**

[⭐ Star this repo](https://github.com/hitech/indonesia-laravel) | [🐛 Report Bug](https://github.com/hitech/indonesia-laravel/issues) | [💡 Request Feature](https://github.com/hitech/indonesia-laravel/issues)

</div>
        
