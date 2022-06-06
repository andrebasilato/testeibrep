<?php
class PagSeguro extends Core
{
    private $pagSeguroUrl = null;//URL do PagSeguro
    private $pagSeguroUrlWs = null;//URL do WS do PagSeguro
    private $pagSeguroUrlStc = null;//URL do STC do PagSeguro
    private $redirectURL = null;//URL do sistema que o cliente será redirecionado após a finalização do pagamento
    private $notificationURL = null;//URL que o sistema receberá as notificações das transações enviadas pelo PagSeguro
    private $email = null;//E-mail cadastrado no PagSeguro
    private $token = null;//Token do usuário no PagSeguro
    public $pessoa = null;
    public $produto = null;
    public $idEscola = null;


    public function __construct($idescola, $idconta = null)
    {
        parent::__construct();

        $this->pagSeguroUrl = $GLOBALS['config']['pagSeguro']['url'];
        $this->pagSeguroUrlWs = $GLOBALS['config']['pagSeguro']['urlWs'];
        $this->pagSeguroUrlStc = $GLOBALS['config']['pagSeguro']['urlStc'];
        $this->redirectURL = $GLOBALS['config']['pagSeguro']['redirectURL'];
        $this->notificationURL = $GLOBALS['config']['pagSeguro']['notificationURL'];

        $this->setarDadosPagSeguro($idescola, $idconta);
        
    }

    public function setarDadosPagSeguro($idescola, $idconta = null)
    {
        if (! is_numeric($idescola) && ! is_numeric($idconta)) {
            return false;
        }

        if (is_numeric($idconta)) {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/contas.class.php';
            $contaObj = new Contas();
            $conta = $contaObj->set('idusuario', $this->idusuario)
                ->set('modulo',  $this->url[0])
                ->set('id',  (int) $idconta)
                ->set('campos', 'm.idescola')
                ->retornar(true, false);

            $idescola = $conta['idescola'];
        }

        $this->idEscola = (int) $idescola;

        require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/escolas.class.php';
        $escolaObj = new Escolas();
        $escola = $escolaObj->set('id', $this->idEscola)
            ->set('campos', 'p.pagseguro_email, p.pagseguro_token')
            ->retornar();

        $this->email = $escola['pagseguro_email'];
        $this->token = $escola['pagseguro_token'];
    }

