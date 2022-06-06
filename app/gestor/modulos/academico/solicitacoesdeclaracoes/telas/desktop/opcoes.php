<head>
    <link href="/assets/css/oraculo.css" rel="stylesheet">
    <link href="/assets/css/oraculo.desktop.css" rel="stylesheet">
</head>

  <section id="global">
    <div class="page-header">
        <h1><?php echo $idioma["opcoes"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
    </div>
    <ul class="breadcrumb">
        <li><? echo $idioma["objeto_selecionado"]; ?></li>
        <li class="active"><strong><?php echo $linha["idsolicitacao_declaracao"]; ?></strong></li>
    </ul>

    <? if($linha["situacao"] == "E"){ ?>
        <div class="control-group" style="display:none;" id="campos_cancelar">
        <form method="post" action="" class="form-horizontal"
            onsubmit="return valida_form();">
              <label class="checkbox" style="margin-left:15px;">
                <?= $idioma['motivo_cancelamento']?>
                    <input name="acao" type="hidden" value="indeferirsolicitacao" />
                    <input name="idsolicitacao" type="hidden"
                    value="<?= $linha['idsolicitacao_declaracao']?>" />
                    <textarea id="motivo_cancelamento" name="motivo_cancelamento" class="span4"></textarea>
                </label>
            <div class="form-actions">
                <input type="submit" class="btn btn-primary"
                value="<?= $idioma["btn_salvar"]; ?>">&nbsp;
            </div>
        </form>
        </div>
    </br>
        <div width="100" style="text-align:center;" id ="div_botoes">
            <a onclick="mostrarCamposDeferir();" class="btn btn-primary" id="btn_declaracao_pre"><?= $idioma["btn_deferir"]; ?></a>
            <a onclick="mostrarCamposCancelar();" class="btn btn-danger"
            id="desaprovar">
                <?= $idioma["btn_indeferir"]; ?>
            </a>
        </div>
    <div id="gerardeclaracao">

    </div>
    <? } ?>
    <script src="/assets/js/jquery.1.7.1.min.js"></script>
    <script type="text/javascript">
        function mostrarCamposCancelar() {
            document.getElementById('campos_cancelar').style.display = 'block';
            document.getElementById('div_botoes').style.display = 'none';
            document.getElementById('link_aprovar').style.display = 'none';
        }
        function mostrarCamposDeferir() {
            //document.getElementById('gerardeclaracao').style.display = 'block';
            $("#gerardeclaracao").html('<iframe id="iframe_declaracao_pre" src="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/gerardeclaracao" width="600" height="500" frameborder="0"></iframe>');
            document.getElementById('div_botoes').style.display = 'none';
        }
        function valida_form(){
            var idmotivo = document.getElementById('motivo_cancelamento').value;
            if (idmotivo == '') {
                alert('Selecione o motivo de cancelamento da solicitação.');
                return false;
            }
            return true;
        }
    </script>
  </section>