<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'airlines' => 'App\Airline'
        ]);

        // Register array size validation rule and replacer
        Validator::extend('array_size', \App\Content\Validator::class . '@ruleArraySize');
        Validator::replacer('array_size', \App\Content\Validator::class . '@replacerArraySize');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
