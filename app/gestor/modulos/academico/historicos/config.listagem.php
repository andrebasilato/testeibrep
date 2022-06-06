<?php
// Array de configuração para a listagem
$config["listagem"] = array(
    array(
        "id" => "idhistorico_escolar",
        "variavel_lang" => "tabela_idtipo",
        "tipo" => "php",
        "coluna_sql" => "idhistorico_escolar",
        "valor" => '
                $diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
                if($diferenca > 24) {
                    return "<span title=\"$diferenca\">".$linha["idhistorico_escolar"]."</span>";
                } else {
                    return "<span title=\"$diferenca\">".$linha["idhistorico_escolar"]."</span> <i class=\"novo\"></i>";
                }
                ',
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 1,
        "tamanho" => 80
    ),
  array(
    "id" => "nome",
    "variavel_lang" => "tabela_nome",
    "tipo" => "banco",
    "evento" => "maxlength='100'",
    "coluna_sql" => "nome",
    "valor" => "nome",
    "busca" => true,
    "busca_class" => "inputPreenchimentoCompleto",
    "busca_metodo" => 2
  ),
  array(
    "id" => "data_cad",
    "variavel_lang" => "tabela_datacad",
    "tipo" => "php",
    "coluna_sql" => "data_cad",
    "valor" => 'return formataData($linha["data_cad"],"br",1);',
    "tamanho" => "140"
  ),
    array(
    "id" => "ativo_painel",
    "variavel_lang" => "tabela_ativo_painel",
    "tipo" => "php",
    "coluna_sql" => "ativo_painel",
    "valor" => 'if($linha["ativo_painel"] == "S") {
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
    "id" => "opcoes",
    "variavel_lang" => "tabela_opcoes",
    "tipo" => "php",
    "valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"Clique aqui para ver as opções desta página\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idhistorico_escolar"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">Opções</a>"',
    "busca_botao" => true,
    "tamanho" => "80"
  )
);

// Array de configuração para a listagem
$config["paginas_lista"] = array(
  array(
    "id" => "idhistorico_escolar_paginas",
    "variavel_lang" => "tabela_idtipo",
    "tipo" => "php",
    "coluna_sql" => "idhistorico_escolar_paginas",
    "valor" => '
            $diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
            if($diferenca > 24) {
                return "<span title=\"$diferenca\">".$linha["idhistorico_escolar_paginas"]."</span>";
            } else {
                return "<span title=\"$diferenca\">".$linha["idhistorico_escolar_paginas"]."</span> <i class=\"novo\"></i>";
            }
            ',
    "busca" => true,
    "busca_class" => "inputPreenchimentoCompleto",
    "busca_metodo" => 1,
    "tamanho" => 80
  ),
    array(
    "id" => "ordem",
    "variavel_lang" => "tabela_ordem",
    "tipo" => "banco",
    "evento" => "maxlength='100'",
    "coluna_sql" => "ordem",
    "valor" => "ordem",
    "busca" => true,
    "busca_class" => "inputPreenchimentoCompleto",
    "busca_metodo" => 2,
    "tamanho" => 70
  ),
  array(
    "id" => "nome",
    "variavel_lang" => "tabela_nome",
    "tipo" => "php",
    "evento" => "maxlength='100'",
    "coluna_sql" => "nome",
	"valor" => 'return cortar($linha["nome"], 35);',
    "busca" => true,
    "busca_class" => "inputPreenchimentoCompleto",
    "busca_metodo" => 2,
  ),
  array(
    "id" => "data_cad",
    "variavel_lang" => "tabela_datacad",
    "tipo" => "php",
    "coluna_sql" => "data_cad",
    "valor" => 'return formataData($linha["data_cad"],"br",1);',
    "tamanho" => "140"
  ),
    array(
        "id" => "opcoes",
        "variavel_lang" => "tabela_opcoes",
        "tipo" => "php",
        "valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"Clique aqui para remover esta página\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idhistorico_escolar_paginas"]."/removerpagina\" data-placement=\"left\" rel=\"tooltip facebox\">Remover</a>&nbsp;<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"Clique aqui para remover esta página\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idhistorico_escolar_paginas"]."/visualizarpagina\" data-placement=\"left\" rel=\"tooltip facebox\">Visualizar</a>&nbsp;<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"Clique aqui para remover esta página\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idhistorico_escolar_paginas"]."/downloadpagina\" data-placement=\"left\" rel=\"tooltip \">Download</a>"',
        "busca_botao" => true,
        "tamanho" => "240"
    )
);

