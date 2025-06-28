<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Prefix Tabel
    |--------------------------------------------------------------------------
    |
    | Prefix ini akan ditambahkan ke semua nama tabel yang digunakan oleh
    | package ini. Misalnya: 'indonesia_provinces', 'indonesia_cities', dst.
    |
    */
    'table_prefix' => 'indonesia_',

    /*
    |--------------------------------------------------------------------------
    | Pola Bahasa Data
    |--------------------------------------------------------------------------
    |
    | Tentukan pola bahasa data yang digunakan:
    | - 'ID' untuk data dalam Bahasa Indonesia
    | - 'EN' untuk data dalam Bahasa Inggris
    |
    */
    'pattern' => 'ID',

    /*
    |--------------------------------------------------------------------------
    | Pengaturan Modul Wilayah
    |--------------------------------------------------------------------------
    |
    | Aktifkan atau nonaktifkan bagian-bagian wilayah tertentu sesuai kebutuhan.
    | Jika diset ke false, maka data dari wilayah tersebut tidak akan dimuat.
    |
    */
    'province' => [
        'enabled' => true,
    ],

    'city' => [
        'enabled' => true,
    ],

    'district' => [
        'enabled' => true,
    ],

    'village' => [
        'enabled' => true,
    ],
];
