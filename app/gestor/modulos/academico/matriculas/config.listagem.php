<?php
$instAux = explode(",", $_SESSION["adm_sindicatos"]);

// Array de configuração para a listagem
$config["listagem"] = array(
	array(
		"id" => "m.idmatricula",
		"variavel_lang" => "tabela_idmatricula",
		"tipo" => "php",
		"coluna_sql" => "m.idmatricula",
		"valor" => '$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
                if($diferenca > 24) {
                    return "<span title=\"$diferenca\">".$linha["idmatricula"]."</span>";
                } else {
                    return "<span title=\"$diferenca\">".$linha["idmatricula"]."</span> <i class=\"novo\"></i>";
                }',
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 1,
		"tamanho" => 40
	),
	array(
		"id" => "idpessoa",
		"variavel_lang" => "tabela_codaluno",
		"tipo" => "banco",
		"evento" => "maxlength='100'",
		"coluna_sql" => "p.idpessoa",
		"valor" => "idpessoa",
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 1,
		"tamanho" => 40
	),
	array(
		"id" => "aluno",
		"variavel_lang" => "tabela_aluno",
		"tipo" => "banco",
		"evento" => "maxlength='100'",
		"coluna_sql" => "p.nome",
		"valor" => "aluno",
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 2,
		"tamanho" => 300
	),
	array(
		"id" => "documento",
		"variavel_lang" => "tabela_documento",
		"tipo" => "banco",
		"evento" => "maxlength='18'",
		"coluna_sql" => "p.documento",
		"valor" => "documento",
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 2,
		"tamanho" => 18
	),
	array(
		"id" => "oferta",
		"variavel_lang" => "tabela_oferta",
		"tipo" => "banco",
		"coluna_sql" => "o.nome",
		"valor" => "oferta",
		"busca" => true,

		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 2
	),
	array(
		"id" => "curso",
		"variavel_lang" => "tabela_curso",
		"tipo" => "php",
		"coluna_sql" => "m.idcurso",

		"valor" => 'return $linha["curso"]["nome"];',
		"busca" => true,
		"busca_tipo" => "select",
		"busca_class" => "inputPreenchimentoCompleto select231",
		"busca_sql" => "SELECT idcurso, CONCAT(idcurso, ' - ' ,nome) as nome_curso FROM cursos WHERE ativo = 'S'", // SQL que alimenta o select
		"busca_sql_valor" => "idcurso", // Coluna da tabela que será usado como o valor do options
		"busca_sql_label" => "nome_curso",
		"busca_metodo" => 1
	),
	array(
		"id" => "sindicato",
		"variavel_lang" => "tabela_sindicato",
		"tipo" => "php",
		"coluna_sql" => "m.idsindicato",
		"valor" => 'return $linha["sindicato"]["nome_abreviado"];',
		"busca" => true,
		"busca_tipo" => "select",
		"busca_class" => "inputPreenchimentoCompleto select231",
		"busca_sql" => "SELECT idsindicato, CONCAT(idsindicato, ' - ' ,nome_abreviado) as nome FROM sindicatos WHERE ativo = 'S'", // SQL que alimenta o select
		"busca_sql_valor" => "idsindicato", // Coluna da tabela que será usado como o valor do options
		"busca_sql_label" => "nome",
		"busca_metodo" => 1
	),
	array(
		"id" => "escola",
		"variavel_lang" => "tabela_escola",
		"tipo" => "php",
		"coluna_sql" => "m.idescola",
		"valor" => 'return $linha["escola"]["nome_fantasia"];',
		"busca" => true,
		"busca_tipo" => "select",
		"busca_class" => "inputPreenchimentoCompleto select231",
		"busca_sql" => "SELECT p.idescola, CONCAT(p.idescola, ' - ' ,i.nome_abreviado, ' - ',p.nome_fantasia) as nome FROM escolas p inner join sindicatos i on (p.idsindicato=i.idsindicato) WHERE p.ativo = 'S' and i.ativo='S' and p.ativo_painel='S' and i.ativo_painel='S' ", // SQL que alimenta o select
		"busca_sql_valor" => "idescola", // Coluna da tabela que será usado como o valor do options
		"busca_sql_label" => "nome",
		"busca_metodo" => 1,
		'tamanho' => 100
	),
	array(
		'id' => 'detran_situacao',
		'variavel_lang' => 'tabela_detran_situacao',
		'tipo' => 'php',
		'coluna_sql' => 'm.detran_situacao',
		'valor' => '
	        if ($linha["detran_situacao"] == "LI") {
	            return "
	                <span
	                data-original-title=\"" . $GLOBALS["situacaoDetran"][$GLOBALS["config"]["idioma_padrao"]][$linha["detran_situacao"]] . "\"
	                class=\"label label-success\"
	                data-placement=\"left\"
	                rel=\"tooltip\">
	                    " . $GLOBALS["situacaoDetran"][$GLOBALS["config"]["idioma_padrao"]][$linha["detran_situacao"]][0] . "
	                </span>";
	        } else {
	            return "
	                <span
	                data-original-title=\"" . $GLOBALS["situacaoDetran"][$GLOBALS["config"]["idioma_padrao"]][$linha["detran_situacao"]] . "\"
	                class=\"label label-important\"
	                data-placement=\"left\"
	                rel=\"tooltip\">
	                    " . $GLOBALS["situacaoDetran"][$GLOBALS["config"]["idioma_padrao"]][$linha["detran_situacao"]][0] . "
	                </span>";
	        }',
		'busca' => true,
		'busca_tipo' => 'select',
		'busca_class' => 'inputPreenchimentoCompleto',
		'busca_array' => 'situacaoDetran',
		'busca_metodo' => 1,
		'tamanho' => 60
	),
	array("id" => "faturada",
		"variavel_lang" => "tabela_faturada",
		"tipo" => "php",
		"coluna_sql" => "m.faturada",
		"valor" => 'if($linha["faturada"] == "S") {
                      return "<span>Sim</span>";
                    } else if($linha["faturada"] == "CUPOM") {
                    	return "<span>Cupom</span>";
                    } else {
                     return "<span>Não</span>";
                    }',
		"busca" => true,
		"busca_array" => "cupom_sim_nao",
		"busca_tipo" => "select",
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 1),
	array("id" => "situacao",
		"variavel_lang" => "tabela_situacao",
		"tipo" => "php",
		"coluna_sql" => "m.idsituacao",
		"valor" => 'return "<span data-original-title=\"".$linha["situacao"]."\" class=\"label\" style=\"background:#".$linha["situacao_cor_bg"]."; color:#".$linha["situacao_cor_nome"]."\" data-placement=\"left\" rel=\"tooltip\">".$linha["situacao"]."</span>";',
		"busca" => true,
		"busca_tipo" => "select",
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_sql" => "SELECT idsituacao, nome FROM matriculas_workflow WHERE ativo = 'S'", // SQL que alimenta o select
		"busca_sql_valor" => "idsituacao", // Coluna da tabela que será usado como o valor do options
		"busca_sql_label" => "nome",
		"busca_metodo" => 1),
	array("id" => "contratos_aceitos",
		"variavel_lang" => "tabela_contrato_aceito",
		"tipo" => "php",
		"coluna_sql" => "m.contratos_aceitos",
		"valor" => 'if($linha["contratos_aceitos"] == "S") {
                      return "<span>Sim</span>";
                    } else {
                     return "<span>Não</span>";
                    }',
		"busca" => true,
		"busca_array" => "sim_nao",
		"busca_tipo" => "select",
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 1),
	array(
		"id" => "data_primeiro_acesso",
		"variavel_lang" => "tabela_data_primeiro_acesso",
		"tipo" => "php",
		"coluna_sql" => "m.data_primeiro_acesso",
		"valor" => 'return formataData($linha["data_primeiro_acesso"],"br",1);',
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 3,
		'tamanho' => 60
	),
	array(
		"id" => "data_cad",
		"variavel_lang" => "tabela_datacad",
		"tipo" => "php",
		"coluna_sql" => "m.data_cad",
		"valor" => 'return formataData($linha["data_cad"],"br",1);',
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 3,
		'tamanho' => 60
	),
	array(
		"id" => "opcoes",
		"variavel_lang" => "tabela_opcoes",
		"tipo" => "php",
		"valor" => '
                    if($_SESSION["adm_gestor_sindicato"] == "S" || in_array($linha["idsindicato"], $GLOBALS["instAux"]) )
                    	$opcoes = "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_relatorio_individual_tooltip"]."\" href=\"/".$this->url["0"]."/relatorios/desempenho_individual/html?q[1|m.idmatricula]=".$linha["idmatricula"]."/\" target=\"_blank\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tabela_relatorio_individual"]."</a>
                        		   <a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_administrar_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idmatricula"]."/administrar\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tabela_administrar"]."</a>
                        		   ";
                       	if($linha["documentos"])
                       		$opcoes .= "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_baixar_documentos_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idmatricula"]."/downloaddocumento\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tabela_baixar_documentos"]."</a>";
                       	else
                       		$opcoes .= "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_baixar_documentos_tooltip"]."\" href=\"#\" onclick=\"alert(\' ". $idioma["tabela_baixar_sem_documentos"] ."\')\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tabela_baixar_documentos"]."</a>";
                    	if($linha["idfolha"])
                        return $opcoes . "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_folha_registro_diploma_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/folhasregistrosdiplomas/".$linha["idfolha"]."/diplomas\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tabela_folha_registro_diploma"]."</a>";
                        else
                        return $opcoes;
                   ',
		"busca_botao" => true,
		"tamanho" => "160"
	),
);

