const mix = require('laravel-mix');

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

 mix.browserSync({
     proxy: 'social',
     ui: {
        port: 8080,
         weinre: {
         port: 9090
     }
 }
 });


let productionSourceMaps = false;

mix.js('resources/js/app.js', 'public/js')
    .sourceMaps(productionSourceMaps, 'source-map').version()
    .sass('resources/sass/critical.scss', 'public/css')
    .sass('resources/sass/app.scss', 'public/css');
