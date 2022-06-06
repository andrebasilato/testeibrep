<?php
$caminho = dirname(__DIR__) . '/../../';
define('INTERFACE_DETRAN_RJ_LIBERACAO', retornarInterface('detran_rj_liberacao')['id']);
require_once $caminho . '/app/includes/config.php';
$config['urlSistema'] = 'http://'. $config['url'];

require_once $caminho . '/app/includes/funcoes.php';
require_once $caminho . '/detran/includes/funcoes.php';
require_once $caminho . '/app/classes/PHPMailer/PHPMailerAutoload.php';
require_once $caminho . '/app/classes/core.class.php';
require_once $caminho . '/app/classes/matriculas.class.php';

$coreObj = new Core;
ini_set('soap.wsdl_cache_enabled', '0');
ini_set('soap.wsdl_cache_ttl',0);

$retornoConsulta = [
    '000' => 'Transação efetuada com sucesso',
    '001' => 'Domínio inválido',
    '005' => 'Biometria do curso de legislação inválida',
    '006' => 'Formulário Renach não encontrado',
    '007' => 'CPF obrigatório para EAD',
    '008' => 'CPF não corresponde ao RJ',
    '009' => 'EAD suspensa',
    '101' => 'Formulário em faixa diferente de em exames',
    '102' => 'RJ Transferido',
    '103' => 'RJ em Exigência',
    '105' => 'Idade inferior a permitida',
    '107' => 'Multa compatível para curso',
    '108' => 'CNH com Impedimento/Bloqueio',
    '112' => 'CNH vencida há mais de 30 dias',
    '113' => 'Condutor possui restrições médicas',
    '114' => 'Dados Incompatíveis',
    '115' => 'Código de Serviço Inválido',
    '116' => 'Requerimento 4 não previsto para o serviço',
    '117' => 'Categoria Pretendida Inválida (Auto)',
    '118' => 'Categoria Pretendida diferente da informada  (Moto)',
    '119' => 'Categoria Pretendida diferente da informada  (Auto)',
    '120' => 'Categoria Pretendida inválida (Moto)',
    '121' => 'Tipo chave Inválido',
    '122' => 'Registro não encontrado',
    '123' => 'Formulário com exame de LEG inapto',
    '124' => 'Requerimento deve ser 1ª  Hab ou Rehab',
    '125' => 'Req. Estrangeiro de país sem tratado AUTO',
    '126' => 'Req. inválido para curso de AUTO',
    '127' => 'Categoria Pretendida diferente de B e AB',
    '128' => 'Categoria Pretendida diferente de A e XB (Auto)',
    '129' => 'Categoria Pretendida diferente de AB (Auto)',
    '130' => 'Categoria Pretendida diferente de C e D',
    '131' => 'Categoria Pretendida diferente de D e E',
    '132' => 'Categoria Pretendida diferente de E',
    '133' => 'Categoria Pretendida diferente de XC e XD',
    '134' => 'Categoria Pretendida diferente de XD e XE',
    '135' => 'Categoria Pretendida diferente de XE',
    '136' => 'Categoria Pretendida diferente de AC e AD',
    '137' => 'Categoria Pretendida diferente de AD e AE',
    '138' => 'Categoria Pretendida diferente de AE',
    '139' => 'Categoria Pretendida Incompatível (Auto)',
    '140' => 'Req. Estrangeiro de país sem tratado MOTO',
    '141' => 'Req. inválido para curso de MOTO',
    '142' => 'Categoria Pretendida diferente de X, A, AB e XB',
    '143' => 'Categoria Pretendida diferente de A e XB (Moto)',
    '144' => 'Categoria Pretendida diferente de AB (Moto)',
    '145' => 'Categoria Pretendida diferente de XB e AB',
    '146' => 'Categoria Pretendida diferente de XC e AC',
    '147' => 'Categoria Pretendida diferente de XD e AD',
    '148' => 'Categoria Pretendida diferente de XE e AE',
    '149' => 'Categoria Pretendida Incompatível (Moto)',
    '150' => 'Formulário com exame de LEG inapto na atualização',
    '151' => 'Não necessita prova de atualização',
    '152' => 'Reprovado LEG com idade inferior a 60 anos',
    '153' => 'Candidato não é alvo de CRCI',
    '154' => 'Categoria Incompatível',
    '155' => 'Já possui curso',
    '156' => 'Categoria Permitida diferente da informada',
    '188' => 'Data de inclusão do Req.>12 meses',
    '198' => 'Serviço não é de CRCI',
    '199' => 'Condutor não exerce Atividade Remunerada',
    '201' => 'Pendência etapa – Médico',
    '202' => 'Pendência etapa – Psicológico',
    '203' => 'Pendência etapa – Legislação',
    '206' => 'Pendência etapa – Atualização',
    '300' => 'Formulário RENACH fora no layout (UF<>RJ)',
    '301' => 'CAER fora do layout (UF<>RJ)',
    '302' => 'Data do serviço fora do layout DDMMYYYY',
    '500' => 'Requerimento incompatível',
    '501' => 'Data anterior a de corte',
    '503' => 'Não está matriculado em nenhum curso',
    '504' => 'Não está matriculado no LEG',
    '506' => 'Candidato já possui carga horária AUTO',
    '507' => 'Candidato já possui carga horária MOTO',
    '508' => 'Candidato já possui carga horária LEG',
    '509' => 'Candidato já possui carga horária LEG atualização',
    '510' => 'Candidato já possui carga horária CRCI rehab',
    '511' => 'Candidato já possui carga horária CRCI alt.dados',
    '554' => 'CAER Ausente',
    '555' => 'Serviço já registrado (630)',
    '556' => 'Candidato não matriculado em CRCI',
    '557' => 'Já matriculado em CRCI por esta CFC/EAD',
    '558' => 'Já matriculado em CRCI por outra CFC/EAD',
    '559' => 'Candidato apto para ser matriculado em CRCI',
    '733' => 'PGU não encontrado',
    '802' => 'Formulário Inválido',
];

