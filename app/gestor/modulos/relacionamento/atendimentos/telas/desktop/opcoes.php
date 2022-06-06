<style>
.tabela {
	border:#CCC solid 1px;
	width:100%;	
}
.linha {	
	border-bottom:#CCC solid 1px;
}
.coluna {
	border-right:#CCC solid 1px;	
}
.botao{
	background-color:#F9F9F9;
	color:#000;
	height:350px;
	border:#CCC solid 1px;
	cursor:pointer;
}
.nav > .li {
	border:#CCC solid 1px;
	padding:10px;
}
.li {
	border:#CCC solid 1px;
	padding:10px;
}
.li:hover {	
  text-decoration: none;
  background-color: #eeeeee;
}
.nav > .li:hover {	
  text-decoration: none;
  background-color: #eeeeee;
}
</style>

<div class="page-header">
    <span class="pull-right" style="padding-top:3px; color:#999">
        <div class="btn <?php if ($linha['situacao_pessoa'] == 'C') { ?> btn-success <?php } else if ($linha['situacao_pessoa'] == 'P') { ?> btn-danger <?php } else if ($linha['situacao_pessoa'] == 'I') { ?> btn-info <?php } ?>" style="cursor:default;"> <?php echo $situacao_pessoa[$config['idioma_padrao']][$linha['situacao_pessoa']]; ?> </div>
    </span>
    <h3><?php echo $idioma["opcoes"]; ?> #<?php echo $linha['protocolo']; ?> </h3>
    <small><?= $idioma["data_abertura"]; ?><?= formataData($linha["data_cad"],"br",1); ?> (<?php echo diferencaDias($linha["data_cad"]); ?>)</small>        
</div>
<br />

<div class="row span10" style="margin:0px;">
       
    <div class="span7" style="overflow:auto; margin:0px;" id="col1">
        <section id="global" style="width:98%; height:400px;">
  
            <div class="nav li"> 
              <h4><? echo $linha["nome"]; ?></h4>
              <small><?= $idioma["dia"]; ?><?= formataData($linha["data_cad"],"br",1); ?> <?= $idioma["por"]; ?> <?php echo $linha["cliente"]; ?></small>
              <br /><br />
              <?php echo $linha['descricao']; ?>
            </div>
      
            <?php if ($respostas) { ?>
            <?php foreach ($respostas as $resposta) { ?>
            <ul class="nav li ">      
                <li>
                    <small>
                        <strong><?= $idioma["dia"]; ?></strong><?= formataData($resposta["data_cad"],"br",1); ?> <strong><?= $idioma["por"]; ?></strong> 
                        <?php if ($resposta["cliente"]) echo $resposta["cliente"]; else echo $resposta["usuario"]; ?>
                    </small>
                    <span class="pull-right" style="padding-top:3px; color:#999;">
                      <div class="btn <?php if ($resposta['publica'] == 'S') echo 'dropdown-toggle'; else echo 'btn-danger'; ?>" style="cursor:default; <?php if ($resposta['publica'] == 'S') echo 'dropdown-toggle'; else echo 'btn-danger'; ?>"> <?php if ($resposta['publica'] == 'S') echo $idioma["msg_publica"]; else echo $idioma["msg_privada"]; ?> </div>
                    </span>
                    <br /><br />
                    <?php echo $resposta['resposta']; ?>
                </li>              
            </ul> 
            <?php } ?>
            <?php } else { ?>
              <ul class="nav nav-tabs nav-stacked">      
                <li>
                    <span> <?php echo $idioma["sem_mensagem"]; ?> </span>        
                </li>              
              </ul> 
              
            <?php } ?>
          
        </section>
    </div>
    
    <!-- DIREITA -->
    <div class="span3" style="margin-left:0px; margin-top:0px; margin-right:0px; float:right;">
    	
        <a class="btn" style="width:92%;" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/visualiza" rel="facebox"><?php echo $idioma["abrir"]; ?></a><br />
        
        <?php /* <form action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/visualiza" method="post">
        	<input type="submit" class="botao" style="width:100%;" value="<?php echo $idioma['abrir']; ?>" />
        </form> */ ?>
        <br />
        
        <div style="width:100%; overflow:auto; height:100px; border:#CCC solid 1px;">
          <table cellpadding="4" cellspacing="0" width="100%" >
            <tr class="linha">
              <td class="coluna"><strong><?php echo $idioma['quem_visualiza']; ?></strong></td>
            </tr>
            <?php 
            $tamanho = (count($visualizadores) - 1);
            if ($visualizadores) {
              foreach($visualizadores as $ind => $visualizador) {   
            ?>
                <tr <?php if ($tamanho != $ind) { ?>class="linha" <?php } ?>>
                  <td class="coluna"><?php echo $visualizador['usuario']; ?></td>
                </tr>
            <?php 
              }
            } 
            ?>       
          </table>
        </div>
        
        <br />        

        <table class="tabela" cellpadding="4" cellspacing="0">
          <tr class="linha">
            <td class="coluna"><strong><?php echo $idioma['nome_cliente']; ?></strong></td>
            <td><?php echo $linha["cliente"]; ?></td>
          </tr>
          <tr class="linha">
            <td class="coluna"><strong><?php echo $idioma['telefone_cliente']; ?></strong></td>
            <td><?php echo $pessoaDados['telefone']; ?></td>
          </tr>
          <tr class="linha">
            <td class="coluna"><strong><?php echo $idioma['email_cliente']; ?></strong></td>
            <td><?php echo $pessoaDados['email']; ?></td>
          </tr>
          <tr class="linha">
            <td class="coluna"><strong><?php echo $idioma['empreendimento_cliente']; ?></strong></td>
            <td><?php echo $empreendimentoDados['nome']; ?></td>
          </tr>
          <tr>
            <td class="coluna"><strong><?php echo $idioma['unidade_cliente']; ?></strong></td>
            <td><?php echo $unidadeDados["nome"]; ?></td>
          </tr>
        </table>
        
        <br />
        
        <table class="tabela" cellpadding="4" cellspacing="0">
          <tr class="linha">
            <td class="coluna"><strong><?php echo $idioma['abertura_atendimento']; ?></strong></td>
            <td><?php echo formataData($linha["data_cad"],'pt',1); ?></td>
          </tr>
          <tr class="linha">
            <td class="coluna"><strong><?php echo $idioma['interacao_atendimento']; ?></strong></td>
            <td><?php if ($ultimaInteracao['data_cad']) echo formataData($ultimaInteracao['data_cad'],'pt',1); else echo '--'; ?></td>
          </tr>
          <tr class="linha">
            <td class="coluna"><strong><?php echo $idioma['atendente_atendimento']; ?></strong></td>
            <td><?php if ($ultimoAtendente['usuario']) echo $ultimoAtendente['usuario']; else echo '--'; ?></td>
          </tr>
          <tr class="linha">
            <td class="coluna"><strong><?php echo $idioma['assunto_atendimento']; ?></strong></td>
            <td><?php echo $linha["assunto"]; ?></td>
          </tr>
          <tr>
            <td class="coluna"><strong><?php echo $idioma['subassunto_atendimento']; ?></strong></td>
            <td><?php echo $linha["subassunto"]; ?></td>
          </tr>
        </table>
            
    </div>        
    
</div>