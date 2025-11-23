const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const cleanCSS = require('gulp-clean-css');
const sourcemaps = require('gulp-sourcemaps');
const concat = require('gulp-concat');
const terser = require('gulp-terser');

const paths = {
  styles: {
    main: 'styles/style.scss',
    watch: 'styles/**/*.scss',
    dest: 'styles/dist'
  },
  scripts: {
    src: 'js/*.js',
    dest: 'js/dist'
  }
};

function styles() {
  return gulp.src(paths.styles.main)
    .pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(concat('style.min.css'))
    .pipe(cleanCSS())
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.styles.dest));
}

function scripts() {
  return gulp.src(paths.scripts.src)
    .pipe(sourcemaps.init())
    .pipe(concat('scripts.min.js'))
    .pipe(terser())
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.scripts.dest));
}

function watch() {
  gulp.watch(paths.styles.watch, styles);
  gulp.watch(paths.scripts.src, scripts);
}

exports.styles = styles;
exports.scripts = scripts;
exports.watch = watch;
exports.default = gulp.series(gulp.parallel(styles, scripts), watch);