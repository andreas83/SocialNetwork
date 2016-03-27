/**
 * Created by j on 14.08.15.
 */
var gulp = require('gulp'),
    sass = require('gulp-sass'),
    minifycss = require('gulp-minify-css'),
    jshint = require('gulp-jshint'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename'),
    concat = require('gulp-concat'),
    cache = require('gulp-cache'),
    babel = require('gulp-babel'),
    livereload = require('gulp-livereload'),
    del = require('del');

gulp.task('styles', function() {

    return gulp.src('public/css/scss/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('public/css/assets'))
        .pipe(rename({suffix: '.min'}))
        .pipe(minifycss())
        .pipe(gulp.dest('public/css/assets'))
});

gulp.task('default', () =>
    gulp.src('public/js/*.jsx')
        .pipe(babel({
            presets: ['react']
        }))
        .pipe(gulp.dest('dist'))
);

gulp.task('scripts', function() {
    return gulp.src('public/js/*.js')
        .pipe(jshint('.jshintrc'))
        .pipe(jshint.reporter('default'))
        .pipe(concat('main.js'))
        .pipe(gulp.dest('public/js/assets'))
        .pipe(rename({suffix: '.min'}))
        .pipe(uglify())
        .pipe(gulp.dest('public/js/assets'));
});

gulp.task('clean', function(cb) {
    del(['public/css/assets', 'public/js/assets'], cb);
});

gulp.task('watch', function() {
    // Watch .scss files
    gulp.watch('public/css/**/*.scss', ['styles']);

    // Watch .js files
    gulp.watch('public/js/*.js', ['scripts']);
});

gulp.task('default', function() {
    gulp.start('styles', 'scripts');
});


gulp.task('watch', function() {
    // Create LiveReload server
    livereload.listen();

    // Watch any files in dist/, reload on change
    gulp.watch(['dist/**']).on('change', livereload.changed);
});
