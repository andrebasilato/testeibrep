<?
  // Array de configuração para a listagem	
  $config["listagem"] = array(
					  array("id" => "idpesquisa",
							"variavel_lang" => "tabela_idpesquisa", 
							"tipo" => "php", 
							"coluna_sql" => "idpesquisa", 
							"valor" => '
								$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
								if($diferenca > 24) {
									return "<span title=\"$diferenca\">".$linha["idpesquisa"]."</span>";
								} else {
									return "<span title=\"$diferenca\">".$linha["idpesquisa"]."</span> <i class=\"novo\"></i>";
								}
								',  
							"busca" => true,
							"busca_class" => "inputPreenchimentoCompleto",
							"busca_metodo" => 1,
							"tamanho" => 80),
							
					  array("id" => "nome", 
							"variavel_lang" => "tabela_nome",
							"tipo" => "banco",
							"evento" => "maxlength='100'",
							"coluna_sql" => "nome",
							"valor" => "nome",
							"busca" => true,
							"busca_class" => "inputPreenchimentoCompleto",
							"busca_metodo" => 2),
							
							
					  array("id" => "de", 
							"variavel_lang" => "tabela_de", 
							"coluna_sql" => "de",
							"tipo" => "php", 
							"valor" => 'return formataData($linha["de"],"br",0);',
							"tamanho" => "80"), 	
							
					  array("id" => "ate", 
							"variavel_lang" => "tabela_ate", 
							"coluna_sql" => "ate",
							"tipo" => "php", 
							"valor" => 'return formataData($linha["ate"],"br",0);',
							"tamanho" => "80"),
							
					  array("id" => "situacao", 
							"variavel_lang" => "tabela_ativo_painel", 
							"tipo" => "php",
							"coluna_sql" => "situacao", 
							"valor" => 'return "<span class=\"label\" style=\"background-color:".$linha["situacao_legenda_cor"]."\">".$linha["situacao_legenda"]."</span>";',
							"busca" => true,
							"busca_tipo" => "select",
							"busca_class" => "inputPreenchimentoCompleto",
							"busca_array" => "situacao_pesquisa",
							"busca_metodo" => 1),
							
					array("id" => "nao_envio", 
							"variavel_lang" => "tabela_nao_envio", 
							"coluna_sql" => "nao_enviados_email",
							"tipo" => "php", 
							"valor" => 'return $linha["nao_enviados_email"];',
							"tamanho" => "80",
							"busca" => false,
							"nao_ordenar" => true
							), 								   									  							  								   
					  array("id" => "enviados", 
							"variavel_lang" => "tabela_enviados", 
							"coluna_sql" => "enviados",
							"tipo" => "php", 
							"valor" => 'return $linha["enviados"];',
							"tamanho" => "80",
							"busca" => false,
							"nao_ordenar" => true
							),
							
					  array("id" => "total_reenvio", 
							"variavel_lang" => "tabela_total_reenvio", 
							"coluna_sql" => "total_reenvio",
							"tipo" => "php", 
							"valor" => 'return $linha["total_reenvio"];',
							"tamanho" => "80",
							"busca" => false,
							"nao_ordenar" => true
							),
							
					  array("id" => "respondidos", 
							"variavel_lang" => "tabela_respondidos", 
							"coluna_sql" => "respondidos",
							"tipo" => "php", 
							"valor" => 'return $linha["respondidos"];',
							"tamanho" => "80",
							"busca" => false,
							"nao_ordenar" => true,
							),								  								
									
					  array("id" => "data_cad", 
							"variavel_lang" => "tabela_datacad", 
							"coluna_sql" => "data_cad",
							"tipo" => "php", 
							"valor" => 'return formataData($linha["data_cad"],"br",1);',
							"tamanho" => "140"),
							  
					  array("id" => "opcoes", 
							"variavel_lang" => "tabela_opcoes", 
							"tipo" => "php", 
							"valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idpesquisa"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
							"busca_botao" => true,
							"tamanho" => "80") 
		  
);

