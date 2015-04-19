'use strict';

/**
 * Configure grunt
 */
module.exports = function(grunt) {
	// Load all grunt tasks
	require('matchdep').filter('grunt-*').forEach(grunt.loadNpmTasks);

	// Theme name
	var themename = 'kesselblech';

	// Paths
	var paths = {
		app: 'theme',
		tmp: '.tmp/content/themes/' + themename,
		dist: 'public/content/themes/' + themename
	};

	// Init configuration for grunt
	grunt.initConfig({
		// Paths
		paths: paths,

		// Watch files for changes
		watch: {
			coffee: {
				files: ['<%= paths.app %>/javascripts/{,**/}*.coffee'],
				tasks: ['newer:coffee:dist']
			},
			styles: {
				files: ['<%= paths.app %>/{,**/}*.css'],
				tasks: ['copy:styles']
			},
			less: {
				files: ['<%= paths.app %>/stylesheets/{,**/}*.less'],
				tasks: ['less:server']
			}
		},

		// Enable browserSync
		browserSync: {
			server: {
				options: {
					ui: {
						port: 9002
					},
					proxy: {
						target: '<%= php.server.options.hostname %>:<%= php.server.options.port %>'
					},
					files: [
						'<%= paths.app %>/{*,**/}*.php',
						'<%= paths.app %>/javascripts/{*,**/}*.js',
						'<%= paths.tmp %>/javascripts/{*,**/}*.js',
						'<%= paths.tmp %>/{*,**/}*.css'
					],
					snippetOptions: {
						ignorePaths: "wordpress/**",
						rule: {
							match: /<\/body>/i,
							fn: function (snippet, match) {
								return snippet + match;
							}
						}
					},
					middleware: require('serve-static')('.tmp'),
					watchTask: true,
					notify: true,
					open: true,
					port: 9000,
					logLevel: 'silent',
					ghostMode: {
						clicks: true,
						scroll: true,
						links: true,
						forms: true
					}
				}
			}
		},

		// Start local PHP development server
		php: {
			server: {
				options: {
					hostname: '127.0.0.1',
					port: 9001,
					base: require('path').resolve('public'),
					router: require('path').resolve('public/router.php'),
					keepalive: false,
					open: false,
					settings: {
						'error_log': require('path').resolve('logs/error.log'),
						'log_errors': '1',
						'date.timezone': 'Europe/Berlin'
					}
				}
			}
		},

		// Cleaning
		clean: {
			server: {
				files: [{
					dot: true,
					src: [
						'.tmp',
						'<%= paths.dist %>'
					]
				}]
			}
		},

		// LESS
		less: {
			server: {
				options: {
					paths: ['<%= paths.app %>/stylesheets'],
					dumpLineNumbers: 'comments'
				},
				files: {
					'<%= paths.tmp %>/stylesheets/main.css': '<%= paths.app %>/stylesheets/main.less'
				}
			},
			distDevelop: {
				options: {
					paths: ['<%= paths.app %>/stylesheets'],
					dumpLineNumbers: 'comments'
				},
				files: {
					'<%= paths.dist %>/stylesheets/main.css': '<%= paths.app %>/stylesheets/main.less'
				}
			},
			distMaster: {
				options: {
					paths: ['<%= paths.app %>/stylesheets'],
					cleancss: true
				},
				files: {
					'<%= paths.dist %>/stylesheets/main.css': '<%= paths.app %>/stylesheets/main.less'
				}
			}
		},

		// Symlinking
		symlink: {
			components: {
				src: '<%= paths.app %>/components',
				dest: '<%= paths.tmp %>/components'
			},
			theme: {
				src: '<%= paths.app %>',
				dest: '<%= paths.dist %>'
			}
		},

		// CoffeeScript
		coffee: {
			dist: {
				files: [{
					expand: true,
					cwd: '<%= paths.app %>/javascripts',
					src: '{,*/}*.coffee',
					dest: '<%= paths.tmp %>/javascripts',
					ext: '.js'
				}]
			},
		},

		// RequireJS
		requirejs: {
			compile: {
				options: {
					'name': 'main',
					'baseUrl': '<%= paths.tmp %>/javascripts',
					'mainConfigFile': '<%= paths.tmp %>/javascripts/main.js',
					'out': '<%= paths.dist %>/javascripts/main.js',
					'optimize': 'uglify2'
				}
			}
		},

		// Imagemin
		imagemin: {
			dist: {
				files: [{
					expand: true,
					cwd: '<%= paths.app %>/images',
					src: '{,*/}*.{png,jpg,jpeg}',
					dest: '<%= paths.dist %>/images'
				}]
			}
		},

		// SVGmin
		svgmin: {
			dist: {
				files: [{
					expand: true,
					cwd: '<%= paths.app %>/images',
					src: '{,*/}*.svg',
					dest: '<%= paths.dist %>/images'
				}]
			}
		},

		// Copy static files
		copy: {
			dist: {
				files: [{
					expand: true,
					dot: true,
					cwd: '<%= paths.app %>',
					dest: '<%= paths.dist %>',
					src: [
						'{,**/}*.{php,po,mo}',
						'*.{ico,png,txt,css}',
						'images/{,*/}*.{webp,gif}',
						'components/requirejs/require.js',
						'fonts/*.{eot,svg,ttf,woff}',
						'components/bootstrap/fonts/*.{eot,svg,ttf,woff}'
					]
				}]
			},
			styles: {
				expand: true,
				dot: true,
				cwd: '<%= paths.app %>/stylesheets',
				dest: '<%= paths.tmp %>/stylesheets/',
				src: '{,*/}*.css'
			},
			javascripts: {
				expand: true,
				dot: true,
				cwd: '<%= paths.app %>/javascripts',
				dest: '<%= paths.tmp %>/javascripts/',
				src: '{,*/}*.js'
			}
		},

		// Concurrent: Do some task at the same time to save some time
		concurrent: {
			server: [
				'symlink:theme',
				'coffee:dist',
				'copy:styles'
			],
			dist: [
				'coffee',
				'copy:styles',
				'imagemin',
				'symlink:components',
				'svgmin'
			]
		}
	});

	// Task: server (while development)
	grunt.registerTask('server', function (target) {
		grunt.task.run([
			'clean',
			'less:server',
			'concurrent:server',
			'php',
			'browserSync',
			'watch'
		]);
	});

	// Task: build (while production)
	grunt.registerTask('build', function (target) {
		var subtask = ( target === 'production' ) ? 'distMaster' : 'distDevelop';

		grunt.task.run([
			'clean',
			'copy:javascripts',
			'less:' + subtask,
			'concurrent:dist',
			'requirejs',
			'copy:dist'
		]);
	});

	// Default task
	grunt.registerTask('default', 'server');
}