    /**
      * Cria o code do pagamento no PagSeguro, para o usuário poder realizar o pagamento
      * @access public
      * @param
      * @var int $this->email: [Obrigatório] E-mail cadastrado no PagSeguro (Informada na construção da classe)
      * @var int $this->token: [Obrigatório] Token do usuário no PagSeguro (Informada na construção da classe)
      * @var int $this->pagSeguroUrlWs: [Obrigatório] URL do WS do PagSeguro (Informada na construção da classe)
      * @var int $this->redirectURL: [Obrigatório] URL do sistema que o cliente será redirecionado após a finalização do pagamento (Informada na construção da classe)
      * @var int $this->notificationURL: [Obrigatório] URL que o sistema receberá as notificações das transações enviadas pelo PagSeguro (Informada na construção da classe)
      * @var string $this->descricaoItem: [Obrigatório] Descrição do item que está sendo comprado
      * @var double $this->valorItem: [Obrigatório] Valor do item
      * @var int $this->idItem: [Obrigatório] ID do Item
      * @var string $this->pessoa['celular']: [Opcional] Celular da pessoa
      * @var string $this->pessoa['telefone']: [Opcional] Telefone da pessoa
      * @var date $this->pessoa['data_nasc']: [Opcional] Data nascimento da pessoa
      * @var string $this->pessoa['nome']: [Opcional] Nome da pessoa
      * @var string $this->pessoa['email']: [Opcional] E-mail da pessoa
      * @var string $this->pessoa['documento']: [Opcional] CPF da pessoa
      * @var int $this->pessoa['cep']: [Opcional] CEP da pessoa
      * @var string $this->pessoa['endereco']: [Opcional] Endereço da pessoa
      * @var string $this->pessoa['numero']: [Opcional] Número da pessoa
      * @var string $this->pessoa['bairro']: [Opcional] Bairro da pessoa
      * @var string $this->pessoa['complemento']: [Opcional] Complemento da pessoa
      * @var string $this->pessoa['uf'],: [Opcional] UF da pessoa
      * @var string $this->pessoa['cidade']: [Opcional] Cidade da pessoa
      * @return array
      * @author Yuri Costa-Silva <yuric@alfamaweb.com.br>
    */
    public function criarCode()
    {
        $retorno = array();

        if (empty($this->email)) {
            $erros[] = 'email_pagseguro_vazio';
        }

        if (empty($this->token)) {
            $erros[] = 'token_pagseguro_vazio';
        }

        if (empty($this->pagSeguroUrlWs)) {
            $erros[] = 'pagSeguroUrlWs_vazio';
        }

        if (empty($this->redirectURL)) {
            $erros[] = 'redirectURL_pagseguro_vazio';
        }

        if (empty($this->notificationURL)) {
            $erros[] = 'notificationURL_pagseguro_vazio';
        }

        if (empty($this->reference)) {
            $erros[] = 'reference_vazio';
        }

        if (empty($this->idItem)) {
            $erros[] = 'idItem_vazio';
        }

        if (empty($this->descricaoItem)) {
            $erros[] = 'descricaoItem_vazio';
        }

        if (empty($this->valorItem)) {
            $erros[] = 'valorItem_vazio';
        }

        if (! empty($erros)) {
            $retorno['erro'] = true;
            $retorno['erros'] = $erros;
        } else {
            $telefoneDDD = null;
            $telefone = null;
            if ($this->pessoa['celular'] || $this->pessoa['telefone']) {
                $telefone = ($this->pessoa['celular']) ? $this->pessoa['celular'] : $this->pessoa['telefone'];
                $telefone = str_replace(array('(', ')', '-', ' '), '', $telefone);

                $telefoneDDD = substr($telefone, 0, 2);
                $telefone = substr($telefone, 2);
            }

            $dataNasc = null;
            if ($this->pessoa['data_nasc']) {
                $dataNasc = new DateTime($this->pessoa['data_nasc']);
                $dataNasc = $dataNasc->format('d/m/Y');
            }

            $fields = array(
                'email' => $this->email,//[Obrigatória] E-mail da conta que chama a API
                'token' => $this->token,//[Obrigatória] Token da conta que chama a API
                //'receiverEmail' => 'toninho@alfamacursos.com.br',//[Opcional] Especifica o e-mail que deve aparecer na tela de pagamento
                'currency' => 'BRL',//[Obrigatória] Moeda utilizada (Sempre será BRL = Real)
                'reference' => $this->reference,//[Opcional] Código de referência.
                'itemId1' => $this->idItem,//[Obrigatória] Identificadores dos itens. (Pode ser algo que tenha signifcado para o sistema. Máximo de 100 caracteres)
                'itemDescription1' => substr_replace($this->descricaoItem, '', 100, strlen($this->descricaoItem)),//[Obrigatória] Descrições dos itens (Máximo de 100 caracteres)
                'itemAmount1' => number_format($this->valorItem, 2, '.' , ''),//[Obrigatória] Valores unitários dos itens (Decimal, com 2 casas decimais separadas por ponto)
                'itemQuantity1' => '1',//[Obrigatória] Quantidades dos itens (Mínimo 1, Máximo 999)
                //'itemWeight1' => '1000',//[Opcional] Peso (em gramas) de cada item sendo pago (Usado para calcular o frete pelo pagseguro)
                //'itemShippingCost1' => '100.00',//[Opcional] Custos de frete dos itens (Caso este custo seja especificado, o PagSeguro irá assumi-lo como o custo do frete do item e não fará nenhum cálculo usando o peso do item)
                'senderName' => preg_replace('!\s+!', ' ', $this->pessoa['nome']),//[Opcional] Nome completo do comprador (Máximo 50 caracteres)
                'senderEmail' => $this->pessoa['email'],//[Opcional] E-mail do comprador (Máximo 60 caracteres)
                'senderAreaCode' => $telefoneDDD,//[Opcional] DDD do comprador
                'senderPhone' => $telefone,//[Opcional] Número do telefone do comprador
                'senderCPF' => $this->pessoa['documento'],//[Opcional] CPF do comprador
                'senderBornDate' => $dataNasc,//[Opcional] Data de nascimento do comprador
                'shippingType' => '3',//[Opcional] Tipo de frete (1 = Encomenda normal (PAC),2 = SEDEX, 3 = Tipo de frete não especificado)
                //'shippingCost' => '30.99',//[Opcional] Valor total do frete (Informa o valor total de frete do pedido. Caso este valor seja especificado, o PagSeguro irá assumi-lo como valor do frete e não fará nenhum cálculo referente aos pesos e valores de entrega dos itens.)
                'shippingAddressCountry' => 'BRA',//[Opcional] País do endereço de envio (Sempre será BRA se informado)
                'shippingAddressPostalCode' => $this->pessoa['cep'],//[Opcional] CEP do endereço de envio
                'shippingAddressStreet' => $this->pessoa['endereco'],//[Opcional] Nome da rua do endereço de envio (Máximo 80 caracteres)
                'shippingAddressNumber' => $this->pessoa['numero'],//[Opcional] Número do endereço de envio (Máximo 20 caracteres)
                'shippingAddressDistrict' => $this->pessoa['bairro'],//[Opcional] Bairro do endereço de envio (Máximo 60 caracteres)
                'shippingAddressComplement' => $this->pessoa['complemento'],//[Opcional] Complemento do endereço de envio (Máximo 40 caracteres)
                'shippingAddressState' => $this->pessoa['uf'],//[Opcional] Estado do endereço de envio
                'shippingAddressCity' => $this->pessoa['cidade'],//[Opcional] Cidade do endereço de envio (De 2 à 60 caracteres)
                //'extraAmount' => '10.00',//[Opcional] Valor extra (De -9999999.00 à 9999999.00. Especifica um valor extra que deve ser adicionado ou subtraído ao valor total do pagamento)
                'redirectURL' => $this->redirectURL,//[Opcional] URL de redirecionamento após o pagamento (Máximo 255 caracteres)
                'notificationURL' => $this->notificationURL . '/' . $this->idEscola,//[Opcional] URL para envio de notificações sobre o pagamento (Máximo 255 caracteres)
                'maxUses' => '1',//[Opcional] Número máximo de usos para o código de pagamento (De 0 até 999)
                //'maxAge' => '30',//[Opcional] Prazo de validade (em segundos) do código de pagamento (De 30 até 999999999)
            );

            $fields = http_build_query($fields);

            $curl = curl_init();

            $options = array(
                CURLOPT_URL => $this->pagSeguroUrlWs . '/v2/checkout',
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $fields,
                CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded; charset="utf-8"'),
            );

            curl_setopt_array($curl, $options);

            $resposta = curl_exec($curl);
            $curl_error = curl_error($curl);

            curl_close($curl);

            $xml = simplexml_load_string($resposta);

            if ($curl_error) {
                $retorno['erro'] = true;
                $this->enviarEmailErro($curl_error, 'criarCode');
            } elseif ($xml->error) {
                $retorno['erro'] = true;
                $retorno['erro_pagseguro']['code'] = $xml->error->code;
                $retorno['erro_pagseguro']['message'] = $xml->error->message;
            } else {
                $retorno['sucesso'] = true;
                $retorno['code'] = $xml->code;
                $retorno['date'] = $xml->date;
            }
        }

        return $retorno;
    }