$config["listagem_fila"] = array(
	array(
		"id" => "idpesquisa_pessoa",
	  	"variavel_lang" => "tabela_idfila", 
		"tipo" => "banco", 
		"coluna_sql" => "idpesquisa_pessoa", 
		"valor" => "idpesquisa_pessoa", 
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 1,
		"tamanho" => 60
	),	
	array(
		"id" => "nome", 
		"variavel_lang" => "tabela_nome",
		"coluna_sql" => "nome",
		"tipo" => "banco",
		"evento" => "maxlength='100'",
		"valor" => 'nome',
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 2
	),
	array(
		"id" => "email", 
		"variavel_lang" => "tabela_email",
		"tipo" => "banco",
		"evento" => "maxlength='100'",
		"coluna_sql" => "email",
		"valor" => 'email',
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 2
	),	
	array(
		"id" => "tipo", 
		"variavel_lang" => "tabela_tipo",
		"coluna_sql" => "tipo",
		"tipo" => "php", 
		"valor" => 'if($linha["tipo"] == "UA") {
					  return "<span data-original-title=\"".$idioma["tipo_usuario_adm"]."\" class=\"label\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tipo_usuario_adm"]."</span>";
			       } else if($linha["tipo"] == "MA") {
					  return "<span data-original-title=\"".$idioma["tipo_matricula"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tipo_matricula"]."</span>";
				   } else if($linha["tipo"] == "PR") {
					  return "<span data-original-title=\"".$idioma["tipo_professor"]."\" class=\"label label-warning\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tipo_professor"]."</span>";
				   } else if($linha["tipo"] == "PE") {
					  return "<span data-original-title=\"".$idioma["tipo_pessoa"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tipo_pessoa"]."</span>";
				   } ',
		"tamanho" => "100",
		"busca" => true,
		"busca_tipo" => "select",
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_array" => "tipo_pesquisa",
		"busca_metodo" => 2
	),
	array(
		"id" => "informacao", 
		"variavel_lang" => "tabela_informacao",
		"coluna_sql" => "informacao",
		"tipo" => "php",
		"valor" => 'if($linha["tipo"] == "UA") {
					  return "<a onclick=\"return confirma_pagina_informacao()\" href=\"/gestor/configuracoes/usuariosadm?q[1|u.idusuario]=".$linha["idusuario_adm"]."\"><span data-original-title=\"".$idioma["informacao_usuario_adm"]."\" data-placement=\"left\" rel=\"tooltip\">".$linha["idusuario_adm"]."</span></a>";
			       } else if($linha["tipo"] == "MA") {
					  return "<a onclick=\"return confirma_pagina_informacao()\" href=\"/gestor/academico/matriculas?q[1|m.idmatricula]=".$linha["idmatricula"]."\"><span data-original-title=\"".$idioma["informacao_matricula"]."\" data-placement=\"left\" rel=\"tooltip\">".$linha["idmatricula"]."</span></a>";
				   } else if($linha["tipo"] == "PR") {
					  return "<a onclick=\"return confirma_pagina_informacao()\" href=\"/gestor/cadastros/professores?q[1|p.idprofessor]=".$linha["idprofessor"]."\"><span data-original-title=\"".$idioma["informacao_professor"]."\" data-placement=\"left\" rel=\"tooltip\">".$linha["idprofessor"]."</span></a>";
				   } else if($linha["tipo"] == "PE") {
					  return "<a onclick=\"return confirma_pagina_informacao()\" href=\"/gestor/cadastros/pessoas?q[1|p.idpessoa]=".$linha["idpessoa"]."\"><span data-original-title=\"".$idioma["informacao_pessoa"]."\" data-placement=\"left\" rel=\"tooltip\">".$linha["idpessoa"]."</span></a>";
				   } ',
		"tamanho" => "100"
	),
	array(
		"id" => "data_cad", 
		"variavel_lang" => "tabela_data_cad", 
		"coluna_sql" => "data_cad",
		"tipo" => "php", 
		"valor" => 'return formataData($linha["data_cad"],"br",1);',
		"tamanho" => "80"
	),
	array(
		"id" => "data_enviado", 
		"variavel_lang" => "tabela_data_enviado", 
		"coluna_sql" => "pf.data_envio",
		"tipo" => "php", 
		"valor" => 'return formataData($linha["data_envio"],"br",1);',
		"tamanho" => "80"
	),
	array(
		"id" => "data_resposta", 
		"variavel_lang" => "tabela_data_resposta", 
		"coluna_sql" => "data_resposta",
		"tipo" => "php", 
		"valor" => 'return formataData($linha["data_resposta"],"br",1);',
		"tamanho" => "80"
	),
	array("id" => "envio_email", // Id do atributo
		  "variavel_lang" => "tabela_envio_email", // Referencia a variavel de idioma
		  "tipo" => "php", // Referencia ao tipo do campo (banco => o valor vem do banco de dados, php => é executado um codigo php que retorna o valor)
		  "coluna_sql" => "pf.enviar_email", 
		  "valor" => 'if($linha["enviarEmail"] == "S") {
						  return "<span data-original-title=\"".$idioma["sim"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">S</span>";
					  } else {
						  return "<span data-original-title=\"".$idioma["nao"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">N</span>";
					  }',
		  "busca" => true,
		  "busca_tipo" => "select",
		  "busca_class" => "inputPreenchimentoCompleto",
		  "busca_array" => "sim_nao",
		  "tamanho" => "60",
		  "busca_metodo" => 1),
	array(
		"id" => "idfiltro",
	  	"variavel_lang" => "tabela_idfiltro", 
		"tipo" => "php", 
		"valor" => 'return "<a href=\"#busca".$linha["idfiltro"]."\">".$linha["idfiltro"]."</a>";',
		"tamanho" => 20
	),
	array(
		"id" => "opcoes", 
		"variavel_lang" => "tabela_opcoes", 
		"tipo" => "php", 
		"valor" => '$resp = $this->verificaPesquisaRespondidaPorUsuario($linha["idpesquisa_pessoa"]); if ($resp == 0){ return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_responder_gestor"]."\" href=\"/pesquisa/".$this->url["1"]."/".$this->url["2"]."/".$linha["idpesquisa"]."/responder/".$linha["idpesquisa_pessoa"]."/".$linha["hash"]."/gestor\" data-placement=\"left\" rel=\"tooltip \" target=\"_blank\">".$idioma["responder_gestor"]."</a> <a href=\"javascript:void(0);\" class=\"btn btn-mini\" data-original-title=\"".$idioma["btn_remover"]."\" data-placement=\"left\" rel=\"tooltip\" onclick=\"remover(".$linha["idpesquisa_pessoa"].")\"><i class=\"icon-remove\"></i></a>";} else { 
		  			return "<a href=\"javascript:void(0);\" class=\"btn dropdown-toggle btn-mini\" onclick=\"window.open(\'/".$this->url[0]."/".$this->url[1]."/".$this->url[2]."/".$this->url[3]."/respostas/".$linha["idpesquisa_pessoa"]."\', \'respostas\', \'scrollbars=yes,width=1000,height=600\').focus()\">".$idioma["btn_popup"]."</a> ";}',
		"busca_botao" => true,
		"tamanho" => "110"
	)   
);
$config["listagem_add_fila"] = array(
	array(
		"id" => "opcoes", 
		"variavel_lang" => "tabela_add", 
		"tipo" => "php", 
		"valor" => 'return "<input size=\"1\" type=\"checkbox\" name=\"".id."[".$linha["id"]."]\" id=\"".id."[".$linha["id"]."]\" value=\"".$linha["nome"]."|".$linha["email"]."\"  />"',
	),	
	array(
		"id" => "nome", 
		"variavel_lang" => "tabela_nome",
		//"tipo" => "banco",
		"tipo" => "php",
		"coluna_sql" => "nome",
		"valor" => 'if($linha["ativo_login"] == "N" || $linha["ativo_painel"] == "N") {
					  if ($linha["venda_bloqueada"] == "S")
						return $linha["nome"]." <strong style=\"color:red;\"> (INATIVO e BLOQUEADO) </strong> ";
					  else
						return $linha["nome"]." <strong style=\"color:red;\"> (INATIVO) </strong> ";
			       } else if($linha["venda_bloqueada"] == "S") {
					  return $linha["nome"]." <strong style=\"color:red;\"> (BLOQUEADO) </strong> ";
			       } else {
					  return $linha["nome"];
				   }'
	),
	array(
		"id" => "email", 
		"variavel_lang" => "tabela_email",
		"tipo" => "banco",
		"coluna_sql" => "email",
		"valor" => "email"
	),
	array(
	"id" => "nao_envio", 
	"variavel_lang" => "tabela_envio", 
	"tipo" => "php", 
	"valor" => 'return "<input size=\"2\" type=\"checkbox\" name=\"nao_envio[".$linha["id"]."]\" id=\"nao_envio[".$linha["id"]."]\" value=\"N\"  />"',),

);

