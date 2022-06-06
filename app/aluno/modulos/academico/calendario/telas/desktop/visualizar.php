<?php 
$hoje = strtotime(date('Y-m-d H:i'));
foreach($chatsProvas['chats'] as $chat) { 
	$inicioEntradaAluno = strtotime($chat['inicio_entrada_aluno']);
    $fimEntradaAluno = strtotime($chat['fim_entrada_aluno']);

    $inicioEntrada = explode(' ', $chat['inicio_entrada_aluno']);
    $dataInicioEntrada = $inicioEntrada[0];

    $link = 'href="#myModalChats" data-toggle="modal"';
    
    if($dataHoje == $dataInicioEntrada && $hoje <= $inicioEntradaAluno) {
        $corBotao = 'amarelo';
        $textoBotao = 'chat_dia';
        
        $fimEntrada = explode(' ', $chat['fim_entrada_aluno']);
        $idioma['horario_chat_hoje'] = sprintf($idioma['horario_chat_hoje'], $inicioEntrada[1], $fimEntrada[1]);
        $textoModal = 'horario_chat_hoje';
    } elseif(($hoje >= $inicioEntradaAluno || !$inicioEntradaAluno) && ($hoje <= $fimEntradaAluno || !$fimEntradaAluno)) {
        $corBotao = 'azul';
        $textoBotao = 'abrir';
        
        $matricula = $calendarioObj->retornarMatriculaAvasAluno($usuario['idpessoa'], $chat['idava']);
        $link = 'href="/'.$url[0].'/'.$url[1].'/curso/'.$matricula['idmatricula'].'/'.$chat['idava'].'/chats/'.$chat['idchat'].'" target="_blank"';        
    } elseif(($hoje >= $inicioEntradaAluno || !$inicioEntradaAluno) && ($hoje > $fimEntradaAluno)) {
        $corBotao = 'vermelho';
        $textoBotao = 'ler_chat';
        
        $matricula = $calendarioObj->retornarMatriculaAvasAluno($usuario['idpessoa'], $chat['idava']);
        $link = 'href="/'.$url[0].'/'.$url[1].'/curso/'.$matricula['idmatricula'].'/'.$chat['idava'].'/chats/'.$chat['idchat'].'" target="_blank"';
    } elseif($hoje < $inicioEntradaAluno) {
        $corBotao = 'verde';
        $textoBotao = 'chat_proximo';
        $textoModal = 'chat_fechado';
    }
	?>
    <div class="extra-align">
        <div class="text-side-one">
            <h1><?php echo $chat['nome'] ?></h1>
            <p><?php echo $chat['descricao'] ?></p>
            <p>
                <i>
                    <?php if($chat['inicio_entrada_aluno'] && $chat['inicio_entrada_aluno'] != '0000-00-00 00:00:00') { echo formataData($chat['inicio_entrada_aluno'], 'br',1); } else { echo '--'; } ?>
                     | 
                    <?php if($chat['fim_entrada_aluno'] && $chat['fim_entrada_aluno'] != '0000-00-00 00:00:00') { echo formataData($chat['fim_entrada_aluno'], 'br',1); } else { echo '--'; } ?>
                </i>
            </p> 
        </div>
        <div class="r-text">
            <hr class="except-line">
            <a <?php echo $link; ?>><div class="btn btn-<?php echo $corBotao; ?> btn-responsive"><?php echo $idioma[$textoBotao]; ?></div></a>
        </div>
    </div>
    <div class="clear"></div>
<?php } ?>
<?php if(count($chatsProvas['chats']) > 0) { ?>
<!-- Modal chats -->
<div class="mbox-side-one">
    <div id="myModalChats" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-side-one">
            <i class="icon-quote-right" data-dismiss="modal"></i>
            <p><div class="alert alert-danger text-center box-size-90"><strong><?php echo $idioma[$textoModal]; ?></strong></div></p>
            <i class="icon-remove m-box" data-dismiss="modal"> <strong><?php echo $idioma['fechar']; ?></strong></i>
        </div>                    
    </div>
</div>
<!-- /Modal chats -->
<?php } ?>
<?php 
$modalProvas = '';
foreach($chatsProvas['provas'] as $prova) {  
	$disciplinas = $calendarioObj->retornarDisiciplinasProvas($prova['id_solicitacao_prova']);
	?>
    <div class="extra-align">
        <div class="text-side-one">
            <h1><?php echo $idioma['prova_agendada_para']; ?> <?php echo formataData($prova["data_realizacao"],'br',0); ?></h1> 
            <p><?php echo $idioma['matricula']; ?> <strong><?php echo $prova['idmatricula'] ?></strong></p>
            <p><?php echo $idioma['curso']; ?> <strong><?php echo $prova['curso'] ?></strong></p>
            <p><?php echo $idioma['escola']; ?> <strong><?php echo $prova['escola'] ?></strong></p>
            <p><?php echo $idioma['disciplinas']; ?> <strong><?php echo implode(', ',$disciplinas); ?></strong></p>
            <p><?php echo $idioma['data_solicitacao']; ?> <strong><?php echo formataData($prova['data_cad'],'br',1) ?></strong></p>           
            <p><?php echo $idioma['situacao']; ?> <strong style="color:<?php echo $cor_status_solicitacao_prova[$prova['situacao']]; ?>;"><?php echo $status_solicitacao_prova[$config['idioma_padrao']][$prova['situacao']]; ?></strong></p>
            <p><i><?php echo substr($prova["hora_realizacao_de"],0,-3); ?> | <?php echo substr($prova["hora_realizacao_ate"],0,-3); ?></i></p>
        </div>
        <div class="r-text">
            <hr class="except-line">
            <?php if($prova['situacao'] == 'C') { ?>
                <a href="#myModal<?php echo $prova['id_solicitacao_prova']; ?>" data-toggle="modal"><div class="btn btn-vermelho"><?php echo $idioma['ver_motivo']; ?></div></a>
				<?php 
				$modalProvas .= '
				<div class="mbox-side-one">
					<div id="myModal'.$prova['id_solicitacao_prova'].'" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-side-one">
							<h1>'.$prova['motivo'].'</h1>
							<i class="icon-quote-right" data-dismiss="modal"></i>';
				
				if($prova['descricao'])		
					$modalProvas .= '<p><div class="alert alert-danger text-center box-size-90"><strong>'.$prova['descricao'].'</strong></div></p>';
				else
					$modalProvas .= '<p>&nbsp;</p>';
				
				$modalProvas .= '<i class="icon-remove m-box" data-dismiss="modal"> <strong>'.$idioma['fechar'].'</strong></i>
						</div>                    
					</div>
				</div>';
			} ?>
        </div>
    </div>
    <div class="clear"></div>
<?php } ?>
<?php echo $modalProvas; ?>