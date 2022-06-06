<?php

include("includes/login.php");

$dias = 30;
$ate["dia"] = date("d");
$ate["mes"] = date("m");
$ate["ano"] = date("Y");
$ate["semana"] = date("N");
$de["dia"] = date("d", mktime(0, 0, 0, date("m"), date("d")-$dias, date("Y")));
$de["mes"] = date("m", mktime(0, 0, 0, date("m"), date("d")-$dias, date("Y")));
$de["ano"] = date("Y", mktime(0, 0, 0, date("m"), date("d")-$dias, date("Y")));

include("../classes/empreendimentos.class.php");
include("../classes/reservas.class.php");
include("../classes/pessoas.class.php");
include("../classes/imobiliarias.class.php");
include("../classes/corretores.class.php");

$arrayJson = array();

$linhaObjEmpreendimento = new Empreendimentos();
$unidades = $linhaObjEmpreendimento->listarTotalUnidades();
$empreendimentos = $linhaObjEmpreendimento->listarTotalEmpreendimentos();

$linhaObjReserva = new Reservas();
$reservas = $linhaObjReserva->listarTotalReservas();
$vendas = $linhaObjReserva->listarTotalVendas();
$reservasAtivas = $linhaObjReserva->listarTotalReservasAtivas();
$conversao = ($vendas * 100) / $reservas;
$estoque = ($unidades - ($vendas + $reservasAtivas));

// Informações
$arrayJson["informacoes"]["dias"] = $dias;
$arrayJson["informacoes"]["titulo"] = "Dashboard geral";
$arrayJson["informacoes"]["titulo_periodo"] = "Resumo do período";
$arrayJson["informacoes"]["periodo"] = $de["dia"]."/".$de["mes"]."/".$de["ano"]." a ".$ate["dia"]."/".$ate["mes"]."/".$ate["ano"]."";
$arrayJson["informacoes"]["data_hora"] = $dia_semana[$config["idioma_padrao"]][$ate["semana"]].", ".$ate["dia"]." de ".$meses_idioma[$config["idioma_padrao"]][$ate["mes"]]." de ".$ate["ano"];
$arrayJson["informacoes"]["url_logo_1"] = "http://jotanunes.alfamaoraculo.com.br/especifico/img/jotanunes2.png";
$arrayJson["informacoes"]["url_logo_2"] = "http://jotanunes.alfamaoraculo.com.br/especifico/img/construtor.png";
$arrayJson["informacoes"]["empreendimentos"]["nome"] = "Empreendimentos";
$arrayJson["informacoes"]["empreendimentos"]["valor"] = $empreendimentos;
$arrayJson["informacoes"]["unidades"]["nome"] = "Unidades";
$arrayJson["informacoes"]["unidades"]["valor"] = $unidades;
$arrayJson["informacoes"]["disponibilidade"]["nome"] = "Disponibilidade";
$arrayJson["informacoes"]["disponibilidade"]["valor"] = intval($estoque);
$arrayJson["informacoes"]["reservas"]["nome"] = "Reservas";
$arrayJson["informacoes"]["reservas"]["valor"] = intval($reservas);
$arrayJson["informacoes"]["vendas"]["nome"] = "Vendas";
$arrayJson["informacoes"]["vendas"]["valor"] = intval($vendas);
$arrayJson["informacoes"]["conversao"]["nome"] = "Conversão";
$arrayJson["informacoes"]["conversao"]["valor"] = number_format($conversao,2,'.','');
$arrayJson["informacoes"]["reservas_ativas"]["nome"] = "Reservas ativas";
$arrayJson["informacoes"]["reservas_ativas"]["valor"] = intval($reservasAtivas);

// Grafico Barras
$arrayReservas20 = $linhaObjReserva->totalReservas20Dias();
$arrayJson["grafico_barras"]["reservas"]["nome"] = "Reservas";
$totalReservas = 0;
for($i = 0; $i < $dias; $i++) {
	$data = date("d/m/Y", mktime(0, 0, 0, date("m"), (date("d") - $dias)  + $i, date("Y")));
	$totalReservas += $arrayReservas20[$data];
}
$arrayJson["grafico_barras"]["reservas"]["total"] = $totalReservas;
for($i = 0; $i < $dias; $i++) {
	$data = date("d/m/Y", mktime(0, 0, 0, date("m"), (date("d") - $dias)  + $i, date("Y")));
	$data_sem_ano = date("d/m", mktime(0, 0, 0, date("m"), (date("d") - $dias)  + $i, date("Y")));
	$arrayJson["grafico_barras"]["reservas"]["valor"][] = array("dia" => $data_sem_ano, "valor" => intval($arrayReservas20[$data]), "porcentagem" => number_format((intval($arrayReservas20[$data])/$totalReservas)*100,2,'.',''));
}

