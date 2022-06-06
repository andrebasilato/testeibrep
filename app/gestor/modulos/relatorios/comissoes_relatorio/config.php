<?php
$config["funcionalidade"] = "funcionalidade";
$config["acoes"][1] = "visualizar";

$sqlSindicato = 'select idsindicato, nome_abreviado from sindicatos where ativo = "S"';
if($_SESSION['adm_gestor_sindicato'] != 'S')
    $sqlSindicato .= ' and idsindicato in ('.$_SESSION['adm_sindicatos'].')';
//$sqlSindicato .= ' order by nome_abreviado';

$sqlEscola = 'select idescola, razao_social from escolas where ativo = "S"';
if($_SESSION['adm_gestor_sindicato'] != 'S')
    $sqlEscola .= ' and idsindicato in ('.$_SESSION['adm_sindicatos'].')';
$sqlEscola .= ' order by razao_social';

// Array de configuração para a formulario
$config["formulario"] = array(
  array(
    "fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
    "legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
    "campos" => array( // Campos do formulario
      array(
        "id" => "form_idsindicato",
        "nome" => "idsindicato",
        "nomeidioma" => "form_idsindicato",
        "tipo" => "checkbox",
        "sql" => $sqlSindicato, // SQL que alimenta o select
        "sql_ordem_campo" => "nome_abreviado",
        "sql_ordem" => "asc",
        "sql_valor" => "idsindicato", // Coluna da tabela que será usado como o valor do options
        "sql_label" => "nome_abreviado", // Coluna da tabela que será usado como o label do options
        "valor" => "idsindicato",
        "sql_filtro" => "select * from sindicatos where idsindicato = %",
        "sql_filtro_label" => "nome_abreviado",
        "class" => "span4",
      ),
      array(
        "id" => "form_idescola",
        "nome" => "idescola",
        "nomeidioma" => "form_idescola",
        "tipo" => "select",
        "sql" => $sqlEscola,
        "sql_valor" => "idescola", // Coluna da tabela que será usado como o valor do options
        "sql_label" => "razao_social", // Coluna da tabela que será usado como o label do options
        "sql_filtro" => "select * from escolas where idescola = %",
        "valor" => "idescola",
        "sql_filtro_label" => "razao_social",
      ),
      array(
        "id" => "form_idregra",
        "nome" => "idregra",
        "nomeidioma" => "form_idregra",
        "tipo" => "select",
        "sql" => "select idregra, nome from comissoes_regras where ativo = 'S' and ativo_painel = 'S' order by nome", // SQL que alimenta o select
        "sql_valor" => "idregra", // Coluna da tabela que será usado como o valor do options
        "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
        "sql_filtro" => "select * from comissoes_regras where idregra = %",
        "valor" => "idregra",
        "sql_filtro_label" => "nome",
      ),
      array(
        "id" => "form_competencia_de",
        "nome" => "competencia_de",
        "valor" => "competencia_de",
        "nomeidioma" => "form_competencia_de",
        "tipo" => "input",
        "class" => "span2",
        "evento" => "onchange='validaIntervaloDatasUmAnoSemDia(\"form_competencia_de\",\"form_competencia_ate\")'",
        "validacao" => array("required" => "competencia_de_vazio"),
        "mascara" => "99/9999",
        //"datepicker" => true,
        //"ajudaidioma" => "form_competencia_de_ajuda",
      ),
      array(
        "id" => "form_competencia_ate",
        "nome" => "competencia_ate",
        "valor" => "competencia_ate",
        "nomeidioma" => "form_competencia_ate",
        "tipo" => "input",
        "class" => "span2",
        "evento" => "onchange='validaIntervaloDatasUmAnoSemDia(\"form_competencia_de\",\"form_competencia_ate\")'",
        "validacao" => array("required" => "competencia_ate_vazio"),
        "mascara" => "99/9999",
        //"datepicker" => true,

        //"ajudaidioma" => "form_competencia_ate_ajuda",
      ),
      /*array(
        "id" => "form_idgrupo",
        "nome" => "idgrupo",
        "nomeidioma" => "form_idgrupo",
        "tipo" => "select",
        "sql" => "select idgrupo, nome from grupos_vendedores where ativo = 'S' and ativo_painel = 'S' order by nome", // SQL que alimenta o select
        "sql_valor" => "idgrupo", // Coluna da tabela que será usado como o valor do options
        "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
        "sql_filtro" => "select * from grupos_vendedores where idgrupo = %",
        "valor" => "idgrupo",
        "sql_filtro_label" => "nome",
      ),*/
      array(
        "id" => "form_idvendedor",
        "nome" => "idvendedor",
        "nomeidioma" => "form_idvendedor",
        "tipo" => "select",
        "sql" => "select idvendedor, nome from vendedores where ativo = 'S' and ativo_login = 'S' order by nome", // SQL que alimenta o select
        "sql_valor" => "idvendedor", // Coluna da tabela que será usado como o valor do options
        "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
        "sql_filtro" => "select * from vendedores where idvendedor = %",
        "valor" => "idvendedor",
        "sql_filtro_label" => "nome",
      ),
    )
  )
);
?>