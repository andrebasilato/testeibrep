<? header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <?php incluirLib("head", $config, $usuario); ?>
</head>
<body>

<?php incluirLib("topo", $config, $usuario); ?>
<!-- /Topo -->
<!-- Topo curso -->
<?php incluirLib("topo_curso", $config, $informacoesTopoCurso); ?>
<!-- /Topo curso -->
<!-- Conteudo -->
<div class="content">
    <div class="row-fluid">
        <!-- Disciplinas -->
        <div class="span12">
            <div class="row-fluid show-grid box-bg">
                <div class="top-box box-azul">
                    <h1><?= mb_strtoupper($idioma['titulo']); ?></h1>
                    <i class="icon-book"></i>
                </div>
                <h2 class="ball-icon">&bull;</h2>
                <div class="clear"></div>
                <div class="row-fluid">
                    <div class="div-image">
                        <div class="span12 m-box-table">
                            <img src="<?=$config['datavalid']['caminho_imagem_tutorial'];?>">
                        </div>
                        <div class="span11 float-right m-box-table" align="center">
                            <button
                                    onclick="liberarFoto(event)"
                                    style="width:15%"
                                    class="btn btn-success"
                            >OK</button>
                        </div>
                    </div>
                    <div class="span12 m-box-table div-fotos">
                        <p><?= str_replace('[[ALUNO]]', $_SESSION['cliente_nome'], $idioma['ola']); ?></p>
                        <p><?= $idioma['descricao']; ?></p>
                        <p><?= $idioma['aviso_celular']; ?></p>
                        <p><?= $idioma['aviso_computador']; ?></p>

                        <input type="hidden" name="act" id="act" value="cadastrar">
                        <input type="hidden" name="acao" id="acao" value="cadastrarFoto">
                        <input type="hidden" name="funcao" id="funcao" value="reconhecimento">
                        <br>
                        <br>
                        <center>
                            <div class="" style="max-width: 480px; width:100%; height:480px; margin-bottom: 50px;">
                                <h3><?= $idioma['titulo_foto']; ?></h3>
                                <br>

                                <video id="video" class="video" height="480" width="480" playsinline autoplay muted loop style="max-width: 480px; width:100%; transform: scaleX(-1);"></video>
                                <canvas width="480" height="480" style="display: none; max-width: 480px; width:100%; transform: scaleX(-1);">
                                </canvas>

                                <br>

                                <button class="btn btn-azul" id="limpar" style="float: left;" onclick="limparSnapshot()"><?= $idioma['botao_limpar']; ?></button>
                                <button class="btn btn-azul" id="snapshot" style="float: left;" onclick="snapshot()"><?= $idioma['botao_snapshot']; ?></button>
                                <button class="btn btn-azul" id="enviar" style="float: right;" onclick="enviar(this)"><?= $idioma['botao_enviar']; ?></button>
                            </div>
                        </center>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Disciplinas -->
    </div>
</div>