$arrayVendas20 = $linhaObjReserva->totalVendas20Dias();
$arrayJson["grafico_barras"]["vendas"]["nome"] = "Vendas";
$totalVendas = 0;
for($i = 0; $i < $dias; $i++) {
	$data = date("d/m/Y", mktime(0, 0, 0, date("m"), (date("d") - $dias)  + $i, date("Y")));
	$totalVendas += $arrayVendas20[$data];
}
$arrayJson["grafico_barras"]["vendas"]["total"] = $totalVendas;
for($i = 0; $i < $dias; $i++) {
	$data = date("d/m/Y", mktime(0, 0, 0, date("m"), (date("d") - $dias)  + $i, date("Y")));
	$data_sem_ano = date("d/m", mktime(0, 0, 0, date("m"), (date("d") - $dias)  + $i, date("Y")));
	$arrayJson["grafico_barras"]["vendas"]["valor"][] = array("dia" => $data_sem_ano, "valor" => intval($arrayVendas20[$data]), "porcentagem" => number_format((intval($arrayVendas20[$data])/$totalVendas)*100,2,'.',''));
}

// Top Imobiliárias
$arrayJson["top_imobiliaria"]["quantitativo"]["nome"] = "Top Imobiliária por quantidade";
$sql = "SELECT
		  im.idimobiliaria, UPPER(im.nome) as imobiliaria, count(re.idreserva) as quantidade, sum(re.valor_contrato) as valor
		from imobiliarias im
			 INNER JOIN reservas re on (im.idimobiliaria = re.idimobiliaria)
			 INNER JOIN empreendimentos_unidades un ON (un.idunidade=re.idunidade)
			 INNER JOIN empreendimentos_blocos bl ON (un.idbloco=bl.idbloco)
			 inner join empreendimentos_etapas et	ON (bl.idetapa=et.idetapa)
			 INNER JOIN empreendimentos e ON (et.idempreendimento=e.idempreendimento)
			 INNER JOIN reservas_workflow rw on (re.idsituacao = rw.idsituacao)
			WHERE im.ativo='S' and rw.vendida='S' and rw.ativo='S'
		  and (re.data_cad <= '".$ate["ano"]."-".$ate["mes"]."-".$ate["dia"]." 23:59:59')
		  and (re.data_cad >= '".$de["ano"]."-".$de["mes"]."-".$de["dia"]." 00:00:00')
		  GROUP BY im.idimobiliaria order by quantidade desc limit 10";
$arrayJson["top_imobiliaria"]["quantitativo"]["valor"] = array();
$seleciona = mysql_query($sql) or die(mysql_error());
while( $top = mysql_fetch_assoc($seleciona) ){
	$arrayJson["top_imobiliaria"]["quantitativo"]["valor"][] = $top;
}

$arrayJson["top_imobiliaria"]["vendas"]["nome"] = "Top Imobiliária por valor";
$sql = "SELECT
		  im.idimobiliaria, UPPER(im.nome) as imobiliaria, count(re.idreserva) as quantidade, sum(re.valor_contrato) as valor
		from imobiliarias im
			 INNER JOIN reservas re on (im.idimobiliaria = re.idimobiliaria)
			 INNER JOIN empreendimentos_unidades un ON (un.idunidade=re.idunidade)
			 INNER JOIN empreendimentos_blocos bl ON (un.idbloco=bl.idbloco)
			 inner join empreendimentos_etapas et	ON (bl.idetapa=et.idetapa)
			 INNER JOIN empreendimentos e ON (et.idempreendimento=e.idempreendimento)
			 INNER JOIN reservas_workflow rw on (re.idsituacao = rw.idsituacao)
			WHERE im.ativo='S' and rw.vendida='S' and rw.ativo='S'
		  and (re.data_cad <= '".$ate["ano"]."-".$ate["mes"]."-".$ate["dia"]." 23:59:59')
		  and (re.data_cad >= '".$de["ano"]."-".$de["mes"]."-".$de["dia"]." 00:00:00')
		  GROUP BY im.idimobiliaria order by valor desc limit 10";
$arrayJson["top_imobiliaria"]["vendas"]["valor"] = array();
$seleciona = mysql_query($sql) or die(mysql_error());
while( $top = mysql_fetch_assoc($seleciona) ){
	$arrayJson["top_imobiliaria"]["vendas"]["valor"][] = $top;
}

