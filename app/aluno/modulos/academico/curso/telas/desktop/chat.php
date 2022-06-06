<? header('Content-Type: text/html; charset=utf-8');?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <?php incluirLib("head", $config, $usuario); ?>
    <style>

        .entrouSaiu{
            margin-bottom: 15px;
            font-size: 11px;
            font-style: italic;
            text-align: center;
        }

    </style>
</head>
<body>

<!-- Conteudo -->
<div class="divResizeOne">
    <div class="Resize-top-box box-azul Reset-ResizeOne">
        <h1><?php echo $idioma['participantes']; ?></h1>
        <i class="icon-group"></i>
    </div>

    <div class="Resize-align-2">
        <div id="profPresenceDiv">   </div>
        <div id="presenceDiv">
            <div class="row-fluid">
                <div class="span12 rd-align box-white">
                    <div class="contact-avatar l-align">
                        <img src="/api/get/imagens/pessoas_avatar/40/40/<?php echo $usuario["avatar_servidor"]; ?>" alt="Avatar">
                        <img id="online<?php echo $usuario["idpessoa"]; ?>" src="/assets/img/bola_verde.png" alt="Avatar">
                    </div>
                    <div class="text-side-two details-resume">
                        <h1><?php echo $usuario["nome"]; ?></h1>
                        <p><i><?php echo $idioma['aluno']; ?></i></p>
                    </div>
                </div>
            </div>
        </div>
        <div id="othersDiv">   </div>
    </div>

