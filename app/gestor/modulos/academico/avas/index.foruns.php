<?php  

$config["formulario_foruns"] = array(
  array(
	"fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario																						
	  array(
		"id" => "form_nome",
		"nome" => "nome", 
		"nomeidioma" => "form_nome",
		"tipo" => "input",
		"valor" => "nome",
		"validacao" => array("required" => "nome_vazio"), 
		"class" => "span6",
		"banco" => true,
		"banco_string" => true,
	  ),
	  /*array(
		"id" => "form_descricao",
		"nome" => "descricao",
		"nomeidioma" => "form_descricao",
		"tipo" => "text", 
		"valor" => "descricao",
		"class" => "span6",
		//"validacao" => array("required" => "descricao_vazio"),
		"banco" => true, 
		"banco_string" => true,
	  ),*/
	  array(
		"id" => "form_periode_de",
		"nome" => "periode_de",
		"nomeidioma" => "form_periode_de",
		"tipo" => "input", 
		"valor" => "periode_de",
		//"validacao" => array("required" => "periode_de_vazio"), 
		"valor_php" => 'if($dados["periode_de"]) return formataData("%s", "br", 0)',
		"class" => "span2",
		"mascara" => "99/99/9999",
		"datepicker" => true,
		"banco" => true, 
		"banco_php" => 'return formataData("%s", "en", 0)',
		"banco_string" => true 															
	  ),
	  array(
		"id" => "form_periode_ate",
		"nome" => "periode_ate",
		"nomeidioma" => "form_periode_ate",
		"tipo" => "input", 
		"valor" => "periode_ate",
		//"validacao" => array("required" => "periode_ate_vazio"), 
		"valor_php" => 'if($dados["periode_ate"]) return formataData("%s", "br", 0)',
		"class" => "span2",
		"mascara" => "99/99/9999",
		"datepicker" => true,
		"banco" => true, 
		"banco_php" => 'return formataData("%s", "en", 0)',
		"banco_string" => true 															
	  ),
	 /* array(
		"id" => "form_imagem_exibicao", // Id do atributo HTML
		"nome" => "imagem_exibicao", // Name do atributo HTML
		"nomeidioma" => "form_imagem_exibicao", // Referencia a variavel de idioma
		"arquivoidioma" => "arquivo_enviado", // Referencia a variavel de idioma
		"arquivoexcluir" => "arquivo_excluir", // Referencia a variavel de idioma
		"tipo" => "file", // Tipo do input
		"extensoes" => 'jpg|jpeg|gif|png|bmp',
		"ajudaidioma" => "form_exibir_ava_ajuda",
		//"largura" => 350,
		//"altura" => 180,
		"validacao" => array("formato_arquivo" => "arquivo_invalido"),
		"class" => "span6", //Class do atributo HTML
		"pasta" => "avas_foruns_imagem_exibicao", 
		"download" => true,
		"download_caminho" => $url["0"]."/".$url["1"]."/".$url["2"]."/".$url["3"]."/".$url["4"]."/".$url["5"],
		"excluir" => true,
		"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco_campo" => "imagem_exibicao", // Nome das colunas da tabela do banco de dados que retorna o valor.
		"ignorarsevazio" => true 
	  ),*/
	array(
		"id" => "iddisciplina",
		"nome" => "iddisciplina",
		"nomeidioma" => "form_iddisciplina",
		"tipo" => "select",
		"sql" => "SELECT 
				d.iddisciplina, d.nome
			  FROM
				avas a
				INNER JOIN avas_disciplinas ad ON (a.idava = ad.idava)
				INNER JOIN disciplinas d ON (ad.iddisciplina = d.iddisciplina)
			  WHERE 
				ad.ativo = 'S' AND d.ativo = 'S' AND a.idava = $url[3]", 
		"sql_valor" => "iddisciplina", // Coluna da tabela que será usado como o valor do options
		"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
		"valor" => "iddisciplina",
		"class" => "span4",
		"banco" => true,
		"banco_string" => true,
	),
	  array(
		"id" => "form_ordem",
		"nome" => "ordem",
		"nomeidioma" => "form_ordem",
		"tipo" => "input", 
		"valor" => "ordem",
		"class" => "span1",
		"evento" => "maxlength='2'", 
		//"validacao" => array("required" => "ordem_vazio"),
		"banco" => true,
		"banco_string" => true,
		"numerico" => true															
	  ),
	  array(
		"id" => "form_exibir_ava",
		"nome" => "exibir_ava",
		"nomeidioma" => "form_exibir_ava",
		"tipo" => "select",
		"array" => "sim_nao", // Array que alimenta o select
		"class" => "span2", 
		"valor" => "exibir_ava",
		"validacao" => array("required" => "exibir_ava_vazio"),
		"banco" => true,
		"banco_string" => true
	  ),
      array(
        "id" => "form_enviar_email_automatico",
        "nome" => "enviar_email_automatico",
        "nomeidioma" => "form_enviar_email_automatico",
        "tipo" => "select",
        "array" => "sim_nao", // Array que alimenta o select
        "class" => "span2",
        "valor" => "enviar_email_automatico",
        "validacao" => array("required" => "enviar_email_automatico_vazio"),
        "banco" => true,
        "banco_string" => true
      ),
	  array(
		"id" => "idava_forum", // Id do atributo HTML
		"nome" => "idava", // Name do atributo HTML
		"tipo" => "hidden", // Tipo do input
		"valor" => 'return $this->url["3"];',
		"banco" => true
	  ),
	)
  )								  
);

