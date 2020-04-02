const gulp = require('gulp');
const sass = require('gulp-sass');
// const uglifycss = require('gulp-uglifycss');


//compile scss into css 
gulp.task('sass'), function () {
    return gulp.src('./customStyle/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('./customStyle/new'))
}





//OLD WAY
// async function style() {
//     return gulp.src('./customStyle/custom-css.scss')
//         .pipe(sass())
//         .pipe(gulp.dest('./customStyle/css'))
// }

//Minify the new css
// gulp.task('css', function () {
//     gulp.src('./customStyle/css/*.css')
//         .pipe(uglifycss({
//             // "maxLineLen": 80,
//             "uglyComments": true
//         }))
//         .pipe(gulp.dest('./customStyle/min/'));
// });


// gulp.task('run'['style', 'css']);

// gulp.task('watch', function () {
//     gulp.watch('./customStyle/custom-css.scss', ['sass'])
//     gulp.watch('./customStyle/css/*.css', ['css'])
// });


//NOTE - to get this to work automatically just run gulp
//gulp.task('running', ['run', 'watch']);

//const mins = async () => await gulp.series(style);


// exports.style = style;
