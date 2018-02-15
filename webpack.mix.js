let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

/**
 * Single Page Application
 */
mix.js('resources/assets/js/spa/spa.js', 'public/js/spa')
    .sass('resources/assets/sass/spa/spa.scss', 'public/css/spa')

/**
 * Non-SPA
 */
mix.js('resources/assets/js/front/app.js', 'public/js/front')
    .sass('resources/assets/sass/front/app.scss', 'public/css/front')
