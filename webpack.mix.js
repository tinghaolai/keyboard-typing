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

mix
    .autoload({
        jquery: ['$', 'window.jQuery', 'jQuery'],
        'ally.js/ally.js': ['ally']
    })
    .js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .options({
        processCssUrls: false
    })
    .sourceMaps()
    .browserSync({
        proxy: '127.0.0.1:8000',
        files: [
            'public/lib/css/app.css',  // Generated .css file
            'public/lib/js/app.js',    // Generated .js file
            // =====================================================================
            // You probably need only one of the below lines, depending
            // on which platform this project is being built upon.
            // =====================================================================
            'resources/views/**/*.php'
            // 'public/**/*.+(html|php)',          // Generic .html and/or .php files [no specific platform]
            // 'laravel/resources/views/**/*.php', // Laravel-specific view files
            // 'craft/templates/**/*.+(html|twig)' // Craft-specific templates, as html and/or twig
        ]
    });
