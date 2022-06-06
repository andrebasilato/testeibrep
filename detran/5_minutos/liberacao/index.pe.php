<?php
define('INTERFACE_DETRAN_PE_LIBERACAO', retornarInterface('detran_pe_liberacao')['id']);
require_once $caminho . '/app/classes/matriculas.class.php';
$siglaEstado = 'PE';
$codTransacao = 424;//Envio de créditos de aula
$valorResultado = "CursoDistanciaIniciarResult";

$matriculaObj = new Matriculas();
$situacaoEmCurso = $matriculaObj->retornarSituacaoAtiva();

$sql = 'SELECT
        m.idmatricula, m.renach, p.documento,  p.data_nasc, e.detran_codigo, o.idoferta, c.idcurso, e.idescola,
        (
            SELECT
                COUNT(d.iddisciplina)
            FROM
                ofertas_cursos_escolas oce
                INNER JOIN ofertas_curriculos_avas oca ON (oca.idoferta = oce.idoferta AND oca.idcurriculo = oce.idcurriculo AND oca.ativo = "S" AND oca.idava IS NOT NULL)
                INNER JOIN curriculos_blocos cb ON (cb.idcurriculo = oca.idcurriculo AND cb.ativo = "S")
                INNER JOIN curriculos_blocos_disciplinas cbd ON (cbd.idbloco = cb.idbloco AND cbd.ativo = "S" )
                INNER JOIN disciplinas d ON (d.iddisciplina = cbd.iddisciplina AND d.iddisciplina = oca.iddisciplina AND d.ativo = "S")
            WHERE
                oce.idoferta = o.idoferta AND
                oce.idcurso = c.idcurso AND
                oce.idescola = e.idescola AND
                d.iddisciplina IN (' . implode(',', array_keys($detran_codigo_materia[$siglaEstado])) . ')
        ) AS total_disciplinas,
        (
            SELECT mh.acao FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
            mh.tipo = "detran_creditos" ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS acao_historico,
        (
            SELECT mh.data_cad FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
            mh.tipo = "detran_creditos" ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS data_ultimo_historico,
        (
            SELECT mh.data_cad FROM matriculas_historicos mh
            WHERE mh.idmatricula = m.idmatricula AND mh.tipo = "situacao" AND mh.para = ' . $situacaoEmCurso['idsituacao'] . '
            ORDER BY mh.data_cad ASC LIMIT 1
        ) AS data_inicio_curso
    FROM
        matriculas m
        INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
        INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
        INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
        INNER JOIN escolas e ON (e.idescola = m.idescola)
        INNER JOIN cursos c ON (c.idcurso = m.idcurso)
    WHERE
        c.idcurso IN (' . implode(',', array_keys($detran_tipo_aula[$siglaEstado])) . ') AND
        e.idestado = ' . $estadosDetran[$siglaEstado] . ' AND
        m.ativo = "S" AND
        m.detran_situacao = "AL" AND
        m.detran_creditos = "N" AND
        e.detran_codigo IS NOT NULL AND
        cw.ativa = "S" AND
        (
            SELECT mh.data_cad FROM matriculas_historicos mh
            INNER JOIN matriculas_workflow mw ON (mw.idsituacao = mh.para)
            WHERE mh.idmatricula = m.idmatricula AND mh.tipo = "situacao" AND mw.ativa = "S"
            ORDER BY mh.data_cad ASC LIMIT 1
        ) IS NOT NULL
    ORDER BY data_ultimo_historico ASC
    limit 10
';
$query = $matriculaObj->executaSql($sql);
while ($linha = mysql_fetch_assoc($query)) {
    try {
        if (!empty($linha['renach']) && substr($linha['renach'], 0, 2) != $siglaEstado)
            $detran->ImportacaoPE($linha);

        $arrayEnvio = [
            'Cpf' => $linha['documento'],
            'Nascimento' => $linha['data_nasc'],
            'Curso' => $detran_tipo_aula[$siglaEstado][$linha['idcurso']]['codigo'],
            'Modulo' => $detran_tipo_aula[$siglaEstado][$linha['idcurso']]['modulo'],
            'Cnpj' => $config['detran'][$siglaEstado]['registro_empresa'],
            'CpfInstrutor' => $config['detran'][$siglaEstado]['registro_instrutor'],
            'Inicio' => (new \DateTime($linha['data_inicio_curso']))->format('Y-m-d'),
            'InicioModulo' => (new \DateTime($linha['data_inicio_curso']))->format('Y-m-d'),
        ];
        $_POST = json_encode($arrayEnvio);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $config['detran'][$siglaEstado]['urlJSON'] . '/CursoDistanciaIniciar');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application\json',
            )
        );

        $transacoes->iniciaTransacao(INTERFACE_DETRAN_PE_LIBERACAO, 'E', $_POST);
        $chResult = curl_exec($ch);
        curl_close($ch);

        if ($chResult === false) {
            if ($linha['acao_historico'] != 'detran_nao_respondeu') {
                $matriculaObj->set('id', $linha['idmatricula'])
                    ->adicionarHistorico(null, 'detran_situacao', 'detran_nao_respondeu', null, null, null);
            }
            return;
        }
        $retorno = json_decode(json_decode($chResult, true)[$valorResultado], true);
        $matriculaObj->executaSql('BEGIN');
        $mensagem = json_encode(['codigo' => $retorno[0]['nErro'], 'mensagem' => $retorno[0]['sMsg']]);

        switch ($retorno[0]['nErro']) {
            case 0:
                $sql = 'UPDATE matriculas SET detran_situacao = "LI", data_inicio_curso = NOW() WHERE idmatricula = ' . $linha['idmatricula'];
                $matriculaObj->executaSql($sql);

                $matriculaObj->set('id', $linha['idmatricula'])
                    ->adicionarHistorico(null, 'detran_situacao', 'modificou', 'AL', 'LI', null);
                $transacoes->set("json", $retorno);
                $transacoes->finalizaTransacao(null, 2);
                $transacoes->set("json", null);
                break;
            case 1:
            default:
                $sql = 'UPDATE matriculas SET detran_situacao = "NL" WHERE idmatricula = ' . $linha['idmatricula'];
                $matriculaObj->executaSql($sql);

                $matriculaObj->set('id', $linha['idmatricula'])
                    ->adicionarHistorico(null, 'detran_situacao', 'modificou', 'AL', 'NL', null);
                $transacoes->set("json", $mensagem);
                $transacoes->finalizaTransacao(null, 5);
                $transacoes->set("json", null);
                break;
        }

        salvarLogDetran($matriculaObj, $codTransacao, $linha['idmatricula'], $mensagem, $_POST);
        $matriculaObj->executaSql('COMMIT');
    } catch (Exception $e){
        $transacoes->finalizaTransacao(null, 3, json_encode(['codigo' => $e->getCode(), 'mensagem' => $e->getMessage()]));
        echo $e->getMessage();
    }

}