    /**
      * Cria e captura uma transacao no Pagseguro, e vincula a mesma com a conta.
      * @access public
      * @param
      * @var string $this->post['codigo_transacao_pagseguro']: [Obrigatório] Code da transação com o pagSeguro (o pagSeguro retorna quando realiza o pagamento)
      * @var string $this->post['idconta']: [Obrigatório] ID da conta para qual criará a transação no Pagar.me
      * @var string $this->idusuario: ID do usuário, caso seja ele que esteja criando a transão
      * @return array
      * @author Yuri Costa-Silva <yuric@alfamaweb.com.br>
    */
    public function criarTransacao() 
    {
        if (empty($this->post['codigo_transacao_pagseguro'])) {
            $erros[] = 'codigo_transacao_pagseguro_vazio';
        }

        if (empty($this->post['idconta'])) {
            $erros[] = 'idconta_vazio';
        } else {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/contas.class.php';
            $contaObj = new Contas();
            $conta = $contaObj->set('idusuario', $this->idusuario)
                ->set('modulo',  $this->url[0])
                ->set('id',  (int) $this->post['idconta'])
                ->set('campos', 'c.idsituacao, c.idconta, c.valor')
                ->retornar();

            if (empty($conta)) {
                $erros[] = 'conta_nao_existe';
            }
        }

        $existeTransacao = $this->buscarTransacao(null, $this->post['codigo_transacao_pagseguro']);

        if (! empty($existeTransacao['idpagseguro'])) {
            $retorno['erro'] = true;
            $retorno['mensagem'] = 'transacao_existe';
            return $retorno;
        }

        if (!empty($erros)) {
            $retorno['erro'] = true;
            $retorno['erros'] = $erros;
        } else {
            try {
                $sql = 'INSERT INTO
                            pagseguro
                        SET
                            ativo = "S",
                            data_cad = NOW(),
                            idconta = ' . $conta['idconta'] . ',
                            code = "' . $this->post['codigo_transacao_pagseguro'] . '"';
                $salvar = $this->executaSql($sql);
                $idpagseguro = mysql_insert_id();

                $this->atualizaTransacao($idpagseguro);
            } catch (Exception $e) {
                $retorno['exception'] = nl2br($e->getMessage());
            }

            if ($salvar) {
                $retorno['sucesso'] = true;
            } else {
                $retorno['erro'] = true;
                $retorno['erros'][] = 'erro_inesperado';
            }
        }

        return $retorno;
    }

