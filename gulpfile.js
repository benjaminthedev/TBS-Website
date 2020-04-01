const gulp = require('gulp');
const sass = require('gulp-sass');

//compile scss into css 
function style() {
    return gulp.src('./assets/css/scss/**/*.scss')
        .pipe(sass())
        .pipe(gulp.dest('./assets/css'))
}

function watch() {
    gulp.watch('./assets/css/scss/**/*.scss', style);
}

exports.style = style;
exports.watch = watch;