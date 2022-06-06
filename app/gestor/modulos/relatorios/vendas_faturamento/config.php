<?php
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
$sqlSindicato .= ' order by nome_abreviado';	
										
// Array de configuração para a formulario			
$config["formulario"] = array(
				  array("fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
						"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
						"campos" => array( // Campos do formulario
										 array(
												"id" => "idregiao",
												"nome" => "q[1|est.idregiao]",
												"nomeidioma" => "form_idregiao",
												"tipo" => "select",
												"sql" => "SELECT idregiao, nome FROM regioes ORDER BY nome", // SQL que alimenta o select
												"sql_valor" => "idregiao", // Coluna da tabela que será usado como o valor do options
												"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
												"valor" => "idregiao",
												"sql_filtro" => "select * from regioes where idregiao=%",
												"sql_filtro_label" => "nome",															
												),
										 /*array(
												"id" => "idmantenedora",
												"nome" => "q[7|mat.idmantenedora]",
												"nomeidioma" => "form_idmantenedora",
												"tipo" => "checkbox",
												"sql" => "SELECT idmantenedora, nome_fantasia FROM mantenedoras WHERE ativo='S' ",
												"sql_ordem_campo" => "nome_fantasia", 												
												"sql_valor" => "idmantenedora", 
												"sql_label" => "nome_fantasia",
												"valor" => "idmantenedora",
												"sql_filtro" => "select * from mantenedoras where idmantenedora=%",
												"sql_filtro_label" => "nome_fantasia"														
												),*/
										
										 array(
												"id" => "idsindicato",
												"nome" => "q[1|mat.idsindicato]",
												"nomeidioma" => "form_idsindicato",
												"tipo" => "select",
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
													"nome" => "q[1|mat.idcurso]",
													"nomeidioma" => "form_idcurso",
													"json" => true,
													"json_idpai" => "idsindicato",
													"json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"]."/ajax_cursos",
													"json_input_pai_vazio" => "form_selecione_sindicato",
													"json_input_vazio" => "form_selecione_curso",
													"json_campo_exibir" => "nome",
													"tipo" => "select",
													"valor" => "idcurso",
													"sql_filtro" => "SELECT * FROM cursos 
																	WHERE idcurso=%",
													"sql_filtro_label" => "nome",	
													//"validacao" => array("required" => "curso_vazio"),	
													),
										/*array(
												"id" => "idcurso",
												"nome" => "q[1|mat.idcurso]",
												"nomeidioma" => "form_idcurso",
												"tipo" => "select",
												"sql" => "SELECT idcurso, nome FROM cursos WHERE ativo='S' ",
												"sql_ordem_campo" => "nome", 												
												"sql_valor" => "idcurso", 
												"sql_label" => "nome",
												"valor" => "idcurso",
												"sql_filtro" => "select * from cursos where idcurso=%",
												"sql_filtro_label" => "nome",
												"validacao" => array("required" => "curso_vazio"),												
												),	*/									
										 /*array(
												"id" => "idescola",
												"nome" => "q[7|mat.idescola]",
												"nomeidioma" => "form_idescola",
												"tipo" => "checkbox",
												"sql" => "SELECT idescola, nome_fantasia FROM escolas WHERE ativo='S' ",
												"sql_ordem_campo" => "nome_fantasia", 												
												"sql_valor" => "idescola", 
												"sql_label" => "nome_fantasia",
												"valor" => "idescola",
												"sql_filtro" => "select * from escolas where idescola=%",
												"sql_filtro_label" => "nome_fantasia"														
												),*/
										 array(
												"id" => "idestado",
												"nome" => "q[1|est.idestado]",
												"nomeidioma" => "form_idestado",
												"tipo" => "select",
												"sql" => "SELECT idestado, nome FROM estados ORDER BY nome", // SQL que alimenta o select
												"sql_valor" => "idestado", // Coluna da tabela que será usado como o valor do options
												"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
												"valor" => "idestado",
												"sql_filtro" => "select * from estados where idestado=%",
												"sql_filtro_label" => "nome"															
												),
										 /*array(
												"id" => "idcidade",
												"nome" => "q[1|p.idcidade]",
												"nomeidioma" => "form_idcidade",
												"json" => true,
												"json_idpai" => "idestado",
												"json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"]."/ajax_cidades/",
												"json_input_pai_vazio" => "form_selecione_estado",
												"json_input_vazio" => "form_selecione_cidade",
												"json_campo_exibir" => "nome",
												"tipo" => "select",
												"valor" => "idcidade",
												"sql_filtro" => "select * from cidades where idcidade=%",
												"sql_filtro_label" => "nome"															
												),*/
										 
										 
										 
										 
										 array(
											"id" => "ano",
											"nome" => "ano",
											"nomeidioma" => "form_ano",
											"tipo" => "select",
											"array" => "anos",
											"class" => "span2", 
											"valor" => "ano",
											"validacao" => array("required" => "ano_vazio"),
											),	
										 array(
											"id" => "mes",
											"nome" => "mes",
											"nomeidioma" => "form_mes",
											"tipo" => "select",
											"array" => "meses",
											"class" => "span2",
											"validacao" => array("required" => "mes_vazio"),											
											"valor" => "mes",														
											),
											
											
											
											
										 array(
												"id" => "idoferta",
												"nome" => "q[1|mat.idoferta]",
												"nomeidioma" => "form_idoferta",
												"tipo" => "select",
												"sql" => "SELECT idoferta, nome FROM ofertas WHERE ativo='S' ORDER BY nome", // SQL que alimenta o select
												"sql_valor" => "idoferta", // Coluna da tabela que será usado como o valor do options
												"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
												"valor" => "idoferta",
												"sql_filtro" => "select * from ofertas where idoferta=%",
												"sql_filtro_label" => "nome",															
												),
										  )
						)					  
			);						
											
?>