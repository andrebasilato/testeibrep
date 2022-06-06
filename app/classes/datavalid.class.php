<?php
#Class: DataValid.class.php
#Data: 24/08/2020;
#Developer: Diego Gama;
#Empresa: Alfama Web ou AW8
#Intuito: comporta métodos para utilizar as diversas API do Data Valid (CPF, Biometria Facial e Digital ).
class DataValid extends Core{
    public $token_result;
    private $plano;
    private $versao;
    private $modoDemo;
    private $urlPF;
    private $urlToken;
    private $urlPFBioDigital;
    private $urlPFBioFacial;
    private $bearer;

    function __construct() {
        $this->modoDemo        = $GLOBALS['config']['datavalid']['demo'];
        $this->plano           = $GLOBALS['config']['datavalid']['plano'];
        $this->versao          = $GLOBALS['config']['datavalid']['versao'];

        # Urls Datavalid e seus Planos
        $urlPlanoDemo = "https://gateway.apiserpro.serpro.gov.br/datavalid-demonstracao";
        $urlPlanoComum = "https://gateway.apiserpro.serpro.gov.br/datavalid";
        $urlPlanoEntidade = "https://gateway.apiserpro.serpro.gov.br/datavalid-entidade";
        $urlPlanoBiometria = "https://gateway.apiserpro.serpro.gov.br/datavalid/biometria";
        $urlPlanoBasico = "https://gateway.apiserpro.serpro.gov.br/datavalid/basico";

        switch (strtolower($this->plano)){
            case 'entidade':
                $this->plano = $urlPlanoEntidade;
                break;
            case 'biometria':
                $this->plano = $urlPlanoBiometria;
                break;
            case 'basico':
                $this->plano = $urlPlanoBasico;
                break;
            default:
                $this->plano = $urlPlanoComum;
                break;
        }

        $pf = "validate/pf";
        $digitais = "validate/pf-digitais";
        $pfFace = "validate/pf-face";

        if($this->modoDemo){
            $this->urlPF           = $urlPlanoDemo.'/v'.(int)$this->versao.'/'.$pf;
            $this->urlPFBioDigital = $urlPlanoDemo.'/v'.(int)$this->versao.'/'.$digitais;
            $this->urlPFBioFacial  = $urlPlanoDemo.'/v'.(int)$this->versao.'/'.$pfFace;
            $this->bearer          = "4e1a1858bdd584fdc077fb7d80f39283";
        }else{
            $this->urlPF           = $this->plano.'/v'.(int)$this->versao.'/'.$pf;
            $this->urlPFBioDigital = $this->plano.'/v'.(int)$this->versao.'/'.$digitais;
            $this->urlPFBioFacial  = $this->plano.'/v'.(int)$this->versao.'/'.$pfFace;
            $this->urlToken        = "https://gateway.apiserpro.serpro.gov.br/token";
            $this->token_result    = $this->conectaDatavalid();
            $this->bearer          = $this->token_result->access_token;
        }
    }

    #Método para conectar ao DataValid
    private function conectaDatavalid(){
        $postData = 'grant_type=client_credentials';
        $base64Chave = base64_encode(
            $GLOBALS['config']['datavalid']['consumer_key'].
            ":".
            $GLOBALS['config']['datavalid']['consumer_secret']);

        $curlHead = array(
            "Authorization:Basic ".$base64Chave,
            "Content-Type:application/x-www-form-urlencoded"
        );
        $result = json_decode($this->curl_datavalid($this->urlToken, $curlHead, $postData));
        return $result;

    }

    #Método para limpar caracteres especiais de uma string de CPF
    public function replaceDataCPF($cpf){
        $cpfNovo = str_replace(".", "", $cpf);
        $cpfNovo = str_replace("-", "", $cpfNovo);
        return $cpfNovo;
    }

    public function transformaImagemBase64($filename,$filetype){
        if ($filename) {
            $imgbinary = fread(fopen($filename, "r"), filesize($filename));
            return base64_encode($imgbinary);
        }
        return false;
    }

    public function imagemParaBase64($urlImage){
        $url = $urlImage;
        $image = file_get_contents($url);
        if ($image !== false){
            return base64_encode($image);
        }
        return false;
    }

    #Método para validar dados de uma pessoa física por biometria Facial
    public function validaPFBiometriaFacial($dadosPessoa,$foto){
        $dadosPF = array();
        $dadosPF['key']['cpf'] = $dadosPessoa['documento'];
        $dadosPF['answer']['nome'] = $dadosPessoa['nome'];
        $dadosPF['answer']['sexo'] = $dadosPessoa['sexo'];
        $dadosPF['answer']['nacionalidade'] = 1;
        $dadosPF['answer']['filiacao']['nome_mae'] = $dadosPessoa['filiacao_mae'];
        $dadosPF['answer']['filiacao']['nome_pai'] = $dadosPessoa['filiacao_pai'];

        #dados da CNH
        $dadosPF['answer']['cnh']['categoria'] = $dadosPessoa['categoria'];
        $dadosPF['answer']['cnh']['numero_registro'] = $dadosPessoa['cnh'];
        $dadosPF['answer']['cnh']['data_primeira_habilitacao'] = $dadosPessoa['data_primeira_habilitacao'];
        $dadosPF['answer']['cnh']['data_validade'] = $dadosPessoa['data_validade'];
        $dadosPF['answer']['cnh']['registro_nacional_estrangeiro'] = $dadosPessoa['rne'];
        $dadosPF['answer']['cnh']['data_ultima_emissao'] = $dadosPessoa['cnh_data_emissao'];
        $dadosPF['answer']['cnh']['codigo_situacao'] = "3";

        #dados da face
        $dadosPF['answer']["biometria_face"] = $this->imagemParaBase64($foto);
        $dadosPF['answer']['data_nascimento'] = $dadosPessoa['data_nasc'];
        $dadosPF['answer']['situacao_cpf'] = "regular";
        $curlHead = array(
            "Accept:application/json",
            "Authorization:Bearer $this->bearer",
            "content-type:application/json"
        );
        return $this->curl_datavalid($this->urlPFBioFacial, $curlHead, $dadosPF);

    }
    private function curl_datavalid($url, $head, $dados=False){
        $curlHead = $head;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $curlHead);
        if($dados != false) {
            if(in_array("Accept:application/json",$head))
                $dados = json_encode($dados);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $dados);
        }
        $result = curl_exec($curl);
        curl_error($curl);
        $curl_info = curl_getinfo($curl);
        if( !$result ){die("Falha ao conectar datavalid: ".$url);}
        curl_close($curl);
        return $result;
    }
}
?>