$config["listagem_detran"] = array(
	array(
		"id" => "idmatricula",
		"variavel_lang" => "tabela_idmatricula",
		"tipo" => "banco",
		"evento" => "maxlength='50'",
		"coluna_sql" => "m.idmatricula",
		"valor" => "idmatricula",
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 2,
		"tamanho" => 50
	),
	array(
		"id" => "aluno",
		"variavel_lang" => "tabela_aluno",
		"tipo" => "banco",
		"evento" => "maxlength='100'",
		"coluna_sql" => "p.nome",
		"valor" => "aluno",
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 2,
		"tamanho" => 300
	),
	array(
		"id" => "documento",
		"variavel_lang" => "tabela_documento",
		"tipo" => "banco",
		"evento" => "maxlength='100'",
		"coluna_sql" => "p.documento",
		"valor" => "documento",
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 2,
		"tamanho" => 150
	),
	array(
		"id" => "curso",
		"variavel_lang" => "tabela_curso",
		"tipo" => "php",
		"coluna_sql" => "m.idcurso",

		"valor" => 'return $linha["curso"]["nome"];',
		"busca" => true,
		"busca_tipo" => "select",
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_sql" => "SELECT idcurso, nome FROM cursos WHERE ativo = 'S'", // SQL que alimenta o select
		"busca_sql_valor" => "idcurso", // Coluna da tabela que será usado como o valor do options
		"busca_sql_label" => "nome",
		"busca_metodo" => 1
	),
	array(
		"id" => "sindicato",
		"variavel_lang" => "tabela_sindicato",
		"tipo" => "php",
		"coluna_sql" => "m.idsindicato",
		"valor" => 'return $linha["sindicato"]["nome_abreviado"];',
		"busca" => true,
		"busca_tipo" => "select",
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_sql" => "SELECT idsindicato, nome_abreviado FROM sindicatos WHERE ativo = 'S'", // SQL que alimenta o select
		"busca_sql_valor" => "idsindicato", // Coluna da tabela que será usado como o valor do options
		"busca_sql_label" => "nome_abreviado",
		"busca_metodo" => 1
	),
	array(
		"id" => "escola",
		"variavel_lang" => "tabela_escola",
		"tipo" => "php",
		"coluna_sql" => "m.idescola",
		"valor" => 'return $linha["escola"]["nome_fantasia"];',
		"busca" => true,
		"busca_tipo" => "select",
		"busca_class" => "inputPreenchimentoCompleto select231",
		"busca_sql" => "SELECT p.idescola, CONCAT(p.idescola, ' - ' ,i.nome_abreviado, ' - ',p.nome_fantasia) as nome FROM escolas p inner join sindicatos i on (p.idsindicato=i.idsindicato) WHERE p.ativo = 'S' and i.ativo='S' and p.ativo_painel='S' and i.ativo_painel='S' ", // SQL que alimenta o select
		"busca_sql_valor" => "idescola", // Coluna da tabela que será usado como o valor do options
		"busca_sql_label" => "nome",
		"busca_metodo" => 1,
		'tamanho' => 100
	),
	array(
		"id" => "data_conclusao",
		"variavel_lang" => "tabela_data_conclusao",
		"tipo" => "php",
		"coluna_sql" => "m.data_conclusao",
		"valor" => 'return formataData($linha["data_conclusao"],"br",0);',
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 3,
		"tamanho" => 300
	),
	array(
		"id" => "opcoes",
		"variavel_lang" => "tabela_opcoes",
		"tipo" => "php",
		"valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_administrar_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idmatricula"]."/administrar\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tabela_administrar"]."</a>";',
		"busca_botao" => true,
		"tamanho" => "160"
	),
);