$config["listagem_add_fila_pessoa"] = array(
	array(
		"id" => "opcoes", 
		"variavel_lang" => "tabela_add", 
		"tipo" => "php", 
		"valor" => 'return "<input size=\"1\" type=\"checkbox\" name=\"".id."[".$linha["id"]."]\" id=\"".id."[".$linha["id"]."]\" value=\"".$linha["nome"]."|".$linha["email"]."\"  />"',
	),	
	array(
		"id" => "nome", 
		"variavel_lang" => "tabela_nome",
		//"tipo" => "banco",
		"tipo" => "php",
		"coluna_sql" => "nome",
		"valor" => 'if($linha["ativo_login"] == "N" || $linha["ativo_painel"] == "N") {
					  if ($linha["venda_bloqueada"] == "S")
						return $linha["nome"]." <strong style=\"color:red;\"> (INATIVO e BLOQUEADO) </strong> ";
					  else
						return $linha["nome"]." <strong style=\"color:red;\"> (INATIVO) </strong> ";
			       } else if($linha["venda_bloqueada"] == "S") {
					  return $linha["nome"]." <strong style=\"color:red;\"> (BLOQUEADO) </strong> ";
			       } else {
					  return $linha["nome"];
				   }'
	),
	array(
		"id" => "email", 
		"variavel_lang" => "tabela_email",
		"tipo" => "banco",
		"coluna_sql" => "email",
		"valor" => "email"
	),
	array(
		"id" => "nao_envio", 
		"variavel_lang" => "tabela_envio", 
		"tipo" => "php", 
		"valor" => 'return "<input size=\"2\" type=\"checkbox\" name=\"nao_envio[".$linha["id"]."]\" id=\"nao_envio[".$linha["id"]."]\" value=\"N\"  />"',
	),
);

