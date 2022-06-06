<?php
/**
 *
 */
class FastConnect extends Core
{
    CONST FORMA_PAGAMENTO_BOLETO = 'B';
    CONST FORMA_PAGAMENTO_CARTAO = 'C';

    private $url_pgto = null;    // URL do ambiente sandbox do Fastconnect
    private $cliente_code = null;//
    private $client_key = null;  //
    private $url_pgto_retorno = null; // [Opcional] -> Url de callback, Sempre que houver mudança de status no pagamento, será enviada uma requisição POST com os dados da transação. Ex.: https://callback.meusite.com
    public $situacao_pgto = null;

    // Dados do comprador
    // nm_cliente  // [Obrigatório] -> Nome completo do comprador
    // nu_documento// [Obrigatório] ->  CPF ou CNPJ do comprador
    // ds_email    // [Obrigatório] -> Email do comprador
    // nu_telefone // [Obrigatório] -> Número do celular do comprador
    // ds_cep      // [Opcional] -> CEP do comprador
    // ds_endereco // [Opcional] -> Endereço do comprador
    // ds_complemento // [Opcional] -> Complemento do endereço do comprador
    // ds_numero   // [Opcional] -> Número do endereço do comprador
    // ds_bairro   // [Opcional] -> Nome do bairro do comprador
    // nm_cidade   // [Opcional] -> Nome da cidade do comprador
    // nm_estado   // [Opcional] -> Sigla do estado do comprador

    // Dados do cartão de crédito
    // ds_softdescriptor // [Opcional] -> Descrição que irá aparecer na fatura do cartão de crédito do comprador. Somente letras maiúsculas
    // ds_cartao_token // [Opcional] 
    // nm_bandeira // [Obrigatório, se ds _cartao _token não for informado] -> Bandeira do cartão. As bandeiras disponíveis são: visa, master, diners, elo, amex, aura, hipercard, jcb, discover. Somente letras minúsculas 
    // nu_cartao   // [Obrigatório, se ds _cartao _token não for informado] -> Número do cartão
    // nm_titular  // [Obrigatório, se ds _cartao _token não for informado] -> Nome do titular do cartão
    // dt_validade // [Obrigatório, se ds _cartao _token não for informado] -> Validade do cartão. Formato: MM/YY
    // tp_capturar // [Obrigatório, se ds _cartao _token não for informado] -> Efetua a cobrança na hora da transação. Padrão: true.

    //Dados do boleto
    // vl_juros
    // vl_multa
    // ds_info
    // ds_instrucao
    // gerar

    // Dados referentes à compra
    // vl_total     // [Obrigatório] -> Valor total da compra
    // dt_vencimento// [Opcional] ->  Data de vencimento da compra. Padrão: data atual
    // dt_cobranca  // [Opcional] -> Caso queira agendar o pagamento para outro dia. Padrão: data atual
    // dia_cobranca // [Opcional] -> Caso o tipo _venda for "AS", você pode indicar o melhor dia de cobrança. Padrão: 01.
    // nu_referencia// [Opcional] -> Número de referência da compra (gerado aleatoriamente pelo sistema)
    // nu_parcelas  // [Obrigatório] -> Quantidade de parcelas da compra
    // tipo_venda   // [Obrigatório] -> Tipo de venda efetuada. Os tipos são: AV = (A Vista), PB = (Parcelado pelo banco), PL = (Parcelado pela loja), AS = (Assinatura). Padrão: AV

    // Dados de retorno
    // situacao:
    //     - ATIVO
    //     - ABERTO
    //     - CANCELADO
    //     - CANCELADO POR ESTORNO
    //
    // outras situações (fornecidas pelo suporte do fastconnect):
    //     - PAGO     = Pagamento realizado
    //     - PENDENTE = Aguardando pagamento, ocorre quando é feito um agendamento / captura no crédito ou quando um boleto for gerado.

    /**
     * [__construct]
     */
    public function __construct($cliente_code = null, $client_key = null)
    {
        parent::__construct();

        $this->url_pgto = $GLOBALS['config']['fastConnect']['url_producao'];
        $this->url_pgto_retorno = $GLOBALS['config']['fastConnect']['url_retorno'];
        $this->cliente_code = empty($cliente_code) ? $GLOBALS['config']['fastConnect']['cliente_code'] : $cliente_code;
        $this->client_key = empty($client_key) ? $GLOBALS['config']['fastConnect']['client_key'] : $client_key;

        $this->situacao_pgto = $GLOBALS['situacoesTransacaoFastConnect']['pt_br'];
    }

