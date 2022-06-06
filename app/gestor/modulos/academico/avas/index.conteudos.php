<?php
/*$config["formulario_conteudos"] = array(
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
            "id" => "form_tipo_edicao",
            "nome" => "tipo_edicao",
            "nomeidioma" => "form_tipo_edicao",
            "tipo" => "select",
            "array" => "texto_blocos_arquivo", // Array que alimenta o select
            "class" => "span2",
            "valor" => "tipo_edicao",
            "validacao" => array("required" => "tipo_edicao_vazio"),
            "ajudaidioma" => "form_tipo_edicao_ajuda",
            "banco" => true,
            "banco_string" => true
        ),
	  array(
		"id" => "form_conteudo",
		"nome" => "conteudo",
		"nomeidioma" => "form_conteudo",
		"tipo" => "text", 
		"editor" => true,
		"evento" => "style='height:200px;'", 
		"valor" => "conteudo",
		"validacao" => array("required" => "conteudo_vazio"),
		"class" => "xxlarge",
		"banco" => true,
		"banco_string" => true
	  ),
	  array(
		"id" => "form_botao_variaveis_pessoa", // Id do atributo HTML
		"nome" => "botao_variaveis_pessoa", // Name do atributo HTML
		"nomeidioma" => "form_botao_variaveis_pessoa", // Referencia a variavel de idioma
		"tipo" => "php", // Tipo do input
		"colunas" => 2,
		"botao_hide" => true,
		"valor" => array(
		  array(
			"variavel_titulo_pessoa" => "titulo",
			"variavel_pessoa_nome" => "[[aluno][nome]]",
			"variavel_pessoa_nome_primeiro" => "[[aluno][primeiro_nome]]",
		  )
		),
		"class" => "span4" //Class do atributo HTML															
	  ),
	  array(
		"id" => "form_botao_variaveis_conteudo", // Id do atributo HTML
		"nome" => "botao_variaveis_conteudo", // Name do atributo HTML
		"nomeidioma" => "form_botao_variaveis_conteudo", // Referencia a variavel de idioma
		"tipo" => "php", // Tipo do input
		"colunas" => 2,
		"botao_hide" => true,
		"valor" => array(
		  array(
			"variavel_titulo_conteudo" => "titulo",
			"variavel_proximo_conteudo" => "[[conteudo][proximo_conteudo]]",
			"variavel_conteudo_anterior" => "[[conteudo][conteudo_anterior]]",
		  )
		),
		"class" => "span4" //Class do atributo HTML															
	  ),
	  array(
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
		"pasta" => "avas_conteudos_imagem_exibicao", 
		"download" => true,
		"download_caminho" => $url["0"]."/".$url["1"]."/".$url["2"]."/".$url["3"]."/".$url["4"]."/".$url["5"],
		"excluir" => true,
		"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco_campo" => "imagem_exibicao", // Nome das colunas da tabela do banco de dados que retorna o valor.
		"ignorarsevazio" => true 
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
		"ajudaidioma" => "form_ativo_ajuda",
		"banco" => true,
		"banco_string" => true
		),
	  array(
		"id" => "idava_conteudo", // Id do atributo HTML
		"nome" => "idava", // Name do atributo HTML
		"tipo" => "hidden", // Tipo do input
		"valor" => 'return $this->url["3"];',
		"banco" => true
	  ),
	)
  )								  
);*/

