<script>
    var regras = new Array();
    <?php
    foreach ($config["formulario"] as $fieldsetid => $fieldset) {
        foreach ($fieldset["campos"] as $campoid => $campo) {
            if (is_array($campo["validacao"])) {
                foreach ($campo["validacao"] as $tipo => $mensagem) {
                    if ($campo["id"] != "form_idpais") {
                        if ($campo["tipo"] == "file") { ?>
                            regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $campo["extensoes"]; ?>,<?php echo $campo["tamanho"]; ?>,<?php echo $idioma[$mensagem]; ?>");
                        <?php } else { ?>
                            regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $idioma[$mensagem]; ?>");
                        <?php
                        }
                    } else {
                        ?>
                        regras.push("<?php echo $tipo; ?>,form_idpais3,<?php echo $idioma[$mensagem]; ?>");
    <?php
                    }
                }
            }
        }
    }
    ?>
    
    jQuery(document).ready(function($) {
                <?php
                foreach ($config["formulario"] as $fieldsetid => $fieldset) {
                    foreach ($fieldset["campos"] as $campoid => $campo) {
                        if ($campo["mascara"]) { ?>
                            <?php if ($campo["mascara"] == "99/99/9999") { ?>
                                $("#<?= $campo["id"]; ?>").mask("<?= $campo["mascara"]; ?>");
                                $('#<?= $campo["id"]; ?>').change(function() {
                                    if ($('#<?= $campo["id"]; ?>').val() != '') {
                                        valordata = $("#<?= $campo["id"]; ?>").val();
                                        date = valordata;
                                        ardt = new Array;
                                        ExpReg = new RegExp("(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/[12][0-9]{3}");
                                        ardt = date.split("/");
                                        erro = false;
                                        if (date.search(ExpReg) == -1) {
                                            erro = true;
                                        } else if (((ardt[1] == 4) || (ardt[1] == 6) || (ardt[1] == 9) || (ardt[1] == 11)) && (ardt[0] > 30))
                                            erro = true;
                                        else if (ardt[1] == 2) {
                                            if ((ardt[0] > 28) && ((ardt[2] % 4) != 0))
                                                erro = true;
                                            if ((ardt[0] > 29) && ((ardt[2] % 4) == 0))
                                                erro = true;
                                        }
                                        if (erro) {
                                            alert("\"" + valordata + "\" não é uma data válida!!!");
                                            $('#<?= $campo["id"]; ?>').focus();
                                            $("#<?= $campo["id"]; ?>").val('');
                                            return false;
                                        }
                                        return true;
                                    }
                                });
                            <?php } elseif ($campo["mascara"] == "(99) 9999-9999" || $campo["mascara"] == "(99) 9999-9999?9") { ?>
                                $('#<?= $campo["id"]; ?>').focusout(function() {
                                    var phone, element;
                                    element = $(this);
                                    element.unmask();
                                    phone = element.val().replace(/\D/g, '');
                                    if (phone.length > 10) {
                                        element.mask("(99) 99999-999?9");
                                    } else {
                                        element.mask("(99) 9999-9999?9");
                                    }
                                }).trigger('focusout');
                            <?php } else { ?>
                                $("#<?= $campo["id"]; ?>").mask("<?= $campo["mascara"]; ?>");
                            <?php } ?>
                        <?php
                        }
                        if ($campo["datepicker"]) { ?>
                            $("#<?= $campo["id"]; ?>").datepicker($.datepicker.regional["pt-BR"]);
                        <?php
                        }
                        if ($campo["numerico"]) { ?>
                            $("#<?= $campo["id"]; ?>").keypress(isNumber);
                            $("#<?= $campo["id"]; ?>").blur(isNumberCopy);
                        <?php
                        }
                        if ($campo["decimal"]) { ?>
                            $("#<?= $campo["id"]; ?>").maskMoney({
                                symbol: "R$",
                                decimal: ",",
                                thousands: "."
                            });
                        <?php
                        }
                        if ($campo["json"]) { ?>
                            $('#<?= $campo["id"]; ?>').html('<option value="">– <?= $idioma[$campo["json_input_pai_vazio"]]; ?> –</option>');
                            $('#<?= $campo["json_idpai"]; ?>').change(function() {
                                if ($(this).val()) {
                                    $.getJSON('<?= $campo["json_url"]; ?>', {
                                        <?= $campo["json_idpai"]; ?>: $(this).val(),
                                        ajax: 'true'
                                    }, function(json) {
                                        var options = '<option value="">– <?= $idioma[$campo["json_input_vazio"]]; ?> –</option>';
                                        for (var i = 0; i < json.length; i++) {
                                            var selected = '';
                                            if (json[i].<?= $campo["valor"]; ?> == <?= intval($linha[$campo["valor"]]); ?>)
                                                var selected = 'selected';
                                            options += '<option value="' + json[i].<?= $campo["valor"]; ?> + '" ' + selected + '>' + json[i].<?= $campo["json_campo_exibir"]; ?> + '</option>';
                                        }
                                        $('#<?= $campo["id"]; ?>').html(options);
                                    });
                                } else {
                                    $('#<?= $campo["id"]; ?>').html('<option value="">– <?= $idioma[$campo["json_input_pai_vazio"]]; ?> –</option>');
                                }
                            });

                            $.getJSON('<?= $campo["json_url"]; ?><?= $linha[$campo["json_idpai"]]; ?>', function(json) {
                                var options = '<option value="">- <?= $idioma[$campo["json_input_vazio"]]; ?> -</option>';
                                for (var i = 0; i < json.length; i++) {
                                    var selected = '';
                                    if (json[i].<?= $campo["valor"]; ?> == <?= intval($linha[$campo["valor"]]); ?>)
                                        var selected = 'selected';
                                    options += '<option value="' + json[i].<?= $campo["valor"]; ?> + '" ' + selected + '>' + json[i].<?= $campo["json_campo_exibir"]; ?> + '</option>';
                                }
                                $('#<?= $campo["id"]; ?>').html(options);
                            });
                            <?php
                        }

                        //ALTERAÇÃO PARA OS RELATÓRIOS COM DE E ATE - INÍCIO
                        if ($campo["botao_hide"]) {

                            if ($campo['tipo'] == 'select') { ?>

                                $('#div_form_<?= $campo["iddiv"]; ?>').show();
                                $('#div_form_<?= $campo["iddiv2"]; ?>').show();
                                $('#<?= $campo["id"]; ?> option[value="PER"]').attr('selected', 'selected');

                                $('#<?= $campo["id"]; ?>').change(function() {
                                        var remover_1 = 0;
                                        var remover_2 = 0;
                                        aux_d = $('#<?= $campo["id"]; ?>').attr('value');
                                        div1_obr = '<?= $campo["iddiv_obr"]; ?>';
                                        div2_obr = '<?= $campo["iddiv2_obr"]; ?>';

                                        if (aux_d == 'PER') {
                                            $('#div_form_<?= $campo["iddiv"]; ?>').show("fast");
                                            $('#div_form_<?= $campo["iddiv2"]; ?>').show("fast");

                                            if (div1_obr)
                                                regras.push("required,form_<?= $campo["iddiv"]; ?>,<?= $idioma[$campo["iddiv"] . "_vazio"] ?>");
                                            if (div2_obr)
                                                regras.push("required,form_<?= $campo["iddiv2"]; ?>,<?= $idioma[$campo["iddiv2"] . "_vazio"] ?>");

                                        } else {
                                            $('#div_form_<?= $campo["iddiv2"]; ?>').hide("fast");
                                            $('#div_form_<?= $campo["iddiv"]; ?>').hide("fast");
                                            $('#form_<?= $campo["iddiv"]; ?>').attr("value", "");
                                            $('#form_<?= $campo["iddiv2"]; ?>').attr("value", "");
                                            for (var i = 0; i < regras.length; i++) {
                                                if (regras[i] == 'required,form_<?= $campo["iddiv"]; ?>,<?= $idioma[$campo["iddiv"] . "_vazio"] ?>')
                                                    remover_1 = i;
                                            }
                                            if (remover_1 != 0)
                                                regras.splice(remover_1, 1);
                                            for (var i = 0; i < regras.length; i++) {
                                                if (regras[i] == 'required,form_<?= $campo["iddiv2"]; ?>,<?= $idioma[$campo["iddiv2"] . "_vazio"] ?>')
                                                    remover_2 = i;
                                            }
                                            if (remover_2 != 0)
                                                regras.splice(remover_2, 1);
                                        }
                                    }

                                );
                <?php
                            }
                        }
                        //ALTERAÇÃO PARA OS RELATÓRIOS COM DE E ATE - FIM

                    }
                }
                ?>
    });
</script>