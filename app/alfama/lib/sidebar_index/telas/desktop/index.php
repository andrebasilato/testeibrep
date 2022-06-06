<div class='section section-small'>
    <div class='section-header'>
        <h5><?= $idioma["numeros"]; ?></h5>
    </div>
    <div class='section-body'>
        <div class='row-fluid' style="padding: 10px">
            <table class="" border="0" cellpadding="5" cellspacing="0" style="width:100%">
                <tbody>
                    <?php
                    foreach ($orio_interfaces as $ind => $interface) {
                        if(!isset($interface["total"])) $interface["total"] = 0;
                        ?>
                    <tr style="border-bottom: 1px #E4E4E4 solid">
                            <td><?= $interface["nome"]; ?></td>
                            <td width="30" style="text-align: right">
                                    <?= number_format($interface["total"], 0, ',', '.'); ?>
                            </td>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>