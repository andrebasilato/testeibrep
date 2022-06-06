<section id="global">
    <div class="page-header">
        <h1><?=$idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
    </div>

     <div id="aviso" style="display: none">
        Esse chat não está aberto aos alunos.
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
            <?php
            $now = strtotime('now');

            foreach($chats as $chat) {
                $fim = strtotime($chat['fim_entrada_aluno']);
                $inicio = strtotime($chat['inicio_entrada_aluno']);
            ?>
                <tr>
                    <td><?php echo $chat["nome"]; ?></td>
                    <td><?php echo $chat["ava"]; ?></td>
                    <td><?php echo formataData($chat["inicio_entrada_aluno"],'br',1); ?></td>
                    <td><?php echo formataData($chat["fim_entrada_aluno"],'br',1); ?></td>
                    <?php if ($now > $inicio) { ?>
                        <td><a style="cursor: pointer" class="btn btn-mini" onclick="window.open('/aluno/academico/curso/<?= $chat["idmatricula"]; ?>-<?= $chat["idbloco_disciplina"]; ?>/chat/<?php echo $chat["idchat"]; ?>', 'page', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=1000,height=600');"><?php echo $idioma["abrir"];?></a></td>
                    <?php } else { ?>
                        <td><a style="cursor: pointer" class="btn btn-mini"  rel="facebox" onclick="window.alert('Esse chat não está aberto aos alunos.');"><?php echo $idioma["abrir"];?></a></td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </table>
    </div>
</section>