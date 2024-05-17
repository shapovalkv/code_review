const process = require('process')
const mix = require('laravel-mix')
// const cssImport = require('postcss-import')
// const cssNesting = require('postcss-nesting')
const webpackConfig = require('./webpack.config')

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
    .js('resources/js/inertia-app.js', 'public/js')
    .copyDirectory('resources/assets/images', 'public/images')
    .vue({
        runtimeOnly: (process.env.NODE_ENV || 'production') === 'production',
        extractStyles: true,
        globalStyles: 'resources/assets/scss/_variables.scss',
    })
    .webpackConfig(webpackConfig)
    .sass('resources/assets/scss/app.scss', 'public/css/inertia-app.css')
    .sourceMaps();

if (mix.inProduction()) {
    mix.version();
}


