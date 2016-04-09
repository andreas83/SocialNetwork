/**
 * Created by j on 14.08.15.
 * Modifed by nfo on 31.3.16
 *
 */
var gulp = require('gulp'),
    minifycss = require('gulp-minify-css'),
    jshint = require('gulp-jshint'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename'),
    concat = require('gulp-concat'),
    cache = require('gulp-cache'),
    babel = require("gulp-babel"),
    sass = require('gulp-sass'),
    cssmin = require('gulp-cssmin'),
    del = require('del');

/*    gutil = require('gulp-util'), */

gulp.task('react', function() {
    return gulp.src(
        [
            'public/js/jsx/Author.jsx',
            'public/js/jsx/Comments.jsx',
            'public/js/jsx/Stream.jsx',
            'public/js/jsx/Likes.jsx',
            'public/js/jsx/SearchBox.jsx',
            'public/js/jsx/GroupBox.jsx',
            'public/js/jsx/ShareBox.jsx',
            'public/js/jsx/Notifications.jsx',
            'public/js/jsx/InitStream.jsx'
        ]
    )
    .pipe(babel({"presets": ["react"]}))
    .pipe(concat('react.js'))
    .pipe(gulp.dest('public/js/'))
});

gulp.task('compressjs', function(){
    return gulp.src(
        [
            "bower_components/highlightjs/highlight.pack.min.js",
            "bower_components/jquery/dist/jquery.min.js",
            "bower_components/jquery-textcomplete/dist/jquery.textcomplete.min.js",
            "bower_components/bootstrap-css/js/bootstrap.min.js",
            "bower_components/react/react.min.js",
            "bower_components/react/react-dom.min.js",
            "public/js/main.js",
            "public/js/react.js"
        ]
    )
     .pipe(uglify())
     .pipe(concat('app.js'))
     .pipe(gulp.dest('public/js/'))
          

});

gulp.task('sass', function () {
  return gulp.src('public/css/scss/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('public/css'));
});

gulp.task('compress', function(){

    return gulp.src(['bower_components/fontawesome/css/font-awesome.min.css',  
                     'bower_components/bootstrap-css/css/bootstrap.min.css', 
                     'bower_components/highlightjs/styles/railscasts.css',
                     'public/css/dmdn.css'])
    .pipe(concat('style.min.css'))
    .pipe(cssmin())
    .pipe(gulp.dest('public/css'));


});


gulp.task('icons', function() { 
    return gulp.src(['bower_components/fontawesome/fonts/**.*', 'bower_components/bootstrap-css/fonts/*.*']) 
        .pipe(gulp.dest('public/fonts')); 
});



gulp.task('default', function() {
    gulp.start('react', 'sass', 'compress', 'icons');
});


gulp.task('watch', function() {
    // Watch .scss files
    gulp.watch('public/css/scss/*.scss', ['sass', 'compress']);

    // Watch .jsx files
    gulp.watch('public/js/jsx/*.jsx', ['react', 'compressjs']);
});



