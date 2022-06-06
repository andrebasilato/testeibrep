<!-- <div id="removerNotas" style="display:none;"> -->
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
body {
  background-color: #FFF !important;
  background-image:none;
  padding-top:0px !important;
}
</style>
<script src="/assets/js/jquery.1.7.1.min.js"></script>
<link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
<link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">
</head>
<body style="min-width:500px;">
<div class="page-header">
    <h1 style="margin-bottom: 1rem;border-bottom: 2px solid #f4f4f4;padding-bottom: 6px;"><?php echo $idioma["pagina_titulo"]; ?>&nbsp;<small><?php echo $idioma["pagina_subtitulo"]; ?></small></h1>
</div>
    <form class="form-inline" method="post" onsubmit="return confirmaZerarTentativa();">
    <?php if($_POST["msg"]) { ?>
        <div class="alert alert-success fade in">
            <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
            <strong><?= $idioma[$_POST["msg"]]; ?></strong>
        </div>
        <script>alert('<?= $idioma[$_POST["msg"]]; ?>');</script>
        <?php } ?>
        <div class="">
            <div class="facebox_tentativas">
                <input id='marcar' type="checkbox" class='marcar' name="marca_todas" onclick="marcardesmarcar(event)" />
                <label for='marcar'>Marcar todas</label>
            </div>
        </div>
        <input name="acao" type="hidden" value="zerar_tentativas" />
        <table cellpadding="5" cellspacing="0" class="table table-bordered table-condensed" <?php if($margin) { ?>style="margin-top:<?php echo $margin; ?>px;<?php } ?>">
            

        <tr>
            <td bgcolor="#F4F4F4" style="height:20px;width: 14%"><strong><?php echo $idioma["selecionar"]; ?></strong></td>
            <td bgcolor="#F4F4F4" style="height:20px;width: 48%"><strong><?php echo $idioma["avaliacao_nome"]; ?></strong></td>
            <td bgcolor="#F4F4F4" style="height:20px"><strong><?php echo $idioma["data_realizacao"]; ?></strong></td>
        
            <td bgcolor="#F4F4F4" style="height:20px;width: 15%"><strong><?= $idioma["notas_nota"]; ?>
               
            </strong></td>
        </tr>
        
        <?php foreach($tentativas as $ind => $tentativa) { ?>
            <tr>
            <!-- remover_nota -->
                <td style='text-align: center;'><input name="zerar_tentativas[<?php echo $tentativa["idprova"]; ?>]" class="idprova marcar" type="checkbox" value="<?php echo $tentativa["idprova"]; ?>" /></td>
                <td align="right" style="text-align:center;"><strong><?php echo $tentativa["avaliacao"]; ?></strong></td>
                <td align="right" style="text-align:center;"><strong><?php echo formataData($tentativa["inicio"], "br", 0); ?></strong></td>
                <td align="right" style="text-align:center;"><strong><?php echo $tentativa["nota"]; ?></strong></td>

                <!-- <td style="text-align:center;">
                </td> -->
            </tr>
        <? } ?>
        </table>
        <tr>
        <td colspan="<?= $colunas+1; ?>" style="text-align:center"><input type="submit" name="button" id="button" value="<?= $idioma["btn_zerar"]; ?>" class="btn btn-mini " /></td>
        </tr>
    </form>

    <script>
    $(document).ready(function(){
        var n = $( ".idprova" ).length;

        parent.contador(n);
    });
    function confirmaZerarTentativa(){

        var confirma = confirm('Deseja realmente zerar as tentativas selecionadas?');
        if(confirma) {
            // contador();
            return true;
        } else {
            return false;
        }

    }
    function marcardesmarcar(event){

        $(".marcar").each(
            function() {
                if(event.target != $(this)[0]) $(this).prop("checked", !$(this).prop("checked"));
        });
    }
    </script>
    </body>
</html>
<!-- </div> -->