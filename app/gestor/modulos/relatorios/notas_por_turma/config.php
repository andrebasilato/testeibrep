<?php

$config["funcionalidade"] = "funcionalidade";
$config["acoes"][1] = "visualizar";

$sqlSindicato = 'select idsindicato, nome_abreviado from sindicatos where ativo = "S"';
if($_SESSION['adm_gestor_sindicato'] != 'S')
	$sqlSindicato .= ' and idsindicato in ('.$_SESSION['adm_sindicatos'].')';	
$sqlSindicato .= ' order by nome_abreviado';	

$sqlEscola = 'select idescola, razao_social from escolas where ativo = "S"';
if($_SESSION['adm_gestor_sindicato'] != 'S')
	$sqlEscola .= ' and idsindicato in ('.$_SESSION['adm_sindicatos'].')';	
$sqlEscola .= ' order by razao_social';

		$config["listagem"] = array(

							array("id" => "idmatricula", 
							  	  "variavel_lang" => "tabela_matricula",
							  	  "tipo" => "banco",
								  "coluna_sql" => "ma.idmatricula",
								  "valor" => 'idmatricula',	
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),

					  		array("id" => "aluno",
							  	  "variavel_lang" => "tabela_aluno", 
							  	  "tipo" => "banco", 
							  	  "coluna_sql" => "pe.nome", 
							  	  "valor" => "aluno", 
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 1,
								  "tamanho" => 60),


							array("id" => "tipo_avaliacao",
							  	  "variavel_lang" => "tabela_tipo_avaliacao", 
							  	  "tipo" => "banco", 
							  	  "coluna_sql" => "mnt.nome", 
							  	  "valor" => "tipo", 
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 1,
								  "tamanho" => 60),	  						


							/*array("id" => "disciplina",
							  	  "variavel_lang" => "tabela_disciplina", 
							  	  "tipo" => "banco", 
							  	  "coluna_sql" => "mn.iddisciplina", 
							  	  "valor" => 'disciplina', 
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 1,
								  "tamanho" => 60
								  ),*/

							array("id" => "modelo", 
							  	  "variavel_lang" => "tabela_modelo",
							  	  "tipo" => "banco",
								  "coluna_sql" => "mn.nome",
								  "valor" => 'modelo',	
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),
								 								  
							array("id" => "nota",
							  	  "variavel_lang" => "tabela_nota", 
							  	  "tipo" => "php", 
							  	  "coluna_sql" => "mn.nota", 
							  	  "valor" => 'if ($linha["nota_conceito"] == "S") {
												return notaConceito($linha["nota"]);
											} else {
												return number_format($linha["nota"],2,",",".");
											}', 
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 1,
								  "tamanho" => 60
							),

							array(
								"id" => "tipo_situacao",
								"variavel_lang" => "tabela_situacao", 
								"tipo" => "banco", 
								"valor" => "situacao", 
								"tamanho" => 60
							),
						);

						
