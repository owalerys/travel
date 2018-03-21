<?php

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
    return view('coming-soon');
});

Route::get('/home', function () {
    return redirect()->route('spa');
});

Route::middleware(['auth'])->group(function () {

    /**
     * Front-end endpoints
     */
    Route::prefix('app')->group(function () {
        // app delivery view
        Route::get('/{catchall?}', 'SpaController@index')->where('catchall', '(.*)')->name('spa');
    });

    /**
     * Profile endpoints
     */
    Route::prefix('profile')->group(function () {
        Route::get('/', 'ProfileController@show');
        Route::get('/permissions', 'ProfileController@permissions');
    });

    /**
     * Content endpoints
     */
    Route::prefix('content')->group(function () {

        // Schema
        Route::prefix('schema')->group(function () {
            Route::get('/', 'ContentController@schema');
        });

        // Airlines
        Route::prefix('airlines')->group(function () {
            Route::get('/', 'ContentController@airlines');
            Route::get('/active', 'ContentController@activeAirlines');
        });

        // Articles
        Route::prefix('articles')->group(function () {
            Route::get('/', 'ContentController@articles');
            Route::get('/live', 'ContentController@liveArticles');
            Route::post('/active', 'ContentController@activeArticles');
        });

        Route::prefix('countries')->group(function () {
            Route::get('/', 'ContentController@countries');
        });

    });

    /**
     * Modification endpoints
     */
    Route::prefix('manage')->group(function () {

        Route::prefix('article')->group(function () {

            /**
             * Article Index Creation/Modification/Retrieval
             */
            Route::post('/', 'ArticleController@create');
            Route::patch('/{article}/status', 'ArticleController@status');
            Route::get('/{article}', 'ArticleController@retrieve');

            /**
             * Article Version Creation/Modification/Retrieval
             */
            Route::post('/{article}/{version}', 'VersionController@fork');
            Route::patch('/{article}/{version}', 'VersionController@update');
            Route::get('/{article}/{version}', 'VersionController@retrieve');
            Route::post('/{article}/{version}/status', 'VersionController@status');

            /**
             * Files
             */
            Route::post('/{article}/{version}/upload', 'UploadController@create');
            Route::delete('/{article}/{version}/upload/{file}', 'UploadController@delete');
            Route::get('/{article}/{version}/upload/{file}', 'UploadController@retrieve');
            Route::patch('/{article}/{version}/upload/{file}', 'UploadController@update');

        });

    });

});

Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout');

Route::get('/home', 'HomeController@index')->name('home');