    /**
     * [tratarCampos]
     * @param  [array] $arrDados
     * @return [array]
     */
    public function tratarCampos($arrDados){

        $dtatual = date("Y-m-d");

        if($arrDados['tipo'] == 'boleto') {
            $arrDados['gerar'] = 'true';
        }

        if ($arrDados['nu_telefone']) {
            $arrDados['nu_telefone'] = str_replace(array('(', ')', '-', ' '), '', $arrDados['nu_telefone']);
        }

        if ($arrDados['nu_cartao']){
            $card = explode(" ", $arrDados['nu_cartao']);
            $arrDados['nu_cartao'] = $card[0].$card[1].$card[2].$card[3];

            $arrDados['tipo'] = 'cartao';
            $arrDados['gerar'] = 'false';
            $arrDados['tp_capturar'] = true;
        }

        if ($arrDados['dt_validade']){
            $dt = explode(" ",$arrDados['dt_validade']);
            $arrDados['dt_validade'] = $dt[0].$dt[1].$dt[2];
        }

        // Mesmo setando a data de cobrança de acordo com o config, o boleto do fastconnect chegava com 7 dias a mais.  - 7
        $arrDados['dt_cobranca'] = (new DateTime($dtatual))
            //->modify('+' . ($GLOBALS['config']['dias_vencimento_conta']) . ' days')
            ->format('Y-m-d');

        $arrDados['dt_vencimento'] = (new DateTime($dtatual))
            ->modify('+' . $GLOBALS['config']['dias_vencimento_conta'] . ' days')
            ->format('Y-m-d');

        $arrDados['nm_bandeira'] = ($arrDados['nm_bandeira'] == "mastercard") ? "master" : $arrDados['nm_bandeira'];

        return $arrDados;
    }

    /**
     * [setCamposJson]
     */
    public function setCamposJson($arrDados){
        $campos = "";

        $arrDados = $this->tratarCampos($arrDados);

        $campos.= '{
            "url_retorno":"'.$this->url_pgto_retorno.'",
            "nm_cliente":"'.$arrDados['nm_cliente'].'", 
            "nu_documento":"'.str_replace('-','', str_replace('.', '', $arrDados['nu_documento'])).'",
            "ds_email":"'.$arrDados['ds_email'].'", 
            "nu_telefone":"'.$arrDados['nu_telefone'].'",
            "vl_total":'.$arrDados['vl_total'].', 
            "nu_referencia":"'.$arrDados['nu_referencia'].'", 
            "nu_parcelas":'.$arrDados['nu_parcelas'].', 
            "tipo_venda":"'.$arrDados['tipo_venda'].'",
            "ds_cep":"'.$arrDados['ds_cep'].'",
            "ds_endereco":"'.$arrDados['ds_endereco'].'",
            "ds_bairro":"'.$arrDados['ds_bairro'].'",
            "ds_complemento":"'.$arrDados['ds_complemento'].'", 
            "ds_numero":'.$arrDados['ds_numero'].', 
            "nm_cidade":"'.$arrDados['nm_cidade'].'", 
            "nm_estado":"'.$arrDados['nm_estado'].'", 
            "dt_vencimento":"'.$arrDados['dt_vencimento'].'",
            "dt_cobranca":"'.$arrDados['dt_cobranca'].'"';

        if ($arrDados['tipo_venda'] != 'AV') {
            $campos .= empty($arrDados['tp_capturar']) ? '' : ',"tp_capturar":' . $arrDados['tp_capturar'] . '';
            $campos .= empty($arrDados['nu_cartao']) ? '' : ',"nu_cartao":"' . $arrDados['nu_cartao'] . '"';
            $campos .= empty($arrDados['nm_bandeira']) ? '' : ',"nm_bandeira":"' . $arrDados['nm_bandeira'] . '"';
            $campos .= empty($arrDados['nm_titular']) ? '' : ',"nm_titular":"' . $arrDados['nm_titular'] . '"';
            $campos .= empty($arrDados['dt_validade']) ? '' : ',"dt_validade":"' . $arrDados['dt_validade'] . '"';
        }

        $campos.= !isset($arrDados['gerar']) ? '' : ',"gerar":'.$arrDados['gerar'].'';

        $campos.= !isset($arrDados['vl_parcela']) ? '' : ',"vl_parcela":"'.$arrDados['vl_parcela'].'"';
        $campos.= !isset($arrDados['vl_venda']) ? '' : ',"vl_venda":"'.$arrDados['vl_venda'].'"';
        $campos.= !isset($arrDados['dt_pagamento']) ? '' : ',"dt_pagamento":"'.$arrDados['dt_pagamento'].'"';
        $campos.= !isset($arrDados['nu_venda']) ? '' : ',"nu_venda":"'.$arrDados['nu_venda'].'"';
        $campos.= !isset($arrDados['dia_cobranca']) ? ',"dia_cobranca":'. 01 : ',"dia_cobranca":'.$arrDados['dia_cobranca'].'';

        $campos.= !isset($arrDados['ds_cartao_token']) ? '' : ',"ds_cartao_token":"'.$arrDados['ds_cartao_token'].'"';
        $campos.= !isset($arrDados['ds_mascara_cartao']) ? '' : ',"ds_mascara_cartao":"'.$arrDados['ds_mascara_cartao'].'"';
        $campos.= !isset($arrDados['vl_juros']) ? '' : ',"vl_juros":"'.$arrDados['vl_juros'].'"';
        $campos.= !isset($arrDados['vl_multa']) ? '' : ',"vl_multa":"'.$arrDados['vl_multa'].'"';
        $campos.= !isset($arrDados['ds_info']) ? '' : ',"ds_info":"'.$arrDados['ds_info'].'"';
        $campos.= !isset($arrDados['ds_instrucao']) ? '' : ',"ds_instrucao":"'.$arrDados['ds_instrucao'].'"';

        $campos.= '}';

        return $campos;
    }

