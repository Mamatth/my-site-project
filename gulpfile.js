'use strict';

const gulp = require('gulp');
const sass = require('gulp-sass');
const notify = require('gulp-notify');
const sourcemaps = require('gulp-sourcemaps');
const gulpif = require('gulp-if');

var isProd = false;

const pathsCss = [
    "themes/*",
    "modules/*"
];

const tasks = {
  css: [],
  watchcss: []
};

pathsCss.forEach(function(path){
  let taskNameCss = "css-" + path.replace("/", "-"),
      taskNameCssWatch = taskNameCss + ":watch",
      pathRoot = './project/' + path + "/",
      pathSass = pathRoot + "sass/**/*.scss",
      pathCss = pathRoot + "css",
      pathSourceMaps = pathRoot + "css";

  gulp.task(taskNameCss, function () {
    return gulp.src(pathSass)
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(gulpif(!isProd, sourcemaps.write()))
        .pipe(gulp.dest(pathCss))
        .pipe(notify("<%= file.relative %> : Created"));;
  });

  gulp.task(taskNameCssWatch, function () {
    gulp.watch(pathSass, [taskNameCss]);
  });

  tasks.css.push(taskNameCss);
  tasks.watchcss.push(taskNameCssWatch);
});

gulp.task('set-prod', function(){
  isProd = true;
});

// Default tasks
gulp.task('css', tasks.css);

gulp.task('css:prod', ['set-prod', 'css']);

gulp.task('css:watch', tasks.watchcss);

// Permet de lancer en une seule commande la compilation des fichiers sass en css
gulp.task('default', ['css', 'css:watch']);