var gulp = require('gulp'),
    sass = require('gulp-ruby-sass'),
    less = require('gulp-less'),
    rewriteCSS = require('gulp-rewrite-css'),
    path = require('path'),
    autoprefixer = require('gulp-autoprefixer'),
    cssnano = require('gulp-cssnano'),
    jshint = require('gulp-jshint'),
    uglify = require('gulp-uglify'),
    imagemin = require('gulp-imagemin'),
    rename = require('gulp-rename'),
    concat = require('gulp-concat'),
    notify = require('gulp-notify'),
    cache = require('gulp-cache'),
    livereload = require('gulp-livereload'),
    del = require('del'),
    orderedMergeStream = require('ordered-merge-stream');

/*
 |--------------------------------------------------------------------------
 | Default task
 |--------------------------------------------------------------------------
 */

gulp.task('default', ['clean'], function() {
    gulp.start('styles', 'scripts', 'copy', 'images');
});

/*
 |--------------------------------------------------------------------------
 | Watch
 |--------------------------------------------------------------------------
 */

gulp.task('watch', function() {

  // Create LiveReload server
  livereload.listen();

  // Watch .scss files
  gulp.watch('resources/assets/less/**/*.less', ['styles']);

  // Watch .js files
  gulp.watch('resources/assets/js/**/*.js', ['scripts']);

  // Watch image files
  gulp.watch('../assets/images/**/*', ['images']);

});

gulp.task('watch_styles', function() {

  // Create LiveReload server
  livereload.listen();

  // Watch .scss files
  gulp.watch('resources/assets/sass/**/*.scss', ['styles']);

  // Watch .less files
  gulp.watch('resources/assets/less/**/*.less', ['styles']);

});

gulp.task('watch_scripts', function() {

  // Create LiveReload server
  livereload.listen();

  // Watch .js files
  gulp.watch('resources/assets/js/**/*.js', ['scripts']);

});


gulp.task('watch_editor_styles', function() {

  // Create LiveReload server
  livereload.listen();

  // Watch .scss files
  gulp.watch('resources/assets/sass/editor/*.scss', ['editor_styles']);

});

gulp.task('watch_editor_scripts', function() {

  // Create LiveReload server
  livereload.listen();

  // Watch editor .js files
  gulp.watch('resources/assets/js/editor/**/*.js', ['editor_scripts']);

});

/*
 |--------------------------------------------------------------------------
 | Styles
 |--------------------------------------------------------------------------
 */

gulp.task('styles', function() {

  var lessStream = gulp.src([
      'resources/assets/less/*.less',
      'bower_components/bootstrap-timepicker/css/timepicker.less'
    ])
  	.pipe(less({
      paths: [
        path.join(
          __dirname,
        'icons'
        )
      ]
    }))
    .pipe(concat('less-files.less'));

  var scssStream = sass([
    'resources/assets/sass/style.scss',
    'bower_components/ladda/css/ladda.scss',
    'bower_components/font-awesome/scss/font-awesome.scss',
    ], {
      style: 'expanded',
      loadPath: [
        'resources/assets/sass',
        'bower_components/ladda/css',
        'bower_components/spinthatshit/src',
        'bower_components/font-awesome/scss'
      ]
    })
    .pipe(concat('scss-files.scss'));

  var cssStream = gulp.src([
      /*'bower_components/bootstrap/dist/css/bootstrap.css',
      'bower_components/sweetalert/dist/sweetalert.css',*/
      'bower_components/datatables.net-bs/css/dataTables.bootstrap.css',
      'bower_components/datatables.net-responsive-bs/css/responsive.bootstrap.css',
      'bower_components/datatables-rowreorder/css/rowReorder.bootstrap.css',
      'bower_components/select2/dist/css/select2.css',
      'bower_components/dropzone/dist/dropzone.css',
      'bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css',
      'bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css',
      'bower_components/bootstrap-daterangepicker/daterangepicker.css',
      'bower_components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css',
      'bower_components/jquery-colorbox/example1/colorbox.css',
      'bower_components/circliful/css/jquery.circliful.css',
      'bower_components/fontawesome-iconpicker/dist/css/fontawesome-iconpicker.css'
/*
      'bower_components/tinymce/skins/lightgray/content.inline.min.css',
      'bower_components/tinymce/skins/lightgray/content.min.css',
      'bower_components/tinymce/skins/lightgray/skin.min.css',
*/
    ])
    .pipe(rewriteCSS({
      debug: false,
      destination: '../assets/css/',
      adaptPath: function(path) {
        var tgt = path.targetFile;
        tgt = tgt.replace('/img/', '/images/');
        return tgt;
      }
    }))
    .pipe(concat('css-files.css'));

  var mergedStream = orderedMergeStream([cssStream, lessStream, scssStream]);

  mergedStream.pipe(concat('styles.css'))
      .pipe(autoprefixer({
        browsers: ['last 2 version'],
        cascade: false
      }))
      .pipe(gulp.dest('../assets/css'))
      .pipe(rename({suffix: '.min'}))
      .pipe(cssnano({
        discardComments: {removeAll: true}
      }))
      .pipe(gulp.dest('../assets/css'))
      .pipe(livereload())
      .pipe(notify({ message: 'Styles task complete' }));

  return mergedStream;
});

