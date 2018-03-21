let mix = require('laravel-mix');
let path = require('path');

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
 * Custom webpack config
 */
mix.webpackConfig({
    resolve: {
        alias: {
            Travel: path.resolve(__dirname, 'resources/assets/js/spa')
        },
        extensions: ['.js', '.vue']
    }
});

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

/**
 * Production Cache-Busting
 */
if (mix.inProduction()) {
    mix.version()
}
