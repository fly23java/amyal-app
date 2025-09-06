const mix = require('laravel-mix');
const path = require('path');

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

// JavaScript bundling with optimization
mix.js([
    'resources/js/app.js',
], 'public/js/app.js')

// CSS bundling and optimization
.postCss('resources/css/app.css', 'public/css', [
    require('autoprefixer'),
    require('cssnano')({
        preset: 'default',
    }),
])

// Combine all CSS files into one optimized file
.styles([
    'public/app-assets/vendors/css/charts/apexcharts.css',
    'public/app-assets/vendors/css/extensions/toastr.min.css',
    'public/app-assets/css-rtl/bootstrap.css',
    'public/app-assets/css-rtl/bootstrap-extended.css',
    'public/app-assets/css-rtl/colors.css',
    'public/app-assets/css-rtl/components.css',
    'public/app-assets/css-rtl/themes/dark-layout.css',
    'public/app-assets/css-rtl/themes/bordered-layout.css',
    'public/app-assets/css-rtl/core/menu/menu-types/vertical-menu.css',
    'public/app-assets/css-rtl/pages/dashboard-ecommerce.css',
    'public/app-assets/css-rtl/plugins/charts/chart-apex.css',
    'public/app-assets/css-rtl/plugins/extensions/ext-component-toastr.css',
    'public/app-assets/css-rtl/custom-rtl.css',
    'public/assets/css/style-rtl.css',
], 'public/css/all.css')

// Bundle vendor JavaScript libraries
.scripts([
    'public/app-assets/vendors/js/vendors.min.js',
    'public/app-assets/vendors/js/charts/apexcharts.min.js',
    'public/app-assets/vendors/js/extensions/toastr.min.js',
    'public/app-assets/js/core/app-menu.js',
    'public/app-assets/js/core/app.js',
], 'public/js/vendor.js')

// Copy and optimize images (only if directory exists)
// .copyDirectory('resources/images', 'public/images')

// Enable versioning for cache busting in production
.version()

// Source maps for development
.sourceMaps(!mix.inProduction())

// Webpack optimization options
.options({
    processCssUrls: false,
    postCss: [
        require('autoprefixer'),
    ]
})

// Additional webpack configuration
.webpackConfig({
    resolve: {
        alias: {
            '@': path.resolve('resources/js'),
        },
    },
    optimization: {
        splitChunks: {
            chunks: 'all',
            cacheGroups: {
                vendor: {
                    test: /[\\/]node_modules[\\/]/,
                    name: 'vendors',
                    chunks: 'all',
                },
            },
        },
    },
})

// Production optimizations
if (mix.inProduction()) {
    mix.options({
        terser: {
            terserOptions: {
                compress: {
                    drop_console: true,
                },
            },
        },
    });
}