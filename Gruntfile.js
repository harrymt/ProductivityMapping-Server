module.exports = function (grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json')
    });

    grunt.initConfig({

        sass: {
            dist: {
                options: {
                    style: 'compressed'
                },
                files: {
                    'css/style.css': 'scss/style.scss'
                }
            }
        },

        concat: {
            options: {
                separator: ';'
            },
            dist: {
                src: ['js/jquery.min.js', 'js/app.js'],
                dest: 'js/main.min.js'
            }
        },

        uglify: {
            options: {
                mangle: false
            },
            my_target: {
                files: {
                    'js/main.min.js': ['js/main.min.js']
                }
            }
        },

        watch: {
            css: {
                files: ['scss/*.scss'],
                tasks: ['sass'],
                options: {
                    spawn: false
                }
            },

            javascript: {
                files: ['js/*.js'],
                tasks: ['concat']
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-concat'); // Concatenate JS
    grunt.loadNpmTasks('grunt-contrib-uglify'); // Minify JS
    grunt.loadNpmTasks('grunt-contrib-sass'); // Process Sass files
    grunt.loadNpmTasks('grunt-contrib-watch'); // On file update, do task
    grunt.loadNpmTasks('grunt-serve'); // Local server

    grunt.registerTask('default', ['concat', 'sass']);

};
