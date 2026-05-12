<?php

return [
    'menu_utama' => [
        'title' => 'Menu Utama',
        'icon' => 'fa-home',
        'menus' => [
            ['key' => 'dashboard', 'label' => 'Dashboard', 'route' => 'dashboard'],
            ['key' => 'identifikasi_risiko', 'label' => 'Identifikasi Risiko', 'route' => 'identifikasi-risiko.index'],
            ['key' => 'analisis_risiko', 'label' => 'Analisis Risiko', 'route' => 'analisis-risiko.index'],
            ['key' => 'analisis_kecukupan', 'label' => 'Analisis Kecukupan', 'route' => 'analisis-kecukupan.index'],
            ['key' => 'daftar_risiko', 'label' => 'Daftar Lengkap', 'route' => 'daftar-risiko.index'],
            ['key' => 'evaluasi_risiko', 'label' => 'Evaluasi Risiko', 'route' => 'evaluasi-risiko.index'],
        ]
    ],
    'master_data' => [
        'title' => 'Master Data',
        'icon' => 'fa-database',
        'menus' => [
            ['key' => 'kategori_risiko', 'label' => 'Kategori Risiko', 'route' => 'kategori-risiko.index'],
            ['key' => 'ruang_lingkup', 'label' => 'Ruang Lingkup', 'route' => 'ruang-lingkup.index'],
            ['key' => 'probabilitas', 'label' => 'Skala Probabilitas', 'route' => 'probabilitas.index'],
            ['key' => 'dampak', 'label' => 'Skala Dampak', 'route' => 'dampak.index'],
        ]
    ],
    'pengaturan' => [
        'title' => 'Pengaturan',
        'icon' => 'fa-cogs',
        'menus' => [
            ['key' => 'hak_akses', 'label' => 'Hak Akses', 'route' => 'hak-akses.index'],
            ['key' => 'users', 'label' => 'Manajemen User', 'route' => 'users.index'],
            ['key' => 'roles', 'label' => 'Manajemen Role', 'route' => 'roles.index'],
            ['key' => 'units', 'label' => 'Manajemen Unit', 'route' => 'units.index'],
            ['key' => 'periode', 'label' => 'Manajemen Periode', 'route' => 'periode.index'],
        ]
    ]
];
