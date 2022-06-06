<?php
$config["formulario_avaliacoes"] = array(
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
                "id" => "form_avaliador",
                "nome" => "avaliador",
                "nomeidioma" => "form_avaliador",
                "botao_hide" => true,
                "iddivs" => array("periodo_correcao_dias","subjetivas_faceis","subjetivas_intermediarias","subjetivas_dificeis"),
                "tipo" => "select",
                "iddiv" => "periodo_correcao_dias",
                "iddiv2" => "subjetivas_faceis",
                "iddiv3" => "subjetivas_intermediarias",
                "iddiv4" => "subjetivas_dificeis",
                "array" => "tipo_avaliador", // Array que alimenta o select
                "class" => "span2",
                "valor" => "avaliador",
                "validacao" => array("required" => "avaliador_vazio"),
                "banco" => true,
                "banco_string" => true
            ),

            array(
                "id" => "form_periodo_correcao_dias",
                "nome" => "periodo_correcao_dias",
                "nomeidioma" => "form_periodo_correcao_dias",
                "tipo" => "input",
                "valor" => "periodo_correcao_dias",
                "validacao" => array("required" => "periodo_correcao_dias_vazio"),
                "class" => "span2",
                "banco" => true,
                "input_hidden" => true,
                "ajudaidioma" => "form_periodo_correcao_dias_ajuda",
                "numerico" => true
            ),

            array(
                "id" => "form_subjetivas_faceis",
                "nome" => "subjetivas_faceis",
                "nomeidioma" => "form_subjetivas_faceis",
                "tipo" => "input",
                "valor" => "subjetivas_faceis",
                "validacao" => array("required" => "subjetivas_faceis_vazio"),
                "class" => "span2",
                "banco" => true,
                "input_hidden" => true,
                //"banco_string" => true,
                "numerico" => true
            ),
            array(
                "id" => "form_subjetivas_intermediarias",
                "nome" => "subjetivas_intermediarias",
                "nomeidioma" => "form_subjetivas_intermediarias",
                "tipo" => "input",
                "valor" => "subjetivas_intermediarias",
                "validacao" => array("required" => "subjetivas_intermediarias_vazio"),
                "class" => "span2",
                "numerico" => true,
                "input_hidden" => true,
                "banco" => true,
            ),
            array(
                "id" => "form_subjetivas_dificeis",
                "nome" => "subjetivas_dificeis",
                "nomeidioma" => "form_subjetivas_dificeis",
                "tipo" => "input",
                "valor" => "subjetivas_dificeis",
                "validacao" => array("required" => "subjetivas_dificeis_vazio"),
                "class" => "span2",
                "banco" => true,
                "input_hidden" => true,
                "numerico" => true
            ),
            /*array(
              "id" => "form_periodo_correcao_de",
              "nome" => "periodo_correcao_de",
              "nomeidioma" => "form_periodo_correcao_de",
              "tipo" => "input",
              "valor" => "periodo_de",
              "validacao" => array("required" => "periodo_correcao_de_vazio"),
              "valor_php" => 'if($dados["periodo_correcao_de"]) return formataData("%s", "br", 0)',
              "class" => "span2",
              "mascara" => "99/99/9999",
              "datepicker" => true,
              "banco" => true,
              "banco_php" => 'return formataData("%s", "en", 0)',
              "input_hidden" => true,
              //"banco_string" => true
            ),
            array(
              "id" => "form_periodo_correcao_ate",
              "nome" => "periodo_correcao_ate",
              "nomeidioma" => "form_periodo_correcao_ate",
              "tipo" => "input",
              "valor" => "periodo_correcao_ate",
              "validacao" => array("required" => "periodo_correcao_ate_vazio"),
              "valor_php" => 'if($dados["periodo_correcao_ate"]) return formataData("%s", "br", 0)',
              "class" => "span2",
              "mascara" => "99/99/9999",
              "datepicker" => true,
              "banco" => true,
              "banco_php" => 'return formataData("%s", "en", 0)',
              "input_hidden" => true,
              //"banco_string" => true
            ),*/
            array(
                "id" => "form_objetivas_faceis",
                "nome" => "objetivas_faceis",
                "nomeidioma" => "form_objetivas_faceis",
                "tipo" => "input",
                "valor" => "objetivas_faceis",
                "validacao" => array("required" => "objetivas_faceis_vazio"),
                "class" => "span2",
                "banco" => true,
                "banco_string" => true,
                "numerico" => true
            ),
            array(
                "id" => "form_objetivas_intermediarias",
                "nome" => "objetivas_intermediarias",
                "nomeidioma" => "form_objetivas_intermediarias",
                "tipo" => "input",
                "valor" => "objetivas_intermediarias",
                "validacao" => array("required" => "objetivas_intermediarias_vazio"),
                "class" => "span2",
                "banco" => true,
                "banco_string" => true,
                "numerico" => true
            ),
            array(
                "id" => "form_objetivas_dificeis",
                "nome" => "objetivas_dificeis",
                "nomeidioma" => "form_objetivas_dificeis",
                "tipo" => "input",
                "valor" => "objetivas_dificeis",
                "validacao" => array("required" => "objetivas_dificeis_vazio"),
                "class" => "span2",
                "banco" => true,
                "banco_string" => true,
                "numerico" => true
            ),
            array(
                "id" => "iddisciplina_perguntas",
                "nome" => "iddisciplina_perguntas",
                "nomeidioma" => "form_iddisciplina_perguntas",
                "tipo" => "checkbox",
                "sql" => "select 
					d.iddisciplina, d.nome
				  from
					avas a
					inner join avas_disciplinas ad on (a.idava = ad.idava)
					inner join disciplinas d on (ad.iddisciplina = d.iddisciplina)
				  where 
					ad.ativo = 'S' and d.ativo = 'S' and a.idava = $url[3]",
                "sql_valor" => "iddisciplina", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "validacao" => array("required" => "disciplina_perguntas_vazio"),
                "ajudaidioma" => "form_ajuda_disciplina_perguntas",
                "class" => "span2 optionzzz"
            ),
            array(
                "id" => "iddisciplina_nota",
                "nome" => "iddisciplina_nota",
                "nomeidioma" => "form_iddisciplina_nota",
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
                "validacao" => array("required" => "disciplina_nota_vazio"),
                "ajudaidioma" => "form_ajuda_disciplina_nota",
                "valor" => "iddisciplina_nota",
                "class" => "span4",
                "banco" => true,
            ),
            array(
                "id" => "form_tempo",
                "nome" => "tempo",
                "nomeidioma" => "form_tempo",
                "tipo" => "input",
                "valor" => "tempo",
                //"validacao" => array("required" => "tempo_vazio"),
                "mascara" => "99:99:99",
                "class" => "span2",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "form_tempo_alerta",
                "nome" => "tempo_alerta",
                "nomeidioma" => "form_tempo_alerta",
                "tipo" => "input",
                "valor" => "tempo_alerta",
                "mascara" => "99:99:99",
                "class" => "span2",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "form_intervalo_tentativas",
                "nome" => "intervalo_tentativas",
                "nomeidioma" => "form_intervalo_tentativas",
                "tipo" => "input",
                "valor" => "intervalo_tentativas",
                //"validacao" => array("required" => "tempo_vazio"),
                "mascara" => '999:99:99?9',
                "class" => "span2",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "idtipo",
                "nome" => "idtipo",
                "nomeidioma" => "form_avaliacao",
                "tipo" => "select",
                "sql" => "SELECT idtipo, nome FROM matriculas_notas_tipos WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome ", // SQL que alimenta o select
                "sql_valor" => "idtipo", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "valor" => "idtipo",
                "class" => "span2",
                "validacao" => array("required" => "avaliacao_vazio"),
                "banco" => true
            ),
            array(
                "id" => "form_periode_de",
                "nome" => "periode_de",
                "nomeidioma" => "form_periode_de",
                "tipo" => "input",
                "valor" => "periode_de",
                "validacao" => array("required" => "periode_de_vazio"),
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
                "validacao" => array("required" => "periode_ate_vazio"),
                "valor_php" => 'if($dados["periode_ate"]) return formataData("%s", "br", 0)',
                "class" => "span2",
                "mascara" => "99/99/9999",
                "datepicker" => true,
                "banco" => true,
                "banco_php" => 'return formataData("%s", "en", 0)',
                "banco_string" => true
            ),
            array(
                "id" => "form_qtde_tentativas",
                "nome" => "qtde_tentativas",
                "nomeidioma" => "form_qtde_tentativas",
                "tipo" => "input",
                "valor" => "qtde_tentativas",
                "class" => "span2",
                "banco" => true,
                "banco_string" => true,
                "numerico" => true
            ),
            array(
                "id" => "form_nota_minima",
                "nome" => "nota_minima",
                "nomeidioma" => "form_nota_minima",
                "tipo" => "input",
                "valor" => "nota_minima",
                "class" => "span2",
                "banco" => true,
                "banco_string" => true,
                "decimal" => true
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
                "validacao" => array("formato_arquivo" => "arquivo_invalido_imagem_exibicao"),
                "class" => "span6", //Class do atributo HTML
                "pasta" => "avas_avaliacoes_imagem_exibicao",
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
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "idava_avaliacao", // Id do atributo HTML
                "nome" => "idava", // Name do atributo HTML
                "tipo" => "hidden", // Tipo do input
                "valor" => 'return $this->url["3"];',
                "banco" => true
            ),
        )
    )
);

