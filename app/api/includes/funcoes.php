<?php
function incluirLib($lib,$config,$informacoes = null){
	$url = $GLOBALS["url"];
	$lib = "lib/".$lib."/index.php";
	if(file_exists($lib)){
		include($lib);
	} else {
		echo "<strong>Erro ao tentar incluir a LIB, verifique o código.</strong> <br> LIB: $lib";
	}
}

function incluirTela($tela,$config,$informacoes = null){
	$url = $GLOBALS["url"];
	$root = $_SERVER['DOCUMENT_ROOT']."adm/modulos/".$url[1]."/".$url[2];
	$tela_arquivo = $root."/telas/".$tela.".php";
	$tela_idioma = $root."/idiomas/".$config["idioma_padrao"]."/".$tela.".php";
	if(file_exists($tela_arquivo) && file_exists($tela_idioma)){
		include($tela_idioma);
		include($tela_arquivo);
	} else {
		echo "<strong>Erro ao tentar incluir a TELA, verifique o código.</strong> <br> TELA: $tela_arquivo <br> Idioma: $tela_idioma";
	}
}

function listarFuncionalidades($url) {
	$diretorio = getcwd() . '/modulos//' . $url;
	$ponteiro  = opendir($diretorio);
	
	$pastasArray = array();
	
	while ($nome_itens = readdir($ponteiro)) {
		if ($nome_itens != "." && $nome_itens != ".." && $nome_itens != "index" && is_dir($diretorio.'//'.$nome_itens)) {
			$pastas[]=$nome_itens;
		}		
	}
	
	if ($pastas != "") { 
		foreach($pastas as $listar){
		   
		   $arrayAux = array();
		   
		   $lines = file($diretorio."//".$listar."/idiomas//".$GLOBALS["config"]["idioma_padrao"]."/config.php");
		   $total = count($lines);
		   for ($i = 1; $i < $total; $i++){
			  $pos_titulo = strpos($lines[$i], "funcionalidade");
			  if ($pos_titulo !== false) {			 
				 $ex_igual = explode('=',$lines[$i]);
				 $c_tit = explode(';',$ex_igual[1]);	
				 $pattern = '/"/i';
				 $replacement = '';
				 $nome = preg_replace($pattern, $replacement, $c_tit[0]);
			  }			  	  
		   }
		   
		   
		   $achou_img = false;
		   $lines_2 = file($diretorio."//".$listar."/config.php");
		   $total_2 = count($lines_2);
		   for ($i = 1; $i < $total_2; $i++){
			  $pos_icone_2 = strpos($lines_2[$i], "funcionalidade_icone_32");
			  if ($pos_icone_2 !== false) {
				  $ex_igual_2 = explode('=',$lines_2[$i]);
				  $c_img = explode(';',$ex_igual_2[1]);
				  $arrayAux["imagem"] = $c_img[0];
				  //echo "<img src=".$c_img[0]." />";
				  $achou_img = true;
			  }				  
		   }
		   
		   if (!$achou_img)
		     	$arrayAux["imagem"] = "/assets/img/menu_completo.png";
			    //echo "<img src='/assets/img/oraculo_32x32.png' />";	

		   $arrayAux["pasta"] = $listar;
		   $arrayAux["nome"] = $nome;
		   //echo "<a href='$listar'>$nome</a><br>";
		   
		   $pastasArray[] = $arrayAux;
		   unset($arrayAux);
		   
		   unset($nome);
		   unset($listar);
		}
	}
	
	return $pastasArray;
		
}

function adicionarCabecalhoJson($codigo = 200)
{
    http_response_code($codigo);
    header('Content-Type: application/json');
    header('Expires: 0');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET');
    header('Pragma: no-cache');
}

function adicionarHeaders()
{
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
}

function validaCPF($cpf = null) {

    // Verifica se um número foi informado
    if(empty($cpf)) {
        return false;
    }

    // Elimina possivel mascara
    $cpf = preg_replace("/[^0-9]/", "", $cpf);
    $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);

    // Verifica se o numero de digitos informados é igual a 11
    if (strlen($cpf) != 11) {
        return false;
    }
    // Verifica se nenhuma das sequências invalidas abaixo
    // foi digitada. Caso afirmativo, retorna falso
    else if ($cpf == '00000000000' ||
        $cpf == '11111111111' ||
        $cpf == '22222222222' ||
        $cpf == '33333333333' ||
        $cpf == '44444444444' ||
        $cpf == '55555555555' ||
        $cpf == '66666666666' ||
        $cpf == '77777777777' ||
        $cpf == '88888888888' ||
        $cpf == '99999999999') {
        return false;
        // Calcula os digitos verificadores para verificar se o
        // CPF é válido
    } else {

        for ($t = 9; $t < 11; $t++) {

            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf{$c} != $d) {
                return false;
            }
        }

        return true;
    }
}
	
?>