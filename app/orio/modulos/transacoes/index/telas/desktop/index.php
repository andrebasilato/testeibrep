<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head", $config, $usuario); ?>
    <script>
        function radio_filtro(obj) {
            var texto = document.getElementById('q[9|idinterface]').value;

            if ($(obj).attr('class') == 'btn btn-small') {
                $(obj).attr('class', 'btn btn-small btn-success');
            } else if ($(obj).attr('class') == 'btn btn-small btn-success') {
                $(obj).attr('class', 'btn btn-small');
            }

            if (texto == '') {
                texto = '0';
            }

            if (texto.search(obj.id) > 0) {
                var texto = texto.replace(obj.id, '');
            } else {
                texto += obj.id;
            }

            document.getElementById('q[9|idinterface]').value = texto;
        }

        function escolherCampoXML(valor) {
            if ('requisicao' == valor) {
                document.getElementById('xml_requisicao').className = 'span1 inputQtd';
                document.getElementById('xml_requisicao').style.display = 'inline';

                document.getElementById('xml_resposta').className = 'span1';
                document.getElementById('xml_resposta').value = '';
                document.getElementById('xml_resposta').style.display = 'none';
            } else {
                document.getElementById('xml_resposta').className = 'span1 inputQtd';
                document.getElementById('xml_resposta').style.display = 'inline';

                document.getElementById('xml_requisicao').className = 'span1';
                document.getElementById('xml_requisicao').value = '';
                document.getElementById('xml_requisicao').style.display = 'none';
            }
        }
    </script>
