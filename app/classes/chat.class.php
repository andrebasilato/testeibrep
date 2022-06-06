<?php
/**
 * Chat
 *
 * @package Oráculo
 * @author  Jefersson Nathan <jeferssonn@alfamaweb.com.br>
 *
 * @license (c) AlfamaWeb 2014
 */
class Chat
{
    const PRIMARY_KEY = 'idchat';
    const CURRENT_TABLE = 'avas_chats';
    const CHAT_MESSAGES = 'chats_mensagens';

    /** @var Core object */
    private $_core;

    /** @var array menssagens ou categorias */
    private $_data;

    /** @var integer id do usuário logado */
    private $_userId;

    /** @var integer id do chat atual */
    private $_chatId;

    /** @var integer tipo de usuário */
    private $_userType = 0;

    /**
     * Guarda uma instancia da classe core, que será ultilizada como auxilio
     * para operações destrutivas no banco. (INSERT, UPDATE, DELETE, ETC)
     *
     * @param Core $core
     *
     * @return \Chat
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function __construct(Core $core)
    {
        $this->_core = $core;
    }

    /**
     * Identifica o usuário de acordo com o seu tipo
     *
     * Tipos permitidos
     *
     * Array (
     *     0 => Alunos
     *     1 => Professores
     * )
     *
     * @param $type
     *
     * @return $this
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function setTypePerson($type)
    {
        $this->_userType = (int)$type;
        return $this;
    }

    /**
     * Chama métodos da classe core, a  partir da instancia passada ao construtor
     *
     * @param $method
     * @param $args
     *
     * @return mixed
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function __call($method, $args)
    {
        return call_user_func_array(array($this->_core, $method), $args);
    }

    /**
     * Guarda o Id do usuário no objeto, para ser usado
     * nas querys e possivélmente em outras coisas
     *
     * @param integer $userId id do usuário
     *
     * @return $this
     * @api
     */
    public function setId($userId)
    {
        $this->_userId = (int)$userId;
        return $this;
    }

    /**
     * Registra uma nova mensagem
     *
     * @param null $idmatricula
     *
     * @return void
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function registerNewMessage($idmatricula = NULL)
    {
        if (verificaPermissaoAcesso(true)) {
            if ($this->_userType == 0) {
                $this->contabilizar($idmatricula);
            }

            $query = 'INSERT INTO ' . self::CHAT_MESSAGES . '
                        SET mensagem ="' . mysql_real_escape_string($_POST['mensagem']) . '",
                        idpessoa = ' . $this->_userId . ',
                        usuario_tipo = ' . $this->_userType . ',
                        idchat = ' . (int)$this->_chatId;

            $this->_core->executaSql($query);
            $this->_core->salvarDados();
        }
    }

    /**
     * Informação sobre um chat específico
     *
     * @param $chatId
     *
     * @return array
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     *
     * @api
     */
    public function infoAbout($chatId)
    {
        // Dados do chat
        $result['dados'] = $this->_core->retornarLinha(
            'SELECT * FROM ' . self::CURRENT_TABLE . ' WHERE idchat =' . $chatId
        );

        // Pessoas do chat
        $sql = 'SELECT DISTINCT b.idpessoa, p.nome, p.avatar_servidor as avatar
                 FROM `' . self::CHAT_MESSAGES . '` b
          INNER JOIN pessoas p ON p.idpessoa =  b.idpessoa
                    WHERE b.usuario_tipo = 0 AND b.idchat = ' . $chatId;

        $this->_core->set('limite', -1)
            ->set('ordem', 'asc')
            ->set('ordem_campo', 'p.nome');

		$result['pessoas'] = $this->_core->retornarLinhas(
            $this->_core->set('sql', $sql)
        );

        $sql = 'SELECT DISTINCT b.idpessoa, p.nome, p.avatar_servidor as avatar
                 FROM `' . self::CHAT_MESSAGES . '` b
          INNER JOIN professores p ON p.idprofessor =  b.idpessoa
                    WHERE b.usuario_tipo = 1 AND b.idchat = ' . $chatId;

        $this->_core->set('limite', -1)
            ->set('ordem', 'asc')
            ->set('ordem_campo', 'p.nome');

		// Professores do chat
        $result['professores'] = $this->_core->retornarLinhas(
            $this->_core->set('sql', $sql)
        );

        foreach ($result['pessoas'] as $key => $value) {
            $pessoas[$value['idpessoa']] = $value['nome'];
            $pessoas[$value['idpessoa'] . '_avatar'] = $value['avatar'];
        }

        foreach ($result['professores'] as $key => $value) {
            $professores[$value['idpessoa']] = $value['nome'];
            $professores[$value['idpessoa'] . '_avatar'] = $value['avatar'];
        }

        $result['pessoas'] = $pessoas;
        $result['professores'] = $professores;
        return $result;
    }