    /**
      * Monitora todas as alterações feitas na tabela do pagSeguro
      * @access public
      * @param
      * @var int $this->monitora_qual: [Obrigatório] ID do pagSeguro que foi alterado
      * @var array $this->monitora_dadosantigos: [Obrigatório] Dados que estava antes da alteração no pagSeguro
      * @var array $this->monitora_dadosnovos: [Obrigatório] Dados que ficou após a alteração no pagSeguro
      * @return bool
      * @author Yuri Costa-Silva <yuric@alfamaweb.com.br>
    */
    private function monitoraPagSeguro()
    {
        if (! $this->monitora_qual || ! isset($this->monitora_dadosantigos) || ! isset($this->monitora_dadosnovos)) {
            return false;
        } else {
            $sql = 'INSERT INTO
                        pagseguro_monitora
                    SET
                        data_cad = NOW(),
                        idpagseguro = ' . $this->monitora_qual;
            $this->executaSql($sql);
            $idmonitora = mysql_insert_id();

            foreach ($this->monitora_dadosantigos as $ind => $var) {
                if ($this->monitora_dadosantigos[$ind] != $this->monitora_dadosnovos[$ind]) {
                    $sql = 'INSERT INTO
                                pagseguro_monitora_log
                            SET
                                idmonitora = ' . $idmonitora . ',
                                campo = "' . $ind . '",
                                de = "' . mysql_real_escape_string($this->monitora_dadosantigos[$ind]) . '",
                                para = "' . mysql_real_escape_string($this->monitora_dadosnovos[$ind]) . '"';
                    $this->executaSql($sql);
                }
            }
        }

        return true;
    }

    /**
      * Busca o dados de uma transação no PagSeguro
      * @access public
      * @param string $codigoTransacao: [Obrigatório] Código da transação do PagSeguro que será retornada
      * @var
      * @return array
      * @author Yuri Costa-Silva <yuric@alfamaweb.com.br>
    */
    public function retornaTransacao($codigoTransacao)
    {
        if (empty($codigoTransacao)) {
            $erros['erro'] = true;
            $erros['erros'][] = 'parametros_incompletos';
            return $erros;
        }

        if (empty($this->email)) {
            $erros[] = 'email_pagseguro_vazio';
        }

        if (empty($this->token)) {
            $erros[] = 'token_pagseguro_vazio';
        }

        if (! empty($erros)) {
            $retorno['erro'] = true;
            $retorno['erros'] = $erros;
            return $retorno;
        }

        $curl = curl_init();

        $options = array(
            CURLOPT_URL => $this->pagSeguroUrlWs . '/v2/transactions/' . $codigoTransacao . '?email=' . $this->email . '&token=' . $this->token,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded; charset="utf-8"'),
        );

        curl_setopt_array($curl, $options);

        $resposta = curl_exec($curl);
        $curl_error = curl_error($curl);

        curl_close($curl);

        $xml = simplexml_load_string($resposta);

        if ($curl_error) {
            $retorno['erro'] = true;
            $this->enviarEmailErro($curl_error, 'retornaTransacao');
        } else {
            $retorno['sucesso'] = true;
            $retorno['xml_puro'] = $resposta;
            $retorno['xml'] = $xml;
        }

        return $retorno;
    }

    /**
      * Consulta uma notificação de transação no PagSeguro
      * @access public
      * @param string $codigoTransacao: [Obrigatório] Código da transação do PagSeguro que será retornada
      * @var
      * @return array
      * @author Yuri Costa-Silva <yuric@alfamaweb.com.br>
    */
    public function retornaNotificacao($codigoNotificacao)
    {
        if (empty($codigoNotificacao)) {
            $erros['erro'] = true;
            $erros['erros'][] = 'parametros_incompletos';
            return $erros;
        }

        $curl = curl_init();

        $options = array(
            CURLOPT_URL => $this->pagSeguroUrlWs . '/v3/transactions/notifications/' . $codigoNotificacao . '?email=' . $this->email . '&token=' . $this->token,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded; charset="utf-8"'),
        );

        curl_setopt_array($curl, $options);

        $resposta = curl_exec($curl);
        $curl_error = curl_error($curl);

        curl_close($curl);

        $xml = simplexml_load_string($resposta);

        if ($curl_error) {
            $retorno['erro'] = true;
            $this->enviarEmailErro($curl_error, 'retornaNotificacao');
        } else {
            $retorno['sucesso'] = true;
            $retorno['xml_puro'] = $resposta;
            $retorno['xml'] = $xml;
        }

        return $retorno;
    }

    /**
      * Envia e-mail de erro. É chamada caso de erro na requisição pela Curl
      * @access private
      * @param string $erro: [Obrigatório] Erro retornado
      * @param string $metodo: [Obrigatório] Nome do método que gerou o erro
      * @var
      * @return void
      * @author Yuri Costa-Silva <yuric@alfamaweb.com.br>
    */
    private function enviarEmailErro($erro, $metodo)
    {
        $nomeDe = $GLOBALS['config']['tituloEmpresa'];
        $emailDe = $GLOBALS['config']['emailSistema'];

        $nomePara = 'TIME SISTEMAS';
        $emailPara = 'timesistemas@alfamaweb.com.br';

        $assunto = 'ERRO AO REALIZAR REQUISIÇÃO NO PAGSEGURO DO ' . $GLOBALS['config']['tituloSistema'] . ' [' . $GLOBALS['config']['tituloEmpresa'] . '] - ' . date('H:i d/m/Y');
        $mensagem = 'Erro ocorrido ao realizar requisição no método ' . $metodo . '() da classe pagseguro.class.php
            <br /><br />
            urlSistema: ' . $GLOBALS['config']['urlSistema'] . '<br />
            SCRIPT_NAME: ' . $_SERVER['SCRIPT_NAME'] . '<br />
            REQUEST_URI: ' . $_SERVER['REQUEST_URI'] . '<br />
            Erro: ' . $erro;

        $this->enviarEmail($nomeDe, $emailDe, $assunto, $mensagem, $nomePara, $emailPara, 'layout', 'utf-8');
    }

