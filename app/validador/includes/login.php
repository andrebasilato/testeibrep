<?php
$opValidacao= $_GET["opValidacao"];

if(!$opValidacao)
	$opValidacao = $_POST["opValidacao"];

$tipo_documento = $_POST["tipo_documento"];

if($opValidacao == "validacao" && $tipo_documento == "D"){
	//Validar aqui o código e mostrar as informaçãoes da declaração-------------
	$codigo_validacao = $_POST['txt_codigo'];

	$sql = "select
				  md.data_cad,
				  md.idmatriculadeclaracao,
				  p.nome as aluno,
				  dt.nome as tipo,
				  d.nome as declaracao,
                  m.data_conclusao
			from
			  	matriculas_declaracoes md
				inner join matriculas m on (md.idmatricula = m.idmatricula)
				inner join pessoas p on (p.idpessoa = m.idpessoa)
				inner join declaracoes d on (md.iddeclaracao = d.iddeclaracao)
				inner join declaracoes_tipos dt on (d.idtipo = dt.idtipo or md.idtipo = dt.idtipo)
			where
				md.cod_validacao = '{$codigo_validacao}' AND md.ativo = 'S'";
	$queryValidar = mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
	$total_valida = mysql_num_rows($queryValidar);

	if($total_valida == 1){
		$info = mysql_fetch_array($queryValidar);
        $info["tipo_documento"] = "declaracao";
	} else {
	  $_POST["msg"] = "codigo_invalido";
	  incluirLib("login",$config);
	  exit();
	}

}else if($opValidacao == "validacao" && $tipo_documento == "DC"){
	//Validar aqui o código e mostrar as informaçãoes da declaração-------------
	$codigo_validacao = $_POST['txt_codigo'];

	$sql = "select
				  frdm.data_cad,
				  frdm.idfolha_matricula,
				  p.nome as aluno,
				  c.nome as nomeCurso,
                  m.data_conclusao
			from
			  	folhas_registros_diplomas_matriculas frdm
				inner join matriculas m on (frdm.idmatricula = m.idmatricula)
				inner join pessoas p on (p.idpessoa = m.idpessoa)
				INNER JOIN cursos c ON (m.idcurso = c.idcurso)
			where
				frdm.cod_validacao = '{$codigo_validacao}' AND frdm.ativo = 'S'";
	$queryValidar = mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
	$total_valida = mysql_num_rows($queryValidar);

	if($total_valida == 1){
		$info = mysql_fetch_array($queryValidar);
        $info["tipo_documento"] = "certificado";
	} else {
	  $_POST["msg"] = "codigo_invalido";
	  incluirLib("login",$config);
	  exit();
	}

}else if($opValidacao == "validacao" && $tipo_documento == "H"){
	//Validar aqui o código e mostrar as informaçãoes da declaração-------------
	$codigo_validacao = $_POST['txt_codigo'];

	$sql = "select
				  frdm.data_cad,
				  frdm.idmatricula_historico,
				  p.nome as aluno,
                  m.data_conclusao
			from
			  	matriculas_historico frdm
				inner join matriculas m on (frdm.idmatricula = m.idmatricula)
				inner join pessoas p on (p.idpessoa = m.idpessoa)
			where
				frdm.cod_validacao = '{$codigo_validacao}' AND frdm.ativo = 'S'";
	$queryValidar = mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
	$total_valida = mysql_num_rows($queryValidar);

	if($total_valida == 1){
		$info = mysql_fetch_array($queryValidar);
        $info["tipo_documento"] = "historico";
	} else {
	  $_POST["msg"] = "codigo_invalido";
	  incluirLib("login",$config);
	  exit();
	}

}else {
	if(!isset($_SESSION["usu_professor_email"])){
		incluirLib("login",$config);
		exit();
	}
}
?>
