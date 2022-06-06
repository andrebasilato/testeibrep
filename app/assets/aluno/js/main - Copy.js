
$(document).ready(function(e) {
    
	init();
	
	$(window).resize(function(){
		
		Organizar();
		
	});
	

	Organizar();
	menuApoio(0);
	hidetoolTip();
	initConteudoTip();
	initSubmenuTip();
	initCombo();
	
	$("a[data-rjtp^='menu']").each(function(index, element) {
        
		$(this).mouseover(function(){
			var titulo = $(this).attr('data-titulo');
			var descricao = $(this).attr('data-descricao');
				showtoolTip($(this),titulo,descricao);
			}).mouseout(function(){
				hidetoolTip();
			});
		//
    });
	
	$('.boxcomentario').hide();
	$('.boxrota').hide();
	$('.combocurso-lista').hide();
	
	var previousScroll = 0;
	var posScroll = 0;
	$('.sidebar-anotacoes-conteudo').scroll(function(event){
	var currentScroll = $(this).scrollTop();
       if (currentScroll > previousScroll){
           //console.log('down');
		  
		   
       } else {
          //console.log('up');
       }
	    $('.sidebar-anotacoes-conteudo').css('background-position','10px '+ (-1*currentScroll)+'px')
       previousScroll = currentScroll;
		
	});
	
});

function showtoolTip(obj,titulo,desc){
	$('.avatooltip').show();
	$('.avatooltip').css('top',obj.offset().top);
	$('.avatooltip').css('left',obj.offset().left + obj.width + 10);
	$('.avatooltip strong').html(titulo);
	$('.avatooltip small').html(desc);
	
}

function hidetoolTip(){
	$('.avatooltip').hide();
}

function Organizar(){
	
	
	
	
	
	if(chaveFullscreen){
		
		var widthTotal = $('.content').width();
		var altura =  $(window).innerHeight() - ( $('.topo').height() - 61 );
		$('#coluna-dados').width(widthTotal);//},velocidade,'easeOutCubic');
		$('.statusbar').width(widthTotal);//},velocidade,'easeOutCubic');
		$('.conteudo .area').height(altura-100);//},velocidade,'easeOutCubic');
		$('.conteudo').height(altura);//},velocidade,'easeOutCubic');
	
	} else {
		var altura =  $(window).innerHeight() - $('.conteudo').offset().top ;
		
		$('.conteudo').height(altura);
		var larguraConteudo = $('.conteudo').width() - ( $('.coluna-menu').width() + $('.coluna-apoio').width() ) -20;
		var largura = $('.conteudo').width() - ( $('.coluna-menu').width() + $('.coluna-apoio').width() ) -30;
		if(chaveMenuApoio){
			largura = $('.conteudo').width() - ( $('.coluna-menu').width() + 35 ) -30;
		}
		
		//var dataset = -10;
		//	console.log($(this).attr('data-size'));
		console.log( $('.coluna-dados').attr('data-size') );
		
			if($('.coluna-dados').attr('data-size')){
				console.log(largura);
				larguraConteudo -= $('.coluna-dados').attr('data-size');
				console.log(largura);
			}
		
		$('#coluna-dados').width(largura);
		
		$('#coluna-conteudo').width(larguraConteudo);
		
		
		$('.statusbar').width( $('.conteudo').width()  - (  $('.coluna-menu').width() + 20) );
		$('.apoio-content').height(altura - 155);
		ajustarSidebar();
		$('.area').each(function(index, element) {
			var altura = $(this).parent().height()-100;
			$(this).height(altura);
    	});
		$('.area-conteudo').each(function(index, element) {
			
			var altura = $(this).parent().height() - 10;
			$(this).height(altura);
    	});
		
	}

	
	ajustarConteudo();
	montagemMenu();
	
}