$config["formulario_conteudos"] = array(
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
                "id" => "form_tipo_edicao",
                "nome" => "tipo_edicao",
                "nomeidioma" => "form_tipo_edicao",
                "tipo" => "select",
                "array" => "texto_blocos_arquivo", // Array que alimenta o select
                "class" => "span2",
                "valor" => "tipo_edicao",
                "validacao" => array("required" => "tipo_edicao_vazio"),
                "ajudaidioma" => "form_tipo_edicao_ajuda",
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "form_conteudo_cke",
                "nome" => "conteudo_cke",
                "nomeidioma" => "form_conteudo_cke",
                "tipo" => "text",
                "editor" => true,
                "evento" => "style='height:200px;'",
                "valor" => "conteudo",
                //"validacao" => array("required" => "conteudo_vazio"),
                "class" => "xxlarge",
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "form_conteudo_online",
                "nome" => "conteudo_online",
                "nomeidioma" => "form_conteudo_online",
                "tipo" => "botao",
                "link" => "javascript:void(0)",
                "target" => "_top",
                "onclick" => "popup('/" . $url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5] . "/editor')",
                "class" => " btn-primary",
                "textobotao" => "Abrir editor de blocos",
                "preview" => "/" . $url[0]. "/" .$url[1]. "/" . $url[2]. "/" .$url[3]. "/" .$url[4]. "/".$url[5] . "/preview"
            ),
            array(
                "id" => "form_html", // Id do atributo HTML
                "nome" => "html", // Name do atributo HTML
                "nomeidioma" => "form_html", // Referencia a variavel de idioma
                "arquivoidioma" => "arquivo_enviado", // Referencia a variavel de idioma
                "arquivoexcluir" => "arquivo_excluir", // Referencia a variavel de idioma
                "tipo" => "file", // Tipo do input
                "extensoes" => 'html',
                "ajudaidioma" => "form_html_ajuda",
                //"largura" => 350,
                //"altura" => 180,
                "validacao" => array("formato_arquivo" => "arquivo_invalido"),
                "class" => "span6", //Class do atributo HTML
                "pasta" => "avas_conteudos_html",
                "download" => false,
                "excluir" => false,
                "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_campo" => "html", // Nome das colunas da tabela do banco de dados que retorna o valor.
                "ignorarsevazio" => true
            ),
            array(
                "id" => "form_botao_variaveis_pessoa", // Id do atributo HTML
                "nome" => "botao_variaveis_pessoa", // Name do atributo HTML
                "nomeidioma" => "form_botao_variaveis_pessoa", // Referencia a variavel de idioma
                "tipo" => "php", // Tipo do input
                "colunas" => 2,
                "botao_hide" => true,
                "valor" => array(
                    array(
                        "variavel_titulo_pessoa" => "titulo",
                        "variavel_pessoa_nome" => "[[aluno][nome]]",
                        "variavel_pessoa_nome_primeiro" => "[[aluno][primeiro_nome]]",
                    )
                ),
                "class" => "span4" //Class do atributo HTML
            ),
            array(
                "id" => "form_botao_variaveis_conteudo", // Id do atributo HTML
                "nome" => "botao_variaveis_conteudo", // Name do atributo HTML
                "nomeidioma" => "form_botao_variaveis_conteudo", // Referencia a variavel de idioma
                "tipo" => "php", // Tipo do input
                "colunas" => 2,
                "botao_hide" => true,
                "valor" => array(
                    array(
                        "variavel_titulo_conteudo" => "titulo",
                        "variavel_proximo_conteudo" => "[[conteudo][proximo_conteudo]]",
                        "variavel_conteudo_anterior" => "[[conteudo][conteudo_anterior]]",
                    )
                ),
                "class" => "span4" //Class do atributo HTML
            ),
            array(
                "id" => "form_download_conteudo",
                "nome" => "download_conteudo",
                "nomeidioma" => "form_download_conteudo",
                "tipo" => "botao",
                "link" => "/" . $url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5] . "/pagedownload",
                "target" => "_blank",
                "textobotao" => "Download do Conteúdo em HTML"
            ),
            array(
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
                "pasta" => "avas_conteudos_imagem_exibicao",
                "download" => true,
                "download_caminho" => $url["0"]."/".$url["1"]."/".$url["2"]."/".$url["3"]."/".$url["4"]."/".$url["5"],
                "excluir" => true,
                "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_campo" => "imagem_exibicao", // Nome das colunas da tabela do banco de dados que retorna o valor.
                "ignorarsevazio" => true
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
                "ajudaidioma" => "form_ativo_ajuda",
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "idava_conteudo", // Id do atributo HTML
                "nome" => "idava", // Name do atributo HTML
                "tipo" => "hidden", // Tipo do input
                "valor" => 'return $this->url["3"];',
                "banco" => true
            ),
        )
    )
);