/*
 |--------------------------------------------------------------------------
 | Scripts
 |--------------------------------------------------------------------------
 */

gulp.task('scripts', function() {
  return gulp.src([
      'bower_components/jquery/dist/jquery.js',
      'bower_components/jquery-ui/jquery-ui.js',
      'bower_components/director/build/director.js',
      'bower_components/bootstrap/dist/js/bootstrap.js',
      'bower_components/bootstrap-validator/dist/validator.js',
      'bower_components/fastclick/lib/fastclick.js',
      'bower_components/blockUI/jquery.blockUI.js',
      'bower_components/jquery.nicescroll/dist/jquery.nicescroll.min.js',
      'bower_components/jquery-colorbox/jquery.colorbox.js',
      'bower_components/jquery.scrollTo/jquery.scrollTo.js',
      /*'bower_components/wow/dist/wow.js',*/
  		'bower_components/jquery-form/src/jquery.form.js',
      'bower_components/ladda/js/spin.js',
      'bower_components/ladda/js/ladda.js',
      'bower_components/ladda/js/ladda.jquery.js',
      'bower_components/sweetalert2/dist/sweetalert2.js',
      /*'bower_components/sweetalert/dist/sweetalert.min.js',*/
      'bower_components/datatables.net/js/jquery.dataTables.js',
      'bower_components/datatables.net-bs/js/dataTables.bootstrap.js',
      'bower_components/datatables.net-responsive/js/dataTables.responsive.js',
      'bower_components/datatables.net-responsive-bs/js/responsive.bootstrap.js',
      'bower_components/datatables-rowreorder/js/dataTables.rowReorder.js',
      'bower_components/select2/dist/js/select2.js',
      'bower_components/moment/moment.js',
      'bower_components/dropzone/dist/dropzone.js',
      'bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js',
      'bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.js',
      'bower_components/bootstrap-daterangepicker/daterangepicker.js',
      'bower_components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js',
      'bower_components/Flot/jquery.flot.js',
/*      'bower_components/Flot/jquery.flot.stack.js',*/
      'bower_components/Flot/jquery.flot.time.js',
      'bower_components/Flot/jquery.flot.pie.js',
      'bower_components/flot.tooltip/js/jquery.flot.tooltip.js',
      'bower_components/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.js',
      'bower_components/mustache.js/mustache.js',
      'bower_components/notifyjs/dist/notify.js',
      'bower_components/notifyjs/dist/styles/metro/notify-metro.js',
      'bower_components/bootstrap-timepicker/js/bootstrap-timepicker.js',
      'bower_components/hopscotch/dist/js/hopscotch.js',
      'bower_components/circliful/js/jquery.circliful.js',
      'bower_components/jquery-throttle-debounce/jquery.ba-throttle-debounce.js',
      'bower_components/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.js',
      'bower_components/masonry/dist/masonry.pkgd.js',

      'bower_components/tinymce/tinymce.js',
      /*'bower_components/tinymce/plugins/** /*.js',*/
      'bower_components/tinymce/plugins/link/plugin.js',
      'bower_components/tinymce/plugins/paste/plugin.js',
      'bower_components/tinymce/plugins/contextmenu/plugin.js',
      'bower_components/tinymce/plugins/textpattern/plugin.js',
      'bower_components/tinymce/plugins/autolink/plugin.js',
      'bower_components/tinymce/plugins/image/plugin.js',
      'bower_components/tinymce/plugins/code/plugin.js',

      'bower_components/tinymce/themes/inlite/theme.js',
      'bower_components/tinymce/themes/modern/theme.js',

      'resources/assets/js/*.js'
      /*'resources/assets/js/** / *.js'*/
    ])
//    .pipe(jshint('.jshintrc'))
//    .pipe(jshint.reporter('default'))
    .pipe(concat('scripts.js'))
    .pipe(gulp.dest('../assets/js'))
    .pipe(rename({suffix: '.min'}))
    .pipe(uglify({
      mangle: true
    }))
    .pipe(gulp.dest('../assets/js'))
    .pipe(livereload())
    .pipe(notify({ message: 'Scripts task complete' }));
});

