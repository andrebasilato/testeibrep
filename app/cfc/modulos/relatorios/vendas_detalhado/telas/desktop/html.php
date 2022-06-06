<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
body, td, th {
    font-family: Verdana, Geneva, sans-serif;
    font-size: 10px;
    color: #000;
}
body {
    background-color: #FFF;
    background-image: none;
    padding-top: 0px;
    margin-left: 5px;
    margin-top: 5px;
    margin-right: 5px;
    margin-bottom: 5px;
}
a:link {
    color: #000;
    text-decoration: none;
}
a:visited {
    text-decoration: none;
    color: #000;
}
a:hover {
    text-decoration: underline;
    color: #000;
}
a:active {
    text-decoration: none;
    color: #000;
}
table.zebra-striped {
    padding: 0;
    font-size: 9px;
    border-collapse: collapse;
}
table.zebra-striped th, table td {
    padding: 4px;
    line-height: 16px;
    text-align: left;
}
table.zebra-striped th {
    padding-top: 9px;
    font-weight: bold;
    vertical-align: middle;
}
table.zebra-striped td {
    vertical-align: top;
    border: 1px solid #000;
}
table.zebra-striped tbody th {
    border-top: 1px solid #ddd;
    vertical-align: top;
}
.zebra-striped tbody tr:nth-child(odd) td, .zebra-striped tbody tr:nth-child(odd) th {
    background-color: #F4F4F4;
}
.zebra-striped tbody tr:hover td, .zebra-striped tbody tr:hover th {
    background-color: #E4E4E4;
}
</style>
<style type="text/css" media="print">
body, td, th {
    font-family: Verdana, Geneva, sans-serif;
    font-size: 9px;
    color: #000;
}
.impressao {
    display: none;
}
</style>
<script src="/assets/js/jquery.1.7.1.min.js"></script>
<script src="/assets/plugins/facebox/src/facebox.js"></script>
<script src="/assets/js/validation.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-tooltip.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('a[rel*=facebox]').facebox();
  });
  var regras = new Array();
  regras.push("required,nome,Nome obrigatório");
</script>
</head>
<body>

<table width="100%" border="0" cellpadding="10" cellspacing="0">
  <tr>
    <td height="80"><table border="0" cellspacing="0" cellpadding="8">
      <tr>
        <td><a href="/<?= $url[0]; ?>" class="logo"></a><?php/*<img src="<?php echo $config['logo_pequena']; ?>" />*/?></td>
      </tr>
    </table></td>
    <td align="center"><h2><strong><?= $idioma["pagina_titulo"]; ?></strong></h2></td>
    <td  align="right" valign="top"><table border="0" align="right" cellpadding="3" cellspacing="0" class="impressao">
      <tr>
        <td><img src="/assets/img/print_24x24.png" width="24" height="24"></td>
        <td><a href="javascript:window.print();">
          <?= $idioma["imprimir"]; ?>
        </a></td>

        <td>
            <a class="btn" href="#link_salvar" rel="facebox" ><?php echo $idioma['salvar_relatorio'] ?></a>
            <div id="link_salvar" style="display:none;">
                <div style="width:300px;">
                <form method="post" onSubmit="return validateFields(this, regras)">
                    <input type="hidden" name="acao" value="salvar_relatorio" />
                    <label for="nome"><strong><?php echo $idioma['tabela_nome']; ?>:</strong></label>
                    <input type="text" class="input" name="nome" id="nome" style="height:30px;" /><br /><br />
                    <input type="submit" class="btn" value="<?php echo $idioma['salvar_relatorio'] ?>" />
                </form>
                </div>
            </div>
        </td>
        <td>
            <form method="post" action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/xls?<?php echo $_SERVER["QUERY_STRING"]; ?>">
                <input class="btn" type="submit" value="<?php echo $idioma['baixar_planilha'] ?>" />
            </form>
        </td>

      </tr>
    </table></td>
  </tr>
</table>

<? if($mensagem_sucesso) { ?>
    <div class="alert alert-success fade in">
        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
        <strong><?= $idioma[$mensagem_sucesso]; ?></strong>
    </div>
<? } else if($mensagem_erro) { ?>
    <div class="alert alert-error fade in">
        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
        <strong><?= $idioma[$mensagem_erro]; ?></strong>
    </div>
<? } ?>