    /**
     * [getIdSituacao]
     * @param  [string] $situacao
     * @return [integer]
     */
    public function getIdSituacao($situacao){
        //  Status fastConnect
        //   1 => 'ATIVO', 
        //   2 => 'Aberto',
        //   3 => 'Pago',
        //   4 => 'Pendente',
        //   5 => 'CAncelado',
        //   6 => 'Cancelado por estorno'

        $idsituacao = array_search($situacao, $this->situacao_pgto);
        $retornoId = ($idsituacao) ? $idsituacao : 0; // caso retorne false, será setado 0 (zero).

        return $retornoId;
    }

    /**
     * [fazerTransacaoCartao]
     * @return
     */
    public function fazerTransacaoCartao($arrDados)
    {
        $fields = $this->setCamposJson($arrDados);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url_pgto."/credito",
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $fields,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Client-Code: $this->cliente_code",
                "Client-key: $this->client_key"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $retorno = ($err) ? "cURL Error #:" . $err : $response;
        $arrRetorno = json_decode($retorno,true);

        if ($arrRetorno['success']){
            $arrDados['idsituacao'] = $this->getIdSituacao($arrRetorno['data']['situacao']);
            $arrDados['nu_cartao'] = str_pad(substr($arrDados['nu_cartao'], -4), 16, "X", STR_PAD_LEFT);

            $arrDados = array_merge($arrDados,$arrRetorno['data']);

            $salvou = $this->salvarDados($arrDados);
        }

        if (!($salvou['sucesso'])){
            $dadosLog = ['metodo' => 'fazerTransacaoCartao','retorno' => $retorno, 'envio' => $fields];
        }
        else{
            $dadosLog = ['metodo' => 'fazerTransacaoCartao','retorno' => $retorno, 'envio' => $fields, 'idfastconnect' => $salvou['id']];
            $arrRetorno['idfastconnect'] = $salvou['id'];
        }

        $dadosLog = ($arrRetorno['success']) ? array_merge($dadosLog,$arrRetorno['data']) : array_merge($dadosLog,$arrRetorno['errors']);
        $salvouLog = $this->salvarLog($dadosLog);

        return $arrRetorno;
    }

    /**
     * [gerarBoleto]
     * @return
     */
    public function gerarBoleto($arrDados){
        $arrDados['tipo_venda'] = 'AV';

        $fields = $this->setCamposJson($arrDados);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url_pgto."/boleto",
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $fields,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Client-Code: $this->cliente_code",
                "Client-key: $this->client_key"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $retorno = ($err) ? "cURL Error #:" . $err : $response;
        $arrRetorno = json_decode($retorno,true);

        if ($arrRetorno['success']){
            $arrDados['idsituacao'] = $this->getIdSituacao($arrRetorno['data']['situacao']);

            $arrDados = array_merge($arrDados,$arrRetorno['data']);

            $salvou = $this->salvarDados($arrDados);
        }

        if (!($salvou['sucesso'])){
            $dadosLog = ['metodo' => 'gerarBoleto','retorno' => $retorno, 'envio' => $fields];
        }
        else{
            $dadosLog = ['metodo' => 'gerarBoleto','retorno' => $retorno, 'envio' => $fields, 'idfastconnect' => $salvou['id']];
            $arrRetorno['idfastconnect'] = $salvou['id'];
        }

        $dadosLog = ($arrRetorno['success']) ? array_merge($dadosLog,$arrRetorno['data']) : array_merge($dadosLog,$arrRetorno['errors']);
        $salvouLog = $this->salvarLog($dadosLog);

        return $arrRetorno;
    }

    /**
     * [gerarBoleto]
     * @return
     */
    public function gerarLinkPagamento($arrDados){
        //$fields = $this->setCamposJson($arrDados);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url_pgto."/link",
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $arrDados,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Client-Code: $this->cliente_code",
                "Client-key: $this->client_key"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $retorno = ($err) ? "cURL Error #:" . $err : $response;
        $arrRetorno = json_decode($retorno,true);


        return $arrRetorno;
    }