</div>
<div class="divResizeTwo box-cgray" id="chat-scroll">
    <div class="Reset-ResizeTwo top-box box-gray">
        <h1><?php echo $chat['dados']['nome']; ?></h1>
        <?php /*?><a href=""><i class="closed-x" data-dismiss="modal" style="margin-top: 10px; position: relative;"> <strong><?php echo $idioma['fechar']; ?></strong></i></a><?php */?>
    </div>
    <div class="conversation-box opened-chat" id="chat-container">
        <?php
        foreach ($mensagens as $mensagem) {
            $tipoPessoa = ($mensagem['usuario_tipo'] == 1) ? 'professores' : 'pessoas';
            $class1 = 'send-box';
            $class2 = 'l-align';
            $class3 = 'l-align';
            if($tipoPessoa == 'pessoas' && $mensagem['idpessoa'] == $usuario['idpessoa']) {
                $class1 = 'receive-box';
                $class2 = 'r-align';
                $class3 = 'r-text r-align';
            }
            ?>
            <div class="row-fluid m-box" data-idmessage="<?php echo $mensagem['idchat_mensagem']; ?>">
                <div class="span12 <?php echo $class1; ?>">
                    <div class="<?php echo $class2; ?>">
                        <div class="contact-avatar">
                            <img src="/api/get/imagens/<?php echo $tipoPessoa; ?>_avatar/56/56/<?php echo $chat[$tipoPessoa][$mensagem['idpessoa'].'_avatar']; ?>" alt="Avatar">
                            <h3><?php echo $chat[$tipoPessoa][$mensagem['idpessoa']]; ?></h3>
                        </div>
                    </div>
                    <div class="box-chat <?php echo $class3; ?>">
                        <p><?php echo nl2br(htmlspecialchars($mensagem['mensagem'])); ?></p>
                        <h4><?php echo formataData($mensagem['data_cad'],'br',1); ?></h4>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?php if(($hoje >= $inicioEntradaAluno || !$inicioEntradaAluno) && ($hoje <= $fimEntradaAluno || !$fimEntradaAluno)) { ?>
    <div class="talk-box">
        <form id="form_mensagem" name="form_mensagem" method="post" class="no-margin">
            <input type="hidden" name="acao" id="acao" value="novamensagem">
            <div style="width:80%;"></div>
            <div style="width:80%;"></div>
            <textarea style="width: 75%;margin-top: 10px;margin-left: 10px;min-height: 50px;float: left;" name="mensagem" id="mensagem" placeholder="<?php echo $idioma['digite_mensagem']; ?>"></textarea>
            <input style="margin-top: 16px;float: left;width: 19%;margin-left: 16px;" type="button" id="btn_enviar" name="btn_enviar" class="btn btn-azul btn-large btn-chat" value="<?php echo $idioma['enviar']; ?>" />
            <br>
        </form>
    </div>
    <input  type="checkbox" value="sim" name="metodo" id="metodo" style="float: left;margin-left: 16px;" />
    <span style="font-size: 12px;font-weight: bold;"><?=$idioma['msgEnter']?></span>
<?php } ?>
<!-- /Conteudo -->
<?php //incluirLib("rodape", $config, $usuario); ?>
<script src="/assets/aluno_novo/js/jquery-1.10.2.min.js"></script>
<script src="/assets/aluno_novo/js/jquery-1.9.1.min.js"></script>
<script src="/assets/aluno_novo/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/assets/aluno_novo/js/jquery.cycle2.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.0.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.0.0/firebase-database.js"></script>
<script>
    // Initialize Firebase
    var config = {
        apiKey: "<?= $config['firebase']['apiKey'] ;?>",
        authDomain: "<?= $config['firebase']['authDomain'] ;?>",
        databaseURL: "<?= $config['firebase']['databaseURL'] ;?>",
        projectId: "<?= $config['firebase']['projectId'] ;?>",
        storageBucket: "<?= $config['firebase']['storageBucket'] ;?>"
    };

    firebase.initializeApp(config);

    jQuery(document).ready(function($) {
        var listRef = firebase.database().ref("<?=$GLOBALS['config']['link_integracao_firebase_pessoal']?>/chat/<?php echo $url[6]; ?>/aluno");
        var userRef = listRef.push();

        var presenceRef = firebase.database().ref(".info/connected");
        presenceRef.on("value", function(snap) {
            if (snap.val()) {
                //userRef.set(true);
                userRef.set({
                    nome: '<?=$_SESSION["cliente_nome"]?>',
                    idaluno: '<?=$_SESSION["cliente_idpessoa"]?>',
                    avatar_servidor: '<?=$usuario["avatar_servidor"]?>'
                });
                userRef.onDisconnect().remove();
            }
        });


        listRef.on("value", function(snap) {
            console.log("# of online users = " + snap.numChildren());
        });

        function getMessageId(snapshot) {
            return snapshot.name().replace(/[^a-z0-9\-\_]/gi, '');
        }

        var myIds = [];
        listRef.on("child_added", function(snapshot) {
            var user = snapshot.val();
            $("#othersDiv").children("#div"+user.idaluno).remove().fadeOut();
            if(user.idaluno != '<?=$_SESSION["cliente_idpessoa"]?>' && $.inArray(user.idaluno,myIds) == '-1'){

                var div = ' <div  class="row-fluid">'+
                    '<div class="span12 rd-align box-white">'+
                    ' 	  <div class="contact-avatar l-align">'+
                    '		 <img src="/api/get/imagens/pessoas_avatar/40/40/'+user.avatar_servidor+'" alt="Avatar">'+
                    '		 <img id="online'+user.idaluno+'" src="/assets/img/bola_verde.png" alt="Avatar">'+
                    '	  </div>'+
                    '	 <div  class="text-side-two details-resume">'+
                    '		<h1>'+user.nome+'	</h1>            '+
                    '		<p><i><?php echo $idioma['aluno']; ?></i></p>  '+
                    '	 </div>  '+
                    '</div>'+
                    '</div> ';
                if (document.getElementById('online'+user.idaluno) == null){
                    $("<div/>")//bola_verde.png
                        .attr("id",'div'+user.idaluno).attr("class",'row-fluid')
                        .html(div)
                        .appendTo("#presenceDiv").fadeIn();
                    $("#chat-container").append('<h3 class="entrouSaiu">' + user.nome + ' entrou no chat...</h3>');
                }
                myIds.push(getMessageId(snapshot));
            }
        });


        listRef.on("child_removed", function(snapshot) {
            var user = snapshot.val();
            if(user.idaluno != '<?=$_SESSION["cliente_idpessoa"]?>'){
                $("#online"+user.idaluno).remove();
                var delet =$("#presenceDiv").children("#div" + user.idaluno).html();
                $("#presenceDiv").children("#div" +user.idaluno).remove().fadeOut();

                myIds.splice($.inArray(user.idaluno,myIds));

                $("<div/>")//bola_verde.png
                    .attr("id", 'div'+user.idaluno)
                    .html(delet)
                    .appendTo("#othersDiv").fadeIn();
            }
            $("#chat-container").append('<h3 class="entrouSaiu">' + user.nome + ' saiu do chat...</h3>');
        });

        var myProfessor = [];
        var listRef2 = firebase.database().ref("<?=$GLOBALS['config']['link_integracao_firebase_pessoal']?>/chat/<?php echo $url[6]; ?>/professor");

        listRef2.on("child_added", function(snapshot) { //   profPresenceDiv       profOthersDiv
            var user = snapshot.val();
            $("#othersDiv").children("#divprof"+user.idprofessor).remove().fadeOut();
            if($.inArray(user.idprofessor,myProfessor) == '-1'){
                var div = ' <div class="row-fluid">'+
                    '<div class="span12 rd-align box-white">'+
                    ' 	  <div class="contact-avatar l-align">'+
                    '		 <img src="/api/get/imagens/professores_avatar/40/40/'+user.avatar_servidor+'" alt="Avatar">'+
                    '		 <img id="onlinep'+user.idprofessor+'" src="/assets/img/bola_verde.png" alt="Avatar">'+
                    '	  </div>'+
                    '	 <div class="text-side-two details-resume">'+
                    '		<h1>'+user.nome+'	</h1>            '+
                    '		<p><i><?php echo $idioma['professor']; ?></i></p>  '+
                    '	 </div>  '+
                    '</div>'+
                    '</div> ';

                if (document.getElementById('onlinep'+user.idprofessor) == null){
                    $("<div/>")//bola_verde.png
                        .attr("id",'divprof'+user.idprofessor).attr("class",'row-fluid')
                        .html(div)
                        .appendTo("#profPresenceDiv").fadeIn();
                    $("#chat-container").append('<h3 class="entrouSaiu">' + user.nome + '(Professor) entrou no chat...</h3>');
                }
                myProfessor.push(user.idprofessor);
            }
        });

        listRef2.on("child_removed", function(snapshot) {
            var user = snapshot.val();

            $("#onlinep"+user.idprofessor).remove();
            var delet =$("#profPresenceDiv").children("#divprof" + user.idprofessor).html();
            $("#profPresenceDiv").children("#divprof" +user.idprofessor).remove().fadeOut();

            myProfessor.splice($.inArray(user.idprofessor,myIds));

            $("<div/>")//bola_verde.png
                .attr("id", 'divprof'+user.idprofessor)
                .html(delet)
                .appendTo("#othersDiv").fadeIn();
            $("#chat-container").append('<h3 class="entrouSaiu">' + user.nome + '(Professor) saiu do chat...</h3>');

        });

    });

    var todosprofessores = function(){
        <? foreach ($chat['professores'] as $k => $pessoa) {
        if(is_numeric($k)){ ?>
        var div =  ' <div  class="row-fluid">'+
            '<div class="span12 rd-align box-white">'+
            ' <div class="contact-avatar l-align">'+
            '		 <img src="/api/get/imagens/professores_avatar/40/40/<?=$chat['professores'][$k.'_avatar']?>" alt="Avatar">'+
            '	  </div>'+
            '	 <div  class="text-side-two details-resume">'+
            '		<h1><?=$pessoa?></h1>            '+
            '		<p><i><?php echo $idioma['professor']; ?></i></p>  '+
            '	 </div>  '+
            '</div>'+
            '</div> ';
        if (document.getElementById('divprof<?=$k?>') == null){
            $("<div/>").attr("id",'divprof<?=$k?>').attr("class",'row-fluid')
                .html(div)
                .appendTo("#othersDiv").fadeIn();
        }
        <? } }?>
    };
    var todosalunos = function(){
        <? foreach ($chat['pessoas'] as $k => $pessoa) {
        if(is_numeric($k) and $k != $_SESSION["cliente_idpessoa"]){ ?>
        var div =  ' <div  class="row-fluid">'+
            '<div class="span12 rd-align box-white">'+
            ' <div class="contact-avatar l-align">'+
            '		 <img src="/api/get/imagens/pessoas_avatar/40/40/<?=$chat['pessoas'][$k.'_avatar']?>" alt="Avatar">'+
            '	  </div>'+
            '	 <div  class="text-side-two details-resume">'+
            '		<h1><?=$pessoa?></h1>            '+
            '		<p><i><?php echo $idioma['aluno']; ?></i></p>  '+
            '	 </div>  '+
            '</div>'+
            '</div> ';
        if (document.getElementById('div<?=$k?>') == null){
            $("<div/>").attr("id",'div<?=$k?>').attr("class",'row-fluid')
                .html(div)
                .appendTo("#othersDiv").fadeIn();
        }
        <? } }?>
    };
    jQuery(document).ready(function($) {
        setInterval(function(){
            todosalunos();
            todosprofessores();
        }, 3000);
    });


</script>

<?php if($hoje >= $inicioEntradaAluno && $hoje <= $fimEntradaAluno) { ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var Espera = 0;
            function atualiza(){

                $.post('/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $matricula['idmatricula']; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $url[6]; ?>/atualizarChat',
                    {
                        lastId: $('#chat-container > div:last-of-type').attr('data-idmessage'),
                        acao: 'atualizarChat'
                    }, function (data) {
                        var newMessages = JSON.parse(data);
                        console.log('Atualizando Chat...');

                        for (var message in newMessages) {
                            var tipo_pasta = (newMessages[message].usuario_tipo == 1) ? 'professores' : 'pessoas';
                            var class1 = 'send-box';
                            var class2 = 'l-align';
                            var class3 = 'l-align';
                            if(tipo_pasta == 'pessoas' && newMessages[message].idpessoa == <?php echo $usuario['idpessoa']; ?>) {
                                class1 = 'receive-box';
                                class2 = 'r-align';
                                class3 = 'r-text r-align';
                            }
                            if(newMessages[message].avatar == null){
                                newMessages[message].avatar  = '';
                            }
                            $("#chat-container").append('<div class="row-fluid m-box" data-idmessage="'+ newMessages[message].idchat_mensagem +'"><div class="span12 '+ class1 +'"><div class="'+ class2 +'"><div class="contact-avatar"><img src="/api/get/imagens/'+ tipo_pasta +'_avatar/56/56/' + newMessages[message].avatar + '" alt="Avatar"><h3>' + newMessages[message].nome + '</h3></div></div><div class="box-chat '+ class3 +'"><p>' + newMessages[message].mensagem + '</p><h4>' + newMessages[message].data_cad + '</h4></div></div></div>');
                        }
                        //var chatContainer = document.getElementById('chat-scroll');
                        //chatContainer.scrollTop = chatContainer.scrollHeight;
                    });

            }
            setInterval(function(){
                atualiza();
            }, 2000);

            $("#btn_enviar").on("click", function() {
                enviaMensagem();
            });

            $("#mensagem").on( "keypress", function(event) {
                if (event.which == 13 && !event.shiftKey && $('#metodo').prop('checked')) {
                    event.preventDefault();
                    enviaMensagem();
                }
            });

            function enviaMensagem(){
                if($("#mensagem").val().trim() == ''){
                    alert('<?=$idioma['alerta']?>');
                    $("#mensagem").val('');
                    $("#mensagem").focus();
                    return false;
                }
                if(Espera == 0){
                    Espera = 1;
                    $.ajax({
                        type: "POST",
                        data: { acao:'novamensagem', mensagem:$("#mensagem").val()},
                        url: "/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $matricula['idmatricula']; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $url[6]; ?>",
                        dataType: "html",
                        success: function(result){
                            $("#mensagem").val('');
                            $("#mensagem").focus();
                            Espera = 0;
                        }
                    });
                }
            }

        });

    </script>
<?php } ?>
<script type="text/javascript">

    var chatContainer = document.getElementById('chat-scroll');
    chatContainer.scrollTop = chatContainer.scrollHeight;

</script>
</body>
</html>