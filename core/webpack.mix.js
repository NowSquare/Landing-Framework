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

mix.disableNotifications();

mix.setPublicPath('../');

mix.options({
    processCssUrls: false,
    postCss: [require('autoprefixer')],
    uglify: {
      uglifyOptions: {
        warnings: false,
        parse: {},
        compress: {},
        mangle: true,
        output: null,
        toplevel: true,
        nameCache: null,
        ie8: true,
        keep_fnames: false,
      }
    },
});

mix
	 /* Lead assets */
   .js('resources/js/leads.js', '../modal/scripts.js')
   .sass('resources/sass/leads.scss', '../modal/style.css', {
      outputStyle: 'compressed'
    })

	 /* Lead modal assets */
   .js('resources/js/lead-modal.js', '../modal/modal.js')
   .sass('resources/sass/lead-modal.scss', '../modal/modal.css', {
      outputStyle: 'compressed'
    })
;