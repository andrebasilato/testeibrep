//Adicionando Grafico
var controle_exibicao = 0;
var numero_id_exibicao = 0;
var numero_id_exibicao_remocao = 0;
var remocao_toda_vez = false;
var cores = new Array;
cores[0] = '#cc7a98';
cores[1] = '#638db4';
cores[2] = '#e8713c';
var cor_controle = 0;
var tamanhos = new Array;
tamanhos[0] = 'small';
tamanhos[1] = 'medium';
tamanhos[2] = 'large';
var tamanho_controle = 0;


$(function () {		
	//var acessos = 0;
	var gestor = 0;
	var professor = 0;
	var vendedor = 0;
	var aluno = 0;
	
        $(document).ready(function() {
		
            Highcharts.setOptions({
                global: {
                    useUTC: false
                }
            });
        
            var chart;
            $('#container').highcharts({			
                chart: {
                    type: 'spline',
                    animation: Highcharts.svg, // don't animate in old IE
                    marginRight: 10,
                    events: {
                        load: function() {
        
                            // set up the updating of the chart each second
                            var series = this.series;
							
							
							$.getJSON('http://apresentacao.alfamaweb.com.br/novo_ava/grafico/json.php', function(json){    
								//acessos = json.onlines.total;
								gestor = json.onlines.gestor;
								professor = json.onlines.professor;
								vendedor = json.onlines.vendedor;
								aluno = json.onlines.aluno;								
							 });
							 
                            setInterval(function() {								
							
								 $.getJSON('http://apresentacao.alfamaweb.com.br/novo_ava/grafico/json.php', function(json){    
									//acessos = json.onlines.total;	
									gestor = json.onlines.gestor;
									professor = json.onlines.professor;
									vendedor = json.onlines.vendedor;
									aluno = json.onlines.aluno;
								 });
							
                                var x = (new Date()).getTime(), // current time
                                    //y = Math.random();
									//y = parseInt(acessos);
									y2 = parseInt(gestor);
									y3 = parseInt(professor);
									y4 = parseInt(vendedor);
									y5 = parseInt(aluno);
                                series[0].addPoint([x, y2], true, true);
								series[1].addPoint([x, y3], true, true);
								series[2].addPoint([x, y4], true, true);
								series[3].addPoint([x, y5], true, true);
                            }, 2500);
                        }
                    }
                },
                title: {
                    text: ''
                },
                xAxis: {
                    type: 'datetime',
                    tickPixelInterval: 150
                },
                yAxis: {
                    title: {
                        enabled: false
                    },
                    plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }]
                },
                tooltip: {
                    formatter: function() {
                            return '<b>'+
                            (this.y) +'<br/>'+
							//Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) +'<br/>'+
							//Highcharts.numberFormat(this.y, 2)
                            this.y;
                    }
                },
                legend: {
					layout: 'horizontal',
					align: 'middle',
					verticalAlign: 'bottom',
					borderWidth: 0,
					enabled: true
				},
                exporting: {
                    enabled: false
                },
                series: [
					{
						name: 'Gestor',
						data: (function() {
							// generate an array of random data
							var data = [],
								time = (new Date()).getTime(),
								i;
			
							for (i = -10; i <= 0; i++) {						
								data.push({
									x: time + i * 2000,
									y: 0
								});
							}
							return data;
						})()
					},
					{
						name: 'Professor',
						data: (function() {
							// generate an array of random data
							var data = [],
								time = (new Date()).getTime(),
								i;
			
							for (i = -10; i <= 0; i++) {						
								data.push({
									x: time + i * 2000,
									y: 0
								});
							}
							return data;
						})()
					},
					{
						name: 'Vendedor',
						data: (function() {
							// generate an array of random data
							var data = [],
								time = (new Date()).getTime(),
								i;
			
							for (i = -10; i <= 0; i++) {						
								data.push({
									x: time + i * 2000,
									y: 0
								});
							}
							return data;
						})()
					},
					{
						name: 'Aluno',
						data: (function() {
							// generate an array of random data
							var data = [],
								time = (new Date()).getTime(),
								i;
			
							for (i = -10; i <= 0; i++) {						
								data.push({
									x: time + i * 2000,
									y: 0
								});
							}
							return data;
						})()
					}
				]
            });
        });
        
    });

    //Adicionando Boxes
    function array_search(needle, haystack) {
            for(var i in haystack) {
                if(haystack[i] == needle) return i;
            }
            return false;
        }
 
        $(document).ready(function(){			
            boxes = $('.box');
            setInterval(function(){
                showBox();
            }, 500);
        });		
 
        function showBox()
		{			
		
            $.getJSON( "http://apresentacao.alfamaweb.com.br/novo_ava/grafico/json.php", function( data ) {
                Items = new Array();
				
				var total_img = 0;
 
                // $('.box').removeClass('selected').animate({opacity: 0}, 1000).removeAttr('style');
				if (controle_exibicao == 8 || remocao_toda_vez == true) {
					$('#img_'+(++numero_id_exibicao_remocao)).removeClass('selected').animate({opacity: 0}, 1000);
					remocao_toda_vez = true;					
				} else if (controle_exibicao == 9) {
					controle_exibicao = 0;
				}
 
                $.each(data.alunos, function() {				
				
						Index = Math.floor((Math.random() * $(boxes).length));
	 
						while (! array_search(Index, Items)) {
							Items.push([Index]);
						}
					
                });

                iterator = 0;
                $(data.alunos).each(function(){		
						++controle_exibicao;
						if (cor_controle > 2){
							cor_controle = 0;
						}
						if (tamanho_controle > 2){
							tamanho_controle = 0;
						}
						$($(boxes)[Items[iterator]])
							.addClass('selected')							
							.attr('id', 'img_'+(++numero_id_exibicao))
							// .animate({opacity: 1}, 1000)
							.html('<div class="'+tamanhos[tamanho_controle++]+'" style="background: '+cores[cor_controle++]+' url(\'http://ibrep.alfamaoraculo.com.br/api/get/imagens/pessoas_avatar/x/x/' + $(this)[0].avatar_servidor + '\') ;-webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover; opacity: 0.6;"></div><span><h1>' + $(this)[0].nome + "</h1>" + "<h2>" + $(this)[0].cidade + "</h2>" + "<h3>" + $(this)[0].estado + "</h3></span>")
							// .attr( "style", "background: url('http://apresentacao.alfamaweb.com.br/novo_ava/user/assets/images/1.png') ;-webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;");
							//.attr( 'style', "background: url('http://ibrep.alfamaoraculo.com.br/api/get/imagens/pessoas_avatar/x/x/" + $(this)[0].avatar_servidor + "') ;-webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover; opacity: 0;")
							.animate({opacity: 1}, 1000);
 
                    iterator++;
                });
  
           });
        }