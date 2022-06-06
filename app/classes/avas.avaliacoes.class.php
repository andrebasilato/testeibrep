<?php
class Avaliacoes extends Ava {

    var $idava = null;

    function ListarTodasAvaliacao() {
        $this->sql = "select
                    ".$this->campos."
                  from
                    avas_avaliacoes aa
                    inner join avas a on (aa.idava = a.idava)
                  where
                    aa.ativo = 'S' and
                    a.idava = ".$this->idava;

        $this->aplicarFiltrosBasicos();
        $this->set('groupby', 'aa.idavaliacao');
        return $this->retornarLinhas();
    }

    function RetornarAvaliacao() {
        $this->sql = "SELECT
                    ".$this->campos."
                  FROM
                    avas_avaliacoes aa
                    INNER JOIN avas a ON aa.idava = a.idava
                  WHERE
                    aa.ativo = 'S' AND
                    aa.idavaliacao = '".$this->id."' AND
                    a.idava = ".$this->idava;
        return $this->retornarLinha($this->sql);
    }

    public function retornarDisciplinasPerguntas() {
        $this->sql = "SELECT
                    ".$this->campos."
                  FROM
                    avas_avaliacoes_disciplinas avd
                  WHERE
                    avd.ativo = 'S' AND
                    avd.idavaliacao = '".$this->id."'";
        return $this->retornarLinhas();
    }

    function CadastrarAvaliacao() {

        if (! count($this->post['iddisciplina_perguntas'])) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_disciplinas_perguntas_vazio';
            return $this->retorno;
        }

        $disciplinas = implode(',', $this->post['iddisciplina_perguntas']);

        $this->sql = "SELECT
                        count(idpergunta) as total
                    FROM
                        perguntas
                    WHERE
                        ativo = 'S' AND
                        ativo_painel = 'S' AND
                        iddisciplina IN(".$disciplinas.") AND
                        avaliacao_virtual = 'S' and tipo = 'O' AND
                        dificuldade = 'F' ";
        $objetivas_faceis = $this->retornarLinha($this->sql);

        if($objetivas_faceis['total'] < $this->post["objetivas_faceis"]){
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_objetivas_faceis_insuficientes';
        }

        $this->sql = "SELECT
                        count(idpergunta) AS total
                FROM
                    perguntas
                WHERE
                    ativo = 'S' AND
                    ativo_painel = 'S' AND
                    iddisciplina IN(".$disciplinas.") AND
                    avaliacao_virtual = 'S' AND
                    tipo = 'O' AND
                    dificuldade = 'M' ";
        $objetivas_medias = $this->retornarLinha($this->sql);

        if($objetivas_medias['total'] < $this->post["objetivas_intermediarias"]){
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_objetivas_medias_insuficientes';
        }

        $this->sql = "SELECT
                        count(idpergunta) AS total
                    FROM
                        perguntas
                    WHERE
                        ativo = 'S' AND
                        ativo_painel = 'S' AND
                        iddisciplina IN(".$disciplinas.") AND
                        avaliacao_virtual = 'S' AND
                        tipo = 'O' AND
                        dificuldade = 'D' ";
        $objetivas_dificeis = $this->retornarLinha($this->sql);

        if($objetivas_dificeis['total'] < $this->post["objetivas_dificeis"]){
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_objetivas_dificeis_insuficientes';
        }

        if ($this->post["avaliador"] == 'professor'){
            $this->sql = "SELECT
                        count(idpergunta) AS total
                    FROM
                        perguntas
                    WHERE
                        ativo = 'S' AND
                        ativo_painel = 'S' AND
                        iddisciplina IN(".$disciplinas.") AND
                        avaliacao_virtual = 'S' AND
                        tipo = 'S' AND
                        dificuldade = 'F' ";
            $subjetivas_faceis = $this->retornarLinha($this->sql);

            if($subjetivas_faceis['total'] < $this->post["subjetivas_faceis"]){
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = 'erro_subjetivas_faceis_insuficientes';
            }

            $this->sql = "SELECT
                        count(idpergunta) as total
                    FROM
                        perguntas
                    WHERE
                        ativo = 'S' AND
                        ativo_painel = 'S' AND
                        iddisciplina IN(".$disciplinas.") AND
                        avaliacao_virtual = 'S' AND
                        tipo = 'S' AND
                        dificuldade = 'M' ";
            $subjetivas_medias = $this->retornarLinha($this->sql);

            if($subjetivas_medias['total'] < $this->post["subjetivas_intermediarias"]){
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = 'erro_subjetivas_medias_insuficientes';
            }

            $this->sql = "SELECT
                            count(idpergunta) as total
                        FROM
                            perguntas
                        WHERE
                            ativo = 'S' AND
                            ativo_painel = 'S' AND
                            iddisciplina IN(".$disciplinas.") AND
                            avaliacao_virtual = 'S' AND
                            tipo = 'S' AND
                            dificuldade = 'D' ";
            $subjetivas_dificeis = $this->retornarLinha($this->sql);

            if($subjetivas_dificeis['total'] < $this->post["subjetivas_dificeis"]){
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = 'erro_subjetivas_dificeis_insuficientes';
            }
        }