// Array de configuração para a formulario
$config["formulario"] = array(
    array(
        'fieldsetid' => 'dadosdoobjeto',
        'legendaidioma' => 'legendadadosdados',
        'campos' => array(
        	/*
			array(
				"id" => "idmantenedora",
				"nome" => "q[1|ma.idmantenedora]",
				"nomeidioma" => "form_mantenedora",
				"tipo" => "select",
				"sql" => "SELECT idmantenedora, nome_fantasia FROM mantenedoras 
							WHERE ativo='S' ORDER BY nome_fantasia",
				"sql_valor" => "idmantenedora", 
				"sql_label" => "nome_fantasia", 																											
				"valor" => "idmantenedora",
					"sql_filtro" => "select * from mantenedoras where idmantenedora=%",
					"sql_filtro_label" => "nome_fantasia",																
			),
			*/
            array(
				"id" => "idsindicato",
				"nome" => "q[1|i.idsindicato]",
				"nomeidioma" => "form_sindicato",
				"tipo" => "select",
				"sql" => $sqlSindicato,
				"sql_valor" => "idsindicato", 
				"sql_label" => "nome_abreviado",																										
				"valor" => "idsindicato",
					"sql_filtro" => "select * from sindicatos where idsindicato=%",
					"sql_filtro_label" => "nome_abreviado",																
			),			
			array(
				"id" => "idescola",
				"nome" => "q[1|po.idescola]",
				"nomeidioma" => "form_escola",
				"tipo" => "select",
				"sql" => $sqlEscola,
				"sql_valor" => "idescola", 
				"sql_label" => "razao_social",																										
				"valor" => "idescola",
					"sql_filtro" => "select * from escolas where idescola=%",
					"sql_filtro_label" => "razao_social",																
			),
			array(
				"id" => "idturma",
				"nome" => "q[1|ma.idturma]",
				"nomeidioma" => "form_turma",
				"tipo" => "select",
				"sql" => "SELECT idturma, nome
						FROM ofertas_turmas 
						WHERE ativo='S' 
						ORDER BY nome",
				"sql_valor" => "idturma", 
				"sql_label" => "nome",																										
				"valor" => "idturma",
					"sql_filtro" => "SELECT * FROM ofertas_turmas WHERE idturma=%",
					"sql_filtro_label" => "nome",																
			),
			array(
				"id" => "iddisciplina",
				"nome" => "q[1|mn.iddisciplina]",
				"nomeidioma" => "form_disciplina",
				"tipo" => "select",
				"sql" => "SELECT iddisciplina, nome
						FROM disciplinas 
						WHERE ativo='S' 
						ORDER BY nome",
				"sql_valor" => "iddisciplina", 
				"sql_label" => "nome",																										
				"valor" => "iddisciplina",
					"sql_filtro" => "SELECT * FROM disciplinas WHERE iddisciplina=%",
					"sql_filtro_label" => "nome",																
			),
			array(
				"id" => "idtipo",
				"nome" => "q[1|mnt.idtipo]",
				"nomeidioma" => "form_tipo",
				"tipo" => "select",
				"sql" => "SELECT idtipo, nome
						FROM matriculas_notas_tipos 
						WHERE ativo='S' 
						ORDER BY idtipo",
				"sql_valor" => "idtipo", 
				"sql_label" => "nome",																										
				"valor" => "idtipo",
					"sql_filtro" => "SELECT * FROM matriculas_notas_tipos WHERE idtipo=%",
					"sql_filtro_label" => "nome",																
			),
			
            /*array(
				"id" => "idcurso",
				"nome" => "q[1|cu.idcurso]",
				"nomeidioma" => "form_curso",
				"tipo" => "select",
				"sql" => "SELECT idcurso, nome
						FROM cursos 
						WHERE ativo='S' 
						ORDER BY nome",
				"sql_valor" => "idcurso", 
				"sql_label" => "nome",																										
				"valor" => "idcurso",
					"sql_filtro" => "SELECT * FROM cursos WHERE idcurso=%",
					"sql_filtro_label" => "nome",																
			),*/
			
			array(
					"id" => "idoferta",
					"nome" => "q[1|ma.idoferta]",
					"nomeidioma" => "form_idoferta",
					"tipo" => "select",
					"sql" => "SELECT idoferta, nome 
							FROM ofertas 
							WHERE ativo='S' 
							ORDER BY nome", // SQL que alimenta o select
					"sql_valor" => "idoferta", // Coluna da tabela que será usado como o valor do options
					"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
					"valor" => "idoferta",
					"class" => "span3",
					"sql_filtro" => "SELECT * FROM ofertas 
									WHERE idoferta=%",
					"sql_filtro_label" => "nome",																
					),
			array(
					"id" => "idcurso",
					"nome" => "q[1|ma.idcurso]",
					"nomeidioma" => "form_curso",
					"json" => true,
					"sql" => "SELECT idcurso, nome FROM cursos WHERE ativo='S' ORDER BY nome",
					"sql_valor" => "idcurso", // Coluna da tabela que será usado como o valor do options
					"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
					"json_idpai" => "idoferta",
					"json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"]."/ajax_cursos/",
					"json_input_pai_vazio" => "form_selecione_oferta",
					"json_input_vazio" => "form_selecione_curso",
					"json_campo_exibir" => "nome",
					"tipo" => "select",
					"valor" => "idcurso",
					"sql_filtro" => "SELECT * FROM cursos WHERE idcurso=%",
					"sql_filtro_label" => "nome",																
					),
			
        )
    )
);						
						
?>