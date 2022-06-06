<?php
	include_once("../classes/relatorios.class.php");		
	$linhaObj = new Relatorios();	
	
	
	if($_GET["remrel"]){
		
		$sql = "delete from relatorios where idrelatorio='".intval($_GET["remrel"])."' and idusuario='".$_SESSION["adm_idusuario"]."'";
		$deleta = mysql_query($sql);
		echo "
			
			<script>
				alert('Relatório removido com sucesso!');
			</script>
		
		";
		
	}
	
	
	
	$linhaObj->Set("ordem","DESC");
	$linhaObj->Set("idusuario",$informacoes['idusuario']);
	$linhaObj->Set("limite",-1);
	$linhaObj->Set("ordem_campo",'ultimo_view');
	$linhaObj->Set("campos","*");
	$relatorioArray = $linhaObj->ListarTodas();

	include("idiomas/".$config["idioma_padrao"]."/index.php");
	include("telas/".$config["tela_padrao"]."/index.php");
?>