<!-- /Conteudo -->
<?php incluirLib("rodape", $config, $usuario); ?>
<?php
$idModal = "reconhecimentoFacial";
echo getModalOraculo(
    $idModal,
    "Aviso",
    "Olá! A seguir temos algumas orientações para a foto de reconhecimento facial, é importante que as siga com bastante atenção pois esta foto será utilizada como base para os reconhecimentos futuros aqui na plataforma.
Caso ainda possua alguma dúvida ou dificuldade para tirar a foto, favor entrar em contato conosco através do telefone: " . $config["telefone"]);
?>
<script>
    $('#<?php echo $idModal ?>').modal('show');
</script>
<script src="/assets/js/jquery.1.7.1.min.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-alert.js"></script>
<script src="/assets/js/validation.js"></script>
<link rel="stylesheet" href="/assets/plugins/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="/assets/plugins/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>

<link rel="stylesheet" href="/assets/plugins/facebox/src/facebox.css" type="text/css" media="screen" />
<script src="/assets/plugins/facebox/src/facebox.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-tooltip.js"></script>

<script>
    $('#limpar').hide();
    $('#enviar').hide();

    var video = document.querySelector('video');
    var canvas = window.canvas = document.querySelector('canvas');

    var button = document.querySelector('button');

    function snapshot()
    {
        canvas.getContext('2d').drawImage(video, 0, 0);
        $(video).hide();
        $(canvas).show();
        $('#enviar').show();
        $('#limpar').show();
        $('#snapshot').hide();
    }

    function limparSnapshot()
    {
        $(video).show();
        $(canvas).hide();
        $('#limpar').hide();
        $('#enviar').hide();
        $('#snapshot').show();
    }

    function enviar(el) {
        $(el).attr('disabled','disabled');
        // canvas.width = video.videoWidth;
        // canvas.height = video.videoHeight;

        var idmatricula = <?= $idmatricula; ?>;
        var dataURL = canvas.toDataURL();
        var act = document.getElementById('act').value;
        var acao = document.getElementById('acao').value;
        var funcao = document.getElementById('funcao').value;
        var blobBin = atob(dataURL.split(',')[1]);
        var array = [];
        for(var i = 0; i < blobBin.length; i++) {
            array.push(blobBin.charCodeAt(i));
        }
        var file = new Blob([new Uint8Array(array)], {type: 'image/png'});
        var url = (window.URL || window.webkitURL).createObjectURL(file);
        var data = new FormData();
        data.append('file', file);
        data.append('idmatricula', idmatricula);
        data.append('act', act);
        data.append('acao', acao);
        data.append('funcao', funcao);
        <?php
            if($_GET['opLogin'] == 'sair'){
        ?>
                data.append('saindoSistema', 'S');
        <?php
            }
        ?>

        if(act == 'comparar'){
            var id = document.getElementById('id').value;
            data.append('id', id);
            var local = "index.php";
        }else
        {
            var local = "";
        }

        $.ajax({
            url :  "<?= $config["urlSistema"] . '/' . $url[0] . '/academico/curso/index.reconhecimento.php'?>",
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
        }).done(function(respond){
            <?php
                if ($_GET['opLogin'] == "sair") {
            ?>
                    alert('<?= $idioma['biometria_logout']; ?>')
                    window.location.href = "<?= $config["urlSistema"] . '/' . $url[0] . '/academico/curso/' . $matricula['idmatricula'] . '?opLogin=inatividade'?>";
            <?php
                } else {
            ?>
                    if( respond === "true" ){
                        alert('<?= $idioma['alert_sucesso']; ?>');
                        window.location.href = local;
                    } else if( respond === "false" ){
                        alert('<?= $idioma['alert_warning']; ?>');
                        window.location.href = local;
                    } else if( respond === "limite_datavalid" ){
                        alert('<?= $idioma['alert_limite_datavalid']; ?>');
                        window.location.href = local;
                    }else if(respond == 'liberacao_temporaria_datavalid'){
                        alert('<?= sprintf($idioma['alert_liberacao_temporaria_datavalid'], ucwords(strtolower(explode(' ', $usuario['nome'])[0]))); ?>');
                        window.location.href = local;
                    } else if (respond === "biometria falsa") {
                        alert('<?= $idioma['alert_warning']; ?>');
                        window.location.href = local;
                    }
            <?php
                }
            ?>

        });

    };

    var constraints = {
        audio: false,
        video: {
            width: {
                min: 480,
                ideal: 480,
                max: 480,
            },
            height: {
                min: 480,
                ideal: 480,
                max: 480,
            },
            facingMode: "user"
        },
    };

    function handleSuccess(stream) {
        window.stream = stream; // make stream available to browser console
        video.srcObject = stream;
    }

    function handleError(error) {
        console.log('navigator.getUserMedia error: ', error);
    }

    function ligarCamera() {
        $('.div-fotos').show();
        $('.div-image').hide();
        navigator.mediaDevices.getUserMedia(constraints).
        then(handleSuccess).catch(handleError);
    }

    $('.div-fotos').hide();

    function liberarFoto(e) {
        ligarCamera()
    }

</script>

</body>
</html>
