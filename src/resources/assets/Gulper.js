var gulp       = require('gulp');
var gulpif     = require('gulp-if');
var rev        = require('gulp-rev');
var clean      = require('gulp-clean');
var concat     = require('gulp-concat');
var rename     = require("gulp-rename");
var less       = require('gulp-less');
var sourcemaps = require('gulp-sourcemaps');
var minifyCss  = require('gulp-minify-css');
var uglify     = require('gulp-uglify');
var notify     = require("gulp-notify");
var livereload = require('gulp-livereload');
var revDel     = require('rev-del');
var path       = require('path');
var _          = require('underscore');

var Gulper = function () {
    this.paths = {
        build      : '',
        lessInclude: [],
        css        : {},
        js         : {},
        copy       : {}
    };
};

Gulper.prototype.buildPath = function (path) {
    this.paths.build = path;

    return this;
};

Gulper.prototype.setBowerComponentsDirname = function (path) {
    this.paths.bower_components = path;

    return this;
};

Gulper.prototype.registerLessInclude = function (path) {
    this.paths.lessInclude.push(path);

    return this;
};

Gulper.prototype.registerCssTask = function (source, destination) {
    return this.registerTask('css', source, destination);
};

Gulper.prototype.registerJsTask = function (source, destination) {
    return this.registerTask('js', source, destination);
};

Gulper.prototype.registerCopyTask = function (source, destination) {
    return this.registerTask('copy', source, destination);
};

Gulper.prototype.registerTask = function (type, source, destination) {
    if (!_.has(this.paths[type], destination)) {
        this.paths[type][destination] = [];
    }

    if (_.isArray(source)) {
        this.paths[type][destination] = _.union(this.paths[type][destination], source);
    } else {
        this.paths[type][destination].push(source)
    }

    return this;
};

Gulper.prototype.registerTasks = function () {
    var paths      = this.paths;
    var production = false;

    gulp.task('clean', function () {
    });

    gulp.task('copy', function () {
        _.map(paths.copy, function (source, destination) {
            gulp
                .src(source)
                .pipe(gulp.dest(path.join(paths.build, destination)))
                .pipe(livereload());
        });
    });

    gulp.task('css', function () {
        _.map(paths.css, function (source, destination) {
            gulp
                .src(source)
                .pipe(gulpif(!production, sourcemaps.init()))
                .pipe(less({paths: paths.lessInclude}))
                .pipe(concat({path: destination, cwd: ''}))
                .pipe(rename({suffix: '.min'}))
                .pipe(gulpif(production, minifyCss({keepSpecialComments: 0})))
                .pipe(rev())
                .pipe(gulpif(!production, sourcemaps.write('.')))
                .pipe(gulp.dest(paths.build))
                .pipe(livereload())
                .pipe(rev.manifest(path.join(paths.build, 'rev-manifest.json'), {base: paths.build, merge: true}))
                .pipe(revDel({dest: paths.build}))
                .pipe(gulp.dest(paths.build));
        });
    });

    gulp.task('js', function () {
        _.map(paths.js, function (source, destination) {
            gulp
                .src(source)
                .pipe(gulpif(!production, sourcemaps.init()))
                .pipe(concat({path: destination, cwd: ''}))
                .pipe(rename({suffix: '.min'}))
                .pipe(gulpif(production, uglify()))
                .pipe(rev())
                .pipe(gulpif(!production, sourcemaps.write('.')))
                .pipe(gulp.dest(paths.build))
                .pipe(livereload())
                .pipe(rev.manifest(path.join(paths.build, 'rev-manifest.json'), {base: paths.build, merge: true}))
                .pipe(revDel({dest: paths.build}))
                .pipe(gulp.dest(paths.build));
        });
    });
};

module.exports = Gulper;