$config["listagem_add_fila_matricula"] = array(
	array(
		"id" => "opcoes", 
		"variavel_lang" => "tabela_add", 
		"tipo" => "php", 
		"valor" => 'return "<input size=\"1\" type=\"checkbox\" name=\"".id."[".$linha["id"]."]\" id=\"".id."[".$linha["id"]."]\" value=\"".$linha["nome"]."|".$linha["email"]."\"  />"',
	),	
	array(
		"id" => "nome", 
		"variavel_lang" => "tabela_nome",
		//"tipo" => "banco",
		"tipo" => "php",
		"coluna_sql" => "nome",
		"valor" => 'if($linha["ativo_login"] == "N" || $linha["ativo_painel"] == "N") {
					  if ($linha["venda_bloqueada"] == "S")
						return $linha["nome"]." <strong style=\"color:red;\"> (INATIVO e BLOQUEADO) </strong> ";
					  else
						return $linha["nome"]." <strong style=\"color:red;\"> (INATIVO) </strong> ";
			       } else if($linha["venda_bloqueada"] == "S") {
					  return $linha["nome"]." <strong style=\"color:red;\"> (BLOQUEADO) </strong> ";
			       } else {
					  return $linha["nome"];
				   }'
	),
	array(
		"id" => "email", 
		"variavel_lang" => "tabela_email",
		"tipo" => "banco",
		"coluna_sql" => "email",
		"valor" => "email"
	),
	array(
		"id" => "matricula", 
		"variavel_lang" => "tabela_matricula",
		"tipo" => "banco",
		"coluna_sql" => "idmatricula",
		"valor" => "idmatricula"
	),
	
	array(
	  "id" => "nao_envio", 
	  "variavel_lang" => "tabela_envio", 
	  "tipo" => "php", 
	  "valor" => 'return "<input size=\"2\" type=\"checkbox\" name=\"nao_envio[".$linha["id"]."]\" id=\"nao_envio[".$linha["id"]."]\" value=\"N\"  />"',
	), 	
);
						   
?>