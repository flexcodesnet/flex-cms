const mix = require('laravel-mix');
mix.disableNotifications();

let glob = require('glob');

mix.options({
    processCssUrls: false,
    clearConsole: true,
    terser: {
        extractComments: false,
    }
});
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
glob.sync('./platform/**/**/webpack.mix.js').forEach(item => require(item));

mix.js("resources/js/app.js", "public/assets/adminlte/custom/script/vue.js").vue();