$config["listagem_avaliacoes"] = array(
    array(
        "id" => "idavaliacao",
        "variavel_lang" => "tabela_idavaliacao",
        "tipo" => "banco",
        "coluna_sql" => "idavaliacao",
        "valor" => "idavaliacao",
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
        "coluna_sql" => "aa.nome",
        "valor" => "nome",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2
    ),
    array(
        "id" => "exibir_ava",
        "variavel_lang" => "tabela_exibir_ava",
        "tipo" => "php",
        "coluna_sql" => "aa.exibir_ava",
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
        "coluna_sql" => "aa.data_cad",
        "tipo" => "php",
        "valor" => 'return formataData($linha["data_cad"],"br",1);',
        "tamanho" => "140"
    ),
    array(
        "id" => "opcoes",
        "variavel_lang" => "tabela_opcoes",
        "tipo" => "php",
        "valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$this->url["3"]."/".$this->url["4"]."/".$linha["idavaliacao"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
        "busca_botao" => true,
        "tamanho" => "80"
    )
);

$linhaObj->Set('config', $config);
include('../classes/avas.avaliacoes.class.php');

$linhaObj = new Avaliacoes();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|28");

$linhaObj->set('idusuario', $usuario['idusuario'])
    ->set('monitora_onde', $config['monitoramento']['onde_avaliacoes'])
    ->set('idava', (int) $url[3]);

