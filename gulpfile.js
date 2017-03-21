// npm install --save-dev Gulp gulp-util gulp-webserver gulp-postcss autoprefixer precss cssnano postcss-math gulp-uglify gulp-concat gulp-browserify postcss-animation gulp-if cssnano gulp-cssnano gulp-rev-replace gulp-rev rev-del gulp-clean gulp-imagemin imagemin-pngcrush gulp-jsonminify

var gulp = require('gulp'),
    gutil = require('gulp-util'),
    clean = require('gulp-clean'),
    pngcrush = require('imagemin-pngcrush'),
    imagemin = require('gulp-imagemin'),
    clean = require('gulp-clean'),
    webserver = require('gulp-webserver'),
    postcss = require('gulp-postcss'),
    autoprefixer = require('autoprefixer'),
    precss = require('precss'),
    postcss = require('gulp-postcss'),
    cssnano = require('cssnano'),
    gulpcssnano = require('gulp-cssnano'),
    math = require('postcss-math'),
    jsonminify = require ('gulp-jsonminify'),
    uglify = require ('gulp-uglify'),
    concat = require ('gulp-concat'),
    browserify = require ('gulp-browserify'),
    animation = require('postcss-animation'),
    gulpif = require('gulp-if'),
    rev = require('gulp-rev'),
    revDel = require('rev-del'),
    revReplace = require('gulp-rev-replace'),

    source = 'process/css/',
    jssource = ['process/scripts/'],
    dest = 'builds/development/',
    prod = 'builds/production/',
    noProcss = ['builds/development/css/jquery.mCustomScrollbar.css'],
    isProd,
    isDev,
    limbo = 'process/limbo/',
    build;


//sets a variable that if set = production environment otherwise it defaults to development, at cmd prompt use NODE_ENV=production gulp
env = process.env.NODE_ENV || 'development';

//boolean for is prod = true or isDev = true
isProd = env==='production';
isDev = env === 'development';

//variable changes in different environments
if (isDev) {
    build = dest;
} else {
    build = prod;
}


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
        .pipe(gulp.dest(dest + 'css'))
        .pipe(gulpif(isProd, gulpcssnano({
          sourcemap: true
        })))
        .pipe(gulpif(isProd, gulp.dest(limbo + 'css')));
});


//browserify scripts
gulp.task('script', function(){
  gulp.src(jssource + '*.js')
  // .pipe(concat('scripts.js'))
  .pipe(browserify())
  .on('error', gutil.log)
  .pipe(gulpif(isDev, gulp.dest(dest + 'scripts/')))
  .pipe(gulpif(isProd, uglify()))
  .pipe(gulpif(isProd, gulp.dest(limbo + 'scripts/')));
});

//PRODUCTION TASKS

//move .html files to production folder
gulp.task('moveHTML', function() {
    gulp.src(dest + '*.html')
    .pipe(gulpif(isProd,
      gulp.dest(limbo)));
});

//not processing svg and ico files through imagemin so going directly to prod
gulp.task('moveImages', function(){
  gulp.src(dest + 'images/**/*.{svg,ico}')
  .pipe(gulpif(isProd,
    gulp.dest(prod + 'images/')));
});

//css files associated with plugins that do not need to go through postcss but need to move to limbo

gulp.task('moveCSS', function(){
  gulp.src(noProcss)
  .pipe(gulpif(isProd, gulpcssnano()))
  .pipe(gulpif(isProd, gulp.dest(limbo + 'css')));
});

gulp.task('moveFiles', ['moveHTML', 'moveImages', 'moveCSS']);

//if isProd will minify json files and move to limbo
gulp.task('jsonminify', function () {
    gulp.src('builds/development/js/*.json')
        .pipe(gulpif(isProd, jsonminify()))
        .pipe(gulpif(isProd, gulp.dest(limbo + 'js')));
});

//if isProd compresses image files and rmoves viewbox for svg and puts in limbo if i
gulp.task('images', function () {
    gulp.src('builds/development/images/**/*.{jpg,JPEG,png,jpeg,gif}')
        .pipe(gulpif(isProd, imagemin({
            progressive: true,
            svgoPlugins: [{
                removeViewBox: false
            }],
            use: [pngcrush()]
        })))
        .pipe(gulpif(isProd, gulp.dest(prod + 'images')));
});

gulp.task('uglify', function(){
  gulp.src(dest + 'scripts/*.js')
  .pipe(uglify())

})

//==============MAKE PRODUCTION BUILD
// three tasks for file hashing and cachebusting
//***********************************************************

//deletes production and interim files for prod build -- do this first
gulp.task('cleanProd', function(){
    gulp.src(prod, {read: false})
        .pipe(clean());
    gulp.src(limbo, {read:false})
        .pipe(clean());
});



//creates signature on all files indicated and moves them to prod folder and creates manifest -- rev-manifest.json. do this third
gulp.task('rev', function () {
    gulp.src(limbo + '**/*.{js,css,json,png,ico,jpg,JPG,jpeg,pdf,svg,gif}')
        .pipe(rev())
        .pipe(gulp.dest(prod))
        .pipe(rev.manifest())
        .pipe(gulp.dest(prod))
        .pipe(revDel({dest: prod}))
        .pipe(gulp.dest(prod))
});

//replaces all references to the modified file names in PHP files that are sitting in limbo, then moves them to prod run revcss fourth as revreplace is a dependency
gulp.task('revreplace', function () {
    var manifest = gulp.src(prod + 'rev-manifest.json');
    return gulp.src(limbo + '*.html')
        .pipe(revReplace({manifest: manifest,
                          replaceInExtensions: ['.js', '.css', '.html']
                         }))
        .pipe(gulp.dest(prod));
});

//replacess all references in .css and .js files that are in prod and saves them to prod
gulp.task('revcss', ['revreplace'], function(){

    var manifest = gulp.src('builds/production/rev-manifest.json');
    return gulp.src('builds/production/**/*.{css,js}')
        .pipe(revReplace({manifest: manifest,
                          replaceInExtensions: ['.js', '.css', '.html']
                         }))
        .pipe(gulp.dest(prod));
});


  gulp.task('watch', function() {
      gulp.watch(source + '**/*.css', ['css', 'moveFiles']);
      gulp.watch(dest + '**/*.html', ['moveFiles']);
      gulp.watch(jssource + '*.js', ['script']);
      gulp.watch(dest + 'images/**/*.*', ['moveFiles'])
  });


gulp.task('webserver', function() {
   gulp.src(build)
   .pipe(webserver({
            livereload: true,
            open: true
        }));
});
//4-step process to get to prod 1. cleanProd 2. preProd 3. rev 4. revcss. Step#2 is the only one that needs to be run in NODE_ENV=production


gulp.task('preprod', ['moveFiles', 'css', 'script', 'images', 'jsonminify']);
gulp.task('default', ['moveFiles', 'css', 'script', 'images', 'webserver', 'watch']);