function montagemMenu(){

	var areamenu = $('.coluna-menu .area').height();
	var totalmenu = Math.floor(areamenu / 60);
	var chaveDrop = false;
	var menus =new Array();
	$('.coluna-menu .area .menu-box').children('.icomenu').each(function(index, element) {
        
		if(index < totalmenu-2){
			$(this).show();
		} else {
			$(this).hide();
			menus.push($(this));
			chaveDrop = true;
		}
    });
	$('.btdrop').hide();
	
	if(chaveDrop){
		$('.btdrop').show();
	}
	
	$('.coluna-menu .area').height(totalmenu*60)
	
	var submenuY = $('.btdrop a').offset().top;
	$('.submenu').css('top',submenuY + 'px');
	
	$('.submenu ul').html('');
	var alturafinal = 0;
	for(var i =0; i<  menus.length; i++){
		alturafinal += 46;
		
		$('.submenu ul').append('<li><a class="corpadrao" onMouseOver="javascript:submenuTipsOver()" onMouseOut="javascript:submenuTipsOut()" href="'+ $(menus[i]).children().attr('href') +'"><span class="'+ $(menus[i]).children().attr('class') + '"></span>'+$(menus[i]).children().attr('data-titulo')+'<small>'+ $(menus[i]).children().attr('data-descricao') + '</small></a</li>');
		//console.log('criando o menu');
	}
	
	$('.submenu').height(alturafinal);
	$('.submenu').css('margin-top','-'+((alturafinal/2)-20)+ 'px');
}




function log(texto){
	console.log('debug:' + texto);
}


function showComboops(){
	if(chavecombo){
		$('.combocurso-lista').children().each(function(index, element) {
				$(this).stop().animate({opacity:1},200);
		});
	};
}
function hideComboops(id){
	if(chavecombo){
	$('.combocurso-lista').children().each(function(index, element) {
		if(index != id){
			$(this).stop().animate({opacity:.5},200);
		};
	});
	};
}

function init(){
	$('.coluna-dados').css('left',"80px");
	
	/*
	$('.menu-principal').children().each(function(index, element) {
        $(this).children('a').mouseover(function(){
				var bg = $(this).parent().children('.bg-menu');
				bg.stop().animate({height:25},200,'easeOutCubic');
			}).mouseout(function(){
				var bg = $(this).parent().children('.bg-menu');
				bg.stop().animate({height:3},200,'easeOutCubic');	
		});
    });
	*/
	
}


/* --------------------------  */
var chaveconteudoTip = false;
var timeConteudoTip;

function menuPrincipal(){
	if(chaveconteudoTip){
		chaveconteudoTip = false;
		fecharConteudoTip();
		timeConteudoTip = null; 
	} else {
		chaveconteudoTip = true;
		abrirConteudoTip();
		timeConteudoTip = setTimeout(fecharConteudoTip,5000);
	};
}

function initConteudoTip(){
	$('#menuTips a').each(function(index, element) {
        $(this).mouseover( function(){ 
			acaoMenuTip(timeConteudoTip,1);
		} ).mouseout( function(){
			acaoMenuTip(timeConteudoTip,0);
		} );
    });
	
	
	fecharConteudoTip();
}



function acaoMenuTip(tipo,chave){
	
	if(chave){
		if(timeConteudoTip){
			clearTimeout(timeConteudoTip);
		}
	} else {
		timeConteudoTip = setTimeout(fecharConteudoTip,1000);
	}
	
}

function abrirConteudoTip(){
	chaveconteudoTip = true;
	var menuposY = $('#menuBt-Tip').offset().top + 25;
	var menuposX = $('#menuBt-Tip').offset().left -60;

	$('.menuConteudo').css('top',menuposY);
	$('.menuConteudo').css('left',menuposX);
	$('.menuConteudo').fadeIn('fast');

}

function fecharConteudoTip(){
	$('.menuConteudo').fadeOut('fast');
	chaveconteudoTip = false;
}


/* ---------------------------  */





var chaveSubmenuTip = false;


function submenuActive(){
	if(chaveconteudoTip){
		chaveconteudoTip = false;
		fecharConteudoTip();
		timeConteudoTip = null; 
	} else {
		chaveconteudoTip = true;
		abrirConteudoTip();
		timeConteudoTip = setTimeout(fecharConteudoTip,1000);
	};
}

function initSubmenuTip(){
	
	$('#menu-box a').each(function(index, element) {
        $(this).mouseover( function(){ 
			acaoMenuTip(timeSubmenuTip,1);
		} ).mouseout( function(){
			acaoMenuTip(timeSubmenuTip,0);
		} );
    });
	
	$('.btdrop').mouseover( function(){ 
	//console.log('mouse over');
			abrirSubmenu();
		} ).mouseout( function(){
		//	console.log('mouse out');
			acaoSubMenuTip(timeSubmenuTip,0);
		} );
	
	fecharSubmenu();
}

