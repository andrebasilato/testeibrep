<?php
if (date("I"))
	$hora = date("H")-1;
else
	$hora = date("H");

$hora = date("Y-m-d H:i:s",mktime($hora, date("i")-5, date("s"), date("m"), date("d"), date("Y")));

//$sql = "select nome, email from usuarios_adm where ativo = 'S' and ultimo_view >= '".$hora."'";
$sql = "select 
			ua.nome, ua.avatar_servidor, uap.nome as perfil, c.nome as cidade 
		from 
			usuarios_adm ua 
			inner join usuarios_adm_perfis uap on (ua.idperfil = uap.idperfil)
			left outer join cidades c on (ua.idcidade = c.idcidade)
		where 
			ua.ativo = 'S' and ua.ultimo_view >= '".$hora."'";
$query_gestores = mysql_query($sql);
$total_gestores = mysql_num_rows($query_gestores);

$sql = "SELECT 
			p.nome, p.avatar_servidor, c.nome AS cidade
		FROM 
			professores p
		LEFT JOIN 
			cidades c 
		ON ( c.idcidade = p.idcidade ) 
		WHERE 
			p.ativo =  'S' 
		AND p.ultimo_view >= '".$hora."'";
$query_professores = mysql_query($sql);
$total_professores = mysql_num_rows($query_professores);

$sql = "select 
 			v.nome, v.avatar_servidor, c.nome AS cidade 
		FROM 
			vendedores v 
		LEFT JOIN 
			cidades c ON v.idcidade = c.idcidade 
		where 
			v.ativo = 'S' and v.ultimo_view >= '".$hora."'";
$query_vendedores = mysql_query($sql);
$total_vendedores = mysql_num_rows($query_vendedores);

$sql = "SELECT 
			p.nome, p.avatar_servidor, c.nome AS cidade
		FROM 
			pessoas p
			LEFT OUTER JOIN cidades c ON ( p.idcidade = c.idcidade ) 
		WHERE 
			p.ativo = 'S' and p.ultimo_view >= '".$hora."'";
$query_alunos = mysql_query($sql) or die(mysql_error());
$total_alunos = mysql_num_rows($query_alunos);

include("idiomas/".$config["idioma_padrao"]."/index.php");
include("telas/".$config["tela_padrao"]."/index.php");
?>