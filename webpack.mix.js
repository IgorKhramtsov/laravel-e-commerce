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

mix.setPublicPath(path.normalize('www/'));
mix.js('resources/assets/js/app.js', './js/')
    .webpackConfig({
        resolve: {
            alias: {
                '@': path.resolve('resources/assets/sass')
            }
        }
    })
   .sass('resources/assets/sass/app.scss', './css/');