    /**
     * [salvarDados]
     * @param  [array] $dados
     * @return [array]
     */
    public function salvarDados($dados){

        $retorno = [];
        $dados = $this->tratarCampos($dados);
        try {
            $sql = 'INSERT INTO
                        fastconnect
                    SET
                        ativo = "S",
                        data_cad = NOW(),
                        url_retorno = "'.$this->url_pgto_retorno.'",
                        nm_cliente = "'.$dados['nm_cliente'].'",
                        nu_documento = "'.$dados['nu_documento'].'",
                        ds_email = "'.$dados['ds_email'].'",
                        nu_telefone = "'.$dados['nu_telefone'].'",
                        vl_total = "'.$dados['vl_total'].'",
                        nu_referencia = "'.$dados['nu_referencia'].'",
                        nu_parcelas = "'.$dados['nu_parcelas'].'",
                        tipo_venda = "'.$dados['tipo_venda'].'",
                        dia_cobranca = '.$dados['dia_cobranca'].',
                        ds_cep = "'.$dados['ds_cep'].'",
                        ds_endereco = "'.$dados['ds_endereco'].'",
                        ds_bairro = "'.$dados['ds_bairro'].'",
                        ds_complemento = "'.$dados['ds_complemento'].'",
                        ds_numero = "'.$dados['ds_numero'].'",
                        nm_cidade = "'.$dados['nm_cidade'].'",
                        nm_estado = "'.$dados['nm_estado'].'",
                        ds_softdescriptor = "'.$dados['ds_softdescriptor'].'",
                        nm_bandeira = "'.$dados['nm_bandeira'].'",
                        nu_cartao = "'.$dados['nu_cartao'].'",
                        nm_titular = "'.$dados['nm_titular'].'",
                        dt_validade = "'.$dados['dt_validade'].'",
                        nu_venda = "'.$dados['nu_venda'].'",
                        tipo = "'.$dados['tipo'].'",
                        id_venda = "'.$dados['id_venda'].'",
                        id_cliente = "'.$dados['id_cliente'].'",
                        dt_venda = "'.$dados['dt_venda'].'",
                        dt_cobranca = "'.$dados['dt_cobranca'].'",
                        dt_vencimento = "'.$dados['dt_vencimento'].'",
                        situacao = "'.$dados['situacao'].'",
                        nu_parcela = "'.$dados['nu_parcela'].'",
                        vl_parcela = "'.$dados['vl_parcela'].'",
                        vl_venda = "'.$dados['vl_venda'].'",
                        fid = "'.$dados['fid'].'",
                        ds_cartao_token = "'.$dados['ds_cartao_token'].'"';

            if (isset($dados['dt_pagamento']) && !empty($dados['dt_pagamento']))
                $sql.= ',dt_pagamento = "'.$dados['dt_pagamento'].'"';
            if (isset($dados['link_pdf']))
                $sql.= ',link_pdf = "'.$dados['link_pdf'].'"';
            if (isset($dados['link_pagamento']))
                $sql.= ',link_pagamento = "'.$dados['link_pagamento'].'"';
            if (isset($dados['idsituacao']))
                $sql.= ',idsituacao = '.$dados['idsituacao'];
            if (isset($dados['idescola']))
                $sql.= ',idescola = '.$dados['idescola'];
            if (isset($dados['idconta']))
                $sql.= ',idconta = '.$dados['idconta'];

            $salvar = $this->executaSql($sql);
            $idfastconnect = mysql_insert_id();
        }
        catch (Exception $e) {
            $retorno['exception'] = nl2br($e->getMessage());
        }

        if ($salvar){
            $retorno['id'] = $idfastconnect;
            $retorno['sucesso'] =  true;
        }
        else{
            $retorno['sucesso'] =  false;
        }

        return $retorno;
    }

    /**
     * [salvarLog]
     * @param  [array] $dados
     * @return [array]
     */
    public function salvarLog($dados){

        $retorno = [];

        try {
            $sql = "INSERT INTO
                        fastconnect_logs
                    SET
                        ativo = 'S',
                        data_cad = NOW(),
                        metodo = '".$dados['metodo']."',
                        retorno = '".addslashes($dados['retorno'])."'";

            if (isset($dados['envio'])) $sql.= ',envio = "'.addslashes($dados['envio']).'"';
            if (isset($dados['fid'])) $sql.= ',fid = "'.$dados['fid'].'"';
            if (isset($dados['idfastconnect'])) $sql.= ',idfastconnect = "'.$dados['idfastconnect'].'"';

            $salvar = $this->executaSql($sql);
            $idlog = mysql_insert_id();
        }
        catch (Exception $e) {
            $retorno['exception'] = nl2br($e->getMessage());
        }

        if ($salvar){
            $retorno['id'] = $idlog;
            $retorno['sucesso'] =  true;
        }
        else{
            $retorno['sucesso'] =  false;
        }

        return $retorno;
    }

    /**
     * [consultarDadosId]
     * @param  [integer] $idfastconnect
     * @param  [integer] $fid
     * @return [array]
     */
    public function consultarDadosId($idfastconnect = null, $fid = null)
    {
        if (empty($idfastconnect) && empty($fid)) {
            $erros['erro'] = true;
            $erros['erros'][] = 'parametros_incompletos';
            return $erros;
        }

        $sql = 'SELECT f.* FROM fastconnect f WHERE f.ativo = "S"';

        if ($idfastconnect) {
            $sql .= ' AND f.idfastconnect = ' . (int) $idfastconnect;
        }

        if ($fid) {
            $sql .= ' AND f.fid = "' . $fid . '"';
        }

        return $this->retornarLinha($sql);
    }

    /**
     * [adicionaIdConta]
     * @param  [integer] $idfastconnect
     * @param  [integer] $idconta
     * @return
     */
    public function adicionaIdConta($idfastconnect,$idconta){

        $sql = 'UPDATE
                    fastconnect
                set idconta = '.$idconta.'
                WHERE
                    idfastconnect = '.$idfastconnect;

        $retorno = $this->executaSql($sql);

        return $retorno;
    }

