<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     *
     * @var array
     */
    public $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'admin_auth' => \App\Filters\AdminFilter::class,
        'bendahara_auth' => \App\Filters\BendaharaFilter::class,
        'ketua_auth' => \App\Filters\KetuaFilter::class,
        'anggota_auth' => \App\Filters\AnggotaFilter::class,
        'login_auth' => \App\Filters\LoginFilter::class,
        'maintenance' => \App\Filters\MaintenanceFilter::class
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array
     */
    public $globals = [
        'before' => [
            // 'honeypot',
            // 'csrf',
            // 'invalidchars',
            'maintenance',
        ],
        'after' => [
            'toolbar',
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'post' => ['foo', 'bar']
     *
     * If you use this, you should disable auto-routing because auto-routing
     * permits any HTTP method to access a controller. Accessing the controller
     * with a method you don’t expect could bypass the filter.
     *
     * @var array
     */
    public $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * Example:
     * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     *
     * @var array
     */
    public $filters = [
        'login_auth' => [
            'before' => [
                '/',
                'registrasi',
                'reg_proc',
                'auth'
            ]
        ],
        'admin_auth' => [
            'before' => ['admin/*']
        ],
        'bendahara_auth' => [
            'before' => ['bendahara/*']
        ],
        'ketua_auth' => [
            'before' => ['ketua/*']
        ],
        'anggota_auth' => [
            'before' => ['anggota/*']
        ]
    ];
}