$codTransacao = 20; //Consulta Processo do aluno - TR 630

$matriculaObj = new Matriculas();
$siglaEstado = 'RJ';

$numeroSequencial = mysql_fetch_assoc($matriculaObj->executaSql('SELECT (COUNT(*)+1) as proximo FROM detran_logs'));

$parteFixa = [
    'sequencial' => str_pad($numeroSequencial['proximo'], 6, '0', STR_PAD_LEFT),
    'cod-transacao' => '630',
    'modalidade' => '4',
    'cliente' => str_pad($config['detran'][$siglaEstado]['pUsuario'], 11),
    'uf-transa' => 'RJ',
    'uf-origem' => 'RJ',
    'uf-destino' => 'RJ',
    'tipo' => '0',
    'tamanho' => '0045',
    'retorno' => '00',
    'juliano' => str_pad(date('z')+1, 3, '0', STR_PAD_LEFT),
];

$sql = 'SELECT
        m.idmatricula, m.renach, p.documento, p.categoria, e.detran_codigo, c.idcurso,
        (
            SELECT mh.acao FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
            mh.tipo = "detran_situacao" ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS acao_historico,
        (
            SELECT mh.data_cad FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
            mh.tipo = "detran_situacao" ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS data_ultimo_historico
    FROM
        matriculas m
        INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
        INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
        INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
        INNER JOIN escolas e ON (e.idescola = m.idescola)
        INNER JOIN cursos c ON (c.idcurso = m.idcurso)
    WHERE
        c.idcurso IN (' . implode(',', array_keys($detran_tipo_aula[$siglaEstado])) . ')
        AND p.categoria IS NOT NULL
        AND e.idestado = ' . $estadosDetran[$siglaEstado] . '
        AND m.ativo = "S"
        AND m.detran_situacao = "AL"
        AND cw.fim = "N"
        AND cw.inativa = "N"
        AND cw.cancelada = "N"
        AND m.renach IS NOT NULL
    ORDER BY data_ultimo_historico ASC
    limit 10';


$query = $matriculaObj->executaSql($sql);
while ($linha = mysql_fetch_assoc($query)) {
    try {
        $parteVariavel = [
            'cpf' => str_pad($linha['documento'], 11),
            'tipo-chave' => '1',
            'numero-chave' => str_pad($linha['renach'], 11),
            'codigo-servico' => str_pad($codTransacao, 3, 0, STR_PAD_LEFT),
            'data-servico' => date('dmY'),
            'caer' => $config['detran'][$siglaEstado]['caer'],
            'categoria-veiculo' => str_pad($linha['categoria'], 4),
        ];

        $string_tripa = str_pad(implode('',$parteVariavel), 3237);
        for($i=0; $i<13; $i++){
            $indice = $i * 249;
            $variavel[$i] = substr($string_tripa, $indice, 249);
        }

        $dadosSOAP = [
            'GTRN0003' => [
                'PARTE-FIXA' => implode('', $parteFixa),
                'PARTE-VARIAVEL' => $variavel,
            ]
        ];
        $transacoes->iniciaTransacao(INTERFACE_DETRAN_RJ_LIBERACAO, 'E', $dadosSOAP);
        $conexaoSOAP = new SoapClient($config['detran'][$siglaEstado]['urlWsl']);
        $auth = array(
            'exx-natural-security'=>'TRUE',
            'exx-natural-library'=>'LIBBRK',
            'exx-rpc-userID'=>$config['detran'][$siglaEstado]['pUsuario'],
            'exx-rpc-password'=>$config['detran'][$siglaEstado]['pSenha'],
        );

        $authvar = new SoapVar($auth, SOAP_ENC_OBJECT);
        $header = new SoapHeader('urn:com.softwareag.entirex.xml.rt', 'EntireX', $authvar);

        $conexaoSOAP->__setSoapHeaders($header);

        $options = [];
        $respostaSoap = $conexaoSOAP->__soapCall('GTRN0003', $dadosSOAP, $options);
        $retorno = json_decode(json_encode($respostaSoap), true);


        if (is_array($retorno['PARTE-VARIAVEL']['string'])) {
            $codigoRetorno = substr($retorno['PARTE-VARIAVEL']['string'][0], 0, 3);
        } else {
            $codigoRetorno = substr($retorno['PARTE-VARIAVEL']['string'], 0, 3);
        }
        $retorno = json_encode(['codigo' => $codigoRetorno, 'mensagem' => $retornoConsulta[$codigoRetorno]]);
        $stringEnvio = trim(implode('', $parteFixa) . ' | ' . implode('', $variavel));

        $matriculaObj->executaSql('BEGIN');
        if (in_array($codigoRetorno, ['000', '557', '558', '559'])) {
            $sql = 'UPDATE matriculas SET detran_situacao = "LI", data_inicio_curso = NOW() WHERE idmatricula = ' . $linha['idmatricula'];
            $matriculaObj->executaSql($sql);

            $matriculaObj->set('id', $linha['idmatricula'])
                ->adicionarHistorico(null, 'detran_situacao', 'modificou', 'AL', 'LI', null);
            $transacoes->finalizaTransacao(null, 2, $retorno, null);
        } else {
            $sql = 'UPDATE matriculas SET detran_situacao = "NL" WHERE idmatricula = ' . $linha['idmatricula'];
            $matriculaObj->executaSql($sql);

            $matriculaObj->set('id', $linha['idmatricula'])
                ->adicionarHistorico(null, 'detran_situacao', 'modificou', 'AL', 'NL', null);
            $transacoes->finalizaTransacao(null, 5, $retorno, null);
        }

        salvarLogDetran(
            $matriculaObj,
            $codTransacao,
            $linha['idmatricula'],
            $retorno,
            $stringEnvio
        );

        $matriculaObj->executaSql('COMMIT');
    } catch (Exception $excecao) {
        $transacoes->finalizaTransacao(null, 5, json_encode(['codigo' => $excecao->getCode(), 'mensagem' => $excecao->getMessage()]), null);

        if ($linha['acao_historico'] != 'detran_nao_respondeu') {
            $matriculaObj->set('id', $linha['idmatricula'])
                ->adicionarHistorico(null, 'detran_situacao', 'detran_nao_respondeu', null, null, null);
        }
    }
}
