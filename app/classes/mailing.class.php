<?php
class Mailing extends Core
{

    var $idemail      = NULL;
    var $ind          = NULL;
    var $val          = NULL;
    var $query        = NULL;
    var $get          = NULL;
    var $linha_antiga = NULL;
    var $linha_nova   = NULL;
    var $files        = NULL;
    var $linha        = array();
    var $post         = array();

    function ListarTodas() {
        $this->sql = "SELECT ".$this->campos." FROM
                            mailings where ativo='S'";

        $this->aplicarFiltrosBasicos();

        $this->groupby = "idemail";
        $this->retorno = $this->retornarLinhas();

        foreach($this->retorno as $ind => $mailing) {

            // Verificamos o total na fila que vão receber email.
            $this->sql = "SELECT count(*) as total FROM mailings_fila where idemail='".$mailing["idemail"]."' and `enviar_email` = 'S' and ativo='S' group by idemail";
            $nafilaemail = $this->RetornarLinha($this->sql);

            // Verificamos o total na fila que vão ou não receber email.
            $this->sql = "SELECT count(*) as total FROM mailings_fila where idemail='".$mailing["idemail"]."' and ativo='S' group by idemail";
            $nafilatodos = $this->RetornarLinha($this->sql);

            // Verificamos o total na fila espera de email.
            $this->sql = "SELECT count(*) as total FROM mailings_fila where idemail='".$mailing["idemail"]."' and enviado = 'S' and `enviar_email` = 'S' and ativo='S' group by idemail";
            $enviados = $this->RetornarLinha($this->sql);

            $this->retorno[$ind]["enviados"] = intval($enviados["total"])."/".intval($nafilaemail["total"]);

            $this->retorno[$ind]["respondidos"] = intval($respondidos)."/".intval($nafilatodos["total"]);

            $this->retorno[$ind]["situacao_legenda"] = $GLOBALS["situacao_mailing"][$this->config["idioma_padrao"]][$mailing["situacao"]];
            $this->retorno[$ind]["situacao_legenda_cor"] = $GLOBALS["situacao_mailing_cor"][$mailing["situacao"]];

        }
        return $this->retorno;
    }

    function Retornar() {
        $this->sql = "SELECT ".$this->campos."
                            FROM
                             mailings where ativo='S' and idemail='".$this->id."'";
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

    function reenviarMailing($idemail) {
        $this->sql = "select
                    m.idemail,
                    m.nome,
                    mf.hash,
                    mf.idemail_pessoa,
                    mf.email,
                    mf.nome as nome_pessoa,
                    mf.data_envio
                  from
                    mailings m
                    inner join mailings_fila mf on m.idemail = mf.idemail
                  where
                    m.situacao = 2 and
                    m.ativo = 'S' and
                    mf.ativo = 'S' and
                    mf.enviado = 'S' and
                    mf.idemail = '".$idemail."'
                  order by
                    mf.data_cad, mf.idemail_pessoa ";
        $this->limite = -1;
        $this->ordem_campo = false;
        $this->ordem = false;
        $fila = $this->retornarLinhas();

        mysql_query("START TRANSACTION");
        foreach($fila as $linha) {
            $sql_hist = "insert into mailings_fila_reenvio_historico set idemail_pessoa = '".$linha['idemail_pessoa']."', data = '".$linha['data_envio']."' ";
            $hist = $this->executaSql($sql_hist);
            $sql_env = "update mailings_fila set enviado = 'N' where idemail_pessoa = '".$linha['idemail_pessoa']."' and enviar_email = 'S' ";
            $env = $this->executaSql($sql_env);

            if (!$hist || !$env)
                $erro = true;
        }

        $sql_pesq = "update mailings set situacao = '1', total_reenvio = (total_reenvio + 1) where idemail = '".$idemail."'";
        $pesq = $this->executaSql($sql_pesq);
        if(!$pesq)
            $erro = true;

        if(!$erro) {
           $this->monitora_oque = 32;
           $this->monitora_onde = 146;
           $this->monitora_qual = $idemail;
           $this->Monitora();
           mysql_query("COMMIT");
           $info['sucesso'] = true;
        } else {
           mysql_query("ROLLBACK");
           $info['sucesso'] = false;
        }

        return $info;
    }

    function ListarPessoasAss() {
        $this->sql = "(SELECT ".$this->campos." FROM
                            mailings_fila pp
                            INNER JOIN pessoas per ON (pp.idpessoa=per.idpessoa)
                        where pp.ativo='S' and pp.idemail = ".intval($this->id).")";

        $this->groupby = "pp.idemail_pessoa";
        return $this->retornarLinhas();
    }

