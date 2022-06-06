<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php global $chat;
global $usu_professor;
global $config; ?>
<head>
    <script src="/assets/js/jquery.1.7.1.min.js"></script>
  <?php incluirLib('head', $config, $usu_professor); ?>

<style>
body{background-color: #e4e4e4; padding-top: 20px;}
.area{background-color: #FFF; }
.principal-titulo {
    width: 100% !important;
    float: left !important;
    border-bottom: 1px solid #dedede !important;
    margin-bottom: 10px !important;
}
.principal-titulo h2 {
    color: #797776 !important;
    font-size: 19px !important;
    margin-top: -3px !important;
    margin-bottom: 10px !important;
    letter-spacing: -1px !important;
    float: left !important;
}
.corbgVerde-claro {
    background-color: #6bbd0b !important;
}
.forum-base-titulo {
    height: 50px !important;
    position: relative !important;
    width: 100% !important;
    font-size: 20px !important;
    text-transform: uppercase !important;
    color: #FFF !important;
    line-height: 52px !important;
    font-weight: lighter !important;
    padding-left: 12px !important;
}
.forum-faixa {
    width: 100% !important;
    height: 187px !important;
    background: url(assets/img/forum/bg_cinza.png) no-repeat #e1e1e1 !important;
}
.forum-busca {
    width: 400px !important;
    color: #5a5a5a !important;
    top: 42px !important;
    position: relative !important;
}
.forum-busca h2 {
    color: #5a5a5a !important;
    font-size: 29px !important;
    text-transform: uppercase !important;
    margin-bottom: 5px !important;
}
.forum-faixa-ator {
    position: absolute;
    bottom: 0px;
    right: 0px;
}
.forum-faixa-central {
    width: 540px;
    position: relative;
    left: 50%;
    margin-left: -270px;
    height: 187px;
}
.forum-busca-campo {
    border-radius: 5px;
    background-color: #FFF;
    width: 300px;
}
.forum-busca-campo input {
    top: 4px;
    position: relative;
    width: 267px;
    font-size: 11px;
    padding-top: 6px;
    background-color: transparent;
    border: 0px solid;
    -webkit-appearance: none;
    -webkit-user-select: none;
    box-shadow: none !important;
}
:-webkit-autofill {
    color: #fff !important;
}
.forum-busca-campo a {
}
.forum-breadcrumbs {
    height: 35px;
    width: 100%;
    margin-top: 10px;
    margin-bottom: 10px;
    position: relative;
}
.forum-breadcrumbs-bts {
    font-size: 17px;
    position: relative;
    top: 7px;
}
.bts-forum-criar {
    padding: 8px;
    position: absolute;
    right: 0px;
    top: 0px;
    background-color: #f7f7f7;
}
.forum-coluna {
}
.forum-base-titulo {
    height: 50px;
    position: relative;
    width: 100%;
    font-size: 20px;
    text-transform: uppercase;
    color: #FFF;
    line-height: 52px;
    font-weight: lighter;
    padding-left: 12px;
}
.corbgVerde-escuro {
    background-color: #589a0a !important;
}
.corbgVerde-claro {
    background-color: #6bbd0b !important;
}
.forum-lista-topicos li {
    list-style: none !important;
    background-color: #f2f2f2 !important;
    position: relative !important;
    margin-bottom: 1px !important;
}
.forum-lista-topicos li p {
    width: 90% !important;
    font-size: 14px !important;
    color: #454545 !important;
    position: relative !important;
    padding: 10px !important;
}
.forum-lista-topicos-view {
    position: absolute !important;
    right: 10px !important;
    top: 70% !important;
    margin-top: -20px !important;
}
.forum-lista-alunos {
}
.forum-lista-alunos li {
    list-style: none !important;
    background-color: #f2f2f2 !important;
    position: relative !important;
    margin-bottom: 1px !important;
    position: relative !important;
    float: left !important;
    width: 100% !important;
    padding: 5px !important;
}
.forum-lista-alunos-avatar {
    border-radius: 50% !important;
    width: 45px !important;
    height: 45px !important;
    float: left !important;
    overflow: hidden !important;
    margin-right: 10px !important;
}
.forum-lista-alunos-nome {
    font-size: 14px !important;
    text-transform: uppercase !important;
    position: absolute !important;
    top: 70% !important;
    margin-top: -20px !important;
    left: 56px !important;
}
.forum-lista-alunos-view {
    position: absolute !important;
    right: 10px !important;
    top: 70% !important;
    margin-top: -20px !important;
}
.forum-lista-alunos a {
}
.forum-listagem {
}
.forum-listagem li {
    position: relative !important;
    list-style: none !important;
    width: 100% !important;
    float: left !important;
    padding: 10px !important;
    border-bottom: 1px dotted #CCC !important;
    margin-bottom: 6px !important;
}
.forum-listagem-titulo {
    float: left !important;
    position: relative !important;
    /*width: 53% !important;*/
}
    p {
        margin: 0 0 -5px;
        font-family: Verdana, "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 13px !important;
        line-height: 15px;
    }
.forum-listagem-titulo h2 {
    margin: 0px !important;
    font-size: 22px !important;
    text-transform: uppercase !important;
    letter-spacing: -2px !important;
    color: #0088cc;
}
.forum-listagem-avatar {
    position: relative !important;
    /*float: left !important;*/
    overflow: hidden !important;
    border-radius: 50% !important;
    width: 45px !important;
    height: 45px !important;
    margin-right: 10px !important;
}
.forum-listagem-status {
    float: right !important;
    position: absolute !important;
    right: 0px !important;
    width: 214px !important;
    border-left: 1px solid #e8e8e8 !important;
}
.forum-listagem-coluna {
    width: 71px;
    text-align: center;
    float: left;
}
.forum-listagem-coluna div {
}
.texto-apoio {
    font-size: 12px;
    color: #CCC;
}
.paginacao {
    width: 100%;
    float: left;
    margin-top: 10px;
    margin-bottom: 10px;
}
.paginacao a {
    padding: 7px 10px 5px 10px;
    background-color: #f2f2f2;
    float: left;
    text-transform: uppercase;
    margin: 2px;
}
.paginacao a:hover, .paginacao .active {
    background-color: #0088cc;
    color: #FFF;
}
.forum-form {
    position: relative;
    float: left;
    width: 100%;
    margin-bottom: 20px;
}
.forum-form input[type="submit"] {
    text-align: center;
    width: 142px;
    height: 40px;
    color: #FFF;
    border: none;
}
.forum-form input[type="text"] {
    float: left;
    width: 100%;
    border: 1px solid #d4d4d4;
    height: 35px;
    margin-bottom: 10px;
    padding-left: 10px;
    max-width: 450px;
}
.forum-form textarea {
    float: left;
    width: 100%;
    border: 1px solid #d4d4d4;
    height: 120px;
    margin-bottom: 13px;
    padding-left: 10px;
    max-width: 500px;
}
.forum-form label {
    width: 100%;
    float: left;
    color: #777777;
    font-size: 18px;
    text-transform: uppercase;
    margin-top: 8px;
}
.forum-form .divisor {
    width: 100%;
    float: left;
}

.forum-interna-listagem{
    float:left;
}
.forum-interna-listagem li{
    border-bottom:1px dotted #ccc;
    position:relative;
}
.forum-interna-avatar{
    width: 45px;
    height: 45px;
    border-radius: 50%;
    position: absolute;
    top: 15px;
    overflow: hidden;
    left: 10px;

}
.forum-interna-botoes{
}
.forum-interna-conteudo{
    width:100%;
    padding:10px;
    padding-left:66px;
}
.forum-citacao{
    padding:15px;
    background-color:#fffdc3;
    margin-bottom:10px;
}
.forum-interna-botoes{

    width:100%;
    margin-bottom:30px;
}
.forum-interna-botoes a{
    float:left;
    margin:2px;
    padding:5px 10px 2px 10px;
    text-transform:uppercase;
    color:#FFF;
}
.corbg-btlaranja{
    background-color:#f18d2b;
}
.forum-interna-botoes a img{
    margin-top: -7px;
    margin-left: 3px;
}
.corbgcinza{
    background-color:#4b4b4b !important;
}
.forum-tag{
    padding: 10px;
    text-transform: uppercase;
    position: absolute;
    right: -11px;
    top: -12px;
    color: #FFF;
    letter-spacing: -1px;
    font-size:13px;
}
.forum-interna-conteudo h3{
    position:relative;
}

.forum-interna-titulo{
    background-color:#f7f7f7;
    position:relative;
    float:left;
    padding:10px;
    width:100%;
}
.forum-interna-titulo .forum-listagem-status{
    width:150px;
}
.forum-aviso{
    width:100%;
    padding:20px;
    color:#FFF;
    text-align:center;
    text-transform:uppercase;
    margin-bottom:10px;
}
.corbgVermelho{
    background-color:#C30 !important;
}

.bts-forum-criar{
    color:#FFF;
}
.forum-acoes{
    padding:15px;
    width:100%;
    background-color:#f2f2f2;

}
.forum-acoes a{
    float:left !important;
    width:100% !important;
    padding:5px !important;
    background-color:#949494 !important;
    color:#FFF !important;
    margin-bottom:3px !important;
    text-transform:uppercase !important;
    text-align:center !important;
    transition:all .2s !important;
    color: #0088cc;
    text-decoration: none;

}
.forum-acoes a:hover{

    background-color:#797979;
}
.forum-acoes a img{
    margin-top:-7px;
    margin-left:3px;
}
.lista-chat
{
    background-color: #f2f2f2 !important;
    height: 372px !important;
    overflow-y: scroll !important;
}
.chat-listagem
{
    background-color: #f2f2f2 !important;
    height: 450px !important;
    overflow-y: scroll !important;
}
.chat-titulo
{
    margin-top: -44px !important;
    padding: 0px 10px 0px 59px !important;
    top: 0px !important;
    /*width: 100% !important;*/
}
.chat-tx-apoio
{
    font-size: 10px !important;
    /*margin-top: -7px;*/
    text-transform: uppercase;
}
.chat-tx-vermelho
{
    color: #F30 !important;
}
.chat-tx-verde
{
    color: #093 !important;
}
.chat-avatar-inverso
{
    float: right !important;
}
.chat-listagem-inverso
{
    padding-left: 0px !important;
    padding-right: 59px !important;
}
.chat-input
{
    appearance: textfield !important;
    border: none !important;
    color: #FFF !important;
    float: left !important;
    height: 30px !important;
    max-width: none !important;
    padding-left: 10px !important;
    width: 70% !important;
}
.chat-bt
{
    float: right;
    height: 50px !important;
}
.marge20-up
{
    margin-top: 20px;
}
.chat-input::-webkit-input-placeholder
{
    color: #FFF !important;
}
.chat-input::-moz-placeholder
{
    color: #FFF;
}

.corbgpadrao {
background-color: rgba(11,137,189,1) !important;
}
.principal-area {
padding: 20px !important;
box-sizing: border-box !important;
border-bottom: 1px #CCC solid !important;
border-right: 1px #CCC solid !important;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
    $('.principal-texto').css('overflow', 'hidden');
});
</script>
</head>
<body>
    <div id="content">
        <div class="conteudo" style="width: 95%; margin: 0 auto;">
           <div class="coluna-dados" id="coluna-conteudo">
            <div class="area area-conteudo" >
                <div class="principal-area">

                    <div  class="principal-titulo">
                        <h2>CHAT - <?php echo htmlspecialchars($info['dados']['nome']); ?>
                          <span style="font-weight: 100;background: #CF1826; color: #fff; padding: 3px 14px; top: 33px; border-radius: 10px 0; font-size: 16px; position: absolute; right: 46px;">Ambiente do Tutor</span></h2>
                       </h2>
                    </div>

                    <div style="clear: both"></div>
                    <div class="principal-texto">

                       <div class="row-fluid">
                        <div class="span4" style="background: #f2f2f2"> <!-- coluna 1 -->

                            <div class=""  style="width: 96.7%;">
                                <div class="forum-base-titulo corbgVerde-claro">Participantes</div>
                                <div style="height: 400px !important;width: 103.7% !important;overflow: auto !important;overflow-x: hidden !important;">
                                    <ul id="profPresenceDiv" class="forum-lista-alunos" style="overflow: hidden; width: 91.7%;">
                                                <li id="divprof<?=$_SESSION["usu_professor_idprofessor"]?>">
                                                    <div class="forum-lista-alunos-avatar">
                                                    <img src="/api/get/imagens/professores_avatar/45/45/<?php echo $usu_professor["avatar_servidor"]; ?>" width="45" height="45">
                                                    </div>
                                                    <div  class="forum-lista-alunos-nome"> <a href="#" class="chat-tx-verde">
                                                    <?php echo $usu_professor['nome']; ?>
                                                    <img id="online<?php echo $usu_professor["idprofessor"]; ?>" src="/assets/img/bola_verde.png" alt="Avatar">
                                                    </a><div class="chat-tx-apoio chat-tx-vermelho">PROFESSOR / MONITOR</div></div>
                                                </li>
                                    </ul>
                                    <ul id="presenceDiv" class="forum-lista-alunos" style="overflow: hidden; width: 91.7%;"></ul>
                                    <ul id="othersDiv" class="forum-lista-alunos" style="overflow: hidden; width: 91.7%;"></ul>
                                 </div>
                               <?php /*?> <ul class="forum-lista-alunos lista-chat" style="overflow-x: hidden; width: 94.7%;">
                                    <?php
                                         $i = 1;
                                         foreach ($info['professores'] as $k => $pessoa) : break; ?>
                                            <?php if ($i == 2) {$i = 1; continue 1;} ?>
                                            <li>
                                                <div class="forum-lista-alunos-avatar"><img src="/api/get/imagens/professores_avatar/45/45/<?php echo $info["professores"]["{$k}_avatar"]; ?>" width="45" height="45"></div>
                                                <div  class="forum-lista-alunos-nome"> <a href="#" class="chat-tx-verde"><?php echo $pessoa; ?></a><div class="chat-tx-apoio chat-tx-vermelho">PROFESSOR / MONITOR</div></div>
                                            </li>
                                        <?php $i++; endforeach; ?>

                                        <?php
                                        $i = 1;
                                        foreach ($info['pessoas'] as $k => $pessoa) : break; ?>
                                            <?php if ($i == 2) {$i = 1; continue 1;} ?>
                                            <li>
                                                <div class="forum-lista-alunos-avatar"><img src="/api/get/imagens/pessoas_avatar/45/45/<?php echo $info["pessoas"]["{$k}_avatar"]; ?>" width="45" height="45"></div>
                                                <div  class="forum-lista-alunos-nome"> <a href="#" class="chat-tx-verde"><?php echo $pessoa; ?></a><div class="chat-tx-apoio chat-tx-vermelho"></div></div>
                                            </li>
                                        <?php $i++; endforeach; ?>
                            </ul><?php */?>
                        </div>

                    </div><!-- fim da coluna 1 -->

                    <div class="span8"><!-- fim da coluna 2 -->

                        <ul class="forum-listagem chat-listagem" id="chat-container" style="overflow-x: hidden; margin-left: 0;">

                        <?php
                            $messages = array_reverse( $chat->getResult() );

                            foreach ($messages as $message) {

                                $tipoPessoa = ($message['usuario_tipo'] == 1) ? 'professores' : 'pessoas';

                                if ($tipoPessoa == 'professores' && $message['idpessoa'] == $usu_professor['idprofessor']) {
                                    echo '<li style="border-left: 4px solid #000" data-idmessage="'.$message['idchat_mensagem'].'" data-idpessoa="'.$message['idpessoa'].'">
                                    <div class="forum-listagem-avatar"><img src="/api/get/imagens/professores_avatar/45/45/'.$info[$tipoPessoa]["{$message['idpessoa']}_avatar"].'" width="45" height="45"></div>
                                    <div class="forum-listagem-titulo chat-titulo"><p>'.date('d/m/Y \- H:i:j', strtotime($message['data_cad'])).'</p>
                                    <a href="#"><h2>'.$info[$tipoPessoa][$message['idpessoa']].' diz:</h2></a>
                                    <p>'.nl2br(htmlspecialchars($message['mensagem'])).'</p>
                                    </div>
                                    </li>';
                                    continue 1;
                                }

                                if ($tipoPessoa == 'professores') {
                                    echo '<li data-idmessage="'.$message['idchat_mensagem'].'" data-idpessoa="'.$message['idpessoa'].'">
                                    <div class="forum-listagem-avatar"><img src="/api/get/imagens/professores_avatar/45/45/'.$info[$tipoPessoa]["{$message['idpessoa']}_avatar"].'" width="45" height="45"></div>
                                    <div class="forum-listagem-titulo chat-titulo"><p>'.date('d/m/Y \- H:i:j', strtotime($message['data_cad'])).'</p>
                                    <a href="#"><h2>'.$info[$tipoPessoa][$message['idpessoa']].' diz:</h2></a>
                                    <p>'.nl2br(htmlspecialchars($message['mensagem'])).'</p>
                                    </div>
                                    </li>';
                                    continue 1;
                                }

                                echo '<li data-idmessage="'.$message['idchat_mensagem'].'" data-idpessoa="'.$message['idpessoa'].'">
                                <div class="forum-listagem-avatar"><img src="/api/get/imagens/pessoas_avatar/45/45/'.$info[$tipoPessoa]["{$message['idpessoa']}_avatar"].'" width="45" height="45"></div>
                                <div class="forum-listagem-titulo chat-titulo"><p>'.date('d/m/Y \- H:i:j', strtotime($message['data_cad'])).'</p>
                                <a href="#"><h2>'.$info[$tipoPessoa][$message['idpessoa']].' diz:</h2></a>
                                <p>'.nl2br(htmlspecialchars($message['mensagem'])).'</p>
                                </div>
                                </li>';
                            }
                        ?>

                        </ul>


                        <?php if ((strtotime(date('Y-m-d H:i:s')) < strtotime($info['dados']['fim_entrada_aluno']) ||  null == $info['dados']['fim_entrada_aluno']) && (strtotime(date('Y-m-d H:i:s')) > strtotime($info['dados']['inicio_entrada_aluno']))) { ?>
                        <div class="forum-form marge20-up">
                            <form method="post" class="new-message">
                               <div class="forum-base-titulo corbgpadrao">
                                    <input type="hidden" name="acao" value="novamensagem">
                                    <textarea id="mensagem" name="mensagem" class="chat-input corbgpadrao" autofocus="autofocus" placeholder="ESCREVA SEU RECADO" style="width: 70% !important"></textarea>
									<?php /*?><input type="text" class="chat-input corbgpadrao" name="mensagem" autofocus="autofocus" placeholder="ESCREVA SEU RECADO" style="width: 75% !important" /><?php */?>
                                    <input style="width: 27% !important;float:none;" class="corbgpadrao btfade chat-bt" id="btn_enviar" name="btn_enviar" type="button" value="ENVIAR " />
                                </div>
                            </form>
                        </div>
                        <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
<? #incluirLib("rodape",$config,$usu_professor); ?>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
<script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
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
	var listRef = firebase.database().ref("<?=$GLOBALS['config']['link_integracao_firebase_pessoal']?>/chat/<?php echo $url[3]; ?>/professor");
	var userRef = listRef.push();

	var presenceRef = firebase.database().ref(".info/connected");
	presenceRef.on("value", function(snap) {
					if (snap.val()) {
						//userRef.set(true);
						userRef.set({
							nome: '<?=$_SESSION["usu_professor_nome"]?>',
							idprofessor: '<?=$_SESSION["usu_professor_idprofessor"]?>',
							avatar_servidor: '<?=$usu_professor["avatar_servidor"]?>'
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
	var myProfessor = [];
	listRef.on("child_added", function(snapshot) { //   profPresenceDiv       profOthersDiv
		var user = snapshot.val();
		$("#othersDiv").children("#divprof"+user.idprofessor).remove().fadeOut();
			if(user.idprofessor != '<?=$_SESSION["usu_professor_idprofessor"]?>' && $.inArray(user.idprofessor,myProfessor) == '-1'){

			var div = ' <div class="forum-lista-alunos-avatar">'+
						'	  <img src="/api/get/imagens/professores_avatar/45/45/'+user.avatar_servidor+'" width="45" height="45">'+
						'	  </div> '+
						'	  <div  class="forum-lista-alunos-nome"> <a href="#" class="chat-tx-verde">'+
						'	  '+user.nome+' '+
						'	  <img id="onlinep'+user.idprofessor+'" src="/assets/img/bola_verde.png" alt="Avatar">'+
						'	  </a><div class="chat-tx-apoio chat-tx-vermelho">PROFESSOR / MONITOR</div>'+
						'</div>';

				if (document.getElementById('onlinep'+user.idprofessor) == null){
					$("<li/>")//bola_verde.png
						.attr("id",'divprof'+user.idprofessor)
						.html(div)
						.appendTo("#profPresenceDiv").fadeIn();
				}
				myProfessor.push(user.idprofessor);
		}
    });

    listRef.on("child_removed", function(snapshot) {
		  		var user = snapshot.val();

				$("#onlinep"+user.idprofessor).remove();
				var delet =$("#profPresenceDiv").children("#divprof" + user.idprofessor).html();
				$("#profPresenceDiv").children("#divprof" +user.idprofessor).remove().fadeOut();
				$("<li/>")//bola_verde.png
					.attr("id", 'divprof'+user.idprofessor)
					.html(delet)
					.appendTo("#othersDiv").fadeIn()/*;*/
				myProfessor.splice($.inArray(user.idprofessor,myProfessor));


    });

	var myIds = [];
	var listRef2 = firebase.database().ref("<?=$GLOBALS['config']['link_integracao_firebase_pessoal']?>/chat/<?php echo $url[3]; ?>/aluno");

    listRef2.on("child_added", function(snapshot) { //   profPresenceDiv       profOthersDiv
		var user = snapshot.val();
		$("#othersDiv").children("#div"+user.idaluno).remove().fadeOut();
			if($.inArray(user.idaluno,myIds) == '-1'){

			var div = ' <div class="forum-lista-alunos-avatar">'+
						'	  <img src="/api/get/imagens/pessoas_avatar/45/45/'+user.avatar_servidor+'" width="45" height="45">'+
						'	  </div> '+
						'	  <div  class="forum-lista-alunos-nome"> <a href="#" class="chat-tx-verde">'+
						'	  '+user.nome+' '+
						'	  <img id="online'+user.idaluno+'" src="/assets/img/bola_verde.png" alt="Avatar">'+
						'	  </a>'+
						'</div>';

				if (document.getElementById('online'+user.idaluno) == null){
					$("<li/>")//bola_verde.png
						.attr("id",'div'+user.idaluno)
						.html(div)
						.appendTo("#presenceDiv").fadeIn();
				}
				myIds.push(user.idaluno);
		}
    });

    listRef2.on("child_removed", function(snapshot) {
		  		var user = snapshot.val();

				$("#online"+user.idaluno).remove();
				var delet =$("#presenceDiv").children("#div" + user.idaluno).html();
				$("#presenceDiv").children("#div" +user.idaluno).remove().fadeOut();

				myIds.splice($.inArray(user.idaluno,myIds));

				$("<li/>")//bola_verde.png
					.attr("id", 'div'+user.idaluno)
					.html(delet)
					.appendTo("#othersDiv").fadeIn();

     });
});

var todosprofessores = function(){
		 <? foreach ($info['professores']as $k => $pessoa) {
				if(is_numeric($k)){ ?>
					var div = ' <div class="forum-lista-alunos-avatar">'+
								'	  <img src="/api/get/imagens/pessoas_avatar/45/45/<?=$info['professores'][$k.'_avatar']?>" width="45" height="45">'+
								'	  </div> '+
								'	  <div  class="forum-lista-alunos-nome"> <a href="#" class="chat-tx-verde">'+
								'	  <?=$pessoa?> '+
								'	  </a>'+
								'	  </a><div class="chat-tx-apoio chat-tx-vermelho">PROFESSOR / MONITOR</div>'+
								'</div>';
					if (document.getElementById('divprof<?=$k?>') == null){
						$("<li/>")
							.attr("id",'divprof<?=$k?>')
							.html(div)
							.appendTo("#othersDiv").fadeIn();
					}
			<? } }?>
	};
 var todosalunos = function(){
		 <? foreach ($info['pessoas'] as $k => $pessoa) {
				if(is_numeric($k) and $k != $_SESSION["cliente_idpessoa"]){ ?>
					var div = ' <div class="forum-lista-alunos-avatar">'+
								'	  <img src="/api/get/imagens/pessoas_avatar/45/45/<?=$info['pessoas'][$k.'_avatar']?>" width="45" height="45">'+
								'	  </div> '+
								'	  <div  class="forum-lista-alunos-nome"> <a href="#" class="chat-tx-verde">'+
								'	  <?=$pessoa?> '+
								'	  </a>'+
								'</div>';
					if (document.getElementById('div<?=$k?>') == null){
						$("<li/>")
							.attr("id",'div<?=$k?>')
							.html(div)
							.appendTo("#othersDiv").fadeIn();
					}
			<? } }?>
};

</script>
<script type="text/javascript">
    var chatContainer = document.getElementById('chat-container');
    chatContainer.scrollTop = chatContainer.scrollHeight;
</script>
<script language="javascript" type="text/javascript">
jQuery(document).ready(function($) {

    var Espera = 0;
   // verifica se há mais registros no banco de dados
   var lastId = $('#chat-container .bubble:last-child').attr('data-idmessage');
   var resourceToChatMessages = '/<?php echo Request::url('1-4', '/'); ?>atualizarChat';


	function atualiza(){
	    $.post(resourceToChatMessages,
        {
            lastId: $('#chat-container > li:last-child').attr('data-idmessage'),
            acao: 'atualizarChat'
        }, function (data) {
            var newMessages = JSON.parse(data);
            console.log('Atualizando Chat...');

            for (var message in newMessages) {
                var tipo_pasta = (newMessages[message].usuario_tipo == 1) ? 'professores' : 'pessoas';
					if(newMessages[message].avatar == null){
							newMessages[message].avatar  = '';
						}
                $("#chat-container").append('<li  data-idmessage="'+ newMessages[message].idchat_mensagem +'" data-idpessoa="'+ newMessages[message].idpessoa +'"> <div class="forum-listagem-avatar"><img src="/api/get/imagens/' + tipo_pasta + '_avatar/45/45/' + newMessages[message].avatar + '" width="45" height="45"></div> <div class="forum-listagem-titulo chat-titulo"><p>Agora</p> <a href="#"><h2>' + newMessages[message].nome + ' diz:</h2></a> <p>' + newMessages[message].mensagem + '</p> </div> </li> ');

                var chatContainer = document.getElementById('chat-container');
                chatContainer.scrollTop = chatContainer.scrollHeight;

            }
        });
	}
   // window.alert(idOfChat);

   setInterval(function(){
    // if ($('#chat-container > li:last-child').attr('data-idmessage')) {
	   	todosalunos();
		todosprofessores();
   		atualiza();
    // }
	}, 2000);

	$("#btn_enviar").on("click", function() {
			 	enviaMensagem();
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
                            url: "/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $url[6]; ?>",
                            dataType: "html",
                            success: function(result){
                                    $("#mensagem").val('');
                                    $("#mensagem").focus();
                                    Espera = 0;
                            }
                    });
                }
        }


}(jQuery));
</script>
</div>
</body>
</html>