$linhaObj->config["banco"] = $config["banco_avaliacoes"];
$linhaObj->config["formulario"] = $config["formulario_avaliacoes"];

if($_POST["acao"] == "salvar_avaliacao"){
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|29");

    if($_FILES) {
        foreach($_FILES as $ind => $val) {
            $_POST[$ind] = $val;
        }
    }

    $linhaObj->Set("post",$_POST);
    $linhaObj->Set("id",$url[3]);
    if($_POST[$config["banco_avaliacoes"]["primaria"]])
        $salvar = $linhaObj->ModificarAvaliacao();
    else
        $salvar = $linhaObj->CadastrarAvaliacao();

    if($salvar["sucesso"]){
        if($_POST[$config["banco_avaliacoes"]["primaria"]]) {
            $linhaObj->Set("pro_mensagem_idioma","modificar_sucesso");
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
        } else {
            $linhaObj->Set("pro_mensagem_idioma","cadastrar_sucesso");
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
        }
        $linhaObj->Processando();
    }
} elseif($_POST["acao"] == "remover_avaliacao") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|30");
    $linhaObj->Set("post",$_POST);
    $remover = $linhaObj->RemoverAvaliacao();
    if($remover["sucesso"]){
        $linhaObj->Set("pro_mensagem_idioma","remover_sucesso");
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
        $linhaObj->Processando();
    }
}

