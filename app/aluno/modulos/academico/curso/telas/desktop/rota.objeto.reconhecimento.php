<div class="row-fluid">
<h1 style="font-size: 30px;margin-left: 18px;">Reconhecimento</h1>
<hr>

    <?php if (empty($imagemPrincipal)) { ?>
        <div class="div-image">
            <div class="span12 m-box-table">
                <img src="/assets/aluno/img/reconhecimento-tutorial-ibrep.jpg">  
            </div>
            <div class="span12 float-right m-box-table">
                <button 
                    onclick="liberarFoto(event)"
                    style="float: right"
                    class="btn btn-success"
                >OK</button>
            </div>
        </div>
    <?php } ?>
        
    <div class="span12 m-box-table div-fotos">
        <input type="hidden" name="act" id="act" value="cadastrar">
        <input type="hidden" name="acao" id="acao" value="compararFoto">
        <input type="hidden" name="funcao" id="funcao" value="reconhecimento">
        <br>
        <br>
        <center>
            <div class="" style="max-width: 480px; width:100%; height:480px; margin-bottom: 50px;">
                <h3><?= $idioma['titulo_foto']; ?></h3>
                <br>

                <video id="video" class="video" height="480" width="480" playsinline autoplay muted loop style="max-width: 480px; width:100%; transform: scaleX(-1);"></video>
                <canvas width="480" height="480" style="display: none; max-width: 480px; width:100%; transform: scaleX(-1);"></canvas>
                
                <br>
                    
                <button class="btn btn-azul" id="limpar" style="float: left;" onclick="limparSnapshot()">Tirar nova foto</button>
                <button class="btn btn-azul" id="snapshot" style="float: left;" onclick="snapshot()">Tirar foto</button>
                <button class="btn btn-azul" id="enviar" style="float: right;" onclick="enviar()">Enviar</button>
            </div>
        </center>
    </div>
</div>


<!-- /Conteudo -->
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

function enviar() {
    var idmatricula = <?= $idmatricula; ?>;
    <?php if ('avaliacoes' != $url[5]) { ?>
        var idobjetorota = <?= $objeto['idobjeto']; ?>;
    <?php } ?>
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
    <?php if ('avaliacoes' != $url[5]) { ?>
        data.append('idobjetorota', idobjetorota);
    <?php } ?>
    data.append('act', act);
    data.append('acao', acao);
    data.append('funcao', funcao);
    var local = "";

    if (act == 'comparar') {    
        var id = document.getElementById('id').value;
        data.append('id', id);
        local = "index.php";
    }

    $.ajax({
        url :  "index.reconhecimento.php",
        type: 'POST',
        data: data,
        contentType: false,
        processData: false,
    }).done(function(respond) {
        if(respond == 'S'){
            alert('<?= $idioma['sucesso']; ?>')
            <?php
            if ('fazer' == $url[7]) { ?>
                window.location.href = "<?= $config["urlSistema"] . '/' . $url[0] . '/academico/curso/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/avaliacoes/' . $url[6] . '/fazer?reconhecimento=' . $recon; ?>";
            <?php } elseif ($objeto['objeto_proximo']['idobjeto']) { ?>
                window.location.href = "<?= $config["urlSistema"] . '/' . $url[0] . '/academico/curso/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($url[6] + 1) . '#conteudo'; ?>";
            <?php } else { ?>
                window.location.href = "<?= $config["urlSistema"] . '/' . $url[0] . '/academico/curso/' . $matricula['idmatricula'] . '/' . $proximo . '/rota/1#conteudo'; ?>";
            <?php } ?>
        } else {
            if(respond == 'limite_datavalid'){
                alert('<?= $idioma['alert_limite_datavalid']; ?>')
            }else if(respond == 'liberacao_temporaria_datavalid'){
                alert('<?= sprintf($idioma['alert_liberacao_temporaria_datavalid'], ucwords(strtolower(explode(' ', $usuario['nome'])[0]))); ?>')
            }else {
                alert('<?= $idioma['falha']; ?>')
            }
            limparSnapshot();
            limparSnapshot();
        }
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

<?php if (empty($imagemPrincipal)) { ?>
    $('.div-fotos').hide();
    
    function liberarFoto(e) {
        ligarCamera()
    }
<?php } else { ?>
    ligarCamera()
<?php } ?>

</script>

</body>
</html>