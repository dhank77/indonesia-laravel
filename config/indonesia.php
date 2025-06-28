<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Prefix Tabel
    |--------------------------------------------------------------------------
    |
    | Prefix ini akan ditambahkan ke semua nama tabel yang digunakan oleh
    | package ini. Contoh hasil: 'indonesia_provinces', 'indonesia_cities', dst.
    |
    */
    'table_prefix' => 'indonesia_',

    /*
    |--------------------------------------------------------------------------
    | Pola Bahasa Data
    |--------------------------------------------------------------------------
    |
    | Tentukan pola bahasa data yang akan digunakan untuk data wilayah:
    | - 'ID' : Bahasa Indonesia
    | - 'EN' : Bahasa Inggris
    |
    */
    'pattern' => 'ID',

    /*
    |--------------------------------------------------------------------------
    | Pengaturan Data Wilayah
    |--------------------------------------------------------------------------
    |
    | Kamu dapat mengaktifkan/menonaktifkan level data wilayah tertentu
    | seperti provinsi, kota/kabupaten, kecamatan, dan desa/kelurahan.
    |
    | - Jika salah satu wilayah (misal: 'province') diset ke `false`, maka data
    |   pada level tersebut tidak akan dimuat, tetapi data level di bawahnya
    |   tetap bisa dimuat berdasarkan filter di bagian "only".
    |
    | - Bagian "only" memungkinkan kamu memuat hanya data turunan dari wilayah
    |   tertentu saja. Misalnya:
    |
    |   'only' => [
    |       'type' => 'province',
    |       'code' => '76',
    |   ]
    |
    |   Maka hanya data provinsi dengan kode 76 dan semua data kota, kecamatan,
    |   serta desa yang berada di dalamnya yang akan dimuat.
    |
    */
    'data_location' => [
        'province' => true,
        'city' => true,
        'district' => true,
        'village' => true,

        // Filter berdasarkan wilayah induk (opsional)
        'only' => [
            'type' => '', // Bisa: province, city, district, villages
            'code' => '', // Kode wilayah sesuai tipe di atas bisa lihat kodenya disini (https://kodewilayah.id)
        ],
    ],
];
