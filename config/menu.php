<?php

return [
    'menu_utama' => [
        'title' => 'Menu Utama',
        'icon' => 'fa-home',
        'menus' => [
            ['key' => 'dashboard', 'label' => 'Dashboard', 'route' => 'dashboard'],
            ['key' => 'identifikasi_resiko', 'label' => 'Identifikasi Resiko', 'route' => 'dashboard'],
            ['key' => 'analisis_resiko', 'label' => 'Analisis Resiko', 'route' => 'dashboard'],
            ['key' => 'analisis_kecukupan', 'label' => 'Analisis Kecukupan', 'route' => 'dashboard'],
            ['key' => 'daftar_lengkap', 'label' => 'Daftar Lengkap', 'route' => 'dashboard'],
            ['key' => 'evaluasi_resiko', 'label' => 'Evaluasi Resiko', 'route' => 'dashboard'],
        ]
    ],
    'master_data' => [
        'title' => 'Master Data',
        'icon' => 'fa-database',
        'menus' => [
            ['key' => 'master_resiko', 'label' => 'Master Resiko (Soon)', 'route' => 'dashboard'],
        ]
    ],
    'pengaturan' => [
        'title' => 'Pengaturan',
        'icon' => 'fa-cogs',
        'menus' => [
            ['key' => 'hak_akses', 'label' => 'Hak Akses', 'route' => 'hak-akses.index'],
            ['key' => 'manajemen_user', 'label' => 'Manajemen User', 'route' => 'users.index'],
            ['key' => 'manajemen_role', 'label' => 'Manajemen Role', 'route' => 'roles.index'],
            ['key' => 'manajemen_unit', 'label' => 'Manajemen Unit', 'route' => 'units.index'],
        ]
    ]
];
