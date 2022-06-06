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
    "id" => "oferta",
    "variavel_lang" => "tabela_oferta",
    "tipo" => "banco",
    "coluna_sql" => "of.nome",
    "valor" => "oferta",
    "busca" => true,
    "busca_class" => "inputPreenchimentoCompleto",
    "busca_metodo" => 2
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
  array(
    "id" => "escola",
    "variavel_lang" => "tabela_escola",
    "tipo" => "banco",
    "coluna_sql" => "po.nome_fantasia",
    "valor" => "escola",
    "busca" => true,
    "tamanho" => 80,
    "busca_class" => "inputPreenchimentoCompleto",
    "busca_metodo" => 2
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
    "id" => "tipo",
    "variavel_lang" => "tabela_tipo",
    "tipo" => "banco",
    "coluna_sql" => "td.idtipo",
    "valor" => "tipo",
    "busca" => true,
    "tamanho" => 100,
    "busca_tipo" => "select",
    "busca_class" => "inputPreenchimentoCompleto",
    "busca_sql" => "SELECT idtipo, nome FROM tipos_documentos WHERE ativo = 'S'", // SQL que alimenta o select
    "busca_sql_valor" => "idtipo", // Coluna da tabela que será usado como o valor do options
    "busca_sql_label" => "nome",
    "busca_metodo" => 1
  ),
  /*array(
    "id" => "associacao",
    "variavel_lang" => "tabela_associacao",
    "tipo" => "php",
    "coluna_sql" => "ta.idtipo",
    "valor" => 'if($linha["associacao"]) {
                  return $linha["associacao"];
                } else {
                  return " Titular ";
                }',
    "busca" => true,
    "tamanho" => 100,
    "busca_tipo" => "select",
    "busca_class" => "inputPreenchimentoCompleto",
    "busca_sql" => "SELECT idtipo, nome FROM tipos_associacoes WHERE ativo = 'S'", // SQL que alimenta o select
    "busca_sql_valor" => "idtipo", // Coluna da tabela que será usado como o valor do options
    "busca_sql_label" => "nome",
    "busca_metodo" => 1
  ),*/
  array(
    "id" => "arquivo_nome",
    "variavel_lang" => "tabela_arquivo_nome",
    "tipo" => "banco",
    "coluna_sql" => "md.arquivo_nome",
    "valor" => "arquivo_nome",
    "busca" => true,
    "busca_class" => "inputPreenchimentoCompleto",
    "busca_metodo" => 2
  ),
  array(
    "id" => "download",
    "variavel_lang" => "tabela_download",
    "tipo" => "php",
    "valor" => '
                if($linha["arquivo_nome"]) {
                    if(strpos($linha["arquivo_tipo"],"image") !== false) {
                        return "<a class=\"btn btn-mini fancybox\" href=\"/".$this->url["0"]."/".$this->url["1"]."/matriculas/".$linha["idmatricula"]."/administrar/documentos/visualizardocumento/".$linha["iddocumento"]."\" rel=\"gallery\" title=\"".$linha["tipo"]." (".$linha["arquivo_nome"].")\">".$idioma["tabela_visualizar"]."</a>";
                    } else {
                        return "<a class=\"btn btn-mini\" href=\"/".$this->url["0"]."/".$this->url["1"]."/matriculas/".$linha["idmatricula"]."/administrar/documentos/downloaddocumento/".$linha["iddocumento"]."\">".$idioma["tabela_download"]."</a>";
                    }
                } else {
                    return " - - ";
                }

                '
  ),
   array(
    "id" => "alterar_situacao",
    "variavel_lang" => "tabela_alterar_situacao",
    "tipo" => "php",
    "valor" => 'if($linha["situacao"]["visualizacoes"][8] && $this->verificaPermissao($GLOBALS["perfil"]["permissoes"], $this->url["2"]."|2",false)) {
                  return "<a href=\"/".$this->url[0]."/".$this->url[1]."/".$this->url[2]."/validardocumento/".$linha["iddocumento"]."/".$linha["idmatricula"]."\" rel=\"facebox\" >
                            <span class=\"label\" style=\"background-color:".$situacao_documento_cores[$linha["situacao_documento"]]."\" title=\"".$idioma["clique_alterar"]."\" rel=\"tooltip\">
                                ".$GLOBALS["situacao_documento"][$GLOBALS["config"]["idioma_padrao"]][$linha["situacao_documento"]]."
                            </span>
                        </a>";
                } else {
                    return "<span title=\"".$idioma["sem_permisao"]."\" rel=\"tooltip\" class=\"label\" style=\"background-color:".$situacao_documento_cores[$linha["situacao_documento"]]."\" title=\"".$idioma["clique_alterar"]."\" rel=\"tooltip\">
                                ".$GLOBALS["situacao_documento"][$GLOBALS["config"]["idioma_padrao"]][$linha["situacao_documento"]]."
                            </span>";
                }'
  ),
  array(
    "id" => "opcoes",
    "variavel_lang" => "tabela_opcoes",
    "tipo" => "php",
    "valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/matriculas/".$linha["idmatricula"]."/administrar#documentosmatricula\" data-placement=\"left\" rel=\"tooltip\" target=\"_blank\">".$idioma["tabela_abrir"]."</a>"',
    "busca_botao" => true,
    "tamanho" => "100"
  )
);
?>