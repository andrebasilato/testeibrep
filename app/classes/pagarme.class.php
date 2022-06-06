<?php
include_once DIR_APP . '/classes/orio/Transacoes.php';

class PagarmeObj extends Core
{
    /**
      * Cria e captura uma transação no Pagar.me, e vincula a mesma com a conta.
      * @access public
      * @param
      * @var int $this->idescola: [Opcional] ID da escola da conta para qual serão geradas as faturas
      * @return void
      * @author Yuri Costa-Silva <yuric@alfamaweb.com.br>
    */
    public function criarTransacao()
    {
        if (empty($this->post['token'])) {
            $erros[] = 'token_vazio';
        }

        if (empty($this->post['idconta'])) {
            $erros[] = 'idconta_vazio';
        } else {
        	require_once DIR_APP . '/classes/contas.class.php';
        	$contaObj = new Contas();
			$conta = $contaObj->set('idusuario', $this->idusuario)
				->set('idescola', $this->idescola)
				->set('modulo',  $this->url[0])
				->set('id',  (int) $this->post['idconta'])
				->set('campos', 'c.idsituacao, c.idconta, c.valor')
				->retornar();

	        if (empty($conta)) {
	            $erros[] = 'conta_nao_existe';
	        }
        }

        if (! empty($erros)) {
            $retorno['erro'] = true;
            $retorno['erros'] = $erros;
        } else {
        	try {
        		require_once DIR_APP . '/classes/pagarme-php-master/Pagarme.php';
        		Pagarme::setApiKey($GLOBALS['config']['pagarme']['api_key']);

	        	$transacaoObj = new \Transacoes();
				$interface = retornarInterface('pagarme_criar_transacao');
				$transacaoObj->iniciaTransacao($interface['id'], 'S');
				$transacaoObj->salvarJsonTransacaoEnviada($_POST['token']);

				$transaction = PagarMe_Transaction::findById($_POST['token']);
				
				$transacaoObj->salvarJsonTransacaoResposta((array) $transaction);
				$transacaoObj->finalizaTransacao(null, 2);

	        	$sql = 'INSERT INTO
		                    pagarme
		                SET
							ativo = "S",
							data_cad = NOW(),
							idconta = ' . $conta['idconta'] . ',
							object = ' . ((isset($transaction->object)) ? '"' . $transaction->object . '"' : 'NULL') . ',
							id = ' . ((isset($transaction->id)) ? '"' . $transaction->id . '"' : 'NULL') . ',
							status = ' . ((isset($transaction->status)) ? '"' . $transaction->status . '"' : 'NULL') . ',
							date_created = ' . ((isset($transaction->date_created)) ? '"' . $transaction->date_created . '"' : 'NULL') . ',
							date_updated = ' . ((isset($transaction->date_updated)) ? '"' . $transaction->date_updated . '"' : 'NULL') . ',
							payment_method = ' . ((isset($transaction->payment_method)) ? '"' . $transaction->payment_method . '"' : 'NULL') . ',
							installments = ' . ((isset($transaction->installments)) ? '"' . $transaction->installments . '"' : 'NULL') . ',
							amount = ' . ((isset($transaction->amount)) ? '"' . $transaction->amount . '"' : 'NULL') . ',
							authorized_amount = ' . ((isset($transaction->authorized_amount)) ? '"' . $transaction->authorized_amount . '"' : 'NULL') . ',
							paid_amount = ' . ((isset($transaction->paid_amount)) ? '"' . $transaction->paid_amount . '"' : 'NULL') . ',
							refunded_amount = ' . ((isset($transaction->refunded_amount)) ? '"' . $transaction->refunded_amount . '"' : 'NULL') . ',
							authorization_code = ' . ((isset($transaction->authorization_code)) ? '"' . $transaction->authorization_code . '"' : 'NULL') . ',
							tid = ' . ((isset($transaction->tid)) ? '"' . $transaction->tid . '"' : 'NULL') . ',
							nsu = ' . ((isset($transaction->nsu)) ? '"' . $transaction->nsu . '"' : 'NULL') . ',
							refuse_reason = ' . ((isset($transaction->refuse_reason)) ? '"' . $transaction->refuse_reason . '"' : 'NULL') . ',
							status_reason = ' . ((isset($transaction->status_reason)) ? '"' . $transaction->status_reason . '"' : 'NULL') . ',
							acquirer_response_code = ' . ((isset($transaction->acquirer_response_code)) ? '"' . $transaction->acquirer_response_code . '"' : 'NULL') . ',
							acquirer_name = ' . ((isset($transaction->acquirer_name)) ? '"' . $transaction->acquirer_name . '"' : 'NULL') . ',
							soft_descriptor = ' . ((isset($transaction->soft_descriptor)) ? '"' . $transaction->soft_descriptor . '"' : 'NULL') . ',
							cost = ' . ((isset($transaction->cost)) ? '"' . $transaction->cost . '"' : 'NULL') . ',
							postback_url = ' . ((isset($transaction->postback_url)) ? '"' . $transaction->postback_url . '"' : 'NULL') . ',
							capture_method = ' . ((isset($transaction->capture_method)) ? '"' . $transaction->capture_method . '"' : 'NULL') . ',
							antifraud_score = ' . ((isset($transaction->antifraud_score)) ? '"' . $transaction->antifraud_score . '"' : 'NULL') . ',
							boleto_url = ' . ((isset($transaction->boleto_url)) ? '"' . $transaction->boleto_url . '"' : 'NULL') . ',
							boleto_barcode = ' . ((isset($transaction->boleto_barcode)) ? '"' . $transaction->boleto_barcode . '"' : 'NULL') . ',
							boleto_expiration_date = ' . ((isset($transaction->boleto_expiration_date)) ? '"' . $transaction->boleto_expiration_date . '"' : 'NULL') . ',
							referer = ' . ((isset($transaction->referer)) ? '"' . $transaction->referer . '"' : 'NULL') . ',
							ip = ' . ((isset($transaction->ip)) ? '"' . $transaction->ip . '"' : 'NULL') . ',
							subscription_id = ' . ((isset($transaction->subscription_id)) ? '"' . $transaction->subscription_id . '"' : 'NULL') . ',
							phone_object = ' . ((isset($transaction->phone->object)) ? '"' . $transaction->phone->object . '"' : 'NULL') . ',
							phone_id = ' . ((isset($transaction->phone->id)) ? '"' . $transaction->phone->id . '"' : 'NULL') . ',
							phone_ddi = ' . ((isset($transaction->phone->ddi)) ? '"' . $transaction->phone->ddi . '"' : 'NULL') . ',
							phone_ddd = ' . ((isset($transaction->phone->ddd)) ? '"' . $transaction->phone->ddd . '"' : 'NULL') . ',
							phone_number = ' . ((isset($transaction->phone->number)) ? '"' . $transaction->phone->number . '"' : 'NULL') . ',
							address_object = ' . ((isset($transaction->address->object)) ? '"' . $transaction->address->object . '"' : 'NULL') . ',
							address_id = ' . ((isset($transaction->address->id)) ? '"' . $transaction->address->id . '"' : 'NULL') . ',
							address_street = ' . ((isset($transaction->address->street)) ? '"' . $transaction->address->street . '"' : 'NULL') . ',
							address_street_number = ' . ((isset($transaction->address->street_number)) ? '"' . $transaction->address->street_number . '"' : 'NULL') . ',
							address_neighborhood = ' . ((isset($transaction->address->neighborhood)) ? '"' . $transaction->address->neighborhood . '"' : 'NULL') . ',
							address_complementary = ' . ((isset($transaction->address->complementary)) ? '"' . $transaction->address->complementary . '"' : 'NULL') . ',
							address_zipcode = ' . ((isset($transaction->address->zipcode)) ? '"' . $transaction->address->zipcode . '"' : 'NULL') . ',
							address_country = ' . ((isset($transaction->address->country)) ? '"' . $transaction->address->country . '"' : 'NULL') . ',
							address_state = ' . ((isset($transaction->address->state)) ? '"' . $transaction->address->state . '"' : 'NULL') . ',
							address_city = ' . ((isset($transaction->address->city)) ? '"' . $transaction->address->city . '"' : 'NULL') . ',
							customer_object = ' . ((isset($transaction->customer->object)) ? '"' . $transaction->customer->object . '"' : 'NULL') . ',
							customer_id = ' . ((isset($transaction->customer->id)) ? '"' . $transaction->customer->id . '"' : 'NULL') . ',
							customer_date_created = ' . ((isset($transaction->customer->date_created)) ? '"' . $transaction->customer->date_created . '"' : 'NULL') . ',
							customer_document_type = ' . ((isset($transaction->customer->document_type)) ? '"' . $transaction->customer->document_type . '"' : 'NULL') . ',
							customer_document_number = ' . ((isset($transaction->customer->document_number)) ? '"' . $transaction->customer->document_number . '"' : 'NULL') . ',
							customer_name = ' . ((isset($transaction->customer->name)) ? '"' . $transaction->customer->name . '"' : 'NULL') . ',
							customer_email = ' . ((isset($transaction->customer->email)) ? '"' . $transaction->customer->email . '"' : 'NULL') . ',
							customer_born_at = ' . ((isset($transaction->customer->born_at)) ? '"' . $transaction->customer->born_at . '"' : 'NULL') . ',
							customer_gender = ' . ((isset($transaction->customer->gender)) ? '"' . $transaction->customer->gender . '"' : 'NULL') . ',
							card_object = ' . ((isset($transaction->card->object)) ? '"' . $transaction->card->object . '"' : 'NULL') . ',
							card_id = ' . ((isset($transaction->card->id)) ? '"' . $transaction->card->id . '"' : 'NULL') . ',
							card_date_created = ' . ((isset($transaction->card->date_created)) ? '"' . $transaction->card->date_created . '"' : 'NULL') . ',
							card_date_updated = ' . ((isset($transaction->card->date_updated)) ? '"' . $transaction->card->date_updated . '"' : 'NULL') . ',
							card_brand = ' . ((isset($transaction->card->brand)) ? '"' . $transaction->card->brand . '"' : 'NULL') . ',
							card_holder_name = ' . ((isset($transaction->card->holder_name)) ? '"' . $transaction->card->holder_name . '"' : 'NULL') . ',
							card_first_digits = ' . ((isset($transaction->card->first_digits)) ? '"' . $transaction->card->first_digits . '"' : 'NULL') . ',
							card_last_digits = ' . ((isset($transaction->card->last_digits)) ? '"' . $transaction->card->last_digits . '"' : 'NULL') . ',
							card_valid = ' . ((isset($transaction->card->valid)) ? '"' . $transaction->card->valid . '"' : 'NULL') . ',
							card_expiration_date = ' . ((isset($transaction->card->expiration_date)) ? '"' . $transaction->card->expiration_date . '"' : 'NULL') . ',
							card_country = ' . ((isset($transaction->card->country)) ? '"' . $transaction->card->country . '"' : 'NULL') . ',
							card_fingerprint = ' . ((isset($transaction->card->fingerprint)) ? '"' . $transaction->card->fingerprint . '"' : 'NULL') . ',
							metadata = ' . ((count($transaction->metadata) > 0) ? '"' . mysql_escape_string(json_encode((array) $transaction->metadata)) . '"' : 'NULL') . ',
							antifraud_metadata = ' . ((count($transaction->antifraud_metadata) > 0) ? '"' . mysql_escape_string(json_encode((array) $transaction->antifraud_metadata)) . '"' : 'NULL') . ',
							retorno_pagarme = ' . ((isset($transaction)) ? '"' . mysql_escape_string(json_encode((array) $transaction)) . '"' : 'NULL');
		        $salvar = $this->executaSql($sql);
		        $idpagarme = mysql_insert_id();

		        $dados = array(
		                        'amount' => str_replace('.', '', $conta['valor']),
		                        'metadata' => array(
		                                            'idconta' => $conta['idconta']
		                                        )
		                    );
		
		        //Captura a transação
		        $transaction->capture($dados);

		        $sqlModificacoesPagarme = 'SELECT * FROM pagarme WHERE idpagarme = ' . $idpagarme;
	       		$linhaAntiga = $this->retornarLinha($sqlModificacoesPagarme);

		        $sql = 'UPDATE
		                    pagarme
		                SET
							object = ' . ((isset($transaction->object)) ? '"' . $transaction->object . '"' : 'NULL') . ',
							status = ' . ((isset($transaction->status)) ? '"' . $transaction->status . '"' : 'NULL') . ',
							date_created = ' . ((isset($transaction->date_created)) ? '"' . $transaction->date_created . '"' : 'NULL') . ',
							date_updated = ' . ((isset($transaction->date_updated)) ? '"' . $transaction->date_updated . '"' : 'NULL') . ',
							payment_method = ' . ((isset($transaction->payment_method)) ? '"' . $transaction->payment_method . '"' : 'NULL') . ',
							installments = ' . ((isset($transaction->installments)) ? '"' . $transaction->installments . '"' : 'NULL') . ',
							amount = ' . ((isset($transaction->amount)) ? '"' . $transaction->amount . '"' : 'NULL') . ',
							authorized_amount = ' . ((isset($transaction->authorized_amount)) ? '"' . $transaction->authorized_amount . '"' : 'NULL') . ',
							paid_amount = ' . ((isset($transaction->paid_amount)) ? '"' . $transaction->paid_amount . '"' : 'NULL') . ',
							refunded_amount = ' . ((isset($transaction->refunded_amount)) ? '"' . $transaction->refunded_amount . '"' : 'NULL') . ',
							authorization_code = ' . ((isset($transaction->authorization_code)) ? '"' . $transaction->authorization_code . '"' : 'NULL') . ',
							tid = ' . ((isset($transaction->tid)) ? '"' . $transaction->tid . '"' : 'NULL') . ',
							nsu = ' . ((isset($transaction->nsu)) ? '"' . $transaction->nsu . '"' : 'NULL') . ',
							refuse_reason = ' . ((isset($transaction->refuse_reason)) ? '"' . $transaction->refuse_reason . '"' : 'NULL') . ',
							status_reason = ' . ((isset($transaction->status_reason)) ? '"' . $transaction->status_reason . '"' : 'NULL') . ',
							acquirer_response_code = ' . ((isset($transaction->acquirer_response_code)) ? '"' . $transaction->acquirer_response_code . '"' : 'NULL') . ',
							acquirer_name = ' . ((isset($transaction->acquirer_name)) ? '"' . $transaction->acquirer_name . '"' : 'NULL') . ',
							soft_descriptor = ' . ((isset($transaction->soft_descriptor)) ? '"' . $transaction->soft_descriptor . '"' : 'NULL') . ',
							cost = ' . ((isset($transaction->cost)) ? '"' . $transaction->cost . '"' : 'NULL') . ',
							postback_url = ' . ((isset($transaction->postback_url)) ? '"' . $transaction->postback_url . '"' : 'NULL') . ',
							capture_method = ' . ((isset($transaction->capture_method)) ? '"' . $transaction->capture_method . '"' : 'NULL') . ',
							antifraud_score = ' . ((isset($transaction->antifraud_score)) ? '"' . $transaction->antifraud_score . '"' : 'NULL') . ',
							boleto_url = ' . ((isset($transaction->boleto_url)) ? '"' . $transaction->boleto_url . '"' : 'NULL') . ',
							boleto_barcode = ' . ((isset($transaction->boleto_barcode)) ? '"' . $transaction->boleto_barcode . '"' : 'NULL') . ',
							boleto_expiration_date = ' . ((isset($transaction->boleto_expiration_date)) ? '"' . $transaction->boleto_expiration_date . '"' : 'NULL') . ',
							referer = ' . ((isset($transaction->referer)) ? '"' . $transaction->referer . '"' : 'NULL') . ',
							ip = ' . ((isset($transaction->ip)) ? '"' . $transaction->ip . '"' : 'NULL') . ',
							subscription_id = ' . ((isset($transaction->subscription_id)) ? '"' . $transaction->subscription_id . '"' : 'NULL') . ',
							phone_object = ' . ((isset($transaction->phone->object)) ? '"' . $transaction->phone->object . '"' : 'NULL') . ',
							phone_id = ' . ((isset($transaction->phone->id)) ? '"' . $transaction->phone->id . '"' : 'NULL') . ',
							phone_ddi = ' . ((isset($transaction->phone->ddi)) ? '"' . $transaction->phone->ddi . '"' : 'NULL') . ',
							phone_ddd = ' . ((isset($transaction->phone->ddd)) ? '"' . $transaction->phone->ddd . '"' : 'NULL') . ',
							phone_number = ' . ((isset($transaction->phone->number)) ? '"' . $transaction->phone->number . '"' : 'NULL') . ',
							address_object = ' . ((isset($transaction->address->object)) ? '"' . $transaction->address->object . '"' : 'NULL') . ',
							address_id = ' . ((isset($transaction->address->id)) ? '"' . $transaction->address->id . '"' : 'NULL') . ',
							address_street = ' . ((isset($transaction->address->street)) ? '"' . $transaction->address->street . '"' : 'NULL') . ',
							address_street_number = ' . ((isset($transaction->address->street_number)) ? '"' . $transaction->address->street_number . '"' : 'NULL') . ',
							address_neighborhood = ' . ((isset($transaction->address->neighborhood)) ? '"' . $transaction->address->neighborhood . '"' : 'NULL') . ',
							address_complementary = ' . ((isset($transaction->address->complementary)) ? '"' . $transaction->address->complementary . '"' : 'NULL') . ',
							address_zipcode = ' . ((isset($transaction->address->zipcode)) ? '"' . $transaction->address->zipcode . '"' : 'NULL') . ',
							address_country = ' . ((isset($transaction->address->country)) ? '"' . $transaction->address->country . '"' : 'NULL') . ',
							address_state = ' . ((isset($transaction->address->state)) ? '"' . $transaction->address->state . '"' : 'NULL') . ',
							address_city = ' . ((isset($transaction->address->city)) ? '"' . $transaction->address->city . '"' : 'NULL') . ',
							customer_object = ' . ((isset($transaction->customer->object)) ? '"' . $transaction->customer->object . '"' : 'NULL') . ',
							customer_id = ' . ((isset($transaction->customer->id)) ? '"' . $transaction->customer->id . '"' : 'NULL') . ',
							customer_date_created = ' . ((isset($transaction->customer->date_created)) ? '"' . $transaction->customer->date_created . '"' : 'NULL') . ',
							customer_document_type = ' . ((isset($transaction->customer->document_type)) ? '"' . $transaction->customer->document_type . '"' : 'NULL') . ',
							customer_document_number = ' . ((isset($transaction->customer->document_number)) ? '"' . $transaction->customer->document_number . '"' : 'NULL') . ',
							customer_name = ' . ((isset($transaction->customer->name)) ? '"' . $transaction->customer->name . '"' : 'NULL') . ',
							customer_email = ' . ((isset($transaction->customer->email)) ? '"' . $transaction->customer->email . '"' : 'NULL') . ',
							customer_born_at = ' . ((isset($transaction->customer->born_at)) ? '"' . $transaction->customer->born_at . '"' : 'NULL') . ',
							customer_gender = ' . ((isset($transaction->customer->gender)) ? '"' . $transaction->customer->gender . '"' : 'NULL') . ',
							card_object = ' . ((isset($transaction->card->object)) ? '"' . $transaction->card->object . '"' : 'NULL') . ',
							card_id = ' . ((isset($transaction->card->id)) ? '"' . $transaction->card->id . '"' : 'NULL') . ',
							card_date_created = ' . ((isset($transaction->card->date_created)) ? '"' . $transaction->card->date_created . '"' : 'NULL') . ',
							card_date_updated = ' . ((isset($transaction->card->date_updated)) ? '"' . $transaction->card->date_updated . '"' : 'NULL') . ',
							card_brand = ' . ((isset($transaction->card->brand)) ? '"' . $transaction->card->brand . '"' : 'NULL') . ',
							card_holder_name = ' . ((isset($transaction->card->holder_name)) ? '"' . $transaction->card->holder_name . '"' : 'NULL') . ',
							card_first_digits = ' . ((isset($transaction->card->first_digits)) ? '"' . $transaction->card->first_digits . '"' : 'NULL') . ',
							card_last_digits = ' . ((isset($transaction->card->last_digits)) ? '"' . $transaction->card->last_digits . '"' : 'NULL') . ',
							card_valid = ' . ((isset($transaction->card->valid)) ? '"' . $transaction->card->valid . '"' : 'NULL') . ',
							card_expiration_date = ' . ((isset($transaction->card->expiration_date)) ? '"' . $transaction->card->expiration_date . '"' : 'NULL') . ',
							card_country = ' . ((isset($transaction->card->country)) ? '"' . $transaction->card->country . '"' : 'NULL') . ',
							card_fingerprint = ' . ((isset($transaction->card->fingerprint)) ? '"' . $transaction->card->fingerprint . '"' : 'NULL') . ',
							metadata = ' . ((count($transaction->metadata) > 0) ? '"' . mysql_escape_string(json_encode((array) $transaction->metadata)) . '"' : 'NULL') . ',
							antifraud_metadata = ' . ((count($transaction->antifraud_metadata) > 0) ? '"' . mysql_escape_string(json_encode((array) $transaction->antifraud_metadata)) . '"' : 'NULL') . ',
							retorno_pagarme = ' . ((isset($transaction)) ? '"' . mysql_escape_string(json_encode((array) $transaction)) . '"' : 'NULL') . '
						WHERE
							idpagarme = ' . $idpagarme;
		        $salvar = $this->executaSql($sql);

				$linhaNova = $this->retornarLinha($sqlModificacoesPagarme);

	            $this->monitora_qual = $idpagarme;
	            $this->monitora_dadosantigos = $linhaAntiga;
	            $this->monitora_dadosnovos = $linhaNova;
	            $this->MonitoraPagarme();
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
      * Monitora todas as alterações feitas na tabela do pagarme
      * @access public
      * @param
      * @var int $this->monitora_qual: [Obrigatório] ID do pagarme que foi alterado
      * @var array $this->monitora_dadosantigos: [Obrigatório] Dados que estava antes da alteração no pagarme
      * @var array $this->monitora_dadosnovos: [Obrigatório] Dados que ficou após a alteração no pagarme
      * @return bool
      * @author Yuri Costa-Silva <yuric@alfamaweb.com.br>
    */
    private function MonitoraPagarme()
    {
        if (!$this->monitora_qual || !isset($this->monitora_dadosantigos) || !isset($this->monitora_dadosnovos)) {
            return false;
        } else {
        	$sql = 'INSERT INTO
						pagarme_monitora
					SET
						data_cad = NOW(),
						idpagarme = ' . $this->monitora_qual;
	        $this->executaSql($sql);
	        $idmonitora = mysql_insert_id();

            foreach ($this->monitora_dadosantigos as $ind => $var) {
                if ($this->monitora_dadosantigos[$ind] != $this->monitora_dadosnovos[$ind]) {
                    $sql = 'INSERT INTO
								pagarme_monitora_log
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
      * Retorna as contas que tenham pagamento sem retorno do pagar.me
      * @access public
      * @param
      * @var
      * @return array
      * @author Yuri Costa-Silva <yuric@alfamaweb.com.br>
    */
    public function retornaPagamentosSemRetorno()
    {
        $this->sql = 'SELECT 
							p.idpagarme,
							p.id,
							c.idconta
						FROM 
							pagarme p
							INNER JOIN contas c ON (c.idconta = p.idconta)
							INNER JOIN contas_workflow cw ON (cw.idsituacao = c.idsituacao)
						WHERE
							p.status IN ("processing", "authorized", "waiting_payment", "pending_refund") AND
							p.ativo = "S" AND
							cw.emaberto = "S" AND
							c.ativo = "S" AND
							p.cron = "N"';

        $this->groupby = 'c.idconta';
        $this->ordem_campo = 'c.idconta';
        $this->ordem = 'ASC';
        $this->limite = 30;

        $retorno = $this->retornarLinhas();

        if (!$retorno) {
        	$sql = 'UPDATE pagarme SET cron = "N"';
	        $this->executaSql($sql);
        }

        return $retorno;
    }

    /**
      * Busca uma transação feita
      * @access public
      * @param int $idpagarme: [Obrigatório] ID do pagarme, que será retornada os dados do BD.
      * @var
      * @return array
      * @author Yuri Costa-Silva <yuric@alfamaweb.com.br>
    */
	public function BuscarTransacao($idpagarme)
	{
		if (! $idpagarme) {
            $erros['erro'] = 'true';
            $erros['erros'][] = 'parametros_incompletos';
            return $erros;
        }

		$sql = 'SELECT
					p.*
				FROM
					pagarme p
				WHERE
					p.idpagarme = ' . (int)$idpagarme;
		return $this->retornarLinha($sql);
	}

    /**
     * Faz a consulta do pagamento e caso esteja pago, já da baixa no sistema
     * @access public
     * @param $idpagarme: ID do pagamento feito para realizar a busca
     * @var
     * @return array: Retorna array com erros, ou com sucesso
     * @author Yuri Costa-Silva <yuric@alfamaweb.com.br>
     */
	public function atualizaTransacao($idpagarme, $cron = false)
	{
		$retorno = array();

		$this->executaSql('BEGIN');

		//Retorna a transação efetuada
		$transacaoPagamento = $this->BuscarTransacao($idpagarme);

    	if ($transacaoPagamento) {
    		try {
    			if ($cron) {
    				$sql = 'UPDATE pagarme SET cron = "S" WHERE idpagarme = ' . $transacaoPagamento['idpagarme'];
		        	$this->executaSql($sql);
    			}

	    		require_once DIR_APP . '/classes/pagarme-php-master/Pagarme.php';
	    		Pagarme::setApiKey($GLOBALS['config']['pagarme']['api_key']);

				$transacaoObj = new \Transacoes();
				$interface = retornarInterface('pagarme_criar_transacao');
				$transacaoObj->iniciaTransacao($interface['id'], 'S');
				$transacaoObj->salvarJsonTransacaoEnviada($transacaoPagamento['id']);
				
				$transaction = PagarMe_Transaction::findById($transacaoPagamento['id']);
				
				$transacaoObj->salvarJsonTransacaoResposta((array) $transaction);
				$transacaoObj->finalizaTransacao(null, 2);

	    		if ($transaction) {
		    		$sqlModificacoesPagarme = 'SELECT * FROM pagarme WHERE idpagarme = ' . $idpagarme;
		       		$linhaAntiga = $this->retornarLinha($sqlModificacoesPagarme);

					$sql = 'UPDATE
								pagarme
							SET
								object = ' . ((isset($transaction->object)) ? '"' . $transaction->object . '"' : 'NULL') . ',
								status = ' . ((isset($transaction->status)) ? '"' . $transaction->status . '"' : 'NULL') . ',
								date_created = ' . ((isset($transaction->date_created)) ? '"' . $transaction->date_created . '"' : 'NULL') . ',
								date_updated = ' . ((isset($transaction->date_updated)) ? '"' . $transaction->date_updated . '"' : 'NULL') . ',
								payment_method = ' . ((isset($transaction->payment_method)) ? '"' . $transaction->payment_method . '"' : 'NULL') . ',
								installments = ' . ((isset($transaction->installments)) ? '"' . $transaction->installments . '"' : 'NULL') . ',
								amount = ' . ((isset($transaction->amount)) ? '"' . $transaction->amount . '"' : 'NULL') . ',
								authorized_amount = ' . ((isset($transaction->authorized_amount)) ? '"' . $transaction->authorized_amount . '"' : 'NULL') . ',
								paid_amount = ' . ((isset($transaction->paid_amount)) ? '"' . $transaction->paid_amount . '"' : 'NULL') . ',
								refunded_amount = ' . ((isset($transaction->refunded_amount)) ? '"' . $transaction->refunded_amount . '"' : 'NULL') . ',
								authorization_code = ' . ((isset($transaction->authorization_code)) ? '"' . $transaction->authorization_code . '"' : 'NULL') . ',
								tid = ' . ((isset($transaction->tid)) ? '"' . $transaction->tid . '"' : 'NULL') . ',
								nsu = ' . ((isset($transaction->nsu)) ? '"' . $transaction->nsu . '"' : 'NULL') . ',
								refuse_reason = ' . ((isset($transaction->refuse_reason)) ? '"' . $transaction->refuse_reason . '"' : 'NULL') . ',
								status_reason = ' . ((isset($transaction->status_reason)) ? '"' . $transaction->status_reason . '"' : 'NULL') . ',
								acquirer_response_code = ' . ((isset($transaction->acquirer_response_code)) ? '"' . $transaction->acquirer_response_code . '"' : 'NULL') . ',
								acquirer_name = ' . ((isset($transaction->acquirer_name)) ? '"' . $transaction->acquirer_name . '"' : 'NULL') . ',
								soft_descriptor = ' . ((isset($transaction->soft_descriptor)) ? '"' . $transaction->soft_descriptor . '"' : 'NULL') . ',
								cost = ' . ((isset($transaction->cost)) ? '"' . $transaction->cost . '"' : 'NULL') . ',
								postback_url = ' . ((isset($transaction->postback_url)) ? '"' . $transaction->postback_url . '"' : 'NULL') . ',
								capture_method = ' . ((isset($transaction->capture_method)) ? '"' . $transaction->capture_method . '"' : 'NULL') . ',
								antifraud_score = ' . ((isset($transaction->antifraud_score)) ? '"' . $transaction->antifraud_score . '"' : 'NULL') . ',
								boleto_url = ' . ((isset($transaction->boleto_url)) ? '"' . $transaction->boleto_url . '"' : 'NULL') . ',
								boleto_barcode = ' . ((isset($transaction->boleto_barcode)) ? '"' . $transaction->boleto_barcode . '"' : 'NULL') . ',
								boleto_expiration_date = ' . ((isset($transaction->boleto_expiration_date)) ? '"' . $transaction->boleto_expiration_date . '"' : 'NULL') . ',
								referer = ' . ((isset($transaction->referer)) ? '"' . $transaction->referer . '"' : 'NULL') . ',
								ip = ' . ((isset($transaction->ip)) ? '"' . $transaction->ip . '"' : 'NULL') . ',
								subscription_id = ' . ((isset($transaction->subscription_id)) ? '"' . $transaction->subscription_id . '"' : 'NULL') . ',
								phone_object = ' . ((isset($transaction->phone->object)) ? '"' . $transaction->phone->object . '"' : 'NULL') . ',
								phone_id = ' . ((isset($transaction->phone->id)) ? '"' . $transaction->phone->id . '"' : 'NULL') . ',
								phone_ddi = ' . ((isset($transaction->phone->ddi)) ? '"' . $transaction->phone->ddi . '"' : 'NULL') . ',
								phone_ddd = ' . ((isset($transaction->phone->ddd)) ? '"' . $transaction->phone->ddd . '"' : 'NULL') . ',
								phone_number = ' . ((isset($transaction->phone->number)) ? '"' . $transaction->phone->number . '"' : 'NULL') . ',
								address_object = ' . ((isset($transaction->address->object)) ? '"' . $transaction->address->object . '"' : 'NULL') . ',
								address_id = ' . ((isset($transaction->address->id)) ? '"' . $transaction->address->id . '"' : 'NULL') . ',
								address_street = ' . ((isset($transaction->address->street)) ? '"' . $transaction->address->street . '"' : 'NULL') . ',
								address_street_number = ' . ((isset($transaction->address->street_number)) ? '"' . $transaction->address->street_number . '"' : 'NULL') . ',
								address_neighborhood = ' . ((isset($transaction->address->neighborhood)) ? '"' . $transaction->address->neighborhood . '"' : 'NULL') . ',
								address_complementary = ' . ((isset($transaction->address->complementary)) ? '"' . $transaction->address->complementary . '"' : 'NULL') . ',
								address_zipcode = ' . ((isset($transaction->address->zipcode)) ? '"' . $transaction->address->zipcode . '"' : 'NULL') . ',
								address_country = ' . ((isset($transaction->address->country)) ? '"' . $transaction->address->country . '"' : 'NULL') . ',
								address_state = ' . ((isset($transaction->address->state)) ? '"' . $transaction->address->state . '"' : 'NULL') . ',
								address_city = ' . ((isset($transaction->address->city)) ? '"' . $transaction->address->city . '"' : 'NULL') . ',
								customer_object = ' . ((isset($transaction->customer->object)) ? '"' . $transaction->customer->object . '"' : 'NULL') . ',
								customer_id = ' . ((isset($transaction->customer->id)) ? '"' . $transaction->customer->id . '"' : 'NULL') . ',
								customer_date_created = ' . ((isset($transaction->customer->date_created)) ? '"' . $transaction->customer->date_created . '"' : 'NULL') . ',
								customer_document_type = ' . ((isset($transaction->customer->document_type)) ? '"' . $transaction->customer->document_type . '"' : 'NULL') . ',
								customer_document_number = ' . ((isset($transaction->customer->document_number)) ? '"' . $transaction->customer->document_number . '"' : 'NULL') . ',
								customer_name = ' . ((isset($transaction->customer->name)) ? '"' . $transaction->customer->name . '"' : 'NULL') . ',
								customer_email = ' . ((isset($transaction->customer->email)) ? '"' . $transaction->customer->email . '"' : 'NULL') . ',
								customer_born_at = ' . ((isset($transaction->customer->born_at)) ? '"' . $transaction->customer->born_at . '"' : 'NULL') . ',
								customer_gender = ' . ((isset($transaction->customer->gender)) ? '"' . $transaction->customer->gender . '"' : 'NULL') . ',
								card_object = ' . ((isset($transaction->card->object)) ? '"' . $transaction->card->object . '"' : 'NULL') . ',
								card_id = ' . ((isset($transaction->card->id)) ? '"' . $transaction->card->id . '"' : 'NULL') . ',
								card_date_created = ' . ((isset($transaction->card->date_created)) ? '"' . $transaction->card->date_created . '"' : 'NULL') . ',
								card_date_updated = ' . ((isset($transaction->card->date_updated)) ? '"' . $transaction->card->date_updated . '"' : 'NULL') . ',
								card_brand = ' . ((isset($transaction->card->brand)) ? '"' . $transaction->card->brand . '"' : 'NULL') . ',
								card_holder_name = ' . ((isset($transaction->card->holder_name)) ? '"' . $transaction->card->holder_name . '"' : 'NULL') . ',
								card_first_digits = ' . ((isset($transaction->card->first_digits)) ? '"' . $transaction->card->first_digits . '"' : 'NULL') . ',
								card_last_digits = ' . ((isset($transaction->card->last_digits)) ? '"' . $transaction->card->last_digits . '"' : 'NULL') . ',
								card_valid = ' . ((isset($transaction->card->valid)) ? '"' . $transaction->card->valid . '"' : 'NULL') . ',
								card_expiration_date = ' . ((isset($transaction->card->expiration_date)) ? '"' . $transaction->card->expiration_date . '"' : 'NULL') . ',
								card_country = ' . ((isset($transaction->card->country)) ? '"' . $transaction->card->country . '"' : 'NULL') . ',
								card_fingerprint = ' . ((isset($transaction->card->fingerprint)) ? '"' . $transaction->card->fingerprint . '"' : 'NULL') . ',
								metadata = ' . ((count($transaction->metadata) > 0) ? '"' . mysql_escape_string(json_encode((array) $transaction->metadata)) . '"' : 'NULL') . ',
								antifraud_metadata = ' . ((count($transaction->antifraud_metadata) > 0) ? '"' . mysql_escape_string(json_encode((array) $transaction->antifraud_metadata)) . '"' : 'NULL') . ',
								retorno_pagarme = ' . ((isset($transaction)) ? '"' . mysql_escape_string(json_encode((array) $transaction)) . '"' : 'NULL') . '
							WHERE
								idpagarme = ' . $transacaoPagamento['idpagarme'];
			        $salvar = $this->executaSql($sql);

					$linhaNova = $this->retornarLinha($sqlModificacoesPagarme);

		            $this->monitora_qual = $idpagarme;
		            $this->monitora_dadosantigos = $linhaAntiga;
		            $this->monitora_dadosnovos = $linhaNova;
		            $this->MonitoraPagarme();

		            $sql = 'UPDATE 
								contas
							SET
								autorizacao_cartao = ' . ((isset($transaction->authorization_code)) ? '"' . $transaction->authorization_code . '"' : 'NULL') . '
							WHERE
								idconta  = ' . $transacaoPagamento['idconta'];
					$this->executaSql($sql);

		            //Se status estiver como pago ou estornado
		    		if ($transaction->status == 'paid' || $transaction->status == 'refunded') {
		    			$date = new DateTime($transaction->date_updated);//Cria uma data com a data de atualização do transação
						$date->setTimezone(new DateTimeZone('America/Bahia'));//Seta o TimeZone da Bahia para a data

	        			//INÍCIO ATUALIZA SITUAÇÃO DA CONTA PARA SITUAÇÃO PAGA
	        			require_once DIR_APP . '/classes/contas.class.php';
			        	$contaObj = new Contas();
						$contaObj->Set('idusuario', $this->idusuario);
						$contaObj->Set('idescola', $this->idescola);
						$contaObj->Set('modulo',  $this->url[0]);

						//Busca os dados da conta
						$sqlModificacoesConta = 'SELECT idsituacao, idconta, idescola FROM contas WHERE idconta = ' . $transacaoPagamento['idconta'];
			  			$linhaAntigaConta = $this->retornarLinha($sqlModificacoesConta);

			  			if ($transaction->status == 'paid') {
			  				//Retorna o id da situação paga da conta
							$situacaoConta = $contaObj->retornarSituacaoPago();

							$valor_pago = '"' . ($transaction->paid_amount / 100) . '"';
							$data_pagamento = '"' . $date->format('Y-m-d') . '"';

							if (! empty($linhaAntigaConta['idescola'])) {
								$sql = 'SELECT 
										COUNT(c.idconta) AS total
									FROM 
										escolas e 
										INNER JOIN contas c ON (e.idescola = c.idescola) 
										INNER JOIN contas_workflow cw ON (cw.idsituacao = c.idsituacao) 
									WHERE
										e.idescola = ' . $linhaAntigaConta['idescola'] . ' AND
										e.acesso_bloqueado = "S" AND
										c.idconta <> ' . $linhaAntigaConta['idconta'] . ' AND
										DATE_FORMAT(NOW(), "%Y-%m-%d") >= DATE_ADD(c.data_vencimento, INTERVAL 3 DAY) AND
										c.ativo = "S" AND 
										cw.emaberto = "S"';
								$contaAtrasada = $this->retornarLinha($sql);

								if ($contaAtrasada['total'] == 0) {
									$sql = 'UPDATE escolas SET acesso_bloqueado = "N" WHERE idescola = ' . $linhaAntigaConta['idescola'];
									$this->executaSql($sql);

									$sql = 'INSERT INTO escolas_historico SET data_cad = NOW(),
										idusuario = NULL, acesso_bloqueado = "N", idescola = ' . $linhaAntigaConta['idescola'] ;
						            $this->executaSql($sql);
								}
							}
			  			} else {//Pagamento estornado
			  				//Retorna o id da situação em aberto da conta
							$situacaoEmAberto = $contaObj->retornarSituacaoEmAberto();
							$situacaoConta = $situacaoEmAberto['idsituacao'];

							$valor_pago = 'NULL';
							$data_pagamento = 'NULL';
			  			}

			  			//Atualiza a situação da conta
			  			$sql = 'UPDATE
		  							contas
		  						SET
		  							data_modificacao_fatura = NOW(),
		  							idsituacao = ' . $situacaoConta . ',
		  							valor_pago = ' . $valor_pago . ',
		  							data_pagamento = ' . $data_pagamento . '
		  						WHERE
		  							idconta = ' . $transacaoPagamento['idconta'];
						$this->executaSql($sql);	

						//Busca os novos dados da conta
						$linhaNovaConta = $this->retornarLinha($sqlModificacoesConta);

						//Adiciona o histórico da mudança da situação da conta
						$contaObj->Set('id', $transacaoPagamento['idconta']);
						$contaObj->AdicionarHistorico('situacao', 'modificou', $linhaAntigaConta['idsituacao'], $linhaNovaConta['idsituacao']);
						//FIM ATUALIZA SITUAÇÃO DA CONTA PARA SITUAÇÃO PAGA
		    		}

		    		$this->executaSql('COMMIT');
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
      * Cria uma transação de boleto no Pagar.me, e vincula a mesma com a conta.
      * @access public
      * @param int $idconta: [Obrigatório] ID da conta para qual será gerado o boleto
      * @var int $this->idescola: [Opcional] ID da escola da conta para qual será gerada o boleto
      * @return void
      * @author Yuri Costa-Silva <yuric@alfamaweb.com.br>
    */
    public function criarTransacaoBoleto($idconta)
    {
        if (empty($idconta)) {
            $erros[] = 'idconta_vazio';
        } else {
        	require_once DIR_APP . '/classes/contas.class.php';
        	$contaObj = new Contas();
			$conta = $contaObj->set('idusuario', $this->idusuario)
				->set('idescola', $this->idescola)
				->set('modulo',  $this->url[0])
				->set('id',  (int) $idconta)
				->set('campos', 'c.idsituacao, c.idconta, c.valor, c.data_vencimento, c.idescola')
				->retornar();

	        if (empty($conta)) {
	            $erros[] = 'conta_nao_existe';
	        }
        }

        if (! empty($erros)) {
            $retorno['erro'] = true;
            $retorno['erros'] = $erros;
        } else {
        	try {
        		require_once DIR_APP . '/classes/escolas.class.php';
	        	$escolabj = new Escolas();
				$escola = $escolabj->set('modulo',  $this->url[0])
					->set('id',  $conta['idescola'])
					->set('campos', 'p.idescola, p.idestado, p.idcidade, p.idsindicato, p.nome_fantasia AS escola, p.documento, p.email, p.documento_tipo,
						CONCAT_WS(" ", l.nome, p.endereco) AS endereco, p.numero, p.complemento,
						p.bairro, c.nome AS cidade, e.sigla AS uf, p.cep, p.telefone')
					->retornar();

				require_once DIR_APP . '/classes/pagarme-php-master/Pagarme.php';
				Pagarme::setApiKey($GLOBALS['config']['pagarme']['api_key']);

				require_once DIR_APP . '/classes/contas.class.php';
				$contasObj = new Contas();
				$valorCorrigido = $contasObj->calcularJurosMulta($conta['idconta']);

				$valorConta = $conta['valor'];
				if (! empty($valorCorrigido['valor_corrigido'])) {
					$valorConta = number_format($valorCorrigido['valor_corrigido'], 2, '.', '');
				}

				$hoje = new DateTime();
				$vencimentoBoleto = $valorCorrigido['proximoDiaUtilVencimento']->format('c');
				if ($valorCorrigido['proximoDiaUtilVencimento']->format('Y-m-d') < $hoje->format('Y-m-d')) {
					$hoje->modify('+1 day');//Gera para amanhã, pq o Pagar.me não aceita vencimento para o mesmo dia.
					$vencimentoBoleto = proximoDiaUtil(
						$hoje->format('Y-m-d'),
						$escola['idestado'],
						$escola['idcidade'],
						$escola['idescola'],
						$escola['idsindicato']
					);
					$vencimentoBoleto = $vencimentoBoleto->format('c');
				}

				$transacaoArray = [
			        'amount' => str_replace('.', '', $valorConta),
			        'payment_method' => 'boleto',
			        'postback_url' => $GLOBALS['config']['pagarme']['postback_url'],
			        'metadata' => ['idconta' => $conta['idconta']],
			        'boleto_expiration_date' => $vencimentoBoleto,
	               	'customer' => [
	               		'name' => $escola['escola'],
	               		'document_number' => $escola['documento']
	               	]
			    ];

			    $telefone = str_replace(['(', ')', '-', ' '], '', $escola['telefone']);

				if (
                    $escola['email']
                    && $escola['endereco']
                    && $escola['numero']
                    && $escola['bairro']
                    && $escola['cidade']
                    && $escola['uf']
                    && $escola['cep']
                    && substr($telefone, 0, 2)
                    && substr($telefone, 2)
                ) {
	                $transacaoArray['customer']['email'] = $escola['email'];
	                $transacaoArray['customer']['address']['street'] = $escola['endereco'];
	                $transacaoArray['customer']['address']['street_number'] = $escola['numero'];
	                $transacaoArray['customer']['address']['complementary'] = $escola['complemento'];
	                $transacaoArray['customer']['address']['neighborhood'] = $escola['bairro'];
	                $transacaoArray['customer']['address']['city'] = $escola['cidade'];
	                $transacaoArray['customer']['address']['state'] = $escola['uf'];
	                $transacaoArray['customer']['address']['zipcode'] = $escola['cep'];
	                $transacaoArray['customer']['phone']['ddd'] = substr($telefone, 0, 2);
	                $transacaoArray['customer']['phone']['number'] = substr($telefone, 2);
                }

				$transacaoObj = new \Transacoes();
				$interface = retornarInterface('pagarme_criar_boleto');
				$transacaoObj->iniciaTransacao($interface['id'], 'S');
				$transacaoObj->salvarJsonTransacaoEnviada($transacaoArray);
				
				
			    $transaction = new PagarMe_Transaction($transacaoArray);
			    $transaction->charge();
				$transacaoObj->salvarJsonTransacaoResposta((array) $transaction);
				$transacaoObj->finalizaTransacao(null, 2);
				
				if(empty($escola['documento'])){
					throw new Exception('Documento '.strtoupper($escola['documento_tipo']).' não informado no cadastro do CFC!', 422);
				}

	        	$sql = 'INSERT INTO
		                    pagarme
		                SET
							ativo = "S",
							data_cad = NOW(),
							idconta = ' . $conta['idconta'] . ',
							object = ' . ((isset($transaction->object)) ? '"' . $transaction->object . '"' : 'NULL') . ',
							id = ' . ((isset($transaction->id)) ? '"' . $transaction->id . '"' : 'NULL') . ',
							status = ' . ((isset($transaction->status)) ? '"' . $transaction->status . '"' : 'NULL') . ',
							date_created = ' . ((isset($transaction->date_created)) ? '"' . $transaction->date_created . '"' : 'NULL') . ',
							date_updated = ' . ((isset($transaction->date_updated)) ? '"' . $transaction->date_updated . '"' : 'NULL') . ',
							payment_method = ' . ((isset($transaction->payment_method)) ? '"' . $transaction->payment_method . '"' : 'NULL') . ',
							installments = ' . ((isset($transaction->installments)) ? '"' . $transaction->installments . '"' : 'NULL') . ',
							amount = ' . ((isset($transaction->amount)) ? '"' . $transaction->amount . '"' : 'NULL') . ',
							authorized_amount = ' . ((isset($transaction->authorized_amount)) ? '"' . $transaction->authorized_amount . '"' : 'NULL') . ',
							paid_amount = ' . ((isset($transaction->paid_amount)) ? '"' . $transaction->paid_amount . '"' : 'NULL') . ',
							refunded_amount = ' . ((isset($transaction->refunded_amount)) ? '"' . $transaction->refunded_amount . '"' : 'NULL') . ',
							authorization_code = ' . ((isset($transaction->authorization_code)) ? '"' . $transaction->authorization_code . '"' : 'NULL') . ',
							tid = ' . ((isset($transaction->tid)) ? '"' . $transaction->tid . '"' : 'NULL') . ',
							nsu = ' . ((isset($transaction->nsu)) ? '"' . $transaction->nsu . '"' : 'NULL') . ',
							refuse_reason = ' . ((isset($transaction->refuse_reason)) ? '"' . $transaction->refuse_reason . '"' : 'NULL') . ',
							status_reason = ' . ((isset($transaction->status_reason)) ? '"' . $transaction->status_reason . '"' : 'NULL') . ',
							acquirer_response_code = ' . ((isset($transaction->acquirer_response_code)) ? '"' . $transaction->acquirer_response_code . '"' : 'NULL') . ',
							acquirer_name = ' . ((isset($transaction->acquirer_name)) ? '"' . $transaction->acquirer_name . '"' : 'NULL') . ',
							soft_descriptor = ' . ((isset($transaction->soft_descriptor)) ? '"' . $transaction->soft_descriptor . '"' : 'NULL') . ',
							cost = ' . ((isset($transaction->cost)) ? '"' . $transaction->cost . '"' : 'NULL') . ',
							postback_url = ' . ((isset($transaction->postback_url)) ? '"' . $transaction->postback_url . '"' : 'NULL') . ',
							capture_method = ' . ((isset($transaction->capture_method)) ? '"' . $transaction->capture_method . '"' : 'NULL') . ',
							antifraud_score = ' . ((isset($transaction->antifraud_score)) ? '"' . $transaction->antifraud_score . '"' : 'NULL') . ',
							boleto_url = ' . ((isset($transaction->boleto_url)) ? '"' . $transaction->boleto_url . '"' : 'NULL') . ',
							boleto_barcode = ' . ((isset($transaction->boleto_barcode)) ? '"' . $transaction->boleto_barcode . '"' : 'NULL') . ',
							boleto_expiration_date = ' . ((isset($transaction->boleto_expiration_date)) ? '"' . $transaction->boleto_expiration_date . '"' : 'NULL') . ',
							referer = ' . ((isset($transaction->referer)) ? '"' . $transaction->referer . '"' : 'NULL') . ',
							ip = ' . ((isset($transaction->ip)) ? '"' . $transaction->ip . '"' : 'NULL') . ',
							subscription_id = ' . ((isset($transaction->subscription_id)) ? '"' . $transaction->subscription_id . '"' : 'NULL') . ',
							phone_object = ' . ((isset($transaction->phone->object)) ? '"' . $transaction->phone->object . '"' : 'NULL') . ',
							phone_id = ' . ((isset($transaction->phone->id)) ? '"' . $transaction->phone->id . '"' : 'NULL') . ',
							phone_ddi = ' . ((isset($transaction->phone->ddi)) ? '"' . $transaction->phone->ddi . '"' : 'NULL') . ',
							phone_ddd = ' . ((isset($transaction->phone->ddd)) ? '"' . $transaction->phone->ddd . '"' : 'NULL') . ',
							phone_number = ' . ((isset($transaction->phone->number)) ? '"' . $transaction->phone->number . '"' : 'NULL') . ',
							address_object = ' . ((isset($transaction->address->object)) ? '"' . $transaction->address->object . '"' : 'NULL') . ',
							address_id = ' . ((isset($transaction->address->id)) ? '"' . $transaction->address->id . '"' : 'NULL') . ',
							address_street = ' . ((isset($transaction->address->street)) ? '"' . $transaction->address->street . '"' : 'NULL') . ',
							address_street_number = ' . ((isset($transaction->address->street_number)) ? '"' . $transaction->address->street_number . '"' : 'NULL') . ',
							address_neighborhood = ' . ((isset($transaction->address->neighborhood)) ? '"' . $transaction->address->neighborhood . '"' : 'NULL') . ',
							address_complementary = ' . ((isset($transaction->address->complementary)) ? '"' . $transaction->address->complementary . '"' : 'NULL') . ',
							address_zipcode = ' . ((isset($transaction->address->zipcode)) ? '"' . $transaction->address->zipcode . '"' : 'NULL') . ',
							address_country = ' . ((isset($transaction->address->country)) ? '"' . $transaction->address->country . '"' : 'NULL') . ',
							address_state = ' . ((isset($transaction->address->state)) ? '"' . $transaction->address->state . '"' : 'NULL') . ',
							address_city = ' . ((isset($transaction->address->city)) ? '"' . $transaction->address->city . '"' : 'NULL') . ',
							customer_object = ' . ((isset($transaction->customer->object)) ? '"' . $transaction->customer->object . '"' : 'NULL') . ',
							customer_id = ' . ((isset($transaction->customer->id)) ? '"' . $transaction->customer->id . '"' : 'NULL') . ',
							customer_date_created = ' . ((isset($transaction->customer->date_created)) ? '"' . $transaction->customer->date_created . '"' : 'NULL') . ',
							customer_document_type = ' . ((isset($transaction->customer->document_type)) ? '"' . $transaction->customer->document_type . '"' : 'NULL') . ',
							customer_document_number = ' . ((isset($transaction->customer->document_number)) ? '"' . $transaction->customer->document_number . '"' : 'NULL') . ',
							customer_name = ' . ((isset($transaction->customer->name)) ? '"' . $transaction->customer->name . '"' : 'NULL') . ',
							customer_email = ' . ((isset($transaction->customer->email)) ? '"' . $transaction->customer->email . '"' : 'NULL') . ',
							customer_born_at = ' . ((isset($transaction->customer->born_at)) ? '"' . $transaction->customer->born_at . '"' : 'NULL') . ',
							customer_gender = ' . ((isset($transaction->customer->gender)) ? '"' . $transaction->customer->gender . '"' : 'NULL') . ',
							card_object = ' . ((isset($transaction->card->object)) ? '"' . $transaction->card->object . '"' : 'NULL') . ',
							card_id = ' . ((isset($transaction->card->id)) ? '"' . $transaction->card->id . '"' : 'NULL') . ',
							card_date_created = ' . ((isset($transaction->card->date_created)) ? '"' . $transaction->card->date_created . '"' : 'NULL') . ',
							card_date_updated = ' . ((isset($transaction->card->date_updated)) ? '"' . $transaction->card->date_updated . '"' : 'NULL') . ',
							card_brand = ' . ((isset($transaction->card->brand)) ? '"' . $transaction->card->brand . '"' : 'NULL') . ',
							card_holder_name = ' . ((isset($transaction->card->holder_name)) ? '"' . $transaction->card->holder_name . '"' : 'NULL') . ',
							card_first_digits = ' . ((isset($transaction->card->first_digits)) ? '"' . $transaction->card->first_digits . '"' : 'NULL') . ',
							card_last_digits = ' . ((isset($transaction->card->last_digits)) ? '"' . $transaction->card->last_digits . '"' : 'NULL') . ',
							card_valid = ' . ((isset($transaction->card->valid)) ? '"' . $transaction->card->valid . '"' : 'NULL') . ',
							card_expiration_date = ' . ((isset($transaction->card->expiration_date)) ? '"' . $transaction->card->expiration_date . '"' : 'NULL') . ',
							card_country = ' . ((isset($transaction->card->country)) ? '"' . $transaction->card->country . '"' : 'NULL') . ',
							card_fingerprint = ' . ((isset($transaction->card->fingerprint)) ? '"' . $transaction->card->fingerprint . '"' : 'NULL') . ',
							metadata = ' . ((count($transaction->metadata) > 0) ? '"' . mysql_escape_string(json_encode((array) $transaction->metadata)) . '"' : 'NULL') . ',
							antifraud_metadata = ' . ((count($transaction->antifraud_metadata) > 0) ? '"' . mysql_escape_string(json_encode((array) $transaction->antifraud_metadata)) . '"' : 'NULL') . ',
							retorno_pagarme = ' . ((isset($transaction)) ? '"' . mysql_escape_string(json_encode((array) $transaction)) . '"' : 'NULL');
		        $salvar = $this->executaSql($sql);
            } catch (Exception $e) {
		        $retorno['exception'] = nl2br($e->getMessage());
				$transacaoObj->finalizaTransacao(null, 3, json_encode(['codigo' => $e->getCode(), 'mensagem' => $e->getMessage()]));
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
}