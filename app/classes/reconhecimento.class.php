<?php

class Reconhecimento extends Core {

    const URL_DETECT = 'https://brazilsouth.api.cognitive.microsoft.com/face/v1.0/detect';
    private $probabDefault;
    private $rangeMinimo;
    private $headers = array('Content-Type' => 'application/json','Ocp-Apim-Subscription-Key' => '3c90bc90874140dc84700889a9e3c776');
    function __construct() {
        $this->probabDefault = $GLOBALS['config']['datavalid']['probabDefault'];
        $this->rangeMinimo = $GLOBALS['config']['reconhecimento']['range_minimo'];
    }

    public function ListarFalhas(){
        $POG = $_GET['q'];
        $liberacao_temporaria = $_GET['q']['1|liberacao_temporaria']; unset($_GET['q']['1|liberacao_temporaria']); //POG

        if ($liberacao_temporaria)
            $cond_liberacao_temporaria = " AND (
                (rec.tipo_biometria = 'AZURE' AND m.biometria_liberada != '$liberacao_temporaria')
                OR
                (rec.tipo_biometria = 'DATAVALID' AND m.liberacao_temporaria_datavalid = '$liberacao_temporaria')
            )";

        $this->sql = "SELECT m.idmatricula, m.data_cad, p.nome as aluno, s.nome_abreviado as sindicato, e.nome_fantasia as escola,
        rec.tipo_biometria,
        rec.data_falha,
        biometria_liberada,
        liberacao_temporaria_datavalid,
        envio_foto_documento_oficial,
        CASE
            WHEN rec.tipo_biometria = 'AZURE' AND biometria_liberada = 'N' THEN 'S'
            WHEN rec.tipo_biometria = 'DATAVALID' AND liberacao_temporaria_datavalid = 'S' THEN 'S'
            ELSE 'N'
        END AS liberacao_temporaria
        FROM matriculas m
        INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
        INNER JOIN sindicatos s ON (m.idsindicato = s.idsindicato)
        INNER JOIN escolas e ON (m.idescola = e.idescola)
        INNER JOIN matriculas_workflow mw ON (m.idsituacao = mw.idsituacao)
        INNER JOIN (
            SELECT idmatricula, data_cad as data_falha, 'DATAVALID' as tipo_biometria
            FROM matriculas_reconhecimentos
            WHERE probabilidade_datavalid < " . $this->probabDefault . " AND idmatricula IS NOT NULL
            UNION
            SELECT idmatricula, data_cad as data_falha, 'AZURE' as tipo_biometria
            FROM reconhecimento_fotos
            WHERE confidence < " . $this->rangeMinimo . " AND idmatricula IS NOT NULL
        ) as rec ON (rec.idmatricula = m.idmatricula)
        WHERE
            m.ativo = 'S'
            AND mw.fim = 'N' and mw.cancelada = 'N' and mw.inativa = 'N' and mw.inicio = 'N'
            $cond_tipo_biometria
            $cond_falha
            $cond_liberacao_temporaria
        ";
        $this->aplicarFiltrosBasicos();
        $this->ordem_campo = "rec.data_falha";
        $this->ordem = "DESC";
        $this->groupby = "m.idmatricula, m.data_cad, p.nome, s.nome_abreviado, e.nome_fantasia";
        $this->mantem_groupby = true;
        $this->limite = -1;

        $_GET['q'] = $POG;

