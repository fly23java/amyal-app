const mix = require('laravel-mix');

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

 mix.js([
    'resources/js/app.js',
   
    ]
, 'public/js/app.js')
    .postCss('resources/css/app.css', 'public/css')
  
    .postCss('public/app-assets/vendors/css/charts/apexcharts.css', 'public/css/app.css')
    .postCss('public/app-assets/vendors/css/extensions/toastr.min.css', 'public/css/app.css')
    .postCss('public/app-assets/css-rtl/bootstrap.css', 'public/css/app.css')
    .postCss('public/app-assets/css-rtl/bootstrap-extended.css', 'public/css/app.css')
    .postCss('public/app-assets/css-rtl/colors.css', 'public/css/app.css')
    .postCss('public/app-assets/css-rtl/components.css', 'public/css/app.css')
    .postCss('public/app-assets/css-rtl/themes/dark-layout.css', 'public/css/app.css')
    .postCss('public/app-assets/css-rtl/themes/bordered-layout.css', 'public/css/app.css')
    .postCss('public/app-assets/css-rtl/core/menu/menu-types/vertical-menu.css', 'public/css/app.css')
    .postCss('public/app-assets/css-rtl/pages/dashboard-ecommerce.css', 'public/css/app.css')
    .postCss('public/app-assets/css-rtl/plugins/charts/chart-apex.css', 'public/css/app.css')
    .postCss('public/app-assets/css-rtl/plugins/extensions/ext-component-toastr.css', 'public/css/app.css')
    .postCss('public/app-assets/css-rtl/custom-rtl.css', 'public/css/app.css')
    .postCss('public/assets/css/style-rtl.css', 'public/css/app.css')
   
  
    
    
    .sourceMaps();