$tipoPessoas = array(
  "professor" => array(
	"nome" => "Professor",
	"funcionalidades" => array(
	  "topicos" => array(
		"nome" => "Tópico",
		"acoes" => array(
		  1 => "Criar um novo tópico",
		  2 => "Responder os tópicos",
		  3 => "Bloquear os tópicos",
		  4 => "Ocultar os tópicos",
		  5 => "Visualizar os tópicos ocultos",
		  6 => "Votar nos tópicos",
		  //7 => "Administrar prazo do tópico",
		  8 => "Anexar arquivo ao tópico",
		)
	  ),
	  "mensagens" => array(
		"nome" => "Mensagens",
		"acoes" => array(
		  1 => "Ocultar mensagem",
		  2 => "Visualizar mensagens ocultas",
		  3 => "Moderar mensagens",
		  4 => "Visualizar mensagens que foram moderadas",
		  5 => "Anexar arquivo a mensagem",
		)
	  ),
	  /*"posts" => array(
		"nome" => "Post",
		"acoes" => array(
		  1 => "Enviar post com anexo"
		)
	  )*/	  
	)
  ),
  /*"tutor_presencial" => array(
	"nome" => "Tutor presencial",
	"funcionalidades" => array(
	  "topicos" => array(
		"nome" => "Tópico",
		"acoes" => array(
		  1 => "Criar um novo tópico",
		  2 => "Responder os tópico",
		  3 => "Bloquear os tópico",
		  4 => "Ocultar os tópico",
		  5 => "Visualizar os tópicos ocultos",
		  6 => "Votar nos tópicos",
		  //7 => "Administrar prazo do tópico",
		)
	  ),
	  "mensagens" => array(
		"nome" => "Mensagens",
		"acoes" => array(
		  1 => "Ocultar mensagem",
		  2 => "Visualizar mensagens ocultas",
		  3 => "Moderar mensagens",
		  4 => "Visualizar mensagens que foram moderadas"
		)
	  )	  
	)
  ),*/
  /*"tutor_online" => array(
	"nome" => "Tutor online",
	"funcionalidades" => array(
	  "topicos" => array(
		"nome" => "Tópico",
		"acoes" => array(
		  1 => "Criar um novo tópico",
		  2 => "Responder os tópico",
		  3 => "Bloquear os tópico",
		  4 => "Ocultar os tópico",
		  5 => "Visualizar os tópicos ocultos",
		  6 => "Votar nos tópicos",
		  //7 => "Administrar prazo do tópico",
		)
	  ),
	  "mensagens" => array(
		"nome" => "Mensagens",
		"acoes" => array(
		  1 => "Ocultar mensagem",
		  2 => "Visualizar mensagens ocultas",
		  3 => "Moderar mensagens",
		  4 => "Visualizar mensagens que foram moderadas"
		)
	  ),	  
	)
  ),*/
  "aluno" => array(
	"nome" => "Aluno",
	"funcionalidades" => array(
	  "topicos" => array(
		"nome" => "Tópico",
		"acoes" => array(
		  1 => "Criar um novo tópico",
		  2 => "Responder os tópicos",
		  3 => "Bloquear os tópicos",
		  4 => "Ocultar os tópicos",
		  5 => "Visualizar os tópicos ocultos",
		  6 => "Votar nos tópicos",
		  //7 => "Administrar prazo do tópico",
		  8 => "Anexar arquivo ao tópico",
		  9 => "Atividades do fórum",
		)
	  ),
	  "mensagens" => array(
		"nome" => "Mensagens",
		"acoes" => array(
		  1 => "Ocultar mensagem",
		  2 => "Visualizar mensagens ocultas",
		  3 => "Moderar mensagens",
		  4 => "Visualizar mensagens que foram moderadas",
		  5 => "Anexar arquivo a mensagem",
		  6 => "Atividades do fórum",
		)
	  ),
	  /*"posts" => array(
		"nome" => "Post",
		"acoes" => array(
		  1 => "Enviar post com anexo"
		)
	  )*/	  
	)
  ),
);

