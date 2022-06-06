module.exports = function(grunt) {

    // Configurações do nosso projeto
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        
        watch: {
            css_app: {
                files: ['app/app/assets/css/*', 'app/assets/bootstrap_v2/css/*'],
                tasks: ['concat','cssmin']
            },
            js_app: {
                files: ['app/assets/js/*', 'app/assets/bootstrap_v2/js/*'],
                tasks: ['concat','uglify']
            }			
        },
		
		concat: {
            css_desktop: {
                src: [
                    'app/assets/bootstrap_v2/css/bootstrap.css', 
					'app/assets/font_awesome/css/font-awesome.min.css', 
					'app/assets/css/jquery_ui_1.8.16.custom.css',					
					'app/assets/css/oraculo.css',
					'app/assets/css/menu.css',
					'app/assets/plugins/facebox/src/facebox.css',					
					'app/assets/css/oraculo.desktop.css'
                ],
                dest: 'app/assets/min/aplicacao.desktop.css'
            },
            css_mobile: {
                src: [
                    'app/assets/bootstrap_v2/css/bootstrap.css',
					'app/assets/font_awesome/css/font-awesome.min.css',
					'app/assets/css/jquery_ui_1.8.16.custom.css',
					'app/assets/css/oraculo.css',
					'app/assets/css/menu.css',
					'app/assets/plugins/facebox/src/facebox.css',
					'app/assets/css/oraculo.mobile.css'
                ],
                dest: 'app/assets/min/aplicacao.mobile.css'
            },	
            css_aluno: {
                src: [
                    'app/assets/aluno_novo/css/normalize.css',
					'app/assets/aluno_novo/bootstrap/css/bootstrap.css',
					'app/assets/aluno_novo/bootstrap/css/bootstrap-responsive.css',
					'app/assets/aluno_novo/css/main.css'
                ],
                dest: 'app/assets/min/aplicacao.aluno.css'
            },
            js_desktop: {
                src: [	'app/assets/js/jquery.1.7.1.min.js',
						'app/assets/plugins/facebox/src/facebox.js',
						'app/assets/js/jquery.maskMoney.js',
						'app/assets/js/validation.js',
						'app/assets/js/jquery.maskedinput_1.3.js',
						//'app/assets/bootstrap_v2/js/bootstrap-transition.js',
						'app/assets/bootstrap_v2/js/bootstrap-alert.js',
						'app/assets/bootstrap_v2/js/bootstrap-modal.js',
						'app/assets/bootstrap_v2/js/bootstrap-dropdown.js',
						//'app/assets/bootstrap_v2/js/bootstrap-scrollspy.js',
						'app/assets/bootstrap_v2/js/bootstrap-tab.js',
						'app/assets/bootstrap_v2/js/bootstrap-tooltip.js',
						//'app/assets/bootstrap_v2/js/bootstrap-popover.js',
						'app/assets/bootstrap_v2/js/bootstrap-button.js',
						'app/assets/bootstrap_v2/js/bootstrap-collapse.js',
						//'app/assets/bootstrap_v2/js/bootstrap-carousel.js',
						//'app/assets/bootstrap_v2/js/bootstrap-typeahead.js',
						'app/assets/js/construtor.js'
                ],
                dest: 'app/assets/min/aplicacao.desktop.js'
            },
            js_mobile: {
                src: [	'app/assets/js/jquery.1.7.1.min.js',
						'app/assets/plugins/facebox/src/facebox.js',
						'app/assets/js/jquery.maskMoney.js',
						'app/assets/js/validation.js',
						'app/assets/js/jquery.maskedinput_1.3.js',
						//'app/assets/bootstrap_v2/js/bootstrap-transition.js',
						'app/assets/bootstrap_v2/js/bootstrap-alert.js',
						'app/assets/bootstrap_v2/js/bootstrap-modal.js',
						'app/assets/bootstrap_v2/js/bootstrap-dropdown.js',
						//'app/assets/bootstrap_v2/js/bootstrap-scrollspy.js',
						'app/assets/bootstrap_v2/js/bootstrap-tab.js',
						'app/assets/bootstrap_v2/js/bootstrap-tooltip.js',
						//'app/assets/bootstrap_v2/js/bootstrap-popover.js',
						'app/assets/bootstrap_v2/js/bootstrap-button.js',
						'app/assets/bootstrap_v2/js/bootstrap-collapse.js',
						//'app/assets/bootstrap_v2/js/bootstrap-carousel.js',
						//'app/assets/bootstrap_v2/js/bootstrap-typeahead.js',
						'app/assets/js/construtor.js'
                ],
                dest: 'app/assets/min/aplicacao.mobile.js'
            },	
            js_aluno: {
                src: [	'app/assets/aluno_novo/js/jquery-1.10.2.min.js',
						'app/assets/aluno_novo/js/jquery-migrate-1.2.1.min.js',
						'app/assets/aluno_novo/js/jquery.cycle2.min.js',
						'app/assets/aluno_novo/js/vendor/modernizr-2.6.2.min.js',
						'app/assets/aluno_novo/js/prefixfree.min.js',
						'app/assets/aluno_novo/js/respond.min.js',
						'app/assets/aluno_novo/bootstrap/js/bootstrap.min.js',
						'app/assets/aluno_novo/js/main.js',
						'app/assets/aluno_novo/js/svgcheckbx.js'
                ],
                dest: 'app/assets/min/aplicacao.aluno.js'
            },				
		},
		
        cssmin: {
            desktop: {
                options: {
					banner: "/*" +
							" * -------------------------------------------------------\n" +
							" * Projeto: <%= pkg.name %>\n" +
							" * Versão: <%= pkg.version %>\n" +
							" * Author: <%= pkg.author.name %> (<%= pkg.author.email %>)\n" +
							" *\n" +
							" * Copyright (c) <%= grunt.template.today(\"yyyy\") %> <%= pkg.title %>\n" +
							" * -------------------------------------------------------\n" +
							" */\n",
					keepSpecialComments: 0
                },
                expand: true,
                files: {
                    'app/assets/min/aplicacao.desktop.min.css': ['app/assets/min/aplicacao.desktop.css']
                }
            },
            mobile: {
                options: {
					banner: "/*" +
							" * -------------------------------------------------------\n" +
							" * Projeto: <%= pkg.name %>\n" +
							" * Versão: <%= pkg.version %>\n" +
							" * Author: <%= pkg.author.name %> (<%= pkg.author.email %>)\n" +
							" *\n" +
							" * Copyright (c) <%= grunt.template.today(\"yyyy\") %> <%= pkg.title %>\n" +
							" * -------------------------------------------------------\n" +
							" */\n",
					keepSpecialComments: 0
                },
                expand: true,
                files: {
                    'app/assets/min/aplicacao.mobile.min.css': ['app/assets/min/aplicacao.mobile.css']
                }
            },
            aluno: {
                options: {
					banner: "/*" +
							" * -------------------------------------------------------\n" +
							" * Projeto: <%= pkg.name %>\n" +
							" * Versão: <%= pkg.version %>\n" +
							" * Author: <%= pkg.author.name %> (<%= pkg.author.email %>)\n" +
							" *\n" +
							" * Copyright (c) <%= grunt.template.today(\"yyyy\") %> <%= pkg.title %>\n" +
							" * -------------------------------------------------------\n" +
							" */\n",
					keepSpecialComments: 0
                },
                expand: true,
                files: {
                    'app/assets/min/aplicacao.aluno.min.css': ['app/assets/min/aplicacao.aluno.css']
                }
            }			
        },
		

		uglify: {
            options: {
                mangle: false,
				banner: "/*" +
						" * -------------------------------------------------------\n" +
						" * Projeto: <%= pkg.name %>\n" +
						" * Versão: <%= pkg.version %>\n" +
						" * Author: <%= pkg.author.name %> (<%= pkg.author.email %>)\n" +
						" *\n" +
						" * Copyright (c) <%= grunt.template.today(\"yyyy\") %> <%= pkg.title %>\n" +
						" * -------------------------------------------------------\n" +
						" */\n",
            },
            desktop: {
                files: {
                    'app/assets/min/aplicacao.desktop.min.js': ['app/assets/min/aplicacao.desktop.js']
                }
            },
            mobile: {
                files: {
                    'app/assets/min/aplicacao.mobile.min.js': ['app/assets/min/aplicacao.mobile.js']
                }
            },
            mobile: {
                files: {
                    'app/assets/min/aplicacao.aluno.min.js': ['app/assets/min/aplicacao.aluno.js']
                }
            }
        },		
											
		
    });

    // Carregando os plugins
	/*
    grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-imagemin');
	grunt.loadNpmTasks('grunt-contrib-htmlmin');
	*/
	require('load-grunt-tasks')(grunt);



    // Tasks
    // Padrão é obrigatória
    grunt.registerTask('default', ['concat','cssmin','uglify']);
	grunt.registerTask('watch', ['concat','cssmin','uglify','watch']); 

};