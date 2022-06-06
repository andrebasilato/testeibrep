// JavaScript Document - Autor (Henrique Feitosa)
function get_estrelas () {
	return document.getElementById('estrelas_avalicao').getElementsByTagName('div');	
}

function get_selecionado () { 
	estrelas = new Array();
	estrelas = get_estrelas();
	tamanho = estrelas.length;
	for (var i=0; i<tamanho; i++) {
		var classe = estrelas[i].className;
		if (estrelas[i].className.indexOf('selected') != -1)
			return estrelas[i];
	}
}

function iniciar_rating () {
	stars = new Array();
	stars = get_estrelas();
	len = stars.length;
	var selected = get_selecionado();
	if (selected != null) {
		for (var i=0; i<len; i++)
			if (stars[i].id <= selected.id) {
				if (stars[i].id == selected.id)
					stars[i].setAttribute('class', 'star-rating star-rating-on selected');
				else
					stars[i].setAttribute('class', 'star-rating star-rating-on');
			} else
				break;
	}
}

function over (elemento) {
	estrelas = new Array();
	estrelas = get_estrelas();
	tamanho = estrelas.length;
	var selecionado = get_selecionado();
	for (var i=0; i<tamanho; i++) {
		if (selecionado != null) {
			if (estrelas[i].id <= elemento.id) {
				if (estrelas[i].id != selecionado.id)
					estrelas[i].setAttribute('class', 'star-rating star-rating-hover');
				else
					estrelas[i].setAttribute('class', 'star-rating star-rating-hover selected');
			} else {
				if (estrelas[i].id != selecionado.id)
					estrelas[i].setAttribute('class', 'star-rating');
				else
					estrelas[i].setAttribute('class', 'star-rating selected');	
			}
		} else {
			if (estrelas[i].id <= elemento.id) {
					estrelas[i].setAttribute('class', 'star-rating star-rating-hover');
			} else {
					estrelas[i].setAttribute('class', 'star-rating');	
			}	
		}						
	}	
}

function out () {
	estrelas = new Array();
	estrelas = get_estrelas();
	tamanho = estrelas.length;
	var selecionado = get_selecionado();
	for (var i=0; i<tamanho; i++) {
		if (selecionado != null) {
			if (estrelas[i].id <= selecionado.id) {
				if (estrelas[i].id != selecionado.id)
					estrelas[i].setAttribute('class', 'star-rating star-rating-on');
				else
					estrelas[i].setAttribute('class', 'star-rating star-rating-on selected');
			} else {
				estrelas[i].setAttribute('class', 'star-rating');	
			}
		} else {
			estrelas[i].setAttribute('class', 'star-rating');	
		}
	}	
}

function selecionar (objeto) { 
	estrelas = new Array();
	estrelas = get_estrelas();
	tamanho = estrelas.length;
	var selecionado = get_selecionado();
	if (selecionado != null) {
	  if (selecionado.id < objeto.id)
		  selecionado.setAttribute('class', 'star-rating star-rating-hover');
	  else
		  selecionado.setAttribute('class', 'star-rating');
	}
	for (var i=0; i<tamanho; i++) {
		if (estrelas[i].id <= objeto.id) 
			estrelas[i].setAttribute('class', 'star-rating star-rating-on');
		else
			estrelas[i].setAttribute('class', 'star-rating');	
	}
	objeto.setAttribute('class', 'star-rating star-rating-on selected');
}