        $linhas = $this->retornarLinhas();
        return $linhas;
    }

    public function requestDetect(HTTP_Request2 $request, array $parameters, array $body)
    {
        $url = $request->getUrl();
        $request->setConfig(array(
            'ssl_verify_peer'   => false,
            'ssl_verify_host'   => false
        ));
        $request->setHeader($this->headers);
        $url->setQueryVariables($parameters);
        $request->setMethod(HTTP_Request2::METHOD_POST);
        //body
        $request->setBody(json_encode($body));

        try
        {
            $response = $request->send();
            return $response->getBody();
        }
        catch (HttpException $ex)
        {
            return $ex;
        }
    }

    public function retornaImagemPrincipal($idMatricula)
    {
        $this->sql = "SELECT * FROM matriculas_reconhecimentos
                    WHERE ativo = 'S' AND ativo_painel = 'S' AND foto_principal = 'S' AND idmatricula = " . $idMatricula;

        return $this->retornarLinha($this->sql);
    }

    public function retornaImagensPrincipaisDatavalid($idMatricula)
    {
        $this->sql = "SELECT
                    mr.idfoto,
                    mr.foto,
                    mr.json,
                    mr.ativo_painel,
                    mr.data_cad,
                    mr.probabilidade_datavalid,
                    mr.ip
                    FROM matriculas_reconhecimentos AS mr
                    WHERE mr.ativo = 'S'
                    AND mr.ativo_painel = 'S'
                    AND mr.foto_principal = 'S'
                    AND mr.probabilidade_datavalid IS NOT NULL
                    AND mr.idmatricula = " . $idMatricula;
        $this->limite = 1;
        $this->ordem_campo = "mr.data_cad";
        $this->ordem = "DESC";
        $this->groupby = "mr.idfoto";
        return $this->retornarLinhas();
    }

    public function retornarTodasComparacoes($idMatricula)
    {
        $this->sql = "SELECT
            mr.idfoto,
            mr.foto as principal,
            mr.data_cad as dt_principal,
            rf.*
            FROM reconhecimento_fotos rf
            INNER JOIN matriculas_reconhecimentos mr ON (mr.idfoto = rf.idfoto_principal)
            WHERE rf.ativo = 'S'
            AND rf.ativo_painel = 'S'
            AND rf.idmatricula = " . $idMatricula;
        $this->limite = -1;
        $this->ordem_campo = "mr.data_cad";
        $this->ordem = "DESC";
        $this->groupby = "idreconhecimento";
        return $this->retornarLinhas();
    }

    public function retornarTodasFotosDatavalid($idMatricula)
    {
        $this->sql = "SELECT
            mcf.foto,
            mcf.json,
            mcf.ativo_painel,
            mcf.data_cad,
            mcf.probabilidade_datavalid,
            mcf.ip
            FROM matriculas_reconhecimentos AS mcf
            WHERE mcf.ativo = 'S'
            AND mcf.probabilidade_datavalid IS NOT NULL
            AND mcf.probabilidade_datavalid = 0
            AND mcf.idmatricula = " . $idMatricula;
        $this->limite = -1;
        $this->ordem_campo = "mcf.data_cad";
        $this->ordem = "DESC";
        $this->groupby = "mcf.idfoto";
        return $this->retornarLinhas();
    }

    public function registrarImagemPrincipal()
    {

        $pessoasObj = new Pessoas();
        $matriculaObj = new Matriculas();
        $diretorio = $_SERVER['DOCUMENT_ROOT'] . '/storage/matriculas_reconhecimentos/';
        $nome_servidor = date("YmdHis") . "_" . uniqid() . '.png';
        $caminhoFoto = $diretorio . $nome_servidor;

        $dadosMat = $matriculaObj->getMatricula($_POST['idmatricula']);
        $curso = $matriculaObj->RetornarCurso();
        $dadosSindicato = $matriculaObj->retornarSindicato();
        $pessoasObj->Set("campos","p.*");
        $pessoasObj->Set("id",$dadosMat['idpessoa']);
        $dadosPessoa = $pessoasObj->retornar();
        if (self::upload_imagem($caminhoFoto)) {
            $this->sql = "INSERT INTO 
                                matriculas_reconhecimentos 
                            SET 
                                data_cad = NOW(), 
                                foto = '{$nome_servidor}', 
                                tamanho = '{$_FILES['file']['size']}', 
                                extensao = '{$_FILES['file']['type']}', 
                                ip = '{$_SERVER['REMOTE_ADDR']}', 
                                ativo = 'S', 
                                ativo_painel = 'S',
                                idmatricula = {$_POST['idmatricula']}, 
                                probabilidade_datavalid = 0, 
                                json = ''";
            if(isset($_POST['saindoSistema']))
                $this->sql .= ", foto_principal = 'N'";
            else
                $this->sql .= ", foto_principal = 'S'";
            $this->executaSql($this->sql);
            $_POST['idimagem'] = mysql_insert_id();
            if(
                $matricula["biometria_liberada"] != 'S'
                && $curso['usar_datavalid'] == 'S'
                && $dadosSindicato['usar_datavalid'] == 'S'
            ) {
                $datavalid = $this->datavalid($dadosMat, $dadosPessoa, $_POST['idimagem'], $caminhoFoto);
                if($datavalid['resultado'] == 'S') {
                    echo "true";
                    return true;
                } else echo "liberacao_temporaria_datavalid";
            } else {
                echo "true";
                return true;
            }
        }else{
            echo "false";
            return false;
        }

    }

    public function removerImagemPrincipal()
    {
        $retorno = array('sucesso' => false);
        $this->sql = "UPDATE matriculas_reconhecimentos";
        $this->sql .= " SET ativo_painel = 'N'";
        $this->sql .= " WHERE idfoto = " . $_POST['idimagem'];

        if($this->executaSql($this->sql))
            $retorno['sucesso'] = true;
        return $retorno;
    }

    public function removerLiberacaoTemporaria($idMatricula)
    {
        $this->sql = "UPDATE matriculas SET liberacao_temporaria_datavalid = 'N' WHERE idmatricula = " . $idMatricula;
        $this->executaSql($this->sql);
    }

    public function datavalid($dadosMat, $dadosPessoa, $idfoto, $caminhoFoto){
        $datavalObj = new DataValid();
        // $respDatavalid = '{"nome":true,"sexo":true,"nacionalidade":true,"filiacao":{},"cnh":{"nome":true,"categoria":true,"nome_similaridade":1.0,"numero_registro":true,"data_primeira_habilitacao":true,"data_validade":true,"data_ultima_emissao":true,"codigo_situacao":false},"documento":{},"endereco":{},"cpf_disponivel":true,"nome_similaridade":1.0,"data_nascimento":true,"situacao_cpf":true,"biometria_face":{"disponivel":true,"probabilidade":"Altíssima probabilidade","similaridade":0.9623871867119049}}';
        $respDatavalid = $datavalObj->validaPFBiometriaFacial($dadosPessoa, $caminhoFoto);
        $respDatavalidDecoded = json_decode($respDatavalid);
        $probab = (float) number_format($respDatavalidDecoded->biometria_face->similaridade, 2);

        $this->sql = "UPDATE matriculas_reconhecimentos SET probabilidade_datavalid={$probab}, json = '{$respDatavalid}' WHERE idfoto = $idfoto";
        $this->executaSql($this->sql);

        if ($falha = $probab < $this->probabDefault && !isset($_POST['saindoSistema'])) {
            $this->sql = "UPDATE matriculas SET liberacao_temporaria_datavalid = 'S' WHERE idmatricula = " . $dadosMat['idmatricula'];
            $this->executaSql($this->sql);
        }

        $retorno = array(
            'probab'=>$probab,
            'json'=>$respDatavalid,
            'resultado'=> ($falha ? 'N' : 'S')
        );
        return $retorno;
    }

    public function azure($nome_servidor, $dadosMat){
        $imagemMatricula = $this->retornaImagemPrincipal($_POST['idmatricula']);
        $retorno['sucesso'] = $retorno['resultado'] = false;

        if ($dadosMat['biometria_liberada'] != 'S') {
            $urlImagemMatricula = $GLOBALS['config']['urlSistema'] . '/api/get/imagens/matriculas_reconhecimentos/480/480/' . $imagemMatricula['foto'] . '?reconhecimento=1';
            $urlImagemComparacao = $GLOBALS['config']['urlSistema'] . '/api/get/imagens/matriculas_comparacoes_fotos/480/480/' . $nome_servidor . '?reconhecimento=1';
            //REQUISITA O AZURE PARA NO METODO FACE-DETECT PARA RETORNAR O FACEID E ATRIBUTOS.
            $resultado = $this->detect($urlImagemMatricula);
            $hashFotoPadrao = $resultado[0]['faceId'];

            //REQUISITA O AZURE PARA NO METODO FACE-DETECT PARA RETORNAR O FACEID E ATRIBUTOS.
            $resultado2 = $this->detect($urlImagemComparacao);
            $hashFotoComparacao = $resultado2[0]['faceId'];

            if ($hashFotoPadrao && $hashFotoComparacao) {
                $parameters = array();
                $request = new Http_Request2('https://brazilsouth.api.cognitive.microsoft.com/face/v1.0/verify');
                $body = array("faceId1" => $hashFotoPadrao, "faceId2" => $hashFotoComparacao);
                $result_request = $this->requestDetect($request, $parameters, $body);
                $resultado4 = $result_request;
                $resultado3 = json_decode($resultado4, true);

                $age = intval($resultado2[0]['faceAttributes']['age']);
                $face_id = $hashFotoComparacao;
                $gender = $resultado2[0]['faceAttributes']['gender'];
                $confidence = floatval($resultado3['confidence']);
                $is_identical = $resultado3['isIdentical'];
                $json = $resultado4;

                $retorno['identical'] = $resultado3['isIdentical'];
                $retorno['confidence'] = $resultado3['confidence'];
                $retorno['sucesso'] = $retorno['resultado'] = floatval($resultado3['confidence']) >= floatval($this->rangeMinimo);
            } else {
                $retorno['sucesso'] = $retorno['resultado'] = false;
                $age = 0;
                $face_id = $gender = $is_identical = '';
                $confidence = 0;
                $json =
                    '{
                        "mensagem": "O Azure não retornou os dados para comparar as biometrias faciais."
                        "biometrias": [
                            "fotoPadrao": "'.$hashFotoPadrao.'",
                            "fotoComparacao": "'.$hashFotoComparacao.'"
                        ]
                    }';
            }
        } else {
            $retorno['sucesso'] = $retorno['resultado'] = true;
            $age = 0;
            $face_id = $gender = $is_identical = '';
            $confidence = $this->rangeMinimo;
            $json = '{"mensagem": "Matrícula liberada para pular a etapa de validação das fotos no Azure."}';
        }

        $sucesso = $retorno['sucesso'] ? 'S': 'N';
        $resultado = $retorno['resultado'] ? 'S': 'N';
        //ARMAZENA RESPOSTA NO BANCO
        try {
            $this->sql = "INSERT INTO reconhecimento_fotos SET 
                data_cad = NOW(), 
                face_id = '{$face_id}',
                face_att_age = {$age}, 
                face_att_gender = '{$gender}',
                idmatricula = {$_POST['idmatricula']}, 
                ativo = 'S', 
                ativo_painel = 'S', 
                idfoto_principal = {$imagemMatricula['idfoto']}, 
                foto_comparada_azure = '{$nome_servidor}',
                sucesso = '{$sucesso}', 
                resultado = '{$resultado}', 
                confidence = {$confidence}, 
                isIdentical = '{$is_identical}',
                ip = '{$_SERVER['REMOTE_ADDR']}', 
                json = '{$json}'";
            if (!empty($_POST['idobjetorota'])) {
                $this->sql .= ", idobjetorota = " . $_POST['idobjetorota'];
            }

            $this->executaSql($this->sql);
        } catch (PDOException $e) {
            echo $this->sql . "<br>" . $e->getMessage();
        }
        return $retorno;
    }

    public function compararFotos($useDV = false)
    {
        $pessoasObj = new Pessoas();
        $matriculaObj = new Matriculas();
        $dadosMat = $matriculaObj->getMatricula($_POST['idmatricula']);
        $pessoasObj->Set("campos","p.*");
        $pessoasObj->Set("id",$dadosMat['idpessoa']);
        $dadosPessoa = $pessoasObj->retornar();
        $dadosSindicato = $matriculaObj->retornarSindicato();
        $curso = $matriculaObj->RetornarCurso();
        $diretorio = $_SERVER['DOCUMENT_ROOT'] . '/storage/matriculas_comparacoes_fotos/';
        $nome_servidor = date("YmdHis") . "_" . uniqid() . '.png';
        $caminhoFoto = $diretorio . $nome_servidor;
        $movendoImagem = self::upload_imagem($caminhoFoto);
        if(!$movendoImagem) {
            echo 'N';
            return "N";
        }

        $retorno = "S";
        if (
            $useDV == true
            && $matricula["biometria_liberada"] != 'S'
            && $curso['usar_datavalid'] == 'S'
            && $dadosSindicato['usar_datavalid'] == 'S'
        ) {
            $datavalid = $this->datavalid($dadosMat, $dadosPessoa, $movendoImagem, $caminhoFoto);
            if(!$datavalid['resultado'])
                $retorno = "N";
        }

        $azure = $this->azure($nome_servidor, $dadosMat);
        if (!$azure['resultado'])
            $retorno = "N";
        echo $retorno;
        return $retorno;
    }

    private function detect($url)
    {
        $parameters = [
            'returnFaceId' => 'true',
            'returnFaceLandmarks' => 'false',
            'returnFaceAttributes' => 'age,gender'
        ];
        $request = new Http_Request2(self::URL_DETECT);
        $body = [
            'url' => $url
        ];
        return json_decode($this->requestDetect($request, $parameters, $body), true);
    }

    private function reduzir_tentativas($dadosMat)
    {
        $this->sql = "UPDATE matriculas SET limite_datavalid = (limite_datavalid - 1) where idmatricula = ".
            $dadosMat['idmatricula'];
        $this->executaSql($this->sql);
    }
    private function upload_imagem($diretorio){
        $retorno = move_uploaded_file($_FILES['file']['tmp_name'], $diretorio);
        $tamanho = strlen($_FILES['file']['tmp_name']);
        if(substr($_FILES['file']['tmp_name'],$tamanho-42, $tamanho) === 'app\tests\20201221164413_5fe0fb0d86624.png'){
            $retorno = true;
        }
        return $retorno;
    }
}

?>