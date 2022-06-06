<style type="text/css">
.box-ava {
  padding: 0px;
}
.ava-opcoes {
  background-color: #666666;
  width: 150px;
  position: relative;
  top: 0px;
  left: 0px;
  float: left;
  padding: 5px;
  -moz-border-radius: 3px 0px 0px 3px;
  -webkit-border-radius: 3px 0px 0px 3px;
  border-radius: 3px 0px 0px 3px;

  background: #666666;
  background: -moz-linear-gradient(top, #999999 0%, #999999 3%, #666666 100%);
  background: -webkit-gradient( linear, left top, left bottom, color-stop(0, #999999 ), color-stop(0.03, #999999), color-stop(1, #666666));
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#999999', endColorstr='#666666');
  border-right: 1px solid #666666;
}
.ava-conteudo {
  padding: 10px 10px 10px 180px;
}
.semSubMenuEscolhido {
  border: 1px solid #777777;
  padding: 4px 10px 4px 10px;
  background: #F4F4F4;
  -moz-border-radius: 3px;
  -webkit-border-radius: 3px;
  border-radius: 3px;
}
</style>
<div class="ava-opcoes">
  <ul id="menuVertical">
    <li class="semSubMenu <?php if($url[4] == 'resumo') {  echo "semSubMenuEscolhido"; }?>">
      <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/resumo" <?php if($url[4] == 'resumo') {  echo 'style="color:#000000;"'; }?>>
        <div class="divImagem"><img src="<?php if($url[4] == 'resumo') {  echo "/assets/icones/preto/16/home_16.png"; } else { echo "/assets/icones/branco/16/home-branco_16.png"; } ?>" /></div>
        <div class="divImagemHover"><img src="/assets/icones/preto/16/home_16.png" /></div>
        <?php echo $idioma["tab_resumo"]; ?>
      </a>
    </li>
    <li class="semSubMenu <?php if($url[4] == 'editar') {  echo "semSubMenuEscolhido"; }?>">
      <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/editar" <?php if($url[4] == 'editar') {  echo 'style="color:#000000;"'; }?>>
        <div class="divImagem"><img src="<?php if($url[4] == 'editar') {  echo "/assets/icones/preto/16/checklist_16.png"; } else { echo "/assets/icones/branco/16/checklist_16.png"; } ?>" /></div>
        <div class="divImagemHover"><img src="/assets/icones/preto/16/checklist_16.png" /></div>
        <?php echo $idioma["tab_editar"]; ?>
      </a>
    </li>
    <li class="semSubMenu <?php if($url[4] == 'disciplinas') {  echo "semSubMenuEscolhido"; }?>">
      <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/disciplinas" <?php if($url[4] == 'disciplinas') {  echo 'style="color:#000000;"'; }?>>
        <div class="divImagem"><img src="<?php if($url[4] == 'disciplinas') {  echo "/assets/icones/preto/16/checklist_16.png"; } else { echo "/assets/icones/branco/16/checklist_16.png"; } ?>" /></div>
        <div class="divImagemHover"><img src="/assets/icones/preto/16/checklist_16.png" /></div>
        <?php echo $idioma["tab_disciplinas"]; ?>
      </a>
    </li>
    <li class="semSubMenu <?php if($url[4] == 'aulaonline') {  echo "semSubMenuEscolhido"; }?>">
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/aulaonline" <?php if($url[4] == 'aulaonline') {  echo 'style="color:#000000;"'; }?>>
            <div class="divImagem"><img src="<?php if($url[4] == 'aulaonline') {  echo "/assets/icones/preto/16/midias_16.png"; } else { echo "/assets/icones/branco/16/midias_16.png"; } ?>" /></div>
            <div class="divImagemHover"><img src="/assets/icones/preto/16/midias_16.png" /></div>
            <?php echo $idioma["tab_aulaonline"]; ?>
        </a>
    </li>
    <li class="semSubMenu <?php if($url[4] == 'rotasdeaprendizagem') {  echo "semSubMenuEscolhido"; }?>">
      <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/rotasdeaprendizagem" <?php if($url[4] == 'rotasdeaprendizagem') {  echo 'style="color:#000000;"'; }?>>
      <div class="divImagem"><img src="<?php if($url[4] == 'rotasdeaprendizagem') {  echo "/assets/icones/preto/16/posicao_unidades_16.png"; } else { echo "/assets/icones/branco/16/posicao_empreendimentos_16.png"; } ?>" /></div>
      <div class="divImagemHover"><img src="/assets/icones/preto/16/posicao_unidades_16.png" /></div>
      <?php echo $idioma["tab_rotasdeaprendizagem"]; ?>
      </a>
    </li>
    <li class="semSubMenu <?php if($url[4] == 'conteudos') {  echo "semSubMenuEscolhido"; }?>">
      <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/conteudos" <?php if($url[4] == 'conteudos') {  echo 'style="color:#000000;"'; }?>>
        <div class="divImagem"><img src="<?php if($url[4] == 'conteudos') {  echo "/assets/icones/preto/16/pesquisas_16.png"; } else { echo "/assets/icones/branco/16/pesquisas_16.png"; } ?>" /></div>
        <div class="divImagemHover"><img src="/assets/icones/preto/16/pesquisas_16.png" /></div>
        <?php echo $idioma["tab_conteudos"]; ?>
      </a>
    </li>
    <li class="semSubMenu <?php if($url[4] == 'objetosdivisores') {  echo "semSubMenuEscolhido"; }?>">
      <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/objetosdivisores" <?php if($url[4] == 'objetosdivisores') {  echo 'style="color:#000000;"'; }?>>
        <div class="divImagem"><img src="<?php if($url[4] == 'objetosdivisores') {  echo "/assets/icones/preto/16/pesquisas_16.png"; } else { echo "/assets/icones/branco/16/pesquisas_16.png"; } ?>" /></div>
        <div class="divImagemHover"><img src="/assets/icones/preto/16/pesquisas_16.png" /></div>
        <?php echo $idioma["tab_objetos_divisores"]; ?>
      </a>
    </li>
    <li class="semSubMenu <?php if($url[4] == 'videos') {  echo "semSubMenuEscolhido"; }?>">
      <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/videos" <?php if($url[4] == 'videos') {  echo 'style="color:#000000;"'; }?>>
        <div class="divImagem"><img src="<?php if($url[4] == 'videos') {  echo "/assets/icones/preto/16/midias_16.png"; } else { echo "/assets/icones/branco/16/midias_16.png"; } ?>" /></div>
        <div class="divImagemHover"><img src="/assets/icones/preto/16/midias_16.png" /></div>
        <?php echo $idioma["tab_videos"]; ?>
      </a>
    </li>
    <li class="semSubMenu <?php if($url[4] == 'audios') {  echo "semSubMenuEscolhido"; }?>">
      <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/audios" <?php if($url[4] == 'audios') {  echo 'style="color:#000000;"'; }?>>
        <div class="divImagem"><img src="<?php if($url[4] == 'audios') {  echo "/assets/icones/preto/16/relatorio_16.png"; } else { echo "/assets/icones/branco/16/relatorio-branco_16.png"; } ?>" /></div>
        <div class="divImagemHover"><img src="/assets/icones/preto/16/relatorio_16.png" /></div>
        <?php echo $idioma["tab_audios"]; ?>
      </a>
    </li>
    <li class="semSubMenu <?php if($url[4] == 'downloads') {  echo "semSubMenuEscolhido"; }?>">
      <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/downloads" <?php if($url[4] == 'downloads') {  echo 'style="color:#000000;"'; }?>>
        <div class="divImagem"><img src="<?php if($url[4] == 'downloads') {  echo "/assets/icones/preto/16/metas_16.png"; } else { echo "/assets/icones/branco/16/metas_16.png"; } ?>" /></div>
        <div class="divImagemHover"><img src="/assets/icones/preto/16/metas_16.png" /></div>
        <?php echo $idioma["tab_downloads"]; ?>
      </a>
    </li>
    <li class="semSubMenu <?php if($url[4] == 'perguntas') {  echo "semSubMenuEscolhido"; }?>">
      <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/perguntas" <?php if($url[4] == 'perguntas') {  echo 'style="color:#000000;"'; }?>>
        <div class="divImagem"><img src="<?php if($url[4] == 'perguntas') {  echo "/assets/icones/preto/16/perguntas_pesquisa_16.png"; } else { echo "/assets/icones/branco/16/perguntas_pesquisas_16.png"; } ?>" /></div>
        <div class="divImagemHover"><img src="/assets/icones/preto/16/perguntas_pesquisa_16.png" /></div>
        <?php echo $idioma["tab_perguntas"]; ?>
      </a>
    </li>
    <li class="semSubMenu <?php if($url[4] == 'enquetes') {  echo "semSubMenuEscolhido"; }?>">
      <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/enquetes" <?php if($url[4] == 'enquetes') {  echo 'style="color:#000000;"'; }?>>
        <div class="divImagem"><img src="<?php if($url[4] == 'perguntas') {  echo "/assets/icones/preto/16/perguntas_pesquisa_16.png"; } else { echo "/assets/icones/branco/16/perguntas_pesquisas_16.png"; } ?>" /></div>
        <div class="divImagemHover"><img src="/assets/icones/preto/16/perguntas_pesquisa_16.png" /></div>
        <?php echo $idioma["tab_enquetes"]; ?>
      </a>
    </li>
    <li class="semSubMenu <?php if($url[4] == 'links') {  echo "semSubMenuEscolhido"; }?>">
      <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/links" <?php if($url[4] == 'links') {  echo 'style="color:#000000;"'; }?>>
        <div class="divImagem"><img src="<?php if($url[4] == 'links') {  echo "/assets/icones/preto/16/respostas_padroes_16.png"; } else { echo "/assets/icones/branco/16/respostas_padroes_16.png"; } ?>" /></div>
        <div class="divImagemHover"><img src="/assets/icones/preto/16/respostas_padroes_16.png" /></div>
        <?php echo $idioma["tab_links"]; ?>
      </a>
    </li>
    <li class="semSubMenu <?php if($url[4] == 'foruns') {  echo "semSubMenuEscolhido"; }?>">
      <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/foruns" <?php if($url[4] == 'foruns') {  echo 'style="color:#000000;"'; }?>>
        <div class="divImagem"><img src="<?php if($url[4] == 'foruns') {  echo "/assets/icones/preto/16/administrativo_16.png"; } else { echo "/assets/icones/branco/16/administrativos_16.png"; } ?>" /></div>
        <div class="divImagemHover"><img src="/assets/icones/preto/16/administrativo_16.png" /></div>
        <?php echo $idioma["tab_foruns"]; ?>
      </a>
    </li>
    <li class="semSubMenu <?php if($url[4] == 'exercicios') {  echo "semSubMenuEscolhido"; }?>">
      <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/exercicios" <?php if($url[4] == 'exercicios') {  echo 'style="color:#000000;"'; }?>>
        <div class="divImagem"><img src="<?php if($url[4] == 'exercicios') {  echo "/assets/icones/preto/16/contratos_16.png"; } else { echo "/assets/icones/branco/16/contratos_16.png"; } ?>" /></div>
        <div class="divImagemHover"><img src="/assets/icones/preto/16/contratos_16.png" /></div>
        <?php echo $idioma["tab_exercicios"]; ?>
      </a>
    </li>
    <li class="semSubMenu <?php if($url[4] == 'avaliacoes') {  echo "semSubMenuEscolhido"; }?>">
      <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/avaliacoes" <?php if($url[4] == 'avaliacoes') {  echo 'style="color:#000000;"'; }?>>
        <div class="divImagem"><img src="<?php if($url[4] == 'avaliacoes') {  echo "/assets/icones/preto/16/contratos_16.png"; } else { echo "/assets/icones/branco/16/contratos_16.png"; } ?>" /></div>
        <div class="divImagemHover"><img src="/assets/icones/preto/16/contratos_16.png" /></div>
        <?php echo $idioma["tab_avaliacoes"]; ?>
      </a>
    </li>
    <li class="semSubMenu <?php if($url[4] == 'simulados') {  echo "semSubMenuEscolhido"; }?>">
      <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/simulados" <?php if($url[4] == 'simulados') {  echo 'style="color:#000000;"'; }?>>
        <div class="divImagem"><img src="<?php if($url[4] == 'simulados') {  echo "/assets/icones/preto/16/grupos_administrativos_16.png"; } else { echo "/assets/icones/branco/16/grupos_imobiliarios_16.png"; } ?>" /></div>
        <div class="divImagemHover"><img src="/assets/icones/preto/16/grupos_administrativos_16.png" /></div>
        <?php echo $idioma["tab_simulados"]; ?>
      </a>
    </li>
    <?php /*?><li class="semSubMenu <?php if($url[4] == 'tiraduvidas') {  echo "semSubMenuEscolhido"; }?>">
      <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/tiraduvidas" <?php if($url[4] == 'tiraduvidas') {  echo 'style="color:#000000;"'; }?>>
        <div class="divImagem"><img src="<?php if($url[4] == 'tiraduvidas') {  echo "/assets/icones/preto/16/tipolgia_16.png"; } else { echo "/assets/icones/branco/16/tiescolagia_16.png"; } ?>" /></div>
        <div class="divImagemHover"><img src="/assets/icones/preto/16/tipolgia_16.png" /></div>
        <?php echo $idioma["tab_tiraduvidas"]; ?>
      </a>
    </li><?php */?>
    <li class="semSubMenu <?php if($url[4] == 'chats') {  echo "semSubMenuEscolhido"; }?>">
      <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/chats" <?php if($url[4] == 'chats') {  echo 'style="color:#000000;"'; }?>>
        <div class="divImagem"><img src="<?php if($url[4] == 'chats') {  echo "/assets/icones/preto/16/relacionamento_16.png"; } else { echo "/assets/icones/branco/16/relacionamento-branco_16.png"; } ?>" /></div>
        <div class="divImagemHover"><img src="/assets/icones/preto/16/relacionamento_16.png" /></div>
        <?php echo $idioma["tab_chats"]; ?>
      </a>
    </li>
    <li class="semSubMenu <?php if($url[4] == 'discovirtual') {  echo "semSubMenuEscolhido"; }?>">
      <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/discovirtual" <?php if($url[4] == 'discovirtual') {  echo 'style="color:#000000;"'; }?>>
        <div class="divImagem"><img src="<?php if($url[4] == 'discovirtual') {  echo "/assets/icones/preto/16/relacionamento_16.png"; } else { echo "/assets/icones/branco/16/relacionamento-branco_16.png"; } ?>" /></div>
        <div class="divImagemHover"><img src="/assets/icones/preto/16/relacionamento_16.png" /></div>
        <?php echo $idioma["tab_discovirtual"]; ?>
      </a>
    </li>
    <li class="semSubMenu <?php if($url[4] == 'faqs') {  echo "semSubMenuEscolhido"; }?>">
      <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/faqs" <?php if($url[4] == 'faqs') {  echo 'style="color:#000000;"'; }?>>
        <div class="divImagem"><img src="<?php if($url[4] == 'faqs') {  echo "/assets/icones/preto/16/perguntas_pesquisa_16.png"; } else { echo "/assets/icones/branco/16/perguntas_pesquisas_16.png"; } ?>" /></div>
        <div class="divImagemHover"><img src="/assets/icones/preto/16/perguntas_pesquisa_16.png" /></div>
        <?php echo $idioma["tab_faqs"]; ?>
      </a>
    </li>
    <li class="semSubMenu <?php if($url[4] == 'clonarava') {  echo "semSubMenuEscolhido"; }?>">
      <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/clonarava" <?php if($url[4] == 'clonarava') {  echo 'style="color:#000000;"'; }?>>
        <div class="divImagem"><img src="<?php if($url[4] == 'clonarava') {  echo "/assets/icones/preto/16/regioesdeempreendimentos_16.png"; } else { echo "/assets/icones/branco/16/regiao_empreendimentos_16.png"; } ?>" /></div>
        <div class="divImagemHover"><img src="/assets/icones/preto/16/regioesdeempreendimentos_16.png" /></div>
        <?php echo $idioma["tab_clonarava"]; ?>
      </a>
    </li>
    <li class="semSubMenu <?php if($url[4] == 'remover') {  echo "semSubMenuEscolhido"; }?>">
      <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/remover" <?php if($url[4] == 'remover') {  echo 'style="color:#000000;"'; }?>>
        <div class="divImagem"><img src="<?php if($url[4] == 'remover') {  echo "/assets/icones/preto/16/tipos_de_arquivos_16.png"; } else { echo "/assets/icones/branco/16/tipos_de_arquivos_16.png"; } ?>" /></div>
        <div class="divImagemHover"><img src="/assets/icones/preto/16/tipos_de_arquivos_16.png" /></div>
        <?php echo $idioma["tab_remover"]; ?>
      </a>
    </li>
  </ul>
</div>