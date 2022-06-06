<?php
/**
 * `Contratos`
 *
 * @author     Gabriel Manzano <gabriel@alfamaweb.com.br>
 * @author     Tomaz Novaes <tomaz@alfamaweb.com.br>
 * @author     Henrique Feitosa <henriquef@alfamaweb.com.br>
 * @author     Daiane Azevedo <daianea@alfamaweb.com.br>
 * @author     Jefersson Nathan <jeferssonn@alfamaweb.com.br>
 *
 * @package    Oráculo
 * @copyright  Copyright (c) 2014 Alfama Web (http://alfamaweb.com.br)
 * @license    Proprietary AlfamaWeb
 * @version    $Id$
 */
class Contratos extends Core
{
    const CURRENT_TABLE = 'contratos';
    const IMAGES_TABLE = 'contratos_imagens';

    /**
     * Retorna um contrato
     *
     * @param $idContrato
     *
     * @throws InvalidArgumentException
     * @return array|boolean
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function getContrato($idContrato)
    {
        if (!is_numeric($idContrato)) {
            throw new InvalidArgumentException('Argumento passado à *getContrato* deve ser um valor numérico');
        }

        $query = sprintf('SELECT * FROM %s WHERE idcontrato = %d', self::CURRENT_TABLE, $idContrato);
        return $this->retornarLinha($query);
    }

    public function cadastrarImagem()
    {
        $this->return = array();

        $pasta = $_SERVER['DOCUMENT_ROOT'] . '/storage/contratos_imagens/' . $_POST['idcontrato'];

        $extensao = strtolower(strrchr($_FILES['background']['name'], '.'));
        $nomeServidor = date('YmdHis') . '_' . uniqid() . $extensao;

        mkdir($pasta, 0777);
        chmod($pasta, 0777);

        $envio = move_uploaded_file($_FILES['background']['tmp_name'], $pasta . '/' . $nomeServidor);

        chmod($pasta . '/' . $nomeServidor, 0777);

        $db = new Zend_Db_Select(new Zend_Db_MySql);

        $insert = $db->insert(self::IMAGES_TABLE, array(
            'data_cad' => 'NOW()',
            'idcontrato' => $_POST['idcontrato'],
            'arquivo_nome' => $db->quote($_FILES["background"]["name"]),
            'arquivo_servidor' => $db->quote($nomeServidor),
            'arquivo_tipo' => $db->quote($_FILES["background"]["type"]),
            'arquivo_tamanho' => $db->quote($_FILES["background"]["size"])
        ));

        $salvar = $this->executaSql($insert);

        if ($salvar) {
            $this->return["sucesso"] = true;
            $this->return["mensagem"] = "arquivos_matricula_envio_sucesso";
        } else {
            $this->return["sucesso"] = false;
            $this->return["mensagem"] = "arquivos_matricula_envio_erro";
        }

        return $this->return;
    }

    public function ListarTodas()
    {
        $this->sql = "SELECT " . $this->campos . " FROM " . self::CURRENT_TABLE . " WHERE ativo = 'S'";

        $this->aplicarFiltrosBasicos();
        $this->groupby = "idcontrato";
        return $this->retornarLinhas();
    }

    public function listarImagens($filtros)
    {
        $this->sql = "SELECT *, CONCAT('[[imagem][',idimagem,']]') as tag FROM " . self::IMAGES_TABLE . " WHERE ativo='S'";
        if($filtros)
        {
            foreach ($filtros as $coluna => $valor)
            {
                $this->sql .= " AND $coluna = $valor";
            }
        }
        $this->aplicarFiltrosBasicos();
        $this->groupby = "idcontrato";
        return $this->retornarLinhas();
    }

    function Retornar()
    {
        $this->sql = "SELECT " . $this->campos . " FROM contratos where ativo='S' and idcontrato='" . $this->id . "'";
        return $this->retornarLinha($this->sql);
    }

    function Cadastrar()
    {
        $file_aux['name'] = $_POST['background'];
        $validacao_tamanho = $this->ValidarArquivo($file_aux['name']);
        if ($validacao_tamanho) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $validacao_tamanho;
            return $this->retorno;
        }
        return $this->SalvarDados();
    }

    function Modificar()
    {
        $file_aux['name'] = $_POST['background'];
        $validacao_tamanho = $this->ValidarArquivo($file_aux['name']);
        if ($validacao_tamanho) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $validacao_tamanho;
            return $this->retorno;
        }
        return $this->SalvarDados();
    }

    function Remover()
    {
        return $this->RemoverDados();
    }

    function RemoverArquivo($modulo, $pasta, $dados, $idioma)
    {
        echo $this->ExcluirArquivo($modulo, $pasta, $dados, $idioma);
    }

    function RetornarContratosGrupos()
    {
        $this->sql = "select
                      " . $this->campos . "
                    from
                      contratos
                    where
                      ativo = 'S' and
                      idcontrato = " . $this->id;
        return $this->retornarLinha($this->sql);
    }

    public function filtrar($content)
    {
        $patterns = array('#\[\[imagem\]\[(\d+)\]\]#i' => 'SELECT * FROM ' . self::IMAGES_TABLE . ' WHERE idimagem = %d');

        // Faz busca e replace apartir de regex
        foreach ($patterns as $pattern => $query) {
            preg_match_all($pattern, $content, $match);
            $midiasCollection = array_combine($match[1], $match[0]);

            foreach ($midiasCollection as $id => $tagToReplace) {
                $_query = mysql_fetch_object(mysql_query(sprintf($query, $id)));
                $content = str_ireplace($tagToReplace, '<img src="http://' . $_SERVER['SERVER_NAME'] . '/storage/contratos_imagens/' . $_query->idcontrato . '/' . $_query->arquivo_servidor . '" />', $content);
            }

        }

        return $content;
    }

    function BuscarSindicato() {
        $this->sql = "SELECT
                            i.idsindicato AS 'key',
                            i.nome_abreviado AS value
                        FROM
                            sindicatos i
                        WHERE
                            i.nome_abreviado LIKE '%".$_GET["tag"]."%' AND
                            i.ativo = 'S' AND
                            i.ativo_painel = 'S' AND
                            NOT EXISTS (
                                            SELECT
                                                ci.idsindicato
                                            FROM
                                                contratos_sindicatos ci
                                                INNER JOIN contratos c ON (c.idcontrato = ci.idcontrato)
                                            WHERE
                                                ci.idsindicato = i.idsindicato AND
                                                ci.idcontrato = ".intval($this->id)." AND
                                                ci.ativo = 'S' AND
                                                c.ativo = 'S'
                                        )";
        $this->limite = -1;
        $this->ordem_campo = "value";
        $this->groupby = "value";

        $dados = $this->retornarLinhas();
        return json_encode($dados);
    }

    function ListarSindicatosAss() {
        $this->sql = "select
                        ".$this->campos."
                      from
                        sindicatos i
                        inner join contratos_sindicatos ci ON (i.idsindicato = ci.idsindicato)
                      where
                        ci.ativo = 'S' and
                        ci.idcontrato = ".intval($this->id);

        $this->groupby = "ci.idcontrato_sindicato";
        return $this->retornarLinhas();
    }

    function AssociarSindicatos($idcontrato, $arraySindicatos) {
        foreach($arraySindicatos as $ind => $id) {
            //Verifica se já existe associação de algum cartão com essa sindicato
            $this->sql = "SELECT
                                count(ci.idcontrato_sindicato) AS total,
                                ci.idcontrato_sindicato
                            FROM
                                contratos_sindicatos ci
                                INNER JOIN contratos c ON (c.idcontrato = ci.idcontrato)
                            WHERE
                                ci.idcontrato = '".intval($idcontrato)."' AND
                                ci.idsindicato = '".intval($id)."' AND
                                ci.ativo = 'S' AND
                                c.ativo = 'S'";
            $existeAssSindicato = $this->retornarLinha($this->sql);

            //Se não existir outro cartão associado à essa sindicato
            if ($existeAssSindicato['total'] == 0) {
                $this->sql = "SELECT
                                    count(idcontrato_sindicato) AS total,
                                    idcontrato_sindicato
                                FROM
                                    contratos_sindicatos
                                WHERE
                                    idcontrato = '".intval($idcontrato)."' AND
                                    idsindicato = '".intval($id)."'";
                $totalAss = $this->retornarLinha($this->sql);
                if($totalAss["total"] > 0) {
                    $this->sql = "UPDATE contratos_sindicatos SET ativo = 'S' WHERE idcontrato_sindicato = ".$totalAss["idcontrato_sindicato"];
                    $associar = $this->executaSql($this->sql);
                    $this->monitora_qual = $totalAss["idcontrato_sindicato"];
                } else {
                    $this->sql = "INSERT INTO
                                        contratos_sindicatos
                                    SET
                                        ativo = 'S',
                                        data_cad = now(),
                                        idcontrato = '".intval($idcontrato)."',
                                        idsindicato = '".intval($id)."'";
                    $associar = $this->executaSql($this->sql);
                    $this->monitora_qual = mysql_insert_id();
                }

                if ($associar) {
                    $this->retorno["sucesso"] = true;
                    $this->monitora_oque = 1;
                    $this->monitora_onde = 234;
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

    function DesassociarSindicatos() {

        include_once("../includes/validation.php");
        $regras = array(); // stores the validation rules

        //VERIFICANDO SE OPNIAO REMOVER FOI MARCADA
        if(!$this->post["remover"])
            $regras[] = "required,remover,remover_vazio";

        //VALIDANDO FORMULÃRIO
        $erros = validateFields($this->post, $regras);

        //SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
        if(!empty($erros)){
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        }else{
            $this->sql = "update contratos_sindicatos set ativo = 'N' where idcontrato_sindicato = ".intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if($desassociar){
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 234;
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

    public function retornarContratosSindicato($idsindicato) {

        $this->sql = "SELECT
                        c.idcontrato,
                        c.nome,
                        'contrato' as tipo
                    FROM
                        contratos c
                    WHERE
                        c.ativo =  'S' AND
                        c.ativo_painel =  'S' AND
                        c.gerar_aluno =  'S' AND
                        (
                            NOT EXISTS (
                                SELECT
                                    ci.idcontrato_sindicato
                                FROM
                                    contratos_sindicatos ci
                                WHERE
                                    ci.ativo = 'S' and
                                    ci.idcontrato = c.idcontrato
                            )
                        OR
                            EXISTS (
                                SELECT
                                    cii.idcontrato_sindicato
                                FROM
                                    contratos_sindicatos cii
                                WHERE
                                    cii.ativo = 'S' AND
                                    cii.idsindicato = '" . intval($idsindicato) . "' AND
                                    cii.idcontrato = c.idcontrato
                            )
                        )
                    GROUP BY c.idcontrato";

        $this->limite = -1;
        $this->ordem_campo = 'c.nome';
        $this->ordem = 'ASC';

        return $this->retornarLinhas();
    }


    function ListarCursosAssociadas()
    {
        $this->sql = "select
                    " . $this->campos . "
                  from
                    contratos_cursos pi
                    inner join cursos i ON (pi.idcurso = i.idcurso)
                  where
                    i.ativo = 'S' and
                    pi.ativo= 'S' and
                    pi.idcontrato = " . intval($this->id);

        $this->limite = -1;
        $this->ordem = "asc";
        $this->ordem_campo = "i.nome";
        return $this->retornarLinhas();
    }

    function AssociarCurso()
    {
        foreach ($this->post["cursos"] as $idcurso) {
            $this->sql = "select count(idcontrato_curso) as total, idcontrato_curso from contratos_cursos where idcontrato = '" . $this->id . "' and idcurso = '" . intval($idcurso) . "'";
            $totalAssociado = $this->retornarLinha($this->sql);
            if ($totalAssociado["total"] > 0) {
                $this->sql = "update contratos_cursos set ativo = 'S' where idcontrato_curso = " . $totalAssociado["idcontrato_curso"];
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $totalAssociado["idcontrato_curso"];
            } else {
                $this->sql = "insert into contratos_cursos set ativo = 'S', data_cad = now(), idcontrato = '" . $this->id . "', idcurso = '" . intval($idcurso) . "'";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();
            }
            if ($associar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 174;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }

    function DesassociarCurso()
    {

        include_once("../includes/validation.php");
        $regras = array(); // stores the validation rules

        if (!$this->post["remover"])
            $regras[] = "required,remover,remover_vazio";

        $erros = validateFields($this->post, $regras);

        if (!empty($erros)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        } else {
            $this->sql = "update contratos_cursos set ativo = 'N' where idcontrato_curso = " . intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 174;
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

    function BuscarCursos()
    {
        $this->sql = "select
                    i.idcurso as 'key',
                    i.nome as value
                  from
                    cursos i
                  where
                                        i.nome LIKE '%" . $this->get["tag"] . "%' and
                    i.ativo = 'S' and
                    not exists (
                      select
                        pi.idcontrato
                      from
                        contratos_cursos pi
                      where
                        pi.idcurso = i.idcurso and
                        pi.idcontrato = '" . intval($this->id) . "' and
                        pi.ativo = 'S'
                    )";

        $this->limite = -1;
        $this->ordem_campo = "i.nome";
        $this->groupby = "i.idcurso";
        $this->retorno = $this->retornarLinhas();

        return json_encode($this->retorno);
    }


}
