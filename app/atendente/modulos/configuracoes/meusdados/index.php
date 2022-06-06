<?php
include("../classes/vendedores.class.php");
include("config.php");

$linhaObj = new Vendedores();
$linhaObj->Set("idvendedor",$usu_vendedor["idvendedor"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);

if($_POST["acao"] == "salvar") {

  if ($_FILES) {
        foreach ($_FILES as $ind => $val) {
            $_POST[$ind] = $val;
        }
    }

  if(senhaSegura($_POST["senha_antiga"], $config["chaveLogin"]) == $usu_vendedor["senha"]) {
	$_POST["idvendedor"] = $usu_vendedor["idvendedor"];
	$_POST["email_antigo"] = $usu_vendedor["email"];
	$linhaObj->Set("onde","V"); //Informa de que modulo foi feita a modificação dos dadps da pessoa 
	$linhaObj->Set("idsolicita_alteracao",$usu_vendedor["idvendedor"]);// Id do usuário de fez a modificação
	$linhaObj->Set("post", $_POST);
	$salvar = $linhaObj->Modificar();
	
	if($salvar["sucesso"]){	
	  if($salvar["validacao"]) {
		$linhaObj->Set("pro_mensagem_idioma","modificar_validacao_sucesso");
	  } else {
		$linhaObj->Set("pro_mensagem_idioma","modificar_sucesso");
	  }
	  $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]);
	  $linhaObj->Processando();
	}
  } else {
	$salvar["sucesso"] = false;
	$salvar["erros"][] = "senha_antiga_invalida";
  }
}


if($url[2] == "meusdados") {

	if (isset($usu_vendedor["idvendedor"])) {
		$linhaObj->Set("id", (int)$usu_vendedor["idvendedor"]);
		$linhaObj->Set("campos", "*");
		$linha = $linhaObj->Retornar();
		if (isset($url[3])) {
			switch ($url[3]) {
				case "download":
					include("telas/" . $config["tela_padrao"] . "/download.php");
					exit;
				case "excluir":
					include("idiomas/" . $config["idioma_padrao"] . "/excluir.arquivo.php");
					$linhaObj->RemoverArquivo('vendedores', 'avatar', $linha, $idioma);
					exit;
				default:
					header("Location: /" . $url[0] . "/" . $url[1] . "/" . $url[2] . "");
					exit();
			}
		} else {
			include("idiomas/" . $config["idioma_padrao"] . "/formulario.php");
			include("telas/" . $config["tela_padrao"] . "/formulario.php");
		}
	}

	$linhaObj->Set("id",intval($usu_vendedor["idvendedor"]));
	$linhaObj->Set("campos","*");
	$linha = $linhaObj->Retornar();

	if(is_array($linha)) $linha = array_map(stripslashes,$linha);
	
	include("idiomas/".$config["idioma_padrao"]."/index.php");
	include("telas/".$config["tela_padrao"]."/index.php");
} else {
	header("Location: /".$url[0]."/".$url[1]);
	exit();
}
?>