// Top Corretor
$arrayJson["top_corretor"]["quantitativo"]["nome"] = "Top Corretor por quantidade";
$sql = "SELECT
		  co.idcorretor, co.nome AS corretor, UPPER(im.nome) as imobiliaria, count(re.idreserva) as quantidade, sum(re.valor_contrato) as valor
		FROM corretores co
		  INNER JOIN reservas re on (co.idcorretor = re.idcorretor)
		 INNER JOIN empreendimentos_unidades un ON (un.idunidade=re.idunidade)
		 INNER JOIN empreendimentos_blocos bl ON (un.idbloco=bl.idbloco)
		 inner join empreendimentos_etapas et	ON (bl.idetapa=et.idetapa)	
		 INNER JOIN empreendimentos e ON (et.idempreendimento=e.idempreendimento)						
		  INNER JOIN imobiliarias im on (co.idimobiliaria = im.idimobiliaria)
		  INNER JOIN reservas_workflow rw on (re.idsituacao = rw.idsituacao)
			WHERE co.ativo='S' and rw.vendida='S' and rw.ativo='S'
		  and (re.data_cad <= '".$ate["ano"]."-".$ate["mes"]."-".$ate["dia"]." 23:59:59')
		  and (re.data_cad >= '".$de["ano"]."-".$de["mes"]."-".$de["dia"]." 00:00:00')
		  GROUP BY co.idcorretor order by quantidade desc limit 10";
$arrayJson["top_corretor"]["quantitativo"]["valor"] = array();
$seleciona = mysql_query($sql) or die(mysql_error());
while( $top = mysql_fetch_assoc($seleciona) ){
	$arrayJson["top_corretor"]["quantitativo"]["valor"][] = $top;
}

$arrayJson["top_corretor"]["vendas"]["nome"] = "Top Corretor por valor";
$sql = "SELECT
		  co.idcorretor, co.nome AS corretor, UPPER(im.nome) as imobiliaria, count(re.idreserva) as quantidade, sum(re.valor_contrato) as valor
		FROM corretores co
		  INNER JOIN reservas re on (co.idcorretor = re.idcorretor)
		 INNER JOIN empreendimentos_unidades un ON (un.idunidade=re.idunidade)
		 INNER JOIN empreendimentos_blocos bl ON (un.idbloco=bl.idbloco)
		 inner join empreendimentos_etapas et	ON (bl.idetapa=et.idetapa)	
		 INNER JOIN empreendimentos e ON (et.idempreendimento=e.idempreendimento)						
		  INNER JOIN imobiliarias im on (co.idimobiliaria = im.idimobiliaria)
		  INNER JOIN reservas_workflow rw on (re.idsituacao = rw.idsituacao)
			WHERE co.ativo='S' and rw.vendida='S' and rw.ativo='S'
		  and (re.data_cad <= '".$ate["ano"]."-".$ate["mes"]."-".$ate["dia"]." 23:59:59')
		  and (re.data_cad >= '".$de["ano"]."-".$de["mes"]."-".$de["dia"]." 00:00:00')
		  GROUP BY co.idcorretor order by valor desc limit 10";
$arrayJson["top_corretor"]["vendas"]["valor"] = array();
$seleciona = mysql_query($sql) or die(mysql_error());
while( $top = mysql_fetch_assoc($seleciona) ){
	$arrayJson["top_corretor"]["vendas"]["valor"][] = $top;
}

// Historico
$arrayJson["hitorico"]["nome"] = "Últimas atividades";
$arrayJson["hitorico"]["valor"] = array();
	
if(!$_GET["limite"]) $_GET["limite"] = 15;

$sql = "SELECT
		rh.idhistorico,
		rh.idreserva,
		rh.data_cad,
		rh.de,
		rh.para,
		ua.nome as quem,
		rw_de.nome as de_nome,
		rw_de.cor_bg as de_cor_bg,
		rw_de.cor_nome as de_cor_nome,
		rw_para.nome as para_nome,
		rw_para.cor_bg as para_cor_bg,
		rw_para.cor_nome as para_cor_nome
	  FROM reservas_historicos rh
		inner join usuarios_adm ua on (ua.idusuario = rh.idusuario)
		inner join reservas_workflow rw_de on (rh.de = rw_de.idsituacao)
		inner join reservas_workflow rw_para on (rh.para = rw_para.idsituacao)
	  WHERE
		tipo='situacao'
		and acao='modificou'
		and idusuario_imobiliaria is null
		and idcorretor is null";
if($_GET["apartirde"]) $sql .= " and idhistorico > ".intval($_GET["apartirde"]);
$sql .= " order by idhistorico desc limit ".intval($_GET["limite"]);
$seleciona = mysql_query($sql) or die(mysql_error());
while( $historico = mysql_fetch_assoc($seleciona) ){
	$arrayJson["hitorico"]["valor"][] = $historico;
}