$config["listagem_foruns"] = array(
  array(
	"id" => "idforum",
	"variavel_lang" => "tabela_idforum", 
	"tipo" => "banco", 
	"coluna_sql" => "idforum", 
	"valor" => "idforum", 
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 1,
	"tamanho" => 60
  ),
  array(
	"id" => "nome", 
	"variavel_lang" => "tabela_nome",
	"tipo" => "banco",
	"evento" => "maxlength='100'",
	"coluna_sql" => "f.nome",
	"valor" => "nome",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  array(
    "id" => "disciplina", 
    "variavel_lang" => "tabela_disciplina",
    "tipo" => "php",
    "evento" => "maxlength='100'",
    "coluna_sql" => "d.nome",
    "valor" => 'if ($linha["disciplina"]) {
                    return $linha["disciplina"];
                } else {
                    return "--";
                }',
    "busca" => true,

    "busca_class" => "inputPreenchimentoCompleto",
    "busca_metodo" => 2
  ),
  array(
	"id" => "exibir_ava", 
	"variavel_lang" => "tabela_exibir_ava", 
	"tipo" => "php",
	"coluna_sql" => "f.exibir_ava", 
	"valor" => 'if($linha["exibir_ava"] == "S") {
				  return "<span data-original-title=\"".$idioma["ativo"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">A</span>";
				} else {
				  return "<span data-original-title=\"".$idioma["inativo"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">I</span>";
				}',
	"busca" => true,
	"busca_tipo" => "select",
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_array" => "ativo",
	"busca_metodo" => 1,
	"tamanho" => 60
  ),
  array(
	"id" => "total_topicos", 
	"variavel_lang" => "tabela_total_topicos", 
	"tipo" => "banco",
	"valor" => "total_topicos",
	"tamanho" => "60"
  ),
  array(
	"id" => "total_respostas", 
	"variavel_lang" => "tabela_total_respostas",
	"tipo" => "banco",
	"valor" => 'total_respostas',
	"tamanho" => "60"
  ), 								  				
  array(
	"id" => "data_cad", 
	"variavel_lang" => "tabela_datacad", 
	"coluna_sql" => "f.data_cad",
	"tipo" => "php", 
	"valor" => 'return formataData($linha["data_cad"],"br",1);',
	"tamanho" => "140"
  ), 
  array(
	"id" => "opcoes", 
	"variavel_lang" => "tabela_opcoes", 
	"tipo" => "php", 
	"valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$this->url["3"]."/".$this->url["4"]."/".$linha["idforum"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
	"busca_botao" => true,
	"tamanho" => "80"
  ) 
);

$config["formulario_foruns_topicos"] = array(
  array(
	"fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario																						
	  array(
		"id" => "form_nome",
		"nome" => "nome", 
		"nomeidioma" => "form_nome",
		"tipo" => "input",
		"valor" => "nome",
		"validacao" => array("required" => "nome_vazio"), 
		"class" => "span6",
		"banco" => true,
		"banco_string" => true,
	  ),
	  array(
		"id" => "form_mensagem",
		"nome" => "mensagem",
		"nomeidioma" => "form_mensagem",
		"tipo" => "text", 
		"valor" => "mensagem",
		"class" => "span6",
		"validacao" => array("required" => "mensagem_vazio"),
		"banco" => true, 
		"banco_string" => true,
	  ),
	  /*array(
		"id" => "form_periode_de",
		"nome" => "periode_de",
		"nomeidioma" => "form_periode_de",
		"tipo" => "input", 
		"valor" => "periode_de",
		//"validacao" => array("required" => "periode_de_vazio"), 
		"valor_php" => 'if($dados["periode_de"]) return formataData("%s", "br", 0)',
		"class" => "span2",
		"mascara" => "99/99/9999",
		"datepicker" => true,
		"banco" => true, 
		"banco_php" => 'return formataData("%s", "en", 0)',
		"banco_string" => true 															
	  ),
	  array(
		"id" => "form_periode_ate",
		"nome" => "periode_ate",
		"nomeidioma" => "form_periode_ate",
		"tipo" => "input", 
		"valor" => "periode_ate",
		//"validacao" => array("required" => "periode_ate_vazio"), 
		"valor_php" => 'if($dados["periode_ate"]) return formataData("%s", "br", 0)',
		"class" => "span2",
		"mascara" => "99/99/9999",
		"datepicker" => true,
		"banco" => true, 
		"banco_php" => 'return formataData("%s", "en", 0)',
		"banco_string" => true 															
	  ),
	  array(
		"id" => "form_arquivo", // Id do atributo HTML
		"nome" => "arquivo", // Name do atributo HTML
		"nomeidioma" => "form_arquivo", // Referencia a variavel de idioma
		"arquivoidioma" => "arquivo_enviado", // Referencia a variavel de idioma
		"arquivoexcluir" => "arquivo_excluir", // Referencia a variavel de idioma
		"tipo" => "file", // Tipo do input
		"extensoes" => 'jpg|jpeg|gif|png|bmp|doc|docx|pdf|xls|xlsx|ppt|pptx|txt',
		"ajudaidioma" => "form_arquivo_ajuda",
		//"largura" => 350,
		//"altura" => 180,
		"validacao" => array("formato_arquivo" => "arquivo_invalido"),
		"class" => "span6", //Class do atributo HTML
		"pasta" => "avas_foruns_topicos_arquivo", 
		"download" => true,
		"download_caminho" => $url["0"]."/".$url["1"]."/".$url["2"]."/".$url["3"]."/".$url["4"]."/".$url["5"]."/".$url["6"]."/".$url["7"],
		"excluir" => true,
		"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco_campo" => "arquivo", // Nome das colunas da tabela do banco de dados que retorna o valor.
		"ignorarsevazio" => true 
	  ),*/
	  array(
		"id" => "idforum_topico", // Id do atributo HTML
		"nome" => "idforum", // Name do atributo HTML
		"tipo" => "hidden", // Tipo do input
		"valor" => 'return $this->url["5"];',
		"banco" => true
	  ),
	  array(
		"id" => "idusuario_topico", // Id do atributo HTML
		"nome" => "idusuario", // Name do atributo HTML
		"tipo" => "hidden", // Tipo do input
		"valor" => '',
		"banco" => true
	  ),
	)
  )								  
);

