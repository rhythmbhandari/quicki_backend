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

mix.js('resources/js/pusher.js', 'public/js')
.js('resources/js/sos_events_listener.js', 'public/js')
.js('resources/js/booking_timed_out_listener.js', 'public/js')
.postCss('resources/css/app.css','public/css')



mix.browserSync('127.0.0.1:8000');