// Mensagens
$mensagens[] = "Nova reserva #35892 feita pelo corretor Gabriel Manzano.";
$mensagens[] = "Imobiliária Premium é a TOP1 de vendas no preríodo.";
$mensagens[] = "35% das reservas em atendimento estão em Análise bancária.";
$mensagens[] = "O empreendimento Marinas foi o mais vendido no período.";
$mensagens[] = "O corretor Raphael Jordany vendeu R$ 1.256.325 em 30 dias.";
$mensagens[] = "A conversão da Jotanunes no período é de 58,9%.";
$arrayJson["mensagens"] = $mensagens;

$arrayJson["empreendimentos_geral"]["nome"] = "Geral";
$arrayJson["empreendimentos_geral"]["cor_fundo"] = "E4E4E4";

$arrayJson["empreendimentos"] = array();
$sql = "SELECT idempreendimento, nome FROM empreendimentos
  			where ativo='S'
   				order by nome";
$seleciona = mysql_query($sql) or die(mysql_error());
while( $empreendimento = mysql_fetch_assoc($seleciona) ){
	$empreendimento["cor_fundo"] = "F4F4F4";
	
	$situacoes = $linhaObjReserva->RetornarSituacoesWorkflow();
	$linhaObjEmpreendimento->Set("id",$empreendimento["idempreendimento"]);
	$dashboard = $linhaObjEmpreendimento->DashBoardEmpreendimento();						
	$dadosGrafico = $linhaObjEmpreendimento->GraficoVendasEmpreendimento();
	
	// Adicionado para o grafico de situacoes de unidades.
	$linhaObjEmpreendimento->Set("pagina",1);
	$linhaObjEmpreendimento->Set("ordem",$NULL);
	$linhaObjEmpreendimento->Set("limite",-1);
	$linhaObjEmpreendimento->Set("ordem_campo",NULL);
	$linhaObjEmpreendimento->Set("campos","un.*, bl.nome as bloco, et.nome as etapa, em.nome as empreendimento");		
	$unidadesArray = $linhaObjEmpreendimento->listarUnidadesEmpreendimento(intval($empreendimento["idempreendimento"]));		
	
	$empreendimento["unidades"]["nome"] = "Unidades";
	$empreendimento["unidades"]["valor"] = $dashboard["unidades"];
	
	$empreendimento["vendas"]["nome"] = "Vendas";
	$empreendimento["vendas"]["valor"] = intval($dashboard["vendas"]);	
	
	$empreendimento["disponibilidade"]["nome"] = "Disponibilidade";
	$empreendimento["disponibilidade"]["valor"] = intval($dashboard["estoque"]);			
	
	$empreendimento["reservas"]["nome"] = "Reservas ativas";
	$empreendimento["reservas"]["valor"] = intval($dashboard["reservas"]);
	
	$empreendimento["conversao"]["nome"] = "Conversão";
	$empreendimento["conversao"]["valor"] = $dashboard["porcentagem"];		
	
	$empreendimento["unidades_situacao"]["nome"] = "Situações das unidades";
	$empreendimento["unidades_situacao"]["valor"] = array();
	foreach($situacoes as $ind => $situacao){
		$aux = array();
		$aux["nome"] = $situacao["nome"];
		$aux["cor_bg"] = $situacao["cor_bg"];
		$aux["cor_nome"] = $situacao["cor_nome"];
		$aux["valor"] = intval($dadosGrafico[$situacao["idsituacao"]]);
		$empreendimento["unidades_situacao"]["valor"][] = $aux;
	}
	
	$empreendimento["reservas_situacao"]["nome"] = "Situações das reservas";	
	$empreendimento["reservas_situacao"]["valor"] = array();	
	
	$situacao_empreendimento = array();
	foreach($unidadesArray as $ind => $unidade){
		$situacao_empreendimento[$unidade["situacao"]["situacao_para_venda"]]++;
	}
	foreach($situacao_unidade[$config["idioma_padrao"]] as $situacao => $qtd){
		$aux = array();
		$aux["nome"] = $situacao_unidade[$config["idioma_padrao"]][$situacao];
		$aux["cor_bg"] = $situacao_unidade_cores[$situacao];
		$aux["valor"] = intval($situacao_empreendimento[$situacao]);
		$empreendimento["reservas_situacao"]["valor"][] = $aux;
	}			
	
	$arrayJson["empreendimentos"][] = $empreendimento;
}

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');
echo json_encode($arrayJson);

?>