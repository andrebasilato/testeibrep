<section id="global">
    <div class="page-header">
        <h1><?=$idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
    </div>
    <div class="span9" style="margin-left:0px">
        <table width="700" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
            <tr>
                <td bgcolor="#F4F4F4"><strong><?php echo $idioma["chat"];?></strong></td>
                <td bgcolor="#F4F4F4"><strong><?php echo $idioma["ava"];?></strong></td>
                <td bgcolor="#F4F4F4"><strong><?php echo $idioma["data_abertura"];?></strong></td>
                <td bgcolor="#F4F4F4"><strong><?php echo $idioma["data_fechamento"];?></strong></td>
                <td bgcolor="#F4F4F4">&nbsp;</td>
            </tr>
            <?php foreach($chats as $chat) { ?>
                <tr>
                    <td><?php echo $chat["nome"]; ?></td>
                    <td><?php echo $chat["ava"]; ?></td>
                    <td><?php echo formataData($chat["inicio_entrada_aluno"],'br',1); ?></td>
                    <td><?php echo formataData($chat["fim_entrada_aluno"],'br',1); ?></td>
                    <td><a class="btn btn-mini" href="/gestor/academico/avas/<?php echo $chat["idava"]; ?>/chats?q[1|idchat]=<?php echo $chat["idchat"]; ?>" target="_blank"><?php echo $idioma["visualizar"];?></a></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</section>