
<ul class="nav nav-tabs" style="font-size:10px;">
    <li<?php if (!$url[5]) { echo ' class="active"'; } ?>>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $informacoes['idmatricula']; ?>/administrar">
            <?= $idioma['menu_informacoes']; ?>
        </a>
    </li>
    <li<?php if ($url[5] == 'notas') { echo ' class="active"'; } ?>>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $informacoes['idmatricula']; ?>/administrar/notas">
            <?= $idioma['menu_notas_matricula']; ?>
        </a>
    </li>
    <li<?php if ($url[5] == 'documentos') { echo ' class="active"'; } ?>>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $informacoes['idmatricula']; ?>/administrar/documentos">
            <?= $idioma['menu_documentos_matricula']; ?>
        </a>
    </li>
    <?php /* if($informacoes["possui_financeiro"] == "S") { ?>
	    <li<?php if ($url[5] == 'financeiro') { echo ' class="active"'; } ?>>
	        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $informacoes['idmatricula']; ?>/administrar/financeiro">
	            <?= $idioma['menu_financeiro_matricula']; ?>
	        </a>
	    </li>
    <?php } */ ?>
    <li<?php if ($url[5] == 'declaracoes') { echo ' class="active"'; } ?>>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $informacoes['idmatricula']; ?>/administrar/declaracoes">
            <?= $idioma['menu_declaracoes_matricula']; ?>
        </a>
    </li>
    <li<?php if ($url[5] == 'contratos') { echo ' class="active"'; } ?>>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $informacoes['idmatricula']; ?>/administrar/contratos">
            <?= $idioma['menu_contratos_matricula']; ?>
        </a>
    </li>
    <li<?php if ($url[5] == 'mensagens') { echo ' class="active"'; } ?>>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $informacoes['idmatricula']; ?>/administrar/mensagens">
            <?= $idioma['menu_mensagens_matricula']; ?>
        </a>
    </li>
    <li<?php if ($url[5] == 'historico') { echo ' class="active"'; } ?>>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $informacoes['idmatricula']; ?>/administrar/historico">
            <?= $idioma['menu_historico_matricula']; ?>
        </a>
    </li>
      <li>
          <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $informacoes['idmatricula']; ?>/gerar_historico" rel="facebox" <?php/*target="_blank"*/?> style="background-color:#0055D5; color:#FFF">
              <?= $idioma['btn_historico_escolar']; ?>
          </a>
      </li>
    <?php
    if ($informacoes['diploma']['total'] && $informacoes["situacao"]["visualizacoes"][77] ||
        $informacoes["alunoAprovadoNotas"] && $informacoes["alunoAprovadoNotasDias"] &&
        $informacoes["situacao"]["visualizacoes"][77]) {
        if ($informacoes["alunoAprovadoNotas"]) {
            $idFolha = $informacoes['oferta_curso']['idfolha'];
        } else {
            $idFolha = $informacoes['diploma']['idfolha'];
        }
        ?>
        <li>
            <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/folhasregistrosdiplomas/<?= $idFolha; ?>/diplomas/<?= $url[3] ?>/gerar" target="_blank" style="background-color:#0055D5; color:#FFF">
                <?= $idioma['btn_gerar_diploma']; ?>
            </a>
        </li>
        <?php
    }
    ?>
</ul>