$config["listagem_falha_biometrica"] = array(
	array(
		"id" => "m.idmatricula",
		"variavel_lang" => "tabela_idmatricula",
		"tipo" => "php",
		"coluna_sql" => "m.idmatricula",
		"valor" => '$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
                if($diferenca > 24) {
                    return "<span title=\"$diferenca\">".$linha["idmatricula"]."</span>";
                } else {
                    return "<span title=\"$diferenca\">".$linha["idmatricula"]."</span> <i class=\"novo\"></i>";
                }',
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 1,
		"tamanho" => 40
	),
	array(
		"id" => "aluno",
		"variavel_lang" => "tabela_aluno",
		"tipo" => "banco",
		"evento" => "maxlength='100'",
		"coluna_sql" => "p.nome",
		"valor" => "aluno",
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 2,
		"tamanho" => 300
	),
	array(
		"id" => "sindicato",
		"variavel_lang" => "tabela_sindicato",
		"tipo" => "php",
		"coluna_sql" => "m.idsindicato",
		"valor" => 'return $linha["sindicato"]',
		"busca" => true,
		"busca_tipo" => "select",
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_sql" => "SELECT idsindicato, nome_abreviado FROM sindicatos WHERE ativo = 'S'", // SQL que alimenta o select
		"busca_sql_valor" => "idsindicato", // Coluna da tabela que será usado como o valor do options
		"busca_sql_label" => "nome_abreviado",
		"busca_metodo" => 1
	),
	array(
		"id" => "escola",
		"variavel_lang" => "tabela_escola",
		"tipo" => "php",
		"coluna_sql" => "m.idescola",
		"valor" => 'return $linha["escola"];',
		"busca" => true,
		"busca_tipo" => "select",
		"busca_class" => "inputPreenchimentoCompleto select231",
		"busca_sql" => "SELECT p.idescola, CONCAT(p.idescola, ' - ' ,i.nome_abreviado, ' - ',p.nome_fantasia) as nome FROM escolas p inner join sindicatos i on (p.idsindicato=i.idsindicato) WHERE p.ativo = 'S' and i.ativo='S' and p.ativo_painel='S' and i.ativo_painel='S' ", // SQL que alimenta o select
		"busca_sql_valor" => "idescola", // Coluna da tabela que será usado como o valor do options
		"busca_sql_label" => "nome",
		"busca_metodo" => 1,
		'tamanho' => 100
	),
	array(
		"id" => "tipo_biometria",
		"variavel_lang" => "tabela_tipo_biometria",
		"tipo" => "banco",
		"coluna_sql" => "rec.tipo_biometria",
		"valor" => "tipo_biometria",
		"busca" => true,
		"busca_array" => "tipo_biometria",
		"busca_tipo" => "select",
		"busca_class" => "inputPreenchimentoCompleto",
		"tamanho" => 100,
		"busca_metodo" => 1
	),
	array(
		"id" => "data_biometria",
		"variavel_lang" => "tabela_data_falha_biometrica",
		"tipo" => "php",
		"coluna_sql" => "rec.data_falha",
		"valor" => 'return formataData($linha["data_falha"],"br",1);',
		"busca_class" => "inputPreenchimentoCompleto",
		"busca" => true,
		"busca_metodo" => 3,
		"tamanho" => 150
	),
	array(
		"id" => "liberacao_temporaria",
		"variavel_lang" => "tabela_liberacao_temporaria",
		"tipo" => "php",
		"coluna_sql" => "liberacao_temporaria",
		"valor" => 'if($linha["liberacao_temporaria"] == "S") {
			return "<span>Sim</span>";
		  } else {
		   return "<span>Não</span>";
		  }',
		"busca" => true,
		"busca_array" => "sim_nao",
		"busca_tipo" => "select",
		"busca_class" => "inputPreenchimentoCompleto",
		"tamanho" => 80,
		"busca_metodo" => 1
	),
	array(
		"id" => "envio_foto_documento_oficial",
		"variavel_lang" => "tabela_documento_foto",
		"tipo" => "php",
		"coluna_sql" => "envio_foto_documento_oficial",
		'busca' => true,
		'busca_tipo' => 'select',
		'busca_class' => 'inputPreenchimentoCompleto',
		'busca_array' => 'sim_nao',
		'busca_metodo' => 1,
		"valor" => 'if ($linha["envio_foto_documento_oficial"] == "S") {
	            return "
	                <span
	                data-original-title=\"SIM\"
	                class=\"label label-success\"
	                data-placement=\"left\"
	                rel=\"tooltip\">SIM
	                </span>";
	        } else {
	            return "
	                <span
	                data-original-title=\"NÃO\"
	                class=\"label label-important\"
	                data-placement=\"left\"
	                rel=\"tooltip\">NÃO
	                </span>";
	        }',
		"tamanho" => 100
	),
	array(
		"id" => "opcoes",
		"variavel_lang" => "tabela_opcoes",
		"tipo" => "php",
		"valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_administrar_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idmatricula"]."/administrar/reconhecimento\" data-placement=\"left\" rel=\"tooltip\">".$idioma["visualizar"]."</a>";',
		"busca_botao" => true,
		"tamanho" => "160"
	),
);
?>
