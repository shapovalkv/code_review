const mix = require('laravel-mix');
const process = require("process");
const webpackConfig = require("./webpack.config");

// Admin

mix.setPublicPath('public/dist/frontend');

mix.setResourceRoot('/dist/frontend');
// mix.webpackConfig({
//     output: {
//         path:__dirname+'/public/dist/frontend',
//     }
//
// });

mix.js('resources/js/libs.js','js');
mix.scripts(['node_modules/bootstrap/dist/js/bootstrap.js'],'public/dist/frontend/js/bootstrap5.js');
mix
    .js('resources/module/template/frontend/dashboard/dashboard.js','js')
    .js('resources/module/template/frontend/header/header.js','js')
    .js('resources/module/template/frontend/job/editJob.js','js')
    .js('resources/module/template/frontend/job/manageJob.js','js')
    .js('resources/module/template/frontend/applicants/manageApplicants.js','js')
    .js('resources/module/template/frontend/equipment/manageEquipment.js','js')
    .js('resources/module/template/frontend/bookmarks/manageBookmarks.js','js')
    .js('resources/module/template/frontend/company/editCompany.js','js')
    .vue({
        extractStyles: true,
        globalStyles: 'resources/assets/scss/_variables.scss',
    })
    .webpackConfig(webpackConfig)

mix.sass('public/sass/app.scss','css');
mix.sass('public/sass/contact.scss','css');
mix.sass('public/sass/rtl.scss','css');
mix.sass('public/sass/notification.scss','css');
// ----------------------------------------------------------------------------------------------------
//Booking
mix.sass('public/module/order/scss/checkout.scss','module/order/css');
mix.sass('public/module/user/scss/user.scss','module/user/css');
mix.sass('public/module/user/scss/profile.scss','module/user/css');
mix.sass('public/module/news/scss/news.scss','module/news/css');
mix.sass('public/module/media/scss/browser.scss','module/media/css');
mix.sass('public/module/location/scss/location.scss','module/location/css');
mix.sass('public/module/social/scss/social.scss','module/social/css');
mix.sass('public/module/gig/scss/gig.scss','module/gig/css');
mix.sass('resources/sass/chat.scss','css');

if (mix.inProduction()) {
    mix.version();
}