    /**
     * [retornaPagamentosSemRetorno]
     * @return [array]
     */
    public function retornaPagamentosSemRetorno()
    {
        //  Status fastConnect
        //   1 => 'ATIVO', ***
        //   2 => 'ABERTO', ***
        //   3 => 'PAGO',
        //   4 => 'PENDENTE', ***
        //   5 => 'CANCELADO',
        //   6 => 'CANCELADO POR ESTORNO'

        $this->sql = 'select f.idfastconnect,
                       f.fid,
                       c.idconta,
                       f.idescola
                from fastconnect f inner join contas c on (c.idconta = f.idconta)
                                   inner join contas_workflow cw on (cw.idsituacao = c.idsituacao)
                where cw.emaberto = "S"
                      and c.ativo = "S"
                      and f.ativo = "S"
                      and f.cron = "N"
                      and f.idsituacao in (0,1,2,3,4)';

        $this->groupby = 'c.idconta';
        $this->ordem_campo = 'c.idconta';
        $this->ordem = 'ASC';
        $this->limite = 30;

        $retorno = $this->retornarLinhas();

        if (empty($retorno)) {
            $sql = 'UPDATE fastconnect SET cron = "N"';
            $this->executaSql($sql);
        }

        return $retorno;
    }

    public function consultarVendasDiaPage($date, $page)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url_pgto."/vendas?dt_venda=".$date."&page=".$page,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Client-Code: $this->cliente_code",
                "Client-key: $this->client_key"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $retorno = ($err) ? "cURL Error #:" . $err : $response;

        $dadosLog = ['metodo' => 'consultarVendas','retorno' => $retorno];
        $salvouLog = $this->salvarLog($dadosLog);

