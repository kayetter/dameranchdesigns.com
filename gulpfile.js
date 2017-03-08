// npm install --save-dev Gulp gulp-util gulp-webserver gulp-postcss autoprefixer precss gulp-postcss cssnano postcss-math gulp-uglify gulp-concat gulp-browserify postcss-animation

var gulp = require('gulp'),
    gutil = require('gulp-util'),
    webserver = require('gulp-webserver'),
    postcss = require('gulp-postcss'),
    autoprefixer = require('autoprefixer'),
    precss = require('precss'),
    postcss = require('gulp-postcss'),
    cssnano = require('cssnano'),
    math = require('postcss-math'),
    uglify = require ('gulp-uglify'),
    concat = require ('gulp-concat'),
    browserify = require ('gulp-browserify'),
    animation = require('postcss-animation'),

    source = 'process/css/',
    jssource = 'process/scripts/',
    dest = 'builds/development/';

gulp.task('html', function() {
    gulp.src(dest + '*.html');
});

gulp.task('css', function() {
    gulp.src(source + 'style.css')
        .pipe(postcss([
            precss(),
            math(),
            animation(),
            autoprefixer()
            // cssnano()
        ]))
        .on('error', gutil.log)
        .pipe(gulp.dest(dest + 'css'));
});

gulp.task('script', function(){
  gulp.src(jssource + '*.js')
  // .pipe(concat('scripts.js'))
  .pipe(browserify())
  .on('error', gutil.log)
  .pipe(gulp.dest(dest + 'scripts/'))
})

gulp.task('watch', function() {
    gulp.watch(source + '**/*.css', ['css']);
    gulp.watch(dest + '**/*.html', ['html']);
    gulp.watch(jssource + '*.js', ['script']);
});

gulp.task('webserver', function() {
    gulp.src(dest)
        .pipe(webserver({
            livereload: true,
            open: true
        }));
});

gulp.task('default', ['html', 'css', 'script', 'webserver', 'watch']);
