<?php

// Colocar curso

$config["funcionalidade"] = "funcionalidade";
$config["acoes"][1] = "visualizar";

$dias_min["pt_br"] = array(
	"1" => "Seg",
	"2" => "Ter",
	"3" => "Qua",
	"4" => "Qui",
	"5" => "Sex",
	"6" => "Sáb",
	"7" => "Dom",
);

$meses["pt_br"] = array(
    "01" => "Janeiro",
	"02" => "Fevereiro",
	"03" => "Março",
	"04" => "Abril",
	"05" => "Maio",
	"06" => "Junho",
	"07" => "Julho",
	"08" => "Agosto",
	"09" => "Setembro",
	"10" => "Outubro",
	"11" => "Novembro",
	"12" => "Dezembro",
);

$meses_min["pt_br"] = array(
    "01" => "Jan",
	"02" => "Fev",
	"03" => "Mar",
	"04" => "Abr",
	"05" => "Mai",
	"06" => "Jun",
	"07" => "Jul",
	"08" => "Ago",
	"09" => "Set",
	"10" => "Out",
	"11" => "Nov",
	"12" => "Dez",
);

$ano = date("Y");
$anos["pt_br"] = array(
    $ano => $ano,
	($ano-1) => ($ano-1),
	($ano-2) => ($ano-2),
);

$sqlSindicato = 'select idsindicato, nome_abreviado from sindicatos where ativo = "S"';
if($_SESSION['adm_gestor_sindicato'] != 'S')
	$sqlSindicato .= ' and idsindicato in ('.$_SESSION['adm_sindicatos'].')';	
//$sqlSindicato .= ' order by nome_abreviado';	
										
// Array de configuração para a formulario			
$config["formulario"] = array(
				  array("fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
						"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
						"campos" => array( // Campos do formulario
										 array(
												"id" => "idregiao",
												"nome" => "idregiao",
												"nomeidioma" => "form_idregiao",
												"tipo" => "select",
												"sql" => "SELECT idregiao, nome FROM regioes ORDER BY nome", // SQL que alimenta o select
												"sql_valor" => "idregiao", // Coluna da tabela que será usado como o valor do options
												"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
												"valor" => "idregiao",
												"sql_filtro" => "select * from regioes where idregiao=%",
												"sql_filtro_label" => "nome",															
												),
												
												
										
										 array(
												"id" => "idsindicato",
												"nome" => "idsindicato",
												"nomeidioma" => "form_idsindicato",
												"tipo" => "checkbox",
												"sql" => $sqlSindicato,
												"sql_ordem_campo" => "nome_abreviado", 												
												"sql_valor" => "idsindicato", 
												"sql_label" => "nome_abreviado",
												"valor" => "idsindicato",
												"sql_filtro" => "select * from sindicatos where idsindicato=%",
												"sql_filtro_label" => "nome_abreviado",
												//"validacao" => array("required" => "sindicato_vazio"),
												),
												
										 array(
												"id" => "idcurso",
												"nome" => "idcurso",
												"nomeidioma" => "form_idcurso",
												"tipo" => "checkbox",
												"sql" => "SELECT idcurso, nome FROM cursos", // SQL que alimenta o select
												"sql_ordem_campo" => "nome",
                                                "sql_valor" => "idcurso", // Coluna da tabela que será usado como o valor do options
												"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
												"valor" => "idcurso",
												"sql_filtro" => "select * from cursos where idcurso=%",
												"sql_filtro_label" => "nome",															
												),												
																							 
											  array(
												"id" => "form_de",
												"nome" => "de",
												"valor" => "de",
												"nomeidioma" => "form_de",
												"tipo" => "input",
												"class" => "span2",
												"validacao" => array("required" => "de_vazio"),
												"mascara" => "99/9999",
											  ),
											  array(
												"id" => "form_ate",
												"nome" => "ate",
												"valor" => "ate",
												"nomeidioma" => "form_ate",
												"tipo" => "input",
												"class" => "span2",
												"validacao" => array("required" => "ate_vazio"),
												"mascara" => "99/9999",
											  ),
											
										/*	
										 array(
												"id" => "idoferta",
												"nome" => "idoferta",
												"nomeidioma" => "form_idoferta",
												"tipo" => "select",
												"sql" => "SELECT idoferta, nome FROM ofertas WHERE ativo='S' ORDER BY nome", // SQL que alimenta o select
												"sql_valor" => "idoferta", // Coluna da tabela que será usado como o valor do options
												"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
												"valor" => "idoferta",
												"sql_filtro" => "select * from ofertas where idoferta=%",
												"sql_filtro_label" => "nome",															
												),	
										*/
										
										  )
										  
						)					  
			);						
											
?>