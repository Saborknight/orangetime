var util = require('util');
var browserSync = require('browser-sync').create('EHS Server');
var del = require('del');
var gulp = require('gulp');
var autoprefixer = require('gulp-autoprefixer');
var cache = require('gulp-cache');
var connect = require('gulp-connect-php');
var cssnano = require('gulp-cssnano');
var gulpIf = require('gulp-if');
var imagemin = require('gulp-imagemin');
var plumber = require('gulp-plumber');
var prompt = require('gulp-prompt');
var sass = require('gulp-sass');
var uglify = require('gulp-uglify');
var useref = require('gulp-useref');
var gutil = require('gulp-util');
var runSequence = require('run-sequence');
var ftp = require('gulp-invipo-deploy');

confPhp = true;
confSSL = true;
confWP = true;
confFtpTarget = 'insecure'; // Options: production, insecure

devDir = 'orangetime-dev';
buildDir = 'orangetime-build';
confName = 'orangetime';
confProxy = util.format('http%s://%s.com', confSSL ? 's' : '', confName);

error = gutil.colors.red;
warning = gutil.colors.yellow;
info = gutil.colors.blue;
success = gutil.colors.green;

gutil.env.tunnel ? confTunnel = confName : confTunnel = false;

gulp.task('default', function(callback) {
	runSequence(['sass', 'browserSync', 'watch'],
		callback);
});

gulp.task('build', ['ftp'], function(callback) {
	log('Building!', 'info');

	runSequence('clean:dist',
		['css', 'useref', 'images', 'wpScreen', 'fonts', 'languages', 'phpCp'],
		'ftp',
		callback);
	gutil.beep();
});

gulp.task('watch', [/*'browserSync',*/ 'sass'], function() {
	gulp.watch(globDeclaration('.scss', 'assets/src/blog'), ['sass'])
	// gulp.watch(buildDir + '/**/*.html', browserSync.reload)
	// gulp.watch(buildDir + '/**/*.js', browserSync.reload)
	// gulp.watch(buildDir + '/**/*.php', browserSync.reload)
	// gulp.watch(devDir + '/**/*.*', ['ftp:dev'])
});

gulp.task('sass', function() {
	sassProcess();
});

gulp.task('css', function() {
	sassProcess();
	autoprefix();
	orangetime();

	if( confWP ) {
		return gulp.src(globDeclaration('style.css'))
			.pipe(gulp.dest(buildDir))
	}
});

gulp.task('ftp', function() {
	/**
	 * Enables ftp features.
	 * 
	 * @use gulp ftp [-t ftpTarget| --target ftpTarget][-w | --watch]
	 **/

	var target = gutil.env.target || gutil.env.t;
	var watch = gutil.env.watch || gutil.env.w;

	if(target) {
		confFtpTarget = target;
	}

	if(watch) {
		changed = '';
		log('Watching');
		gulp.watch(devDir, ['ftp'])
			.on('change', function(event) {
				changed = event.path;
				log(changed + ' has changed!');
			});
	}

	if(confFtpTarget === null || confFtpTarget === '') {
		log('No target server has been set!', 'error');
	} else {
		(confFtpTarget === 'insecure') ? log('WARNING: This connection will be insecure!', 'warning') : log('Ehmmm...', 'info');

		setTimeout( function() {
			log(util.format('Syncronising %s environment!', confFtpTarget), 'info');

			return ftp({
					conn: require('./.ftp-config.json')[confFtpTarget],
					src: devDir,
					dest: '/webroot/wp-content/themes/orangetime',
					globs: [
						(changed) ? changed : './**/*.*',
						'!./**/*.mp4',
						'!./libs/**/*.*',
					],
					clean: false,
				});
		}, 10000, confFtpTarget);
	}
});

gulp.task('ftp:dev', function() {
	/**
	 * Enables ftp features.
	 * 
	 * @use gulp ftp [-t ftpTarget| --target ftpTarget]
	 **/

	var target = gutil.env.target || gutil.env.t;

	if(target) {
		confFtpTarget = target;
	}

	if(confFtpTarget === null || confFtpTarget === '') {
		log('No target server has been set!', 'error');
	} else {
		(confFtpTarget === 'insecure') ? log('WARNING: This connection will be insecure!', 'warning') : log('Ehmmm...', 'info');

		setTimeout( function() {
			log(util.format('Syncronising %s environment!', confFtpTarget), 'info');

			return ftp({
					conn: require('./.ftp-config.json')[confFtpTarget],
					src: devDir,
					dest: '/webroot/wp-content/themes/orangetime',
					globs: [
						'./assets/**/styles_screen_mod.css',
						'!./**/*.mp4',
						'!./libs/**/*.*',
					],
					clean: false,
				});
		}, 1000, confFtpTarget);
	}
});

gulp.task('browserSync', ['php'], function() {
	browserSync.init({
		proxy: confPhp ? confProxy : false,
		server: !confPhp ? { baseDir: devDir } : false,
		tunnel: confTunnel,
		reloadOnRestart: true,
		port: 8010
	});
});

gulp.task('php', function() {
	if(confPhp) {
		connect.server({ base: devDir, port: 8010, keepalive: true});
	}
});

