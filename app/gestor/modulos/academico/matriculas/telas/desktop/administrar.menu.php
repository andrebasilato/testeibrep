<ul class="nav nav-tabs" style="font-size:10px;">
    <li<?php if (!$url[5]) {
        echo ' class="active"';
    } ?>>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $informacoes['idmatricula']; ?>/administrar">
            <?= $idioma['menu_informacoes']; ?>
        </a>
    </li>
    <li<?php if ($url[5] == 'notas') {
        echo ' class="active"';
    } ?>>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $informacoes['idmatricula']; ?>/administrar/notas">
            <?= $idioma['menu_notas_matricula']; ?>
        </a>
    </li>
    <li<?php if ($url[5] == 'financeiro') {
        echo ' class="active"';
    } ?>>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $informacoes['idmatricula']; ?>/administrar/financeiro">
            <?= $idioma['menu_financeiro_matricula']; ?>
        </a>
    </li>
    <li<?php if ($url[5] == 'documentos') {
        echo ' class="active"';
    } ?>>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $informacoes['idmatricula']; ?>/administrar/documentos">
            <?= $idioma['menu_documentos_matricula']; ?>
        </a>
    </li>
    <li<?php if ($url[5] == 'declaracoes') {
        echo ' class="active"';
    } ?>>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $informacoes['idmatricula']; ?>/administrar/declaracoes">
            <?= $idioma['menu_declaracoes_matricula']; ?>
        </a>
    </li>
    <li<?php if ($url[5] == 'reconhecimento') {
        echo ' class="active"';
    } ?>>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $informacoes['idmatricula']; ?>/administrar/reconhecimento">
            <?= $idioma['menu_reconhecimento']; ?>
        </a>
    </li>
    <li<?php if ($url[5] == 'contratos') {
        echo ' class="active"';
    } ?>>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $informacoes['idmatricula']; ?>/administrar/contratos">
            <?= $idioma['menu_contratos_matricula']; ?>
        </a>
    </li>
    <li<?php if ($url[5] == 'mensagens') {
        echo ' class="active"';
    } ?>>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $informacoes['idmatricula']; ?>/administrar/mensagens">
            <?= $idioma['menu_mensagens_matricula']; ?>
        </a>
    </li>
    <li<?php if ($url[5] == 'historico') {
        echo ' class="active"';
    } ?>>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $informacoes['idmatricula']; ?>/administrar/historico">
            <?= $idioma['menu_historico_matricula']; ?>
        </a>
    </li>

    <li>
        <a href="/<?= $url[0]; ?>/cadastros/pessoas/<?php echo $informacoes["pessoa"]["idpessoa"]; ?>/acessarcomo"
           target="_blank" onclick="return confirmaAcessoComo('<?php echo $informacoes['pessoa']['nome']; ?>');"
           style="background-color:#0055D5; color:#FFF">
            <?= $idioma['btn_acessar_como']; ?>
        </a>
    </li>

    <li>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $informacoes['idmatricula']; ?>/dossie"
           target="_blank" style="background-color:#0055D5; color:#FFF">
            <?= $idioma['btn_dossie']; ?>
        </a>
    </li>

    <li>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $informacoes['idmatricula']; ?>/gerar_historico"
           rel="facebox" style="background-color:#0055D5; color:#FFF">
            <?= $idioma['btn_historico_escolar']; ?>
        </a>
    </li>
    <?php if(!empty($informacoes['detran']['exibir_botao_credito'])) {?>
        <li>
            <a href="#" style="background-color:#0055D5; color:#FFF" onclick="integracaoDetran('Tem certeza que deseja <?=$informacoes['detran']['exibir_botao_credito']?> créditos?', '/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $informacoes['idmatricula']; ?>/<?=($informacoes['detran']['exibir_botao_credito'] == 'enviar') ? 'enviar_credito_detran' : 'reenviar_credito_detran' ?>')">
                <?= ($informacoes['detran']['exibir_botao_credito'] == 'enviar') ? $idioma['btn_enviar_credito'] : $idioma['btn_reenviar_credito']; ?>
            </a>
        </li>
    <?php } if(!empty($informacoes['detran']['exibir_botao_certificado'])) {?>
        <li>
            <a href="#" style="background-color:#0055D5; color:#FFF" onclick="integracaoDetran('Tem certeza que deseja <?=$informacoes['detran']['exibir_botao_certificado']?> certificado?', '/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $informacoes['idmatricula']; ?>/<?=($informacoes['detran']['exibir_botao_certificado'] == 'enviar') ? 'enviar_certificado_detran' : 'reenviar_certificado_detran' ?>')">
               <?= ($informacoes['detran']['exibir_botao_certificado'] == 'enviar') ? $idioma['btn_enviar_certificado'] : $idioma['btn_reenviar_certificado']; ?>
            </a>
        </li>
    <?php
    }
    if ( !empty($informacoes['detran']['exibir_botao_cancelamento']) && $informacoes['detran_cancelamento'] == 'N' && $informacoes['idsituacaoCancelada'] == $informacoes['idsituacao']) {
        ?>
        <li>
            <a href="#" style="background-color:#0055D5; color:#FFF" onclick="integracaoDetran('Tem certeza que deseja reenviar cancelamento?', '/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $informacoes['idmatricula']; ?>/reenviar_cancelamento_detran')">
                <?= $idioma['btn_reenviar_cancelamento']; ?>
            </a>
        </li>
        <?php
    }
    if (($informacoes['diploma']['total'] || $informacoes["alunoAprovadoNotas"]) && $informacoes["alunoAprovadoNotasDias"]) {
        if ($informacoes["alunoAprovadoNotas"] && empty($informacoes['diploma']['idfolha'])) {
            $idFolha = $informacoes['oferta_curso']['idfolha'];
        } else {
            $idFolha = $informacoes['diploma']['idfolha'];
        }
        ?>
        <li>
            <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/folhasregistrosdiplomas/<?= $idFolha; ?>/diplomas/<?= $url[3] ?>/gerar"
               target="_blank" style="background-color:#0055D5; color:#FFF">
                <?= $idioma['btn_gerar_diploma']; ?>
            </a>
        </li>
        <?php
    }
    ?>
</ul>
<script type="text/javascript">
function integracaoDetran(alerta, url)
{
    var controller = new AbortController()
    var segundos = 7;
    var timeoutId = setTimeout(() => controller.abort(), 1000 * segundos);
    Swal.fire({
        title: alerta,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: 'Sim, eu tenho!',
        cancelButtonText: 'Voltar',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return fetch(url,  { signal: controller.signal })
            .then(resposta => {
                if (!resposta.ok) {
                    throw new Error(resposta.statusText)
                }
                return resposta.json()
            })
            .catch(error => {
                console.error(error);
                var errorMsg = (error.name === 'AbortError') ? 'Tempo de conexão excedido!' : error;
                Swal.fire(
                    'Algo deu errado...', 
                    `${errorMsg}`, 
                    'error'
                )
            })
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((retorno) => {
        if (retorno.isConfirmed) {
            Swal.fire(
                (retorno.value?.erro == false) ? 'Tudo certo!' : 'Algo deu errado...', 
                retorno.value?.mensagem, 
                (retorno.value?.erro == false) ? 'success' : 'error'
            )
            .then(() => {
                document.location.reload(true);
            });
        } 
    });
}
</script>