    /**
     * Identifica e guarda no objeto o id de um chat, para onde as operações
     * serão destinadas
     *
     * @param $chatId
     *
     * @return object Chat
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     *
     * @api
     */
    public function setChatId($chatId)
    {
        $this->_chatId = (int)$chatId;
        return $this;
    }

    /**
     * Usa o id atual para fazer as operações
     *
     * @return object Chat
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     *
     * @api
     */
    public function useCurrentId()
    {
        global $usu_professor;

        $this->_userId = (int)$usu_professor['idprofessor'];
        return $this;
    }

    /**
     * Lista todas as conversas cadastradas no oráculo
     *
     * @param null $id
     * @param int $type
     *
     * @return object Chat
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     *
     * @api
     */
    public function allConversation($id = null, $type = 0)
    {
        if (!is_array($id)) {

            $query = 'SELECT * FROM `' . self::CURRENT_TABLE . '` WHERE ';

            if ($id) {
                $query .= ' idava = ' . $id . ' AND ';
            }

            $query .= ' ativo = "S"';

        } else {
            if (!$id && $id !== null) {
                $this->_data = array();
                return $this;
            }

            $id = array_unique($id);

            $query = 'SELECT * FROM `' . self::CURRENT_TABLE . '`
                WHERE idava IN (' . implode(', ', $id) . ')
                           AND ativo = "S"';
        }

        $query .= ' AND exibir_ava = "S"';

        if ($this->_userType == 0) {
            $query .= ' AND (inicio_campanha IS NULL OR inicio_campanha < NOW())';

			if ($type)
				$query .= ' AND (fim_entrada_aluno IS NULL OR fim_entrada_aluno > NOW())';
			/*if (!$type) {
                $query .= ' AND (fim_entrada_aluno IS NULL OR fim_entrada_aluno > NOW())';
            } else {
                $query .= ' AND idava = (
                    SELECT idava FROM avas_disciplinas
                        WHERE idava_disciplina = ' . $type . '
                )';
            }*/
        }

        $query .= $this->applySearchFilters();

        $this->_core->set('sql', $query)
            ->set('campos', '*')
            ->set('ordem_campo', isset($_GET['cmp']) ? $_GET['cmp'] : self::PRIMARY_KEY)
            ->set('limite', isset($_GET['qtd']) ? $_GET['qtd'] : 30)
            ->set('pagina', isset($_GET['pag']) ? $_GET['pag'] : 1)
            ->set('ordem', isset($_GET['ord']) ? $_GET['ord'] : 'DESC')
            ->set('groupby', '*');

        $this->_data = $this->_core->retornarLinhas();

        return $this;
    }

    /**
     * Rederiza um template html na tela do browser
     *
     * @param  string $template caminho para o arquivo de template
     * @param  array $dados array com dados usados no template, as chaves do
     *                          array viram variaveis "soltas"
     *
     * @return void
     */
    public function renderTo($template, array $dados = null)
    {
        extract($dados);

        if (file_exists($template)) {
            require $template;
        }
    }

