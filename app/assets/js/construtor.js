	function exibeLoading() {
		
		var htmlLoading = '<div id="load_facebox" style="display:none; margin-top:180px;"><img src="/assets/img/ajax_loader.png" width="64" height="64"></div>';
		showOverlay();	
		$('body').append(htmlLoading);
		$('#load_facebox').show().css({
		  top:	getPageScroll()[1] + (getPageHeight() / 10),
		  left:	$(window).width() / 2 - ($('#facebox .popup').outerWidth() / 2)
		});
		$('#facebox').css('left', $(window).width() / 2 - ($('#facebox .popup').outerWidth() / 2));	
		
	}
	
	function fechaLoading(){

		$('#facebox_overlay').fadeOut(200, function(){
		  $("#facebox_overlay").removeClass("facebox_overlayBG")
		  $("#facebox_overlay").addClass("facebox_hide")
		  $("#facebox_overlay").remove()
		});
		
	}

  // getPageScroll() by quirksmode.com
  function getPageScroll() {
    var xScroll, yScroll;
    if (self.pageYOffset) {
      yScroll = self.pageYOffset;
      xScroll = self.pageXOffset;
    } else if (document.documentElement && document.documentElement.scrollTop) {	 // Explorer 6 Strict
      yScroll = document.documentElement.scrollTop;
      xScroll = document.documentElement.scrollLeft;
    } else if (document.body) {// all other Explorers
      yScroll = document.body.scrollTop;
      xScroll = document.body.scrollLeft;
    }
    return new Array(xScroll,yScroll)
  }

  // Adapted from getPageSize() by quirksmode.com
  function getPageHeight() {
    var windowHeight
    if (self.innerHeight) {	// all except Explorer
      windowHeight = self.innerHeight;
    } else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
      windowHeight = document.documentElement.clientHeight;
    } else if (document.body) { // other Explorers
      windowHeight = document.body.clientHeight;
    }
    return windowHeight
  }	
  
  function showOverlay() {
	  
    if ($('#facebox_overlay').length == 0)
      $("body").append('<div id="facebox_overlay" class="facebox_hide"></div>')

    $('#facebox_overlay').hide().addClass("facebox_overlayBG")
      .css('opacity', $.facebox.settings.opacity)
      //.click(function() { $(document).trigger('close.facebox') })
      .fadeIn(200)
    return false
  }
  
  
  function abrePopup(theURL,winName,features) { //v2.0
	window.open(theURL,winName,features);
  } 
  
  function number_format( number, decimals, dec_point, thousands_sep ) {
    // %     nota 1: Para 1000.55 retorna com precisão 1 no FF/Opera é 1,000.5, mas no IE é 1,000.6
    // *     exemplo 1: number_format(1234.56);
    // *     retorno 1: '1,235'
    // *     exemplo 2: number_format(1234.56, 2, ',', ' ');
    // *     retorno 2: '1 234,56'
    // *     exemplo 3: number_format(1234.5678, 2, '.', '');
    // *     retorno 3: '1234.57'
    // *     exemplo 4: number_format(67, 2, ',', '.');
    // *     retorno 4: '67,00'
    // *     exemplo 5: number_format(1000);
    // *     retorno 5: '1,000'
    // *     exemplo 6: number_format(67.311, 2);
    // *     retorno 6: '67.31'
 
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function (n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
	// Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	if (s[0].length > 3) {
	  s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || '').length < prec) {
	  s[1] = s[1] || '';
	  s[1] += new Array(prec - s[1].length + 1).join('0');
	}
	return s.join(dec);
}

function retornarDiferencaDatas(data_1, data_2) {
	if (data_1 == null || data_2 == null)
		return false;
		
	//dates in js are counted from 0, so 05 is june
	var mes_data_1 = data_1.substr(3, 2);
	if (mes_data_1 > 1)
		mes_data_1 = (mes_data_1 - 1);
		
	var mes_data_2 = data_2.substr(3, 2);
	if (mes_data_2 > 1)
		mes_data_2 = (mes_data_2 - 1);
	
	data_1 = new Date(data_1.substr(6, 7), mes_data_1, data_1.substr(0, 2));
	data_2 = new Date(data_2.substr(6, 7), mes_data_2, data_2.substr(0, 2));
	
	var diff = Math.floor(data_2.getTime() - data_1.getTime());
	var dia = 1000* 60 * 60 * 24;

	return Math.floor(diff/dia);
}

