<?php

namespace AwOra\orio;

require_once 'RequisicaoJson.php';

class GestaoTokens extends \Core
{
    const TABELA = 'orio_tokens';
    const CHAVE_PRIMARIA = 'tk.idtoken';

    private $coluna;
    private $tabela_aux;
    private $coluna_aux;
    private $usuarioLogado;

    public function __construct()
    {
        parent::__construct();

        if (! self::TABELA || ! self::CHAVE_PRIMARIA) {
            throw new \Exception('parametros_nao_configurados', StatusCode::BAD_REQUEST);
        }
    }

    public function remover()
    {
        return $this->removerDados();
    }

    private function aplicarFiltros()
    {
        foreach ($_GET["q"] as $campo => $valor) {
            $campo = explode("|", $campo);
            $valor = str_replace("'", "", $valor);

            if (($valor || $valor === "0") && $valor <> "todos") {
                switch ($campo[0]) {
                    // filtro pelo valor exato
                    case 1:
                        $this->sql .= "AND " . $campo[1] . " = '" . $valor . "' ";
                        break;
                    // filtro pelo comando like
                    case 2:
                        $busca = str_replace("\\'", "", $valor);
                        $busca = str_replace("\\", "", $busca);
                        $busca = explode(" ", $busca);
                        
                        foreach ($busca as $ind => $buscar) {
                            $this->sql .= "AND " . $campo[1] . " LIKE '%" . urldecode($buscar) . "%' ";
                        }

                        break;
                    // filtros personalizados
                    case 999:
                        switch ($campo[1]) {
                            case 'dados_usuario':
                                $this->sql .= "AND (";
                                $this->sql .= " ua.nome LIKE '%"
                                    . urldecode($valor) . "%' OR uat.email LIKE '%" . urldecode($valor) . "%'";
                                $this->sql .= " ) ";
                                break;
                        }
                        break;
                }
            }
        }
    }

    private function retornarQueryPadrao()
    {
        $this->sql = "
            SELECT {$this->campos},
                ua.nome as gestor, ua.email as usuario_email
            FROM " . self::TABELA . " tk
            LEFT JOIN usuarios_adm ua ON (tk.idgestor=ua.idusuario)
            WHERE tk.ativo = 'S'
            ";

        if (is_array($_GET["q"])) {
            $this->aplicarFiltros();
        }

        $this->groupby = self::CHAVE_PRIMARIA;

        return $this->sql;
    }

    public function listarTodas()
    {
        $this->sql = $this->retornarQueryPadrao();

        return $this->retornarLinhas();
    }

    private function alterarDadosFuncionalidade($funcionalidade)
    {
        if (! $funcionalidade) {
            return;
        }

        switch ($funcionalidade) {
            case 'gestor':
                $this->coluna = 'idusuario';
                $this->tabela_aux = 'usuarios_adm';
                $this->coluna_aux = 'idusuario';
                break;
        }
    }

    public function retornarPorUsuario(
        $idUsuario,
        $funcionalidade,
        $unico = false,
        $email = null,
        $filtroDescricao = false
    ) {
        $this->alterarDadosFuncionalidade($funcionalidade);

        if ($email) {
            $appendInner = "INNER JOIN {$this->tabela_aux} u ON (tk.{$this->coluna} = u.{$this->coluna_aux}) ";
            $appendAnd = "
                AND u.ativo = 'S'
                AND u.ativo_login = 'S'
                AND u.email = '{$email}'
                ";
        }

        $this->sql = "
            SELECT tk.*, ua.nome
            FROM " . self::TABELA . " tk
            LEFT JOIN usuarios_adm ua ON (ua.idusuario = tk.idusuario)
            {$appendInner}
            WHERE tk.$this->coluna = " . $idUsuario . "
            AND tk.ativo = 'S'
            AND tk.ativo_token = 'S'
            {$appendAnd}
            ";

        if ($filtroDescricao) {
             $this->sql .= " AND tk.descricao like '%"
                . $this->retornarEscapeStringQuery($filtroDescricao) . "%'";
        }

        if ($unico) {
            $this->sql .= "ORDER BY tk.data_cad DESC ";
            return $this->retornarLinha($this->sql);
        }
        
        $this->groupby = self::CHAVE_PRIMARIA;
        $this->ordem_campo = "tk.data_cad";
        $this->limite = -1;

        return $this->retornarLinhas();
    }

