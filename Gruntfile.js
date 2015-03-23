module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    autoprefixer: {
      multiple_files: {
          expand: true,
          flatten: true,
          src:['build/css/*.css'],
          dest: 'build/css/'
      }
    },
    uglify: {
      build: {
          src: [
            'js/*.js',
          ],
        dest: 'build/global.<%= grunt.template.today("dd-mm-ss") %>.js'
      }
    },

    sass:{
      dist: {
        options: {
          style: 'expanded',
          sourcemap: 'none'
        },
        files: [{
          expand: true,
          src:['css/*.scss'],
          dest: 'build/css/',
          ext:'.css',
          flatten : true
        }]
      }
    },

    cssmin: {
      target: {
        files: {
          'build/global.<%= grunt.template.today("dd-mm-ss") %>.css':'build/css/*.css',
        }
      }
    },

    watch: {
      scripts: {
        files: [
            '*/*.css',
            '*/*.scss',
            '*/*/*.scss',
            'js/*.js',
        ],
        tasks: ['clean:before','sass','copy:js','copy:css','copy:fonts','autoprefixer','bell'],
        options: {
          spawn: false,
        },
      },
    },    

    copy: {
      prodfonts: {
        files: [
          {expand: true, src: [
            'fonts/*.*',
          ], 
          dest: 'build/fonts/', 
          filter: 'isFile',
          flatten : true
          },
        ],
      },
      fonts: {
        files: [
          {expand: true, src: [
            'fonts/*.*',
          ], 
          dest: 'build/css/fonts/', 
          filter: 'isFile',
          flatten : true
          },
        ],
      },
      js: {
        files: [
          {expand: true, src: [
            'js/*.js',
            'js/tool-man/*.js',
            // 'fancybox/*.js',
            // 'fancybox/helpers/*.js',
          ], 
          dest: 'build/js/', 
          filter: 'isFile',
          flatten : true
          },
        ],
      },
      css: {
        files: [
          {expand: true, src: [
            'css/*.css',
          ], 
          dest: 'build/css/', 
          filter: 'isFile',
          flatten : true
          },
        ],
      },
    },

    concat: {
      options: {
          stripBanners: true,
          banner: '/*! <%= src %> */\n',
      },
      js: {
          src: [
            'js/*.js',
          ],
          dest: 'build/global.<%= grunt.template.today("dd-mm-ss") %>.js'
      },
      css: {
          src: ['build/css/*.css'],
          dest: 'build/global.<%= grunt.template.today("dd-mm-ss") %>.css'
      }
    },

    clean: {
      before:["build/*.js","build/*.css","build/css/*.css","build/css/fonts/*.*","build/js/*.js"],
      after:["build/*.tmp.*","build/global.sass.css","build/global.cssmin.css","build/css/*.css","build/css/*.map"],
    }
  });

  // Load the plugins that provides the tasks
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-autoprefixer');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-bell');

  // Default task(s).
  grunt.registerTask('default', ['clean:before','sass','copy:js','copy:css','copy:fonts','autoprefixer','bell']);
  grunt.registerTask('prod', ['clean:before','sass','copy:js','copy:css','copy:prodfonts','autoprefixer','cssmin','uglify','clean:after','bell']);

//  grunt.registerTask('concat', ['clean:before','sass','autoprefixer','concat:css','concat:js','clean:after']);
//  grunt.registerTask('keep', ['clean:before','sass','autoprefixer','concat:css','concat:js']);



};