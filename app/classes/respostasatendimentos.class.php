<?php
/**
 * Respostar_Atendimentos
 *
 * @package Oraculo
 */
class Respostas_Atendimentos extends Core
{
    /** Tabela principal para a maioria das operações */
    const CURRENT_TABLE = 'atendimentos_respostas_automaticas';

    /** Tabelas "secundárias" */
    const TABLE_ASSUNTO = 'atendimentos_assuntos';
    const TABLE_RESPOSTAR_ASSUNTO = 'atendimentos_respostas_automaticas_assuntos';

    const TABLE_ASSOCIAR_RESPOSTA = 'atentimentos_repostas_escolas';

    /**
     * @return array Retorna todas as linhas que estão ativas
     */
    public function listarTodas()
    {
        $this->sql = sprintf(
            'SELECT %s FROM %s WHERE ativo = "S"',
            $this->campos,
            self::CURRENT_TABLE
        );

        $this->aplicarFiltrosBasicos();
        $this->groupby = 'idresposta';
        return $this->retornarLinhas();
    }

    /**
     * @return array Retorna apenas uma linha
     */
    public function retornar()
    {
        $this->sql = sprintf(
            'SELECT %s FROM %s WHERE ativo="S" AND idresposta="%d"',
            $this->campos,
            self::CURRENT_TABLE,
            $this->id
        );

        return $this->retornarLinha($this->sql);
    }

    public function cadastrar() {
        return $this->salvarDados();
    }

    public function modificar() {
        return $this->salvarDados();
    }

    public function remover() {
        return $this->removerDados();
    }

    /**
     * return array Assuntos que estão ativo e ativo no painel
     */
    public function listarAssuntos()
    {
        $this->sql = sprintf(
            "SELECT %s FROM %s WHERE ativo='S' AND ativo_painel = 'S'",
            $this->campos,
            self::TABLE_ASSUNTO
        );

        $this->set('groupby', 'idassunto');
        return $this->retornarLinhas();
    }


    function RetornarAssuntosAssoc()
    {
        $this->sql = "SELECT ".$this->campos."
                            FROM
                              atendimentos_respostas_automaticas_assuntos where idresposta = '".$this->id."' AND ativo='S'";
        $this->groupby = "idresposta_assunto";
        return $this->retornarLinhas();
    }