<table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td>Registros encontrados: <?= count($dadosArray); ?></td>
  </tr>
</table>

<?php $relatorioObj->GerarTabela($dadosArray,$_GET["q"],$idioma); ?>


<table id="sortTableExample" border="1" width="1800" cellpadding="5" >
  <thead>
    <tr>
      <th bgcolor="#E4E4E4" class=" headerSortReloca"><div class='headerNew'>Data</div></th>
      <th bgcolor="#F4F4F4" class=" headerSortReloca"><div class='headerNew'>Matrícula</div></th>
      <th bgcolor="#F4F4F4" class=" headerSortReloca"><div class='headerNew'>Contrato</div></th>
      <th bgcolor="#F4F4F4" class=" headerSortReloca" ><div class='headerNew'>Aluno</div></th>
      <th bgcolor="#F4F4F4" class=" headerSortReloca"><div class='headerNew'>Situação</div></th>
      <th bgcolor="#F4F4F4" class=" headerSortReloca" ><div class='headerNew'>Oferta</div></th>
      <th bgcolor="#F4F4F4" class=" headerSortReloca"><div class='headerNew'>Curso</div></th>
      <th bgcolor="#F4F4F4" class=" headerSortReloca" ><div class='headerNew'>Turma</div></th>
      <th bgcolor="#E4E4E4" class=" headerSortReloca" ><div class='headerNew'>Valor do contrato</div></th>
      <th bgcolor="#E4E4E4" class=" headerSortReloca" ><div class='headerNew'>Forma de pagamento</div></th>
      <th bgcolor="#E4E4E4" class=" headerSortReloca" ><div class='headerNew'>Parcelas</div></th>
      <th bgcolor="#F4F4F4" class=" headerSortReloca" ><div class='headerNew'>Vendedor</div></th>
    </tr>
  </thead>
  <tbody>
<?
$vendasVendedor = array();
$vendasCidade = array();
$dataAux = null;
$subtotal = array();
$total = 0;
foreach($dadosArray as $ind => $linha) {

    $data = formataData($linha["data_cad"],'br',0);

    if($ind == 0) $dataAux = $data;

    $subtotal[$data] += $linha["valor_contrato"];
    $total += $linha["valor_contrato"];

    $vendasVendedor[$linha["idvendedor"]]["nome"] = $linha["vendedor"];
    $vendasVendedor[$linha["idvendedor"]]["quantidade"] += 1;
    $vendasVendedor[$linha["idvendedor"]]["valor"] += $linha["valor_contrato"];

    $vendasCidade[$linha["idcidade"]]["cidade"] = $linha["cidade"];
    $vendasCidade[$linha["idcidade"]]["estado"] = $linha["estado"];
    $vendasCidade[$linha["idcidade"]]["quantidade"] += 1;
    $vendasCidade[$linha["idcidade"]]["valor"] += $linha["valor_contrato"];
?>
<?
    if($dataAux <> $data) {
?>
    <tr>
      <td colspan="8" align="right" style="text-align:right"><strong>Subtotal do dia <?= $dataAux; ?></strong> &nbsp;&nbsp; </td>
      <td bgcolor="#E4E4E4"><table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td style="color:#999"><strong>R$</strong></td>
          <td align="right" style="text-align:right;"><strong>
            <?= number_format($subtotal[$dataAux], 2, ',', '.'); ?>
          </strong></td>
        </tr>
      </table></td>
      <td colspan="3">&nbsp;</td>
    </tr>
<?
    $dataAux = $data;
} ?>
    <tr>
      <td bgcolor="#E4E4E4"><?= $data; ?></td>
      <td><?= $linha["idmatricula"]; ?></td>
      <td><?= $linha["numero_contrato"]; ?></td>
      <td><?= $linha["cliente"]; ?></td>
      <td><span><?= $linha["situacao_wf_nome"]; ?></span></td>
      <td><?= $linha["oferta"]; ?></td>
      <td><?= $linha["curso"]; ?></td>
      <td><?= $linha["turma"]; ?></td>
      <td bgcolor="#E4E4E4"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td style="color:#999">R$</td>
                <td align="right" style="text-align:right;"><?= number_format($linha["valor_contrato"], 2, ',', '.'); ?></td>
              </tr>
            </table></td>
      <td bgcolor="#E4E4E4"><?= $forma_pagamento_conta["pt_br"][$linha["forma_pagamento"]]; ?></td>
      <td bgcolor="#E4E4E4"><?= $linha["quantidade_parcelas"]; ?></td>
      <td><?= $linha["vendedor"]; ?></td>
    </tr>

<? } ?>


    <tr>
      <td colspan="8" style="text-align:right;"><strong>Total: &nbsp;&nbsp;</strong></td>
      <td bgcolor="#E4E4E4"><table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td style="color:#999"><strong>R$</strong></td>
          <td align="right" style="text-align:right;"><strong><?= number_format($total, 2, ',', '.'); ?></strong></td>
        </tr>
      </table></td>
      <td colspan="3">&nbsp;</td>
    </tr>


  </tbody>
