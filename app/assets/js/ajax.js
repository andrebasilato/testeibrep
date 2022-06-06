var nomeDiv;
var req; 
function loadXMLDoc_fim(url){ 
	req = null; 
	
	// Procura por um objeto nativo (Mozilla/Safari) 
	if (window.XMLHttpRequest) { 
		req = new XMLHttpRequest(); 
		req.onreadystatechange = processReqChange_fim; 
		req.open("GET", url, true); 
		req.send(null); 
		// Procura por uma versão ActiveX (IE) 
	} else if (window.ActiveXObject) { 
		req = new ActiveXObject("Microsoft.XMLHTTP"); 
		if (req) { 
			req.onreadystatechange = processReqChange_fim; 
			req.open("GET", url, true); 
			req.send(); 
		} 
	} 
} 

function processReqChange_fim(){ 
	// apenas quando o estado for "completado" 
	if (req.readyState == 4) { 
	// apenas se o servidor retornar "OK" 
		if (req.status == 200) { 
			// procura pela div id="news" e insere o conteudo 
			// retornado nela, como texto HTML 
			document.getElementById(nomeDiv).innerHTML = req.responseText; 
		} else { 
			alert("Houve um problema ao obter os dados:\n" + req.statusText); 
		} 
	} 
} 

function solicita(div,obj){
	nomeDiv = div;
	//alert(div);
	document.getElementById(nomeDiv).innerHTML = "<img src=\"/assets/img/ajax_load.gif\"><b>Processando...</b>";
    loadXMLDoc_fim(obj);
	//alert(msg);
}