gulp.task('useref', function(){
	if(!confWP) {
		return gulp.src( (confPhp) ? globDeclaration('.php') : globDeclaration('.html') )
			.pipe(useref())
			// Minifies only if it's a JavaScript file
			.pipe(gulpIf('*.js', uglify()))
			// Minifies only if it's a CSS file
			.pipe(gulpIf('*.css', cssnano()))
			.pipe(gulp.dest(buildDir))
	} else {
		log('JavaScript Minifying', 'info');

		return gulp.src( globDeclaration('.js') )
			// Minifies only if it's a JavaScript file
			.pipe(gulpIf('*.js', uglify()))
			.pipe(gulp.dest(buildDir));
	}
});

gulp.task('images', function(){
	return gulp.src( (confWP) ? globDeclaration('.+(png|jpg|jpeg|gif|svg)', 'images') : globDeclaration('.+(png|jpg|jpeg|gif|svg)') )
		.pipe(cache(imagemin({
			// Setting interlaced to true
			interlaced: true
		})))
		.pipe(gulp.dest(buildDir + '/img'))
});

gulp.task('wpScreen', function() {
	if( confWP ) {
		return gulp.src( globDeclaration('screenshot.+(png|jpg|jpeg|gif|svg)') )
			.pipe(gulp.dest(buildDir))
	}
})

gulp.task('fonts', function() {
	return gulp.src(globDeclaration('', 'fonts'))
		.pipe(gulp.dest(buildDir + '/fonts'))
});

gulp.task('languages', function() {
	return gulp.src(globDeclaration('', 'languages'))
		.pipe(gulp.dest(buildDir + '/languages'))
});

gulp.task('phpCp', function() {
	if(confWP) return gulp.src( globDeclaration('.php') )
		.pipe(gulp.dest(buildDir));
});

gulp.task('clean:dist', function() {
	return del.sync(buildDir);
});

gulp.task('cache:clear', function (callback) {
	return cache.clearAll(callback);
});

function orangetime() {
	log('Handling Orangetime Build', 'info');

	return gulp.src([util.format('%s/%s**/%s', devDir, 'assets/', '*'),
		util.format('!%s/%s**/*%s', devDir, 'assets/src/blog/', 'styles_screen_mod.scss')])
		.pipe(gulp.dest(buildDir + '/assets/'));
}

// setTimeout doesn't catch the 'state' variable when nested in a function because it's being stupid!
// function ftp(state) {
// 	(state === 'insecure') ? log('WARNING: This connection will be insecure!', 'warning') : log('Ehmmm...', 'info');

// 	setTimeout( function() {
// 		log('Syncronising!', 'info');
// 		log(state);

// 		return ftp({
// 				conn: require('./.ftp-config.json')[state],
// 				src: buildDir,
// 				dest: '/webroot/wp-content/themes/orangetime',
// 				globs: [
// 					'./**/*.*',
// 				],
// 				clean: false,
// 			});
// 	}, 10000, state);
// }

function sassProcess() {
	log('Compiling [sass]', 'info');

	return gulp.src([util.format('%s/%s**/%s', devDir, 'assets/src/blog/', 'styles_screen_mod.scss'), 
		util.format('!%s/%s**/%s-OLD*', devDir, 'assets/src/blog/', 'styles_screen_mod.scss')])
		.pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError)) // Using gulp-sass
		.pipe(gulp.dest(devDir + '/assets/dist/css/'))
		.pipe(browserSync.reload({
			stream: true
	}));
}

function autoprefix() {
	log('Autoprefixing', 'info');

	if(confWP) {
		return gulp.src([util.format('%s/%s**/%s', devDir, 'assets/dist/css/', 'styles_screen_mod.css'),
		util.format('!%s/%s**/%s-OLD*', devDir, 'assets/dist/css/', 'styles_screen_mod.min.css')])
			.pipe(autoprefixer({
				browsers: ['last 2 versions', 'ie >= 9', 'and_chr >= 2.3']
			}))
			// Minifies only if it's a CSS file
			.pipe(gulpIf('*.css', cssnano()))
			.pipe(gulp.dest(buildDir + '/assets/dist/css/'));
	} else {
		return gulp.src(globDeclaration('.css', 'css'))
			.pipe(autoprefixer({
				browsers: ['last 2 versions', 'ie >= 9', 'and_chr >= 2.3']
			}))
			.pipe(gulp.dest(buildDir + '/css'));
	}
}

function globDeclaration(fileType, folder = false) {
	srcGlob = [util.format('%s/%s**/*%s', devDir, folder ? folder +'/' : '', fileType), 
		util.format('!%s/%s**/*-OLD*%s', devDir, folder ? folder +'/' : '', fileType)];

	return srcGlob;
}

function log(message, state = 'info') {
	switch(state) {
		case 'error':
			var preText = error.dim('xxxxxxx>');
			var postText = error.dim('<xxxxxxx');
			var message = error(message);
			break;

		case 'warning':
			var preText = warning.dim('-------------->');
			var message = warning(message);
			break;

		case 'success':
			var preText = success.dim('------->');
			var message = success(message);
			break;

		default:
			var preText = info.dim('------->');
			var message = info(message);
			break;

	}

	return gutil.log( util.format('Logging %s %s %s'), preText, message, postText ? postText : '' );
}

var gulp_src = gulp.src;
gulp.src = function() {
	return gulp_src.apply(gulp, arguments)
		.pipe(plumber(function(error) {
			// Output an error message
			log('Error (' + error.plugin + '): ' + error.message, 'error');
			// emit the end event, to properly end the task
			this.emit('end');
		})
	);
};