function submenuTipsOver(){
	acaoSubMenuTip(timeSubmenuTip,1);
}
function submenuTipsOut(){
	acaoSubMenuTip(timeSubmenuTip,0);
}

var timeSubmenuTip;
function acaoSubMenuTip(tipo,chave){
	
	if(chave){
		if(timeSubmenuTip){
			clearTimeout(timeSubmenuTip);
		}
	} else {
		timeSubmenuTip = setTimeout(fecharSubmenu,1000);
	}
	
}

function abrirSubmenu(){
	chaveconteudoTip = true;
	$('.submenu').fadeIn('fast');
	

}

function fecharSubmenu(){
	$('.submenu').fadeOut('fast');
	chaveconteudoTip = false;
}





function initCombo(){
	$('.clickarea').each(function(index, element) {
        $(this).mouseover( function(){ 
			acaoComboTip(timeComboTip,1);
		} ).mouseout( function(){
			acaoComboTip(timeComboTip,0);
		} );
    });
	
};


var chavecombo = false;
function openCombo(){
	
	if(!chavecombo){
		abrirCombomenu();
		acaoComboTip(timeComboTip,0);
	} else {
		fecharCombomenu();
	} 
	
}

var timeComboTip;
function acaoComboTip(tipo,chave){
	if(chave){
		if(timeComboTip){
			clearTimeout(timeComboTip);
		}
	} else {
		timeComboTip = setTimeout(fecharCombomenu,1000);
	}
}

function abrirCombomenu(){
	chavecombo = true;
	$('.combocurso-lista').fadeIn('fast');
}

function fecharCombomenu(){
	$('.combocurso-lista').fadeOut('fast');
	chavecombo = false;
}




/* -------------------------------------- */

var chaveMenuApoio = false;

function acoesApoio(){
	if(chaveMenuApoio){
		chaveMenuApoio = false;
		apoioAbrir();
		fecharPrincipalSidebar();
		$('.bt-remove-apoio span').addClass('icon-arrow-1-right');
		$('.bt-remove-apoio span').removeClass('icon-arrow-1-left');
	} else {
		chaveMenuApoio = true;
		abrirPrincipalSidebar();
		apoioFechar();
		$('.bt-remove-apoio span').removeClass('icon-arrow-1-right');
		$('.bt-remove-apoio span').addClass('icon-arrow-1-left');
	}
}

var velocidade = 500;

function apoioFechar(){
	$('.coluna-apoio').animate({right:-345},velocidade,'easeOutCubic');
	$('.bt-remove-apoio').animate({'margin-left':-36},velocidade,'easeOutCubic');
}
function apoioAbrir(){
	$('.coluna-apoio').animate({right:0},velocidade,'easeOutCubic');
	$('.bt-remove-apoio').animate({'margin-left':0},velocidade,'easeOutCubic');
}

/* --------------------------------------------- */

function abrirPrincipalSidebar(){
	var widthatual = $('#coluna-dados').width() + 310;
	$('#coluna-dados').animate({width:widthatual},velocidade,'easeOutCubic');
}
function fecharPrincipalSidebar(){
	var widthatual = $('#coluna-dados').width() - 310;
	$('#coluna-dados').animate({width:widthatual},velocidade,'easeOutCubic');
}

/* ---------------------------------------------- */

var chaveFullscreen = false;

function acaoFullscreen(){
	if(chaveFullscreen){
		chaveFullscreen = false;
		fullscreenOff();
	} else {
		chaveFullscreen = true;
		fullscreenOn();
	}
}

function fullscreenOn(){
	var widthTotal = $('.content').width();
	var altura =  $(window).innerHeight() - ( $('.topo').height() - 61 );
	
	$('.coluna-menu').animate({'margin-left':-70},velocidade,'easeOutCubic');
	$('.coluna-apoio').animate({'right':-345},velocidade,'easeOutCubic');
	$('.topo').animate({'margin-top':-61},velocidade,'easeOutCubic');
	$('#coluna-dados').animate({'left':0,width:widthTotal},velocidade,'easeOutCubic');
	$('.menu-site').animate({'margin-left':0},velocidade,'easeOutCubic');
	$('.statusbar').animate({'width':widthTotal},velocidade,'easeOutCubic');
	
	
	$('.conteudo').animate({'height':altura},velocidade,'easeOutCubic');
	
	$('.conteudo .area').animate({'height':altura-100},velocidade,'easeOutCubic');
	
	
	$('.principal-texto').height( altura - 180 )
	
	
	
}


