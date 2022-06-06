<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head", $config, $usuario); ?>

    <script>
        function radio_filtro(obj) {
            id = obj.id;
            var array = document.getElementById('presenca_radio').getElementsByTagName('span');
            var t = array.length;
            document.getElementById('filtro_mes').value = obj.name;
            if (document.getElementById('filtro_ano').value == null) {
                document.getElementById('filtro_ano').value = '<?php echo date("Y"); ?>';
            }

            document.getElementById('form_filtro').submit();
        }

        function radio_filtro_ano(obj) {
            id = obj.id;
            var array = document.getElementById('presenca_radio_ano').getElementsByTagName('span');
            var t = array.length;
            document.getElementById('filtro_ano').value = obj.value;
            if (document.getElementById('filtro_mes').value == null) {
                document.getElementById('filtro_mes').value = '<?php echo date("m"); ?>';
            }

            document.getElementById('form_filtro').submit();
        }

        function radio_filtro_dia(data) {
            document.getElementById('filtro_dia').value = data;
            document.getElementById('form_filtro_dia').submit();
        }

    </script>
</head>
<body>
<?php incluirLib("topo", $config, $usuario); ?>
<div class="container-fluid">
<section id="global">
    <div class="page-header">
        <h1><?= $idioma["pagina_titulo"]; ?> &nbsp;
            <small><?= $idioma["pagina_subtitulo"]; ?></small>
        </h1>
    </div>
    <ul class="breadcrumb">
        <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span>
        </li>
        <li class="active"><?= $idioma["pagina_titulo"]; ?></li>
        <? if ($_GET["q"]) { ?>
            <li><span class="divider">/</span> <a
                href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["nav_resetarbusca"]; ?></a>
            </li><? } ?>
        <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
    </ul>