$config['midias_listagem'] = array(
    array(
        "id" => "idhistorico_escolar_midia",
        "variavel_lang" => "tabela_idtipo",
        "tipo" => "php",
        "coluna_sql" => "idhistorico_escolar_midia",
        "valor" => '
                $diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
                if($diferenca > 24) {
                    return "<span title=\"$diferenca\">".$linha["idhistorico_escolar_midia"]."</span>";
                } else {
                    return "<span title=\"$diferenca\">".$linha["idhistorico_escolar_midia"]."</span> <i class=\"novo\"></i>";
                }
                ',
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 1,
        "tamanho" => 80
    ),
        array(
            "id" => "nome",
            "variavel_lang" => "tabela_nome",
            "tipo" => "banco",
            "evento" => "maxlength='100'",
            "coluna_sql" => "nome",
            "valor" => "nome",
            "busca" => true,
            "busca_class" => "inputPreenchimentoCompleto",
            "busca_metodo" => 2
        ),
		
        array(
            "id" => "opcoes",
            "variavel_lang" => "tabela_tag",
            "tipo" => "php",
            "evento" => "maxlength='100'",
            "valor" => 'return "<code><small>[[midia][".$linha["idhistorico_escolar_midia"]."]]</small></code>";',
            "busca" => false,
            //"busca_botao" => true,
            //"busca_class" => "inputPreenchimentoCompleto",
            //"busca_metodo" => 2,
            "tamanho" => "130"
        ),
		
		array(
			"id" => "opcoes",
			"variavel_lang" => "tabela_opcoes",
			"tipo" => "php",
			"valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idhistorico_escolar"]."/imagens/".$linha["idhistorico_escolar_midia"]."/baixar_imagem\" >Download</a>"',
			"tamanho" => "80"
	    ),
		
		array(
			"id" => "opcoes",
			"variavel_lang" => "tabela_opcoes",
			"tipo" => "php",
			"valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"Clique aqui para remover esta imagem\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idhistorico_escolar"]."/imagens/".$linha["idhistorico_escolar_midia"]."/remover_imagem\" data-placement=\"left\" rel=\"tooltip \" onclick=\"return confirm(\'Deseja realmente excluir o arquivo? \')\">Remover</a>"',
			"busca_botao" => true,
			"tamanho" => "80"
	    )

);

// MIDIAS
$config['midias_lista'] = array(
  array(
    'fieldsetid' => 'dadosdoobjeto',
    'legendaidioma' => 'titulo_form',
    'campos' => array(
      array(
        'id' => 'form_nome',
        'nome' => 'nome',
        'tipo' => 'input',
        'banco' => true,
        'valor' => 'nome',
        'class' => 'span6',
        'validacao' => array('required' => 'nome_vazio'),
        'nomeidioma' => 'form_nome',
        'banco_string' => true
      ),
      array(
        'id' => 'form_file',
        'nome' => 'arquivo',
        'nomeidioma' => 'form_imagem',
        'arquivoidioma' => 'arquivo_enviado',
        'arquivoexcluir' => 'arquivo_excluir',
        'tipo' => 'file',
        'extensoes' => 'jpg|jpeg|gif|png|bmp',
        'largura' => 350,
        'altura' => 180,
        'validacao' => array('formato_arquivo' => 'arquivo_invalido'),
        'class' => 'span6',
        'pasta' => Historicos::PASTAMIDIAS,
        'download' => true,
        'excluir' => true,
        'banco' => true,
        'banco_campo' => 'imagem',
        'ignorarsevazio' => true
      )
    )
  )
);