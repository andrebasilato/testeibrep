
var tempototal = 500;
var tempoAtual = 0;
var quizPerguntas_total = 0;
var quizPerguntas_atual = 0;
var quizPerguntas = Array();

$(document).ready(function(e) {
	initQuiz();
});


function initQuiz(){
	
	
	$.ajax({
		  url:"_quiz_perguntas.php",
		  type:"POST",
		  contentType:"application/json; charset=utf-8",
		  dataType:"json",
		  success: function(data){
			  tempototal = data.tempo;
			//  console.log('tempo total do quiz:' + tempototal);
			  for(var keys in data.perguntas){
				  var pergunta = '<H2 class="quiz-pergunta corpadrao">'+ data.perguntas[keys].titulo  +'</H2><div class="box-pergunta" data-quiz="'+ quizPerguntas_total + '"><small>Escolha a resposta correta</small>';
						for(var ops in data.perguntas[keys].opcoes){
							pergunta += '<a href="javascript:void(0)">'+ data.perguntas[keys].opcoes[ops] +'</a>';
						};
				   pergunta += '</div>';
				  
				  var dados = Array();
				  dados.push(data.perguntas[keys].correta);
				  dados.push(pergunta);
				  dados.push(-1);
				  dados.push(data.perguntas[keys].titulo);
				  dados.push(data.perguntas[keys].opcoes[data.perguntas[keys].correta]);
				  quizPerguntas.push(dados);
				  
				 quizPerguntas_total++; 
			  }
			  
			  quizStatus();
			  setarQuiz(0);
			  //console.log('retorno quiz:' + quizPerguntas);
			  quizBotoes();
			  
		  ativarTimer();
		  }
		})
		
		
		
}


var chavequizClick = true;
function quizNext(){
	if(chavequizClick){
		chavequizClick = false;
		quizPerguntas_atual++;
		$('#quiz-visor').fadeOut('fast',function(){
			setarQuiz(quizPerguntas_atual)
			});
	}
}


function quizPrev(){
	if(chavequizClick){
		chavequizClick = false;
		quizPerguntas_atual--;
		$('#quiz-visor').fadeOut('fast',function(){
			setarQuiz(quizPerguntas_atual)
			});
	}
}


function quizBotoes(){
	$('#quizprev').hide();
	$('#quiznext').hide();
	$('#quizfim').hide();
	if(quizPerguntas_atual >= 1){
		$('#quizprev').show();
		};
	if(quizPerguntas_atual < quizPerguntas_total-1){
		$('#quiznext').show();
	} else {
		$('#quizfim').show();
	}
}

function setarQuiz(id){
	
	var dados = quizPerguntas[id];
	var resposta = dados[0];
	var perguntas = dados[1];
	var gabarito = dados[2];
	
	
	
	
	
	//quizPerguntas_atual = id+1;
	
	$('#quiz-visor').html(perguntas);
	$('#quiz-visor').fadeIn('fast',function(){ chavequizClick = true; });
	
	$('div[data-quiz]').each(function(index, element) {
		var idpergunta = $(this).attr("data-quiz");
		$(this).children('a').each(function(index, element) {
			$(this).attr('data-index',index);
			$(this).click(function(){
					clickpergunta(idpergunta,index);
				});
		});
	});
	quizStatus();
	quizBotoes();
	clickpergunta(id,gabarito);
}

function quizStatus(){
	var quizperguntaAtual = quizPerguntas_atual + 1;
	$('.quiz-capitulos').html('PERGUNTA ' + pad(quizperguntaAtual.toString(),2) + ' / '+ pad(quizPerguntas_total.toString(),2));
}

function startQuiz(){
	
	
//	tempoAtual = tempototal;
//	ativarTimer();
	
}

var respostas = {}
function clickpergunta(idpergunta,idresposta){
	//console.log(idpergunta,idresposta);
	$('div[data-quiz="'+idpergunta+'"]').find('a').removeClass('quiz-ativo');
	
	$('div[data-quiz="'+idpergunta+'"]').find('a[data-index="'+idresposta+'"]').addClass('quiz-ativo');
	respostas[idpergunta] = idresposta;
	
	quizPerguntas[idpergunta][2] = idresposta;
	//gabarito();
}

function gabarito(){
	//console.log(respostas);
}

var timerchave;
function ativarTimer(){
	tempoAtual = tempototal;
	timerEvent();
}

function timerEvent(){
	timerchave = setTimeout(timerEvent, 1000);
	$('#quiz-tempo').html(secondsToTime(tempoAtual));
	var porcentagem = (tempoAtual * 100) / tempototal;
	tempoAtual--;
	$('#quiz-areia-um').css('height',porcentagem + '%');
	$('#quiz-areia-dois').css('height',(100 - porcentagem) + '%');
	if(tempoAtual == 0){
		clearTimeout(timerchave);
		$('#quiz-tempo').html(secondsToTime(0));
		timerOver();
	}
}

function timerOver(){
	//console.log('o tmepo acabou o que fazer???');
	
	$('#quiz-visor').fadeOut('fast',function(){ $('#quiz-visor-score').fadeIn('fast'); });
	$('.quiz-controle').fadeOut('fast');
	
	var acertos = 0;
	var erros = 0
	var respostas = '';
	for(var i=0; i< quizPerguntas.length; i++){
		//console.log(i, quizPerguntas[i][0] + ' respondido:' + quizPerguntas[i][2]);
		if(quizPerguntas[i][0] == quizPerguntas[i][2]){
			acertos++;
		} else {
			erros++;
		}
		
		
		respostas += '<div class="quiz-gabarito"><h2>'+ quizPerguntas[i][3] +'</h2><small>'+ quizPerguntas[i][4] +'</small></div>';
		
	}
	
	$('.quiz-acertos').html(acertos + ' ACERTOS');
	$('.quiz-erros').html(erros + ' ERROS');
	
	
	$('.quiz-lista-gabarito').html(respostas);
	
	//respostas
	
}



function pad (str, max) {
  return str.length < max ? pad("0" + str, max) : str;
}

function secondsToTime(secs)
{
    var hours = Math.floor(secs / (60 * 60));

    var divisor_for_minutes = secs % (60 * 60);
    var minutes = Math.floor(divisor_for_minutes / 60);

    var divisor_for_seconds = divisor_for_minutes % 60;
    var seconds = Math.ceil(divisor_for_seconds);

    var obj = {
        "h": hours,
        "m": minutes,
        "s": seconds
    };
	
	//console.log(hours.length)
	
    return pad(hours.toString(),2) +':'+ pad(minutes.toString(),2) +':'+ pad(seconds.toString(),2) ;
}