    function BuscarPessoa() {

        $this->sql = "select
                        p.idpessoa as 'key', p.nome as value
                      from
                        pessoas p
                      where
                        (p.nome like '%".$this->get["tag"]."%') AND p.ativo = 'S' AND
                      NOT EXISTS (SELECT mf.idpessoa FROM mailings_fila mf WHERE mf.idpessoa = p.idpessoa AND ativo = 'S' AND mf.idemail = '".$this->id."')";

        $this->limite = -1;
        $this->ordem_campo = "p.nome";
        $this->groupby = "mf.idpessoa";
        $dados = $this->retornarLinhas();

        return json_encode($dados);

    }

    function AssociarPessoas() {

        foreach($this->post["pessoas"] as $this->ind => $this->val) {

            $this->sql = "select idemail_pessoa from mailings_fila where idemail = '".$this->id."' and idpessoa = '".$this->val."' ";
            $this->linha = $this->retornarLinha($this->sql);

            if(!$this->linha){
                $this->sql = "insert into mailings_fila
                              (ativo, data_cad, idemail, idpessoa)
                              values
                              ('S',now(),'".$this->id."','".$this->val."')";

                $this->query = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();

                $this->sql = "update mailings_fila set hash = '".md5($this->monitora_qual)."' where idemail_pessoa = ".$this->monitora_qual;
                $this->query = $this->executaSql($this->sql);

                $sql_enviado = "update mailings set situacao = '1' where idemail = '".$this->id."' ";
                $resultado_enviado = $this->executaSql($sql_enviado);

                if($this->query){
                    $this->retorno["sucesso"] = true;
                    $this->monitora_oque = 1;
                    $this->monitora_onde = 150;
                    $this->Monitora();
                } else {
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = $this->sql;
                    $this->retorno["erros"][] = mysql_error();
                }
            }else{
                $this->sql = "update mailings_fila set ativo = 'S' where idemail_pessoa = '".$this->linha["idemail_pessoa"]."'";
                $this->query = $this->executaSql($this->sql);

                if($this->query){
                    $this->retorno["sucesso"] = true;
                    $this->monitora_oque = 2;
                    $this->monitora_onde = 150;
                    $this->monitora_qual = $this->linha["idemail_pessoa"];
                    $this->Monitora();
                } else {
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = $this->sql;
                    $this->retorno["erros"][] = mysql_error();
                }

            }
        }

        return $this->retorno;
    }

