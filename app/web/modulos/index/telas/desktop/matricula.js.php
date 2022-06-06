
<script type="text/javascript" src="/assets/loja/js/jquery.js"></script>
<script type="text/javascript" src="/assets/loja/js/bootstrap.js"></script>
<script type="text/javascript" src="/assets/loja/js/prefixfree.min.js"></script>
<script type="text/javascript" src='/assets/loja/js/jquery.cycle2.min.js'></script>
<script type="text/javascript" src="/assets/loja/js/jquery.creditCardValidator.js"></script>
<script type="text/javascript" src='/assets/loja/js/jquery.cycle2.carousel.min.js'></script>
<script type="text/javascript" src="/assets/loja/js/linceform/linceform.js"></script>
<script type="text/javascript" src="/assets/loja/js/velocity.min.js"></script>
<script type="text/javascript" src="/assets/loja/js/main.js"></script>
<script type="text/javascript" src="/assets/js/construtor.js"></script>
<script type="text/javascript" src="/assets/js/validation.js"></script>
<script type="text/javascript" src="/assets/js/jquery.maskedinput_1.3.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.center.min.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.msg.min.js"></script>
<script type="text/javascript" src="/assets/plugins/facebox/src/facebox.js"></script>
<script type="text/javascript" src="/assets/js/select/bootstrap-select.js"></script>

<script type="text/javascript" src="<?= $config['pagSeguro']['urlStc']; ?>/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>

