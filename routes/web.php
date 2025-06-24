<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\GithubController;
use App\Http\Controllers\Auth\SocialiteCallbackController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Auth\Passwords\Confirm;
use App\Http\Livewire\Auth\Passwords\Email;
use App\Http\Livewire\Auth\Passwords\Reset;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\Auth\Verify;
use App\Http\Livewire\Components\SplashScreen;
use App\Http\Livewire\Components\HomeScreen;
use App\Http\Livewire\Components\InterestScreen;
use App\Http\Livewire\Components\KnowledgeScreen;
use App\Http\Livewire\Components\DevelopersScreen;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::view('/', 'welcome')->name('home');

Route::get('/', SplashScreen::class)->name('app.splash');
Route::get('home', HomeScreen::class)->name('app.home');
Route::get('teste', function() {
    return view('teste');
})->name('app.test');

Route::group(['middleware' => 'auth'], function() {
    Route::get('interesses', InterestScreen::class)->name('app.interest');
    Route::get('conhecimentos', KnowledgeScreen::class)->name('app.knowledge');
    Route::get('desenvolvedores', DevelopersScreen::class)->name('app.developers');
});

Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)
        ->name('login');

    Route::get('register', Register::class)
        ->name('register');
});

Route::get('password/reset', Email::class)
    ->name('password.request');

Route::get('password/reset/{token}', Reset::class)
    ->name('password.reset');

Route::middleware('auth')->group(function () {
    Route::get('email/verify', Verify::class)
        ->middleware('throttle:6,1')
        ->name('verification.notice');

    Route::get('password/confirm', Confirm::class)
        ->name('password.confirm');
});

Route::middleware('auth')->group(function () {
    Route::get('email/verify/{id}/{hash}', EmailVerificationController::class)
        ->middleware('signed')
        ->name('verification.verify');

    Route::post('logout', LogoutController::class)
        ->name('logout');
});

/*Route::get('/auth/github/redirect', function () {
    return Socialite::driver('github')->redirect();
})->name('socialite.redirect-github');

Route::get('/auth/github', GithubController::class);

Route::get('/auth/google/redirect', function () {
    return Socialite::driver('google')->redirect();
})->name('socialite.redirect-google');

Route::get('/auth/google', GoogleController::class);*/

Route::group(['prefix' => 'auth', 'as' => 'socialite.'], function () {
    Route::get('/{driver}/redirect', function (string $driver) {
        return Socialite::driver($driver)->redirect();
    })->name('redirect')->middleware('checkIfAutoLogin');
    Route::get('/{driver}', SocialiteCallbackController::class)->name('callback');
});