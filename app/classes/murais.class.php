<?php
class Murais extends Core {

    var $idmural      = NULL;
    var $ind          = NULL;
    var $val          = NULL;
    var $query        = NULL;
    var $get          = NULL;
    var $linha_antiga = NULL;
    var $linha_nova   = NULL;
    var $files        = NULL;
    var $linha        = array();

    function ListarTodas() {
        $this->sql = "select
                        ".$this->campos."
                      from
                        murais
                      where
                        ativo = 'S'";

        $this->aplicarFiltrosBasicos();

        $this->groupby = "idmural";
        $murais = $this->retornarLinhas();

        $limite = $this->limite;
        $total = $this->total;

        foreach($murais as $ind => $mural) {
            $murais[$ind]["total_enviados"] = $this->retornarTotalEnviados($mural["idmural"]);

            $murais[$ind]["total_lidos"] = $this->retornarTotalLidos($mural["idmural"]);
        }

        $this->limite = $limite;
        $this->total = $total;

        return $murais;
    }

    function retornarTotalEnviados($idmural) {

        $this->sql = "select
                        count(idfila) as total
                      from
                        murais_filas
                      where
                        idmural = '".$idmural."' and
                        data_enviado IS NOT NULL and
                        ativo = 'S'";
        $total = $this->retornarLinha($this->sql);

        return $total["total"];

    }

    function retornarTotalLidos($idmural) {

        $this->sql = "select
                        count(idfila) as total
                      from
                        murais_filas
                      where
                        idmural = '".$idmural."' and
                        data_lido IS NOT NULL and
                        ativo = 'S'";
        $total = $this->retornarLinha($this->sql);

        return $total["total"];

    }

    function ListarTodasDisponiveis($campoFila, $id) {

        $this->sql = "SELECT
                        ".$this->campos."
                      FROM
                        murais m
                        INNER JOIN murais_filas mf ON ((m.idmural = mf.idmural) AND (mf.".$campoFila." = ".$id.") AND (mf.ativo = 'S'))
                      WHERE
                        '".date('Ymd')."' >= date_format(m.data_de,'%Y%m%d') AND
                        ('".date('Ymd')."' <= date_format(m.data_ate,'%Y%m%d') OR  m.data_ate IS NULL) AND
                        m.ativo='S'";
        $campos = array("m.descricao", "m.resumo", "m.titulo", "m.idmural");

        if(is_array($_GET["q"])) {
            foreach($campos as $campo){
                $busca = array();
                foreach($_GET["q"] as $ind => $valor) {
                    $valor = str_replace("'","",$valor);
                    $word_search = str_replace("\\'","",$valor);
                    $word_search = str_replace("\\","",$word_search);
                    $word_search = explode(" ",$word_search);
                    foreach($word_search as $ind => $palavra) $busca[] = $campo." LIKE '%".urldecode($palavra)."%' ";
                    $filtro[] = "(".implode(" AND ", $busca).")";
                }
            }
            if($filtro) $this->sql .= " AND (".implode(" OR ",$filtro).")";

            foreach($_GET["q"] as $campo => $valor) {
                $campo = explode("|",$campo);
                $valor = str_replace("'","",$valor);
                if ($campo[0] == 6) {
                    if($valor == "S")
                       $this->sql .= " AND ".$campo[1]." IS NOT NULL";
                    elseif($valor == "N")
                       $this->sql .= " AND ".$campo[1]." IS NULL";
                }
            }

        }
        $this->groupby = "m.idmural";
        return $this->retornarLinhas();
    }

