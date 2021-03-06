module.exports = function (grunt) {
    'use strict';

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json')
    });

    grunt.initConfig({

        sass: {
            dist: {
                options: {
                    style: 'compressed',
                    loadPath: require('node-bourbon').includePaths
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
                src: [
                    'js/d3.min.js',
                    'js/d3.layout.cloud.js',
                    'js/app.js'
                ],
                dest: 'js/built/main.min.js'
            }
        },

        watch: {
            css: {
                files: ['scss/*.scss'],
                tasks: ['default'],
                options: {
                    spawn: false
                }
            },

            javascript: {
                files: ['js/*.js'],
                tasks: ['default']
            }
        }

    });

    grunt.loadNpmTasks('grunt-contrib-concat'); // Concatenate JS
    grunt.loadNpmTasks('grunt-contrib-sass'); // Process Sass files
    grunt.loadNpmTasks('grunt-contrib-watch'); // On file update, do task

    grunt.registerTask('default', ['concat', 'sass']);
};
