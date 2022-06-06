<?php
require '../classes/avas.discovirtual.class.php';
require '../classes/filesystem.class.php';
require '../classes/dataaccess/select.php';
require '../classes/dataaccess/mysql.php';

$config['lista_pastas'] = array(
    array(
        'id' => 'icone',
        'nome' => 'icone',
        'tipo' => 'php',
        'busca_metodo' => 1,
        'busca' => false,
        'tamanho' => 40,
        'coluna_sql' => 'id_discovirtual',
        'valor' => 'return "<img src=\"/assets/icones/arquivos/folder.png\" />";',
    ),
    array(
        'id' => 'id_pasta',
        'variavel_lang' => 'id_pasta',
        'nome' => 'id_pasta',
        'tipo' => 'php',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 1,
        'tamanho' => 100,
        'coluna_sql' => 'id_pasta',
        'valor' => 'return $linha["id_pasta"];',
    ),
    array(
        'id' => 'nome',
        'nome' => 'nome',
        'variavel_lang' => 'nome_pasta',
        'tipo' => 'php',
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2,
        'coluna_sql' => 'nome',
        'valor' => 'return "<a href=\"".
                    sprintf(
                        "/%s/%s/%s/%d/%s",
                        $this->url[0],
                        $this->url[1],
                        $this->url[2],
                        $this->url[3],
                        $this->url[4]
                    ).
                    "/".$linha["id_pasta"]. "\">".$linha["nome"]. "</a>";',
    ),
    array(
        'id' => 'quantidade_de_arquivos',
        'nome' => 'quantidade_de_arquivos',
        'variavel_lang' => 'quantidade_de_arquivos',
        'tipo' => 'php',
        'busca' => false,
        'coluna_sql' => '',
        'valor' => '$count = mysql_fetch_assoc(
            mysql_query(
                "SELECT COUNT(*) as total FROM avas_discosvirtuais
                WHERE idpasta=".$linha["id_pasta"]
            )
        );

        return $count["total"];',
    ),
    array(
        'id' => 'data_cad',
        'nome' => 'data_cad',
        'variavel_lang' => 'data_cad',
        'tipo' => 'php',
        'busca' => false,
        'coluna_sql' => 'data_cad',
        'valor' => 'return formataData($linha["data_cad"],"br",1);',
    ),
    array(
        "id" => "opcoes",
        'variavel_lang' => 'opcoes',
        "tipo" => "php",
        "valor" => 'return
            "<div class=\'btn-group btn-mini\'>
              <a class=\"btn btn-mini dropdown-toggle\"
            href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"].' .
            '"/".$this->url["3"]."/".$this->url["4"]."/".$linha["idavaliacao"].' .
            '"".$linha["id_pasta"]."\"
                rel=\"tooltip\"
                data-original-title=\"".$idioma["abrir_pasta"]."\">
                   ".$idioma["btn_pasta"]."
                </a>
                 <button data-tipo=\"folder\" data-id=\"".$linha["id_pasta"]."\"
                        class=\"delete btn btn-mini dropdown-toggle\"
                            rel=\"tooltip\"
                            data-original-title=\"Excluir\">
                                <i class=\"icon-remove\"></i>
                </button>
            </div>

            "',
        'busca_botao' => true,
    )

);


$config['form_listaa'] = array(
    array(
        'id' => 'icone',
        'nome' => 'icone',
        'tipo' => 'php',
        'busca_metodo' => 1,
        'busca' => false,
        'tamanho' => 40,
        'coluna_sql' => 'id_discovirtual',
        'valor' => 'return "<img src=\"/assets/icones/arquivos/".str_replace("/", "-", $linha["tipo"]).".png\" />";',
    ),
    array(
        'id' => 'id_unico',
        'nome' => 'id_unico',
        'tipo' => 'php',
        'busca_class' => 'span1',
        'busca_metodo' => 1,
        'busca' => true,
        'tamanho' => 100,
        'coluna_sql' => 'id_discovirtual',
        'valor' => 'return $linha["id_discovirtual"];',
    ),
    array(
        'id' => 'nome',
        'busca' => true,
        'busca_metodo' => 2,
        'busca_class' => 'span4',
        'variavel_lang' => 'tabela_datacad',
        'coluna_sql' => 'nome_do_arquivo',
        'tipo' => 'banco',
        'valor' => 'nome_do_arquivo',
    ),
    array(
        'id' => 'data_cad',
        'variavel_lang' => 'tabela_datacad',
        'coluna_sql' => 'data_cad',
        'tipo' => 'php',
        'valor' => 'return formataData($linha["data_cad"],"br",1);',
    ),
    array(
        'id' => 'tipo',
        'variavel_lang' => 'tabela_datacad',
        'coluna_sql' => 'tipo',
        'tipo' => 'php',
        'valor' => 'return $linha["tipo"];',
    ),
    array(
        "id" => "opcoes",
        "variavel_lang" => "tabela_opcoes",
        "tipo" => "php",
        "valor" => 'return "<!--a class=\"btn dropdown-toggle btn-mini\" ' .
            'data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" ' .
            'href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"].' .
            '"/".$this->url["3"]."/".$this->url["4"]."/".$linha["idavaliacao"].' .
            '"/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".' .
            '$idioma["tabela_opcoes"]' .
            '."</a-->
            <div id=\"tool-{$linha["id_discovirtual"]}\" style=\"display: none\">
                http://{$_SERVER["SERVER_NAME"]}/storage/discovirtual/{$linha["idava"]}/{$linha["idpasta"]}/{$linha["nome_no_disco"]}
            </div>
            <div class=\'btn-group btn-mini\'>
                <a href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"].' .
                        '"/".$this->url["3"]."/".$this->url["4"]."/".
                        $this->url["5"]."/download/".$linha["id_discovirtual"]."\"
                        class=\"btn btn-mini\"
                        >
                        download
                </a>
                <a data-id=\"".$linha["id_discovirtual"]."\"
                        data-tipo=\"file\"
                        class=\"copy btn btn-mini dropdown-toggle\"
                        data-original-title=\"Link para o arquivo\"
                        data-placement=\"left\"
                        rel=\"tooltip facebox\"
                        href=\"#tool-{$linha["id_discovirtual"]}\">
                            <i class=\"icon-file\"></i>
                </a>
                <button data-id=\"".$linha["id_discovirtual"]."\"
                        data-tipo=\"file\"
                        class=\"delete btn btn-mini dropdown-toggle\"
                        rel=\"tooltip\"
                        data-original-title=\"Excluir\">
                            <i class=\"icon-remove\"></i>
                </button>
            </div>
            "',
        'busca_botao' => true,
        'tamanho' => 190
    )
);

$linhaObj->set('config', $config);

try {
    /** @var $virtualDisk DiscoVirtual */
    $virtualDisk = $linhaObj = new DiscoVirtual(new Core, new Zend_Db_Select(new Zend_Db_Mysql()));

    if ($virtualDisk->hasPostRequest()) {
        $virtualDisk->processPostRequest()
            ->haltIfNecessary();
    }

    $virtualDisk->getDboConfig();
    $dadosArray = $virtualDisk->fetchAll();

    $options = $virtualDisk->fetchFolderList();

    require 'idiomas/'.$config['idioma_padrao'].'/discovirtual.php';
    require 'telas/'.$config['tela_padrao'].'/index.discovirtual.php';

} catch (Exception $error) {
    echo $error->getMessage();
    exit;
}