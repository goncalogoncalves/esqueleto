let gulp = require('gulp');
let concat = require('gulp-concat');
//let uglify = require('gulp-uglify');
let refresh = require('gulp-livereload');
let minifyCSS = require('gulp-minify-css');
let rename = require("gulp-rename");
//let obfuscator = require('gulp-javascript-obfuscator');
let lr = require('tiny-lr');
let server = lr();

gulp.task('scripts', function() {
	gulp.src(['public/js/main.js'])
	.pipe(concat('main.js'))
	.pipe(rename({ suffix: '.min' }))
	/*.pipe(obfuscator({
		compact: true,
		sourceMap: true
	}))*/
	.pipe(gulp.dest('public/dist/js'))
	.pipe(refresh(server));

	gulp.src(['public/js/functions.js'])
	.pipe(concat('functions.js'))
	.pipe(rename({ suffix: '.min' }))
	/*.pipe(obfuscator({
		compact: true,
		sourceMap: true
	}))*/
	.pipe(gulp.dest('public/dist/js'))
	.pipe(refresh(server));

	gulp.src(['public/js/libs/jquery-3.3.1.min.js'])
	.pipe(concat('libs.js'))
	.pipe(rename({ suffix: '.min' }))
	/*.pipe(obfuscator({
		compact: true,
		sourceMap: true
	}))*/
	.pipe(gulp.dest('public/dist/js'))
	.pipe(refresh(server));
});

gulp.task('styles', function() {
	gulp.src(['public/css/main.css'])
	.pipe(concat('main.css'))
	.pipe(minifyCSS())
	.pipe(rename({ suffix: '.min' }))
	.pipe(gulp.dest('public/dist/css'))
	.pipe(refresh(server));

	gulp.src(['public/css/libs/breve.min.css', 'public/css/libs/fontawesome-all.min.css'])
	.pipe(concat('libs.css'))
	.pipe(minifyCSS())
	.pipe(rename({ suffix: '.min' }))
	.pipe(gulp.dest('public/dist/css'))
	.pipe(refresh(server));
});

gulp.task('lr-server', function() {
	server.listen(35729, function(err) {
		if(err) return console.log(err);
	});
});

gulp.task('default', function() {
	gulp.run('lr-server', 'scripts', 'styles');

	gulp.watch('public/js/**', function(event) {
		gulp.run('scripts');
	});

	gulp.watch('public/css/**', function(event) {
		gulp.run('styles');
	})
});