gulp.task('editor_scripts', function() {
  return gulp.src([
      'bower_components/jquery-ui/jquery-ui.js',
      'bower_components/wow/dist/wow.js',
      'bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js',
      'bower_components/tinymce/tinymce.js',
      /*'bower_components/tinymce/plugins/** /*.js',*/
      'bower_components/tinymce/plugins/media/plugin.js',
      'bower_components/tinymce/plugins//table/plugin.js',
      'bower_components/tinymce/plugins/anchor/plugin.js',
      'bower_components/tinymce/plugins/advlist/plugin.js',
      'bower_components/tinymce/plugins/lists/plugin.js',
      'bower_components/tinymce/plugins/link/plugin.js',
      'bower_components/tinymce/plugins/paste/plugin.js',
      'bower_components/tinymce/plugins/contextmenu/plugin.js',
      'bower_components/tinymce/plugins/textpattern/plugin.js',
      'bower_components/tinymce/plugins/autolink/plugin.js',
      'bower_components/tinymce/plugins/image/plugin.js',
      'bower_components/tinymce/plugins/code/plugin.js',
      'bower_components/tinymce/plugins/colorpicker/plugin.js',
      'bower_components/tinymce/plugins/textcolor/plugin.js',
      'bower_components/tinymce/themes/inlite/theme.js',
      'bower_components/tinymce/themes/modern/theme.js',

      'resources/assets/js/editor/constants.js',
      'resources/assets/js/editor/functions.js',
      'resources/assets/js/editor/templates.js',
      'resources/assets/js/editor/elements/blocks.js',
      'resources/assets/js/editor/elements/images.js',
      'resources/assets/js/editor/elements/icons.js',
      'resources/assets/js/editor/elements/links.js',
      'resources/assets/js/editor/elements/lists.js',
      'resources/assets/js/editor/elements/forms.js',
      'resources/assets/js/editor/elements/text.js',
      'resources/assets/js/editor/elements/fab.landingpages.js',
      'resources/assets/js/editor/elements/fab.forms.js',
      'resources/assets/js/editor/modal.js',
      'resources/assets/js/editor/init.js'
    ])
//    .pipe(jshint('.jshintrc'))
//    .pipe(jshint.reporter('default'))
    .pipe(concat('scripts.editor.js'))
    .pipe(gulp.dest('../assets/js'))
    .pipe(rename({suffix: '.min'}))
    .pipe(uglify({
      mangle: true
    }))
    .pipe(gulp.dest('../assets/js'))
    .pipe(livereload())
    .pipe(notify({ message: 'Scripts task complete' }));
});

