<?php
$situacaoArray = array();
$sql = 'select 
			mw.idsituacao, 
			mw.nome, 
			mw.cor_nome, 
			mw.cor_bg,
            mw.fim,
            mw.cancelada,
			(
				select count(1) from matriculas m where m.idsituacao = mw.idsituacao and m.ativo = "S"';

if($_SESSION["adm_gestor_sindicato"] <> "S") {
	$sql .= ' and m.idsindicato in ('.$_SESSION["adm_sindicatos"].')';
} elseif(intval($_GET['i'])) {
	$sql .= ' and m.idsindicato = '.intval($_GET['i']);
}

if(intval($_GET['c']))
	$sql .= ' and m.idcurso = '.intval($_GET['c']);

$sql .= ') as matriculas 
		 from 
			matriculas_workflow mw 
		where 
			mw.ativo = "S" 
		order by mw.ordem asc';

$contabiliza = mysql_query($sql);
while($situacao = mysql_fetch_assoc($contabiliza)){
	$situacaoArray[] = $situacao;
}

if($_SESSION["adm_gestor_sindicato"] <> "S"){
	$sindicatosArray = array();
	$sql = "select i.idsindicato, i.idmantenedora, i.nome as sindicato from usuarios_adm_sindicatos uai, sindicatos i where uai.idusuario = '".$informacoes["idusuario"]."' and uai.ativo='S' and uai.idsindicato=i.idsindicato and i.ativo='S'";
	$contabiliza = mysql_query($sql);
	while($sindicato = mysql_fetch_assoc($contabiliza)){
		$sindicatosArray[] = $sindicato;
	}
}

include("../classes/solicitacoesdeclaracoes.class.php");
$solicitacoesDeclaracoesObj = new SolicitacoesDeclaracoes();
$solicitacoesDeclaracoesObj->Set("idusuario",$_SESSION['adm_idusuario']);
$solicitacoesDeclaracoesObj->Set("campos","count(1) as total");
$solicitacoesDeclaracoesObj->Set("ordem","asc");
$_GET['q']['2|sd.situacao'] = 'E';
$declaracoes = $solicitacoesDeclaracoesObj->ListarTodas();
unset($_GET['q']['2|sd.situacao']);

include("../classes/provassolicitadas.class.php");
$provasSolicitadasObj = new Provas_Solicitadas();
$provasSolicitadasObj->Set("idusuario",$_SESSION['adm_idusuario']);
$provasSolicitadasObj->Set("campos","COUNT(DISTINCT(ps.id_solicitacao_prova)) as total");
$provasSolicitadasObj->Set("ordem","asc");
$_GET['q']['2|ps.situacao'] = 'E';
$solicitacoes = $provasSolicitadasObj->ListarTodas();
unset($_GET['q']['2|ps.situacao']);

$sql_atend_novo = 'select * from atendimentos_workflow where inicio = "S" and ativo = "S"';
$resultado_atend_novo = mysql_query($sql_atend_novo);
$atend_novo = mysql_fetch_assoc($resultado_atend_novo);

include_once("../classes/atendimentos.class.php");
$atendimentosObj = new Atendimentos();
$atendimentosObj->Set("idgestor",$_SESSION['adm_idusuario']);
$atendimentosObj->Set("idusuario",$_SESSION['adm_idusuario']);
$atendimentosObj->Set("campos","count(1) as total");
$atendimentosObj->Set("ordem","asc");
$atendimentosObj->Set("limite",-1);
$atendimentosObj->Set("pagina_inicial_total",-1);
$_GET['q']['1|ate.idsituacao'] = $atend_novo['idsituacao'];
$atendimentos = $atendimentosObj->ListarTodas();
$atendimentos = $atendimentos[0]['total'];
unset($_GET['q']['1|ate.idsituacao']);

include("../classes/documentosmatriculas.class.php");
$documentosPendentesObj = new Documentos_Matriculas();
$documentosPendentesObj->Set("modulo",$url[0]);
$documentosPendentesObj->Set("idusuario",$_SESSION['adm_idusuario']);
$documentosPendentesObj->Set("campos","COUNT(md.iddocumento) AS total, ma.idsituacao");
$documentosPendentesObj->Set("ordem_campo","ma.idmatricula");
$documentosPendentesObj->Set("ordem","ASC");
$documentosPendentesObj->Set("limite",-1);
$documentosPendentes = $documentosPendentesObj->ListarTodas();

include("idiomas/".$config["idioma_padrao"]."/index.php");
include("telas/".$config["tela_padrao"]."/index.php");