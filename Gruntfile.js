module.exports = function(grunt) {

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        cssmin: {
            options: {
                shorthandCompacting: false,
                roundingPrecision: -1
            },
            target: {
                files: [{
                    src: ['public/css/main.css'],
                    dest: 'public/css/dist/main.min.css'
                }, {
                    src: [
                        'public/css/bootstrap.min.css',
                        'public/css/font-awesome.css',
                    ],
                    dest: 'public/css/dist/libs.min.css'
                }]
            }
        },

        uglify: {
            options: {
                banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
            },
            build: {
                files: [{
                    src: 'public/js/main.js',
                    dest: 'public/js/dist/main.min.js'
                }, {
                    src: 'public/js/functions.js',
                    dest: 'public/js/dist/functions.min.js'
                }, {
                    src: [
                        'public/js/bootstrap.min.js'
                    ],
                    dest: 'public/js/dist/libs.min.js'
                }]
            }

        },
        jshint: {
            all: ['public/js/main.js']
        },
        watch: {
            src: {
                files: ['public/js/main.js', 'public/js/functions.js', 'public/css/main.css'],
                tasks: ['default'],
            }
        }
    });

    // Load the plugin that provides the "uglify" task.
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-watch');

    // Default task(s).
    grunt.registerTask('default', ['uglify', 'cssmin', 'jshint', 'watch']);

};