gulp.task('scripts_map', function() {
  return gulp.src([
      'bower_components/jvectormap/jquery-jvectormap.js',
      'bower_components/jvectormap/lib/jquery-mousewheel.js',
      'bower_components/jvectormap/src/jvectormap.js',
      'bower_components/jvectormap/src/abstract-element.js',
      'bower_components/jvectormap/src/abstract-canvas-element.js',
      'bower_components/jvectormap/src/abstract-shape-element.js',
      'bower_components/jvectormap/src/svg-element.js',
      'bower_components/jvectormap/src/svg-group-element.js',
      'bower_components/jvectormap/src/svg-canvas-element.js',
      'bower_components/jvectormap/src/svg-shape-element.js',
      'bower_components/jvectormap/src/svg-path-element.js',
      'bower_components/jvectormap/src/svg-circle-element.js',
      'bower_components/jvectormap/src/svg-image-element.js',
      'bower_components/jvectormap/src/svg-text-element.js',
      'bower_components/jvectormap/src/vml-element.js',
      'bower_components/jvectormap/src/vml-group-element.js',
      'bower_components/jvectormap/src/vml-canvas-element.js',
      'bower_components/jvectormap/src/vml-shape-element.js',
      'bower_components/jvectormap/src/vml-path-element.js',
      'bower_components/jvectormap/src/vml-circle-element.js',
      'bower_components/jvectormap/src/vector-canvas.js',
      'bower_components/jvectormap/src/simple-scale.js',
      'bower_components/jvectormap/src/ordinal-scale.js',
      'bower_components/jvectormap/src/numeric-scale.js',
      'bower_components/jvectormap/src/color-scale.js',
      'bower_components/jvectormap/src/legend.js',
      'bower_components/jvectormap/src/data-series.js',
      'bower_components/jvectormap/src/proj.js',
      'bower_components/jvectormap/src/map-object.js',
      'bower_components/jvectormap/src/region.js',
      'bower_components/jvectormap/src/marker.js',
      'bower_components/jvectormap/src/map.js',
      'bower_components/jvectormap/src/multimap.js',
    ])
//    .pipe(jshint('.jshintrc'))
//    .pipe(jshint.reporter('default'))
    .pipe(concat('jvectormap.js'))
    .pipe(gulp.dest('../assets/js'))
    .pipe(rename({suffix: '.min'}))
    .pipe(uglify({
      mangle: true
    }))
    .pipe(gulp.dest('../assets/js'))
    .pipe(livereload())
    .pipe(notify({ message: 'Scripts task complete' }));
});

/*
 |--------------------------------------------------------------------------
 | Images
 |--------------------------------------------------------------------------
 */

gulp.task('images', function() {
  return gulp.src('../assets/images/**/*')
    .pipe(imagemin({ optimizationLevel: 3, progressive: true, interlaced: true }))
    .pipe(gulp.dest('../assets/images'))
    .pipe(livereload())
    .pipe(notify({ message: 'Images task complete' }));
});

/*
 |--------------------------------------------------------------------------
 | Copy
 |--------------------------------------------------------------------------
 */

gulp.task('copy', function(){
  gulp.src('bower_components/font-awesome/fonts/*.*')
    .pipe(gulp.dest('../assets/fonts'));

  gulp.src('bower_components/bootstrap-colorpicker/dist/img/bootstrap-colorpicker/*.*')
    .pipe(gulp.dest('../assets/images/bootstrap-colorpicker'));

  gulp.src('bower_components/jquery-colorbox/example2/images/*.*')
    .pipe(gulp.dest('../assets/images/colorbox'));
});

/*
 |--------------------------------------------------------------------------
 | Cleanup
 |--------------------------------------------------------------------------
 */

gulp.task('clean', function() {
    return del([
      '../assets/css/*.css', '!../assets/css/*.min.css',
      '../assets/js/*.js', '!../assets/js/*.min.js'
    ], {
      force: true
    });
});

/*
 * Process elFinder styles
 */

gulp.task('elfinder_styles', function() {
  return sass([
     'resources/assets/sass/elfinder/app.scss'
    ], {
      style: 'expanded',
      loadPath: [
        'resources/assets/sass/elfinder'
      ]
   })
  .pipe(concat('elfinder.css'))
  .pipe(autoprefixer())
  .pipe(gulp.dest('../assets/css'))
  .pipe(rename({suffix: '.min'}))
  .pipe(cssnano({
    discardComments: {removeAll: true}
  }))
  .pipe(gulp.dest('../assets/css'))
  .pipe(livereload());
});

/*
 * Blocks editor
 */

gulp.task('editor_styles', function() {
  return sass([
     'resources/assets/sass/editor/editor.scss'
    ], {
      style: 'expanded',
      loadPath: [
        'resources/assets/sass/editor'
      ]
   })
  .pipe(concat('styles.editor.css'))
  .pipe(autoprefixer())
  .pipe(gulp.dest('../assets/css'))
  .pipe(rename({suffix: '.min'}))
  .pipe(cssnano({
    discardComments: {removeAll: true}
  }))
  .pipe(gulp.dest('../assets/css'))
  .pipe(livereload());
});
