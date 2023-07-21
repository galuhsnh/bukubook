<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Models\User;
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

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//TODO: Tambahkan controller User
Route::middleware(['auth'])->group(function () {
    route::controller(UserController::class)
        //prefix url
        ->prefix('user')
        //prefix name
        ->name('user.')
        //middleware buat kalo yg akses harus masuk login jadi user dulu
        //->middleware(['auth'])
        ->group(function () {
            //user harusnya dalem slash itu
            //user. harusnya dalem name('user.index')
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create')->can('create-user');
            Route::post('/', 'store')->name('store')->can('create-user');
            Route::get('/{user}/edit', 'edit')->name('edit');
            Route::put('/{user}', 'Update')->name('update');
            Route::delete('/{user}', 'destroy')->name('delete');
        });

    Route::resource('category', CategoryController::class);
    Route::controller(BookController::class)
            ->prefix('book')
            ->name('book.')
            ->group(function (){
                Route::get('datatable', 'getDatatable')->name('datatable');
                Route::get('summary', 'summary')->name('summary');
            });
    Route::resource('book', BookController::class);
});
