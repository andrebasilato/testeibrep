<?php
/**
 * `Folhas Registro Diplomas`
 *
 * @author     Gabriel Manzano <gabriel@alfamaweb.com.br>
 * @author     Tomaz Novaes <tomaz@alfamaweb.com.br>
 * @author     Henrique Feitosa <henriquef@alfamaweb.com.br>
 * @author     Jefersson Nathan <jeferssonn@alfamaweb.com.br>
 *
 * @package    Oráculo
 * @copyright  Copyright (c) 2014 Alfama Web (http://alfamaweb.com.br)
 * @license    Proprietary AlfamaWeb
 * @version    $Id$
 */
class Folhas_Registros_Diplomas extends Core
{

    /** Main `table` used in operations in this class */
    const CURRENT_TABLE = 'folhas_registros_diplomas';

    /**
     * @var \Zend_Db_Select
     */
    private $_db;


    /**
     * @var \Matriculas
     */
    private $_matriculationInstance;


    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->_db = new Zend_Db_Select(new Zend_Db_Mysql);
        $this->_matriculationInstance = new Matriculas;
    }

    /**
     *
     * Cancel a matriculation
     *
     * @param  integer $idPaper
     * @param  integer $idMatriculation to update situation
     *
     * @return void    Redirect
     */
    public function actionCancelarMatricula($idPaper, $idMatriculation) {

        $fields = array(
            'cancelado' => '"S"'
        );

        $update = $this->_db->reset()
                            ->update(
                                self::CURRENT_TABLE . '_matriculas',
                                $fields,
                                array(
                                    'idfolha_matricula = '.$idMatriculation,
                                    'idfolha = '.$idPaper
                                )
                            );

        $result = $this->executaSql((string) $update);

        if ($result) {
            $this->set('pro_mensagem_idioma', 'cancelado_sucesso')
                 ->set('url', Request::url('0-4', '/').'diplomas')
                 ->processando();
        }
    }


    /**
     * Action
     *
     * @return void
     */
    public function actionRemover()
    {
        global $perfil;

        $this->verificaPermissao($perfil['permissoes'], Request::url(3) . '|4');
        $this->set('post', $_POST);
        $remover = $this->remover();

        if ($remover['sucesso']) {
            $this->set('pro_mensagem_idioma', 'remover_sucesso')
                 ->set('url', Request::url('0-4', '/'))
                 ->processando();
        }
    }

    /**
     * Action
     *
     * @return void
     */
    public function actionSalvar()
    {
        global $perfil;

        $this->verificaPermissao($perfil['permissoes'], Request::url(3) . '|2');
        $this->set('post', $_POST);

        if ($_POST[$this->config['banco']['primaria']]) {
            $salvar = $this->modificar();
        } else {
            $salvar = $this->cadastrar();
        }

        if ($salvar['sucesso']) {
            if ($_POST[$config['banco']['primaria']]) {
                $this->set('pro_mensagem_idioma', 'modificar_sucesso')
                     ->set('url', Request::url('0-6', '/'));
            } else {
                $this->set('pro_mensagem_idioma', 'cadastrar_sucesso')
                     ->set('url',  Request::url('0-3', '/'));
            }
            $this->processando();
        }

        return $salvar;
    }

    public function clonar($idFolha)
    {
        $array_ignora = [
                'data_cad',
                'ativo',
                'idfolha',
                'idfolha_clone',
        ];

        $this->sql = 'SELECT * FROM folhas_registros_diplomas WHERE idfolha = ' . $idFolha . ' AND ativo = "S"';
        $linha = $this->retornarLinha($this->sql);

        $this->sqlDiplomas = 'SELECT * FROM folhas_registros_diplomas_matriculas WHERE idfolha = ' . $idFolha . ' AND ativo = \'S\' ORDER BY data_cad DESC LIMIT 1';
        $folhaDiplomaRef = $this->retornarLinha($this->sqlDiplomas);

        $linha['nome'] = $linha['nome'];
        $linha['clone'] = $linha['clone'] . '  CLONE DO ID: ' . $idFolha;
        $linha['numero_livro']++;
        $linha['numero_ordem']++;

        if(! empty($folhaDiplomaRef)){
            $linha['numero_ordem'] = $folhaDiplomaRef['numero_ordem'];
            $linha['numero_ordem']++;
        }

        $linha['numero_registro']++;
        $linha['numero_relacao']++;
        $linha['numero_folha']++;


        $this->sql = 'INSERT INTO folhas_registros_diplomas SET
            ativo = "S",
            data_cad = NOW(),
            idfolha_clone = ' . $idFolha;
        $this->sql .= $this->estruturaClone($linha,$array_ignora);
        $salvar = $this->executaSql($this->sql);
        $idfolha = mysql_insert_id();

        if ($salvar) {
            $retorno['id'] = $idfolha;
            $retorno['sucesso'] = true;

            $this->monitora_qual = $idfolha;
            $this->monitora_oque = 7;
            $this->monitora_onde = 172;
        } else {
            $retorno['erro'] = true;
            $retorno['erros'][] = $this->sql;
            $retorno['erros'][] = mysql_error();
        }

        return $retorno;
    }

    public function estruturaClone($array, $ignora)
    {
        $text = '';
        foreach ($array as $ind => $val) {
            if (!in_array($ind,$ignora)) {
                if (is_null($val)) {
                    $text.=  ' ,
                    ' . $ind . ' =  NULL ';
                } else {
                    $text .=  ' ,
                    ' . $ind . ' =  "' . mysql_escape_string($val) . '"' ;
                }
            }
        }

        return $text;
    }

    /**
     * Action
     *
     * @return void
     */
    public function actionassociar_diploma()
    {
        global $perfil;

        $this->verificaPermissao($perfil['permissoes'], Request::url(3) . '|3');

        $matriculas = $_POST['matriculas'];

        if (is_array($matriculas)) {

            foreach ($matriculas as $matricula) {
                $associar = $this->associarDiploma(Request::url(4), trim($matricula));
            }

            if ($associar['associar']) {
                $this->set('pro_mensagem_idioma', 'associar_sucesso')
                     ->set('url', Request::url('0-5', '/'))
                     ->processando();
            }
        }
    }

    /**
     * Assoc a resume to a paper
     *
     * @param  integer $idfolha     Identification number of paper
     * @param  integer $idmatricula Identification number for matriculation
     * @return array                Given information
     */
    public function associarDiploma($idfolha, $idmatricula)
    {
        $idfolha     = (int) $idfolha;
        $idmatricula = (int) $idmatricula;

        $this->id = $idfolha;
        $this->campos = 'frd.limite_matriculas';
        $folhaRegistro = $this->retornar();
        $qntMatriculas = $this->retornarTotalMatriculasDaFolha($idfolha);

        if (! empty($folhaRegistro['limite_matriculas']) && $qntMatriculas >= $folhaRegistro['limite_matriculas']) {
            $_SESSION['errors'][] = 'limite_matriculas_excedido';
            return false;
        }

        $idSituacaoCurso = mysql_fetch_assoc(mysql_query("SELECT idsituacao FROM matriculas_workflow WHERE ativo = 'S' AND ativa = 'S'"));

        $sqlEmCurso = " SELECT
                            data_cad
                        FROM
                            matriculas_historicos
                        WHERE
                            tipo = 'situacao' AND
                            para = ".$idSituacaoCurso['idsituacao']." AND
                            idmatricula = ".$idmatricula."
                        ORDER BY data_cad DESC";

        $dataEmCurso = mysql_fetch_assoc(mysql_query($sqlEmCurso));
        $dataEmCurso = $dataEmCurso['data_cad'];

        $hasDiploma = $this->_matriculationInstance->hasDiploma($idmatricula);

        if ($hasDiploma['total']) {
            $hasDiploma['idmatricula'] = $idmatricula;
            $hasDiploma['aluno'] = $this->_matriculationInstance->getStudentName($idmatricula);
            $_SESSION['errors'][] = $hasDiploma;
            return false;
        }

        $folha       = $this->getRowByIdFolha($idfolha);
        $lastDiploma = $this->getMatriculationByIdFolha($idfolha);
        $count = mysql_fetch_object(
            mysql_query(
                sprintf(
                    'SELECT COUNT(*) as total FROM %s WHERE idfolha = %d',
                    self::CURRENT_TABLE. '_matriculas',
                    $idfolha
                )
            )
        );
        $numero_ordem    = $folha['numero_ordem'];
        $numero_registro = $folha['numero_registro'];
        if (intval($lastDiploma['numero_ordem']) >= 0) {
            $numero_ordem    = $folha['numero_ordem'] + $count->total;
        }
        if (intval($lastDiploma['numero_registro']) >= 0) {
            $numero_registro = $folha['numero_registro'] + $count->total;
        }
        $verifyMatriculation = $this->_matriculationInstance->getMatricula($idmatricula);
        $verifyMatriculation = (is_array($verifyMatriculation)) ? count($verifyMatriculation) : 0;

        $sql = "SELECT * FROM folhas_registros_diplomas_matriculas WHERE idfolha='" . (int) $idfolha . "' AND idmatricula='" . (int) $idmatricula . "' and cancelado ='N'";
        $verifyDuplicity = mysql_query($sql) or die(mysql_error());

        if (($verifyMatriculation >  0)
                && mysql_num_rows($verifyDuplicity) == 0) {

            $dataCurso = 'NOW()';

            $dataAtual = new \DateTime();
            $dataCurso = "'".$dataAtual->format('Y-m-d H:i:s')."'";

            $insert = $this->_db->insert(self::CURRENT_TABLE . '_matriculas', array(
                'idfolha' => $idfolha,
                'ativo' => $this->_db->quoteIdentifier('S'),
                'data_cad' => $dataCurso,
                'idmatricula' => $idmatricula,
                'cancelado' => $this->_db->quoteIdentifier('N'),
                'numero_ordem' => $numero_ordem,
                'numero_registro' => $numero_registro
            ));

            $this->executaSql((string) $insert);
            $idFolhaMatricula = mysql_insert_id();
            $codigoValidacao = md5($idFolhaMatricula);
            $this->sql = "UPDATE folhas_registros_diplomas_matriculas
                        SET cod_validacao = '" . $codigoValidacao . "'
                        WHERE idfolha_matricula = " . $idFolhaMatricula;
            $this->retornarLinha($this->sql);

            $this->sql = "UPDATE matriculas
                        SET data_expedicao = DATE(NOW())
                        WHERE idmatricula = " . $idmatricula;
            $this->executaSql($this->sql);
        }
        return true;
    }

    /**
     * Storage information on database `mysql`
     *
     * @return mixed
     */
    public function cadastrar()
    {
        // If you has in production server a PHP 5.3+
        // Remove it and use DateTime::createFromFormat($format, $dateString);
        $dateInfo = explode('/', $_POST['data_expedicao']);
        $date = $dateInfo[2] . $dateInfo[1] . $dateInfo[0];

        $date = new DateTime($date);
        $_POST['data_expedicao'] = $date->format('Y-m-d');

        // Set on object for persist on `database`
        $this->set('post', $_POST);

        return $this->salvarDados();
    }

    /**
     * Nothing to see here.
     *
     * @param  integer $idfolha           Identification number of paper
     * @param  integer $idmatricula       Identification number of matriculation
     * @param  integer $idfolha_matricula Identification number of matriculation's paper
     * @return boolean                    Even true
     */
    public function cancelarDiploma($idfolha, $idmatricula, $idfolha_matricula)
    {
        return true;
    }

    /**
     * Call a action `to do` by Post
     *
     * @param  string $action Name of action and method in this class
     * @return mixed          Return the method return
     */
    public function doAction($action)
    {
        $action = 'action'.$action;

        if (method_exists($this, $action)) {
            return $this->$action();
        }
    }

    /**
     * Return all errors stored on `$_SESSION['errors']`
     *
     * @return array
     */
    public function getErrors()
    {
        return $_SESSION['errors'];
    }

    /**
     * Return a row from table `folhas_registros_diplomas_matriculas`
     * identified by `idfolha`
     *
     * @param  integer $idfolha Referer to get information
     * @param null $idmatricula
     *
     * @return array            Information of matriculation
     */
    public function getMatriculationByIdFolha($idfolha, $idmatricula = null)
    {
        $db = $this->_db->reset()
                        ->select()
                        ->from('folhas_registros_diplomas_matriculas')
                        ->where('idfolha = ?', $idfolha)
                        ->order('numero_registro');

        if ($idmatricula) {
            $db = $db->where('idmatricula = ?', $idmatricula);
        }

        $sql = (string) $db . ' LIMIT 1';
        $selectDiploma = mysql_query($sql) or die(mysql_error());

        return mysql_fetch_assoc($selectDiploma);
    }

    /**
     * Return a row from `self::CURRENT_TABLE` identified by `idfolha`
     *
     * @param  integer $idfolha Referer to get information
     * @return array            Information of matriculation
     */
    public function getRowByIdFolha($idfolha)
    {
        $db = $this->_db->reset()
                        ->select()
                        ->from(self::CURRENT_TABLE)
                        ->where('idfolha = ?', $idfolha);

        $sql = (string) $db;

        $selectPaper = mysql_query($sql) or die(mysql_error());

        return mysql_fetch_assoc($selectPaper);
    }

    /**
     * Return `all` rows of table `self::CURRENT_TABLE`
     */
    public function listarTodas()
    {

        $this->sql = "
            SELECT {$this->campos}
            FROM folhas_registros_diplomas AS frd
            INNER JOIN sindicatos AS i ON (frd.idsindicato=i.idsindicato)
            LEFT JOIN cursos_sindicatos AS ci ON (frd.idcurso_sindicato=ci.idcurso_sindicato)
            LEFT JOIN cursos AS c ON (c.idcurso = ci.idcurso)
            WHERE (frd.ativo = 'S')
            ";

        if ($_SESSION["adm_gestor_sindicato"] == 'N') {
            $select = $select->where(
                'frd.idsindicato IN('.$_SESSION['adm_sindicatos'].')'
            );
        }

        $this->aplicarFiltrosBasicos();

        $this->set('sql', (string) $select)
             ->set('groupby', 'idfolha');
        return $this->retornarLinhas();
    }

    public function coletarIdFolhaPorMatricula($idMatricula){

        try{
            if(gettype($idMatricula) != "integer"){
                throw new InvalidArgumentException("para realizar a consulta do id da folha, o valor da matrícula precisa ser um inteiro!");
            } else {
            $sql = "SELECT 
                        idfolha 
                    FROM 
                         folhas_registros_diplomas_matriculas 
                    WHERE
                        idmatricula = ${idMatricula}
                        AND ativo = 'S'
                        AND cancelado = 'N'";
            return $this->retornarLinha($sql);
            }
        } catch (InvalidArgumentException $i){
            echo "Ops! {$i->getMessage()}";
        }
    }

    public function listarSelect()
    {

        $this->sql = "
            SELECT {$this->campos}
            FROM folhas_registros_diplomas AS frd
            INNER JOIN sindicatos AS i ON (frd.idsindicato=i.idsindicato)
            LEFT JOIN cursos_sindicatos AS ci ON (frd.idcurso_sindicato=ci.idcurso_sindicato)
            LEFT JOIN cursos AS c ON (c.idcurso = ci.idcurso)
            WHERE (frd.ativo = 'S' AND frd.ativo_painel = 'S')
            ";

        if ($_SESSION["adm_gestor_sindicato"] == 'N') {
            $this->sql .= ' AND frd.idsindicato IN('.$_SESSION['adm_sindicatos'].')';
        }

        $this->aplicarFiltrosBasicos();

        $this->set('sql', (string) $select)
             ->set('groupby', 'idfolha')
             ->set('limite', -1);

        return $this->retornarLinhas();
    }


    /**
     * Modify information of a row stored on `database`
     *
     * @return mixed
     */
    public function modificar()
    {
        $qntMatriculas = $this->retornarTotalMatriculasDaFolha($_POST['idfolha']);
        $qntMatriculas = ($qntMatriculas > 0) ? $qntMatriculas : 1;
        $arrayAlterar[] = 'limite_matriculas';
        $atributos = [
            'validacao' => [
                'range>=' . $qntMatriculas => 'limite_matriculas_minimo'
            ]
        ];
        $this->config['formulario'] = $this->inserirAtributos(
            $this->config['formulario'],
            $arrayAlterar,
            $atributos
        );
        // If you has in production server a PHP 5.3+
        // Remove it and use DateTime::createFromFormat($format, $dateString);
        $dateInfo = explode('/', $_POST['data_expedicao']);
        $date = $dateInfo[2] . $dateInfo[1] . $dateInfo[0];
        $date = new DateTime($date);
        $_POST['data_expedicao'] = $date->format('Y-m-d');
        // Set on object for persist on `database`
        $this->set('post', $_POST);

        return $this->salvarDados();
    }

    /**
     * Disable a row of database.
     * Set column `ativo` to receive value `N`
     *
     * @return mixed
     */
    public function remover()
    {
    	$query = sprintf(
    		'SELECT COUNT(*) as quantity FROM %s WHERE idfolha = %d AND cancelado = "N"',
    		self::CURRENT_TABLE. '_matriculas',
    		$_POST['remover']
    	);

    	$rows = $this->retornarLinha($query);

    	if ($rows['quantity']) {
    		$this->set('pro_mensagem_idioma', 'matriculas_ativas')
				  ->set('url', Request::url('0-5', '/') . 'paginas')
				  ->Processando();
    	}

        $this->desvincularOferta($_POST['remover']);

        return $this->removerDados();
    }

    public function temOferta($idfolha){

        $select = "SELECT
                        o.nome,
                        oc.* 
                    FROM
                        ofertas_cursos oc
                        INNER JOIN ofertas o ON ( o.idoferta = oc.idoferta ) 
                    WHERE
                        oc.idfolha ='$idfolha'  
                        AND o.ativo = 'S' 
                        AND oc.ativo = 'S';";

        $ofertas = $this->retornarLinhasArray($select);

        return $ofertas;
    }
    
    public function desvincularOferta($idfolha){

        $sql = "UPDATE ofertas_cursos 
                SET idfolha = NULL 
                WHERE
                    idfolha = '$idfolha';";

        return   $this->executaSql($sql);
    }

    public function folhaAtiva($idfolha){

      $sql = "SELECT
                ativo 
            FROM
                `folhas_registros_diplomas` 
            WHERE
                idfolha = '$idfolha'
                and ativo = 'S'";

      $resultado = $this->retornarLinha($sql);

      return $resultado;
    }

    /**
     * retorna os dados de uma folha de registro do banco de dados
     *
     * <code>
     * $obj = new Folhas_Registros_Diplomas();
     * $row = $obj->set('campos', '*')
     *            ->set('id', 1)
     *            ->retornar();
     *
     * print_r($row);
     * </code>
     *
     * @return array dados da folha de registro
     */
    public function retornar()
    {
        $select = $this->_db->reset()
                            ->select()
                            ->from(array('frd' => self::CURRENT_TABLE),
                                   array($this->campos))
                            ->join(
                                array('i' => 'sindicatos'),
                                    '(frd.idsindicato=i.idsindicato)',
                                    array() //Obs.: Necessário array vazio para não trazer nenhuma coluna da tabela
                             )
                            ->joinLeft(
                                array('ci' => 'cursos_sindicatos'),
                                    '(frd.idcurso_sindicato=ci.idcurso_sindicato)',
                                    array()
                            )
                            ->joinLeft(
                                array('c' => 'cursos'),
                                    '(c.idcurso = ci.idcurso)',
                                array('c.idcurso, c.nome as curso')
                            )
                            ->where('frd.ativo = ?', 'S')
                            ->where('frd.idfolha = ?', $this->get('id'));
        return $this->set('sql', (string) $select)->retornarLinha($this->sql);
    }

    /**
     * Return a array with information about paper with `idfolha` on `iddiploma`
     *
     * @param  integer $idfolha   Identification number of paper
     * @param  integer $iddiploma Identification nimber of diploma
     * @return array
     */
    public function retornarDiploma($idfolha, $iddiploma)
    {
        $sql = $this->_db->reset()
                         ->select()
                         ->from(array('frdm' => self::CURRENT_TABLE . '_matriculas'))
                         ->join(array('frd' => self::CURRENT_TABLE),
                                '(frd.idfolha=frdm.idfolha)',
                                array('data_expedicao', 'numero_relacao'))
                         ->join(array('mat' => 'matriculas'),
                                '(frdm.idmatricula=mat.idmatricula)')
                         ->join(array('pes' => 'pessoas'),
                                '(mat.idpessoa=pes.idpessoa)',
                                array('pessoa' => 'nome', 'rg'))
                         ->join(array('pol' => 'escolas'),
                                '(mat.idescola=pol.idescola)',
                                array('escola' => 'nome_fantasia'))
                         ->join(array('cur' => 'cursos'),
                                '(mat.idcurso=cur.idcurso)',
                                array('curso' => 'nome'))
                         ->join(array('ofe' => 'ofertas'),
                                '(mat.idoferta=ofe.idoferta)',
                                array('oferta' => 'nome'))
                         ->join(array('tur' => 'ofertas_turmas'),
                                '(mat.idturma=tur.idturma)',
                                array('turma' => 'nome'))
                         ->where('frdm.idfolha_matricula = ?', $iddiploma)
                         ->where('frdm.idfolha = ?', $idfolha)
                         ->where('frdm.ativo = ?', 'S');

        return $this->retornarLinha($sql);
    }

    /**
     * Retorna um diploma
     *
     * @return array information of diploma
     */
    public function retornarDiplomas()
    {
        $this->sql = "SELECT
						{$this->campos},
						pes.nome as nome_aluno
					FROM
						folhas_registros_diplomas_matriculas frdm
                    INNER JOIN `".self::CURRENT_TABLE."` frd ON (frd.idfolha=frdm.idfolha)
                    LEFT JOIN cursos_sindicatos ci ON (frd.idcurso_sindicato=ci.idcurso_sindicato)
                    LEFT JOIN cursos c ON (c.idcurso = ci.idcurso)
                    INNER JOIN matriculas mat ON (frdm.idmatricula=mat.idmatricula)
                    INNER JOIN pessoas pes ON (mat.idpessoa=pes.idpessoa)
                    INNER JOIN escolas pol ON (mat.idescola=pol.idescola)
                    INNER JOIN cursos cur ON (mat.idcurso=cur.idcurso)
                    INNER JOIN ofertas ofe ON (mat.idoferta=ofe.idoferta)
                    INNER JOIN ofertas_turmas tur ON (mat.idturma=tur.idturma)
                WHERE frdm.idfolha='{$this->id}' AND frdm.ativo = 'S'";

        if (is_array($_GET["q"])) {
            foreach ($_GET["q"] as $campo => $valor) {
                //explode = Retira, ou seja retira a "|" da variavel campo
                $campo = explode("|", $campo);
                $valor = str_replace("'", "", $valor);
                // Listagem se o valor for diferente de Todos ele faz um filtro
                if (($valor || $valor === "0") and $valor <> "todos") {
                    // se campo[0] for = 1 é pq ele tem de ser um valor exato
                    if ($campo[0] == 1) {
                        $this->sql .= " and " . $campo[1] . " = '" . $valor . "' ";
                        // se campo[0] for = 2, faz o filtro pelo comando like
                    } elseif ($campo[0] == 2) {
                        $busca = str_replace("\\'", "", $valor);
                        $busca = str_replace("\\", "", $busca);
                        $busca = explode(" ", $busca);
                        foreach ($busca as $ind => $buscar) {
                            $this->sql .= " and " . $campo[1] . " like '%" . urldecode($buscar) . "%' ";
                        }
                    } elseif ($campo[0] == 3) {
                        $this->sql .= " and date_format(" . $campo[1] . ",'%d/%m/%Y') = '" . $valor . "' ";
                    }
                }
            }
        }

        $this->groupby = "idfolha_matricula";
        return $this->retornarLinhas();
    }

    /**
     * Return a JSON from tags finded
     *
     * @param  string $tag sentence to search
     * @param $idsindicato
     *
     * @return string      json formated
     */
    public function searchBy($tag, $idsindicato, $idcurso)
    {
        if($idcurso){
            $findBy = $this->_db->reset()
                    ->select()
                    ->from('matriculas')
                    ->join('pessoas',
                           '(pessoas.idpessoa = matriculas.idpessoa)')
                    ->where('(matriculas.idmatricula LIKE ? OR pessoas.nome LIKE ?)', '%'.$tag.'%')
                    ->where('matriculas.idsindicato = ?', $idsindicato)
                    ->where('matriculas.idcurso = ?',$idcurso );
        } else {
            $findBy = $this->_db->reset()
                    ->select()
                    ->from('matriculas')
                    ->join('pessoas',
                           '(pessoas.idpessoa = matriculas.idpessoa)')
                    ->where('(matriculas.idmatricula LIKE ? OR pessoas.nome LIKE ?)', '%'.$tag.'%')
                    ->where('matriculas.idsindicato = ?', $idsindicato);
        }
        $query = mysql_query((string) $findBy);

        $_content = array();
        while ($row = mysql_fetch_assoc($query)) {
            $_temp = null;
            $_temp['key'] = $row['idmatricula'];
            $_temp['value'] = '('.$row['idmatricula'].') ' . $row['nome'];
            $_content[] = (object) $_temp;
        }

        return $_content;
    }

    /**
     * Return a `StdClass` object with information about finded column or `false`
     *
     * @param string $column from database
     *
     * @throws InvalidArgumentException
     * @return \StdClass with informations from database
     */
    public function getWorkflowIdFor($column)
    {
        if (null === $column) {
            throw new InvalidArgumentException('Is mandatory a column name as parameter!');
        }

        $situation = $this->_db->reset()
                               ->select()
                               ->from('matriculas_workflow')
                               ->where($column.' = ?', 'S');

        return (object) $this->retornarLinha((string) $situation);
    }

	public function retornarMatriculasDaFolha($idfolha, $idmatricula) {

		$this->sql = 'select
						frdm.*
					from
						folhas_registros_diplomas frd
						inner join folhas_registros_diplomas_matriculas frdm on (frd.idfolha = frdm.idfolha)
					where
						frd.idfolha = '.$idfolha.' and
						frdm.idmatricula = '.$idmatricula.' and
						frdm.ativo = "S" and
						frdm.cancelado = "N"';

		return $this->retornarLinha($this->sql);
    }

	public function retornarTotalMatriculasDaFolha($idfolha) {

		$this->sql = 'select
						count(1) as total
					from
						folhas_registros_diplomas_matriculas
					where
						idfolha = '.$idfolha.' and
						ativo = "S" and
						cancelado = "N"';

		$total = $this->retornarLinha($this->sql);
		return $total['total'];
    }

    public function retornarCursosSindicato() {
        $this->sql = "SELECT ci.idcurso_sindicato, c.nome
                      FROM cursos_sindicatos ci INNER JOIN cursos c ON c.idcurso = ci.idcurso
                      WHERE ci.idsindicato =  '".$this->id."'";
        $query = $this->executaSql($this->sql);
        $this->retorno = array();
        while($row = mysql_fetch_assoc($query)){
            $this->retorno[] = $row;
        }
        echo json_encode($this->retorno);
    }

}
