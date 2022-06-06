//Adicionando Grafico
controle_exibicao = 0;
numero_id_exibicao = 0;
numero_id_exibicao_remocao = 0;
remocao_toda_vez = false;

cores = new Array;
cores[0] = '#cc7a98';
cores[1] = '#638db4';
cores[2] = '#e8713c';
cor_controle = 0;

tamanhos = new Array;
tamanhos[0] = 'small';
tamanhos[1] = 'medium';
tamanhos[2] = 'large';
tamanho_controle = 0;

ids_pessoas = new Array();

$(function () {		
	//var acessos = 0;
	var gestor = 0;
	var cfc = 0;
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
							
							
							$.getJSON('json.php', function(json){    
								//acessos = json.onlines.total;
								gestor = json.onlines.gestor;
								cfc = json.onlines.cfc;
								vendedor = json.onlines.vendedor;
								aluno = json.onlines.aluno;								
							 });
							 
                            setInterval(function() {								
							
								 $.getJSON('json.php', function(json){    
									//acessos = json.onlines.total;	
									gestor = json.onlines.gestor;
									cfc = json.onlines.cfc;
									vendedor = json.onlines.vendedor;
									aluno = json.onlines.aluno;
								 });
							
                                var x = (new Date()).getTime(), // current time
                                    //y = Math.random();
									//y = parseInt(acessos);
									y2 = parseInt(gestor);
									y3 = parseInt(cfc);
									y4 = parseInt(vendedor);
									y5 = parseInt(aluno);
                                series[0].addPoint([x, y2], true, true);
								series[1].addPoint([x, y3], true, true);
								series[2].addPoint([x, y4], true, true);
								series[3].addPoint([x, y5], true, true);
                            }, 1500);
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
									x: time + i * 1000,
									y: 0
								});
							}
							return data;
						})()
					},
					{
						name: 'CFC',
						data: (function() {
							// generate an array of random data
							var data = [],
								time = (new Date()).getTime(),
								i;
			
							for (i = -10; i <= 0; i++) {						
								data.push({
									x: time + i * 1000,
									y: 0
								});
							}
							return data;
						})()
					},
					{
						name: 'Atendente',
						data: (function() {
							// generate an array of random data
							var data = [],
								time = (new Date()).getTime(),
								i;
			
							for (i = -10; i <= 0; i++) {						
								data.push({
									x: time + i * 1000,
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
									x: time + i * 1000,
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
            }, 8000);
        });		
 
        function showBox()
		{			
		
            $.getJSON( "json.alunos.php", function( data ) {
                Items = new Array();
 
                // $('.box').removeClass('selected').animate({opacity: 0}, 1000).removeAttr('style');				
 
                $.each(data.alunos, function() {				
					Index = Math.floor((Math.random() * $(boxes).length));
 
					while (! array_search(Index, Items)) {
						Items.push([Index]);
					}					
                });

                iterator = 0;
				tempo_sleep = 0;
                $(data.alunos).each(function(){	
						tempo_sleep = (tempo_sleep+1500);
						
						function display(item, data, sleep){

							setTimeout(function(){
								inserir_fila = true;
								exibicao = ++numero_id_exibicao;
								tamanho_pessoas = ids_pessoas.length;
								for (var i_p = 1; i_p < tamanho_pessoas; i_p++) {
									if (ids_pessoas[i_p] != null && ids_pessoas[i_p] == data[0].idpessoa) {
										inserir_fila = false;
									}
								}														
								
								if (controle_exibicao == 6 || remocao_toda_vez == true) {
									$('#img_'+(++numero_id_exibicao_remocao)).removeClass('selected').animate({opacity: 0}, 500);
									remocao_toda_vez = true;					
									
									ids_pessoas[numero_id_exibicao_remocao] = null;
									
								} else if (controle_exibicao > 7) {
									controle_exibicao = 0;
								}
							
								++controle_exibicao;
								if (cor_controle > 2){
									cor_controle = 0;
								}
								if (tamanho_controle > 2){
									tamanho_controle = 0;
								}
							
								if (inserir_fila == true) {
									ids_pessoas[exibicao] = data[0].idpessoa;

									if (data[0].cidade == null)
										data[0].cidade = " ";
									if (data[0].estado == null)
										data[0].estado = " ";
										
									item
									.addClass('selected')							
									.attr('id', 'img_'+(exibicao))
									.html('<div class="'+tamanhos[tamanho_controle++]+'" style="background: '+cores[cor_controle++]+' url(\'http://ibrep.alfamaoraculo.com.br/api/get/imagens/pessoas_avatar/x/x/' + data[0].avatar_servidor + '\') ;-webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover; opacity: 0.4;"></div><span><h1>' + data[0].nome + "</h1>" + "<h2>" + data[0].cidade + "</h2>" + "<h3>" +data[0].estado + "</h3></span>")
									.animate({opacity: 1}, 1000);
								}
								
							}, sleep);
						}
						
						display($($(boxes)[Items[iterator]]), $(this), tempo_sleep);
						iterator++;
						
                });
  
           });
        }