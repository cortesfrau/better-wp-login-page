//---------------//
//--- Imports ---//
//---------------//
import {src, dest, watch, series, parallel} from 'gulp';
import cleanCss from 'gulp-clean-css';
import gulpif from 'gulp-if';
import postcss from 'gulp-postcss';
import sourcemaps from 'gulp-sourcemaps';
import autoprefixer from 'autoprefixer';
import named from 'vinyl-named';
import webpack from 'webpack-stream';
import del from 'del';
import yargs from 'yargs';
import dartSass from 'sass';
import gulpSass from 'gulp-sass';
const sass = gulpSass(dartSass);
const PRODUCTION = yargs.argv.prod;

//-------------//
//--- Tasks ---//
//-------------//

// Styles Task
export const styles = () => {
  return src(['src/scss/bwplp-bundle.scss'])
    .pipe(gulpif(!PRODUCTION, sourcemaps.init()))
    .pipe(sass().on('error', sass.logError))
    .pipe(gulpif(PRODUCTION, postcss([autoprefixer])))
    .pipe(gulpif(PRODUCTION, cleanCss({compatibility:'ie8'})))
    .pipe(gulpif(!PRODUCTION, sourcemaps.write()))
    .pipe(dest('dist/css'));
}

// Admin Styles Task
export const adminStyles = () => {
  return src(['src/scss/bwplp-admin.scss'])
    .pipe(gulpif(!PRODUCTION, sourcemaps.init()))
    .pipe(sass().on('error', sass.logError))
    .pipe(gulpif(PRODUCTION, postcss([autoprefixer])))
    .pipe(gulpif(PRODUCTION, cleanCss({compatibility:'ie8'})))
    .pipe(gulpif(!PRODUCTION, sourcemaps.write()))
    .pipe(dest('dist/css'));
}

// Images Task
export const img = () => {
  return src('src/img/**/*.{jpg,jpeg,png}')
    .pipe(dest('dist/img'));
}

// Copy Task
export const copy = () => {
  return src(['src/**/*','!src/{img,js,scss}','!src/{img,js,scss}/**/*'])
    .pipe(dest('dist'));
}

// Clean Task
export const clean = () => del(['dist']);

// Scripts Task
export const scripts = () => {
  return src(['src/js/bwplp-bundle.js'])
    .pipe(named())
    .pipe(webpack({
      module: {
        rules: [
          {
            test: /\.js$/,
            use: {
              loader: 'babel-loader',
              options: {
                presets: []
              }
            }
          }
        ]
      },
      mode: PRODUCTION ? 'production' : 'development',
      devtool: !PRODUCTION ? 'inline-source-map' : false,
      output: {
        filename: '[name].js',
      },
      externals: {
        jquery: 'jQuery'
      },
    }))
    .pipe(dest('dist/js'));
}

// Admin Scripts Task
export const adminScripts = () => {
  return src(['src/js/bwplp-admin.js'])
    .pipe(named())
    .pipe(webpack({
      module: {
        rules: [
          {
            test: /\.js$/,
            use: {
              loader: 'babel-loader',
              options: {
                presets: []
              }
            }
          }
        ]
      },
      mode: PRODUCTION ? 'production' : 'development',
      devtool: !PRODUCTION ? 'inline-source-map' : false,
      output: {
        filename: '[name].js',
      },
      externals: {
        jquery: 'jQuery'
      },
    }))
    .pipe(dest('dist/js'));
}

// Watch Task
export const watchForChanges = () => {
  watch('src/scss/**/*.scss', parallel(styles, adminStyles));
  watch('src/js/**/*.js', parallel(scripts, adminScripts));
  watch('src/img/**/*.{jpg,jpeg,png}', img);
  watch(['src/**/*','!src/{img,js,scss}','!src/{img,js,scss}/**/*'], copy);
}

// Dev & Build Tasks
export const dev = series(clean, img, parallel(styles, adminStyles, copy, scripts, adminScripts), watchForChanges);
export const build = series(clean, img, parallel(styles, adminStyles, copy, scripts, adminScripts));
