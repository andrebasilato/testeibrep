<?php
// Array de configuração para a listagem
$config['listagem'] = array(
    array(
        'id' => 'idchat',
        'variavel_lang' => 'tabela_mural',
        'tipo' => 'php',
        'coluna_sql' => 'idchat',
        "valor" => '
            $diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d"), "H");
            if($diferenca > 24) {
                return "<span title=\"$diferenca\">".$linha["idchat"]."</span>";
            } else {
                return "<span title=\"$diferenca\">".$linha["idchat"]."</span> <i class=\"novo\"></i>";
            }
        ',
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 1,
        "tamanho" => 80
      ),
    array(
        "id" => "titulo",
        "variavel_lang" => "tabela_titulo",
        "tipo" => "banco",
        "coluna_sql" => "nome",
        "valor" => "nome",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2
    ),
    array(
        'id' => 'inicio_entrada_aluno',
        'variavel_lang' => 'tabela_inicio',
        'tipo' => 'php',
        'coluna_sql' => 'inicio_entrada_aluno',
        'valor' => 'return formataData($linha["inicio_entrada_aluno"],"br",1);',
        'busca' => false,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2
    ),
    array(
        'id' => 'fim_entrada_aluno',
        'variavel_lang' => 'tabela_encerrar',
        'tipo' => 'php',
        'coluna_sql' => 'fim_entrada_aluno',
        'valor' => 'return formataData($linha["fim_entrada_aluno"],"br",1);',
        'busca' => false,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2
    ),
    array(
      'id' => 'opcoes',
      'variavel_lang' => 'tabela_opcoes',
      'tipo' => 'php',
      'valor' => 'return "<a class=\"btn btn-mini\"
                    data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\"
                    onclick=\"window.open(\'/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idchat"]."\',\''.$linha['idchat'].'\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=900,height=650\');\" data-placement=\"left\">".$idioma["tabela_abrir"]."</a>"',
      "busca_botao" => true,
      "tamanho" => "80"
    )
);