    private function gerarToken()
    {
        return sha1(uniqid(rand(), true));
    }

    public function alterarSituacao($idToken, $situacaoAtual)
    {
        $novaSituacao = ($situacaoAtual == "S" ? 'N' : 'S');

        $this->sql = "
            UPDATE " . self::TABELA . " tk
            SET tk.ativo_token = '{$novaSituacao}'
            WHERE tk.idtoken = {$idToken}
            ";

        $alterar = $this->executaSql($this->sql);

        $retorno = [];
        if ($alterar) {
            $retorno['sucesso'] = true;
            $retorno['novaSituacao'] = $novaSituacao;
        } else {
            $retorno['erro'] = true;
        }

        echo json_encode($retorno);
    }

    public function novoToken($idUsuario, $funcionalidade, $descricao)
    {
        $this->alterarDadosFuncionalidade($funcionalidade);

        $token = $this->gerarToken();

        $this->sql = "
            INSERT INTO " . self::TABELA . "
            SET data_cad = now(),
                ativo = 'S',
                ativo_token = 'S',
                token = '{$token}',
                descricao = '{$descricao}',
                idusuario = {$idUsuario}
        ";

        $retorno = [];
        if ($this->executaSql($this->sql)) {
            $retorno['sucesso'] = true;
            $retorno['token'] = $token;
        } else {
            $retorno['erro'] = true;
        }

        return $retorno;
    }

    public function retornarDadosUsuarioLogado()
    {
        return $this->usuarioLogado;
    }

    private function informarIdUsuario()
    {
        if (! empty($this->usuarioLogado['idusuario'])) {
            $this->usuarioLogado['painel'] = 'gestor';
        }

        $this->usuarioLogado['modulo'] = $this->usuarioLogado['painel'];

        unset(
            $this->usuarioLogado['idusuario_imobiliaria'],
            $this->usuarioLogado['idusuario_corretor']
        );
    }

    public function verificarLogin(
        $token,
        $email = null,
        $funcionalidade = null
    ) {
        if (!$email && !$token) {
            throw new \Exception('parametros_de_acesso_nao_informados', StatusCode::BAD_REQUEST);
        }

        if (! empty($funcionalidade) && $funcionalidade != 'sistema') {
            $this->alterarDadosFuncionalidade($funcionalidade);

            $appendSelect = ", u.nome, u.email ";
            $appendInner = "INNER JOIN {$this->tabela_aux} u ON (tk.{$this->coluna} = u.{$this->coluna_aux}) ";
            $appendAnd = "
                AND u.ativo = 'S'
                AND u.ativo_login = 'S'
                ";

            if (! empty($email)) {
                $appendAnd .= " AND u.email = '{$email}' ";
            }
        }

        $sql = "
            SELECT tk.token, tk.idusuario {$appendSelect}
            FROM " . self::TABELA . " tk
            {$appendInner}
            WHERE tk.token = '" . addslashes($token) . "'
            AND tk.ativo = 'S'
            AND tk.ativo_token = 'S'
            {$appendAnd}
        ";
        
        $this->usuarioLogado = $this->retornarLinha($sql);

        if (! $this->usuarioLogado['token']) {
            throw new \Exception('dados_invalidos', StatusCode::BAD_REQUEST);
        }

        if (! empty($email)) {
            if (
                empty($this->usuarioLogado['idusuario'])
            ) {
                throw new \Exception('dados_invalidos', StatusCode::BAD_REQUEST);
            }

            if (
                ! empty($this->usuarioLogado['idusuario'])
                && empty(
                    $this->retornarPorUsuario(
                        $this->usuarioLogado['idusuario'],
                        'gestor',
                        true,
                        $email
                    )
                )
            ) {
                throw new \Exception('dados_invalidos', StatusCode::BAD_REQUEST);
            }
        }

        $this->informarIdUsuario();
    }

    public function getUsuarioLogado()
    {
        return $this->usuarioLogado;
    }
}