if(isset($url[5])){
    $avaObj = new Ava();

    $avaObj->Set("idusuario",$usuario["idusuario"]);
    $avaObj->Set("monitora_onde",$config["monitoramento"]["onde_rotas_aprendizagem"]);
    $avaObj->Set("id",intval($url[3]));

    if($url[5] == "cadastrar") {

        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|29");

        $avaObj->Set("limite","-1");
        $avaObj->Set("ordem","asc");
        $avaObj->Set("ordem_campo","d.nome");
        $avaObj->Set("campos","d.*, ad.idava_disciplina");
        $disciplinasAva = $avaObj->ListarTodasDisciplinas();

        include("idiomas/".$config["idioma_padrao"]."/formulario.avaliacoes.php");
        include("telas/".$config["tela_padrao"]."/formulario.avaliacoes.php");
        exit();
    } else {
        $linhaObj->Set("id",intval($url[5]));
        $linhaObj->Set("campos","aa.*, a.nome as ava, 
	aa.iddisciplina_nota as iddisciplina");
        $linha = $linhaObj->RetornarAvaliacao();

        if($linha) {
            switch($url[6]) {
                case "editar":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|29");

                    $avaObj->Set("limite","-1");
                    $avaObj->Set("ordem","asc");
                    $avaObj->Set("ordem_campo","d.nome");
                    $avaObj->Set("campos","d.*, ad.idava_disciplina");
                    $disciplinasAva = $avaObj->ListarTodasDisciplinas();

                    $linhaObj->Set("limite","-1");
                    $linhaObj->Set("ordem","asc");
                    $linhaObj->Set("ordem_campo","avd.idavaliacao_disciplina");
                    $linhaObj->Set("campos","avd.*");
                    $disciplinasPerguntas = $linhaObj->RetornarDisciplinasPerguntas();
                    $qtdeTentativas = $linhaObj->retornarQtdeTotalTentativasAvaliacao();
                    include("idiomas/".$config["idioma_padrao"]."/formulario.avaliacoes.php");
                    include("telas/".$config["tela_padrao"]."/formulario.avaliacoes.php");
                    break;
                case "remover":
                    $qtdeTentativas = $linhaObj->retornarQtdeTotalTentativasAvaliacao();
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|30");
                    include("idiomas/".$config["idioma_padrao"]."/remover.avaliacoes.php");
                    include("telas/".$config["tela_padrao"]."/remover.avaliacoes.php");
                    break;
                case "opcoes":
                    include("idiomas/".$config["idioma_padrao"]."/opcoes.avaliacoes.php");
                    include("telas/".$config["tela_padrao"]."/opcoes.avaliacoes.php");
                    break;
                case "download":
                    include("telas/".$config["tela_padrao"]."/download.php");
                    break;
                case "excluir":
                    include("idiomas/".$config["idioma_padrao"]."/excluir.arquivo.php");
                    $linhaObj->RemoverArquivo($url[2]."_".$url[4], $url[7], $linha, $idioma);
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
    if(!$_GET["ordem"]) $_GET["ordem"] = "desc";
    $linhaObj->Set("ordem",$_GET["ord"]);
    if(!$_GET["qtd"]) $_GET["qtd"] = 30;
    $linhaObj->Set("limite",intval($_GET["qtd"]));
    if(!$_GET["cmp"]) $_GET["cmp"] = $config["banco_avaliacoes"]["primaria"];
    $linhaObj->Set("ordem_campo",$_GET["cmp"]);
    $linhaObj->Set("campos","aa.*, a.nome as ava");
    $dadosArray = $linhaObj->ListarTodasAvaliacao();
    include("idiomas/".$config["idioma_padrao"]."/index.avaliacoes.php");
    include("telas/".$config["tela_padrao"]."/index.avaliacoes.php");
}