    /**
     * Retorna mensagens que interferem na cateogia em questão, ele é vital para
     * a aplicação funcionar.
     *
     * @return object Chat
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function allMessages()
    {
        $query = 'SELECT * FROM `' . self::CHAT_MESSAGES . '` WHERE idchat = ' . $this->_chatId;

        $this->_core->set('sql', $query)
            ->set('campos', '*')
			->set('limite', -1)
            ->set('ordem', 'DESC')
            ->set('ordem_campo', 'idchat_mensagem')
            ->set('groupby', '*');

        $this->_data = $this->_core->retornarLinhas();

        return $this;
    }

    /**
     * Pega os registros com informações maior que o parametro $lastId
     *
     * @param  integer Id da ultima mensagem vista pelo usuário
     *
     * @return void
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function getMoreThen($lastId)
    {
        if (!$lastId) {
            $this->_data = array();
            return $this;
        }

        $query = 'SELECT DISTINCT a.idchat_mensagem, a.*, DATE_FORMAT(a.data_cad,"%d/%m/%Y às %Hh%i") AS data_cad, (
                CASE
                    WHEN a.usuario_tipo = 0 THEN
                        (SELECT nome FROM pessoas p WHERE p.idpessoa = a.idpessoa)
                    WHEN a.usuario_tipo = 1 THEN
                        (SELECT nome FROM professores p where p.idprofessor = a.idpessoa)
                    ELSE NULL END
                ) as nome,(
                CASE
                    WHEN a.usuario_tipo = 0 THEN
                        (SELECT avatar_servidor FROM pessoas p WHERE p.idpessoa = a.idpessoa)
                    WHEN a.usuario_tipo = 1 THEN
                        (SELECT avatar_servidor FROM professores p where p.idprofessor = a.idpessoa)
                    ELSE NULL END
                ) as avatar
                        FROM `' . self::CHAT_MESSAGES . '` a
                    WHERE a.idchat = ' . $this->_chatId . '
                    AND a.idchat_mensagem > ' . $lastId . '
                    GROUP BY a.idchat_mensagem';

        $this->_core->set('sql', $query)
            ->set('campos', 'a.*, p.nome')
            ->set('ordem', 'DESC')
            ->set('ordem_campo', 'a.idchat_mensagem')
            ->set('groupby', '*');

        $this->_data = $this->_core->retornarLinhas();

        return $this;
    }

    /**
     * Retorna o resultado do ultimo recurso esperado
     *
     * @return array
     */
    public function getResult()
    {
        return $this->_data;
    }

    public function isValidPeriod()
    {
        return true;
    }

