<?php
// Array de configuração para a listagem	
$config["listagem"] = array(
  array(
	"id" => "idmatricula",
	"variavel_lang" => "tabela_matricula", 
	"tipo" => "php", 
	"coluna_sql" => "ma.idmatricula", 
	"valor" => '
			$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
			if($diferenca > 24) {
				return "<span title=\"$diferenca\">".$linha["idmatricula"]."</span>";
			} else {
				return "<span title=\"$diferenca\">".$linha["idmatricula"]."</span> <i class=\"novo\"></i>";
			}
			',  
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 1,
	"tamanho" => 80
  ),							  								  
  array(
	"id" => "situacao", 
	"variavel_lang" => "tabela_situacao", 
	"tipo" => "php", 
	"coluna_sql" => "ma.idsituacao", 
	"valor" => 'return "<span class=\"label\" style=\"background:#".$linha["cor_bg"].";color:#".$linha["cor_nome"]."\">".$linha["situacao_nome"]."</span>";',
	"busca" => true,
	"tamanho" => 100,
	"busca_tipo" => "select",
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_sql" => "SELECT idsituacao, nome FROM matriculas_workflow WHERE ativo = 'S' AND fim <> 'S' AND cancelada <> 'S' AND inativa <> 'S'", // SQL que alimenta o select
	"busca_sql_valor" => "idsituacao", // Coluna da tabela que será usado como o valor do options
	"busca_sql_label" => "nome",
	"busca_metodo" => 1
  ),
  array(
	"id" => "aluno", 
	"variavel_lang" => "tabela_aluno",
	"tipo" => "banco",
	"coluna_sql" => "pe.nome",
	"valor" => 'aluno',	
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  array(
	"id" => "sindicato", 
	"variavel_lang" => "tabela_sindicato", 
	"tipo" => "banco", 
	"coluna_sql" => "ma.idsindicato", 
	"valor" => 'sindicato',
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
	"tipo" => "banco",
	"coluna_sql" => "ma.idescola", 
	"valor" => "escola",
	"busca" => true,
	"busca_tipo" => "select",
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_sql" => "SELECT p.idescola, CONCAT(i.nome_abreviado, ' - ',p.nome_fantasia) as nome FROM escolas p inner join sindicatos i on (p.idsindicato=i.idsindicato) WHERE p.ativo = 'S' and i.ativo='S' and p.ativo_painel='S' and i.ativo_painel='S' ", // SQL que alimenta o select
	"busca_sql_valor" => "idescola", // Coluna da tabela que será usado como o valor do options
	"busca_sql_label" => "nome",
	"busca_metodo" => 1 
  ),
  array(
	"id" => "curso", 
	"variavel_lang" => "tabela_curso",
	"tipo" => "banco",
	"tamanho" => 60,
	"coluna_sql" => "cu.nome",
	"valor" => "curso",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),								
  /*array(
	"id" => "oferta", 
	"variavel_lang" => "tabela_oferta",
	"tipo" => "banco",
	"coluna_sql" => "of.nome",
	"valor" => "oferta",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),*/
  array(
    "id" => "vendedor", 
    "variavel_lang" => "tabela_vendedor",
    "tipo" => "banco",
    "coluna_sql" => "ve.nome",
    "valor" => 'vendedor', 
    "busca" => true,
    "busca_class" => "inputPreenchimentoCompleto",
    "busca_metodo" => 2
  ),					
  /*array(
	"id" => "nome", 
	"variavel_lang" => "tabela_nome",
	"tipo" => "php",
	"valor" => 'if($linha["contrato"]) {
					return $linha["contrato"];
				} else {
					return $linha["arquivo"];
				}',
	"coluna_sql" => "nome",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 3
  ),*/
  array(
	"id" => "assinado", 
	"variavel_lang" => "tabela_assinado",
	"tipo" => "php",
	"valor" => '$style = "background-color:#060";
				if($linha["cancelado"])
					$style = "";
				if($linha["assinado"]) {
					return "<span class=\"label\" style=\"".$style."\" >".formataData($linha["assinado"],"br",1)."</span>";
				} else {
					return "<span class=\"label\" >Aguardando validação</span>";
				}',
	"coluna_sql" => "mc.assinado",
	"busca" => true,
	"busca_tipo" => "select",
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_array" => "sim_nao",
	"busca_metodo" => 4
  ),
  array(
	"id" => "assinado_devedor", 
	"variavel_lang" => "tabela_assinado_devedor",
	"tipo" => "php",
	"valor" => '$style = "background-color:#060";
				if($linha["cancelado"])
					$style = "";
				if($this->existeDevedorSolidario($linha["idmatricula"])) {
					if($linha["assinado_devedor"]) {
						return "<span class=\"label\" style=\"".$style."\" >".formataData($linha["assinado_devedor"],"br",1)."</span>";
					} else {
						return "<span class=\"label\" >Aguardando validação</span>";
					}
				} else {
					return "--";
				}',
	"coluna_sql" => "mc.assinado_devedor",
	"busca" => true,
	"busca_tipo" => "select",
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_array" => "sim_nao",
	"busca_metodo" => 4
  ),
  array(
	"id" => "validado", 
	"variavel_lang" => "tabela_validado",
	"tipo" => "php",
	"valor" => 'if($linha["validado"]) {
                  $background = "style=\"background-color:#060\"";
                  $situacaoAtual = 2;
                  $validado = formataData($linha["validado"],"br",1);
                } else if($linha["nao_validado"]){
                  $background = "style=\"background-color:#FF0000\"";
                  $situacaoAtual = 1;
                  $validado = formataData($linha["nao_validado"],"br",1);
                } else {
                  $background = "";
                  $situacaoAtual = 0;
                  $validado = "Aguardando validação";
                }
                if($linha["contrato"]) {
	              $nomeContrato = $linha["contrato"];
	            } else {
	              $nomeContrato = $linha["arquivo"];
	            }
                $href = "href=\"#validarcontrato\" rel=\"facebox\" onclick=\"validarContrato(\'".$linha["idmatricula"]."\',\'".$linha["idmatricula_contrato"]."\',\'".$nomeContrato."\',".$situacaoAtual.")\"";

                if($linha["cancelado"]) {
					$background = "";
					$href = "href=\"javascript:alert(\'Esse contrato foi cancelado, portanto não poderá ser validado pelo comercial ou cancelada a validação.\')\"";
				} elseif(!$linha["assinado"]) {
					$href = "href=\"javascript:alert(\'Esse contrato ainda não foi validado pelo aluno, portanto não poderá ser validado pelo comercial.\')\"";
				}

				if($linha["situacao"]["visualizacoes"][13] && $this->verificaPermissao($GLOBALS["perfil"]["permissoes"], $this->url["2"]."|2",false)) {
				  return "<a ".$href." >
							<span class=\"label\" ".$background.">
								".$validado."
							</span>
						</a>";
				} else {
					return "<span class=\"label\" ".$background.">
								".$validado."
							</span>";
				}',
	"coluna_sql" => "mc.validado",
	"busca" => true,
	"busca_tipo" => "select",
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_array" => "sim_nao",
	"busca_metodo" => 4
  ),
  array(
	"id" => "cancelado", 
	"variavel_lang" => "tabela_cancelado",
	"tipo" => "php",
	"valor" => 'if($linha["cancelado"]) {
                  $background = "style=\"background-color:#C00\"";
                  $cancelado = formataData($linha["cancelado"],"br",1);
                  $href = "href=\"javascript:alert(\'Justificativa: ".$linha["justificativa"]."\')\"";
				} else {
                  $background = "";
                  $cancelado = "Cancelar";
                  $href = "href=\"#cancelarcontrato\" rel=\"facebox\" onclick=\"attContrato(\'".$linha["idmatricula"]."\',\'".$linha["idmatricula_contrato"]."\');\"";
                }

				if($linha["situacao"]["visualizacoes"][14] && $this->verificaPermissao($GLOBALS["perfil"]["permissoes"], $this->url["2"]."|3",false)) {
				  return "<a ".$href." >
							<span class=\"label\" ".$background.">
								".$cancelado."
							</span>
						</a>";
				} else {
					return "<span class=\"label\" ".$background.">
								".$cancelado."
							</span>";
				}',
	"coluna_sql" => "mc.cancelado",
	"busca" => true,
	"busca_tipo" => "select",
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_array" => "sim_nao",
	"busca_metodo" => 4
  ),
  array(
	"id" => "download", 
	"variavel_lang" => "tabela_download",
	"tipo" => "php",
	"valor" => 'if($linha["contrato"]) {
					return "<a class=\"btn btn-mini\" href=\"/".$this->url["0"]."/".$this->url["1"]."/matriculas/".$linha["idmatricula"]."/administrar/contratos/contratopdf/".$linha["idmatricula_contrato"]."\" target=\"_blanck\" >PDF</a>
							&nbsp;
                          	<a class=\"btn btn-mini\" href=\"javascript:abrePopup(\'/".$this->url["0"]."/".$this->url["1"]."/matriculas/".$linha["idmatricula"]."/administrar/contratos/contrato/".$linha["idmatricula_contrato"]."\',\'contrato".$linha["idmatricula_contrato"]."\',\'scrollbars=yes,resizable=yes,width=800,height=600\')\" >ABRIR</a>";
				} else {
					return "<a class=\"btn btn-mini\" href=\"/".$this->url["0"]."/".$this->url["1"]."/matriculas/".$linha["idmatricula"]."/administrar/contratos/contratodownload/".$linha["idmatricula_contrato"]."\" target=\"_blanck\" >BAIXAR</a>";
				}'
  ),
  array(
	"id" => "opcoes", 
	"variavel_lang" => "tabela_opcoes", 
	"tipo" => "php", 
	"valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/matriculas/".$linha["idmatricula"]."/administrar/contratos\" data-placement=\"left\" rel=\"tooltip\" target=\"_blank\">".$idioma["tabela_abrir"]."</a>"',
	"busca_botao" => true,
	"tamanho" => "100"
  )
);						   
?>