    function RemoverPessoas() {

        include_once("../includes/validation.php");
        $regras = array(); // stores the validation rules

        //VERIFICANDO SE OPNIAO REMOVER FOI MARCADA
        if(!$this->post["remover"])
            $regras[] = "required,remover,remover_vazio";

        //VALIDANDO FORMULARIO
        $erros = validateFields($this->post, $regras);

        //SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
        if(!empty($erros)){
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        }else{
            $this->sql = "update mailings_fila set ativo = 'N' where idemail_pessoa = ".intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if($desassociar){
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 150;
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

    function ListarImagens() {
        $this->sql = "(SELECT ".$this->campos." FROM
                            mailings_imagens
                        where ativo='S' and idemail = ".intval($this->id).")";

        $this->groupby = "idemail_imagem";
        return $this->retornarLinhas();
    }

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
        $campo = array("pasta" => "mailings_imagens");
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
                    $file['size'] = $this->files['arquivos']['size'][$ind];

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
                        $sql = "insert into mailings_imagens set
                              idemail = '".$this->id."',
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
                            $this->monitora_onde = 148;
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
        $this->sql = "SELECT * FROM mailings_imagens where ativo = 'S' AND idemail = ".$this->id;

        $this->limite = -1;
        $this->ordem = "asc";
        $this->ordem_campo = "idemail_imagem";
        $this->groupby = "idemail_imagem";
        $dados = $this->retornarLinhas();

        return $dados;
    }

    function RemoverImagens() {
        $this->sql = "UPDATE mailings_imagens SET ativo='N' where idemail_imagem = ".$this->id;
        $dados = $this->executaSql($this->sql);

        if ($dados) {
            $this->retorno["sucesso"] = true;
            $this->monitora_oque = 3;
            $this->monitora_onde = 148;
            $this->monitora_qual = $this->id;
            $this->Monitora();
        }

        return $this->retorno;
    }

    function RetornarImagemDownload() {
        $this->sql = "SELECT * FROM mailings_imagens
                      where
                        idemail_imagem = ".$this->id;
        $retorno = $this->retornarLinha($this->sql);

        return $retorno;
    }

    /* METODOS PARA MOSTRAR O PREVIEW Do MAILING */
    function RetornarPreviewMailing($responder = NULL, $layout = 'layout_branco') {

        $dadosArray = array();
        $this->sql = "SELECT nome, corpo_email FROM mailings WHERE idemail = ".$this->id;
        $retorno = $this->retornarLinha($this->sql);

        $variavel = explode("[[I][",$retorno["corpo_email"]);
        if($variavel){
            foreach($variavel as $ind => $val){
                $id = explode("]]",$val);
                $indice[] = $id[0];
            }

            unset($indice[array_search("", $indice)]);

            foreach($indice as $ind => $val){
                $this->sql = "SELECT idemail_imagem, servidor FROM mailings_imagens WHERE ativo = 'S' AND idemail = ".$this->id." AND idemail_imagem = ".intval($val)."";
                $linha = $this->retornarLinha($this->sql);
                $retorno["corpo_email"] = str_replace("[[I][".$val."]]", "<div style=\"text-align:left; width:800px; text-align:center\"><img src=\"http://".$_SERVER["SERVER_NAME"]."/storage/mailings_imagens/".$linha["servidor"]."\" border=\"0\" /></div>", $retorno["corpo_email"]);
            }
        }

        //$retorno["corpo_email"] = "<form action=\"\" method=\"post\" enctype=\"multipart/form-data\" name=\"form\" class=\"form-inline\">"./*$pergunta_nome.*/$retorno["corpo_email"].$resposta_campos."</form>";

        // Busca o layout do email
        $layoutAux = file("../assets/email/".$layout.".html");
        $layoutHTML = "";
        foreach($layoutAux as $linha => $valor) {
            $layoutHTML .= $valor;
            }

        $layoutHTML = str_replace("[[MENSAGEM]]",$retorno["corpo_email"],$layoutHTML);
        $layoutHTML = str_replace("[[URLSISTEMA]]",$this->config["urlSistema"],$layoutHTML);
        $retorno["layout"] = $layoutHTML;

        return $retorno;
    }

    function clonarMailing() {
        $mailingPai = $this->Retornar();

        //COPIAR Mailing
        $this->sql = "  insert into mailings (ativo,ativo_painel,data_cad,nome,descricao, situacao,idemail_pai) values('".$mailingPai['ativo']."', '".$mailingPai['ativo_painel']."', NOW(), '".$mailingPai['nome']."', '".$mailingPai['descricao']."', '0', ".$mailingPai['idemail'].")";
        $this->query = mysql_query($this->sql) or die('Não inseriu mailing'.mysql_error());
        $idNovoMailing = mysql_insert_id();

        // -----------
      if ($idNovoMailing) {

          //COPIAR IMAGENS
          $imagensPai = $this->RetornaImagens();
          if(is_array($imagensPai)) {
              $arrayImagens = array();
              foreach($imagensPai as $id => $imagem){
                  $sql = "insert into mailings_imagens values(NULL, '".$imagem['ativo']."', '".$imagem['data_cad']."', ".$idNovoMailing.", '".$imagem['nome']."', '".$imagem['servidor']."', '".$imagem['tipo']."', ".$imagem['tamanho'].")";
                  //echo $sql;exit;
                  $query = $this->executaSql($sql);
                  if ($query) $arrayImagens[] = $imagem['servidor'];
              }
              //COPIAR AS IMAGENS
              if (is_array($arrayImagens)) {
                  foreach($arrayImagens as $id => $imagem){
                      copy($_SERVER["DOCUMENT_ROOT"]."/storage/mailings_imagens/".$imagem, $_SERVER["DOCUMENT_ROOT"]."/storage/mailings_imagens_clone/".$imagem);
                  }
              }
          }
          // -----------
            $this->monitora_onde = 146; //Mailings

            $this->monitora_oque = 4;
            $this->monitora_qual = $mailingPai['idemail'];
            $this->Monitora();

            $this->monitora_oque = 1;
            $this->monitora_qual = $idNovoMailing;
            $this->Monitora();

          return $idNovoMailing;
      }
      return false;

    }

    function listarFilaMailing($idemail) {
      $this->sql = "select ".$this->campos.", p.nome AS nomePessoa, p.email AS pessoaEmail,
      u.nome AS usuAdm, u.email AS admEmail, pr.nome AS nomeProfessor, pr.email AS professorEmail, resp.nome AS pessoaMatricula,
      resp.email AS emailPessoaMatricula, u.idusuario as idusuario_adm, pr.idprofessor, p.idpessoa, mf.enviar_email as enviarEmail
                    from
                      mailings_fila mf
                      left outer join usuarios_adm u ON(mf.idusuario_gestor = u.idusuario)
                      left outer join pessoas p ON(p.idpessoa = mf.idpessoa)
                      left outer join professores pr ON(pr.idprofessor = mf.idprofessor)
                      left outer join matriculas mat ON(mat.idmatricula = mf.idmatricula)
                      left outer join pessoas resp ON(resp.idpessoa = mat.idpessoa)
                    where
                      mf.ativo = 'S' and
                      mf.idemail = ".$idemail;

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
                $this->sql .= " and mf.".$campo[1]." like '%".urldecode($buscar)."%' ";
              }
            } elseif($campo[0] == 3)  {
                $this->sql .= " and date_format(".$campo[1].",'%d/%m/%Y') = '".$valor."' ";
            }
          }
        }
      }

      $this->sql .= " order by idemail_pessoa desc";

      $this->groupby = "mf.idemail";
      $this->ordem = "desc";
      $this->ordem_campo = "idemail_pessoa";

      $this->sqlAux = str_replace($this->campos, "count(".$this->groupby.") as total", $this->sql);
      $linhaAux = $this->retornarLinha($this->sqlAux);
      $this->total = intval($linhaAux["total"]);

      $this->query = $this->executaSql($this->sql);
      $this->retorno = array();

      while($linha = mysql_fetch_assoc($this->query)){
        $this->retorno[] = $linha;
      }

      return $this->retorno;
  }

    function listarFiltros($idemail) {
    $this->sql = "select mf.*, u.nome from mailings_filtros mf inner join usuarios_adm u on (mf.idusuario = u.idusuario) where mf.idemail = ".$idemail;
    $this->query = $this->executaSql($this->sql);
    $this->retorno = array();
    while($linha = mysql_fetch_assoc($this->query)){
      $this->retorno[] = $linha;
    }
    return $this->retorno;
  }

    function listarFilaAddMailingUsuarios() {

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
                    usuarios_adm.celular,
                    usuarios_adm.ativo_login,
                    usuarios_adm.email
                  FROM
                    usuarios_adm
                  WHERE
                    NOT EXISTS(
                      SELECT
                        usuarios_adm.idusuario
                      FROM
                        mailings_fila
                      WHERE
                        idemail = '".$this->id."' AND
                        usuarios_adm.idusuario = mailings_fila.idusuario_gestor AND
                        mailings_fila.ativo = 'S'
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
    $this->groupby = 'usuarios_adm.email';
    $this->manter_groupby = true;

    $dados = $this->retornarLinhas();
    $dados["filtro"] = $filtro;
    return $dados;

  }

    function listarFilaAddMailingProfessores() {

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

    $this->sql = "SELECT p.idprofessor, p.idprofessor AS id, p.ativo_login, p.nome, p.email , p.celular
                        FROM professores p
                        LEFT JOIN professores_avas pa ON (p.idprofessor = pa.idprofessor AND pa.ativo = 'S')
                        LEFT JOIN professores_cursos pc ON (p.idprofessor = pc.idprofessor AND pc.ativo = 'S')
                        LEFT JOIN professores_ofertas po ON (p.idprofessor = po.idprofessor AND po.ativo = 'S')
                        WHERE NOT EXISTS (
                            SELECT
                            p.idprofessor
                          FROM
                            mailings_fila
                          WHERE
                            idemail = '".$this->id."' AND
                            p.idprofessor = mailings_fila.idprofessor AND
                            mailings_fila.ativo = 'S'
                        )
                        AND p.ativo =  'S'";
    if(count($filtro) > 0) {
      $filtro = join(" AND ",$filtro);
      $this->sql .= " AND (".$filtro.")";
    }

    $this->limite = -1;
    $this->ordem_campo = "nome";
    $this->ordem = "ASC";
    $this->groupby = 'p.email';
    $this->manter_groupby = true;

    $dados = $this->retornarLinhas();
    $dados["filtro"] = $filtro;
    return $dados;

  }

    function listarFilaAddMailingPessoas()
    {

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
                            celular,
                            email
                      FROM
                            pessoas
                      WHERE
                            NOT EXISTS(
                              SELECT
                                    pessoas.idpessoa
                              FROM
                                    mailings_fila
                              WHERE
                                    idemail = '".$this->id."' AND
                                    pessoas.idpessoa = mailings_fila.idpessoa AND
                                    mailings_fila.ativo = 'S'
                            ) AND ativo = 'S' ";
    if(count($filtro) > 0) {
      $filtro = join(" AND ",$filtro);
      $this->sql .= " AND (".$filtro.")";
    }
    $this->limite = -1;
    $this->ordem_campo = "nome";
    $this->ordem = "ASC";
    $this->groupby = 'email';
    $this->manter_groupby = true;

    $dados = $this->retornarLinhas();
    $dados["filtro"] = $filtro;
    return $dados;

  }

    function listarFilaAddMailingVendedores()
    {

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
                            idvendedor,
                            idvendedor as id,
                            ativo_login,
                            nome,
                            celular,
                            venda_bloqueada,
                            email
                      FROM
                            vendedores
                      WHERE
                            NOT EXISTS(
                              SELECT
                                    vendedores.idvendedor
                              FROM
                                    mailings_fila
                              WHERE
                                    idemail = '".$this->id."' AND
                                    vendedores.idvendedor = mailings_fila.idvendedor AND
                                    mailings_fila.ativo = 'S'
                            ) AND ativo = 'S' ";
    if(count($filtro) > 0) {
      $filtro = join(" AND ",$filtro);
      $this->sql .= " AND (".$filtro.")";
    }
    $this->limite = -1;
    $this->ordem_campo = "nome";
    $this->ordem = "ASC";
    $this->groupby = 'email';
    $this->manter_groupby = true;

    $dados = $this->retornarLinhas();
    $dados["filtro"] = $filtro;
    return $dados;

  }

    public function listarFilaAddMailingVisitaVendedores()
   {
        unset($this->post["acao"]);

        $dados = array();
        $filtro = array();
        foreach($this->post as $campo => $valor) {
            if(!empty($valor)) {
                if ($campo == "data_cad_de") {
                    $filtro[] = " date_format(visitas_vendedores.data_cad, '%Y-%m-%d') >= '".formataData($valor,'en',0)."'";
                } elseif ($campo == "data_cad_ate") {
                    $filtro[] = "date_format(visitas_vendedores.data_cad, '%Y-%m-%d') <= '".formataData($valor,'en',0)."'";
                }elseif ($campo == 'nome' || $campo == 'email') {
                    $filtro[] = "visitas_vendedores.".$campo." like '%".$valor."%'";
                } elseif ($campo == "idcurso") {
                    $filtro[] = "visitas_vendedores_cursos.".$campo." = ".$valor;
                } elseif ($campo == "documento") {
                    $filtro[] = "pessoas.".$campo." like '%".$valor."%'";
                } else {
                    $filtro[] = "visitas_vendedores.".$campo." = '".$valor."'";
                }
            }
        }

        $this->sql = "SELECT
                visitas_vendedores.idvisita,
                visitas_vendedores.idvisita as id,
                visitas_vendedores.nome,
                visitas_vendedores.celular,
                visitas_vendedores.email,
                visitas_vendedores.data_cad,
                pessoas.idpessoa,
                pessoas.nome as pessoa,
                pessoas.celular as pessoa_celular,
                pessoas.email as pessoa_email
            FROM
                visitas_vendedores
            LEFT JOIN visitas_vendedores_cursos ON (visitas_vendedores.idvisita = visitas_vendedores_cursos.idvisita)
            LEFT JOIN pessoas ON (visitas_vendedores.idpessoa = pessoas.idpessoa)
            WHERE
                NOT EXISTS(
                    SELECT
                        visitas_vendedores.idvisita
                    FROM
                        mailings_fila
                    WHERE
                        idemail = '{$this->id}' AND
                        visitas_vendedores.idvisita = mailings_fila.idvisita AND
                        mailings_fila.ativo = 'S'
                )
                AND visitas_vendedores.ativo = 'S' ";
        if(count($filtro) > 0) {
            $filtro = join(" AND ",$filtro);
            $this->sql .= " AND (".$filtro.")";
        }

        $this->limite = -1;
        $this->ordem_campo = "nome";
        $this->ordem = "ASC";
        $this->groupby = 'pessoas.email';
        $this->manter_groupby = true;

        $dados = $this->retornarLinhas();
        $dados["filtro"] = $filtro;
        foreach ($dados as $key => $dado) {
            if($dado["idpessoa"]){
                $dados[$key]["email"] = $dado["pessoa_email"];
                $dados[$key]["nome"] = $dado["pessoa"];
                $dados[$key]["celular"] = $dado["pessoa_celular"];
            }
        }
        //print_r2($dados);
        return $dados;
    }

    public function listarFilaAddMailingCFC()
    {
        unset($this->post["acao"]);

        $dados = array();
        $filtro = array();
        foreach($this->post as $campo => $valor) {
            if(!empty($valor)) {
                if ($campo == "nome") {
                    $filtro[] = "nome_fantasia like '%{$valor}%'";
                } elseif ("idsindicato") {
                    $filtro[] = "{$campo} = '{$valor}'";
                } elseif ("idestado") {
                    $filtro[] = "{$campo} = '{$valor}'";
                } elseif ("idcidade") {
                    $filtro[] = "{$campo} = '{$valor}'";
                }
            }
        }

        $this->sql = "SELECT idescola, nome_fantasia, email, gerente_celular as celular
            FROM escolas
            WHERE
                NOT EXISTS(
                    SELECT
                        escolas.idescola
                    FROM
                        mailings_fila
                    WHERE
                        idemail = '{$this->id}' AND
                        escolas.idescola = mailings_fila.idescola AND
                        mailings_fila.ativo = 'S'
                )
                AND escolas.ativo = 'S'
                AND escolas.ativo_painel = 'S' ";
        if(count($filtro) > 0) {
            $filtro = join(" AND ",$filtro);
            $this->sql .= " AND (".$filtro.")";
        }

        $this->sql .= " GROUP BY escolas.idescola ";
        $this->limite = -1;
        $this->ordem_campo = "nome_fantasia";
        $this->ordem = "ASC";

        $dados = $this->retornarLinhas();
        $dados["filtro"] = $filtro;
        foreach ($dados as $key => $dado) {
            if($dado["idescola"]){
                $dados[$key]["id"] = $dado["idescola"];
                $dados[$key]["email"] = $dado["email"];
                $dados[$key]["nome"] = $dado["nome_fantasia"];
            }
        }
        //print_r2($dados);
        return $dados;
    }

    public function listarFilaAddMailingSindicato()
    {
        unset($this->post["acao"]);

        $dados = array();
        $filtro = array();
        foreach($this->post as $campo => $valor) {
            if(!empty($valor)) {
                if ("nome") {
                    $filtro[] = "{$campo} like '%{$valor}%'";
                } elseif ("idestado_competencia") {
                    $filtro[] = "{$campo} = '{$valor}'";
                } elseif ("idcidade") {
                    $filtro[] = "{$campo} = '{$valor}'";
                }
            }
        }

        $this->sql = "SELECT concat('(',idsindicato,') ',nome_abreviado) as nome,
            email, idsindicato, gerente_celular as celular
            FROM sindicatos
            WHERE
                NOT EXISTS(
                    SELECT
                        sindicatos.idsindicato
                    FROM
                        mailings_fila
                    WHERE
                        idemail = '{$this->id}' AND
                        sindicatos.idsindicato = mailings_fila.idsindicato AND
                        mailings_fila.ativo = 'S'
                )
                AND sindicatos.email IS NOT NULL
                AND sindicatos.ativo = 'S' ";
        if(count($filtro) > 0) {
            $filtro = join(" AND ",$filtro);
            $this->sql .= " AND (".$filtro.")";
        }

        // $this->sql .= " GROUP BY sindicatos.idsindicato ";
        $this->limite = -1;
        $this->ordem_campo = "nome";
        $this->ordem = "ASC";
        $this->groupby = 'email';
        $this->manter_groupby = true;

        $dados = $this->retornarLinhas();
        $dados["filtro"] = $filtro;
        foreach ($dados as $key => $dado) {
            if($dado["idescola"]){
                $dados[$key]["id"] = $dado["idescola"];
                $dados[$key]["email"] = $dado["email"];
                $dados[$key]["nome"] = $dado["nome_fantasia"];
            }
        }
        //print_r2($dados);
        return $dados;
    }

    function listarFilaAddMailingMatriculas()
    {
        unset($this->post["acao"]);

        $dados = array();
        $filtro = array();
        $data_alteracao = array();
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
                    $data_alteracao[] = "date_format(mh.data_cad, '%Y-%m-%d') >= '".formataData($valor,'en',0)."'";
                } elseif ($campo == "data_alteracao_ate") {
                    $data_alteracao[] = "date_format(mh.data_cad, '%Y-%m-%d') <= '".formataData($valor,'en',0)."'";
                } elseif ($campo == 'nome' || $campo == 'documento' || $campo == 'email') {
                    $filtro[] = "p.".$campo." like '%".$valor."%'";
                } else {
                    $filtro[] = $campo." = '".$valor."'";
                }

            }
        }

        $sql = "";
        if(count($data_alteracao) > 0) { //FOI JOGADO PARA DENTRO DO "EXISTS" PORQUE FORA NAO FUNCIONA
            $data_alteracao = join(" AND ",$data_alteracao);
            $sql .= " AND (".$data_alteracao.")";
        }

        $this->sql = "SELECT
                        matriculas.idmatricula, matriculas.idmatricula AS id, p.nome, p.email, p.ativo_login ,p.celular
                    FROM
                        matriculas
                    INNER JOIN pessoas p ON ( matriculas.idpessoa = p.idpessoa AND p.ativo = 'S')

                    WHERE
                    EXISTS (
                        SELECT
                            mh.idhistorico
                        FROM
                            matriculas_historicos mh
                        WHERE
                            mh.idmatricula = matriculas.idmatricula AND
                            mh.acao = 'modificou' AND
                            mh.tipo = 'situacao' AND
                            mh.para = matriculas.idsituacao
                            ".$sql.")
                    AND
                    NOT EXISTS (
                        SELECT
                            matriculas.idmatricula
                          FROM
                            mailings_fila mf
                          WHERE
                            idemail = '".$this->id."' AND
                            matriculas.idmatricula = mf.idmatricula AND
                            mf.ativo = 'S'
                    )
                    AND matriculas.ativo =  'S'
                    AND p.ativo =  'S'";

        if(count($filtro) > 0) {
          $filtro = join(" AND ",$filtro);
          $this->sql .= " AND (".$filtro.")";
        }
        //echo $this->sql;exit;
        $this->limite = -1;
        $this->ordem_campo = "p.nome";
        $this->ordem = "ASC";
        $this->groupby = 'p.email';
        $this->manter_groupby = true;

        $dados = $this->retornarLinhas();
        $dados["filtro"] = $filtro;
        return $dados;
  }

    function salvarFila()
    {
        /*******************FUNÇÃO QUE SALVA AS SELEÇÕES E AQUI QUE DIZ DE É OU NÃO PRA ENVIAR POR E-MAIL****************/
        $this->monitora_oque = 1;
        $this->monitora_onde = 149;
        if($this->url[6] == "usuariosadm") {
          $primaria = "idusuario_gestor";
          $tipo = "UA";
        } elseif($this->url[6] == "professores") {
          $primaria = "idprofessor";
          $tipo = "PR";
        } elseif($this->url[6] == "pessoas") {
          $primaria = "idpessoa";
          $tipo = "PE";
        } elseif($this->url[6] == "matriculas") {
          $primaria = "idmatricula";
          $tipo = "MA";
        } elseif($this->url[6] == "atendentes") {
          $primaria = "idvendedor";
          $tipo = "VE";
        } elseif($this->url[6] == "visita_atendentes") {
          $primaria = "idvisita";
          $tipo = "VV";
        } elseif($this->url[6] == "cfc") {
          $primaria = "idescola";
          $tipo = "ES";
        } elseif($this->url[6] == "sindicatos") {
          $primaria = "idsindicato";
          $tipo = "SI";
        }

        if(empty($this->post["filtro"])) {
             $this->post["filtro"] = "NULL";
        } else {
             $this->post["filtro"] = "'".$this->post["filtro"]."'";
        }

        $this->executaSql("BEGIN");

        $sql = "insert into
                  mailings_filtros
                set
                  data_cad = now(),
                  idemail = '".$this->id."',
                  idusuario = '".$this->idusuario."',
                  filtro = ".$this->post["filtro"].",
                  busca = '".$this->post["busca"]."'";
        //echo $sql;exit;
        $this->executaSql($sql);
        $idfiltro = mysql_insert_id();

        foreach($this->post["id"] as $id => $nomeEmail) {

        /*Verifica se não deseja enviar por e-mail*/
          if (!$this->post["nao_envio"][$id]){
                $this->post["nao_envio"][$id] = 'S';}

          $nomeEmail = explode("|",$nomeEmail);
          $sql = "insert into
                    mailings_fila
                  set
                    ".$primaria." = ".$id.",
                    idemail = '".$this->id."',
                    idfiltro = '".$idfiltro."',
                    nome = '".mysql_real_escape_string($nomeEmail[0])."',
                    email = '".mysql_real_escape_string($nomeEmail[1])."',
                    enviar_email = '".$this->post["nao_envio"][$id]."',
                    data_cad = now(),
                    tipo = '".$tipo."',
                    paraemail = 'S' ";

                    if ($this->post["idsms"][$id] and $nomeEmail[2]){
                        $sql .= ' , parasms = "S" , celular = "'.mysql_real_escape_string($nomeEmail[2]).'" ';
                        unset($this->post["idsms"][$id]);
                    }

            //echo $sql;exit;
          if(!$this->executaSql($sql)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
            $this->executaSql("ROLLBACK");
            return $this->retorno;
          } else {
            $this->monitora_qual = mysql_insert_id();
            $this->sql = "update mailings_fila set hash = '".md5($this->monitora_qual)."' where idemail_pessoa = ".$this->monitora_qual;
            $this->query = $this->executaSql($this->sql);

            $sql_enviado = "update mailings set situacao = '1' where idemail = '".$this->id."' ";
            $resultado_enviado = $this->executaSql($sql_enviado);

            $this->Monitora();
          }
        }

        foreach($this->post["idsms"] as $id => $nomeEmail) {


          $nomeEmail = explode("|",$nomeEmail);
            if($nomeEmail[2]){
                 $sql = "insert into
                        mailings_fila
                      set
                        ".$primaria." = ".$id.",
                        idemail = '".$this->id."',
                        idfiltro = '".$idfiltro."',
                        nome = '".mysql_real_escape_string($nomeEmail[0])."',
                        email = '".mysql_real_escape_string($nomeEmail[1])."',
                        celular = '".mysql_real_escape_string($nomeEmail[2])."',
                        data_cad = now(),
                        tipo = '".$tipo."',
                        paraemail = 'N' ,
                        parasms = 'S' ";

                  if(!$this->executaSql($sql)) {
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = $this->sql;
                    $this->retorno["erros"][] = mysql_error();
                    $this->executaSql("ROLLBACK");
                    return $this->retorno;
                  } else {
                    $this->monitora_qual = mysql_insert_id();
                    $this->sql = "update mailings_fila set hash = '".md5($this->monitora_qual)."' where idemail_pessoa = ".$this->monitora_qual;
                    $this->query = $this->executaSql($this->sql);

                    $sql_enviado = "update mailings set situacao = '1' where idemail = '".$this->id."' ";
                    $resultado_enviado = $this->executaSql($sql_enviado);

                    $this->Monitora();
                  }
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
      $this->sql = "update mailings_fila set ativo = 'N' where idemail_pessoa = ".intval($this->post["remover"]);
      $remover = $this->executaSql($this->sql);
      if($remover){
        $this->retorno["sucesso"] = true;
        $this->monitora_oque = 3;
        $this->monitora_onde = 149;
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

    function AlterarSituacao($situacao) {
    if($situacao <> "S" && $situacao <> "N"){
       $info['sucesso'] = false;
       $info['situacao'] = $situacao;
       return json_encode($info);
    }

    if ($situacao == 'S') $st = 1;
    else $st = 3;

    $this->sql = "select * from mailings where idemail = ".intval($this->id);
    $linhaAntiga = $this->retornarLinha($this->sql);

    $this->sql = "update mailings set situacao = '".mysql_real_escape_string($st)."' where idemail='".intval($this->id)."'";
    $executa = $this->executaSql($this->sql);

    $this->sql = "select * from mailings where idemail = ".intval($this->id);
    $linhaNova = $this->retornarLinha($this->sql);

    $info = array();
    if($executa){

       $this->monitora_oque = 2;
       $this->monitora_qual = $this->id;
       $this->monitora_dadosantigos = $linhaAntiga;
       $this->monitora_dadosnovos = $linhaNova;
       $this->Monitora();

       $info['sucesso'] = true;
       $info['situacao'] = $linhaNova["situacao"];
    } else {
       $info['sucesso'] = false;
       $info['situacao'] = $situacao;
    }
    return json_encode($info);
}

    function alterarCorpoEmail() {

          $this->sql = "SELECT corpo_email , corpo_sms FROM mailings WHERE idemail = '".$this->id."'";
          $this->linha_antiga = $this->retornarLinha($this->sql);

          $this->sql = "update mailings set corpo_email = '".$this->post["corpo_email"]."' , corpo_sms = '".$this->post["corpo_sms"]."'  where idemail = '".$this->id."'";
          $this->query = $this->executaSql($this->sql);

          $this->sql = "SELECT corpo_email , corpo_sms FROM mailings WHERE idemail = '".$this->id."'";
          $this->linha_nova = $this->retornarLinha($this->sql);

      if($this->query){
          $this->retorno["sucesso"] = true;
          if($this->linha_antiga){
              $this->monitora_oque = 2;
              $this->monitora_dadosantigos = $this->linha_antiga;
              $this->monitora_dadosnovos = $this->linha_nova;
          }else{
              $this->monitora_oque = 1;
          }
          $this->monitora_qual = $this->id;
          $this->Monitora();
      } else {
          $this->retorno["erro"] = true;
          $this->retorno["erros"][] = $this->sql;
          $this->retorno["erros"][] = mysql_error();
      }

  return $this->retorno;
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

    function RetornarCursos($idoferta, $json = true) {
        $this->sql = "SELECT c.idcurso, c.nome FROM ofertas_cursos oc INNER JOIN cursos c ON oc.idcurso = c.idcurso where oc.idoferta = '".$idoferta."' AND oc.ativo = 'S' ";
        $this->ordem_campo = "c.nome";
        $this->groupby = "c.idcurso";
        $this->limite = -1;
        $this->ordem = "ASC";
        $dados = $this->retornarLinhas();

        if ($json) {
            return json_encode($dados);
        }
        else
            return $dados;
    }

    function RetornarTotalEmailsEnviadosMesAtual() {
        $this->sql = 'SELECT COUNT(*) AS total FROM mailings_fila WHERE DATE_FORMAT(data_envio,"%Y%m") = DATE_FORMAT(NOW(),"%Y%m") AND enviado = "S" AND paraemail = "S"';
        return $this->retornarLinha($this->sql);
    }
}
?>
