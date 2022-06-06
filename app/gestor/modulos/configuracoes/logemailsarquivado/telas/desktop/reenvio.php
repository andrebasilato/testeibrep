<div style="overflow: auto; max-height: 230px; width:350px">
    <div class="page-header">
        <h1><?php echo $idioma["pagina_titulo"]; ?></h1>
    </div>
    <form action="" method="post">
        <!--style="height:30px;"-->
        <?= $idioma['legenda_email']?>
        <input name="email" value="<?= $linha['para_email'];?>" type="text" id="email" class="span4" style="margin-top:20px;margin-bottom:20px;" />
        <input name="acao" type="hidden" value="reenviar_email" /> 
        <input name="idemail" type="hidden" value="<?=$url[3];?>" />
        <span style="float:right;">
            <input class="btn" type="submit" name="salvar" style="" id="salvar" value="<?= $idioma['btn_salvar'] ?>" />
        </span>
    </form>
</div>    