        return json_decode($retorno,true);
    }

    public function consultarVendasDia($date)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url_pgto."/vendas?dt_venda=".$date,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Client-Code: $this->cliente_code",
                "Client-key: $this->client_key"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $retorno = ($err) ? "cURL Error #:" . $err : $response;

        $retorno_arr = json_decode($retorno,true);

        $vendas[] = $retorno_arr['data'];

        $dadosLog = ['metodo' => 'consultarVendas','retorno' => $retorno];
        $salvouLog = $this->salvarLog($dadosLog);

        if($retorno_arr['success']) {
            $page = $retorno_arr['paginate']['current_page'];
            while($retorno_arr['paginate']['lastpage'] > $page) {
                $retorno_page = $this->consultarVendasDiaPage($date, $page++);
                $vendas[] = $retorno_page['data'];
            }
        }

        return $vendas;
    }

    /**
     * [capturarTransacao]: A captura só é possível em uma transação que não foi capturada,
     * a mesma, deve ter sido feita com tp_capturar=false.
     * @param  [string] $fid: identificador da transação realizada.
     * @return
     */
    public function inserirPagamentosPorLink($param){

        $arrRetorno = $this->consultarVendas($param);
        $param["page"] = $page = 1;
        if($arrRetorno['success']) {
            while($page <= $arrRetorno['paginate']['last_page'] + 1) {
                $arrRetorno = $this->consultarVendas($param);
                foreach ($arrRetorno['data'] as $dados) {
                    foreach ($dados["parcelas"] as $transacao) {
                        $linha = $this->consultarDadosId(null, $transacao['fid']);
                        if (is_null($linha['fid']) && ($transacao['nu_parcela'] <= $dados['nu_parcelas'])) {
                            require_once 'pessoas.class.php';
                            $pessoasObj = new Pessoas();
                            $pessoa = $pessoasObj->RetornarPorCPF($dados['nu_documento']);
                            if ($transacao['tipo'] == 'credito')
                                $arrSalvar = $this->consultarTransacao($transacao['fid'])['data'];
                            if ($transacao['tipo'] == 'boleto')
                                $arrSalvar = $this->consultarBoleto($transacao['fid'])['data'];
                            $arrSalvarAux = array(
                                'ds_email' => $pessoa['email'],
                                'nu_telefone' => $pessoa['telefone'],
                                'vl_total' => $arrSalvar['vl_venda'],
                                'dia_cobranca' => 1,
                                'idcliente' => $pessoa['idpessoa'],
                                'idsituacao' => $this->getIdSituacao($arrSalvar['situacao']),
                            );
                            $arrSalvar = array_merge($arrSalvar, $arrSalvarAux);
                            var_dump($arrSalvar);
                            $salvou = $this->salvarDados($arrSalvar);
                        }
                    }
                }
                $param["page"] = $page++;
            }
        }
    }

    public function inserirPagamentosPorLinkApi($arrDados, $idconta){
        $linha = $this->consultarDadosId(null, $arrDados['fid']);

        if (!$linha['fid'] && ($arrDados['nu_parcela'] <= $arrDados['nu_parcelas'])) {
            if ($arrDados['tipo'] == 'credito')
                $arrSalvar = $this->consultarTransacao($arrDados['fid'])['data'];
            if ($arrDados['tipo'] == 'boleto')
                $arrSalvar = $this->consultarBoleto($arrDados['fid'])['data'];
            require_once 'pessoas.class.php';
            $pessoasObj = new Pessoas();
            $pessoa = $pessoasObj->RetornarPorCPF($arrSalvar['nu_documento']);
            $arrSalvarAux = array(
                'ds_email' => $pessoa['email'],
                'nu_telefone' => $pessoa['telefone'],
                'vl_total' => $arrSalvar['vl_venda'],
                'dia_cobranca' => 1,
                'id_cliente' => $pessoa['idpessoa'],
                'idsituacao' => $this->getIdSituacao($arrSalvar['situacao']),
                'idconta' => $idconta,
                'idescola' => $arrDados['idescola'],
            );
            $arrSalvar = array_merge($arrSalvar, $arrSalvarAux);

            $salvou = $this->salvarDados($arrSalvar);
        }
        return $salvou;
    }

    /**
     * [capturarTransacao]: A captura só é possível em uma transação que não foi capturada,
     * a mesma, deve ter sido feita com tp_capturar=false.
     * @param  [string] $fid: identificador da transação realizada.
     * @return
     */
    public function capturarTransacao($fid){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url_pgto."/credito/".$fid."/capturar",
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Client-Code: $this->cliente_code",
                "Client-key: $this->client_key"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $retorno = ($err) ? "cURL Error #:" . $err : $response;

        $dadosLog = ['metodo' => 'capturarTransacao','retorno' => $retorno, 'fid' => $fid];
        $salvouLog = $this->salvarLog($dadosLog);

        return json_decode($retorno,true);
    }

    /**
     * [consultarTransacao]
     * @param  [string] $fid: identificador da transação realizada.
     * @return
     */
    public function consultarTransacao($fid){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url_pgto."/credito/".$fid,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Client-Code: $this->cliente_code",
                "Client-key: $this->client_key"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $retorno = ($err) ? "cURL Error #:" . $err : $response;

        $dadosLog = ['metodo' => 'consultarTransacao','retorno' => $retorno, 'fid' => $fid];
        $salvouLog = $this->salvarLog($dadosLog);

        return json_decode($retorno,true);
    }

    /**
     * [cancelarTransacao]: O cancelamento só pode ser efetuado com transações que ainda não foram efetivadas,
     * por exemplo: agendamentos, parcelas futuras de recorrência e parcelamentos.
     * @param  [string] $fid: identificador da transação realizada.
     * @return
     */
    public function cancelarTransacao($fid){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url_pgto."/credito/".$fid,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Client-Code: $this->cliente_code",
                "Client-key: {{$this->client_key}}"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $retorno = ($err) ? "cURL Error #:" . $err : $response;

        $dadosLog = ['metodo' => 'cancelarTransacao','retorno' => $retorno, 'fid' => $fid];
        $salvouLog = $this->salvarLog($dadosLog);

        return json_decode($retorno,true);
    }

    /**
     * [estornarTransacao]: O estorno de uma transação só é possível,
     * quando a mesma já foi efetivada na adquirente.
     * @param  [string] $fid: identificador da transação realizada.
     * @return
     */
    public function estornarTransacao($fid){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url_pgto."/credito/".$fid."/estornar",
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Client-Code: $this->cliente_code",
                "Client-key: {{$this->client_key}}"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $retorno = ($err) ? "cURL Error #:" . $err : $response;

        $dadosLog = ['metodo' => 'estornarTransacao', 'retorno' => $retorno, 'fid' => $fid];
        $salvouLog = $this->salvarLog($dadosLog);

        return json_decode($retorno,true);
    }

    /**
     * [alterarVencimentoBoleto]
     * @param  [string] $fid: identificador da transação realizada.
     * @return
     */
    public function alterarVencimentoBoleto($fid){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url_pgto."/boleto/".$fid,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS =>"{\n\t\"dt_vencimento\": \"2019-02-13\"\n}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Client-Code: $this->cliente_code",
                "Client-key: $this->client_key"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $retorno = ($err) ? "cURL Error #:" . $err : $response;

        $dadosLog = ['metodo' => 'alterarVencimentoBoleto', 'retorno' => $retorno, 'fid' => $fid];
        $salvouLog = $this->salvarLog($dadosLog);

        return json_decode($retorno,true);
    }

    /**
     * [consultarBoleto]
     * @param  [string] $fid: identificador da transação realizada.
     * @return
     */
    public function consultarBoleto($fid){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url_pgto."/boleto/".$fid,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Client-Code: $this->cliente_code",
                "Client-key: $this->client_key"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $retorno = ($err) ? "cURL Error #:" . $err : $response;

        $dadosLog = ['metodo' => 'consultarBoleto', 'retorno' => $retorno, 'fid' => $fid];
        $salvouLog = $this->salvarLog($dadosLog);

        return json_decode($retorno,true);
    }

    /**
     * [cancelarBoleto]
     * @param  [string] $fid: identificador da transação realizada.
     * @return
     */
    public function cancelarBoleto($fid){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url_pgto."/boleto/".$fid,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Client-Code: $this->cliente_code",
                "Client-key: $this->client_key"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $retorno = ($err) ? "cURL Error #:" . $err : $response;

        $dadosLog = ['metodo' => 'cancelarBoleto', 'retorno' => $retorno, 'fid' => $fid];
        $salvouLog = $this->salvarLog($dadosLog);

        return json_decode($retorno,true);
    }

    /**
     * [consultarVendas]: Retorna todas as vendas referentes aos filtros enviados. Os parâmetros são opcionais.
     * @param  [array] $params:
     *                 [date] dt_venda
     *                 [string] nu_referencia
     *                 [string] nu_venda
     *                 [integer] page
     *                 [integer] per_page
     * @return
     */
    public function consultarVendas($params = null){

        $parametros = "";
        // ... ?dt_venda=2019-01-30&nu_referencia=REF0001&nu_venda=1942-IxDZ-jVur&page=0&per_page=10

        if (isset($params['dt_venda']) && !empty($params['dt_venda'])){
            $parametros.= "?dt_venda=".$params['dt_venda'];
        }

        if (isset($params['nu_referencia']) && !empty($params['nu_referencia'])){
            $parametros.= "&nu_referencia=".$params['nu_referencia'];
        }

        if (isset($params['nu_venda']) && !empty($params['nu_venda'])){
            $parametros.= "&nu_venda=".$params['nu_venda'];
        }

        if (isset($params['page']) && !empty($params['page'])){
            $parametros.= "&page=".$params['page'];
        }

        if (isset($params['per_page']) && !empty($params['per_page'])){
            $parametros.= "&per_page=".$params['per_page'];
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url_pgto."/vendas".$parametros,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Client-Code: $this->cliente_code",
                "Client-key: $this->client_key"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $retorno = ($err) ? "cURL Error #:" . $err : $response;

        $dadosLog = ['metodo' => 'consultarVendas', 'retorno' => $retorno];
        $salvouLog = $this->salvarLog($dadosLog);

        return json_decode($retorno,true);
    }

    /**
     * [cancelarVenda]: O cancelamento de uma venda irá interromper o pagamento das parcelas que ainda se encontram em aberto.
     * @param  [string] $nu_venda
     * @return
     */
    public function cancelarVenda($nu_venda){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url_pgto."/venda/".$nu_venda,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Client-Code: $this->cliente_code",
                "Client-key: $this->client_key"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $retorno = ($err) ? "cURL Error #:" . $err : $response;

        $dadosLog = ['metodo' => 'cancelarVenda', 'retorno' => $retorno];
        $salvouLog = $this->salvarLog($dadosLog);

        return json_decode($retorno,true);
    }

    /**
     * [estornarVenda]: O estorno de uma venda irá ser efetuado no valor total da compra.
     * Só se pode estornar parcelas pagas, uma vez que o estorno é a devolução do dinheiro para o cliente.
     * @param  [string] $nu_venda
     * @return
     */
    public function estornarVenda($nu_venda){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url_pgto."/venda/".$nu_venda."/estornar",
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Client-Code: $this->cliente_code",
                "Client-key: $this->client_key"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $retorno = ($err) ? "cURL Error #:" . $err : $response;

        $dadosLog = ['metodo' => 'estornarVenda', 'retorno' => $retorno, 'fid' => $retorno['data']['fid']];
        $salvouLog = $this->salvarLog($dadosLog);

        return json_decode($retorno,true);
    }

    /**
     * [atualizaTransacao]: Faz a consulta do pagamento e caso esteja pago, já dá baixa no sistema
     * @param  integer $idfastconnect
     * @param  boolean $cron
     * @return
     */
    public function atualizaTransacao($idfastconnect, $cron = false)
    {
        $retorno = array();

        $transacaoPagamento = $this->consultarDadosId($idfastconnect);

        if ($transacaoPagamento) {
            try {

                if (empty($this->naoIniciarTransacao)) {
                    $this->executaSql('BEGIN');
                }

                $retornoTransacao = ($transacaoPagamento['tipo'] == 'cartao')
                    ? $this->consultarTransacao($transacaoPagamento['fid'])
                    : $this->consultarBoleto($transacaoPagamento['fid']);

                $transaction = $retornoTransacao['data'];
                $transaction['idsituacao'] = $this->getIdSituacao($transaction['situacao']);

                if ($retornoTransacao['success']) {

                    $sql = 'UPDATE
                                fastconnect
                            set nu_venda = "'.$transaction['nu_venda'].'",
                                nm_cliente = "'.$transaction['nm_cliente'].'",
                                nu_documento = "'.$transaction['nu_documento'].'",
                                dt_venda = "'.$transaction['dt_venda'].'",
                                fid = "'.$transaction['fid'].'",
                                nu_referencia = "'.$transaction['nu_referencia'].'",
                                dt_cobranca = "'.$transaction['dt_cobranca'].'",
                                dt_pagamento = "'.$transaction['dt_pagamento'].'",
                                dt_vencimento = "'.$transaction['dt_vencimento'].'",
                                dt_atualizacao = "'.date('Y-m-d H:i:s').'",
                                tipo = "'.$transaction['tipo'].'",
                                tipo_venda = "'.$transaction['tipo_venda'].'",
                                situacao = "'.$transaction['situacao'].'",
                                idsituacao = '.$transaction['idsituacao'].',
                                nu_parcelas ='.$transaction['nu_parcelas'].' ,
                                nu_parcela ='.$transaction['nu_parcela'].' ,
                                vl_parcela = "'.$transaction['vl_parcela'].'",
                                vl_venda = "'.$transaction['vl_venda'].'" ';

                    if (isset($transaction['link_pdf'])) $sql.= ',link_pdf = "'.$transaction['link_pdf'].'" ';
                    if (isset($transaction['linha_digitavel'])) $sql.= ',linha_digitavel = "'.$transaction['linha_digitavel'].'" ';
                    if (isset($transaction['codigo_barra'])) $sql.= ',codigo_barra = "'.$transaction['codigo_barra'].'" ';
                    if (isset($transaction['link_pagamento'])) $sql.= ',link_pagamento = "'.$transaction['link_pagamento'].'" ';
                    if (isset($transaction['ds_cartao_token'])) $sql.= ',ds_cartao_token = "'.$transaction['ds_cartao_token'].'" ';
                    if (isset($transaction['ds_mascara_cartao'])) $sql.= ',ds_mascara_cartao = "'.$transaction['ds_mascara_cartao'].'" ';

                    if ($cron) $sql.= ',cron = "S" ';

                    $sql.= ' WHERE
                                idfastconnect = '.$transacaoPagamento['idfastconnect'];
                    $salvar = $this->executaSql($sql);

                    $linhaNova = $this->consultarDadosId($idfastconnect);

                    //  Status fastConnect
                    //   1 => 'ATIVO', 
                    //   2 => 'Aberto',
                    //   3 => 'Pago',
                    //   4 => 'Pendente',
                    //   5 => 'Cancelado',
                    //   6 => 'Cancelado por estorno'

                    /* //Solicitado deixar comentado em 29-01-2020 no chamado #283044
                     * in_array($transaction['idsituacao'], [3,5,6])
                     */
                    if (in_array($transaction['idsituacao'], [3])) {

                        $date = new DateTime($linhaNova['dt_atualizacao']);//Cria uma data com a data de atualização do transação
                        $date->setTimezone(new DateTimeZone('America/Bahia'));//Seta o TimeZone da Bahia para a data

                        //INÍCIO ATUALIZA SITUAÇÃO DA CONTA PARA SITUAÇÃO PAGA
                        require_once 'contas.class.php';
                        $contaObj = new Contas();

                        //Busca os dados da conta
                        $sqlModificacoesConta = 'SELECT idsituacao, idconta, idmatricula FROM contas WHERE idconta = ' . $transacaoPagamento['idconta'];
                        $linhaAntigaConta = $this->retornarLinha($sqlModificacoesConta);

                        //Adiciona o histórico da mudança da situação da conta da matrícula
                        require_once 'matriculas.class.php';
                        $matriculaObj = new Matriculas();
                        $matriculaObj->set('id', $linhaAntigaConta['idmatricula']);

                        //Retorna os dados da matrícula para atualizar o pedido
                        $matricula = $matriculaObj->retornar();

                        if ($transaction['idsituacao'] == 3) {

                            //Retorna o id da situação paga da conta
                            $situacaoConta = $contaObj->retornarSituacaoFastConnect();

                            if (empty($situacaoConta)) {
                                $retorno['erro'] = true;
                                $retorno['erros'][] = 'sem_situacao_fastconnect';
                                return $retorno;
                            }

                            $situacaoPedido = 'P';
                            $valor_pago = '"' . $transaction['vl_venda'] . '"';
                            $data_pagamento = '"' . $transaction['dt_pagamento'] . '"';

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

                            if (!empty($matriculaDisponivelMudanca['idmatricula'])) {
                                $situacaoAtiva = $matriculaObj->retornarSituacaoAtiva();

                                $sql = 'UPDATE matriculas SET idsituacao = ' . $situacaoAtiva['idsituacao'] . '
                                    WHERE idmatricula = ' . $linhaAntigaConta['idmatricula'];
                                $this->executaSql($sql);

                                $matriculaObj->set('id', $linhaAntigaConta['idmatricula'])
                                    ->AdicionarHistorico(
                                        null,
                                        'situacao',
                                        'modificou',
                                        $matricula['idsituacao'],
                                        $situacaoAtiva['idsituacao'],
                                        null
                                    );
                            }
                        } else { //Pagamento cancelado (4 e 5)

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
                        $contaObj->AdicionarHistorico(
                            'situacao',
                            'modificou',
                            $linhaAntigaConta['idsituacao'],
                            $linhaNovaConta['idsituacao']
                        );

                        $matriculaObj->AdicionarHistorico(
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
                }
                else {
                    $retorno['erro'] = true;
                    $retorno['erros'][] = 'sem_retorno_pagamento';
                }
            }
            catch (Exception $e) {
                $retorno['exception'] = nl2br($e->getMessage());
            }
        }
        else {
            $retorno['erro'] = true;
            $retorno['erros'][] = 'pagamento_nao_encontrado_ja_existe';
        }

        return $retorno;
    }
}