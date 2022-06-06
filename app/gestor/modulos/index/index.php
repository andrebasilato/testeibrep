<?php
include("../classes/areas.class.php");
include("../classes/cursos.class.php");
include("../classes/ofertas.class.php");
include("../classes/matriculas.class.php");
include("../classes/usuarios.class.php");
include("../classes/disciplinas.class.php");
include("../classes/professores.class.php");
include("../classes/pessoas.class.php");
include("../classes/atendimentos.class.php");
include("../classes/sindicatos.class.php");
include("../classes/reconhecimento.class.php");

$linhaObjInst = new Sindicatos();
$sindicatos = $linhaObjInst->retornarSindicatosUsuario($usuario["idusuario"], true);

$idsindicato = false;
$idregiao = false;
if ($_GET['i']) {
    $separaGet = explode('|', $_GET['i']);
    if ($separaGet[0] == 'i')
        $idsindicato = intval($separaGet[1]);
    else if ($separaGet[0] == 'r')
        $idregiao = intval($separaGet[1]);
}

$linhaObjCurso = new Cursos();
$cursosUsuario = $linhaObjCurso->retornarCursosUsuario($usuario["idusuario"]);
/*$cursos = $linhaObjCurso->listarTotalCursos($idsindicato, intval($_GET['c']));*/

$linhaObjOferta = new Ofertas();
$ofertas = $linhaObjOferta->listarTotalOfertas();

$linhaObjMatricula = new Matriculas();
$matriculas = $linhaObjMatricula->listarTotalMatriculas($idsindicato, intval($_GET['c']), $idregiao);
require_once '../classes/detran.class.php';
$detanObj = new Detran();
$estadosDetran = $detanObj->listarEstadosIntegrados();
$where = '';
foreach($estadosDetran as $siglaEstado => $idEstado) {
    $cursos = implode(', ', array_keys($GLOBALS['detran_tipo_aula'][$siglaEstado]));
    if ($detanObj->obterSituacaoIntegracao($idEstado)) {
        if ($where != '')
            $where .= 'OR ';
        $where .= "(
                m.ativo = 'S' and
                mw.fim = 'S' and
                mw.cancelada = 'N' and
                mw.inativa = 'N' and
                m.detran_certificado = 'N' and
                mw.inicio = 'N' and
                e.idestado = {$idEstado} and
                m.idcurso in ($cursos)
        ) ";
    }
}
$sql = "select Count(0) as total
        from matriculas m
            inner join matriculas_workflow mw on mw.idsituacao = m.idsituacao and mw.ativo = 'S'
            LEFT JOIN escolas e ON (e.idescola = m.idescola)";
if($where != '')
    $sql .= " where " . $where;
else
    $sql .= " where e.idestado = 0";
$certificadosNaoEnviados = $linhaObjMatricula->retornarLinha($sql);

$linhaObjMatricula->Set("campos", "m.idmatricula as idmatricula1");
$linhaObjMatricula->Set("groupby", "m.idmatricula");
$linhaObjMatricula->Set(
    'where',
    "cw.fim = 'N' and cw.cancelada = 'N' and cw.inativa = 'N' and cw.inicio = 'N'"
);
$linhaObjMatricula->Set("limite", -1);
$linhaObjMatricula->Set("incluirWorkflow", true);
$linhaObjMatricula->Set("incluirEscolas", true);
$dadosArray = $linhaObjMatricula->ListarTodas();

$reconhecimentoObj = new Reconhecimento();
$dadosBiometricos = $reconhecimentoObj->ListarFalhas();
$matriculas_biometria['DATAVALID'] = (int) array_count_values(array_column($dadosBiometricos, 'tipo_biometria'))['DATAVALID'];
$matriculas_biometria['AZURE'] = (int) array_count_values(array_column($dadosBiometricos, 'tipo_biometria'))['AZURE'];

$linhaObjPessoa = new Pessoas();
$pessoas = $linhaObjPessoa->listarTotalPessoas($idsindicato, intval($_GET['c']), $idregiao);

/*$linhaObjProfessor = new Professores();
$professores = $linhaObjProfessor->listarTotalProfessores();*/

/*$linhaObjAtendimento = new Atendimentos();
$atendimentos = $linhaObjAtendimento->listarTotalAtendimentos();*/
$arrayMatriculas20 = $linhaObjMatricula->totalMatriculas20Dias($idsindicato, intval($_GET['c']), $idregiao);

$datas = array();
$totais = array();

if ($config["tela_padrao"] == "mobile") {
    $dias = 6;
} else {
    $dias = 19;
}

$cont_total = 0;
for ($i = 0; $i <= $dias; $i++) {
    $data = date("d/m/Y", mktime(0, 0, 0, date("m"), (date("d") - $dias) + $i, date("Y")));
    $datas[] = "'" . $data . "'";
    $cont_total += $arrayMatriculas20[$data];
    $totais_geral[] = $cont_total;
    if (empty($arrayMatriculas20[$data])) {
        $totais[] = 0;
    } else {
        $totais[] = $arrayMatriculas20[$data];
    }
}
$datas_matriculas = join(", ", $datas);
$totais_matriculas = join(", ", $totais);
$grafico_totais_matriculas = join(", ", $totais_geral);

include("idiomas/" . $config["idioma_padrao"] . "/index.php");
include("telas/" . $config["tela_padrao"] . "/index.php");
?>
