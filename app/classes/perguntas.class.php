<?php

class Perguntas extends Core
{

    public function listarTodas()
    {
        $this->sql = 'SELECT ' . $this->campos . ' FROM perguntas WHERE ativo = "S"';

        $this->aplicarFiltrosBasicos();
        $this->groupby = 'idpergunta';
        return $this->retornarLinhas();
    }

    public function listarPerguntasDaSecao($idSecao)
    {
        $this->sql = 'SELECT ' . $this->campos . ' FROM perguntas WHERE ativo = "S"';
        $this->sql .= ' and iddisciplina = ' . $idSecao;
        $this->aplicarFiltrosBasicos();
        $this->groupby = 'idpergunta';
        return $this->retornarLinhas($this->sql);
    }

    function Retornar()
    {
        $this->sql = "SELECT {$this->campos}
                    FROM
                        perguntas
                    WHERE
                        ativo = 'S' AND
                        idpergunta = '{$this->id}'";
        return $this->retornarLinha($this->sql);
    }

    function Cadastrar()
    {
        if ($this->post["tipo"] == "S") {
            $campos_remover[] = 'multipla_escolha';
            $campos_remover[] = 'sentido';
            $campos_remover[] = 'simulado';
            $campos_remover[] = 'exercicio';
            $campos_remover[] = 'quantidade_colunas';
            $this->config["formulario"] = $this->alterarConfigFormulario($this->config["formulario"], $campos_remover);
        }
        if (!$this->post["permite_anexo_resposta"]) {
            $this->post["permite_anexo_resposta"] = 'N';
        }
        $this->monitora_oque = 1;
        $this->monitora_onde = 39;
        $this->idusuario = $_SESSION['adm_idusuario'];
        return $this->SalvarDados();
    }

    function verificaUsoEmProva()
    {
        $this->sql = 'SELECT idprova
                    FROM
                        matriculas_avaliacoes_perguntas
                    WHERE
                        idpergunta =' . $this->id . '
                    LIMIT 1';
        $resultado = $this->retornarLinha($this->sql);
        $retorno = ($resultado['idprova']) ? true : false;
        return $retorno;
    }

    function Modificar()
    {
        if ($this->post["tipo"] == "S") {
            $campos_remover[] = 'multipla_escolha';
            $campos_remover[] = 'sentido';
            $campos_remover[] = 'simulado';
            $campos_remover[] = 'exercicio';
            $campos_remover[] = 'quantidade_colunas';
            $this->config["formulario"] = $this->alterarConfigFormulario($this->config["formulario"], $campos_remover);
        }
        $this->monitora_oque = 2;
        $this->monitora_onde = 39;
        $this->idusuario = $_SESSION['adm_idusuario'];
        return $this->SalvarDados();
    }

    function Remover()
    {
        $this->monitora_oque = 2;
        $this->monitora_onde = 39;
        $this->idusuario = $_SESSION['adm_idusuario'];
        return $this->RemoverDados();
    }

    function ListarTodasOpcoes()
    {
        $this->sql = "SELECT
                        " . $this->campos . "
                    FROM perguntas_opcoes
                    WHERE
                        ativo = 'S' AND
                        idpergunta = '{$this->id}'";
        return $this->retornarLinhas();
    }

    function RetornarImagemPergunta()
    {
        $this->sql = "SELECT
                        imagem_nome,
                        imagem_servidor,
                        imagem_tipo,
                        imagem_tamanho
                    FROM
                        perguntas
                    WHERE
                        idpergunta = '{$this->id}'";
        return $this->retornarLinha($this->sql);
    }

    public function retornarDisponibilidadePerguntas($disciplinas, $dificuldade, $tipo, $avaliacao_virtual = false, $avaliacao_presencial = false)
    {
        $this->sql = "SELECT
                            count(idpergunta) as total
                        FROM
                            perguntas
                        WHERE
                            ativo = 'S' AND
                            ativo_painel = 'S' AND
                            iddisciplina IN(" . $disciplinas . ") AND
                            tipo = '{$tipo}' AND
                            dificuldade = '{$dificuldade}'";
        if ($avaliacao_virtual) {
            $this->sql .= " AND avaliacao_virtual = 'S' ";
        }
        if ($avaliacao_presencial) {
            $this->sql .= " AND avaliacao_presencial = 'S' ";
        }

        $resultado = $this->retornarLinha($this->sql);
        return $resultado['total'];
    }