function fullscreenOff(){
	var widthTotal = $('.content').width();
	var largura = $('.conteudo').width() - ( $('.coluna-menu').width() + $('.coluna-apoio').width() ) -30;
	
	var altura =  $(window).innerHeight() -107 ;
	$('.conteudo').height(altura);
	
	$('.coluna-menu').animate({'margin-left':0},velocidade,'easeOutCubic');
	$('.coluna-apoio').animate({'right':0},velocidade,'easeOutCubic');
	$('.topo').animate({'margin-top':-10},velocidade,'easeOutCubic');
	$('#coluna-dados').animate({'left':80,width:largura},velocidade,'easeOutCubic');
	$('.menu-site').animate({'margin-left':80},velocidade,'easeOutCubic');
	$('.statusbar').animate({'width':widthTotal-80},velocidade,'easeOutCubic');
	
	$('.conteudo').animate({'height':altura},velocidade,'easeOutCubic');
	$('.conteudo .area').animate({'height':altura-100},velocidade,'easeOutCubic');
	
	$('.principal-texto').height( altura - 180 );
}



function menuApoio(id){
	$('.apoio-menu ul').children().each(function(index, element) {
        $(this).children().removeClass('corpadrao');
		if(id == index){
			$(this).children().addClass('corpadrao');
		}
    });
	switch(id){
		case 0:
			$('#setaApoio').animate({'left':87},500,'easeOutCubic');
		break;
		case 1:
			$('#setaApoio').animate({'left':185},500,'easeOutCubic');
		break;
		case 2:
			$('#setaApoio').animate({'left':288},500,'easeOutCubic');
		break;
	}
	
	$('.sidebar').animate({left:id*-345},500);
}

var chaveComentario = false;

function abrirComentario(){
	if(!chaveComentario){
		chaveComentario = true;
		$('.boxcomentario').show();	
		$('.boxcomentario').css('bottom','80px');
		$('.boxcomentario').css('opacity','0');
		$('.boxcomentario').animate({'right':2,'bottom':96,'opacity':1},1000,'easeOutCubic');
	} else {
		fecharComentario();
	}

}

function fecharComentario(){
	$('.boxcomentario').animate({'right':2,'bottom':80,'opacity':0},300,'easeOutCubic',function(){
			$('.boxcomentario').hide();	
	});
	chaveComentario = false;
}



var chaveRota = false;

function abrirRota(){
	if(!chaveRota){
		chaveRota = true;
		$('.boxrota').show();	
		$('.boxrota').css('bottom','80px');
		$('.boxrota').css('opacity','0');
		$('.boxrota').animate({'bottom':96,'opacity':1},1000,'easeOutCubic');
	} else {
		fecharRota();
	}

}

function fecharRota(){
	$('.boxrota').animate({'right':2,'bottom':80,'opacity':0},300,'easeOutCubic',function(){
			$('.boxrota').hide();	
	});
	chaveRota = false;
}


function ajustarConteudo(){
	$('.principal-texto').height( $('.coluna-dados .area').height() - 80 )
}


function ajustarSidebar(){
	$('.sidebar-box').height( $('.apoio-content').height() );
	ajustarSidebarAnotacoes();
	ajustarSidebarMaterial();
}

function ajustarSidebarAnotacoes(){
	
	var altura = $('.apoio-content').height();
	$('.sidebar-anotacoes-conteudo').height(altura-110)
	
}

function ajustarSidebarMaterial(){
	
	var altura = $('.apoio-content').height();
	$('.sidebar-material-conteudo').height(altura)
	
}



function ampliarFonte(){
	var fontAtual = parseInt($('.principal-texto').css('font-size'));
	fontAtual++;
	if(fontAtual < 19){
		$('.principal-texto').css('font-size',fontAtual + 'px');
	};
	
}

function reduzirFonte(){
	var fontAtual = parseInt($('.principal-texto').css('font-size'));
	fontAtual--;
	if(fontAtual > 10){
		$('.principal-texto').css('font-size',fontAtual + 'px');
	};
}