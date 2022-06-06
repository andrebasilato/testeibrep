<?php

define('INTERFACE_DETRAN_SC_LIBERACAO', retornarInterface('detran_sc_liberacao')['id']);
require_once $caminho . '/app/classes/matriculas.class.php';
$siglaEstado = 'SC';
$codTransacao = 431; //Consulta Processo do aluno

$matriculaObj = new Matriculas();
$situacaoEmCurso = $matriculaObj->retornarSituacaoAtiva();

$sql = 'SELECT
        m.idmatricula, p.documento, p.cnh, c.idcurso,     
        (
            SELECT mh.acao FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
            mh.tipo = "detran_situacao" ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS acao_historico,
        (
            SELECT mh.data_cad FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
            mh.tipo = "detran_situacao" ORDER BY mh.idhistorico DESC LIMIT 1
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
        INNER JOIN escolas e ON (e.idescola = m.idescola)
        INNER JOIN cursos c ON (c.idcurso = m.idcurso)
    WHERE
        c.idcurso IN (' . implode(',', array_keys($detran_tipo_aula[$siglaEstado])) . ') 
        AND e.idestado = ' . $estadosDetran[$siglaEstado] . ' 
        AND m.ativo = "S" 
        AND m.detran_situacao = "AL" 
        AND m.detran_creditos = "N"         
        AND cw.ativa = "S" 
        AND (
            SELECT mh.data_cad FROM matriculas_historicos mh
            INNER JOIN matriculas_workflow mw ON (mw.idsituacao = mh.para)
            WHERE mh.idmatricula = m.idmatricula AND mh.tipo = "situacao" AND mw.ativa = "S"
            ORDER BY mh.data_cad ASC LIMIT 1
        ) IS NOT NULL
    ORDER BY data_ultimo_historico ASC
    limit 10;
';
$query = $matriculaObj->executaSql($sql);

while ($linha = mysql_fetch_assoc($query)) {

    try {
        $arrayEnvio = [

            'cnpjEntidade' => $config['detran'][$siglaEstado]['cnpjInst'],
            'codigoCurso' =>  str_pad($detran_tipo_aula[$siglaEstado][$linha['idcurso']] , 2 , '0' , STR_PAD_LEFT),
            'cpfCondutor' => $linha['documento'],
            'numeroRegistroStr' => $linha['cnh'],

        ];
        $_POST = json_encode($arrayEnvio);

         if($detran_tipo_aula[$siglaEstado][$linha['idcurso']] == 20){
             $endpoint = '/educacao/ead/consulta-matricula-curso-reciclagem';
         } else {
             $endpoint = '/educacao/ead/consulta-matricula-curso-especializado';
         }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $config['detran'][$siglaEstado]['urlJSON'] . $endpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $config['detran'][$siglaEstado]['token']
            )
        );

        $transacoes->iniciaTransacao(INTERFACE_DETRAN_SC_LIBERACAO, 'E', $_POST);
        $chResult = curl_exec($ch);

        $codigoHTTP = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($chResult === false) {
            if ($linha['acao_historico'] != 'detran_nao_respondeu') {
                $matriculaObj->set('id', $linha['idmatricula'])
                    ->adicionarHistorico(null, 'detran_situacao', 'detran_nao_respondeu', null, null, null);
            }
            return;
        }
        $retorno = json_decode($chResult);
        $matriculaObj->executaSql('BEGIN');
        $mensagem = $retorno[0]->mensagemUsuario;

        if($codigoHTTP == 200){ // Caso diferente, será por conta de erro ou indisponibilidade

            switch ($mensagem) {
                case "Condutor liberado para matricula.":

                    $sql = 'UPDATE matriculas SET detran_situacao = "LI" WHERE idmatricula = ' . $linha['idmatricula'];
                    $matriculaObj->executaSql($sql);

                    $matriculaObj->set('id', $linha['idmatricula'])
                        ->adicionarHistorico(null, 'detran_situacao', 'modificou', 'AL', 'LI', null);
                    $transacoes->set("json", $chResult);
                    $transacoes->finalizaTransacao(null, 2);
                    $transacoes->set("json", null);
                    break;
                default:
                    $sql = 'UPDATE matriculas SET detran_situacao = "NL" WHERE idmatricula = ' . $linha['idmatricula'];
                    $matriculaObj->executaSql($sql);

                    $matriculaObj->set('id', $linha['idmatricula'])
                        ->adicionarHistorico(null, 'detran_situacao', 'modificou', 'AL', 'NL', null);
                    $transacoes->set("json", $chResult);
                    $transacoes->finalizaTransacao(null, 5);
                    $transacoes->set("json", null);
                    break;
            }
        }

        salvarLogDetran($matriculaObj, $codTransacao, $linha['idmatricula'], $mensagem, $_POST);
        $matriculaObj->executaSql('COMMIT');
    } catch (Exception $e){
        $transacoes->finalizaTransacao(null, 3, json_encode(['codigo' => $e->getCode(), 'mensagem' => $e->getMessage()]));
        echo $e->getMessage();
    }

}
