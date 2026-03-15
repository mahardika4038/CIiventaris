<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;

class Filters extends BaseFilters
{
    public array $aliases = [
        'csrf'     => CSRF::class,
        'toolbar'  => DebugToolbar::class,
        'honeypot' => Honeypot::class,
        'auth'     => \App\Filters\AuthFilter::class,
        'role'     => \App\Filters\RoleFilter::class,
    ];

    public array $required = [
        'before' => [],
        'after'  => ['toolbar'],
    ];

    public array $globals = [
        'before' => [],
        'after'  => [],
    ];

    // Filter ini tidak perlu karena sudah di-handle di Routes.php
    // dengan $routes->group('', ['filter' => 'auth'], ...)
    public array $filters = [];
}