        if ($this->post["avaliador"] == "sistema") {
            $campos_remover[] = 'periodo_correcao_dias';
            $campos_remover[] = 'subjetivas_faceis';
            $campos_remover[] = 'subjetivas_intermediarias';
            $campos_remover[] = 'subjetivas_dificeis';
            $this->config["formulario"] = $this->alterarConfigFormulario($this->config["formulario"],$campos_remover);
        }

        if ($this->retorno["erro"]) {
            return $this->retorno;
        }


        $salvar = $this->SalvarDados();
        if ($salvar['sucesso']) {
            foreach ($this->post['iddisciplina_perguntas'] as $iddisciplina) {

                $this->sql = "INSERT INTO
                    avas_avaliacoes_disciplinas
                SET
                    data_cad = NOW(),
                    ativo = 'S',
                    idavaliacao = ".(int)$salvar['id'].",
                    iddisciplina = ".(int)$iddisciplina;

                if ($this->executaSql($this->sql)) {

                    $this->monitora_oque = 1;
                    $this->monitora_onde = '178';
                    $this->monitora_qual = mysql_insert_id();
                    $this->Monitora();

                } else {

                    $retorno["erro"] = true;
                    $retorno["erros"][] = $this->sql;
                    $retorno["erros"][] = mysql_error();
                }

            }
        }