    /**
      * Retorna as contas que tenham pagamento sem retorno do pagSeguro
      * @access public
      * @param
      * @var
      * @return array
      * @author Yuri Costa-Silva <yuric@alfamaweb.com.br>
    */
    public function retornaPagamentosSemRetorno()
    {
        $this->sql = 'SELECT 
                            p.idpagseguro,
                            p.code,
                            c.idconta
                        FROM 
                            pagseguro p
                            INNER JOIN contas c ON (c.idconta = p.idconta)
                            INNER JOIN contas_workflow cw ON (cw.idsituacao = c.idsituacao)
                        WHERE
                            p.status IN (0,1,2,9) AND
                            p.ativo = "S" AND
                            cw.emaberto = "S" AND
                            c.ativo = "S" AND
                            p.cron = "N"';

        $this->groupby = 'c.idconta';
        $this->ordem_campo = 'c.idconta';
        $this->ordem = 'ASC';
        $this->limite = 30;

        $retorno = $this->retornarLinhas();

        if (empty($retorno)) {
            $sql = 'UPDATE pagseguro SET cron = "N"';
            $this->executaSql($sql);
        }

        return $retorno;
    }

    /**
      * Busca uma transação feita
      * @access public
      * @param int $idpagseguro: [Obrigatório se não tiver code] ID do pagSeguro, que será retornada os dados do BD.
      * @param int $code: [Obrigatório se não tiver idpagseguro] Code do pagSeguro, que será retornada os dados do BD.
      * @var
      * @return array
      * @author Yuri Costa-Silva <yuric@alfamaweb.com.br>
    */
    public function buscarTransacao($idpagseguro, $code)
    {
        if (empty($idpagseguro) && empty($code)) {
            $erros['erro'] = true;
            $erros['erros'][] = 'parametros_incompletos';
            return $erros;
        }

        $sql = 'SELECT p.* FROM pagseguro p WHERE p.ativo = "S"';

        if ($idpagseguro) {
            $sql .= ' AND p.idpagseguro = ' . (int) $idpagseguro;
        }

        if ($code) {
            $sql .= ' AND p.code = "' . $code . '"';
        }

        return $this->retornarLinha($sql);
    }

