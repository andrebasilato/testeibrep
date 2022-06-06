<?php
// Array de configuração para a listagem	
$config["listagem"] = array(
    array(
        "id" => "idpessoa",
        "variavel_lang" => "tabela_idpessoa",
        "tipo" => "php",
        "coluna_sql" => "idpessoa",
        "valor" => '
			$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
			if($diferenca > 24) {
				return "<span title=\"$diferenca\">".$linha["idpessoa"]."</span>";
			} else {
				return "<span title=\"$diferenca\">".$linha["idpessoa"]."</span> <i class=\"novo\"></i>";
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
        "coluna_sql" => "p.nome",
        "valor" => "nome",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2
    ),
    array(
        "id" => "documento",
        "variavel_lang" => "tabela_documento",
        "tipo" => "banco",
        "coluna_sql" => "p.documento",
        "valor" => "documento",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2
    ),
    array(
        "id" => "email",
        "variavel_lang" => "tabela_email",
        "tipo" => "banco",
        "evento" => "maxlength='100'",
        "coluna_sql" => "p.email",
        "valor" => "email",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2
    ),
    array(
        "id" => "telefone",
        "variavel_lang" => "tabela_telefone",
        "tipo" => "banco",
        "evento" => "maxlength='14'",
        "coluna_sql" => "p.telefone",
        "valor" => "telefone",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2
    ),
    array(
        "id" => "celular",
        "variavel_lang" => "tabela_celular",
        "tipo" => "php",
        "evento" => "maxlength='14'",
        "coluna_sql" => "p.celular",
        "valor" => '$retorno = $linha["celular"];
	if (trim($retorno) !== "")  {
                        $telefoneWhats = "55" . str_replace([" ", "-", "(", ")"], "", $retorno);

                        $primeiroNum = substr(str_replace([" ", "-", "(", ")"], "", $retorno), 2, 1);

                        if(strlen(str_replace([" ", "-", "(", ")"], "", $retorno)) == 11 && $primeiroNum > 6 && $primeiroNum < 10) {
                            $retorno .= "<div class=\"pull-right\">
                                <a class=\"btn btn-mini link-whatsapp\" href=\"https:\/\/api.whatsapp.com\/send?phone=". $telefoneWhats ."\" target=\"_blank\" style=\"font-size:14px;\" data-original-title=\"Abrir Whatsapp\" rel=\"tooltip\" data-placement=\"left\">
                                    <i class=\"fa fa-1x fa-whatsapp\"></i>
                                </a>
                            </div>";
                        }
                    }
	return $retorno;',
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2
    ),
    array(
        "id" => "data_cad",
        "variavel_lang" => "tabela_datacad",
        "tipo" => "php",
        "valor" => 'return formataData($linha["data_cad"],"br",1);',
        "tamanho" => "140",
        "coluna_sql" => "p.data_cad",
    ),
    array(
        "id" => "opcoes",
        "variavel_lang" => "tabela_opcoes",
        "tipo" => "php",
        "valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idpessoa"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
        "busca_botao" => true,
        "tamanho" => "80"
    )
);
?>