    function RetornarPreviewMuralDisponiveis($campoFila, $id) {
        $dadosArray = array();
        $this->sql = "SELECT
                        m.titulo,
                        m.descricao,
                        mf.idfila
                      FROM
                        murais m
                        INNER JOIN murais_filas mf ON ((m.idmural = mf.idmural) AND (mf.".$campoFila." = ".$id.") AND (mf.ativo = 'S'))
                      WHERE
                        m.idmural = ".$this->id." AND
                        m.ativo = 'S'";

        $retorno = $this->retornarLinha($this->sql);
        if (!empty($retorno)){

        $sql = "update murais_filas set data_lido = now() where idfila = ".$retorno["idfila"];
        $this->executaSql($sql);

        $variavel = explode("[[I]][[",$retorno["descricao"]);
        if($variavel){
            $indice = array();
            foreach($variavel as $ind => $val){
                $id = explode("]]",$val);
                $indice[] = $id[0];
            }

            unset($indice[array_search("", $indice)]);

            foreach($indice as $ind => $val){
                $this->sql = "SELECT idmural_imagem, servidor FROM murais_imagens WHERE idmural = ".$this->id." AND idmural_imagem = ".intval($val)." AND ativo = 'S'";
                $linha = $this->retornarLinha($this->sql);
                $retorno["descricao"] = str_replace("[[I]][[".$val."]]", "<div style=\"text-align:left; max-width:800px; text-align:center\"><img src=\"http://".$_SERVER["SERVER_NAME"]."/storage/murais_imagens/".$linha["servidor"]."\" border=\"0\" /></div>", $retorno["descricao"]);
            }
        }

        $variavel = explode("[[A]][[",$retorno["descricao"]);
        if($variavel){
            $indice = array();
            foreach($variavel as $ind => $val){
                $id = explode("]]",$val);
                $indice[] = $id[0];
            }

            unset($indice[array_search("", $indice)]);

            foreach($indice as $ind => $val){
                $this->sql = "SELECT idmural_arquivo, nome FROM murais_arquivos WHERE idmural = ".$this->id." AND idmural_arquivo = ".intval($val)." AND ativo = 'S'";
                $linha = $this->retornarLinha($this->sql);
                $retorno["descricao"] = str_replace("[[A]][[".$val."]]", "<a href=\"http://".$_SERVER["SERVER_NAME"]."/".$this->url[0]."/".$this->url[1]."/".$this->url[2]."/".$this->id."/downloadArquivo/".$linha["idmural_arquivo"]."\" border=\"0\" >".$linha["nome"]."</a>", $retorno["descricao"]);
            }

        }

        //$retorno["descricao"] = "<form action=\"\" method=\"post\" enctype=\"multipart/form-data\" name=\"form\" class=\"form-inline\">" . $retorno["descricao"] . "</form>";
      }
        return $retorno;
    }


    function Retornar() {
        $this->sql = "SELECT ".$this->campos." FROM murais where ativo='S' and idmural='".$this->id."'";
        return $this->retornarLinha($this->sql);
    }

    function Cadastrar() {
        return $this->SalvarDados();
    }

    function Modificar() {
        return $this->SalvarDados();
    }

    function Remover() {
        return $this->RemoverDados();
    }

    function ListarImagens() {
        $this->sql = "(SELECT ".$this->campos." FROM
                            murais_imagens
                        where ativo='S' and idmural = ".intval($this->id).")";

