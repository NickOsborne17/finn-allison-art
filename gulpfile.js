const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const cleanCSS = require('gulp-clean-css');
const sourcemaps = require('gulp-sourcemaps');
const concat = require('gulp-concat');
const webpack = require('webpack-stream');

const paths = {
  styles: {
    main: 'styles/style.scss',
    watch: 'styles/**/*.scss',
    dest: 'styles/dist'
  },
  scripts: {
    entry: './js/main.js',
    watch: 'js/*.js',
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
  return gulp.src(paths.scripts.entry)
    .pipe(webpack({
      mode: 'production',
      output: {
        filename: 'scripts.min.js'
      },
      module: {
        rules: [
          {
            test: /\.js$/,
            exclude: /node_modules/,
            use: {
              loader: 'babel-loader',
              options: {
                presets: ['@babel/preset-env']
              }
            }
          }
        ]
      },
      devtool: 'source-map'
    }))
    .pipe(gulp.dest(paths.scripts.dest));
}

function watch() {
  gulp.watch(paths.styles.watch, styles);
  gulp.watch(paths.scripts.watch, scripts);
}

exports.styles = styles;
exports.scripts = scripts;
exports.watch = watch;
exports.default = gulp.series(gulp.parallel(styles, scripts), watch);