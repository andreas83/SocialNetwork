/**
 * Created by j on 14.08.15.
 * Modifed by nfo on 31.3.16
 * @todo remove sass and every not related 
 */
var gulp = require('gulp'),
    minifycss = require('gulp-minify-css'),
    jshint = require('gulp-jshint'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename'),
    concat = require('gulp-concat'),
    cache = require('gulp-cache'),
    babel = require("gulp-babel");
    del = require('del');



gulp.task('react', function() {
    return gulp.src(['public/js/jsx/Author.jsx', 'public/js/jsx/Comments.jsx', 
		     'public/js/jsx/Stream.jsx', 'public/js/jsx/Likes.jsx', 
                     'public/js/jsx/SearchBox.jsx', 'public/js/jsx/Chat.jsx',
                     'public/js/jsx/ShareBox.jsx', 'public/js/jsx/Notifications.jsx', 'public/js/jsx/InitStream.jsx'])
        .pipe(babel({"presets": ["react"]}))
        .pipe(concat('react.js'))
        .pipe(gulp.dest('public/js/'))
});

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

    // Watch .jsx files
    gulp.watch('public/jsx/*.jsx', ['react']);
});

gulp.task('default', function() {
    gulp.start('react', 'scripts');
});


gulp.task('watch', function() {
    // Create LiveReload server
    livereload.listen();

    // Watch any files in dist/, reload on change
    gulp.watch(['dist/**']).on('change', livereload.changed);
});
