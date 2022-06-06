<?php

namespace OrIO;

use Core;
use AwOra\orio\GestaoTokens;
class funcoesComuns
{
    private $idUsuario;
    public $acessoBanco = null;
    public $retorno = null;

    public function __construct()
    {
        $this->acessoBanco = new Core();
        $this->tokenObj = new GestaoTokens();
    }

    private function verificaPermissaoUsuario($dadosAcesso)
    {
        $sql = 'SELECT
                ua.*, up.permissoes
            FROM
                usuarios_adm ua
                INNER JOIN usuarios_adm_perfis up ON (up.idperfil = ua.idperfil)
            WHERE
                ua.email = "' . mysql_real_escape_string($dadosAcesso->email) . '" AND
                ua.senha = "' . mysql_real_escape_string($dadosAcesso->senha) . '"  AND
                up.ativo = "S" AND
                ua.cvio = "S"';
        return $this->acessoBanco->retornarLinha($sql);
    }

    public function verificarPermissaoRest($dadosAcesso)
    {
        if (! isset($dadosAcesso->email) || ! isset($dadosAcesso->senha)) {
            $retorno['codigo'] = '401';
            $retorno['mensagem'] = 'Sem acesso.';
        } else {
            $retorno = $this->verificaPermissaoUsuario($dadosAcesso);
            if (! $retorno['idusuario']) {
                $retorno['codigo'] = '401';
                $retorno['mensagem'] = 'Login e/ou senha incorreto(s).';
            }
        }

        return $retorno;
    }

    public function adicionarCabecalhoJson($codigo = 200)
    {
        http_response_code($codigo);
        header('Content-Type: application/json');
        header('Expires: 0');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET');
        header('Pragma: no-cache');
    }

    public function adicionarHeaders()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
    }

    public function validateToken(
        $token,
        $pessoa,
        $retornarTokenInvalido = false
    ) {
        $token = $token ? $token : $pessoa['token_app'];

        if (! empty($pessoa['ultimo_token'])) {
            $hoje = new \DateTime();
            $dataUltimoToken = new \DateTime($pessoa['ultimo_token']);
            $diferencaDias = $hoje->diff($dataUltimoToken)->format("%a");
        }

        $retornoValidacao = [
            'status' => "ok",
            'chave_acesso' => $token,
        ];

        if ($retornarTokenInvalido) {
            $retornoValidacao = [
                'status' => "Válido"
            ];

            if ($diferencaDias > 8) {
                $GLOBALS['retorno']['codigo'] = 401;
                $retornoValidacao['status'] = "Não valido";
            }

            $GLOBALS['retorno']['token'] = $retornoValidacao;
            return;
        }

        if ($diferencaDias > 8 || empty($token) || empty($dataUltimoToken)) {
            $novoToken = senhaSegura(uniqid(), $GLOBALS['config']['chaveLogin']);
            $sql = "UPDATE pessoas SET token_app = '".$novoToken."', ultimo_token = NOW() "
                . " WHERE idpessoa = '".$pessoa['idpessoa']."' ";

            if (! $this->acessoBanco->executaSql($sql)) {
                throw new \Exception('erro_atualizar_token', '500');
            }

            $retornoValidacao = [
                'status' => "novo",
                'chave_acesso' => $novoToken,
            ];

            if (empty($token)) {
                $retornoValidacao['status'] = "primeiro_acesso";
            }
        }

        $GLOBALS['retorno']['token'] = $retornoValidacao;
    }

    public function getHeaderToken()
    {

        $headers = $_SERVER['HTTP_AUTHORIZATION']
            ? $_SERVER['HTTP_AUTHORIZATION']
            : $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];

        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }

        return false;
    }

    public function autenticarPessoaPorToken($token, $retornarTokenInvalido = false)
    {
        if (empty($token)) {
            throw new \InvalidArgumentException('erro_token_nao_informado', 400);
        }

        $token = mysql_real_escape_string($token);

        $sql = "SELECT
                    idpessoa, nome, email, senha, ultimo_token, token_app
                FROM
                    pessoas
                WHERE token_app = '$token' AND ativo = 'S'";

        $pessoa = $this->acessoBanco->retornarLinha($sql);

        if (empty($pessoa)) {
            throw new \Exception('token_invalido', 400);
        }

        $this->validateToken($token, $pessoa, $retornarTokenInvalido);

        return $pessoa;
    }

    public function validaCPF($cpf = null)
    {
        // Verifica se um número foi informado
        if(empty($cpf)) {
            return false;
        }

        // Elimina possivel mascara
        $cpf = preg_replace("/[^0-9]/", "", $cpf);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);

        // Verifica se o numero de digitos informados é igual a 11
        if (strlen($cpf) != 11) {
            return false;
        }
        // Verifica se nenhuma das sequências invalidas abaixo
        // foi digitada. Caso afirmativo, retorna falso
        else if ($cpf == '00000000000' ||
            $cpf == '11111111111' ||
            $cpf == '22222222222' ||
            $cpf == '33333333333' ||
            $cpf == '44444444444' ||
            $cpf == '55555555555' ||
            $cpf == '66666666666' ||
            $cpf == '77777777777' ||
            $cpf == '88888888888' ||
            $cpf == '99999999999') {
            return false;
            // Calcula os digitos verificadores para verificar se o
            // CPF é válido
        } else {

            for ($t = 9; $t < 11; $t++) {

                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf{$c} != $d) {
                    return false;
                }
            }

            return true;
        }
    }

    public function errorResponse($codigo, $mensagem, $codigoHttp = 500)
    {
        $retorno = [
            'codigo' => $codigo,
            'mensagem' => $mensagem
        ];

        \http_response_code($codigoHttp);
        
        $this->adicionarCabecalhoJson($retorno['codigo']);

        echo json_encode($retorno);
        exit;
    }

    public function autenticarPorToken($token)
    {
        if (empty($token)) {
            throw new \InvalidArgumentException('token_nao_informado', 400);
        }
        
        $token = mysql_real_escape_string($token);

        $this->tokenObj->verificarLogin($token);
        return $this->tokenObj->retornarDadosUsuarioLogado();
    }

    public function salvarLogRequisicao(array $json)
    {
        $pasta = $_SERVER['DOCUMENT_ROOT'] . '/storage/temp/';
        if (! is_dir($pasta)) {
            mkdir($pasta, 0755, true);
        }
        $fileObj = new \SplFileObject($pasta . 'log_requisicoes_cvio.txt', 'a');
        $string = "---------------------\n";
        $string .= "[" . date('d/m/Y H:i:s') . "]\n";
        $string .= "INTERFACE: " . $GLOBALS['url'][2] . "\n";
        $string .= "CODIGO: " . $json['codigo'] . "\n";
        $string .= "MENSAGEM: " . $json['mensagem'] . "\n";
        $string .= "REMOTE_ADDR: " . $_SERVER['REMOTE_ADDR'] . "\n";
        $string .= "HTTP_USER_AGENT: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
        $string .= "HTTP_EMAIL: " . $_SERVER['HTTP_EMAIL'] . "\n";
        $string .= "HTTP_SENHA: " . $_SERVER['HTTP_SENHA'] . "\n";
        $string .= "POST:[\n";

        foreach ($_POST as $ind => $val) {
            $string .= " $ind: $val\n";
        }

        $string .= "]\n\n";

        $fileObj->fwrite($string);

    }
}
