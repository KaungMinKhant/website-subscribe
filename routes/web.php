<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\WebUserController;

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

Route::get('/', function () {
    return view('welcome');
});
Route::post('/website', [WebsiteController::class, 'store']);
Route::post('/website/{id}/post', [PostController::class, 'store']);
Route::post('/user', [WebUserController::class, 'store']);
Route::post('/user/{user_id}/subscribe/website/{website_id}',
            [WebUserController::class, 'subscribe']);
Route::post('/emails/send',
            [WebUserController::class, 'send_emails']);
