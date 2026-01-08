<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\KesehatanController;
use App\Http\Controllers\EdukasiController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Guest routes (login, register)
Route::middleware('guest')->prefix('app')->name('app.')->group(function () {
    Route::get('login', [AppController::class, 'login'])->name('login');
    Route::post('login', [AppController::class, 'loginPost'])->name('login.post');
    Route::get('register', [AppController::class, 'register'])->name('register');
    Route::post('register', [AppController::class, 'registerPost'])->name('register.post');
});

// Authenticated routes
Route::middleware(['auth', \App\Http\Middleware\EnsureIbuHamilActive::class])
    ->prefix('app')
    ->name('app.')
    ->group(function () {

        // Logout
        Route::post('logout', [AppController::class, 'logout'])->name('logout');

        // Beranda (Dashboard)
        Route::get('beranda', [BerandaController::class, 'index'])->name('beranda');

        // Kesehatan (Pemeriksaan + Skrining)
        Route::get('kesehatan', [KesehatanController::class, 'index'])->name('kesehatan');
        Route::get('skrining/create', [KesehatanController::class, 'createSkrining'])->name('skrining.create');
        Route::post('skrining', [KesehatanController::class, 'storeSkrining'])->name('skrining.store');

        // Edukasi (Artikel + Video)
        Route::get('edukasi', [EdukasiController::class, 'index'])->name('edukasi');
        Route::get('artikel/{slug}', [EdukasiController::class, 'showArtikel'])->name('artikel.show');
        Route::get('video/{slug}', [EdukasiController::class, 'showVideo'])->name('video.show');

        // Profil
        Route::get('profil', [ProfilController::class, 'index'])->name('profil');
        Route::put('profil', [ProfilController::class, 'update'])->name('profil.update');
        Route::put('profil/foto', [ProfilController::class, 'updateFoto'])->name('profil.foto');
        Route::post('profil/password', [ProfilController::class, 'changePassword'])->name('profil.password');

        // Pengaturan
        Route::get('pengaturan', [ProfilController::class, 'pengaturan'])->name('pengaturan');
        Route::put('pengaturan', [ProfilController::class, 'updatePengaturan'])->name('pengaturan.update');

        // Notifikasi
        Route::get('notifikasi', [\App\Http\Controllers\NotifikasiController::class, 'index'])->name('notifikasi');
    });

// PWA Manifest & Service Worker
Route::get('manifest.json', function () {
    return response()->json([
        'name' => 'SIKASIH - Sistem Informasi Kesehatan Ibu Hamil',
        'short_name' => 'SIKASIH',
        'description' => 'Aplikasi kesehatan untuk ibu hamil',
        'start_url' => '/app/beranda',
        'display' => 'standalone',
        'background_color' => '#ffffff',
        'theme_color' => '#EC4899',
        'orientation' => 'portrait-primary',
        'icons' => [
            [
                'src' => asset('images/icon-192.png'),
                'sizes' => '192x192',
                'type' => 'image/png',
                'purpose' => 'any maskable'
            ],
            [
                'src' => asset('images/icon-512.png'),
                'sizes' => '512x512',
                'type' => 'image/png',
                'purpose' => 'any maskable'
            ],
        ],
        'screenshots' => [
            [
                'src' => asset('images/screenshot-mobile.png'),
                'sizes' => '540x720',
                'type' => 'image/png',
                'form_factor' => 'narrow'
            ],
        ],
    ]);
});

Route::get('sw.js', function () {
    return response()->file(public_path('sw.js'), [
        'Content-Type' => 'application/javascript',
    ]);
});
