<?php
// Associa folha para matrículas sem folha associada (Usa regra de homologar certificado)

set_time_limit(0);
$matriculaObj = new Matriculas();
$folhaObj = new Folhas_Registros_Diplomas();
$situacaoAtiva = $matriculaObj->retornarSituacaoAtiva();
$situacaoConcluido = $matriculaObj->retornarSituacaoConcluido();


$sql = "SELECT
	ma.idmatricula,
	oc.idfolha
FROM
	matriculas ma
	INNER JOIN cursos c ON ( ma.idcurso = c.idcurso )
	INNER JOIN sindicatos i ON ( i.idsindicato = ma.idsindicato )
	INNER JOIN cursos_sindicatos cs ON ( i.idsindicato = cs.idsindicato AND c.idcurso = cs.idcurso ) -- verificaMatriculaAprovadaNotasDias
	INNER JOIN ( SELECT idmatricula, para, MIN( data_cad ) AS data_conclusao FROM matriculas_historicos GROUP BY idmatricula, para ) mh ON ( ma.idmatricula = mh.idmatricula AND mh.para = ".$situacaoAtiva['idsituacao']." )
	INNER JOIN ofertas_cursos oc ON ( ma.idoferta = oc.idoferta AND c.idcurso = oc.idcurso )
	LEFT JOIN folhas_registros_diplomas_matriculas f ON ( ma.idmatricula = f.idmatricula )
WHERE
	ma.idsituacao IN (".$situacaoConcluido['idsituacao'].")
	AND	f.idmatricula IS NULL
	AND ma.ativo = 'S'
	AND NOW() >= DATE_ADD( mh.data_conclusao, INTERVAL oc.gerar_quantidade_dias DAY )
    AND oc.idfolha IS NOT NULL
	AND oc.ativo = 'S'
ORDER BY
	mh.data_conclusao,
	oc.gerar_quantidade_dias
LIMIT 500;";

$matriculas = $matriculaObj->retornarLinhasArray($sql);

foreach ($matriculas as $matricula) {
    $folhaObj->associarDiploma($matricula['idfolha'], $matricula['idmatricula']);
}
