var gulp = require('gulp'),
    sass = require('gulp-ruby-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    cssnano = require('gulp-cssnano'),
    uglify = require('gulp-uglify'),
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
  gulp.watch('resources/sass/**/*.scss', ['styles']);

  // Watch .js files
  gulp.watch('resources/scripts/**/*.js', ['scripts']);

  // Watch image files
  gulp.watch('images/**/*', ['images']);

});

gulp.task('watch_styles', function() {

  // Create LiveReload server
  livereload.listen();

  // Watch .scss files
  gulp.watch('resources/sass/**/*.scss', ['styles']);

});

gulp.task('watch_scripts', function() {

  // Create LiveReload server
  livereload.listen();

  // Watch .js files
  gulp.watch('resources/scripts/**/*.js', ['scripts']);

});

/*
 |--------------------------------------------------------------------------
 | Styles
 |--------------------------------------------------------------------------
 */

gulp.task('styles', function() {
  var sassStream = sass([
    'resources/sass/style.scss', 
    'resources/sass/bootstrap.scss',
    'resources/sass/sweetalert.scss',
    'bower_components/tether/src/css/tether.sass',
    /*'bower_components/tether-drop/src/css/drop-theme-basic.sass',*/
    'bower_components/owl.carousel/src/scss/owl.carousel.scss',
    'bower_components/ladda/css/ladda.scss'
  ], {
    style: 'expanded',
    loadPath: [ 
      'resources/sass',
      'bower_components/bootstrap/scss'
    ]
  });

  var mergedStream = orderedMergeStream([sassStream]);

  mergedStream.pipe(concat('style.css'))
    .pipe(autoprefixer({
      browsers: ['last 2 version'], 
      cascade: false
    }))
    .pipe(gulp.dest('css'))
    .pipe(rename({suffix: '.min'}))
    .pipe(cssnano({
      discardComments: {removeAll: true}
    }))
    .pipe(gulp.dest('css'))
    .pipe(livereload())
    .pipe(notify({ message: 'Styles task complete' }));

  return mergedStream;
});


/*
 |--------------------------------------------------------------------------
 | Styles
 |--------------------------------------------------------------------------
 */

gulp.task('styles_lite', function() {
  return sass([
      'resources/sass/plugins/_notify-metro.scss',
      'bower_components/tether/src/css/tether.sass'/*,
      'bower_components/tether-drop/src/css/drop-theme-basic.sass',*/
    ], {
      style: 'expanded',
      loadPath: [ 
        'resources/sass'
      ]
    })
  	.pipe(concat('style.lite.css'))
    .pipe(autoprefixer({
      browsers: ['last 2 version'], 
      cascade: false
    }))
    .pipe(gulp.dest('css'))
    .pipe(rename({suffix: '.min'}))
    .pipe(cssnano({
      discardComments: {removeAll: true}
    }))
    .pipe(gulp.dest('css'))
    .pipe(livereload())
    .pipe(notify({ message: 'Styles task complete' }));
});

/*
 |--------------------------------------------------------------------------
 | Scripts
 |--------------------------------------------------------------------------
 */

gulp.task('scripts', function() {
  return gulp.src([
      'bower_components/jquery/dist/jquery.js',
      'bower_components/tether/dist/js/tether.js',
      /*'bower_components/tether-drop/dist/js/drop.js',*/
      'bower_components/bootstrap/dist/js/bootstrap.js',
      'bower_components/bluebird/js/browser/bluebird.js',
  		'bower_components/blockUI/jquery.blockUI.js',
      'bower_components/owl.carousel/dist/owl.carousel.js',
      /*'bower_components/particles.js/particles.js',
      'bower_components/flat-surface-shader/deploy/fss.js',*/
		  'bower_components/jquery.scrollTo/jquery.scrollTo.js',
      'bower_components/ladda/js/spin.js',
      'bower_components/ladda/js/ladda.js',
      'bower_components/ladda/js/ladda.jquery.js',
      'bower_components/jquery-form/src/jquery.form.js',
      'bower_components/sweetalert2/dist/sweetalert2.js',
      'bower_components/bootstrap-validator/dist/validator.js',
      'bower_components/H5F/src/H5F.js',
      'bower_components/notifyjs/dist/notify.js',
      /*'bower_components/parsleyjs/dist/parsley.js',*/
      'resources/scripts/**/*.js'
    ])
    .pipe(concat('scripts.js'))
    .pipe(gulp.dest('js'))
    .pipe(rename({suffix: '.min'}))
    .pipe(uglify({
      mangle: true
    }))
    .pipe(gulp.dest('js'))
    .pipe(livereload())
    .pipe(notify({ message: 'Scripts task complete' }));
});

/*
 |--------------------------------------------------------------------------
 | Scripts lite
 |--------------------------------------------------------------------------
 */

gulp.task('scripts_lite', function() {
  return gulp.src([
      'bower_components/jquery/dist/jquery.js',
      'bower_components/tether/dist/js/tether.js',
      /*'bower_components/tether-drop/dist/js/drop.js',*/
      'bower_components/notifyjs/dist/notify.js',
      'resources/scripts/notify-metro.js'
    ])
    .pipe(concat('scripts.lite.js'))
    .pipe(gulp.dest('js'))
    .pipe(rename({suffix: '.min'}))
    .pipe(uglify({
      mangle: true
    }))
    .pipe(gulp.dest('js'))
    .pipe(livereload())
    .pipe(notify({ message: 'Scripts task complete' }));
});

/*
 |--------------------------------------------------------------------------
 | Images
 |--------------------------------------------------------------------------
 */

gulp.task('images', function() {
  return gulp.src('images/**/*')
  /*
    .pipe(imagemin({ optimizationLevel: 3, progressive: true, interlaced: true }))*/
    .pipe(gulp.dest('images'))
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
    .pipe(gulp.dest('fonts/font-awesome'));
});


/*
 |--------------------------------------------------------------------------
 | Cleanup
 |--------------------------------------------------------------------------
 */

gulp.task('clean', function() {
    return del([
      'css/*.css', '!css/*.min.css',
      'js/*.js', '!js/*.min.js'
    ], {
      force: true
    });
});