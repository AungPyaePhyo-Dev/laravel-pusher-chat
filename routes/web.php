<?php

use App\Events\PrivateTest;
use App\Http\Controllers\HomeController;
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
    return view('chat');
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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('chat', [HomeController::class, 'chat'])->name('chat');

Route::get('messages', [HomeController::class, 'messages']);

Route::post('messages', [HomeController::class, 'messageStore'])->name('messageStore');
