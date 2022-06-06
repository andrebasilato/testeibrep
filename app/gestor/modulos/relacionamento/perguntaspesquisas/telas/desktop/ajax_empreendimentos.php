<?php
	include_once("../classes/empreendimentos.class.php");	
	$linhaObjEmpreendimento = new Empreendimentos();
	$linhaObjEmpreendimento->Set("ordem","desc");
	$linhaObjEmpreendimento->Set("ordem_campo","e.nome");
	$linhaObjEmpreendimento->Set("idpesquisa",$_POST["idpesquisa"]);
	$empreendimentos = $linhaObjEmpreendimento->ListarEmpreendimentosPesquisa();
?>	
<?php 
	foreach($empreendimentos as $ind => $empreendimento) {
?>
	<option value="<?php echo $empreendimento['idempreendimento']; ?>" <?php if($_POST['idempreendimento'] == $empreendimento['idempreendimento']) echo "selected='selected'" ?> ><?php echo $empreendimento['nome']; ?></option>
<?php }  ?>