</section>
<div class="row-fluid">
    <div class="span12">
        <div class="box-conteudo">
            <? if ($_POST["msg"]) { ?>
                <div class="alert alert-success fade in">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                    <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                </div>
            <? } ?>


            <span class="pull-right" style="padding-top:3px;">
                	<a href="/gestor/relatorios/contas_relatorio" target="_blank"
                       class="btn"><?= $idioma["nav_relatorio"]; ?></a>
                <? if ($linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2", NULL)) { ?>
                    <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/cadastrar"
                       class="btn btn-primary"><i class="icon-plus icon-white"></i> <?= $idioma["nav_cadastrar"]; ?></a>
                <? } ?>
              </span>


            <div id="listagem_informacoes">
                <? printf($idioma["informacoes"], $linhaObj->Get("total")); ?>
                <br/>
                <? printf($idioma["paginas"], $linhaObj->Get("pagina"), $linhaObj->Get("paginas")); ?>


            </div>
            <script>
                function filtrarSindicato(select) {
                    document.getElementById('filtro_sindicato').value = document.getElementById('idsindicato_filtro').options[document.getElementById('idsindicato_filtro').selectedIndex].value;
                    <?php if ($_GET['acao'] == 'filtrar_dia' || !$_GET['acao']) { ?>
                    document.getElementById('form_filtro_dia').submit();
                    <?php } else { ?>
                    document.getElementById('form_filtro').submit();
                    <?php } ?>
                }
            </script>

            <?php
            //if(!$_GET['filtro_mes']) $_GET['filtro_mes'] = date("m");
            if (!$_GET['filtro_ano']) $_GET['filtro_ano'] = date("Y");
            ?>
            <form action="" method="get" id="form_filtro">
                <input name="acao" type="hidden" value="filtrar_data"/>
                <input name="filtro_mes" id="filtro_mes" type="hidden" value=""/>
                <input name="filtro_ano" id="filtro_ano" type="hidden" value=""/>

                <select id="idsindicato_filtro" name="idsindicato_filtro" onchange="filtrarSindicato(this)">
                    <option value="-1">Todos os sindicatos</option>
                    <?php foreach ($sindicatos_usuario as $sindicato) { ?>
                        <option
                            value="<?php echo $sindicato['idsindicato']; ?>" <?php if ($_GET['idsindicato_filtro'] == $sindicato['idsindicato']) echo 'selected="selected"'; ?> ><?php echo $sindicato['nome_abreviado']; ?></option>
                    <?php } ?>
                </select>

                <div class="btn-toolbar">
                    <div class="btn-group" id="presenca_radio_ano">
                        <?php for ($i = -1; $i < 2; $i++) { ?>
                            <input type="button"
                                   class="btn  btn-small <?php if ($_GET['filtro_ano'] == ($_GET['filtro_ano'] + $i)) {
                                       echo 'btn-primary';
                                   } ?>" onclick="radio_filtro_ano(this)" value="<?= ($_GET['filtro_ano'] + $i); ?>"/>
                        <? } ?>
                    </div>
                    <div class="btn-group" id="presenca_radio">
                        <?php for ($i = 1; $i < 13; $i++) {
                            $i = str_pad($i, 2, "0", STR_PAD_LEFT); ?>
                            <input type="button"
                                   class="btn btn-small <?php if ($_GET['filtro_mes'] == $i) {
                                       echo 'btn-primary';
                                   } ?>" name="<?= $i ?>" onclick="radio_filtro(this)"
                                   value="<?= $meses_min_idioma[$config['idioma_padrao']][$i]; ?>"/>
                        <?php } ?>
                    </div>
                </div>

            </form>


            <?php
            if (!$_GET['filtro_dia']) $_GET['filtro_dia'] = date("Y-m-d");
            if (!$_GET['filtro_mes']) {
                $_GET['acao'] = "filtrar_dia";
                $_GET["q"]["3|data_vencimento"] = formataData($_GET['filtro_dia'], "br", 0);
            }
            ?>
            <form action="" method="get" id="form_filtro_dia">
                <input name="acao" type="hidden" value="filtrar_dia"/>
                <input name="filtro_dia" id="filtro_dia" type="hidden" value=""/>
                <input name="idsindicato_filtro" id="filtro_sindicato" type="hidden" value=""/>

                <div class="btn-group" style="margin-bottom: 20px;">
                    <?php
                    for ($i = 3; $i >= -3; $i--) {
                        $data = date("d/m/Y", mktime(0, 0, 0, date("m"), date("d") - $i, date("Y")));
                        $data_filtro = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - $i, date("Y")));
                        $dia = $dia_semana["pt_br"][date("N", mktime(0, 0, 0, date("m"), date("d") - $i, date("Y")))];
                        ?>
                        <?php if ($_GET['filtro_dia'] == $data_filtro && $_GET["acao"] <> "filtrar_data") { ?>
                            <a class="btn btn-small btn-primary" style="color:#FFF;"
                               href="javascript:radio_filtro_dia('<?= $data_filtro; ?>')"><?= $dia; ?> <br/> <span
                                    style="font-size: 9px; color:#FFF"><?= $data; ?></span></a>
                        <?php } else { ?>
                            <a class="btn btn-small"
                               href="javascript:radio_filtro_dia('<?= $data_filtro; ?>')"><?= $dia; ?> <br/> <span
                                    style="font-size: 9px; color:#666"><?= $data; ?></span></a>
                        <?php } ?>
                    <?php } ?>
                </div>
            </form>


            <style>
                .labelReceita {
                    color: #060;
                }

                .labelDespesa {
                    color: #C00;
                }

                .labelSaldo {
                    color: #000;
                }

                .linhaDivisao {
                    color: #666;
                    background-color: #E4E4E4 !important;
                    font-size: 9px;
                    padding: 5px !important;
                    line-height: 12px !important;
                }

                /*
                .divSaldo {
                    background: #FFFDED;
                    border: 2px solid #ffffff;
                    padding: 10px 10px 20px 20px;
                    box-shadow: 1px 2px 6px rgba(208,208,208, 0.5);
                    -moz-box-shadow: 1px 2px 6px rgba(208,208,208, 0.5);
                    -webkit-box-shadow: 1px 2px 6px rgba(208,208,208, 0.5);
                    margin-bottom:20px;
                    margin-top:20px;
                    display:compact;
                }
                */
            </style>
          
          <span class="divSaldo">
          <table border="0" cellpadding="2" cellspacing="0">
              <tr>
                  <td width="200" class="labelReceita">Receita</td>
                  <td>&nbsp;</td>
                  <td width="200" class="labelDespesa">Despesas</td>
                  <td>&nbsp;</td>
                  <td width="200" class="labelSaldo">Saldo</td>
              </tr>
              <tr>
                  <td>
                      <table width="100%" border="0" cellpadding="1">
                          <tr>
                              <td width="20" style="color:#999; font-size:20px">R$</td>
                              <td style="color:#060; font-size:20px"><strong>
                                      <?= number_format($valorReceita, 2, ",", "."); ?>
                                  </strong></td>
                          </tr>
                      </table>
                  </td>
                  <td>-</td>
                  <td>
                      <table width="100%" border="0" cellpadding="1">
                          <tr>
                              <td width="20" style="color:#999; font-size:20px">R$</td>
                              <td style="color:#C00; font-size:20px"><strong>
                                      <?= number_format($valorDespesa, 2, ",", "."); ?>
                                  </strong></td>
                          </tr>
                      </table>
                  </td>
                  <td>=</td>
                  <td>
                      <table width="100%" border="0" cellpadding="1">
                          <tr>
                              <td width="20" style="color:#999; font-size:20px">R$</td>
                              <td style="color:#<? if (($valorReceita - $valorDespesa) > 0) {
                                  echo "060";
                              } else {
                                  echo "C00";
                              } ?>; font-size:20px"><strong>
                                      <?= number_format($valorReceita - $valorDespesa, 2, ",", "."); ?>
                                  </strong></td>
                          </tr>
                      </table>
                  </td>
              </tr>
          </table>
          </span>
            <?php $linhaObj->GerarTabelaContas($dadosArray, $_GET["q"], $idioma); ?>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<? incluirLib("rodape", $config, $usuario); ?>
<script language="javascript" type="text/javascript">
    jQuery(document).ready(function ($) {
        $("#qtd").keypress(isNumber);
        $("#qtd").blur(isNumberCopy);
    });

    document.getElementById('filtro_sindicato').value = '<?php echo $_GET['idsindicato_filtro']; ?>';
    document.getElementById('filtro_dia').value = '<?php echo $_GET['filtro_dia']; ?>';
    document.getElementById('filtro_mes').value = '<?php echo $_GET['filtro_mes']; ?>';
    document.getElementById('filtro_ano').value = '<?php echo $_GET['filtro_ano']; ?>';
</script>
</div>
</body>
</html>