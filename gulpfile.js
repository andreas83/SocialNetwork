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
    gutil = require('gulp-util'),
    del = require('del');



gulp.task('react', function() {
    return gulp.src(
        [
            'public/js/jsx/Author.jsx',
            'public/js/jsx/Comments.jsx',
            'public/js/jsx/Stream.jsx',
            'public/js/jsx/Likes.jsx',
            'public/js/jsx/SearchBox.jsx',
            'public/js/jsx/Chat.jsx',
            'public/js/jsx/ShareBox.jsx',
            'public/js/jsx/Notifications.jsx',
            'public/js/jsx/InitStream.jsx'
        ]
    )
    .pipe(babel({"presets": ["react"]}))
    .pipe(concat('react.js'))
    .pipe(gulp.dest('public/js/'))
});

gulp.task('compress', function(){
    return gulp.src(
        [
            "bower_components/highlightjs/highlight.pack.min.js",
            "bower_components/jquery/dist/jquery.min.js",
            "bower_components/jquery-textcomplete/dist/jquery.textcomplete.min.js",
            "bower_components/bootstrap-css/js/bootstrap.min.js",
            "bower_components/react/react.min.js",
            "bower_components/react/react-dom.min.js",
            "public/js/assets/main.min.js",
            "public/js/react.js"
        ]
    )
     .pipe(uglify().on('error', gutil.log))
     .pipe(concat('app.js'))
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
    gulp.start('react', 'compress', 'scripts');
});


gulp.task('watch', function() {
    // Create LiveReload server
    livereload.listen();

    // Watch any files in dist/, reload on change
    gulp.watch(['dist/**']).on('change', livereload.changed);
});