    /**
     * Lista dos IDS dos cursos referentes a uma matricula ou
     * matricula & disciplina
     *
     * @param $idmatricula
     * @param null $idcomp
     *
     * @return array
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function getCourses($idmatricula, $idcomp = null)
    {

        $query = 'SELECT

        *

        FROM matriculas AS m

        INNER JOIN cursos AS c
        ON c.idcurso = m.idcurso
        AND c.ativo = "S"

        INNER JOIN curriculos AS cv
        ON cv.idcurso = c.idcurso
        AND cv.ativo = "S"

        INNER JOIN curriculos_blocos AS cvb
        ON cvb.idcurriculo = cv.idcurriculo
        AND cvb.ativo = "S"

        INNER JOIN curriculos_blocos_disciplinas AS cbd
        ON cbd.idbloco = cvb.idbloco';

        if ($idcomp) {
            $query .= ' AND cbd.idbloco_disciplina = ' . $idcomp;
        }

        $query .= ' AND cbd.ativo = "S"


        INNER JOIN ofertas_curriculos_avas AS oca
            ON oca.ativo = "S"
                AND oca.iddisciplina = cbd.iddisciplina
                AND oca.idcurriculo = cvb.idcurriculo
                AND oca.idoferta = m.idoferta

        WHERE m.idmatricula = ' . $idmatricula;

        // echo $query;

        $exec = mysql_query($query) or die(mysql_error());

        $_cursos = array();
        while ($row = mysql_fetch_assoc($exec)) {
            if (!empty($row['idava'])) {
                $_cursos[] = $row['idava'];
            }
        }

        return $_cursos;
    }

    /**
     * Aplica filtros de busca á listagem
     *
     * @return string
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function applySearchFilters()
    {
        if (is_array($_GET['q'])) {

            foreach ($_GET['q'] as $campo => $valor) {

                $campo = explode('|', $campo);
                $valor = str_replace('\'', '', $valor);

                if (($valor || $valor === "0") and $valor <> "todos") {

                    if ($campo[0] == 1) {
                        $this->sql .= " and " . $campo[1] . " = '" . $valor . "' ";

                    } elseif ($campo[0] == 2) {

                        $busca = str_replace("\\'", '', $valor);
                        $busca = str_replace("\\", '', $busca);
                        $busca = explode(' ', $busca);

                        foreach ($busca as $ind => $buscar) {
                            $this->sql .= " and " . $campo[1] . " like '%" . urldecode($buscar) . "%' ";
                        }

                    } elseif ($campo[0] == 3) {
                        $this->sql .= " and date_format(" . $campo[1] . ",'%d/%m/%Y') = '" . $valor . "' ";
                    }
                }
            }
        }
        return $this->sql;
	}

	public function contabilizar($idmatricula) {
		if (verificaPermissaoAcesso(false)) {
			$sql = "select idava from avas_chats where idchat = " . $this->_chatId;
            $ava = $this->_core->retornarLinha($sql);

			$sql = "SELECT count(*) as total FROM matriculas_rotas_aprendizagem_objetos WHERE idmatricula = ".$idmatricula." and idava = ".$ava['idava']." and idchat = ".$this->_chatId;
			$verifica = $this->_core->retornarLinha($sql);
			if($verifica["total"] <= 0) {
				$this->_core->executaSql("BEGIN");

				$sql = "SELECT porcentagem_chat FROM avas WHERE idava = ".$ava['idava'];
				$porcentagem = $this->_core->retornarLinha($sql);
				if(!$porcentagem['porcentagem_chat']) $porcentagem['porcentagem_chat'] = 0;

				$sql = "INSERT INTO
						matriculas_rotas_aprendizagem_objetos
					  SET
						data_cad = now(),
						idmatricula = ".$idmatricula.",
						idava = ".$ava['idava'].",
						idchat = ".$this->_chatId.",
						porcentagem = ".$porcentagem['porcentagem_chat'];
				if ($this->_core->executaSql($sql)) {
					$this->_core->sql = 'SELECT
									idmatricula_ava_porcentagem,
									porcentagem,
									COUNT(*) AS total
								FROM
									matriculas_avas_porcentagem
								WHERE
									idmatricula = '.$idmatricula.' AND
									idava = '.$ava['idava'];
					$verificaPorcentagem = $this->_core->retornarLinha($this->_core->sql);
					if (!$verificaPorcentagem['total']) {
						$this->_core->sql = 'INSERT INTO matriculas_avas_porcentagem SET idmatricula = '.$idmatricula.', idava = '.$ava['idava'].', porcentagem = '.$porcentagem['porcentagem_chat'];
					} else {
						$this->_core->sql = 'UPDATE
										matriculas_avas_porcentagem
									SET
										porcentagem = porcentagem = IF((porcentagem + '.$porcentagem['porcentagem_chat'].') > 100, 100, (porcentagem + '.$porcentagem['porcentagem_chat'].'))
									WHERE
										idmatricula_ava_porcentagem = '.$verificaPorcentagem['idmatricula_ava_porcentagem'];
					}

					if ($this->_core->executaSql($this->_core->sql)) {
						$this->_core->sql = 'UPDATE matriculas SET porcentagem = IF((porcentagem + '.$porcentagem['porcentagem_chat'].') > 100, 100, (porcentagem + '.$porcentagem['porcentagem_chat'].')) WHERE idmatricula = '.$idmatricula;
						if ($this->_core->executaSql($this->_core->sql)) {
							$this->_core->executaSql("COMMIT");
						} else {
							$this->_core->executaSql("ROLLBACK");
						}
					} else {
						$this->_core->executaSql("ROLLBACK");
					}
				} else {
					$this->_core->executaSql("ROLLBACK");
				}
			}

			return true;
		}
	}

}