$config["formulario_linksacoes"] = array(
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
			"id" => "form_tipo",
			"nome" => "tipo",
			"nomeidioma" => "form_tipo",
			"tipo" => "select",
			"array" => "linksacoes", // Array que alimenta o select
			"class" => "span2", 
			"valor" => "tipo",
			"banco" => true,
			"banco_string" => true,
			"validacao" => array("required" => "tipo_vazio"), 
			),
		array(
				"id" => "form_url",
				"nome" => "url", 
				"nomeidioma" => "form_url",
				"tipo" => "input",
				"valor" => "url",
				"validacao" => array("required" => "url_vazio"), 
				"class" => "span6",
				"banco" => true,
				"banco_string" => true,
				),
	  array(
		"id" => "idava_conteudo", // Id do atributo HTML
		"nome" => "idava_conteudo", // Name do atributo HTML
		"tipo" => "hidden", // Tipo do input
		"valor" => 'return $this->url["5"];',
		"banco" => true
		),
		
	)
  )								  
);
						
$config["listagem_conteudos"] = array(
  array(
	"id" => "idconteudo",
	"variavel_lang" => "tabela_idconteudo", 
	"tipo" => "banco", 
	"coluna_sql" => "idconteudo", 
	"valor" => "idconteudo", 
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
	"coluna_sql" => "c.nome",
	"valor" => "nome",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  array(
	"id" => "exibir_ava", 
	"variavel_lang" => "tabela_exibir_ava", 
	"tipo" => "php",
	"coluna_sql" => "c.exibir_ava", 
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
	"id" => "data_cad", 
	"variavel_lang" => "tabela_datacad", 
	"coluna_sql" => "c.data_cad",
	"tipo" => "php", 
	"valor" => 'return formataData($linha["data_cad"],"br",1);',
	"tamanho" => "140"
  ), 
  array(
	"id" => "opcoes", 
	"variavel_lang" => "tabela_opcoes", 
	"tipo" => "php", 
	"valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$this->url["3"]."/".$this->url["4"]."/".$linha["idconteudo"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
	"busca_botao" => true,
	"tamanho" => "80"
  ) 
 );
						   						   
$linhaObj->Set("config",$config);						   
require '../classes/avas.conteudos.class.php';
require '../classes/avas.videos.class.php';
require '../classes/dataaccess/db.php';
require '../classes/dataaccess/mysql.php';
		
$linhaObj = new Conteudos();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7");	
	
$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde_conteudos"]);
$linhaObj->Set("idava",intval($url[3]));

$linhaObj->config["banco"] = $config["banco_conteudos"];
$linhaObj->config["formulario"] = $config["formulario_conteudos"];

if($_POST["acao"] == "salvar_conteudo"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
		
  if($_FILES) {
	foreach($_FILES as $ind => $val) {
	  $_POST[$ind] = $val;
	}
  }
  
  $linhaObj->Set("post",$_POST);
  if($_POST[$config["banco_conteudos"]["primaria"]]) {
      $linhaObj->Set("id",intval($url[5]));
      $salvar = $linhaObj->ModificarConteudo();
  }
  else {
      $salvar = $linhaObj->CadastrarConteudo();
  }

    if ($salvar["sucesso"] && (isset($_POST['html']['error']) && $_POST['html']['error'] != 4)){
        if ($_POST[$config["banco_conteudos"]["primaria"]]) {
            $linhaObj->Set("campos","c.html_servidor");
            $conteudoAtual = $linhaObj->RetornarConteudo();
            $conteudoHtml = file_get_contents($_SERVER["DOCUMENT_ROOT"]."/storage/avas_conteudos_html/".$conteudoAtual['html_servidor']);
            $conteudoHtml = str_replace(' style="background: white;"', '', $conteudoHtml);
            $conteudoHtml = str_replace('\'', "\\'", $conteudoHtml);
            $salvar = $linhaObj->ModificarCampoConteudo($conteudoHtml);
            $linhaObj->Set("id",intval($url[5]));
            $linhaObj->RemoverFrames();
        }
    }
  
  if($salvar["sucesso"]){
	if($_POST[$config["banco_conteudos"]["primaria"]]) {
	  $linhaObj->Set("pro_mensagem_idioma","modificar_sucesso");
	  $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
	} else {
	  $linhaObj->Set("pro_mensagem_idioma","cadastrar_sucesso");
	  $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	}
	$linhaObj->Processando();
  }
} elseif($_POST["acao"] == "remover_conteudo") {
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|9");
  $linhaObj->Set("post",$_POST);
  $remover = $linhaObj->RemoverConteudo();
  if($remover["sucesso"]){
      $linhaObj->Set("id",intval($url[5]));
      $linhaObj->RemoverFrames();
	$linhaObj->Set("pro_mensagem_idioma","remover_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	$linhaObj->Processando();
  }
}	elseif($_POST["acao"] == 'salvar_linkacao') {
	$linhaObj->config["banco"] = $config["banco_linksacoes"];
	$linhaObj->config["formulario"] = $config["formulario_linksacoes"];
	$linhaObj->Set("post",$_POST);
	
	if($_POST['tipo'] == 'A'){
		unset($linhaObj->config["formulario"][0]['campos']['2']['validacao']);
	}
	
  if($_POST[$config["banco_linkacoes"]["primaria"]]){ 
		$salvar = $linhaObj->ModificarConteudo();
	} else { 
		$salvar = $linhaObj->cadastrarLinkAcao();
	}

	if($salvar["sucesso"]){
		if($_POST[$config["banco_linkacoes"]["primaria"]]) {
			$linhaObj->Set("pro_mensagem_idioma","modificar_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
		} else {
			$linhaObj->Set("pro_mensagem_idioma","cadastrar_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
		}
		$linhaObj->Processando();
	}
} elseif($_POST['acao'] == 'remover_acaolink'){
	$linhaObj->config["banco"] = $config["banco_linksacoes"];
	$linhaObj->config["formulario"] = $config["formulario_linksacoes"];
	$linhaObj->Set("post",$_POST);
	$remover = $linhaObj->removerLinkAcao();
	
	if($remover["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","remover_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
		$linhaObj->Processando();
	}
} elseif($_POST["acao"] == "salvar_frames") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");

    if(!isset($_POST["pages"]) || $_POST["pages"] == "" || empty($_POST["pages"])) {
        $return["respondeCode"] = 0;
        $return["responseHTML"] = "<h5>Opa!</h5> <p>Não há nada para salvar ou atualizar :(</p>";

        die(json_encode($return));
    }

    $linhaObj->Set("post",$_POST);
    $linhaObj->Set("id",intval($url[5]));
    $conteudoConteudo = $linhaObj->prepararFrames();
    $salvar = $linhaObj->ModificarCampoConteudo($conteudoConteudo);

    if ($salvar["sucesso"]) {
        $return["responseCode"] = 1;
        $return["responseHTML"] = "<h5>Eba!</h5> <p>O site foi salvo com sucesso!</p>";
        die(json_encode($return));
    }
}

if(isset($url[5])){			
  if($url[5] == "cadastrar") {
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
      $linhaObj->config['formulario'] = $linhaObj->config['formulario_conteudos'] = $config['formulario_conteudos'] = $linhaObj->alterarConfigFormulario($config['formulario_conteudos'], ['conteudo_cke', 'conteudo_online', 'html']);
	include("idiomas/".$config["idioma_padrao"]."/formulario.conteudos.php");
	include("telas/".$config["tela_padrao"]."/formulario.conteudos.php");
	exit();
  } elseif($url[5] == "elementos") {
      require '../assets/plugins/ibrepbuilder/elements.php';
      exit;
  } else {
	$linhaObj->Set("id",intval($url[5]));
	$linhaObj->Set("campos","c.*, a.nome as ava");	
	$linha = $linhaObj->RetornarConteudo();

	if($linha) {
	  switch($url[6]) {
		case "editar":
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
		  if ($linha["tipo_edicao"] == "B") {
              $linhaObj->config['formulario'] = $linhaObj->config['formulario_conteudos'] = $config['formulario_conteudos'] = $linhaObj->alterarConfigFormulario($config['formulario_conteudos'], ['conteudo_cke', 'html'], ['conteudo_online' => 'conteudo']);
          } elseif ($linha["tipo_edicao"] == "T") {
              $linhaObj->config['formulario'] = $linhaObj->config['formulario_conteudos'] = $config['formulario_conteudos'] = $linhaObj->alterarConfigFormulario($config['formulario_conteudos'], ['conteudo_online', 'html'], ['conteudo_cke' => 'conteudo']);
              //var_dump($linhaObj->config['formulario'][0]['campos']);
              //exit;
          } elseif ($linha["tipo_edicao"] == "A") {
              $linhaObj->config['formulario'] = $linhaObj->config['formulario_conteudos'] = $config['formulario_conteudos'] = $linhaObj->alterarConfigFormulario($config['formulario_conteudos'], ['conteudo_cke', 'conteudo_online']);
        }
		  include("idiomas/".$config["idioma_padrao"]."/formulario.conteudos.php");
		  include("telas/".$config["tela_padrao"]."/formulario.conteudos.php");
		break;
		case "linksacoes":			
			// $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
			$linhaObj->set('campos', 'acl.*')
							->set('idconteudo', $url[5]);
			$arrayLinksAcoes = $linhaObj->retornarLinksAcoes();

		  include("idiomas/".$config["idioma_padrao"]."/formulario.linksacoes.php");
		  include("telas/".$config["tela_padrao"]."/formulario.linksacoes.php");
		break;
		case "remover":			
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|9");
		  include("idiomas/".$config["idioma_padrao"]."/remover.conteudos.php");
		  include("telas/".$config["tela_padrao"]."/remover.conteudos.php");
		break;
		case "opcoes":			
		  include("idiomas/".$config["idioma_padrao"]."/opcoes.conteudos.php");
		  include("telas/".$config["tela_padrao"]."/opcoes.conteudos.php");
		break;
		case "download":
		  include("telas/".$config["tela_padrao"]."/download.php");
		break;
		case "excluir":
		  include("idiomas/".$config["idioma_padrao"]."/excluir.arquivo.php");
		  $linhaObj->RemoverArquivo($url[2]."_".$url[4], $url[7], $linha, $idioma);
		break;
          case "editor":
              $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
              include("telas/".$config["tela_padrao"]."/index.conteudos.editor.php");
              break;
          case "html":
              $linhaObj->Set("campos","conteudo");
              $frame = $linhaObj->RetornarFrame(intval($url[7]));
              echo $frame['conteudo'];
              break;
          case "blocos":
              $linhaObj->Set("campos","c.*");
              $conteudo = $linhaObj->RetornarConteudo();
              $linhaObj->Set("campos","cf.*");
              $frames = $linhaObj->ListarTodosConteudosFrames();
              $conteudoDetails = array();
              $conteudoDetails['blocks'] = array();
              if($frames != false) {
                  foreach ($frames as $frame) {
                      array_push($conteudoDetails['blocks'], $frame);
                  }
              }
              $conteudoDetails['page_id'] = $conteudo['idconteudo'];
              $conteudoDetails['nome'] = $conteudo['nome'];
              $conteudoFrames[$conteudo['nome']] = $conteudoDetails;
              $siteArray['pages'] = $conteudoFrames;
              $siteArray['is_admin'] = 1;
              echo json_encode($siteArray);
              break;
          case "preview" :
              $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
              $linhaObj->Set("campos","c.conteudo");
              $conteudo = $linhaObj->RetornarConteudo();
              include("telas/".$config["tela_padrao"]."/index.conteudos.preview.php");
              header("X-XSS-Protection: 0");
              break;
          case "pagedownload" :
              $linhaObj->Set("campos","c.conteudo");
              $conteudo = $linhaObj->RetornarConteudo();
              include("telas/".$config["tela_padrao"]."/index.conteudos.download.php");
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
  $linhaObj->Set("pagina",$_GET["pag"]);
  if(!$_GET["ordem"]) $_GET["ordem"] = "asc";
  $linhaObj->Set("ordem",$_GET["ord"]);
  if(!$_GET["qtd"]) $_GET["qtd"] = -1;
  $linhaObj->Set("limite",intval($_GET["qtd"]));
  if(!$_GET["cmp"]) $_GET["cmp"] = $config["banco_conteudos"]["primaria"];
  $linhaObj->Set("ordem_campo",$_GET["cmp"]);
  $linhaObj->Set("campos","c.*, a.nome as ava");	
  $dadosArray = $linhaObj->ListarTodasConteudo();	
  include("idiomas/".$config["idioma_padrao"]."/index.conteudos.php");
  include("telas/".$config["tela_padrao"]."/index.conteudos.php");
}
?>