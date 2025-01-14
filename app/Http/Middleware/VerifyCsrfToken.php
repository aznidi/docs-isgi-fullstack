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
        'sanctum/csrf-cookie',
        'login', // Ajoutez cette ligne pour déboguer, mais retirez-la après
        'register', // Ajoutez cette ligne pour déboguer, mais retirez-la après
    ];

}
