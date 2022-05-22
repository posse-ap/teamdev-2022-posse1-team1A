<?php

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';

Route::get('/', 'App\Http\Controllers\UserController@index')->name('user_index');
Route::post('/', 'App\Http\Controllers\UserController@search')->name('user_search');

Route::get('/search/{keyword?}', 'App\Http\Controllers\UserController@result')->name('user_result');
Route::get('/user/edit', 'App\Http\Controllers\UserController@userEdit')->name('user_edit');
Route::get('/user', 'App\Http\Controllers\UserController@userPage')->name('user_page');
Route::get('/beginner', 'App\Http\Controllers\UserController@beginner')->name('user_beginner');

Route::get('/search/{keyword?}', 'App\Http\Controllers\UserController@result')->name('user_result');

Route::get('/ticket', 'App\Http\Controllers\UserController@ticket')->name('user_ticket');
Route::get('/withdrawal', 'App\Http\Controllers\UserController@withdrawal')->name('user_withdrawal');
Route::post('/withdrawal', 'App\Http\Controllers\UserController@withdrawalPost')->name('user_withdrawal_post');

Route::get('/ticket', 'App\Http\Controllers\TicketController@index')->name('user_ticket');
Route::post('/ticket', 'App\Http\Controllers\TicketController@buy')->name('buy_ticket');

Route::get('/thanks', 'App\Http\Controllers\TicketController@thanks')->name('user_thanks');

Route::get('/terms-of-service', function () {
    return view('user.terms-of-service');
})->name('terms_of_service');

// chat一覧画面
Route::group(['prefix' => 'chat', 'as' => 'chat.'], function () {
    Route::get('/respondent', 'App\Http\Controllers\ChatController@respondent_chat_list')->name('respondent_chat_list');
    Route::post('/respondent/stop', 'App\Http\Controllers\ChatController@reception_stop')->name('reception_stop');
    Route::post('/respondent/start', 'App\Http\Controllers\ChatController@reception_start')->name('reception_start');
    Route::get('client', 'App\Http\Controllers\ChatController@client_chat_list')->name('client_chat_list');


    Route::group(['prefix' => '/{chat_id}'], function () {
        Route::get('/', 'App\Http\Controllers\ChatController@index')->name('index');
        Route::post('/', 'App\Http\Controllers\ChatController@post')->name('post');

        Route::post('/review', 'App\Http\Controllers\ChatController@post_review')->name('post_review');
        Route::post('/call-start', 'App\Http\Controllers\ChatController@call_start')->name('call_start');
    });
    Route::group(['prefix' => '/call'], function () {
        Route::get('/{calling_id}', 'App\Http\Controllers\ChatController@client_call')->name('call');
    });
});

Route::get('/privacy-policy', function () {
    return view('user.privacy-policy');
})->name('privacy_policy');


// 管理者画面
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/userlist', 'App\Http\Controllers\AdminController@userlist')->name('userlist');
    Route::post('/userlist/stop', 'App\Http\Controllers\AdminController@accountStop')->name('userlist_accountStop');
    Route::post('/userlist/active', 'App\Http\Controllers\AdminController@accountActive')->name('userlist_accountActive');
    Route::post('/userlist', 'App\Http\Controllers\AdminController@search')->name('userlist_search');

    Route::get('/index', 'App\Http\Controllers\AdminController@index')->name('index');

    Route::get('/call-evaluation', 'App\Http\Controllers\AdminController@callEvaluation')->name('call_evaluation');
});