    public function retornarPerguntasProva($disciplinas, $dificuldade, $tipo, $avaliacao_virtual = false, $avaliacao_presencial = false, $rand = false)
    {
        $this->sql = "SELECT
                            *
                        FROM
                            perguntas
                        WHERE
                            ativo = 'S' AND
                            ativo_painel = 'S' AND
                            iddisciplina IN(" . $disciplinas . ") AND
                            tipo = '{$tipo}' AND
                            dificuldade = '{$dificuldade}'";
        if ($avaliacao_virtual) {
            $this->sql .= " AND avaliacao_virtual = 'S' ";
        }
        if ($avaliacao_presencial) {
            $this->sql .= " AND avaliacao_presencial = 'S' ";
        }
        if ($rand) {
            $this->ordem_campo = "rand() ";
        }

        $this->ordem = " ";
        $this->groupby = "idpergunta";

        $perguntas = $this->retornarLinhas();
        return $perguntas;
    }

    function CadastrarOpcao()
    {
        if (!$this->post["ordem"]) {
            $this->post["ordem"] = "NULL";
        }

        $this->sql = "SELECT
                        idopcao
                    FROM
                        perguntas_opcoes
                    WHERE
                        idpergunta = '{$this->id}' AND
                        ativo= 'S'";
        $adicionadas = $this->retornarLinhas();

        if (count($adicionadas) > 0) {
            $correta = "N";
        } else {
            $correta = "S";
        }

        $this->sql = "INSERT INTO
                        perguntas_opcoes
                    SET
                        data_cad = now(),
                        idpergunta = '" . $this->id . "',
                        nome = '" . mysql_real_escape_string($this->post["nome"]) . "',
                        ordem = " . (int)$this->post["ordem"] . ",
                        correta = '{$correta}'";
        if ($this->executaSql($this->sql)) {
            $this->monitora_qual = mysql_insert_id();
            $this->retorno["sucesso"] = true;
            $this->monitora_oque = 1;
            $this->monitora_onde = 57;
            $this->idusuario = $_SESSION['adm_idusuario'];
            $this->Monitora();
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }
        return $this->retorno;
    }

