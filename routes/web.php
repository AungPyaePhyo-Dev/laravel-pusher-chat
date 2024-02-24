<?php

use App\Events\PrivateTest;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
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
    return to_route('login');
});

Route::get('broadcast-private', function(){
    $user = User::find(1);
    PrivateTest::dispatch($user);
    return 'sent';
});

Route::get('broadcast-chat', function(){
    $user = User::find(1);
    PrivateTest::dispatch($user);
    return 'sent';
});

Route::get('/login', function(){
    return view('login');
})->name('login_page');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('home', function(){
    return view('home');
})->name('home');