function validaIntervaloDatasUmAno(id_data_inicial, id_data_final) {	
	data_1 = document.getElementById(id_data_inicial).value;
	data_2 = document.getElementById(id_data_final).value;
	diferenca = retornarDiferencaDatas(data_1, data_2);
	if (diferenca > 365) {
		alert('Intervalo entre as datas não pode ser maior que um ano.');
		document.getElementById(id_data_inicial).value = '';
		document.getElementById(id_data_final).value = '';					
	}
}

function validaIntervaloDatasTresMeses(id_data_inicial, id_data_final) {	
	data_1 = document.getElementById(id_data_inicial).value;
	data_2 = document.getElementById(id_data_final).value;
	diferenca = retornarDiferencaDatas(data_1, data_2);
	if (diferenca > 90) {
		alert('Intervalo entre as datas não pode ser maior que um ano.');
		document.getElementById(id_data_inicial).value = '';
		document.getElementById(id_data_final).value = '';					
	}
}

function validaIntervaloDatasUmAnoSemDia(id_data_inicial, id_data_final) {	
	data_1 = '01/' + document.getElementById(id_data_inicial).value;
	data_2 = '01/' + document.getElementById(id_data_final).value;
	diferenca = retornarDiferencaDatas(data_1, data_2);
	if (diferenca > 365) {
		alert('Intervalo entre as datas não pode ser maior que um ano.');
		document.getElementById(id_data_inicial).value = '';
		document.getElementById(id_data_final).value = '';					
	}
}

function number_format(number, decimals, dec_point, thousands_sep) {
	// discuss at: http://phpjs.org/functions/number_format/
	// original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
	// improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// improved by: davook
	// improved by: Brett Zamir (http://brett-zamir.me)
	// improved by: Brett Zamir (http://brett-zamir.me)
	// improved by: Theriault
	// improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// bugfixed by: Michael White (http://getsprink.com)
	// bugfixed by: Benjamin Lupton
	// bugfixed by: Allan Jensen (http://www.winternet.no)
	// bugfixed by: Howard Yeend
	// bugfixed by: Diogo Resende
	// bugfixed by: Rival
	// bugfixed by: Brett Zamir (http://brett-zamir.me)
	// revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
	// revised by: Luke Smith (http://lucassmith.name)
	// input by: Kheang Hok Chin (http://www.distantia.ca/)
	// input by: Jay Klehr
	// input by: Amir Habibi (http://www.residence-mixte.com/)
	// input by: Amirouche
	// example 1: number_format(1234.56);
	// returns 1: '1,235'
	// example 2: number_format(1234.56, 2, ',', ' ');
	// returns 2: '1 234,56'
	// example 3: number_format(1234.5678, 2, '.', '');
	// returns 3: '1234.57'
	// example 4: number_format(67, 2, ',', '.');
	// returns 4: '67,00'
	// example 5: number_format(1000);
	// returns 5: '1,000'
	// example 6: number_format(67.311, 2);
	// returns 6: '67.31'
	// example 7: number_format(1000.55, 1);
	// returns 7: '1,000.6'
	// example 8: number_format(67000, 5, ',', '.');
	// returns 8: '67.000,00000'
	// example 9: number_format(0.9, 0);
	// returns 9: '1'
	// example 10: number_format('1.20', 2);
	// returns 10: '1.20'
	// example 11: number_format('1.20', 4);
	// returns 11: '1.2000'
	// example 12: number_format('1.2000', 3);
	// returns 12: '1.200'
	// example 13: number_format('1 000,50', 2, '.', ' ');
	// returns 13: '100 050.00'
	// example 14: number_format(1e-8, 8, '.', '');
	// returns 14: '0.00000001'

	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	var n = !isFinite(+number) ? 0 : +number,
		prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
		dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
		s = '',
		toFixedFix = function(n, prec) {
			var k = Math.pow(10, prec);
			return '' + (Math.round(n * k) / k).toFixed(prec);
		};

	// Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || '').length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}
	
	return s.join(dec);
}
 