    function AssociarAssuntos(){

        $this->set('monitora_qual', $this->id);

        $this->sql = "SELECT todos FROM atendimentos_respostas_automaticas where idresposta = '".$this->id."'";
        $todos = $this->retornarLinha($this->sql);

        if($todos["todos"]== "S" and !$this->post["todos"]){
            $this->sql = "update atendimentos_respostas_automaticas set todos = 'N' where idresposta = '".$this->id."'";
            $altera = true;
        }elseif($todos["todos"]== "N" and $this->post["todos"]){
            $this->sql = "update atendimentos_respostas_automaticas set todos = 'S' where idresposta = '".$this->id."'";
            $altera = true;
        }

        if($altera){
            $associar = $this->executaSql($this->sql);

            if($associar){
                $this->retorno["sucesso"] = true;
                $this->set('monitora_oque', 3)
                    ->set('monitora_onde', 88)
                    ->set('monitora_qual', $this->id)
                    ->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }

        $this->sql = "SELECT idassunto, idresposta_assunto FROM atendimentos_respostas_automaticas_assuntos WHERE idresposta = '".$this->id."'";
        $sqlAux = $this->executaSql($this->sql);
        while($linha = mysql_fetch_assoc($sqlAux)){
            if(!in_array($linha["idassunto"], $this->post["assuntos"])){
                $this->sql = "update atendimentos_respostas_automaticas_assuntos set ativo = 'N' where idresposta_assunto = ".$linha["idresposta_assunto"];
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $linha["idresposta_assunto"];

                if($associar){
                    $this->retorno["sucesso"] = true;
                    $this->monitora_oque = 3;
                    $this->monitora_onde = 89;
                    $this->monitora_qual = $this->id;
                    $this->Monitora();
                } else {
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = $this->sql;
                    $this->retorno["erros"][] = mysql_error();
                }
            }
        }

        foreach($this->post["assuntos"] as $ind => $idassunto) {
            $this->sql = "select count(idresposta_assunto) as total, idresposta_assunto, ativo from atendimentos_respostas_automaticas_assuntos where idresposta = '".$this->id."' and idassunto = '".intval($idassunto)."'";
            $assoc = $this->retornarLinha($this->sql);

            if($assoc["total"] > 0){
                $this->sql = "update atendimentos_respostas_automaticas_assuntos set ativo = 'S' where idresposta_assunto = ".$assoc["idresposta_assunto"];
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $assoc["idpessoa_associacao"];
            }else{
                $this->sql = "insert into atendimentos_respostas_automaticas_assuntos set ativo = 'S', data_cad = now(), idresposta = '".$this->id."', idassunto = '".intval($idassunto)."'";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();
            }

            if($associar){
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 89;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }

        }

        return $this->retorno;
    }

    function ListarTodasAssunto($idassunto, $idmatricula = null) {

        $this->sql = "SELECT
                        ".$this->campos."
                      FROM
                        atendimentos_respostas_automaticas ara
                        INNER JOIN atendimentos_respostas_automaticas_assuntos araa ON (ara.idresposta = araa.idresposta)
                      WHERE
                        ara.ativo = 'S' AND
                        araa.ativo = 'S' AND
                        (araa.idassunto = '".$idassunto."' OR ara.todos = 'S')
                       GROUP BY ara.idresposta";

        $this->limite = -1;
        $this->ordem = "asc";
        $this->ordem_campo = "nome";
        $this->groupby = "idresposta";
        $respostas =  $this->retornarLinhas();

        if (null !== $idmatricula) {
            $matricula = new Matriculas;
            $info = $matricula->getMatricula($idmatricula);
            $idescola = $info['idescola'];

            $this->sql = sprintf(
                'SELECT idresposta FROM %s WHERE idescola = %d',
                self::TABLE_ASSOCIAR_RESPOSTA,
                $idescola
            );

            $this->set('campos', '*');
            $this->set('groupby', '*');
            $this->set('limite', -1);
            $this->set('ordem', 'DESC');
            $this->set('ordem_campo', 'idresposta');

            $respAssoc = $this->retornarLinhas();

            $assocResult = array();
            foreach ($respAssoc as $_tmp) {
                $assocResult[] = $_tmp['idresposta'];
            }

            $assocRes = array();
            foreach($respostas as $_tmp) {
                $assocRes[] = $_tmp['idresposta'];
            }

            $resultadoDaIntersecao = array_intersect($assocResult, $assocRes);
            $storage = $respostas;

            $respostas = array();
            foreach($resultadoDaIntersecao as $_tmp) {
                foreach($storage as $key => $_tmpStorage){
                    if ($_tmpStorage['idresposta'] == $_tmp)
                    $respostas[] = $storage[$key];
                }
            }
        }

        return $respostas;
    }

    /**
     * Associa uma resposta a um/multiplos escolas.
     *
     * @param ArrayObject $param
     * @param $idResposta
     */
    public function associarRespostaAosEscolas(ArrayObject $param, $idResposta)
    {
        // Limpa a tabela do banco de dados
        $this->executaSql(sprintf('DELETE FROM %s WHERE idresposta = %d', self::TABLE_ASSOCIAR_RESPOSTA, $idResposta));

        if ($param) {
            $insertStmt = sprintf('INSERT INTO %s SET idresposta = %d, idescola=%s', self::TABLE_ASSOCIAR_RESPOSTA, $idResposta, '%s');

            foreach ($param->getIterator() as $idEscola) {
                $this->executaSql(sprintf($insertStmt, $idEscola));
            }
        }
    }

    public function retornarEscolasAssociados($idResposta)
    {
        $this->sql = 'SELECT idescola FROM ' . self::TABLE_ASSOCIAR_RESPOSTA . ' WHERE idresposta = ' . $idResposta;
        $this->campos = 'idescola';
        $this->limite = -1;
        $this->groupby = 'idescola';

        return $this->retornarLinhas();
    }
}