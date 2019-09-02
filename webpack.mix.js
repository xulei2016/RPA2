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

 let jspack = [
    // 'public/js/app.js',   //app
    // 'public/include/jquery/jquery-3.3.1.min.js', //Jquery
    'public/include/bootstrap/dist/js/bootstrap.min.js', //Bootstrap 3.3.7
    'public/include/bootstrap-table/bootstrap-table.min.js',     //bootstrap-table
    'public/include/bootstrap-table/local/bootstrap-table-zh-CN.js',     //
    'public/include/bootstrap-switch/js/bootstrap-switch.min.js',     //bootstrap-switch
    'public/include/jquery-slimscroll/jquery.slimscroll.min.js',     //slimscroll
    'public/include/adminLte/js/adminlte.min.js',     //adminlte
    'public/include/nprogress/nprogress.js',     //nprogress
    'public/include/jquery-pjax/JQuery.pjax.js',     //JQuery.pjax
    'public/include/toastr/toastr.min.js',     //toastr
    'public/include/sweetalert2/sweetalert2.min.js',     //sweetalert2
    'public/include/select2/dist/js/select2.min.js',     //select2
    'public/include/jquery-form/jquery.form.js',     //jquery.form
    'public/include/iCheck/iCheck.min.js',     //iCheck
    'public/include/plupload/js/plupload.full.min.js',     //plupload.full
    // 'public/include/ckeditor/ckeditor.js',     //ckeditor
    'public/include/laydate/laydate.js',     //laydate
    'public/include/jquery-validate/jquery.validate.min.js',     //jquery.validate.min
    'public/include/jquery-validate/localization/messages_zh.min.js',     //messages_zh
    'https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.js',     //fix ie
 ];

 let csspack = [
    'public/include/bootstrap/dist/css/bootstrap.min.css',    //bootstrap
    'public/include/adminLte/css/AdminLTE.min.css',   //AdminLTE
    'public/include/adminLte/css/skins/_all-skins.min.css',     //AdminLTE skins
    'public/include/bootstrap-table/bootstrap-table.css',     //bootstrap-table
    'public/include/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css',     //bootstrap-switch
    'public/include/font-awesome/css/font-awesome.min.css',     //font-awesome
    'public/include/Ionicons/css/ionicons.min.css',     //ionicons
    'public/include/toastr/toastr.min.css',     //toastr
    'public/include/iCheck/minimal/blue.css',     //iCheck
    'public/include/nprogress/nprogress.css',     //nprogress
    'public/include/sweetalert2/sweetalert2.min.css',     //sweetalert2
    'public/include/select2/dist/css/select2.min.css',     //select2
    'public/include/laydate/theme/default/laydate.css',     //laydate
 ];

 mix.js(['resources/assets/js/app.js'], 'public/js')
        .scripts(jspack, 'public/js/all.js')

        // .sass('resources/assets/sass/app.scss', 'public/css')
        .styles(csspack, 'public/css/all.css')


        //font-awesome fonts dir
        .copyDirectory('public/include/font-awesome/fonts', 'public/fonts')
        //laydate theme
        .copyDirectory('public/include/laydate/theme', 'public/js/theme')
        //ionicons fonts dir
      //   .copyDirectory('public/include/ionicons/fonts', 'public/css/iconfont')
        .copyDirectory('public/include/ionicons/fonts', 'public/css/iconfont')
        //ickeck blue theme png
        .copy('public/include/iCheck/minimal/blue.png', 'public/css/')
        //boostrap fonts dir
        .copyDirectory('public/include/bootstrap/fonts', 'public/fonts');