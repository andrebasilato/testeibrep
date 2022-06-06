<?php
$permissoes = unserialize($GLOBALS["usuario"]["perfil"]["permissoes"]);

$modulosPermissoes = array(
                        "academico" => array("nome" => "Acadêmico", "acesso" => false),
                        "cadastros" => array("nome" => "Cadastros", "acesso" => false),
                        "comercial" => array("nome" => "Comercial", "acesso" => false),
                        "configuracoes" => array("nome" => "Configurações", "acesso" => false),
                        "financeiro" => array("nome" => "Financeiro", "acesso" => false),
                        "juridico" => array("nome" => "Jurídico", "acesso" => false),
                        "relacionamento" => array("nome" => "Relacionamento", "acesso" => false),
                        "relatorios" => array("nome" => "Relatórios", "acesso" => false)
                    );

foreach($modulosPermissoes as $modulo => $dados){
		
    $diretorio = getcwd() . '/modulos//' . $modulo;
    $ponteiro  = opendir($diretorio);

    $pastasArray = array();

    while ($nome_itens = readdir($ponteiro)) {
        if ($nome_itens{0} != "_" && $nome_itens != "." && $nome_itens != ".." && $nome_itens != "index" && is_dir($diretorio.'//'.$nome_itens)) {	
            //Verificamos se ele ter permissão de listagem
            if($permissoes[$nome_itens."|1"]) {
                $modulosPermissoes[$modulo]["acesso"] = true;
            }
        }		
    }
}

$_SESSION["modulosPermissoes"] = $modulosPermissoes;