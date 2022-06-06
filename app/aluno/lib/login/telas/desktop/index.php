<? header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?php incluirLib("head", $config, $usuario); ?>
</head>
<style>
    html, body{
        background: url('/assets/aluno/img/bg_login_aluno.jpg') no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        height:100%;
        min-height:100%;
        padding:0;
    }
    .alert{
         position: absolute;          
          z-index: 1;
          width:  100%;
    }
    .login-box {
        position: absolute;
        width: 450px !important;
        height: auto;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);
        margin: 0;
    }
    .login-bg {
        position: relative;
        width: 100% !important;
        padding: 8% 15%;
        background: linear-gradient(to right, rgba(138, 207, 244, .8) 0%, rgba(0, 141, 212, .8) 100%);
        top: -10px;
    }
    .row-fluid-adaptado {
        position: relative;
        background: linear-gradient(to right, rgba(138, 207, 244, .8) 0%, rgba(0, 141, 212, .8) 100%);
        width: 100% !important;
    }
    
    .row-fluid-adaptado:before,
    .row-fluid-adaptado:after {
        display: table;
        line-height: 30px;
        content: "";
    }
    .row-fluid-adaptado:after {
        clear: both;
    }
    * {
        box-sizing: border-box;
    }
    .row-fluid-adaptado [class*="span"] {
        margin-left: 3.564102564102564%;
        *margin-left: 3.5109110747408616%;
        -webkit-box-sizing: border-box;
           -moz-box-sizing: border-box;
                box-sizing: border-box;
    }
</style>
<body>
<!-- Login -->
<?php if($_POST['msg']) { ?>
    <div class="alert <?php if($_POST['msg'] == 'recuperar_senha_enviado_sucesso') { ?>alert-success<?php } else { ?>alert-error<?php } ?> text-center no-margin"><strong><?php echo $idioma[$_POST['msg']]; ?></strong></div>
<?php } ?>
<div class="login-box">
    <div class="login-bg">
        <div class="row-fluid login">
            <div class="align-login">
                <div class="span12 title-login">
                    <img src="/assets/img/ibreptran_logo_pequena.png" alt="Logo"  style="padding-bottom: 20px;" />
                    <h2><?php echo $idioma['bem_vindo']; ?></h2>
                    <h1><?php echo $idioma['painel_aluno']; ?></h1>
                </div>
                <div class="acess-login">
                    <form method="post" id="formLogin" name="formLogin">
                        <input name="opLogin" type="hidden" id="opLogin" value="login" />
                        <div class="span12 box-login">
                            <div class="left-inner-addon">
                                <i class="icon-user"></i>
                                <input type="text" class="form-control span12" id="txt_usuario" name="txt_usuario" value="<?php if($_POST['txt_usuario']) { echo $_POST['txt_usuario']; } else { echo $idioma['seu_email']; } ?>" onFocus="if(this.value=='<?php echo $idioma['seu_email']; ?>')this.value='';" onBlur="if(this.value=='')this.value='<?php echo $idioma['seu_email']; ?>';">
                            </div>
                            <div class="left-inner-addon">
                                <i class="icon-lock"></i>
                                <input type="text" class="form-control span12" id="txt_senha" name="txt_senha" value="<?php echo $idioma['sua_senha']; ?>" onFocus="if(this.value==this.defaultValue){this.value='';this.type='password'};" onBlur="if(this.value==''){this.value=this.defaultValue;this.type='text';}">
                            </div>
                        </div>
                        <div class="span12 enter-login">
                            <input type="submit" class="btn btn-verde btn-medium btn-mob span12" id="entrar" value="<?php echo $idioma['entrar']; ?>">
                            <p><?php echo $idioma['esqueceu_senha']; ?> <span class="click-acess"> <?php echo $idioma['clique_aqui']; ?></span></p>
                        </div>
                    </form>
                </div>
                <div class="pass-login">
                    <form method="post" id="formRecuperar" name="formRecuperar">
                        <p><?php echo $idioma['digite_email']; ?></p>
                        <div class="span12 box-login">
                            <div class="left-inner-addon">
                                <i class="icon-user"></i>
                                <input type="text" name="email" id="email" class="form-control span12" value="<?php echo $idioma['seu_email']; ?>" onFocus="if(this.value==this.defaultValue)this.value='';" onBlur="if(this.value=='')this.value=this.defaultValue;">
                            </div>
                        </div>
                        <div class="span12 enter-login">
                            <input type="hidden" id="opLogin" name="opLogin" value="esqueciMinhaSenha" />
                            <input type="submit" class="btn btn-verde btn-medium btn-mob span12" id="recuperar" value="Recuperar senha">
                            <p><?php echo $idioma['lembrou_senha']; ?> <span class="click-pass"> <?php echo $idioma['clique_aqui']; ?></span></p>
                        </div>
                    </form>
                </div>
            </div>
            <div class="span12 lines no-margin">
                <div class="line-green"></div>
                <div class="line-yellow"></div>
                <div class="line-blue"></div>
            </div>
        </div>
        <div>
            <p class="text-footer"><?php echo $idioma['rodape']; ?></p>
        </div>
    </div>
  </div>
</div>
<!-- /Login -->
<script src="/assets/aluno_novo/js/jquery-1.10.2.min.js"></script>
<script src="/assets/aluno_novo/js/jquery-1.9.1.min.js"></script>
<script src="/assets/aluno_novo/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/assets/aluno_novo/js/jquery.cycle2.min.js"></script>
<script src="/assets/aluno_novo/js/prefixfree.min.js"></script>
<script src="/assets/aluno_novo/js/respond.min.js"></script>
<script src="/assets/aluno_novo/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/aluno_novo/js/main.js"></script>
</body>
</html>