    function ModificarOpcoes()
    {
        $this->monitora_oque = 2;
        $this->monitora_onde = 57;
        foreach ($this->post["ordens"] as $idopcao => $ordem) {
            $this->sql = "SELECT *
                        FROM
                            perguntas_opcoes
                        WHERE
                            idpergunta = '" . $this->id . "' AND
                            idopcao = " . (int)$idopcao;
            $linhaAntiga = $this->retornarLinha($this->sql);

            $this->sql = "UPDATE
                            perguntas_opcoes
                        SET
                            ordem = " . (int)$ordem . "
                        WHERE
                            idpergunta = '" . $this->id . "' AND
                            idopcao = " . (int)$idopcao;
            $executa = $this->executaSql($this->sql);

            $this->sql = "SELECT * FROM
                                perguntas_opcoes
                        WHERE
                            idpergunta = '" . $this->id . "' AND
                            idopcao = " . (int)$idopcao;
            $linhaNova = $this->retornarLinha($this->sql);

            if ($executa) {
                $this->monitora_qual = $idopcao;
                $this->monitora_dadosantigos = $linhaAntiga;
                $this->monitora_dadosnovos = $linhaNova;
                $this->idusuario = $_SESSION['adm_idusuario'];
                $this->Monitora();
                $this->retorno["sucesso"] = true;
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }

    function RemoverOpcao()
    {
        include_once("../includes/validation.php");
        $regras = array(); // stores the validation rules

        if (!$this->post["remover"]) {
            $regras[] = "required,remover,remover_vazio";
        }

        $erros = validateFields($this->post, $regras);

        if (!empty($erros)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        } else {

            $this->sql = "UPDATE perguntas_opcoes
                        SET
                            ativo = 'N'
                        WHERE
                            idopcao = " . (int)$this->post["remover"];

            if ($this->executaSql($this->sql)) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 39;
                $this->idusuario = $_SESSION['adm_idusuario'];
                $this->monitora_qual = intval($this->post["remover"]);
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }

    function ModificarOpcoesAtivoPainel()
    {
        $this->sql = "SELECT * FROM
                        perguntas_opcoes
                    WHERE
                        idpergunta = '{$this->id}' AND
                        idopcao = " . (int)$this->post["idopcao"];
        $linhaAntiga = $this->retornarLinha($this->sql);

        if ($linhaAntiga["ativo_painel"] == "S") {
            $ativo_painel = "N";
        } else {
            $ativo_painel = "S";
        }

        $this->sql = "UPDATE
                        perguntas_opcoes
                    SET
                        ativo_painel = '" . $ativo_painel . "'
                    WHERE
                        idpergunta = '" . $this->id . "' AND
                        idopcao = " . (int)$this->post["idopcao"];
        $executa = $this->executaSql($this->sql);

        $this->sql = "SELECT * FROM
                        perguntas_opcoes
                    WHERE
                        idpergunta = '{$this->id}' AND
                        idopcao = '" . $this->post['idopcao'] . "'";
        $linhaNova = $this->retornarLinha($this->sql);

        $this->retorno = array();

        if ($executa) {
            $this->monitora_oque = 2;
            $this->monitora_onde = 57;
            $this->monitora_qual = $this->post["idopcao"];
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->idusuario = $_SESSION['adm_idusuario'];
            $this->Monitora();

            $this->retorno["sucesso"] = true;
            $this->retorno["ativo"] = $linhaNova["ativo_painel"];
            $this->retorno["opcao"] = $linhaNova["idopcao"];
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["ativo"] = $linhaAntiga["ativo_painel"];
            $this->retorno["opcao"] = $linhaAntiga["idopcao"];
        }

        return json_encode($this->retorno);
    }

    function ModificarRespostaCorreta()
    {
        $mudanca = true;
        $this->sql = "SELECT *
                    FROM
                        perguntas_opcoes
                    WHERE
                        idpergunta = '" . $this->id . "' AND
                        idopcao = " . intval($this->post["idopcao"]);
        $linhaAntiga = $this->retornarLinha($this->sql);

        $this->sql = "SELECT
                            multipla_escolha
                        FROM
                            perguntas
                    WHERE idpergunta = '" . $this->id . "'";
        $pergunta = $this->retornarLinha($this->sql);

        if (($linhaAntiga["correta"] == "N") && ($pergunta["multipla_escolha"] <> "S")) { //Não pode selecionar mais de uma opção----------------------
            $correto = "S";
            $this->sql = "UPDATE
                                perguntas_opcoes
                        SET
                            correta = '" . $correto . "'
                        WHERE
                            idpergunta = '" . $this->id . "' AND
                            idopcao = " . (int)$this->post["idopcao"];
            $executa = $this->executaSql($this->sql);

            $this->sql = "UPDATE
                            perguntas_opcoes
                        SET
                            correta = 'N'
                        WHERE
                            idpergunta = '" . $this->id . "' AND
                            idopcao <> " . (int)$this->post["idopcao"];
            $mudanca = $this->executaSql($this->sql);
        } elseif (($linhaAntiga["correta"] == "N") && ($pergunta["multipla_escolha"] == "S")) { //Mais de uma opção poderão ser marcadas----------------
            $correto = "S";
            $this->sql = "UPDATE
                            perguntas_opcoes
                        SET
                            correta = '" . $correto . "'
                        WHERE
                            idpergunta = '" . $this->id . "' AND
                            idopcao = " . (int)$this->post["idopcao"];
            $executa = $this->executaSql($this->sql);
        } elseif ($linhaAntiga["correta"] == "S") {
            $this->sql = "SELECT
                                count(idopcao) as total_corretas
                        FROM
                            perguntas_opcoes
                        WHERE
                            ativo='S' AND
                            idpergunta='" . $this->id . "' AND
                            correta='S' AND
                            idopcao <> " . (int)$this->post["idopcao"];
            $qtde_corretas = $this->retornarLinha($this->sql);

            if (!$qtde_corretas['total_corretas']) {
                $this->retorno["sucesso"] = false;
                return json_encode($this->retorno);
            } else {
                $correto = "N";
                $this->sql = "UPDATE
                                perguntas_opcoes
                            SET
                                correta = '" . $correto . "'
                            WHERE
                                idpergunta = '" . $this->id . "' AND
                                idopcao = " . (int)$this->post["idopcao"];
                $executa = $this->executaSql($this->sql);
            }
        }

        $this->sql = "SELECT * FROM
                            perguntas_opcoes
                    WHERE
                        idpergunta = '" . $this->id . "' AND
                        idopcao = " . (int)$this->post["idopcao"];
        $linhaNova = $this->retornarLinha($this->sql);

        $this->retorno = array();

        if ($executa) {
            $this->monitora_oque = 2;
            $this->monitora_onde = 57;
            $this->monitora_qual = intval($this->post["idopcao"]);
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->idusuario = $_SESSION['adm_idusuario'];
            $this->Monitora();

            $this->retorno["sucesso"] = true;
            $this->retorno["correta"] = $linhaNova["correta"];
            $this->retorno["opcao"] = $linhaNova["idopcao"];
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["correta"] = $linhaAntiga["correta"];
            $this->retorno["opcao"] = $linhaAntiga["idopcao"];
        }

        return json_encode($this->retorno);
    }

    function ListarDisciplinasAssociadas()
    {
        $this->sql = "SELECT
					" . $this->campos . "
				  FROM
					disciplinas d
					INNER JOIN disciplinas_perguntas dp
                    ON (d.iddisciplina = dp.iddisciplina)
				  WHERE
					dp.ativo = 'S' and
					dp.idpergunta = " . intval($this->id);

        $this->groupby = "dp.iddisciplina_pergunta";
        return $this->retornarLinhas();
    }

    function BuscarDisciplina()
    {
        $this->sql = "SELECT
					d.iddisciplina as 'key', d.nome as value
				  FROM
					disciplinas d
				  WHERE
					 d.nome like '%" . $_GET["tag"] . "%' AND
					 d.ativo = 'S' AND
					 d.ativo_painel = 'S' AND
					 NOT EXISTS (
                                    SELECT
                                        dp.iddisciplina
                                    FROM
                                        disciplinas_perguntas dp
                                    WHERE
                                        dp.iddisciplina = d.iddisciplina AND
                                        dp.idpergunta = '" . $this->id . "' AND
                                        dp.ativo = 'S'
                                )";

        $this->limite = -1;
        $this->ordem_campo = "value";
        $this->groupby = "value";

        $dados = $this->retornarLinhas();
        return json_encode($dados);
    }

    function AssociarDisciplinas($idpergunta, $arrayDisciplinas)
    {
        foreach ($arrayDisciplinas as $id) {
            $this->sql = "SELECT
                                count(iddisciplina_pergunta) as total,
                                iddisciplina_pergunta
                        FROM
                            disciplinas_perguntas
                        WHERE
                            idpergunta = '" . intval($idpergunta) . "' AND
                            idpergunta = '" . intval($id) . "' AND
                            ativo = 'N' ";
            $totalAss = $this->retornarLinha($this->sql);

            if ($totalAss["total"] > 0) {

                $this->sql = "UPDATE
                                disciplinas_perguntas
                            SET
                                ativo = 'S'
                            WHERE
                                iddisciplina_pergunta = " . $totalAss["iddisciplina_pergunta"];
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $totalAss["iddisciplina_pergunta"];

            } else {

                $this->sql = "INSERT INTO
                                    disciplinas_perguntas
                            SET
                                ativo = 'S',
                                data_cad = now(),
                                idpergunta = '" . (int)$idpergunta . "',
                                iddisciplina = '" . (int)$id . "'";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();
            }

            if ($associar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 118;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }

    function RemoverDisciplinas()
    {
        include_once("../includes/validation.php");
        $regras = array(); // stores the validation rules

        if (!$this->post["remover"]) {
            $regras[] = "required,remover,remover_vazio";
        }

        $erros = validateFields($this->post, $regras);

        if (!empty($erros)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        } else {
            $this->sql = "UPDATE disciplinas_perguntas
                        SET
                            ativo = 'N'
                        WHERE
                            iddisciplina_pergunta = " . (int)$this->post["remover"];
            $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 118;
                $this->monitora_qual = intval($this->post["remover"]);
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }

    public function listarDisciplinasClonar($idDisciplina)
    {
        $retorno = [];
        $sql = "SELECT iddisciplina, nome FROM disciplinas WHERE iddisciplina <> {$idDisciplina} ORDER BY nome";
        $res = $this->executaSql($sql);
        while ($linha = mysql_fetch_assoc($res)) {
            $retorno[] = $linha;
        }
        return $retorno;
    }

    public function salvarPerguntasClonar($idDisciplinaDe)
    {
        include_once("../includes/validation.php");
        $regras = array();

        if (empty($this->post['perguntas'])) {
            $regras[] = "required,perguntas,perguntas_vazio";
        }

        if (empty($this->post['selDisciplinas'])) {
            $regras[] = "required,selDisciplinas,disciplinas_vazio";
        }

        $erros = validateFields($this->post, $regras);

        if (!empty($erros)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        } else {
            foreach ($this->post['perguntas'] as $pergunta) {
                $this->sql = "INSERT INTO
                        perguntas_clonar
                    SET
                        data_cad = now(),
                        clonada = 'N',
                        iddisciplina_de = {$idDisciplinaDe},
                        iddisciplina_para = {$this->post['selDisciplinas']},
                        idpergunta = {$pergunta},
                        idusuario = {$this->idusuario}
                    ";
                $inserir = $this->executaSql($this->sql);

                if ($inserir) {
                    $this->retorno["sucesso"] = true;
                    $this->monitora_oque = 1;
                    $this->monitora_onde = 287;
                    $this->monitora_qual = mysql_insert_id();
                    $this->Monitora();
                } else {
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = $this->sql;
                    $this->retorno["erros"][] = mysql_error();
                }
            }
        }
        return $this->retorno;
    }

    public function retornarPerguntasPendentesClonar()
    {
        $sql = "SELECT 
                * 
            FROM 
                perguntas_clonar 
            WHERE 
                clonada = 'N'";
        $res = $this->executaSql($sql);
        $retorno = [];
        while ($linha = mysql_fetch_assoc($res)) {
            $retorno[] = $linha;
        }
        return $retorno;
    }

    public function clonarPergunta($pergunta)
    {
        $array_ignora = [
            'data_cad',
            'ativo',
            'idpergunta',
            'iddisciplina',
            'idopcao'
        ];

        $sql = "SELECT 
                * 
            FROM 
                perguntas 
            WHERE 
                idpergunta = {$pergunta['idpergunta']}";
        $linha = $this->retornarLinha($sql);

        $anteriorimg = $linha['imagem_servidor'];
        if ($linha['imagem_servidor']) {
            $linha['imagem_servidor'] =   $this->novoCloneArquivo($linha['imagem_servidor']);
        }

        $sql = "INSERT INTO 
                perguntas 
            SET 
                data_cad = now(), 
                ativo = 'S', 
                iddisciplina = {$pergunta['iddisciplina_para']} ";
        $sql .= $this->estruturaClone($linha, $array_ignora);
        $this->executaSql($sql);

        $this->monitora_oque = 1;
        $this->monitora_onde = 39;
        $this->monitora_qual = $idPergunta = mysql_insert_id();
        $this->Monitora();

        if ($anteriorimg && file_exists('../storage/disciplinas_perguntas_imagens/' . $anteriorimg)) {
            try {
                copy('../storage/disciplinas_perguntas_imagens/' . $anteriorimg,
                    '../storage/disciplinas_perguntas_imagens/' . $linha['imagem_servidor']);
            } catch(Exception $ex) {

            }
        }

        $sql = "SELECT 
                * 
            FROM 
                perguntas_opcoes 
            WHERE 
                idpergunta = {$pergunta['idpergunta']}";

        $query = $this->executaSql($sql);
        if(mysql_num_rows($query) > 0){
            while($linha =  mysql_fetch_assoc($query)){
                $sql = "INSERT INTO 
                        perguntas_opcoes 
                    SET 
                        ativo = 'S', 
                        data_cad = NOW(), 
                        idpergunta = {$idPergunta} ";
                $sql .= $this->estruturaClone($linha, $array_ignora);
                $this->executaSql($sql);
                $this->monitora_oque = 1;
                $this->monitora_onde = 57;
                $this->monitora_qual = mysql_insert_id();
                $this->Monitora();
            }
        }

        $sql = "UPDATE perguntas_clonar SET clonada = 'S' WHERE idpergunta_clonar = {$pergunta['idpergunta_clonar']}";
        return $this->executaSql($sql);
    }

    private function estruturaClone($array, $ignora)
    {
        $text = '';
        foreach ($array as $ind => $val) {
            if (! in_array($ind, $ignora)) {
                if (is_null($val)) {
                    $text .= "  , " . $ind . " =  NULL ";
                } else {
                    $text .= "  , " . $ind . " =  '" . mysql_escape_string($val) . "'";
                }
            }
        }
        return $text;
    }

    private function novoCloneArquivo($endereco)
    {
        $extensao = strtolower(strrchr($endereco, "."));
        $endereco = date("YmdHis") . "_" . uniqid() . $extensao;
        return $endereco;
    }
}