        $this->groupby = "idmural_imagem";
        return $this->retornarLinhas();
    }

    /* METODO PARA CADASTRAR AS IMAGENS*/

    function uploadFile($file, $campoAux){
        $extensao = strtolower(strrchr($file["name"], "."));
        $nome_servidor = date("YmdHis")."_".uniqid().$extensao;

        if(move_uploaded_file($file["tmp_name"],$_SERVER["DOCUMENT_ROOT"]."/storage/".$campoAux["pasta"]."/".$nome_servidor)) {
            return $nome_servidor;
        } else
            return false;
    }

    function CadastrarImagens($erros = NULL) {
        $permissoes = 'jpg|jpeg|gif|png|bmp';
        $campo = array("pasta" => "murais_imagens");
        foreach ($this->files['arquivos']['name'] as $ind => $arquivo)
            if ($arquivo != "") $setado = true;
            if ($setado) {
                //VALIDA
                foreach ($this->files['arquivos']['name'] as $ind => $arquivo) {
                    $file['name'] = $this->files['arquivos']['name'][$ind];
                    $file['tmp_name'] = $this->files['arquivos']['tmp_name'][$ind];
                    $file['size'] = $this->files['arquivos']['size'][$ind];

                    unset($nome_servidor);

                    $file_aux['name'] = $file;
                    $validacao_tamanho = $this->ValidarArquivo($file_aux['name']);
                    if($validacao_tamanho) {
                        $this->retorno["erro"] = true;
                        $this->retorno["erros"][] = $validacao_tamanho;
                        return $this->retorno;
                    }
                }

                //INSERE
                foreach ($this->files['arquivos']['name'] as $ind => $arquivo) {

                    $file['name']     = $this->files['arquivos']['name'][$ind];
                    $file['tmp_name'] = $this->files['arquivos']['tmp_name'][$ind];
                    $file['size'] = $this->files['arquivo']['size'][$ind];

                    unset($nome_servidor);

                    $file_aux['name'] = $file;
                    $validacao_tamanho = $this->ValidarArquivo($file_aux['name']);
                    if($validacao_tamanho) {
                        $this->retorno["erro"] = true;
                        $this->retorno["erros"][] = $validacao_tamanho;
                        return $this->retorno;
                    }

                    $nome_servidor = $this->uploadFile($file, $campo);

                    if($nome_servidor) {
                        $sql = "insert into murais_imagens set
                              idmural = '".$this->id."',
                              ativo = 'S',
                              data_cad = NOW(),
                              nome = '".$this->files['arquivos']['name'][$ind]."',
                              tipo = '".$this->files['arquivos']['type'][$ind]."',
                              tamanho = '".$this->files['arquivos']['size'][$ind]."',
                              servidor = '".$nome_servidor."' ";
                        $query_arquivo = $this->executaSql($sql);
                        $idarquivo = mysql_insert_id();
                        if (!$query_arquivo) {
                            $erro = true;
                        } else {
                            $this->retorno["sucesso"] = true;
                            $this->monitora_oque = 1;
                            $this->monitora_onde = 82;
                            $this->monitora_qual = $idarquivo;
                            $this->Monitora();
                        }
                    }
                }
            //

        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'sem_arquivos';
        }
        return $this->retorno;

    }

    function RetornaImagens() {
        $this->sql = "SELECT * FROM murais_imagens where ativo = 'S' AND idmural = ".$this->id;

        $this->limite = -1;
        $this->ordem = "asc";
        $this->ordem_campo = "idmural_imagem";
        $this->groupby = "idmural_imagem";
        $dados = $this->retornarLinhas();

        return $dados;
    }

    function RetornaArquivos() {
        $this->sql = "SELECT * FROM murais_arquivos where ativo = 'S' AND idmural = ".$this->id;

        $this->limite = -1;
        $this->ordem = "asc";
        $this->ordem_campo = "idmural_arquivo";
        $this->groupby = "idmural_arquivo";
        $dados = $this->retornarLinhas();

        return $dados;
    }

    function RemoverImagens() {
        $this->sql = "UPDATE murais_imagens SET ativo='N' where idmural_imagem = ".$this->id;
        $dados = $this->executaSql($this->sql);

        if ($dados) {
            $this->retorno["sucesso"] = true;
            $this->monitora_oque = 3;
            $this->monitora_onde = 95;
            $this->monitora_qual = $this->id;
            $this->Monitora();
        }

        return $this->retorno;
    }

    function RetornarImagemDownload() {
        $this->sql = "SELECT * FROM murais_imagens WHERE idmural_imagem = ".$this->id;
        $retorno = $this->retornarLinha($this->sql);

        return $retorno;
    }
    function RetornarArquivoDownload() {
        $this->sql = "SELECT * FROM murais_arquivos WHERE idmural_arquivo = ".$this->id;
        $retorno = $this->retornarLinha($this->sql);

        return $retorno;
    }

    /* FIM */

    /* METODOS PARA MOSTRAR O PREVIEW DA PESQUISA */

    function RetornarPreviewMural() {
        $dadosArray = array();
        $this->sql = "SELECT titulo, descricao FROM murais WHERE idmural = ".$this->id;
        $retorno = $this->retornarLinha($this->sql);

        $variavel = explode("[[I]][[",$retorno["descricao"]);
        if($variavel){
            $indice = array();
            foreach($variavel as $ind => $val){
                $id = explode("]]",$val);
                $indice[] = $id[0];
            }

            unset($indice[array_search("", $indice)]);

            foreach($indice as $ind => $val){
                $this->sql = "SELECT idmural_imagem, servidor FROM murais_imagens WHERE idmural = ".$this->id." AND idmural_imagem = ".intval($val)." AND ativo = 'S'";
                $linha = $this->retornarLinha($this->sql);
                $retorno["descricao"] = str_replace("[[I]][[".$val."]]", "<div style=\"text-align:left; max-width:800px; text-align:center\"><img src=\"http://".$_SERVER["SERVER_NAME"]."/storage/murais_imagens/".$linha["servidor"]."\" border=\"0\" /></div>", $retorno["descricao"]);
            }
        }

        $variavel = explode("[[A]][[",$retorno["descricao"]);
        if($variavel){
            $indice = array();
            foreach($variavel as $ind => $val){
                $id = explode("]]",$val);
                $indice[] = $id[0];
            }

            unset($indice[array_search("", $indice)]);

            foreach($indice as $ind => $val){
                $this->sql = "SELECT idmural_arquivo, nome FROM murais_arquivos WHERE idmural = ".$this->id." AND idmural_arquivo = ".intval($val)." AND ativo = 'S'";
                $linha = $this->retornarLinha($this->sql);
                $retorno["descricao"] = str_replace("[[A]][[".$val."]]", "<a href=\"http://".$_SERVER["SERVER_NAME"]."/".$this->url[0]."/".$this->url[1]."/".$this->url[2]."/".$this->id."/downloadArquivo/".$linha["idmural_arquivo"]."\" border=\"0\" >".$linha["nome"]."</a>", $retorno["descricao"]);
            }

        }
        //$retorno["descricao"] = "<form action=\"\" method=\"post\" enctype=\"multipart/form-data\" name=\"form\" class=\"form-inline\">" . $retorno["descricao"] . "</form>";

        return $retorno;
    }

    function ListarTodosArquivos() {
        $this->sql = "SELECT ".$this->campos." FROM murais_arquivos WHERE ativo='S' AND idmural = '".$this->id."'";
        $this->aplicarFiltrosBasicos();
        $this->groupby = "idmural_arquivo";
        return $this->retornarLinhas();
    }

    function CadastrarArquivos($erros = NULL) {
        $permissoes = 'zip|rar|tar|gz|doc|docx|xls|xlsx|ppt|pptx|pps|ppsx|txt|pdf|odt|ods|odf';
        $campo = array("pasta" => "murais_arquivos");
        foreach ($this->files['arquivos']['name'] as $ind => $arquivo)
            if ($arquivo != "") $setado = true;
            if ($setado) {
                //VALIDA
                foreach ($this->files['arquivos']['name'] as $ind => $arquivo) {
                    //print_r2($this->files['arquivos'],true);
                    $file['name'] = $this->files['arquivos']['name'][$ind];
                    $file['tmp_name'] = $this->files['arquivos']['tmp_name'][$ind];
                    $file['size'] = $this->files['arquivos']['size'][$ind];


                    unset($nome_servidor);

                    $file_aux['name'] = $file;
                    $validacao_tamanho = $this->ValidarArquivo($file_aux['name']);
                    if($validacao_tamanho) {
                        $this->retorno["erro"] = true;
                        $this->retorno["erros"][] = $validacao_tamanho;
                        return $this->retorno;
                    }
                }

                //INSERE
                foreach ($this->files['arquivos']['name'] as $ind => $arquivo) {

                    $file['name']     = $this->files['arquivos']['name'][$ind];
                    $file['tmp_name'] = $this->files['arquivos']['tmp_name'][$ind];

                    unset($nome_servidor);


                    $nome_servidor = $this->uploadFile($file, $campo);

                    if($nome_servidor) {
                        $sql = "insert into murais_arquivos set
                              idmural = '".$this->id."',
                              ativo = 'S',
                              data_cad = NOW(),
                              nome = '".$this->files['arquivos']['name'][$ind]."',
                              tipo = '".$this->files['arquivos']['type'][$ind]."',
                              tamanho = '".$this->files['arquivos']['size'][$ind]."',
                              servidor = '".$nome_servidor."' ";
                        $query_arquivo = $this->executaSql($sql);
                        $idarquivo = mysql_insert_id();
                        if (!$query_arquivo) {
                            $erro = true;
                        } else {
                            $this->retorno["sucesso"][0] = true;
                            $this->retorno["sucesso"][1] = "1";
                            $this->retorno["sucessos"][0] = "arquivo_sucesso";
                            $this->monitora_oque = 1;
                            $this->monitora_onde = 96;
                            $this->monitora_qual = $idarquivo;
                            $this->Monitora();
                        }
                    }
                }
            //

        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'sem_arquivos';
        }
        unset($_POST["acao"]);
        return $this->retorno;

    }

    function RemoverArquivo(){
        $this->sql = "SELECT servidor FROM murais_arquivos where ativo='S' and idmural_arquivo='".$this->post["idmural_arquivo"]."'";
        $manual = $this->retornarLinha($this->sql);

        $this->sql = "update murais_arquivos set ativo = 'N' where idmural_arquivo = '".$this->post["idmural_arquivo"]."'";
        $query = $this->executaSql($this->sql);

        unlink('http://'.$_SERVER["SERVER_NAME"]."/storage/murais_arquivos/".$manual["servidor"]);

        if($query){
            $this->retorno["sucesso"][0] = true;
            $this->retorno["sucesso"][1] = "1";
            $this->retorno["sucessos"][] = "arquivo_deletar";
            $this->monitora_oque = 3;
            $this->monitora_onde = 96;
            $this->monitora_qual = $this->post["idmural_arquivo"];
            $this->Monitora();
        }
        unset($_POST["acao"]);
        return $this->retorno;

    }

  function listarFilaMural($idmural) {
    $this->sql = "select
                    ".$this->campos."
                  from
                    murais_filas mf
                  where
                    mf.ativo = 'S' and
                    mf.idmural = '".$idmural."'";

    if(is_array($_GET["q"])) {
      foreach($_GET["q"] as $campo => $valor) {
        $campo = explode("|",$campo);
        $valor = str_replace("'","",$valor);
        if(($valor || $valor === "0") && $valor <> "todos") {
          if($campo[0] == 1) {
            $this->sql .= " and ".$campo[1]." = '".$valor."' ";
          } elseif($campo[0] == 2)  {
            $busca = str_replace("\\'","",$valor);
            $busca = str_replace("\\","",$busca);
            $busca = explode(" ",$busca);
            foreach($busca as $ind => $buscar){
              $this->sql .= " and ".$campo[1]." like '%".urldecode($buscar)."%' ";
            }
          } elseif($campo[0] == 3)  {
              $this->sql .= " and date_format(".$campo[1].",'%d/%m/%Y') = '".$valor."' ";
          }
        }
      }
    }
    $this->groupby = "mf.idfila";
    return $this->retornarLinhas();
  }

  function listarFilaAddMuralUsuarios() {

    unset($this->post["acao"]);

    $filtro = array();
    $dados = array();
    foreach($this->post as $campo => $valor) {
      if(!empty($valor)) {
        if($campo == "data_nasc_dia") {
          $campo = "date_format(data_nasc, '%d')";
        }elseif($campo == "data_nasc_mes") {
          $campo = "date_format(data_nasc, '%m')";
        } elseif ($campo == "idsindicato" || $campo == "idescola") {
            continue;
        }

        if ($campo == 'nome' || $campo == 'documento' || $campo == 'email')
            $filtro[] = $campo." like '%".$valor."%'";
        else
            $filtro[] = $campo." = '".$valor."'";
      }
    }

    if ($this->post['idescola']) {
        $this->sql = "SELECT p.idsindicato
                        FROM escolas p INNER JOIN sindicatos i
                        WHERE p.idescola = '{$this->post['idescola']}'";
        $sindicato = $this->retornarLinha($this->sql);
        $idsindicato = $sindicato['idsindicato'];
    }

    $this->sql = "SELECT
                    usuarios_adm.idusuario,
                    usuarios_adm.idusuario as id,
                    usuarios_adm.nome,
                    usuarios_adm.ativo_login,
                    usuarios_adm.email
                  FROM
                    usuarios_adm
                  WHERE
                    NOT EXISTS(
                      SELECT
                        usuarios_adm.idusuario
                      FROM
                        murais_filas
                      WHERE
                        idmural = '".$this->id."' AND
                        usuarios_adm.idusuario = murais_filas.idusuario_adm AND
                        murais_filas.ativo = 'S'
                    ) AND usuarios_adm.ativo = 'S' ";
        if ($this->post['idsindicato'] || $this->post['idescola']) {
            $this->sql .= " AND (
                                (SELECT
                                        uai.idusuario
                                    FROM
                                        usuarios_adm_sindicatos uai
                                    LEFT OUTER JOIN escolas p
                                        ON p.idsindicato = uai.idsindicato
                                    WHERE
                                        uai.idusuario = usuarios_adm.idusuario AND
                                        uai.ativo = 'S'
                                        AND (uai.idsindicato = '{$this->post['idsindicato']}'
                                            OR uai.idsindicato = '{$idsindicato}')";
                        if ($this->post['idescola']) {
                            $this->sql .= " AND p.idescola = '{$this->post['idescola']}'";
                        }
                        $this->sql .= " LIMIT 1 ) IS NOT NULL
                                OR usuarios_adm.gestor_sindicato = 'S'
                            )";
                    }
    if(count($filtro) > 0) {
      $filtro = join(" AND ",$filtro);
      $this->sql .= " AND (".$filtro.")";
    }

    $this->limite = -1;
    $this->ordem_campo = "nome";
    $this->ordem = "ASC";
    $dados = $this->retornarLinhas();
    $dados["filtro"] = $filtro;
    return $dados;

  }

  function listarFilaAddMuralProfessores() {

    unset($this->post["acao"]);

    $dados = array();
    $filtro = array();
    foreach($this->post as $campo => $valor) {
      if(!empty($valor)) {
        if($campo == "data_nasc_dia") {
          $campo = "date_format(data_nasc, '%d')";
        }elseif($campo == "data_nasc_mes") {
          $campo = "date_format(data_nasc, '%m')";
        }

        if ($campo == 'nome' || $campo == 'documento' || $campo == 'email')
            $filtro[] = $campo." like '%".$valor."%'";
        else
            $filtro[] = $campo." = '".$valor."'";
      }
    }

    $this->sql = "SELECT p.idprofessor, p.idprofessor AS id, p.ativo_login, p.nome, p.email
                        FROM professores p
                        LEFT JOIN professores_avas pa ON (p.idprofessor = pa.idprofessor AND pa.ativo = 'S')
                        LEFT JOIN professores_cursos pc ON (p.idprofessor = pc.idprofessor AND pc.ativo = 'S')
                        LEFT JOIN professores_ofertas po ON (p.idprofessor = po.idprofessor AND po.ativo = 'S')
                        WHERE NOT EXISTS (
                            SELECT p.idprofessor
                            FROM murais_filas
                            WHERE idmural =  '".$this->id."'
                            AND p.idprofessor = murais_filas.idprofessor
                            AND murais_filas.ativo =  'S'
                        )
                        AND p.ativo =  'S'";
    if(count($filtro) > 0) {
      $filtro = join(" AND ",$filtro);
      $this->sql .= " AND (".$filtro.")";
    }

    $this->sql .= " group by p.idprofessor ";

    $this->limite = -1;
    $this->ordem_campo = "nome";
    $this->ordem = "ASC";

    $dados = $this->retornarLinhas();
    $dados["filtro"] = $filtro;
    return $dados;

  }

  function listarFilaAddMuralVendedores() {

    unset($this->post["acao"]);

    $dados = array();
    $filtro = array();
    foreach($this->post as $campo => $valor) {
       if ($campo == "idsindicato" || $campo == "idescola") {
            continue;
       }

      if(!empty($valor)) {
        if ($campo == 'nome' || $campo == 'documento' || $campo == 'email')
            $filtro[] = $campo." like '%".$valor."%'";
        else
            $filtro[] = $campo." = '".$valor."'";
      }

    }

    if ($this->post['idescola']) {
        $this->sql = "SELECT p.idsindicato
                        FROM escolas p INNER JOIN sindicatos i
                        WHERE p.idescola = '{$this->post['idescola']}'";
        $sindicato = $this->retornarLinha($this->sql);
        $idsindicato = $sindicato['idsindicato'];
    }

    $this->sql = "SELECT vendedores.idvendedor, vendedores.idvendedor AS id, vendedores.ativo_login, vendedores.nome, vendedores.email
                    FROM vendedores
                    LEFT JOIN vendedores_sindicatos vi ON (vi.idvendedor = vendedores.idvendedor AND vi.ativo = 'S')
                    WHERE NOT EXISTS (
                        SELECT vendedores.idvendedor
                        FROM murais_filas
                        WHERE idmural =  '".$this->id."'
                        AND vendedores.idvendedor = murais_filas.idvendedor
                        AND murais_filas.ativo =  'S'
                    )
                    AND vendedores.ativo =  'S'";

    if ($this->post['idsindicato'] || $this->post['idescola']) {
        $this->sql .= " AND (
                    (SELECT
                            vi.idvendedor
                        FROM
                            vendedores_sindicatos vi
                        LEFT OUTER JOIN escolas p
                            ON p.idsindicato = vi.idsindicato
                        WHERE
                            vi.idvendedor = vendedores.idvendedor AND
                            vi.ativo = 'S'
                            AND (vi.idsindicato = '{$this->post['idsindicato']}'
                                OR vi.idsindicato = '{$idsindicato}')";
            if ($this->post['idescola']) {
                $this->sql .= " AND p.idescola = '{$this->post['idescola']}'";
            }
            $this->sql .= " LIMIT 1 ) IS NOT NULL )";
    }

    if(count($filtro) > 0) {
      $filtro = join(" AND ",$filtro);
      $this->sql .= " AND (".$filtro.")";
    }

    $this->sql .= " group by vendedores.idvendedor ";

    $this->limite = -1;
    $this->ordem_campo = "nome";
    $this->ordem = "ASC";

    $dados = $this->retornarLinhas();
    $dados["filtro"] = $filtro;
    return $dados;

  }

  function RetornarMensagensNovas(){

  }


  function listarFilaAddMuralPessoas() {

    unset($this->post["acao"]);

    $dados = array();
    $filtro = array();
    foreach($this->post as $campo => $valor) {
      if(!empty($valor)) {
        if($campo == "data_nasc_dia") {
          $campo = "date_format(data_nasc, '%d')";
        }elseif($campo == "data_nasc_mes") {
          $campo = "date_format(data_nasc, '%m')";
        }

        if ($campo == 'nome' || $campo == 'documento' || $campo == 'email')
            $filtro[] = $campo." like '%".$valor."%'";
        else
            $filtro[] = $campo." = '".$valor."'";
      }
    }

    $this->sql = "SELECT
                    idpessoa,
                    idpessoa as id,
                    ativo_login,
                    nome,
                    email
                  FROM
                    pessoas
                  WHERE
                    NOT EXISTS(
                      SELECT
                        pessoas.idpessoa
                      FROM
                        murais_filas
                      WHERE
                        idmural = '".$this->id."' AND
                        pessoas.idpessoa = murais_filas.idpessoa AND
                        murais_filas.ativo = 'S'
                    ) AND ativo = 'S' ";
    if(count($filtro) > 0) {
      $filtro = join(" AND ",$filtro);
      $this->sql .= " AND (".$filtro.")";
    }
    $this->limite = -1;
    $this->ordem_campo = "nome";
    $this->ordem = "ASC";

    $dados = $this->retornarLinhas();
    $dados["filtro"] = $filtro;
    return $dados;

  }

  function listarFilaAddMuralAtendimentos() {

    unset($this->post["acao"]);

    $dados = array();
    $filtro = array();
    foreach($this->post as $campo => $valor) {
      if(!empty($valor)) {
        $filtro[] = $campo." = '".$valor."'";
      }
    }

    $this->sql = "SELECT
                    idatendimento,
                    idatendimento as id,
                    p.nome,
                    p.email,
                    p.ativo_login
                  FROM
                    atendimentos
                    inner join pessoas p on (atendimentos.idpessoa = p.idpessoa)
                  WHERE
                    NOT EXISTS(
                      SELECT
                        atendimentos.idatendimento
                      FROM
                        murais_filas
                      WHERE
                        idmural = '".$this->id."' AND
                        atendimentos.idatendimento = murais_filas.idatendimento AND
                        murais_filas.ativo = 'S'
                    ) AND atendimentos.ativo = 'S' AND p.ativo = 'S' ";
    if(count($filtro) > 0) {
      $filtro = join(" AND ",$filtro);
      $this->sql .= " AND (".$filtro.")";
    }
    $this->limite = -1;
    $this->ordem_campo = "p.nome";
    $this->ordem = "ASC";

    $dados = $this->retornarLinhas();
    $dados["filtro"] = $filtro;
    return $dados;

  }

  function listarFilaAddMuralMatriculas() {

    unset($this->post["acao"]);

    $dados = array();
    $filtro = array();
    foreach($this->post as $campo => $valor) {

        if(!empty($valor)) {

            if ($campo == "idmatricula") {
                $campo = "matriculas.idmatricula";
            }

            if ($campo == "data_cad_de") {
                $filtro[] = " date_format(matriculas.data_cad, '%Y-%m-%d') >= '".formataData($valor,'en',0)."'";
            } elseif ($campo == "data_cad_ate") {
                $filtro[] = "date_format(matriculas.data_cad, '%Y-%m-%d') <= '".formataData($valor,'en',0)."'";
            } elseif ($campo == "data_alteracao_de") {
                $filtro[] = "date_format(mh.data_cad, '%Y-%m-%d') >= '".formataData($valor,'en',0)."'";
            } elseif ($campo == "data_alteracao_ate") {
                $filtro[] = "date_format(mh.data_cad, '%Y-%m-%d') <= '".formataData($valor,'en',0)."'";
            } elseif ($campo == 'nome' || $campo == 'documento' || $campo == 'email') {
                $filtro[] = "p.".$campo." like '%".$valor."%'";
            } else {
                $filtro[] = $campo." = '".$valor."'";
            }

        }
    }

    $this->sql = "SELECT
                    matriculas.idmatricula, matriculas.idmatricula AS id, p.nome, p.email, p.ativo_login
                FROM
                    matriculas
                INNER JOIN pessoas p ON ( matriculas.idpessoa = p.idpessoa AND p.ativo = 'S')
                LEFT OUTER JOIN  matriculas_historicos mh ON (mh.idmatricula = matriculas.idmatricula AND
                                                        mh.acao = 'modificou' and mh.tipo = 'situacao' AND
                                                        mh.para = matriculas.idsituacao)
                WHERE NOT EXISTS (
                    SELECT matriculas.idmatricula
                    FROM murais_filas
                    WHERE idmural =  '".$this->id."'
                    AND matriculas.idmatricula = murais_filas.idmatricula
                    AND murais_filas.ativo =  'S'
                )
                AND matriculas.ativo =  'S'
                AND p.ativo =  'S'";

    if(count($filtro) > 0) {
      $filtro = join(" AND ",$filtro);
      $this->sql .= " AND (".$filtro.")";
    }
    $this->limite = -1;
    $this->ordem_campo = "p.nome";
    $this->groupby = "matriculas.idmatricula";
    $this->ordem = "ASC";

    $dados = $this->retornarLinhas();
    $dados["filtro"] = $filtro;
    return $dados;

  }

    public function listarFilaAddEscolas() {
        unset($this->post["acao"]);
        $dados = array();
        $filtro = array();
        foreach($this->post as $campo => $valor) {
            if(!empty($valor)) {
                $filtro[] = $campo." = '".$valor."'";
            }
        }
        $this->sql = "SELECT
                        idescola,
                        idescola as id,
                        nome_fantasia as nome,
                        email
                     FROM
                        escolas e
                     WHERE
                        NOT EXISTS(
                            SELECT
                                e.idescola
                            FROM
                                murais_filas
                            WHERE
                                idmural = '".$this->id."' AND
                                e.idescola = murais_filas.idescola AND
                                murais_filas.ativo = 'S'
                        ) AND e.ativo = 'S' AND ativo = 'S' ";
        if(count($filtro) > 0) {
            $filtro = join(" AND ",$filtro);
            $this->sql .= " AND (".$filtro.")";
        }
        $this->limite = -1;
        $this->ordem_campo = "e.nome_fantasia";
        $this->ordem = "ASC";

        $dados = $this->retornarLinhas();
        $dados["filtro"] = $filtro;
        return $dados;
    }

  function salvarFila() {

    $this->monitora_oque = 1;
    $this->monitora_onde = 82;

    if($this->url[6] == "usuariosadm") {
      $primaria = "idusuario_adm";
      $tipo = "UA";
    } elseif($this->url[6] == "professores") {
      $primaria = "idprofessor";
      $tipo = "PO";
    } elseif($this->url[6] == "atendentes") {
      $primaria = "idvendedor";
      $tipo = "VE";
    } elseif($this->url[6] == "pessoas") {
      $primaria = "idpessoa";
      $tipo = "PE";
    } elseif($this->url[6] == "atendimentos") {
      $primaria = "idatendimento";
      $tipo = "AT";
    } elseif($this->url[6] == "matriculas") {
      $primaria = "idmatricula";
      $tipo = "MA";
    } elseif($this->url[6] == "sindicatos") {
      $primaria = "idsindicato";
      $tipo = "IN";
    } elseif($this->url[6] == "cfc") {
      $primaria = "idescola";
      $tipo = "PL";
    }

    if(empty($this->post["filtro"])) {
      $this->post["filtro"] = "NULL";
    } else {
      $this->post["filtro"] = "'".$this->post["filtro"]."'";
    }

    $this->executaSql("BEGIN");

    foreach($this->post["id"] as $id => $nomeEmail) {
      $nomeEmail = explode("|",$nomeEmail);
      $sql = "insert into
                murais_filas
              set
                ".$primaria." = ".$id.",
                idmural = '".$this->id."',
                data_cad = now(),
                nome = '". mysql_real_escape_string($nomeEmail[0])."',
                email = '".mysql_real_escape_string($nomeEmail[1])."',
                data_enviado = now(),
                tipo = '".$tipo."',
                filtro = ".$this->post["filtro"];
      if(!$this->executaSql($sql)) {
        $this->retorno["erro"] = true;
        $this->retorno["erros"][] = $this->sql;
        $this->retorno["erros"][] = mysql_error();
        $this->executaSql("ROLLBACK");

        return $this->retorno;
        exit;
      } else {
        $this->monitora_qual = mysql_insert_id();
        $this->Monitora();
      }
    }

    $this->executaSql("COMMIT");
    $this->retorno["sucesso"] = true;

    return $this->retorno;
  }

  function removerFila() {

    include_once("../includes/validation.php");
    $regras = array(); // stores the validation rules

    //VERIFICANDO SE OPÃ‡ÃƒO REMOVER FOI MARCADA
    if(!$this->post["remover"])
      $regras[] = "required,remover,remover_vazio";

    //VALIDANDO FORMULÃRIO
    $erros = validateFields($this->post, $regras);

    //SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
    if(!empty($erros)){
      $this->retorno["erro"] = true;
      $this->retorno["erros"] = $erros;
    }else{
      $this->sql = "update murais_filas set ativo = 'N' where idfila = ".intval($this->post["remover"]);
      $remover = $this->executaSql($this->sql);
      if($remover){
        $this->retorno["sucesso"] = true;
        $this->monitora_oque = 3;
        $this->monitora_onde = 82;
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

  function RetornarCursosOfertas($oferta){
            $this->sql = "SELECT
                            c.idcurso as idcurso, c.nome as nome
                        FROM cursos c
                       INNER JOIN ofertas_cursos oc ON
                            (oc.idcurso = c.idcurso AND oc.ativo = 'S')
                       WHERE c.ativo =  'S'
                            AND oc.idoferta = '".$oferta."'";
            $res = mysql_query($this->sql);
            $this->retorno = array();
            while($row = mysql_fetch_assoc($res)){
                $this->retorno[] = $row;
            }
            echo json_encode($this->retorno);
    }

    function RetornarCursosOferta() {
        $this->sql = "SELECT c.idcurso, c.nome
                          FROM cursos c
                          INNER JOIN ofertas_cursos oc on c.idcurso = oc.idcurso and oc.ativo = 'S'
                          WHERE oc.idoferta = '".$this->id."'";
        $query = $this->executaSql($this->sql);
        $this->retorno = array();
        while($row = mysql_fetch_assoc($query)){
            $this->retorno[] = $row;
        }
        echo json_encode($this->retorno);
    }

    function RetornarEscolasOferta() {
        $this->sql = "SELECT p.idescola, p.nome_fantasia as nome
                          FROM escolas p
                          INNER JOIN ofertas_escolas op on p.idescola = op.idescola and op.ativo = 'S'
                          WHERE op.idoferta = '".$this->id."'";
        $query = $this->executaSql($this->sql);
        $this->retorno = array();
        while($row = mysql_fetch_assoc($query)){
            $this->retorno[] = $row;
        }
        echo json_encode($this->retorno);
    }

    function RetornarTurmasOferta() {
        $this->sql = "SELECT tu.idturma, tu.nome
                    FROM ofertas_turmas tu
                    WHERE tu.idoferta = '".$this->id."'";
        $query = $this->executaSql($this->sql);
        $this->retorno = array();
        while($row = mysql_fetch_assoc($query)){
            $this->retorno[] = $row;
        }
        echo json_encode($this->retorno);
    }
}

?>