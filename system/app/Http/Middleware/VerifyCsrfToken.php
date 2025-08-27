<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'callback',
        'callback_ipaymu',
        'callback_duitku',
        'callback_tokopay',
        'callback_digiflazz',
        'callback_linkqu',
        'callback_paydisini',
        'callback_apigames',
        'v1/produk',
        'v1/orders',
        'v1/status',
        'v1/cekid'
    ];
}