$linhaObj->Set("config",$config);						   
include("../classes/avas.foruns.class.php");
		
$linhaObj = new Foruns();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|25");	
	
$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde_foruns"]);
$linhaObj->Set("idava",intval($url[3]));
$linhaObj->Set("modulo","gestor");

$linhaObj->config["banco"] = $config["banco_foruns"];
$linhaObj->config["formulario"] = $config["formulario_foruns"];

if($_POST["acao"] == "salvar_forum"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|26");
		
  if($_FILES) {
	foreach($_FILES as $ind => $val) {
	  $_POST[$ind] = $val;
	}
  }
  
  $linhaObj->Set("post",$_POST);		
  if($_POST[$config["banco_foruns"]["primaria"]]) 
	$salvar = $linhaObj->ModificarForum();
  else 
	$salvar = $linhaObj->CadastrarForum();
  
  if($salvar["sucesso"]){
	if($_POST[$config["banco_foruns"]["primaria"]]) {
	  $linhaObj->Set("pro_mensagem_idioma","modificar_sucesso");
	  $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
	} else {
	  $linhaObj->Set("pro_mensagem_idioma","cadastrar_sucesso");
	  $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	}
	$linhaObj->Processando();
  }
} elseif($_POST["acao"] == "remover_forum") {
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|27");
  $linhaObj->Set("post",$_POST);
  $remover = $linhaObj->RemoverForum();
  if($remover["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","remover_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	$linhaObj->Processando();
  }
}

if(isset($url[5])){
  if($url[5] == "cadastrar") {
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|26");
	include("idiomas/".$config["idioma_padrao"]."/formulario.foruns.php");
	include("telas/".$config["tela_padrao"]."/formulario.foruns.php");
	exit;
  } elseif($url[5] == "acessar") {
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|50");
	$linhaObj->Set("ordem","desc");
	$linhaObj->Set("limite","-1");
	$linhaObj->Set("ordem_campo","idforum");
	$linhaObj->Set("campos","f.*, a.nome as ava, d.nome as disciplina");	
	$foruns = $linhaObj->ListarTodasForum();
	
	$populares = $linhaObj->ListarTopicosPopulares(false, true);
	$alunosAtivos = $linhaObj->ListarAlunosAtivos();
	
	include("idiomas/".$config["idioma_padrao"]."/foruns.php");
	include("telas/".$config["tela_padrao"]."/foruns.php");
	exit;
  }
  
  $linhaObj->Set("id",intval($url[5]));
  $linhaObj->Set("campos","f.*, a.nome as ava, d.nome as disciplina");	
  $forum = $linhaObj->RetornarForum();
  if($forum) {
	
	if($_POST["acao"] == "salvar_topico"){
	  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|41");
			
	  $linhaObj->Set("monitora_onde",$config["monitoramento"]["onde_foruns_topicos"]);
	  $linhaObj->Set("idava",intval($url[3]));
	  $linhaObj->Set("id",intval($url[5]));
	
	  $linhaObj->config["banco"] = $config["banco_foruns_topicos"];
	  $linhaObj->config["formulario"] = $config["formulario_foruns_topicos"];

	  $linhaObj->Set("post",$_POST);		
	  /*if($_POST[$config["banco_foruns_topicos"]["primaria"]]) 
		$salvar = $linhaObj->ModificarTopico();
	  else*/ 
		$salvar = $linhaObj->CadastrarTopico();
	  
	  if($salvar["sucesso"]){
		if($_POST[$config["banco_foruns_topicos"]["primaria"]])  {
		  $linhaObj->Set("pro_mensagem_idioma","modificar_topico_sucesso");
		  $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]."/".$url[7]."/".$url[8]);
		} else {
		  $linhaObj->Set("pro_mensagem_idioma","cadastrar_topico_sucesso");
		  $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
		}
		$linhaObj->Processando();
	  }
	} elseif($_POST["acao"] == "responder_topico"){
	  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|42");
	  $linhaObj->Set("post",$_POST);		
	  $salvar = $linhaObj->ResponderTopico(intval($url[7]));
	  
	  if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","responder_topico_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]."/".$url[7]."/".$url[8]);
		$linhaObj->Processando();
	  }
	} elseif($_POST["acao"] == "moderar_topico"){
	  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|43");
	  $linhaObj->Set("post",$_POST);		
	  $salvar = $linhaObj->ModerarTopico(intval($url[7]));
	  
	  if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","moderar_topico_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]."/".$url[7]."/".$url[8]);
		$linhaObj->Processando();
	  }
	} elseif($_POST["acao"] == "moderar_mensagem"){
	  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|48");
	  $linhaObj->Set("post",$_POST);		
	  $salvar = $linhaObj->ModerarMensagem(intval($_POST["idmensagem"]));
	  
	  if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","moderar_mensagem_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]."/".$url[7]."/".$url[8]);
		$linhaObj->Processando();
	  }
	} elseif($_POST["acao"] == "ocultar_topico"){
	  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|44");
	  $salvar = $linhaObj->ocultarTopico(intval($url[7]));
	  
	  if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","ocultar_topico_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]."/".$url[7]."/".$url[8]);
		$linhaObj->Processando();
	  }
	} elseif($_POST["acao"] == "desocultar_topico"){
	  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|44");
	  $salvar = $linhaObj->desocultarTopico(intval($url[7]));
	  
	  if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","desocultar_topico_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]."/".$url[7]."/".$url[8]);
		$linhaObj->Processando();
	  }
	} elseif($_POST["acao"] == "ocultar_mensagem"){
	  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|49");
	  $salvar = $linhaObj->ocultarMensagem(intval($_POST["idmensagem"]));
	  
	  if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","ocultar_mensagem_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]."/".$url[7]."/".$url[8]);
		$linhaObj->Processando();
	  }
	} elseif($_POST["acao"] == "desocultar_mensagem"){
	  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|49");
	  $salvar = $linhaObj->desocultarMensagem(intval($_POST["idmensagem"]));
	  
	  if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","desocultar_topico_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]."/".$url[7]."/".$url[8]);
		$linhaObj->Processando();
	  }
	} elseif($_POST["acao"] == "bloquear_topico"){
	  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|45");
	  $salvar = $linhaObj->bloquearTopico(intval($url[7]));
	  
	  if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","bloquear_topico_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]."/".$url[7]."/".$url[8]);
		$linhaObj->Processando();
	  }
	} elseif($_POST["acao"] == "desbloquear_topico"){
	  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|45");
	  $salvar = $linhaObj->desbloquearTopico(intval($url[7]));
	  
	  if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","desbloquear_topico_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]."/".$url[7]."/".$url[8]);
		$linhaObj->Processando();
	  }
	} elseif($_POST["acao"] == "assinar_topico"){
	  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|46");
	  $salvar = $linhaObj->assinarTopico(intval($url[7]));
	  
	  if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","assinar_topico_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]."/".$url[7]."/".$url[8]);
		$linhaObj->Processando();
	  }
	} elseif($_POST["acao"] == "desassinar_topico"){
	  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|46");
	  $salvar = $linhaObj->desassinarTopico(intval($_POST["idassinatura"]));
	  
	  if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","desassinar_topico_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]."/".$url[7]."/".$url[8]);
		$linhaObj->Processando();
	  }
	}
	
	switch($url[6]) {
	  case "editar":			
		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|26");
		include("idiomas/".$config["idioma_padrao"]."/formulario.foruns.php");
		include("telas/".$config["tela_padrao"]."/formulario.foruns.php");
	  break;
	  case "topicos":	
		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|40");
		if(isset($url[7])){			
		  if($url[7] == "cadastrar") {
			$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|41");
			include("idiomas/".$config["idioma_padrao"]."/foruns.topicos.formulario.php");
			include("telas/".$config["tela_padrao"]."/foruns.topicos.formulario.php");
			exit;
		  } else {	
			$linhaObj->Set("campos","f.*, a.nome as ava");	
			$topico = $linhaObj->RetornarTopico(intval($url[7]));

			if($topico) {
			  switch($url[8]) {
				case "moderar":			
				  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|43");
				  include("idiomas/".$config["idioma_padrao"]."/foruns.topicos.formulario.php");
				  include("telas/".$config["tela_padrao"]."/foruns.topicos.formulario.php");
				break;
				break;
				/*case "remover":			
				  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|26");
				  include("idiomas/".$config["idioma_padrao"]."/remover.foruns.topicos.php");
				  include("telas/".$config["tela_padrao"]."/remover.foruns.topicos.php");
				break;*/
				case "mensagens":		
				  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|47");
				  if($url[9] == "cadastrar" || ($url[9] && ($url[10] == "responder" || $url[10] == "moderar"))) {
					if($url[9] && $url[10] == "moderar") {
					  $linhaObj->Set("campos","*");	
					  $mensagem = $linhaObj->RetornarMensagem(intval($url[9]));
					}
					include("idiomas/".$config["idioma_padrao"]."/foruns.topicos.mensagens.formulario.php");
					include("telas/".$config["tela_padrao"]."/foruns.topicos.mensagens.formulario.php");
					exit;
				  } elseif($url[9] == "json" && $url[10] == "curtir") {
					$linhaObj->Set("post",$_POST);
					echo $linhaObj->CurtirTopicoMensagem();
					exit;
				  } elseif(isset($url[9]) && $url[10] == "download") {
					$linhaObj->Set("campos","*");	
					$mensagem = $linhaObj->RetornarMensagem(intval($url[9]));
					$linhaObj->countabilizarDownloadMensagem($mensagem["idmensagem"]);
					include("telas/".$config["tela_padrao"]."/download.foruns.topicos.mensagens.php");
				  } else {
					$assinatura = $linhaObj->verificaAssinaturaTopico($topico["idtopico"],$usuario["idusuario"],null,null);
					
					$respostas = $linhaObj->ListarTodasMensagens($topico["idtopico"], true);
					
					$participantes = $linhaObj->ParticipantesTopico($topico["idtopico"]);
					
					include("idiomas/".$config["idioma_padrao"]."/foruns.topicos.mensagens.php");
					include("telas/".$config["tela_padrao"]."/foruns.topicos.mensagens.php");
					exit;
				  }
				break;
				case "download":
				  $linhaObj->countabilizarDownloadTopico($topico["idtopico"]);
				  include("telas/".$config["tela_padrao"]."/download.foruns.topicos.php");
				break;
				case "excluir":
				  include("idiomas/".$config["idioma_padrao"]."/excluir.arquivo.php");
				  $linhaObj->RemoverArquivo($url[2]."_".$url[4]."_".$url[6], $url[9], $topico, $idioma);
				break;	
				default:
				  header("Location: /".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
				  exit();
			  }				
			} else {
			  header("Location: /".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
			  exit();
			}			
		  }
		} else {
		  if(!$_GET["pag"]) $_GET["pag"] = 1;
		  $linhaObj->Set("pagina",$_GET["pag"]);
		  if(!$_GET["qtd"]) $_GET["qtd"] = 30;
		  $linhaObj->Set("limite",intval($_GET["qtd"]));
		  $linhaObj->Set("ordem_campo","idtopico");
		  $linhaObj->Set("ordem","desc");
		  $linhaObj->Set("campos","*");	
		  $topicos = $linhaObj->ListarTodasTopico(intval($url[5]), true);
		  
		  $populares = $linhaObj->ListarTopicosPopulares(intval($url[5]), true);	

		  $alunosAtivos = $linhaObj->ListarAlunosAtivos(intval($url[5]));
			  
		  include("idiomas/".$config["idioma_padrao"]."/foruns.topicos.php");
		  include("telas/".$config["tela_padrao"]."/foruns.topicos.php");
		  exit;
		}
	  break;
	  case "remover":			
		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|27");
		include("idiomas/".$config["idioma_padrao"]."/remover.foruns.php");
		include("telas/".$config["tela_padrao"]."/remover.foruns.php");
	  break;
	  case "opcoes":			
		include("idiomas/".$config["idioma_padrao"]."/opcoes.foruns.php");
		include("telas/".$config["tela_padrao"]."/opcoes.foruns.php");
	  break;	
	  case "download":
		include("telas/".$config["tela_padrao"]."/download.foruns.php");
	  break;
	  case "excluir":
		include("idiomas/".$config["idioma_padrao"]."/excluir.arquivo.php");
		$linhaObj->RemoverArquivo($url[2]."_".$url[4], $url[7], $forum, $idioma);
	  break;	
	  default:
		header("Location: /".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
		exit();
	}				
  } else {
	header("Location: /".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	exit();
  }
} else {  	
  $linhaObj->Set("pagina",$_GET["pag"]);
  if(!$_GET["ordem"]) $_GET["ordem"] = "desc";
  $linhaObj->Set("ordem",$_GET["ord"]);
  if(!$_GET["qtd"]) $_GET["qtd"] = 30;
  $linhaObj->Set("limite",intval($_GET["qtd"]));
  if(!$_GET["cmp"]) $_GET["cmp"] = "idforum";
  $linhaObj->Set("ordem_campo",$_GET["cmp"]);
  $linhaObj->Set("campos","f.*, a.nome as ava, d.nome as disciplina");	
  
  $foruns = $linhaObj->ListarTodasForum();
  
  include("idiomas/".$config["idioma_padrao"]."/index.foruns.php");
  include("telas/".$config["tela_padrao"]."/index.foruns.php");
}
?>