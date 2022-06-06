<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/2000/svg">
<head>
    <?php incluirLib('head', $config, $usuario); ?>
</head>
<body>
    <?php incluirLib('topo', $config, $usuario); ?>
    <?php incluirTela('inc_passos', $config, $usuario); ?>

    <div class="container mt50">
        <?php incluirTela('inc_curso', $config, $informacoes); ?>

        <?php $class = ($informacoes['idcurso']) ? 'col-sm-4' : 'col-sm-6'; ?>
        <div class="<?= $class; ?> itemForm border">
            <?php
            $pessoa = $informacoes['pessoa'];
            if (count($pessoa['erros']) > 0) {
                ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span><?= $idioma['form_erros']; ?></span>
                    <?php
                    foreach ($pessoa['erros'] as $ind => $val) {
                        echo '<br />' . $idioma[$val];
                    }
                    ?>
                </div>
                <?php
            }
            ?>
            <h3><?= $idioma['novo_aluno']; ?></h3>
            <form id="form_cadastrar" method='post' class="form-guia">
                <input type="hidden" id="opLogin" name="opLogin" value="atualizar_cadastro">
                <input type="hidden" id="acao" name="acao" value="criar_novo">

                <div class='lince-input'>
                    <label for='input-cpf'></label>
                    <input type='tel' id='documento' name='documento' placeholder='<?= $idioma['form_cpf']; ?>' value="<?= $_POST['documento']; ?>" class='input-cpf' maxlength='14' required>
                </div>
                <div class='lince-input'>
                    <label for='input-email'></label>
                    <input type='email' id='email' name='email' placeholder='<?= $idioma['form_email']; ?>' value="<?= $_POST['email']; ?>" class='input-email' maxlength='100' required>
                </div>
                <div>
                    <input type="submit" value="<?= $idioma['btn_cadastrar']; ?>" class="btBox verde">
                </div>

                <!-- Anti-Spam -->
                <input type='text' name='url-form' class='url-form' value=' ' />
            </form>
        </div>

        <div class="<?= $class; ?> itemForm" id="login">
            <?php
            if ($_POST['msg'] == 'recuperar_senha_enviado_sucesso') {
                ?>
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span><?= $idioma[$_POST['msg']]; ?></span>
                </div>
                <?php
            } elseif ($_POST['msg']) {
                ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span><?= $idioma[$_POST['msg']]; ?></span>
                </div>
                <?php
            }
            ?>
            <h3><?= $idioma['ja_sou_aluno']; ?></h3>
            <form id="form_logar" method='post' class="form-guia">
                <input type="hidden" id="opLogin" name="opLogin" value="atualizar_cadastro">
                <input type="hidden" id="acao" name="acao" value="atualizar_dados">

                <div class='lince-input'>
                    <label for='input-email'></label>
                    <input type='email' id='txt_usuario' name='txt_usuario' placeholder='<?= $idioma['form_email']; ?>' value="<?= $_POST['txt_usuario']; ?>" class='input-email' maxlength='100' required>
                </div>
                <div class='lince-input'>
                    <label for='input-alpha'></label>
                    <input type='password' id='txt_senha' name='txt_senha' placeholder='<?= $idioma['form_senha']; ?>' class='input-alpha' maxlength='30' required>
                </div>
                <div>
                    <input type="submit" value="<?= $idioma['btn_entrar']; ?>" class="btBox verde"><br />
                    <a href="javascript:void" data-dismiss="alert" class="click-acess btTxt"><?= $idioma['esqueci_senha']; ?></a>
                </div>

                <!-- Anti-Spam -->
                <input type='text' name='url-form' class='url-form' value=' ' />
            </form>
        </div>

        <div class="<?= $class; ?> itemForm" id="esqueci_minha_senha" style="display:none;">
            <h3><?= $idioma['recuperar_senha']; ?></h3>
            <form id="form_logar" method='post' class="form-guia">
                <input type="hidden" id="opLogin" name="opLogin" value="esqueciMinhaSenha" />

                <div class='lince-input'>
                    <label for='input-email'></label>
                    <input type='email' id='form_email' name='email' placeholder='<?= $idioma['form_email']; ?>' value="<?= $_POST['email']; ?>" class='input-email' maxlength='100' required>
                </div>
                <div>
                    <input type="submit" value="<?= $idioma['btn_recuperar_senha']; ?>" class="btBox verde"><br />
                    <a href="javascript:void" data-dismiss="alert" class="click-pass btTxt"><?= $idioma['lembrei_senha']; ?></a>
                </div>

                <!-- Anti-Spam -->
                <input type='text' name='url-form' class='url-form' value=' ' />
            </form>
        </div>
    </div>

    <?php incluirLib('rodape', $config, $usuario); ?>
</body>
<script type="text/javascript">
    //Recuperar Senha
    $(".click-acess").click(function() {console.log('teste');
      $("#esqueci_minha_senha").show("fast");
      $("#login").hide("fast");
    });
    $(".click-pass").click(function() {
      $("#esqueci_minha_senha").hide("fast");
      $("#login").show("fast");
    });
</script>
</html>