<script type="text/javascript">
    var regras = new Array();

	<?php
	foreach($config["formulario_pagamentocurso"] as $fieldsetid => $fieldset) {
		
        foreach($fieldset["campos"] as $campoid => $campo) {
			
            if (is_array($campo["validacao"])){
				
                foreach($campo["validacao"] as $tipo => $mensagem) {
				    
                    if($campo["tipo"] == "file"){
?>
					    regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $campo["extensoes"]; ?>,<?php echo $campo["tamanho"]; ?>,<?php echo $idioma[$mensagem]; ?>");
<?				    }
                    else{ ?>
					    regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $idioma[$mensagem]; ?>");
<?                  }
				}
			}
		}
	}
	?>

    // console.log(regras);

    function formatReal(int) {
        var tmpNeg = int+'';
        var int = parseInt(int.toFixed(2).toString().replace(/[^\d]+/g, ''));
        var tmp = int+'';
        var neg = false;

        if (tmpNeg.indexOf("-") == 0) {
            neg = true;
            tmp = tmp.replace("-","");
        }

        if (tmp.length == 1) {
            tmp = "0"+tmp
        }

        tmp = tmp.replace(/([0-9]{2})$/g, ",$1");

        if (tmp.length > 6) {
            tmp = tmp.replace(/([0-9]{3}),([0-9]{2}$)/g, ".$1,$2");
        }

        if (tmp.length > 9) {
            tmp = tmp.replace(/([0-9]{3}).([0-9]{3}),([0-9]{2}$)/g,".$1.$2,$3");
        }

        if (tmp.length > 12) {
            tmp = tmp.replace(/([0-9]{3}).([0-9]{3}).([0-9]{3}),([0-9]{2}$)/g,".$1.$2.$3,$4");
        }

        if (tmp.indexOf(".") == 0) {
            tmp = tmp.replace(".","");
        }

        if (tmp.indexOf(",") == 0) {
            tmp = tmp.replace(",","0,");
        }

        return (neg ? '-'+tmp : tmp);
    }

    /* PagSeguro */
    // var sessaoPagSeguro = $('[name="sessao_pagseguro"]').val();
    var metodosPagamento =  {'BOLETO' : {}, 'CREDIT_CARD' : {}};
    var tokenCartao =  '';
    var valorProduto = 0.00;
    var qtdParcelas = 0;
    var valorParcela = 0.00;

    function matricular(dadosPost) {
        console.log(dadosPost);
        jQuery.ajax({
            url: '/<?= $url[0]; ?>/json/matricular',
            dataType: "json",
            type: "POST",
            data: dadosPost,
            success: function(matricula) {
                //console.log(matricula);

                var dadosProcessar = '';
                var msgErro = '';

                if (matricula.erro) {
                    alert(matricula.erro);
                    $('button.matricular').prop('disabled', false);
                    fechaLoading();
                    return false;
                }

                if (!matricula.fastconnect.success && matricula.fastconnect.errors){
                    msgErro = 'A matrícula não foi concluída!\n'+matricula.fastconnect.errors[0].message;

                    if (matricula.fastconnect.errors[0].fields){
                        
                        var camposErros = matricula.fastconnect.errors[0].fields;
                        var idx = null;
                        var codigosValidacao = ['05','57','78','99','77','70'];
                        var msgValidacao = [
                            'Não Autorizado',
                            'Cartão Expirado',
                            'Cartão Bloqueado',
                            'Tempo de resposta excedido',
                            'Cartão Cancelado',
                            'Problemas com o Cartão de Crédito',
                        ];

                        for (var i = 0; i < camposErros.length; i++) {
                            idx = ($.inArray( camposErros[i].code, codigosValidacao));

                            msgErro += (idx) ? '\n'+msgValidacao[idx] : '';
                        }
                    }

                    msgErro += '\nTente utilizar outra forma de pagamento!';
                    alert(msgErro);

                    $('button.matricular').prop('disabled', false);
                    fechaLoading();
                    return false;
                }

                $.each(matricula, function(ind, value) {
                    if (typeof value == 'object' || typeof value == 'array') {
                        for (ind2 in value) {
                            dadosProcessar += '<input name="' + ind + '[' + ind2 + ']" type="hidden" value="' + value[ind2] + '">';
                        }
                    } else {
                        dadosProcessar += '<input name="' + ind + '" type="hidden" value="' + value + '">';
                    }
                });

                if (dadosProcessar) {
                    $('#formProcessa').attr('action', '<?= "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/pedido-realizado"; ?>').append(dadosProcessar).submit();
                }
            },
            error: function(matricula) {
                $('button.matricular').prop('disabled', false);
                fechaLoading();
            }
        });
    }

    function processarDetalhesProduto() {
        valorProduto = $('[name="valor_final"]').val();
        qtdParcelas = $('[name="qtd_parcelas"]').val();
        valor = $('[name="valor_parcela"]').val();
    }

    function buscarAluno(email) {

        if (email.indexOf("@") > '0' && (email.lastIndexOf(".") > email.indexOf("@"))) {
            
            $.get("/<?= $url[0]; ?>/json/buscarAlunoPorEmail",{email: email, ajax: 'true'}, function( dados ) {
                
                var data = JSON.parse(dados);
                
                if (!data && $('[name="nome"]').val()) {
                    var data = {
                        "bairro" : '',
                        "celular" : '',
                        "data_nasc" : '',
                        "cep" : '',
                        "documento" : '',
                        "endereco" : '',
                        "idcidade" : '',
                        "idestado" : '',
                        "idpais" : '',
                        "idpessoa" : '',
                        "nome" : '',
                        "numero" : '',
                        "rg": '',
                        "sexo": '',
                        "cnh": '',
                        "categoria": '',
                        "ato_punitivo": '',
                        "rg_orgao_emissor": '',
                        "complemento": ''
                    };
                }

                for (var campo in data) {
                    var valor = data[campo];
                    
                    if (campo == 'data_nasc' && valor) {
                        dt = valor.split("-");
                        valor = dt[2] + '/' + dt[1] + '/' + dt[0];
                        $('[name="data_nasc"]').val(valor);
                    }
                    else if (campo == 'sexo'){
                        if(valor == 'M') {
                            $('#input-sexo .select-checked').html('Masculino');
                            $('#input-sexo ul li[data-select-value="M"]').addClass('checked');
                            $('.col-md-4 option[value="M"]').attr('selected','selected');
                            $('.input-select option[value="M"]').val("M").change();
                        }
                        if(valor == 'F') {
                            $('#input-sexo .select-checked').html('Feminino');
                            $('#input-sexo ul li[data-select-value="F"]').addClass('checked');
                            $('.col-md-4 option[value="F"]').attr('selected','selected');
                            $('.input-select option[value="F"]').val("F").change();
                        }
                    }
                    else if (campo == 'idestado'){
                        $('#idestado .select-checked').html(data['estado']);
                        $('#idestado ul li[data-select-value="'+data['idestado']+'"]').addClass('checked');
                        $('.col-md-7 [name="idestado"] option[value="'+data['idestado']+'"]').attr('selected','selected');
                        $('.input-select [name="idestado"] option[value="'+data['idestado']+'"]').val(data['idestado']).change();

                        var estado = $('#idestado ul li.checked').attr('data-select-value');


                        if (estado) {
                            $.get("/<?= $url[0]; ?>/json/retornarCidades",{idestado: estado,ajax: 'true'}, function( data1 ) {

                                var json = JSON.parse(data1);
                                var options = ' <option value="" class="select-placeholder"><?= $idioma['form_idcidade']; ?></option>' +
                                    '<option value="" selected><?= $idioma['form_idcidade']; ?></option>';

                                for (var i = 0; i < json.length; i++) {
                                    var selected = '';

                                    if (json[i].idcidade == data['idcidade']) {
                                        selected = 'selected';
                                        console.log(data['idcidade']);
                                    }

                                    options += '<option value="' + json[i].idcidade + '" '+ selected +'>' + json[i].nome + '</option>';
                                }

                                $("select[name='idcidade']").html(options);
                                regarregarLinceform('idcidade');
                                addScrollSelect($("div.lince-input div"));
                            });
                        }
                    } else if (campo == 'categoria'){
                        console.log(data['categoria']);
                        $('#input-categoria .select-checked').html(data['categoria']);
                        $('#input-categoria ul li[data-select-value="'+data['categoria']+'"]').addClass('checked');
                        $('.col-md-4 [name="categoria"] option[value="'+data['categoria']+'"]').attr('selected','selected');
                        $('.input-select [name="categoria"] option[value="'+data['categoria']+'"]').val(data['categoria']).change();
                    }
                    /*else if (campo == 'idcidade'){
                        $('#idcidade .select-checked').html(data['cidade']);
                        $('#idcidade ul li[data-select-value="'+data['idcidade']+'"]').addClass('checked');
                        $('.col-md-5 [name="idcidade"] option[value="'+data['idcidade']+'"]').attr('selected','selected');
                        $('.input-select [name="idcidade"] option[value="'+data['idcidade']+'"]').val(data['idcidade']).change();
                        regarregarLinceform('idcidade');
                        addScrollSelect($("div.lince-input div"));
                    }*/
                    else{
                        $('[name="' + campo + '"]').val(valor);
                    }
                }
            });
        }
    }

    function addScrollSelect(obj){
        $(obj).find("ul").css("height", "130px").css("overflow", "auto");
    }

    function valida_nome_completo(){ 

        if ($('[name="nome"]').val().split(' ').length <= 1) {
            alert('Informe nome e sobrenome!');
            $('[name="nome"]').focus();

            return false;
        } else {
            $('[name="nome"]').val($('[name="nome"]').val().toUpperCase());
            /*var nomes = $('[name="nome"]').val().split(' ');
            for(var i = 0; i < nomes.length; i++) {
                nomes[i] = nomes[i].charAt(0).toUpperCase() + nomes[i].substr(1).toLowerCase();
                if(i == 0)
                    $('[name="nome"]').val(nomes[i]);
                else {
                    $('[name="nome"]').val($('[name="nome"]').val() + " " + nomes[i]);
                }
            }*/
        }
        return true;
    }

    $(document).ready(function($) {

        var inputEmail = $('input[name="email"]');

        if (inputEmail.val()) {
            var email = inputEmail.val();
            if (email.indexOf("@") > '0' && (email.lastIndexOf(".") > email.indexOf("@"))) {
                buscarAluno(email);
            }
        }

        inputEmail.blur(function() {
            var email = $(this).val();
            if (email.indexOf("@") > '0' && (email.lastIndexOf(".") > email.indexOf("@"))) {
                buscarAluno(email);
            }
        });

        $('#idestado ul li').click(function(e) {
            e.preventDefault();
            var estado = $('#idestado ul li.checked').attr('data-select-value');

            if (estado) {
                $.get("/<?= $url[0]; ?>/json/retornarCidades",{idestado: estado,ajax: 'true'}, function( data ) {

                    var json = JSON.parse(data); 
                    var options = ' <option value="" class="select-placeholder"><?= $idioma['form_idcidade']; ?></option>' +
                        '<option value="" selected><?= $idioma['form_idcidade']; ?></option>';

                    for (var i = 0; i < json.length; i++) {
                        var selected = '';
                        
                        if (json[i].idcidade == idcidade) {
                            selected = 'selected';
                        }

                        options += '<option value="' + json[i].idcidade + '" '+ selected +'>' + json[i].nome + '</option>';
                    }

                    $("select[name='idcidade']").html(options);
                        regarregarLinceform('idcidade');
                    addScrollSelect($("div.lince-input div"));
                });
            } 
            else {
                var options = ' <option value="" class="select-placeholder"><?= $idioma['form_idcidade']; ?></option>' +
                                '<option value="" selected><?= $idioma['form_idcidade']; ?></option>';
                $("select[name='idcidade']").html(options);
                regarregarLinceform('idcidade');
                addScrollSelect($("div.lince-input div"));
            }
        });

        $("div.lince-input div").click(function(){
            addScrollSelect($(this));
        });


        function buscarCEP(cep_informado) {
            // console.log("cep informado = "+cep_informado);

            $.msg({
                autoUnblock : true,
                clickUnblock : false,
                klass : 'white-on-black',
                content: 'Processando solicitação.',
                afterBlock : function() {
                    var self = this;
                    jQuery.ajax({
                        url: "/api/get/cep",
                        dataType: "json",
                        type: "POST",
                        data: {cep: cep_informado},
                        success: function(json) {
                            if (json.sucesso) {
                                $("input[name='endereco']").val(json.endereco)
                                $("input[name='bairro']").val(json.bairro)

                                idcidade = json.idcidade;
                                $("select[name='idestado']").val(json.idestado);
                                clicarValor('idestado', json.idestado);

                                self.unblock();
                            } else {
                                alert('<?= $idioma['json_cep_nao_encontrado']; ?>');
                                self.unblock();
                            }
                        }
                    });
                }
            });
        }

        $("input[name='cep']").blur(function() {
            if ($(this).val()) {
                buscarCEP($(this).val().replace('-', ''));
            }
        });

        function mascaraValor(valor) {
            valor = valor.toString().replace(/\D/g,"");
            valor = valor.toString().replace(/(\d)(\d{8})$/,"$1.$2");
            valor = valor.toString().replace(/(\d)(\d{5})$/,"$1.$2");
            valor = valor.toString().replace(/(\d)(\d{2})$/,"$1,$2");
            return valor
        }

        function condicoesParcelamento() {

            // var inputParcela = $('[name="input_parcela"]'),
            //     brand = $('[name="brand"]'),
            //     valorFinal = $('[name="valor_final"]');

            // PagSeguroDirectPayment.getInstallments({
            //     amount: valorFinal.val(),
            //     brand: brand.val(),
            //     maxInstallmentNoInterest: 2,
            //     success: function(response) {
            //         if (response.installments[brand.val()] != 'undefined' && !response.error) {
            //             var condicoes = response.installments[brand.val()];
            //             var condicaoSelecionada = inputParcela.find('option:selected').val();

            //             inputParcela.find('option').remove();

            //             $(condicoes).each(function( index ) {
            //                 inputParcela.append(
            //                     '<option value="' + this.quantity  + '" data-valor-parcela="' + this.installmentAmount.toFixed(2) + '" data-valor-total="' + this.totalAmount.toFixed(2) + '" ' + ((condicaoSelecionada == this.quantity) ? 'selected' : '') +'>' +
            //                         this.quantity + ((this.quantity == 1) ? ' Parcela de R$ ' + mascaraValor(this.installmentAmount.toFixed(2)) : ' Parcelas de R$ ' + mascaraValor(this.installmentAmount.toFixed(2)) + ' - Total: R$ ' + mascaraValor(this.totalAmount.toFixed(2))) +
            //                     '</option>'
            //                 );
            //             });

            //             inputParcela.trigger('change');
            //             inputParcela.change(function() {
            //                 $('[name="valor_parcela"]').val(inputParcela.find('option:selected').data('valor-parcela'));
            //             });
            //         }
            //     },
            //     error: function(response) {
            //         inputParcela.find('option').remove();
            //         inputParcela.append(
            //             '<option value="1" data-valor-parcela="' + $('[name="valor_parcela_original"]').val() + '" data-valor-total="' + $('[name="valor"]').val() + '">1 Parcela</option>'
            //         );
            //     }
            // });
        }

        // $("input[name='cep']").blur(function() {
            // console.log("cep = "+$(this).val());

            // if ($(this).val()) {
            //     buscarCEP($(this).val().replace('-', ''));
            // }
        // });

        // $(".validar-cupom").click(function() {
        //     var codigoCupom = $('#input-cupom').val();
        //     var parcelas = $('input[name="qtd_parcelas"]').val();
        //     var valor = $('input[name="valor"]').val();

        //     if (codigoCupom) {
        //         var produto = $(this).attr("data-id");
        //         var polo = $(this).attr("data-polo");
        //         var cidade = $(this).attr("data-cidade");

        //         $.getJSON(
        //             '/<?= $url[0]; ?>/json/verificarCupom', {
        //                 idproduto: produto,
        //                 idcidade: cidade,
        //                 cupom: codigoCupom,
        //                 ajax: 'true'
        //             },
        //             function (json) {
        //                 if (json.sucesso) {
        //                     var valorParcelas = formatReal(json.valor_final/parcelas);
        //                     $('[name="valor_parcela"]').val(json.valor_final/parcelas);
        //                     $('[name="valor_final"]').val(json.valor_final);
        //                     $('[name="idcupom"]').val(json.idcupom);
        //                     $('[name="codigo_cupom"]').val(codigoCupom);

        //                     if (json.valor_final > 0) {
        //                         $('#formas-pagamento').show('fast');
        //                         $('#padrao').hide('fast');
        //                         $('.total').html('<sub>' + parcelas + 'x</sub> ' + valorParcelas);

        //                         if ($('[name="brand"]').val()) {
        //                             condicoesParcelamento();
        //                         }
        //                     } else {
        //                         $('[name="forma_pagamento"]').val('');
        //                         $('[name="valor_final"]').val('0.00');
        //                         $('.total').html('R$ 0,00');
        //                         $('#formas-pagamento').hide('fast');
        //                         $('#padrao').show('fast');
        //                     }

        //                     $('.dados-desconto').show('fast');
        //                     $('#valor-desconto').html('R$ ' + formatReal(json.valor_desconto));

        //                 } else {
        //                     $("#cupom_alerta").text('<?= $idioma['cupom_invalido']; ?>').toggleClass('hide');
        //                 }
        //             }
        //         );
        //     } else {
        //         $('.total').html('<sub>' + parcelas + 'x</sub> ' + formatReal(valor/parcelas));
        //         $('[name="valor_parcela"]').val(valor/parcelas);
        //         $('[name="valor_final"]').val(valor);
        //         $('[name="idcupom"]').val('');
        //         $('[name="codigo_cupom"]').val('');
        //         $('.dados-desconto').hide('fast');

        //         $('#formas-pagamento').show('fast');
        //         $('#padrao').hide('fast');
        //     }
        // });

        $('#padrao button.matricular').click(function(e) {
            e.preventDefault();
            $('button.matricular').prop('disabled', true);

            
            if (!valida_nome_completo()){
                $('button.matricular').prop('disabled', false);
                return false;
            }

            if (!validateFields(document.getElementById('form-matricula'), regras)) {
                $('button.matricular').prop('disabled', false);
                return false;
            }

            var dadosPost = {}
            $('input, select').each(function( index ) {
                dadosPost[this.name] = this.value;
            });

            // console.log(dadosPost);
            matricular(dadosPost);
        });

        /* PagSeguro */
        if ($('[name="financeiro"]').val()) { // && sessaoPagSeguro

            // PagSeguroDirectPayment.setSessionId(sessaoPagSeguro);

            processarDetalhesProduto();

            // PagSeguroDirectPayment.getPaymentMethods({
            //     amount: valorProduto,
            //     success: function(metodos) {
            //         if (typeof metodos.paymentMethods.BOLETO == 'object') {
            //             metodosPagamento.BOLETO = metodos.paymentMethods.BOLETO.options;
            //         }

            //         if (typeof metodos.paymentMethods.CREDIT_CARD == 'object') {
            //             metodosPagamento.CREDIT_CARD = metodos.paymentMethods.CREDIT_CARD.options;
            //         }

            //         PagSeguroDirectPayment.onSenderHashReady(function(response){
            //             if(response.status == 'error') {
            //                 return false;
            //             }

            //             $('[name="senderHash"]').val(response.senderHash)
            //         });
            //     },
            //     error: function(erros) {

            //     }
            // });

            $('[name="number"]').blur(function () {
                // condicoesParcelamento();
            });

            $('#cartao button.matricular').click(function(e) {

                e.preventDefault();
                $('button.matricular').prop('disabled', true);
                $('[name="forma_pagamento"]').val('C');
                
                if (!valida_nome_completo()){
                    $('button.matricular').prop('disabled', false);
                    return false;
                }

                if ($('[name="expiry"]').val().length > 5){
                    var dt = $('[name="expiry"]').val().split(' ');
                    $('[name="expiry"]').val(dt[0]+dt[1]+dt[2]);
                }

                regras.push("required,number,Digite o número do cartão.");
                regras.push("required,name,Digite o nome impresso no cartão.");
                regras.push("required,expiry,Digite a validade do cartão.");
                regras.push("required,cvv,Digite o código de verificação do cartão.");
                regras.push("required,input_parcela,Selecione a quantidade de parcelas.");

                if (!validateFields(document.getElementById('form-matricula'), regras)) {
                    $('button.matricular').prop('disabled', false);
                    return false;
                }

                if ($('[name="name"]').val().split(' ').length <= 1) {
                    alert('Nome impresso inválido');
                    $('[name="name"]').focus();
                    $('button.matricular').prop('disabled', false);
                    fechaLoading();
                    return false;
                }

                if ($('[name="expiry"]').val().length != 5) {
                    alert('Validade inválida');
                    $('[name="expiry"]').focus();
                    $('button.matricular').prop('disabled', false);
                    fechaLoading();
                    return false;
                } else {
                    var expirationMonth = $('[name="expiry"]').val().split('/')[0];
                    var expirationYear = $('[name="expiry"]').val().split('/')[1];
                    var data = new Date();

                    if ((expirationMonth > 12 && expirationMonth < 01)
                        || (expirationYear < parseInt(data.getFullYear().toString().substr(-2)) ))
                        {
                            alert('Validade inválida');
                            $('[name="expiry"]').focus();
                            $('button.matricular').prop('disabled', false);
                            fechaLoading();
                            return false;
                        }
                }

                if ($('[name="cvv"]').val().length < 3 || isNaN($('[name="cvv"]').val())) {
                    alert('CVV inválido');
                    $('[name="cvv"]').focus();
                    $('button.matricular').prop('disabled', false);
                    fechaLoading();
                    return false;
                }

                // var param = {
                //     cardNumber: $('[name="number"]').val().replace(/\s/g, ''),
                //     brand: 'visa',//$('[name="brand"]').val(),
                //     cvv: $('[name="cvv"]').val(),
                //     expirationMonth: $('[name="expiry"]').val().split('/')[0],
                //     expirationYear: $('[name="expiry"]').val().split('/')[1],
                //     complete: function(response) {

                //         if (response.error) {
                //             alert('Os dados do cartão são inválidos');
                //             $('button.matricular').prop('disabled', false);
                //             fechaLoading();
                //             return false;
                //         }

                //         $('[name="token"]').val(response.card.token);

                        var dadosPost = {}
                        $('input, select').each(function(index) {
                            dadosPost[this.name] = this.value;
                        });

                        // console.log(dadosPost);
                        matricular(dadosPost);

                //     },
                //     error: function(response) {
                //         $('button.matricular').prop('disabled', false);
                //     }
                // }
                // PagSeguroDirectPayment.createCardToken(param);
            });

            $('#boleto button.matricular').click(function(e) {
                e.preventDefault();
                $('button.matricular').prop('disabled', true);
                $('[name="forma_pagamento"]').val('B');

                if (!valida_nome_completo()){
                    $('button.matricular').prop('disabled', false);
                    return false;
                }

                if (!validateFields(document.getElementById('form-matricula'), regras)) {
                    $('button.matricular').prop('disabled', false);
                    return false;
                }

                var dadosPost = {}
                $('input, select').each(function( index ) {
                    dadosPost[this.name] = this.value;
                });

                matricular(dadosPost);
            });
        }
        // fim PagSeguro

        <?php if (! empty($_GET['cupom'])) { ?>
            $("#desconto").toggleClass('collapse').addClass('collapsed');
            $("#input-cupom").val('<?= $_GET['cupom'] ;?>');
            $(".validar-cupom").trigger('click');
        <?php } ?>
    });
</script>