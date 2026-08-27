<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // Daftarkan alias middleware 'role' agar bisa dipakai di routes
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // Railway (dan PaaS sejenis) menempatkan aplikasi di belakang reverse
        // proxy yang men-terminate HTTPS lalu meneruskan request ke container
        // secara HTTP biasa. Tanpa mempercayai proxy ini, Laravel tidak tahu
        // request asli memakai HTTPS (Request::isSecure() bernilai false),
        // sehingga form/asset/URL bisa ter-generate sebagai http:// meski
        // browser sudah di https:// -- ini penyebab peringatan "not secure"
        // saat submit form login. IP proxy Railway dinamis dan tidak
        // didokumentasikan tetap, jadi kita percayai seluruh proxy ('*'),
        // pola standar untuk PaaS yang aplikasinya memang hanya bisa diakses
        // lewat proxy platform (bukan diekspos langsung ke publik).
        $middleware->trustProxies(at: '*');

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();