    /**
     * Faz a consulta do pagamento e caso esteja pago, já da baixa no sistema
     * @access public
     * @param $idpagseguro: ID do pagamento feito para realizar a busca
     * @var
     * @return array: Retorna array com erros, ou com sucesso
     * @author Yuri Costa-Silva <yuric@alfamaweb.com.br>
     */
    public function atualizaTransacao($idpagseguro, $cron = false)
    {
        $retorno = array();

        //Retorna a transação efetuada
        $transacaoPagamento = $this->buscarTransacao($idpagseguro);

        if ($transacaoPagamento) {
            try {
                if ($cron) {
                    $sql = 'UPDATE pagseguro SET cron = "S" WHERE idpagseguro = ' . $transacaoPagamento['idpagseguro'];
                    $this->executaSql($sql);
                }

                if (empty($this->naoIniciarTransacao)) {
                    $this->executaSql('BEGIN');
                }

                $retornoTransacao = $this->retornaTransacao($transacaoPagamento['code']);
                $transaction = $retornoTransacao['xml'];

                if ($transaction) {
                    $linhaAntiga = $this->buscarTransacao($idpagseguro);

                    $gatewaySystem = null;
                    if (isset($transaction->gatewaySystem)) {
                        //Se tiver o gatewaySystem, irá converter o SimpleXMLElement para array
                        $gatewaySystem = (array)$transaction->gatewaySystem;
                        $gatewaySystem = json_encode($gatewaySystem);
                        $gatewaySystem = json_decode($gatewaySystem, TRUE);
                        $gatewaySystem = serialize($gatewaySystem);
                        $gatewaySystem = addslashes($gatewaySystem);
                    }

                    $sql = 'UPDATE
                                pagseguro
                            SET
                                date = ' . ((isset($transaction->date)) ? '"' . $transaction->date . '"' : 'NULL') . ',
                                reference = ' . ((isset($transaction->reference)) ? '"' . $transaction->reference . '"' : 'NULL') . ',
                                type = ' . ((isset($transaction->type)) ? '"' . $transaction->type . '"' : 'NULL') . ',
                                status = ' . ((isset($transaction->status)) ? '"' . $transaction->status . '"' : 'NULL') . ',
                                cancellationSource = ' . ((isset($transaction->cancellationSource)) ? '"' . $transaction->cancellationSource . '"' : 'NULL') . ',
                                lastEventDate = ' . ((isset($transaction->lastEventDate)) ? '"' . $transaction->lastEventDate . '"' : 'NULL') . ',
                                paymentMethod_type = ' . ((isset($transaction->paymentMethod->type)) ? '"' . $transaction->paymentMethod->type . '"' : 'NULL') . ',
                                paymentMethod_code = ' . ((isset($transaction->paymentMethod->code)) ? '"' . $transaction->paymentMethod->code . '"' : 'NULL') . ',
                                paymentLink = ' . ((isset($transaction->paymentLink)) ? '"' . $transaction->paymentLink . '"' : 'NULL') . ',
                                grossAmount = ' . ((isset($transaction->grossAmount)) ? '"' . $transaction->grossAmount . '"' : 'NULL') . ',
                                discountAmount = ' . ((isset($transaction->discountAmount)) ? '"' . $transaction->discountAmount . '"' : 'NULL') . ',
                                feeAmount = ' . ((isset($transaction->feeAmount)) ? '"' . $transaction->feeAmount . '"' : 'NULL') . ',
                                netAmount = ' . ((isset($transaction->netAmount)) ? '"' . $transaction->netAmount . '"' : 'NULL') . ',
                                escrowEndDate = ' . ((isset($transaction->escrowEndDate)) ? '"' . $transaction->escrowEndDate . '"' : 'NULL') . ',
                                extraAmount = ' . ((isset($transaction->extraAmount)) ? '"' . $transaction->extraAmount . '"' : 'NULL') . ',
                                installmentCount = ' . ((isset($transaction->installmentCount)) ? '"' . $transaction->installmentCount . '"' : 'NULL') . ',
                                creditorFees = ' . ((isset($transaction->creditorFees)) ? '"' . $transaction->creditorFees . '"' : 'NULL') . ',
                                installmentFeeAmount = ' . ((isset($transaction->installmentFeeAmount)) ? '"' . $transaction->installmentFeeAmount . '"' : 'NULL') . ',
                                operationalFeeAmount = ' . ((isset($transaction->operationalFeeAmount)) ? '"' . $transaction->operationalFeeAmount . '"' : 'NULL') . ',
                                intermediationRateAmount = ' . ((isset($transaction->intermediationRateAmount)) ? '"' . $transaction->intermediationRateAmount . '"' : 'NULL') . ',
                                intermediationFeeAmount = ' . ((isset($transaction->intermediationFeeAmount)) ? '"' . $transaction->intermediationFeeAmount . '"' : 'NULL') . ',
                                itemCount = ' . ((isset($transaction->itemCount)) ? '"' . $transaction->itemCount . '"' : 'NULL') . ',
                                items_item_id = ' . ((isset($transaction->items->item->id)) ? '"' . $transaction->items->item->id . '"' : 'NULL') . ',
                                items_item_description = ' . ((isset($transaction->items->item->description)) ? '"' . $transaction->items->item->description . '"' : 'NULL') . ',
                                items_item_quantity = ' . ((isset($transaction->items->item->quantity)) ? '"' . $transaction->items->item->quantity . '"' : 'NULL') . ',
                                items_item_amount = ' . ((isset($transaction->items->item->amount)) ? '"' . $transaction->items->item->amount . '"' : 'NULL') . ',
                                sender_name = ' . ((isset($transaction->sender->name)) ? '"' . $transaction->sender->name . '"' : 'NULL') . ',
                                sender_email = ' . ((isset($transaction->sender->email)) ? '"' . $transaction->sender->email . '"' : 'NULL') . ',
                                sender_phone_areaCode = ' . ((isset($transaction->sender->phone->areaCode)) ? '"' . $transaction->sender->phone->areaCode . '"' : 'NULL') . ',
                                sender_phone_number = ' . ((isset($transaction->sender->phone->number)) ? '"' . $transaction->sender->phone->number . '"' : 'NULL') . ',
                                shipping_type = ' . ((isset($transaction->shipping->type)) ? '"' . $transaction->shipping->type . '"' : 'NULL') . ',
                                shipping_cost = ' . ((isset($transaction->shipping->cost)) ? '"' . $transaction->shipping->cost . '"' : 'NULL') . ',
                                shipping_address_street = ' . ((isset($transaction->shipping->address->street)) ? '"' . $transaction->shipping->address->street . '"' : 'NULL') . ',
                                shipping_address_number = ' . ((isset($transaction->shipping->address->number)) ? '"' . $transaction->shipping->address->number . '"' : 'NULL') . ',
                                shipping_address_complement = ' . ((isset($transaction->shipping->address->complement)) ? '"' . $transaction->shipping->address->complement . '"' : 'NULL') . ',
                                shipping_address_district = ' . ((isset($transaction->shipping->address->district)) ? '"' . $transaction->shipping->address->district . '"' : 'NULL') . ',
                                shipping_address_city = ' . ((isset($transaction->shipping->address->city)) ? '"' . $transaction->shipping->address->city . '"' : 'NULL') . ',
                                shipping_address_state = ' . ((isset($transaction->shipping->address->state)) ? '"' . $transaction->shipping->address->state . '"' : 'NULL') . ',
                                shipping_address_country = ' . ((isset($transaction->shipping->address->country)) ? '"' . $transaction->shipping->address->country . '"' : 'NULL') . ',
                                shipping_address_postalCode = ' . ((isset($transaction->shipping->address->postalCode)) ? '"' . $transaction->shipping->address->postalCode . '"' : 'NULL') . ',
                                gatewaySystem = ' . ((isset($gatewaySystem)) ? '"' . $gatewaySystem . '"' : 'NULL') . ',
                                retorno_pagseguro = ' . ((isset($retornoTransacao['xml_puro'])) ? '"' . addslashes(utf8_encode($retornoTransacao['xml_puro'])) . '"' : 'NULL') . '
                            WHERE
                                idpagseguro = ' . $transacaoPagamento['idpagseguro'];
                    $salvar = $this->executaSql($sql);

                    $linhaNova = $this->buscarTransacao($idpagseguro);

                    $this->monitora_qual = $idpagseguro;
                    $this->monitora_dadosantigos = $linhaAntiga;
                    $this->monitora_dadosnovos = $linhaNova;
                    $this->monitoraPagSeguro();

                    $sql = 'UPDATE 
                                contas
                            SET
                                autorizacao_cartao = ' . ((isset($transaction->gatewaySystem->authorizationCode)) ? '"' . $transaction->gatewaySystem->authorizationCode . '"' : 'NULL') . '
                            WHERE
                                idconta  = ' . $transacaoPagamento['idconta'];
                    $this->executaSql($sql);

                    //Se status estiver como 3: Paga, 4: Disponível(o valor da transação está disponível para saque),
                    //6: Devolvida (O valor da transação foi devolvido para o comprador), 8: Debitado (O valor da transação foi devolvido para o comprador)
                    if ($transaction->status == 3 || $transaction->status == 4 || $transaction->status == 6 || $transaction->status == 8) {
                        $date = new DateTime($transaction->lastEventDate);//Cria uma data com a data de atualização do transação
                        $date->setTimezone(new DateTimeZone('America/Bahia'));//Seta o TimeZone da Bahia para a data

                        //INÍCIO ATUALIZA SITUAÇÃO DA CONTA PARA SITUAÇÃO PAGA
                        require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/contas.class.php';
                        $contaObj = new Contas();
                        $contaObj->set('idusuario', $this->idusuario);
                        $contaObj->set('modulo',  $this->url[0]);

                        //Busca os dados da conta
                        $sqlModificacoesConta = 'SELECT idsituacao, idconta, idmatricula FROM contas WHERE idconta = ' . $transacaoPagamento['idconta'];
                        $linhaAntigaConta = $this->retornarLinha($sqlModificacoesConta);

                        //Adiciona o histórico da mudança da situação da conta da matrícula
                        require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/matriculas.class.php';
                        $matriculaObj = new Matriculas();
                        $matriculaObj->set('id', $linhaAntigaConta['idmatricula'])
                            ->set('idusuario', $this->idusuario)
                            ->set('modulo',  $this->url[0]);

                        //Retorna os dados da matrícula para atualizar o pedido
                        $matricula = $matriculaObj->retornar();

                        if ($transaction->status == 3 || $transaction->status == 4) {
                            if ($transaction->status == 3) {
                                //Retorna o id da situação paga da conta
                                $situacaoConta = $contaObj->retornarSituacaoPagSeguro();
                                if (empty($situacaoConta)) {
                                    $retorno['erro'] = true;
                                    $retorno['erros'][] = 'sem_situacao_pagseguro';
                                    return $retorno;
                                }
                            } elseif ($transaction->status == 4) {
                                $situacaoConta = $contaObj->retornarSituacaoPago();
                            }

                            $situacaoPedido = 'P';

                            $valor_pago = '"' . $transaction->grossAmount . '"';
                            $data_pagamento = '"' . $date->format('Y-m-d') . '"';

                            $sql = "SELECT 
                                        m.idmatricula 
                                    FROM 
                                        matriculas m 
                                        INNER JOIN matriculas_workflow mw 
                                            ON mw.idsituacao = m.idsituacao
                                    WHERE m.idmatricula = '" . $linhaAntigaConta['idmatricula'] ."'
                                    AND mw.fim <> 'S'
                                    AND mw.cancelada <> 'S'
                                    AND mw.ativa <> 'S'";

                            $matriculaDisponivelMudanca = $this->retornarLinha($sql);
                            if (! empty($matriculaDisponivelMudanca['idmatricula'])) {
                                $situacaoAtiva = $matriculaObj->retornarSituacaoAtiva();

                                $sql = 'UPDATE matriculas SET idsituacao = ' . $situacaoAtiva['idsituacao'] . '
                                    WHERE idmatricula = ' . $linhaAntigaConta['idmatricula'];
                                $this->executaSql($sql);

                                $matriculaObj->set('id', $linhaAntigaConta['idmatricula'])
                                    ->adicionarHistorico(
                                        null,
                                        'situacao',
                                        'modificou',
                                        $matricula['idsituacao'],
                                        $situacaoAtiva['idsituacao'],
                                        null
                                    );
                            }
                        } else {//Pagamento estornado
                            //Retorna o id da situação em aberto da conta
                            $situacaoEmAberto = $contaObj->retornarSituacaoEmAberto();
                            $situacaoConta = $situacaoEmAberto['idsituacao'];

                            if (empty($situacaoConta)) {
                                $retorno['erro'] = true;
                                $retorno['erros'][] = 'sem_situacao_em_aberto';
                                return $retorno;
                            }

                            $situacaoPedido = 'A';

                            $valor_pago = 'NULL';
                            $data_pagamento = 'NULL';
                        }

                        //Atualiza a situação da conta
                        $sql = 'UPDATE
                                    contas
                                SET
                                    idsituacao = ' . $situacaoConta . ',
                                    valor_pago = ' . $valor_pago . ',
                                    data_pagamento = ' . $data_pagamento . '
                                WHERE
                                    idconta = ' . $transacaoPagamento['idconta'];
                        $this->executaSql($sql);    

                        //Busca os novos dados da conta
                        $linhaNovaConta = $this->retornarLinha($sqlModificacoesConta);

                        //Adiciona o histórico da mudança da situação da conta
                        $contaObj->set('id', $transacaoPagamento['idconta']);
                        $contaObj->adicionarHistorico(
                            'situacao',
                            'modificou',
                            $linhaAntigaConta['idsituacao'],
                            $linhaNovaConta['idsituacao']
                        );
                        
                        $matriculaObj->adicionarHistorico(
                            null,
                            'parcela_situacao',
                            'modificou',
                            $linhaAntigaConta['idsituacao'],
                            $linhaNovaConta['idsituacao'],
                            $transacaoPagamento['idconta']
                        );

                        if ($matricula['idpedido']) {
                            $this->sql = 'UPDATE loja_pedidos SET situacao = "' . $situacaoPedido . '" WHERE idpedido = ' . $matricula['idpedido'];
                            $this->executaSql($this->sql);
                        }
                        //FIM ATUALIZA SITUAÇÃO DA CONTA PARA SITUAÇÃO PAGA
                    }

                    if (empty($this->naoIniciarTransacao)) {
                        $this->executaSql('COMMIT');
                    }
                    $retorno['sucesso'] = true;
                } else {
                    $retorno['erro'] = true;
                    $retorno['erros'][] = 'sem_retorno_pagamento';
                }
            } catch (Exception $e) {
                $retorno['exception'] = nl2br($e->getMessage());
            }
        } else {
            $retorno['erro'] = true;
            $retorno['erros'][] = 'pagamento_nao_encontrado_ja_existe';
        }

        return $retorno;
    }

