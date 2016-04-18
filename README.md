# Study Mapper (Server)

The server side to Study Mapper. Links with the app component [Study Mapper](https://github.com/harrymt/ProductivityMapping)
 
**Built for the University of Nottingham as a final year BSc Computer Science project.**

In order to build this project, you must place an `Environment_variable.class.php` in the outer directory with the following structure.
This must contain a [Google Maps Javascript API key](https://developers.google.com/maps/signup) for the front end to be displayed.

`Environment_variable.class.php`

```php

<?php
    class Environment_variable {
        // Mysql database settings
        public static $MYSQL_USERNAME = ""; // Username, e.g. 'root'
        public static $MYSQL_PASSWORD = ""; // Password, e.g. 'root'
        public static $MYSQL_DATABASE = ""; // Database name, e.g. 'mydatabase'
        public static $MYSQL_HOST = ""; // Hostname, e.g. 'localhost''

        public static $LOCAL_SERVER_URL = ""; // Folder of API, e.g. http://localhost/~username/folder/api/v1
        public static $SERVER_URL = "";  // This server API url, e.g. http://harrys_server.com/~username/folder/api/v1

        public static $API_KEY = ""; // A unique ID to be passed in with every call, e.g. 'my_secret_password'
        public static $API_GOOGLE_KEY = ""; // Google Maps javascript API key
    }
?>

```

Uses the following technologies.

- [Google Maps](https://developers.google.com/maps/documentation/javascript)
- [Bourbon](http://bourbon.io/)
- [SASS](http://sass-lang.com/)
- [Font Awesome](https://fortawesome.github.io/Font-Awesome/)
- [GruntJS](http://gruntjs.com/)



### How to Build

- Uses [GruntJS](http://gruntjs.com/) as a task runner to build the SCSS and JS files.
- Run `sudo npm install` in main directory
- Run `grunt watch` to watch folders so that SCSS compiles to CSS & JS combines, or just run `grunt` to build all.


## Gruntfile.js

The following Grunt tasks are used they can be found [here](Gruntfile.js).

```javascript
grunt.loadNpmTasks('grunt-contrib-concat'); // Concatenate JS
grunt.loadNpmTasks('grunt-contrib-sass'); // Process Sass files
grunt.loadNpmTasks('grunt-contrib-watch'); // On file update, do task

```
