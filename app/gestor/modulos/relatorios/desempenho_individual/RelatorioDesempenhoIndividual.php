<?php

require_once DIR_APP . "/classes/matriculas_novo.class.php";
class RelatorioDesempenhoIndividual extends Core
{
    const TABELA = "matriculas";

    public function gerarRelatorio($campos)
    {
        $this->sql = "SELECT
                {$campos}
            FROM
                " . self::TABELA . " m
            INNER JOIN pessoas p ON p.idpessoa = m.idpessoa
            INNER JOIN cursos c on c.idcurso = m.idcurso
            INNER JOIN escolas esco on m.idescola = esco.idescola
            INNER JOIN ofertas_cursos_escolas oce ON (m.idoferta = oce.idoferta)
            INNER JOIN estados esc ON (esco.idestado = esc.idestado)
            WHERE
                m.ativo is not null
                AND oce.idoferta = m.idoferta
                AND oce.idcurso = m.idcurso
                AND oce.idescola = m.idescola
        ";

        $this->aplicarFiltrosBasicos();
        $retorno = $this->retornarLinha($this->sql);

        $retorno['avas'] = $this->retornarAvasMatricula(
            $_GET['q']['1|m.idmatricula'],
            $retorno
        );

        return $retorno;
    }

    private function retornarAvasMatricula($idMatricula, $dadosPessoa)
    {
        $idMatricula = intval($idMatricula);
        $idPessoa = intval($dadosPessoa['idpessoa']);
        $matriculasObj = new Matriculas;

        $matriculasObj->id = $idMatricula;
        $matriculasObj->idpessoa = $idPessoa;
        $matriculasObj->matricula = [
            "idoferta" => $dadosPessoa['idoferta'],
            "idcurso" => $dadosPessoa['idcurso'],
            "idescola" => $dadosPessoa['idescola']
        ];

        $dados = $matriculasObj->retornarAvas();

        foreach ($dados as $indexDados => $dado) {
            $sql = "SELECT
                    max( inicio ) AS data_avaliacao,
                    max( nota ) AS nota
                FROM
                    matriculas_avaliacoes
                WHERE
                    idavaliacao IN (SELECT
                            idavaliacao
                        FROM
                            avas_avaliacoes
                        WHERE
                            idava = {$dado['idava']}
                            AND ativo = 'S'
                            AND exibir_ava = 'S'
                    )
                    AND idmatricula = {$idMatricula}
                    AND ativo = 'S'
                ORDER BY
                    idprova DESC
                    LIMIT 1
            ";

            $dadosNota = $this->retornarLinha($sql);

            $dados[$indexDados]['dados_nota'] = $dadosNota;
        }

        return $dados;
    }
}
