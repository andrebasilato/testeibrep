<?php
include_once $caminhoApp . '/app/classes/matriculas.class.php';
include_once $caminhoApp . '/app/classes/usuarios.class.php';
$matriculaObj = new Matriculas();
$usuariosObj = new Usuarios();
$usuarios = $usuariosObj->ListarTodas();
$situacaoAtiva = $matriculaObj->retornarSituacaoAtiva();
$situacaoConcluido = $matriculaObj->retornarSituacaoConcluido();
$situacaoHomologarCertificado = $matriculaObj->retornarSituacaoHomologarCertificado();

$sql = "SELECT
            ma.idmatricula,
            ma.idpessoa,
            ma.idsituacao,
            ma.idsindicato,
            ma.idcurso,
            oc.porcentagem_minima_disciplinas,
            cs.homologar_certificado
        FROM matriculas ma
        INNER JOIN cursos c ON (ma.idcurso = c.idcurso)
        INNER JOIN sindicatos i ON (i.idsindicato = ma.idsindicato)
        INNER JOIN cursos_sindicatos cs ON (i.idsindicato = cs.idsindicato and c.idcurso = cs.idcurso)

        -- verificaMatriculaAprovadaNotasDias
        INNER JOIN (
            SELECT idmatricula, para, MIN(data_cad) as data_conclusao
            FROM matriculas_historicos
            GROUP BY idmatricula, para
        )  mh ON (ma.idmatricula = mh.idmatricula AND mh.para = ".$situacaoAtiva['idsituacao'].")
        INNER JOIN ofertas_cursos oc ON (ma.idoferta = oc.idoferta AND c.idcurso = oc.idcurso)
        WHERE
            ma.idsituacao = ".$situacaoAtiva['idsituacao']." AND
            ma.ativo = 'S' AND
            NOW() >= DATE_ADD(mh.data_conclusao, INTERVAL oc.gerar_quantidade_dias DAY) AND
            ma.liberacao_temporaria_datavalid = 'N'
        ORDER BY mh.data_conclusao, oc.gerar_quantidade_dias
        ";
$query = $matriculaObj->executaSql($sql);
$html = '
<table class="table">
    <thead class="thead-light">
        <tr>
            <th colspan="5" style="text-align: center;">Relação de matriculas alteradas</th>
        </tr>
        <tr>
            <th>ID Matricula</th>
            <th>Aluno</th>
            <th>Curso</th>
            <th>Sindicato</th>
            <th>CFC</th>
        </tr>
    </thead>
<tbody>';
while ($linha = mysql_fetch_assoc($query)) {
    $matriculaObj->set('id', $linha['idmatricula'])->set('idpessoa', $linha['idpessoa']);
    $matricula = $matriculaObj->retornar();
    $aluno = $matriculaObj->retornarPessoa();
    $sindicato = $matriculaObj->RetornarSindicato();
    $curso = $matriculaObj->RetornarCurso();
    $cfc = $matriculaObj->RetornarEscola();
    $alunoAprovadoNotas = $matriculaObj->verificaMatriculaAprovadaNotas($linha['porcentagem_minima_disciplinas']);
    $porcentagemCursoAtual = $matriculaObj->porcentagemCursoAtual((int) $linha['idmatricula']);
    $documentosObrigatoriosPendentes = $matriculaObj->retornarDocumentosPendentes($linha['idmatricula'], $linha['idsindicato'], $linha['idcurso'], false);

    if($alunoAprovadoNotas && empty($documentosObrigatoriosPendentes) && $porcentagemCursoAtual == 100){
        $novaSituacao = ($linha['homologar_certificado'] == 'S') ? $situacaoHomologarCertificado : $situacaoConcluido;
        $matriculaObj->alterarSituacao($linha['idsituacao'], $novaSituacao['idsituacao']);
        $html .= '<tr>';
        $html .= "<td>{$matricula['idmatricula']}</td>";
        $html .= "<td>{$aluno['nome']}</td>";
        $html .= "<td>{$curso['nome']}</td>";
        $html .= "<td>{$sindicato['nome_abreviado']}</td>";
        $html .= "<td>{$cfc['nome_fantasia']}</td>";
        $html .= '</tr>';
    }
}
$html .= '</tbody></table>';
$nomeDe = $GLOBALS["config"]["tituloEmpresa"];
$emailDe = $GLOBALS["config"]["emailSistema"];
foreach($usuarios as $usuario){
    if($usuario['recebe_email_homologacao'] == 'S') {
        $matriculaObj->enviarEmail($nomeDe, $emailDe, 'Matrículas alteradas para Homologar certificado/Curso Concluído', $html, $usuario['nome'], $usuario['email'], $layout = "layout", $charset = 'iso-8859-1');
    }
}