        if (!$retorno["erro"]) {
            $retorno["sucesso"] = true;
            $retorno["id"] = $salvar['id'];
        }
        return $retorno;
    }

    function ModificarAvaliacao() {

        if (count($this->post['iddisciplina_perguntas']) == 0) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_disciplinas_perguntas_vazio';
            return $this->retorno;
        }

        $disciplinas = implode(',', $this->post['iddisciplina_perguntas']);

        $this->sql = "SELECT
                    count(idpergunta) as total
                FROM
                    perguntas
                WHERE
                    ativo = 'S' AND
                    ativo_painel = 'S' AND
                    iddisciplina IN(".$disciplinas.") AND
                    avaliacao_virtual = 'S' AND
                    tipo = 'O' AND
                    dificuldade = 'F' ";
        $objetivas_faceis = $this->retornarLinha($this->sql);

        if($objetivas_faceis['total'] < $this->post["objetivas_faceis"]){
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_objetivas_faceis_insuficientes';
        }

        $this->sql = "SELECT
                    count(idpergunta) AS total
                FROM
                    perguntas
                WHERE
                    ativo = 'S' AND
                    ativo_painel = 'S' AND
                    iddisciplina IN(".$disciplinas.") AND
                    avaliacao_virtual = 'S' AND
                    tipo = 'O' AND
                    dificuldade = 'M' ";
        $objetivas_medias = $this->retornarLinha($this->sql);

        if($objetivas_medias['total'] < $this->post["objetivas_intermediarias"]){
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_objetivas_medias_insuficientes';
        }

        $this->sql = "SELECT
                    count(idpergunta) AS total
                FROM
                    perguntas
                WHERE
                    ativo = 'S' AND
                    ativo_painel = 'S' AND
                    iddisciplina IN(".$disciplinas.") AND
                    avaliacao_virtual = 'S' AND
                    tipo = 'O' AND
                    dificuldade = 'D' ";
        $objetivas_dificeis = $this->retornarLinha($this->sql);

        if($objetivas_dificeis['total'] < $this->post["objetivas_dificeis"]){
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_objetivas_dificeis_insuficientes';
        }

        if ($this->post["avaliador"] == 'professor'){
            $this->sql = "SELECT
                        count(idpergunta) as total
                    FROM
                        perguntas
                    WHERE
                        ativo = 'S' AND
                        ativo_painel = 'S' AND
                        iddisciplina IN(".$disciplinas.") AND
                        avaliacao_virtual = 'S' AND
                        tipo = 'S' AND
                        dificuldade = 'F' ";
            $subjetivas_faceis = $this->retornarLinha($this->sql);

            if($subjetivas_faceis['total'] < $this->post["subjetivas_faceis"]){
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = 'erro_subjetivas_faceis_insuficientes';
            }

            $this->sql = "SELECT
                        count(idpergunta) AS total
                    FROM
                        perguntas
                    WHERE
                        ativo = 'S' AND
                        ativo_painel = 'S' AND
                        iddisciplina IN(".$disciplinas.") AND
                        avaliacao_virtual = 'S' AND
                        tipo = 'S' AND
                        dificuldade = 'M' ";
            $subjetivas_medias = $this->retornarLinha($this->sql);

            if($subjetivas_medias['total'] < $this->post["subjetivas_intermediarias"]){
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = 'erro_subjetivas_medias_insuficientes';
            }

            $this->sql = "SELECT
                        count(idpergunta) as total
                    FROM
                        perguntas
                    WHERE
                        ativo = 'S' AND
                        ativo_painel = 'S' AND
                        iddisciplina in(".$disciplinas.") AND
                        avaliacao_virtual = 'S' AND tipo = 'S' AND
                        dificuldade = 'D' ";
            $subjetivas_dificeis = $this->retornarLinha($this->sql);

            if($subjetivas_dificeis['total'] < $this->post["subjetivas_dificeis"]){
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = 'erro_subjetivas_dificeis_insuficientes';
            }
        }

        if ($this->post["avaliador"] == "sistema") {
            $campos_remover[] = 'periodo_correcao_dias';
            $campos_remover[] = 'subjetivas_faceis';
            $campos_remover[] = 'subjetivas_intermediarias';
            $campos_remover[] = 'subjetivas_dificeis';
            $this->config["formulario"] = $this->alterarConfigFormulario($this->config["formulario"], $campos_remover);
        }

        if ($this->retorno["erro"]) {
            return $this->retorno;
        }

        $salvar = $this->SalvarDados();

        if (!$salvar['sucesso']) {
            return $salvar;
        }

        if ($salvar['sucesso']) {

            $this->sql = "UPDATE
                        avas_avaliacoes_disciplinas
                    SET
                        ativo = 'N'
                    WHERE
                        idavaliacao = ".(int)$salvar['id'];
            $this->executaSql($this->sql);

            $this->monitora_oque = 2;
            $this->monitora_onde = '178';
            foreach ($this->post['iddisciplina_perguntas'] as $iddisciplina) {

                $sql_verifica_existe = "SELECT * FROM avas_avaliacoes_disciplinas
                                    WHERE
                                        idavaliacao = ".(int)$salvar['id']." AND
                                        iddisciplina = ".(int)$iddisciplina;
                $linha_existente = $this->retornarLinha($sql_verifica_existe);

                if ($linha_existente['idavaliacao_disciplina']) {

                    $this->sql = "UPDATE
                        avas_avaliacoes_disciplinas
                    SET
                        ativo = 'S'
                    WHERE
                        idavaliacao = ".(int)$salvar['id']." AND
                        iddisciplina = ".(int)$iddisciplina;

                    if ($this->executaSql($this->sql)) {

                        $this->monitora_qual = mysql_insert_id();
                        $this->Monitora();

                    } else {

                        $retorno["erro"] = true;
                        $retorno["erros"][] = $this->sql;
                        $retorno["erros"][] = mysql_error();
                    }

                } else {

                    $this->sql = "INSERT INTO
                    avas_avaliacoes_disciplinas
                SET
                    data_cad = NOW(),
                    ativo = 'S',
                    idavaliacao = ".(int)$salvar['id'].",
                    iddisciplina = ".(int)$iddisciplina;

                    if ($this->executaSql($this->sql)) {

                        $this->monitora_oque = 1;
                        $this->monitora_onde = '178';
                        $this->monitora_qual = mysql_insert_id();
                        $this->Monitora();

                    } else {

                        $retorno["erro"] = true;
                        $retorno["erros"][] = $this->sql;
                        $retorno["erros"][] = mysql_error();
                    }

                }
            }
        }
        if (!$retorno["erro"]) {
            $retorno["sucesso"] = true;
            $retorno["id"] = $salvar['id'];
        }
        return $retorno;
    }


    public function retornarQtdeTotalTentativasAvaliacao()
    {
        $this->sql = "SELECT
                        count(mavl.idprova) as total
                    FROM
                        matriculas_avaliacoes mavl
                    WHERE
                        mavl.ativo =  'S' AND
                        mavl.idavaliacao = '".(int)$this->id."'";
        $qtdeTentativas = $this->retornarLinha($this->sql);
        return $qtdeTentativas['total'];
    }

    function RemoverAvaliacao() {
        return $this->RemoverDados();
    }

    function RemoverArquivo($modulo, $pasta, $dados, $idioma) {
        echo $this->ExcluirArquivo($modulo, $pasta, $dados, $idioma);
    }

}

?>