</table>

<br>
<br>

<table id="sortTableExample" border="1" cellpadding="5" >
  <thead>
    <tr>
      <th bgcolor="#E4E4E4" class=" headerSortReloca"><div class='headerNew'>Atendente</div></th>
      <th bgcolor="#F4F4F4" class=" headerSortReloca"><div class='headerNew'>Matrículas</div></th>
      <th bgcolor="#F4F4F4" class=" headerSortReloca"><div class='headerNew'>Faturamento</div></th>
      <th bgcolor="#F4F4F4" class=" headerSortReloca" ><div class='headerNew'>Média</div></th>
    </tr>
  </thead>
  <tbody>
 <? foreach($vendasVendedor as $ind => $linha) { ?>
    <tr>
      <td bgcolor="#E4E4E4"><?= $linha["nome"]; ?></td>
      <td><?= $linha["quantidade"]; ?></td>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td style="color:#999">R$</td>
                <td align="right" style="text-align:right;"><?= number_format($linha["valor"], 2, ',', '.'); ?></td>
              </tr>
            </table></td>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td style="color:#999">R$</td>
                <td align="right" style="text-align:right;"><?= number_format($linha["valor"]/$linha["quantidade"], 2, ',', '.'); ?></td>
              </tr>
            </table></td>
      </tr>
   <? } ?>
  </tbody>
</table>


<br>
<br>

<table id="sortTableExample" border="1" cellpadding="5" >
  <thead>
    <tr>
      <th bgcolor="#E4E4E4" class=" headerSortReloca"><div class='headerNew'>Cidade</div></th>
      <th bgcolor="#F4F4F4" class=" headerSortReloca"><div class='headerNew'>Matrículas</div></th>
      <th bgcolor="#F4F4F4" class=" headerSortReloca"><div class='headerNew'>Faturamento</div></th>
      <th bgcolor="#F4F4F4" class=" headerSortReloca" ><div class='headerNew'>Média</div></th>
    </tr>
  </thead>
  <tbody>
 <? foreach($vendasCidade as $ind => $linha) { ?>
    <tr>
      <td bgcolor="#E4E4E4"><?= $linha["estado"]; ?> - <?= $linha["cidade"]; ?></td>
      <td><?= $linha["quantidade"]; ?></td>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td style="color:#999">R$</td>
                <td align="right" style="text-align:right;"><?= number_format($linha["valor"], 2, ',', '.'); ?></td>
              </tr>
            </table></td>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td style="color:#999">R$</td>
                <td align="right" style="text-align:right;"><?= number_format($linha["valor"]/$linha["quantidade"], 2, ',', '.'); ?></td>
              </tr>
            </table></td>
      </tr>
   <? } ?>
  </tbody>
</table>



<?php //$relatorioObj->GerarTabela($dadosArray,$_GET["q"],$idioma); ?>


<table width="100%" border="0" align="center" cellpadding="10" cellspacing="0">
  <tr>
    <td valign="top"><span style="color:#999999;"><?= $idioma["rodape"]; ?></span></td>
    <td align="right" valign="top"><div align="right"><a href="/<?= $url[0]; ?>" class="logo"></a></div><?php/*<img src="/assets/img/logo_pequena.png" width="135" height="50" align="right">*/?></td>
  </tr>
</table>
<?php
// usado no log do php
//cho bl_debug(_debug);
?>
</body>
</html>