</head>
<body>
<?php incluirLib("topo", $config, $usuario); ?>
<?php
$buscaGet = null;
if (isset($_GET["q"])) {
    $buscaGet = $_GET["q"];
}
?>
<div class="container-fluid">
  <section id="global">
    <div class="page-header">
        <h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small class="hidden-phone"><?= $idioma["pagina_subtitulo"]; ?></small></h1>
    </div>
    <ul class="breadcrumb">
        <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
        <li class="active"><?= $idioma["pagina_titulo"]; ?></li>
        <?php if ($buscaGet) { ?><li><span class="divider">/</span> <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["nav_resetarbusca"]; ?></a></li><?php } ?>
        <span class="pull-right visible-desktop" style="padding-top:3px; color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
    </ul>
  </section>
  <div class="row-fluid">
    <div class="span12">
        <div class="box-conteudo">
                <?php
                if (! empty($_POST["msg"])) { ?>
                    <div class="alert alert-success fade in">
                        <a href="" class="close" data-dismiss="alert">×</a>
                        <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                    </div>
                <?php
                } ?>
                <?php
                if (! empty($_POST["erros"])) { ?>
                    <div class="alert alert-error fade in">
                        <a href="" class="close" data-dismiss="alert">×</a>
                        <strong><?= $idioma["form_erros"]; ?></strong>
                            <?php foreach ($_POST["erros"] as $ind => $val) { ?>
                                <br />
                                <?= $idioma[$val]; ?>
                            <?php } ?>
                        </strong>
                    </div>
                <?php
                } ?>

                <div class="alert alert-success fade in">
                    <strong><?= $idioma['info_transacoes']; ?></strong>
                    <a href="javascript:;" class="close" data-dismiss="alert">×</a>
                </div>

                <form action="" method="get" id="form_filtro" class="hidden-phone">

                    <div class="filtros-form">
                        <h4 class="titulo-simples">Filtros</h4>

                        <input name="q[6|data_cad]" placeholder="de:" type="text" class="data" style="width:150px; border-radius: 6px; height: 32px;" value="<?= ! empty($_GET["q"]['6|data_cad']) ? $_GET["q"]['6|data_cad'] : ''; ?>" rel="tooltip" data-original-title="Data de:" data-placement="top" />
                        <input name="q[7|data_cad]" placeholder="até:" type="text" class="data" style="width:150px; border-radius: 6px; height: 32px;" value="<?= ! empty($_GET["q"]['7|data_cad']) ? $_GET["q"]['7|data_cad'] : ''; ?>" rel="tooltip" data-original-title="Data até:" data-placement="top" />

                        <select name="q[n|campoXML]" id="filtro-bloco" onchange="escolherCampoXML(this.value)">
                            <option>-- Filtrar XML --</option>
                            <option value="requisicao" <?= (! empty($_GET['q']['n|campoXML']) && 'requisicao' == $_GET['q']['n|campoXML']) ? 'selected="selected"' : '' ?>>Requisição</option>
                            <option value="resposta" <?= (! empty($_GET['q']['n|campoXML']) && 'resposta' == $_GET['q']['n|campoXML']) ? 'selected="selected"' : '' ?>>Resposta</option>
                        </select>
                        <input name="q[2|xml_requisicao]" id="xml_requisicao" placeholder="requisição:" type="text" class="span1 inputQtd" style="display:none; border-radius: 6px; height: 32px;" value="<?= ! empty($_GET['q']['2|xml_requisicao']) ? $_GET['q']['2|xml_requisicao'] : ''; ?>" rel="tooltip" data-original-title="Requisição" data-placement="top" />
                        <input name="q[2|xml_resposta]" id="xml_resposta" placeholder="resposta:" type="text" class="span1 inputQtd" style="display:none; border-radius: 6px; height: 32px;" value="<?= ! empty($_GET['q']['2|xml_resposta']) ? $_GET['q']['2|xml_resposta'] : ''; ?>" rel="tooltip" data-original-title="Resposta" data-placement="top" />
                        <button type="submit" style="border-radius: 6px; height: 32px;"><i class="fa fa-filter"></i> FILTRAR</button>
                    </div>

                    <input name="q[9|idinterface]" id="q[9|idinterface]" type="hidden" class="idinterface" value=""/>

                    <div class="btn-toolbar" style="padding: 5px">
                        <div><strong>Interface:</strong></div>
                        <div class="btn-group" id="presenca_radio">
                            <?php
                            foreach ($orio_interfaces as $ind => $situacao) {
                                $classe = 'btn btn-small';
                                if (!empty($_GET['q']['9|idinterface']) && strpos($_GET['q']['9|idinterface'], 'bs-' . $ind . '-')) {
                                    $classe .= ' btn-success';
                                }
                                ?>
                            <button type="button" name="situacoes[]" class="<?= $classe; ?>" id="<?= 'bs-' . $ind . '-'; ?>" onclick="radio_filtro(this)"  rel="tooltip" data-placement="top" data-original-title="<?= $situacao['descricao']; ?>" /><?= $situacao['nome'] ?></button>
                                <?php
                            }
                            ?>
                        <button type="submit" class="btn btn-small" style="background-color: #E4E4E4; font-weight: bold;"><i class="fa fa-filter" aria-hidden="true"></i> Filtrar</button>
                        </div>

                    </div>
                    <?php
                    if (!empty($_GET['q']) && is_array($_GET['q'])) {
                        $parametrosRemover = array('9|idinterface', '6|data_cad', '7|data_cad', '2|xml_requisicao', '2|xml_resposta', 'n|campoXML');
                        foreach ($_GET['q'] as $ind => $valor) {
                            if (!in_array($ind, $parametrosRemover)) {
                                ?>
                                <input id="q[<?= $ind; ?>]" type="hidden" value="<?= $valor; ?>" name="q[<?= $ind; ?>]" />
                                <?php
                            }
                        }
                    }

                    if (!empty($_GET['buscarpor']) && !empty($_GET['buscarem'])) {
                        ?>
                        <input name="buscarpor" type="hidden" id="buscarporQtd" value="<?= $_GET['buscarpor']; ?>">
                        <input name="buscarem" type="hidden" id="buscaremQtd" value="<?= $_GET['buscarem']; ?>">
                        <?php
                    }

                    if (!empty($_GET['cmp'])) {
                        ?>
                        <input id="cmp" type="hidden" value="<?= $_GET['cmp']; ?>" name="cmp" />
                        <?php
                    }

                    if (!empty($_GET['ord'])) {
                        ?>
                        <input id="ord" type="hidden" value="<?= $_GET['ord']; ?>" name="ord" />
                        <?php
                    }

                    if (!empty($_GET['qtd'])) {
                        ?>
                        <input id="qtd" type="hidden" value="<?= $_GET['qtd']; ?>" name="qtd" />
                        <?php
                    }
                    ?>
                </form>

        		<div id="listagem_informacoes">
		  			<? printf($idioma["informacoes"], $transacoesObj->Get("total")); ?>
                    <br />
          			<? printf($idioma["paginas"], $transacoesObj->Get("pagina"), $transacoesObj->Get("paginas")); ?>
                </div>
                <?php $transacoesObj->GerarTabela($dadosArray, $_GET["q"], $idioma); ?>
                <div id="listagem_form_busca">
                    <div class="input">
                        <div class="inline-inputs"> <?= $idioma["registros"]; ?>
                            <form action="" method="get" id="formQtd">
                                <?php if (isset($_GET["buscarpor"]) && isset($_GET["buscarem"])) { ?>
                                    <input name="buscarpor" type="hidden" id="buscarporQtd" value="<?= $_GET["buscarpor"]; ?>">
                                    <input name="buscarem" type="hidden" id="buscaremQtd" value="<?= $_GET["buscarem"]; ?>">
                                <?php } ?>
                                <?php if (is_array($buscaGet)) {
                                    foreach ($buscaGet as $ind => $valor){
                                ?>
                                    <input id="q[<?=$ind?>]" type="hidden" value="<?=$valor;?>" name="q[<?=$ind?>]" />
                                <?php } } ?>
                                <?php if (isset($_GET["ord"])) {?>
                                    <input id="ord" type="hidden" value="<?=$_GET["ord"];?>" name="ord" />
                                <?php } ?>
                                <input name="qtd" type="text" class="span1 inputQtd" id="qtd" maxlength="4" value="<?= $transacoesObj->get("limite"); ?>" />
                                <a href="javascript:document.getElementById('formQtd').submit();" class="btn small"><?= $idioma["exibir"]; ?></a>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="pagination">
                    <ul><?= $transacoesObj->gerarPaginacao($idioma); ?></ul>
                </div>
                <div class="clearfix"></div>
        </div>
    </div>
  </div>
    <?php incluirLib("rodape", $config, $usuario); ?>
    <script language="javascript" type="text/javascript">
        jQuery(function($) {
            $("#qtd").keypress(isNumber);
            $("#qtd").blur(isNumberCopy);
        });
        var interface = $('.idinterface');
        var idinterface_descricao = $('.idinterface_descricao');
        interface.change(function() {
            idinterface_descricao.val($(this).val());
        });
        idinterface_descricao.change(function() {
            interface.val($(this).val());
        });

        $('form[id=formBusca]').submit(function (e) {
            var input = document.createElement("input");
            input.type = "hidden";
            input.name = 'q[9|idinterface]';
            input.value = document.getElementById('q[9|idinterface]').value;
            document.getElementById('formBusca').appendChild(input);
        });

        $(".idinterface").val(<?= (!empty($_GET['q']['9|idinterface'])) ? $_GET['q']['9|idinterface'] : null; ?>);
        $(".idinterface_descricao").val(<?= (!empty($_GET['q']['9|idinterface'])) ? $_GET['q']['9|idinterface'] : null; ?>);
        $(".situacao").val(<?= (!empty($_GET['q']['1|situacao'])) ? $_GET['q']['1|situacao'] : null; ?>);
        
        $(".data").mask("99/99/9999 99:99:99");

        <?php if (isset($_GET['q']['n|campoXML']) && 'requisicao' == $_GET['q']['n|campoXML']) { ?>
            document.getElementById('xml_resposta').className = 'span1';
            document.getElementById('xml_resposta').value = '';
            document.getElementById('xml_resposta').style.display = 'none';
        <?php } elseif (isset($_GET['q']['n|campoXML']) && 'resposta' == $_GET['q']['n|campoXML']) {  ?>
            document.getElementById('xml_requisicao').className = 'span1';
            document.getElementById('xml_requisicao').value = '';
            document.getElementById('xml_requisicao').style.display = 'none';
        <?php } else { ?>
            document.getElementById('xml_requisicao').className = 'span1';
            document.getElementById('xml_requisicao').value = '';
            document.getElementById('xml_requisicao').style.display = 'none';
            document.getElementById('xml_resposta').className = 'span1';
            document.getElementById('xml_resposta').value = '';
            document.getElementById('xml_resposta').style.display = 'none';
        <?php } ?>
    </script>
</div>
</body>
</html>