    /**
      * Busca todas as transações em um determinado período no PagSeguro
      * @access public
      * @param string $codigoTransacao: [Obrigatório] Código da transação do PagSeguro que será retornada
      * @var
      * @return array
      * @author Yuri Costa-Silva <yuric@alfamaweb.com.br>
    */
    public function retornaTransacoesPorPeriodo($dataInicial, $dataFinal, $pagina = 1, $limite = 1000)
    {
        if (empty($dataInicial) || empty($dataFinal) || empty($pagina) || empty($limite)) {
            $retorno['erro'] = true;
            $retorno['erros'][] = 'parametros_incompletos';
            return $retorno;
        }

        if (empty($this->email)) {
            $erros[] = 'email_pagseguro_vazio';
        }

        if (empty($this->token)) {
            $erros[] = 'token_pagseguro_vazio';
        }

        if (! empty($erros)) {
            $retorno['erro'] = true;
            $retorno['erros'] = $erros;
            return $retorno;
        }

        $curl = curl_init();

        $options = array(
            CURLOPT_URL => $this->pagSeguroUrlWs . '/v2/transactions/?initialDate=' . $dataInicial . '&finalDate=' . $dataFinal . '&page=' . $pagina . '&maxPageResults=' . $limite . '&email=' . $this->email . '&token=' . $this->token,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded; charset="utf-8"'),
        );

        curl_setopt_array($curl, $options);

        $resposta = curl_exec($curl);
        $curl_error = curl_error($curl);

        curl_close($curl);

        $xml = simplexml_load_string($resposta);

        if ($curl_error) {
            $retorno['erro'] = true;
            $this->enviarEmailErro($curl_error, 'retornaTransacoesPorPeriodo');
        } else {
            $retorno['sucesso'] = true;
            $retorno['xml_puro'] = $resposta;
            $retorno['xml'] = $xml;

            $sql = 'INSERT INTO
                        pagseguro_logs
                    SET
                        data_cad = NOW(),
                        metodo_gerado = "retornaTransacoesPorPeriodo",
                        retorno = "' . addslashes(utf8_encode($resposta)) . '"';
            $this->executaSql($sql);
        }

        return $retorno;
    }
}