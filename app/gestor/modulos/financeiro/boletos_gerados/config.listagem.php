<?php
// Array de configuração para a listagem
/*
idconta
numero
agencia
conta
banco
vencimento
emitente
referencia
descricao
matricula
valor
situacao

*/
$config["listagem"] = array(
    array(
        "id"            => "idconta",
        "variavel_lang" => "tabela_idconta",
        "tipo"          => "php",
        "coluna_sql"    => "c.idconta",
        "valor"         => '
			$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
			if($diferenca > 24) {
				return "<span title=\"$diferenca\">".$linha["idconta"]."</span>";
			} else {
				return "<span title=\"$diferenca\">".$linha["idconta"]."</span> <i class=\"novo\"></i>";
			}
			',
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 2,
        "tamanho"       => 100
    ),
   

    array(
        "id"            => "data_vencimento",
        "variavel_lang" => "tabela_vencimento",
        "tipo"          => "php",
        "coluna_sql"    => "data_vencimento",
        "valor"         => '
					return formataData($linha["data_vencimento"],"br",0);
				',
        "tamanho"       => "80",
        "nao_ordenar"   => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca"         => true,
        "busca_metodo"  => 3
    ),

    array(
        "id"            => "nome",
        "variavel_lang" => "tabela_referencia",
        "tipo"          => "banco",
        "coluna_sql"    => "c.nome",
        "valor"         => "nome",
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 2,
    ),

    /*array(
        "id"            => "documento",
        "variavel_lang" => "tabela_documento",
        "tipo"          => "banco",
        "coluna_sql"    => "c.documento",
        "valor"         => "documento",
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 2,
    ),*/


    array(
        "id"            => "valor",
        "variavel_lang" => "tabela_valor",
        "tipo"          => "php",
        "coluna_sql"    => "c.valor",
        "valor"         => '
				$rs = "<span style=\"color:gray; float:left\">R$</span>";
				if ($linha["qtde_contas"]){
					return "$rs <span style=\"color:green; float:right\"><strong>".number_format($linha["total"],2,",",".")."</strong></span>";
				}else{
					if($linha["valor"] < 0)
						return "$rs <span style=\"color:red; float:right\"><strong>".number_format(($linha["valor"]*-1),2,",",".")."</strong></span>";
					else
						return "$rs <span style=\"color:green; float:right\"><strong>".number_format($linha["valor"],2,",",".")."</strong></span>";
				}
				
				',
        "busca_class"   => "inputPreenchimentoCompleto",
        "nao_ordenar"   => true,
        "busca_metodo"  => 4,
		 "busca"           => true,
        "tamanho"       => 90
    ),

    array("id"              => "situacao",
          "variavel_lang"   => "tabela_situacao",
          "tipo"            => "php",
          "coluna_sql"      => "c.idsituacao",
          "tamanho"         => "100",
          "valor"           => 'if (!$linha["qtde_contas"]){
		  				return "<span data-original-title=\"".$linha["situacao"]."\" class=\"label\" style=\"background:#".$linha["situacao_cor_bg"]."; color:#".$linha["situacao_cor_nome"]."\" data-placement=\"left\" rel=\"tooltip\">".$linha["situacao"]."</span>";
		  			  }else
		  				return "--";',
          "busca"           => true,
          "nao_ordenar"     => true,
          "busca_tipo"      => "select",
          "busca_class"     => "inputPreenchimentoCompleto",
          "busca_sql"       => "SELECT idsituacao, nome FROM contas_workflow WHERE ativo = 'S'", // SQL que alimenta o select
          "busca_sql_valor" => "idsituacao", // Coluna da tabela que será usado como o valor do options
          "busca_sql_label" => "nome",
          "busca_metodo"    => 1),

    array(
        "id"            => "idmatricula",
        "variavel_lang" => "tabela_matricula",
        "tipo"          => "banco",
        "coluna_sql"    => "c.idmatricula",
        "valor"         => "idmatricula",
        "busca"         => true,
        "tamanho"       => "40",
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 2,
    ),
    array(
        "id"            => "aluno",
        "variavel_lang" => "tabela_aluno",
        "tipo"          => "banco",
        "coluna_sql"    => "p.nome",
        "valor"         => "aluno",
        "busca"         => true,
        "tamanho"       => "200",
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 2,
    ),

    array(
        "id"            => "opcoes",
        "variavel_lang" => "tabela_opcoes",
        "tipo"          => "php",
        "valor"         => 'if($linha["idpagamento_compartilhado"]){
					return "<a class=\"btn dropdown-toggle btn-mini\" href=\"/".$this->url["0"]."/".$this->url["1"]."/pagamentos_compartilhados/".$linha["idpagamento_compartilhado"]."/editar\"> Compartilhado </a>";
				}elseif($linha["idmatricula"]){
					return "<a class=\"btn dropdown-toggle btn-mini\" href=\"/".$this->url["0"]."/academico/matriculas/".$linha["idmatricula"]."/administrar#financeiromatricula\"> Matrícula</a>
					&nbsp;<a target=\"_blank\" class=\"btn dropdown-toggle btn-mini\" href=\"/loja/boleto/".$linha["idmatricula"]."/".$linha["idconta"]."\"> Boleto</a>
					";
				}',
        "busca_botao"   => true,
        "tamanho"       => "150"
    )
    /*array(
      "id" => "opcoes",
      "variavel_lang" => "tabela_opcoes",
      "tipo" => "php",
      "valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idconta"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
      "busca_botao" => true,
      "tamanho" => "80"
    ) */
);
?>