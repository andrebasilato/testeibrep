<section id="global">
    <div class="page-header">
        <h1><?=$idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
    </div>
    <div class="span9" style="margin-left:0px">
        <table width="700" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
            <tr>
                <td bgcolor="#F4F4F4"><strong><?php echo $idioma["data_prova"];?></strong></td>
                <td bgcolor="#F4F4F4"><strong><?php echo $idioma["periodo"];?></strong></td>
                <td bgcolor="#F4F4F4"><strong><?php echo $idioma["em_espera"];?></strong></td>
                <td bgcolor="#F4F4F4"><strong><?php echo $idioma["agendada"];?></strong></td>
                <td bgcolor="#F4F4F4"><strong><?php echo $idioma["cancelada"];?></strong></td>
                <td bgcolor="#F4F4F4">&nbsp;</td>
                <td bgcolor="#F4F4F4">&nbsp;</td>
            </tr>
            <?php foreach($provas as $prova) { ?>
                <tr>
                    <td><?php echo formataData($prova["data_realizacao"],'br',0); ?></td>
                    <td><?php echo substr($prova["hora_realizacao_de"],0,-3); ?> - <?php echo substr($prova["hora_realizacao_ate"],0,-3); ?></td>
                    <td style="text-align:center;"><?php echo $prova["total_em_espera"]; ?></td>
                    <td style="text-align:center;"><?php echo $prova["total_agendado"]; ?></td>
                    <td style="text-align:center;"><?php echo $prova["total_cancelado"]; ?></td>
                </tr>
			<?php } ?>
        </table>
    </div>
</section>