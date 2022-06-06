<?php
include("config.php");

//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");

  switch ($url[3]) {
	case "mapa":	
	
	  include("../classes/ofertas.class.php");
	  $ofertasObj = new Ofertas();
	  $ofertasObj->Set("pagina",1);
	  $ofertasObj->Set("ordem","asc");
	  $ofertasObj->Set("limite",-1);
	  $ofertasObj->Set("ordem_campo","nome");
	  $ofertasObj->Set("campos","o.*, ow.nome as situacao, ow.cor_bg as situacao_cor_bg, ow.cor_nome as situacao_cor_nome");	
	  $ofertasArray = $ofertasObj->ListarTodas();	
	  
	  include("../classes/cursos.class.php");
	  $cursosObj = new Cursos();
	  $cursosObj->Set("pagina",1);
	  $cursosObj->Set("ordem","asc");
	  $cursosObj->Set("limite",-1);
	  $cursosObj->Set("ordem_campo","nome");
	  $cursosObj->Set("campos","*");
	  $cursosObj->Set("idusuario",$usuario["idusuario"]);
	  $cursosArray = $cursosObj->ListarTodas();		
	  
	  $estadosArray = array();
	  $sql = "select idestado,nome from estados order by nome asc";
	  $seleciona = mysql_query($sql); 
	  while($estado = mysql_fetch_assoc($seleciona)) {
		$estadosArray[] =  $estado; 
	  }
			
	  include("idiomas/".$config["idioma_padrao"]."/mapa.php");
	  include("telas/".$config["tela_padrao"]."/mapa.php");
	break;

	case "dados.json":	
	  
	  include("../classes/matriculas.class.php");
	  $matriculaObj = new Matriculas();
	  $matriculas = $matriculaObj->ListarMatriculasMapaAlcance((int) $_GET['idoferta'],(int) $_GET['idcurso'],(int) $_GET['idestado']);		
		
	  include("telas/".$config["tela_padrao"]."/dados.json.php");
	break;

	default:
	  include("idiomas/".$config["idioma_padrao"]."/index.php");
	  include("telas/".$config["tela_padrao"]."/index.php");
  }				

?>