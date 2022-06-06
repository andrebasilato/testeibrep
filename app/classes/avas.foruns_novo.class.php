<?php
class Foruns extends Ava {
        
  var $idava = null;
  var $idtopico = null;
  var $modulo = null;
  
  function ListarTodasForum() {     
    $foruns = array();
    //$this->campos = "f.*, a.nome as ava";
    
    $this->sql = "SELECT 
                    ".$this->campos.",
                    GROUP_CONCAT(CONCAT(tb.idmatricula, ' - ', tb.nome) ORDER BY tb.nome SEPARATOR ', ' ) AS participantes 
                  FROM
                    avas_foruns f
                    INNER JOIN avas a ON (f.idava = a.idava)
                    LEFT JOIN disciplinas d ON (d.iddisciplina = f.iddisciplina)
                    INNER JOIN 
                   (
                      SELECT aft.idmatricula,aft.idforum,p.nome 
                      FROM avas_foruns_topicos aft LEFT JOIN matriculas m ON (m.idmatricula = aft.idmatricula)
                                                    LEFT JOIN pessoas p ON (p.idpessoa = m.idpessoa)
                      WHERE aft.ativo = 'S' 
                      UNION
                      SELECT aftm.idmatricula,aft.idforum,p.nome 
                      FROM avas_foruns_topicos aft INNER JOIN avas_foruns_topicos_mensagens aftm ON (aft.idtopico = aftm.idtopico)
                                                    LEFT JOIN matriculas m ON (m.idmatricula = aftm.idmatricula)
                                                    LEFT JOIN pessoas p ON (p.idpessoa = m.idpessoa)   
                      WHERE aft.ativo = 'S'
                    ) as tb ON (tb.idforum = f.idforum)
                  WHERE 
                    f.ativo = 'S'";
    
    if($this->modulo != "professor") {
      $this->sql .= " AND a.idava = ".$this->idava;
    } else {
        $this->sql .= " AND a.idava IN (".$_GET['idavas'].")";
    }
    /*if ($_GET['iddisciplina']) {
        $this->sql .= " AND (f.iddisciplina = {$_GET['iddisciplina']} OR f.iddisciplina is null)";
    }*/         
        
    $this->aplicarFiltrosBasicos();
    
    $this->groupby = "f.idforum";
    $foruns = $this->retornarLinhas();
    
    $ordemAux = $this->ordem;
    $ordem_campoAux = $this->ordem_campo;
    $limiteAux = $this->limite;
    $totalAux = $this->total;
    $paginaAux = $this->pagina;
    $paginasAux = $this->paginas;
    
    foreach($foruns as $ind => $forum) {
      $foruns[$ind]["permissoes"] = $forum["permissoes"] = unserialize($forum["permissoes"]);
      
      if($this->modulo == "gestor") {
        $permissaoTopicoOculto = true;
      } else {
        $permissaoTopicoOculto = $forum["permissoes"][$this->modulo."|topicos|5"];
      }
      
      $this->campos = "*";
      $this->ordem = "desc";
      $this->ordem_campo = "ultima_mensagem_data DESC, idtopico";
      $this->limite = "5";
      
      $foruns[$ind]["topicos"] = empty($forum["idforum"])
        ? 0
        : $this->ListarTodasTopico($forum["idforum"], $permissaoTopicoOculto);   
      
      $foruns[$ind]["total_topicos"] = empty($forum["idforum"])
        ? 0
        : $this->RetornarTotalTopicos($forum["idforum"]);
      $foruns[$ind]["total_respostas"] = empty($forum["idforum"])
        ? 0
        : $this->RetornarTotalRespostas($forum["idforum"]);
    }
    
    $this->ordem = $ordemAux;
    $this->ordem_campo = $ordem_campoAux;
    $this->limite = $limiteAux;
    $this->total = $totalAux;
    $this->pagina = $paginaAux;
    $this->paginas = $paginasAux;
    
    return $foruns;
  }

    
  function RetornarForum() {
    $forum = array();
    
    $this->sql = "SELECT 
                    ".$this->campos."
                  FROM
                    avas_foruns f
                    INNER JOIN avas a ON f.idava = a.idava
                    LEFT JOIN disciplinas d ON (d.iddisciplina = f.iddisciplina)
                  WHERE 
                    f.ativo = 'S' AND 
                    f.idforum = ".$this->id;
                    
    $forum = $this->retornarLinha($this->sql);
    
    $this->idava = $forum['idava'];
    
    $forum["permissoes"] = unserialize($forum["permissoes"]);
    $forum["total_topicos"] = $this->RetornarTotalTopicos($forum["idforum"]);
    $forum["total_respostas"] = $this->RetornarTotalRespostas($forum["idforum"]);
    
    return $forum;
    
  }
    
  function CadastrarForum() {
    $this->post["idava"] = $this->idava;
      
    return $this->SalvarDados();    
  }
    
  function ModificarForum() {
    $this->post["idava"] = $this->idava;
    return $this->SalvarDados();    
  }
    
  function RemoverForum() {
    return $this->RemoverDados();   
  }
  
  function ListarTodasTopico($idforum, $verOculto = false){

    $topicos = array();
    //$this->campos = "*";
    $this->sql = "SELECT 
                    ".$this->campos." 
                  FROM
                    avas_foruns_topicos
                  WHERE 
                    ativo = 'S' AND 
                    idforum = ".$idforum;
    if(!$verOculto) {
      $this->sql .= " AND oculto = 'N'";
    }
    
    $this->groupby = "idtopico";
    $topicos = $this->retornarLinhas();
    foreach($topicos as $ind => $topico) {
      //$topicos[$ind]["respostas"] = $this->RetornarTotalRespostasTopico($topico["idtopico"]);
      $topicos[$ind]["criado_por"] = $this->retornarPessoa($topico["idusuario"], $topico["idprofessor"], $topico["idmatricula"]);
      $topicos[$ind]["ultima_resposta_pessoa"] = $this->retornarPessoa($topico["ultima_mensagem_idusuario"], $topico["ultima_mensagem_idprofessor"], $topico["ultima_mensagem_idmatricula"]);
    }
    
    return $topicos;

  }
  
  function ListarTopicosPopulares($idforum = false, $verOculto = false){
    $topicos = array();
    
    $this->campos = "f.idforum, f.nome, t.idtopico, t.data_cad, t.nome, t.idusuario, t.idprofessor, t.idmatricula, count(m.idtopico) as respostas";
    
    $this->sql = "SELECT 
                    ".$this->campos." 
                  FROM
                    avas_foruns_topicos t
                    INNER JOIN avas_foruns f ON (t.idforum = f.idforum AND f.ativo = 'S' AND f.exibir_ava = 'S')
                    INNER JOIN avas_foruns_topicos_mensagens m ON (t.idtopico = m.idtopico)
                  WHERE 
                    t.ativo = 'S' AND 
                    f.idava = ".$this->idava;
    if($idforum) {
      $this->sql .= " AND f.idforum = ".$idforum;
    }
    if(!$verOculto) {
      $this->sql .= " AND t.oculto = 'N'";
    }
    $this->sql .= " GROUP BY t.idtopico";

    $this->ordem = "DESC";
    $this->ordem_campo = "respostas";
    $this->limite = "5";
    $this->groupby = "t.idtopico";
    $topicos = $this->retornarLinhas();
    
    foreach($topicos as $ind => $topico) {
      $topicos[$ind]["criado_por"] = $this->retornarPessoa($topico["idusuario"], $topico["idprofessor"], $topico["idmatricula"]);
    }
    return $topicos;
  }
  
  function ListarTodasMensagens($idtopico, $verOculto = false){

    $respostas = array();
    $this->campos = "*";
    $this->limite = -1;
    $this->sql = "SELECT 
                    ".$this->campos." 
                  FROM
                    avas_foruns_topicos_mensagens
                  WHERE 
                    ativo = 'S' AND 
                    idtopico = ".$idtopico;
    if(!$verOculto) {
      $this->sql .= " AND oculto = 'N'";
    }
    
    $this->ordem = "ASC";
    $this->ordem_campo = "idmensagem";
    $this->groupby = "idmensagem";
    $respostas = $this->retornarLinhas();
    foreach($respostas as $ind => $resposta) {
      $respostas[$ind]["criado_por"] = $this->retornarPessoa($resposta["idusuario"], $resposta["idprofessor"], $resposta["idmatricula"]);
      if($resposta["idmensagem_associada"]) {
        $mensagemAssociada = $this->RetornarMensagem($resposta["idmensagem_associada"]);
        $mensagemAssociada["criado_por"] = $this->retornarPessoa($mensagemAssociada["idusuario"], $mensagemAssociada["idprofessor"], $mensagemAssociada["idmatricula"]);
        $respostas[$ind]["associada"] = $mensagemAssociada;
      }
    }
    
    return $respostas;

  }
  
  function retornarPessoa($idusuario, $idprofessor, $idmatricula) {
    $retorno = array();
    
    if($idusuario) {
      $this->sql = "SELECT 
                      nome,
                      'gestor' AS tipo,
                      avatar_servidor AS avatar,
                      email
                    FROM
                      usuarios_adm 
                    WHERE 
                      idusuario = ".$idusuario;
      $retorno = $this->retornarLinha($this->sql);
    } elseif($idprofessor) {
      $this->sql = "SELECT 
                      nome,
                      'professor' AS tipo,
                      avatar_servidor AS avatar,
                      email
                    FROM
                      professores
                    WHERE 
                      idprofessor = ".$idprofessor;
      $retorno = $this->retornarLinha($this->sql);
    } elseif($idmatricula) {
      $this->sql = "SELECT 
                      p.nome,
                      'aluno' AS tipo,
                      avatar_servidor AS avatar,
                      email
                    FROM
                      pessoas p 
                      INNER JOIN matriculas m ON (p.idpessoa = m.idpessoa)
                    WHERE 
                      m.idmatricula = ".$idmatricula;
      $retorno = $this->retornarLinha($this->sql);
    }
    
    return $retorno; 
     
  }
  
  function ListarAlunosAtivos($idforum = false){

    $this->campos = "tm.idmensagem, tm.idmatricula, p.nome, p.avatar_servidor, count(tm.idmensagem) as mensagens";
    
    $this->sql = "SELECT 
                    ".$this->campos." 
                  FROM
                    avas_foruns_topicos_mensagens tm
                    INNER JOIN avas_foruns_topicos t ON (tm.idtopico = t.idtopico)
                    INNER JOIN avas_foruns f ON (t.idforum = f.idforum)
                    INNER JOIN matriculas m ON (tm.idmatricula = m.idmatricula)
                    INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
                  WHERE 
                    tm.ativo = 'S' AND 
                    f.idava = ".$this->idava;
                  
    if($idforum) {
      $this->sql .= " AND f.idforum = ".$idforum;
    }
    $this->sql .= " GROUP BY p.idpessoa";
    
    $this->ordem = "DESC";
    $this->ordem_campo = "mensagens";
    $this->limite = "5";
    $this->groupby = "tm.idmensagem";
    return $this->retornarLinhas();
    
  }
  
  function ParticipantesTopico($idtopico) {   
    $this->campos = "tm.idmensagem, tm.idmatricula, p.nome, p.avatar_servidor, count(tm.idmensagem) as mensagens";
    
    $this->sql = "SELECT 
                    ".$this->campos." 
                  FROM
                    avas_foruns_topicos_mensagens tm
                    INNER JOIN avas_foruns_topicos t ON (tm.idtopico = t.idtopico)
                    INNER JOIN matriculas m ON (tm.idmatricula = m.idmatricula)
                    INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
                  WHERE 
                    tm.ativo = 'S' and 
                    t.idtopico = ".$idtopico."
                  GROUP BY p.idpessoa";

    $this->ordem = "desc";
    $this->ordem_campo = "mensagens";
    $this->limite = "-1";
    $this->groupby = "tm.idmensagem";
    return $this->retornarLinhas();
        
  }
  
  function RetornarTotalTopicos($idforum) {
    $this->sql = "SELECT 
                    count(idtopico) AS total
                  FROM
                    avas_foruns_topicos
                  WHERE 
                    idforum = ".$idforum;
    $total = $this->retornarLinha($this->sql);
    return $total["total"];
  }
  
  function RetornarTotalRespostas($idforum) {
    $this->sql = "SELECT 
                    count(m.idmensagem) AS total
                  FROM
                    avas_foruns_topicos_mensagens m
                    INNER JOIN avas_foruns_topicos t ON (m.idtopico = t.idtopico)
                  WHERE 
                    t.idforum = ".$idforum;
    $total = $this->retornarLinha($this->sql);
    return $total["total"];
  }
    
  function RetornarTopico($idtopico) {  
    $topico = array();
    
    if (verificaPermissaoAcesso(false)) {
        if($this->modulo == 'aluno') {
          $this->sql = "UPDATE avas_foruns_topicos SET visualizacoes = visualizacoes + 1 WHERE idtopico = ".$idtopico;
          $this->executaSql($this->sql);
        }
    }
    
    $this->sql = "SELECT 
                    *
                  FROM
                    avas_foruns_topicos
                  WHERE 
                    idtopico = ".$idtopico;
    $topico = $this->retornarLinha($this->sql);
    
    if($topico["moderado"] == "S"){
      $topico["moderado_por"] = $this->retornarPessoa($topico["moderado_idusuario"], $topico["moderado_idprofessor"], $topico["moderado_idmatricula"]);
    }
    
    //$topico["respostas"] = $this->RetornarTotalRespostasTopico($topico["idtopico"]);
    $topico["criado_por"] = $this->retornarPessoa($topico["idusuario"], $topico["idprofessor"], $topico["idmatricula"]);
    $topico["ultima_resposta_pessoa"] = $this->retornarPessoa($topico["ultima_mensagem_idusuario"], $topico["ultima_mensagem_idprofessor"], $topico["ultima_mensagem_idmatricula"]);
    
    return $topico;
  }
  
  function countabilizarDownloadTopico($idtopico) { 
    if (verificaPermissaoAcesso(false)) {
        $this->sql = "UPDATE avas_foruns_topicos SET arquivo_downloads = arquivo_downloads + 1 WHERE idtopico = ".$idtopico;
        return $this->executaSql($this->sql);
    }
  }
  
  function countabilizarDownloadMensagem($idmensagem) { 
    if (verificaPermissaoAcesso(false)) {
        $this->sql = "UPDATE avas_foruns_topicos_mensagens SET arquivo_downloads = arquivo_downloads + 1 WHERE idmensagem = ".$idmensagem;
        return $this->executaSql($this->sql);
    }
  }
  
  function RetornarMensagem($idmensagem) {
    $mensagem = array();
    
    $this->sql = "SELECT 
                    ".$this->campos."
                  FROM
                    avas_foruns_topicos_mensagens
                  WHERE 
                    ativo = 'S' AND 
                    idmensagem = ".$idmensagem;
    return $this->retornarLinha($this->sql);
    
  }
  
  function verificaAssinaturaTopico($idtopico,$idusuario,$idprofessor,$idmatricula){
      
    if($idusuario) {
      $this->sql = "SELECT * FROM avas_foruns_topicos_assinantes WHERE ativo = 'S' and idtopico = ".intval($idtopico)." and idusuario = ".intval($idusuario);
    } elseif($idprofessor) {
      $this->sql = "SELECT * FROM avas_foruns_topicos_assinantes WHERE ativo = 'S' and idtopico = ".intval($idtopico)." and idprofessor = ".intval($idprofessor);
    } elseif($idmatricula) {
      $this->sql = "SELECT * FROM avas_foruns_topicos_assinantes WHERE ativo = 'S' and idtopico = ".intval($idtopico)." and idmatricula = ".intval($idmatricula);
    }
    
    $verifica = $this->retornarLinha($this->sql);
    if($verifica["idassinatura"]){
      return $verifica["idassinatura"];       
    } else {
      return false;       
    }
    
  }
    
  function CadastrarTopico() {
    
    if (verificaPermissaoAcesso(true)) {
        $erros = array();
        
        if(!$this->post["idforum"]) {
          $erros[] = "idforum_vazio";
        }
        if(!$this->post["nome"]) {
          $erros[] = "nome_vazio";
        }
        if(!$this->post["mensagem"]) {
          $erros[] = "mensagem_vazio";
        }
        
        if($_FILES["arquivo"]["name"]) {
          if($this->ValidarArquivo($_FILES["arquivo"])) {
            $erros[] = "arquivo_invalido";
          } else {
            $extensao = strtolower(strrchr($_FILES["arquivo"]["name"], "."));
            $arquivoServidor = date("YmdHis")."_".uniqid().$extensao;
            if(!move_uploaded_file($_FILES["arquivo"]["tmp_name"],$_SERVER["DOCUMENT_ROOT"]."/storage/avas_foruns_topicos_arquivo/".$arquivoServidor)) {
              $erros[] = "arquivo_falhou";
            }
          }
        }
        
        if(count($erros) <= 0) {      
          $this->sql = "INSERT INTO 
                          avas_foruns_topicos
                        SET 
                          idforum = ".$this->post["idforum"].",
                          data_cad = now(),
                          nome = '".$this->post["nome"]."',
                          mensagem = '".$this->post["mensagem"]."'";
          
          if($this->modulo == "gestor"){ 
             $this->sql .= ", idusuario = '".$this->idusuario."'";
          } elseif($this->modulo == "aluno"){        
             $this->sql .= ", idmatricula = '".$this->idmatricula."'";
          } elseif($this->modulo == "professor") {
            $this->sql .= ", idprofessor = '".$this->idprofessor."'";
          }
          
          if($_FILES["arquivo"]["name"]){ 
             $this->sql .= ", arquivo_nome = '".$_FILES["arquivo"]["name"]."',
                           arquivo_servidor = '".$arquivoServidor."',
                           arquivo_tipo = '".$_FILES["arquivo"]["type"]."',
                           arquivo_tamanho = ".$_FILES["arquivo"]["size"];
          }
          
          if($this->executaSql($this->sql)){        
            $this->monitora_oque = 1;
            $this->monitora_onde = "153";
            $this->monitora_qual = mysql_insert_id();
            $this->Monitora();
            $this->retorno["sucesso"] = true;
            $this->retorno["id"] = $this->monitora_qual;
          } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
          }
        } else {
          $this->retorno["sucesso"] = false;
          $this->retorno["erros"] = $erros;     
        }
        
        return $this->retorno;
    }
  }
  
  function ResponderTopico($idtopico) {
    if (verificaPermissaoAcesso(true)) {
        $erros = array();
        
        if(!$idtopico) {
          $erros[] = "idtopico_vazio";
        }
        if(!$this->post["mensagem"]) {
          $erros[] = "mensagem_vazio";
        }
        if($_FILES["arquivo"]["name"]) {
          if($this->ValidarArquivo($_FILES["arquivo"])) {
            $erros[] = "arquivo_invalido";
          } else {
            $extensao = strtolower(strrchr($_FILES["arquivo"]["name"], "."));
            $arquivoServidor = date("YmdHis")."_".uniqid().$extensao;
            if(!move_uploaded_file($_FILES["arquivo"]["tmp_name"],$_SERVER["DOCUMENT_ROOT"]."/storage/avas_foruns_topicos_mensagens_arquivo/".$arquivoServidor)) {
              $erros[] = "arquivo_falhou";
            }
          }
        }
        
        if(count($erros) <= 0) {      
          $this->sql = "INSERT INTO 
                          avas_foruns_topicos_mensagens
                        SET 
                          data_cad = now(), 
                          idtopico = ".$idtopico.",
                          mensagem = '".$this->post["mensagem"]."'";
          if($this->post["idmensagem_associada"]){ 
             $this->sql .= ", idmensagem_associada = ".$this->post["idmensagem_associada"];
          }
          if($this->modulo == "gestor") { 
             $this->sql .= ", idusuario = '".$this->idusuario."'";
          } elseif($this->modulo == "professor") {
            $this->sql .= ", idprofessor = '".$this->idprofessor."'";
          } else {       
             $this->sql .= ", idmatricula = '".$this->idmatricula."'";
          }
          if($_FILES["arquivo"]["name"]){ 
             $this->sql .= ", arquivo_nome = '".$_FILES["arquivo"]["name"]."',
                           arquivo_servidor = '".$arquivoServidor."',
                           arquivo_tipo = '".$_FILES["arquivo"]["type"]."',
                           arquivo_tamanho = ".$_FILES["arquivo"]["size"];
          }
      
          if($this->executaSql($this->sql)){
            $this->monitora_oque = 1;
            $this->monitora_onde = "154";
            $this->monitora_qual = mysql_INSERT_id();
            $this->Monitora();

            $informacoesEmail['idmensagem'] = $this->monitora_qual;

            $this->sql = "UPDATE avas_foruns_topicos SET total_mensagens = total_mensagens + 1, ultima_mensagem_data = now()";
            if($this->modulo == "gestor") { 
                $this->sql .= ", ultima_mensagem_idusuario = ".$this->idusuario."";
                $informacoesEmail['idusuario'] = $this->idusuario;
            } elseif($this->modulo == "professor") {
                $this->sql .= ", ultima_mensagem_idprofessor = '".$this->idprofessor."'";
                $informacoesEmail['idprofessor'] = $this->idprofessor;
            } else { 
                $this->sql .= ", ultima_mensagem_idmatricula = '".$this->idmatricula."'";
                $informacoesEmail['idmatricula'] = $this->idmatricula;
            }
            $this->sql .= " WHERE idtopico = ".intval($idtopico);
            $this->executaSql($this->sql);
            $informacoesEmail['idtopico'] = $idtopico;
            $this->retorno["sucesso"] = true;
            $this->EnviarEmailAtividade('topicorespondido', $informacoesEmail, true);
            
            /*$this->sql = "INSERT INTO mensagens_alerta(tipo_alerta, idtopico, idmatricula)
              SELECT DISTINCT 'forum', $idtopico, m.idmatricula FROM matriculas m 
              INNER JOIN ofertas_curriculos_avas oca ON (m.idoferta = oca.idoferta AND m.ativo = 'S') 
              INNER JOIN avas_foruns av ON (av.idava = oca.idava)
              INNER JOIN avas_foruns_topicos aft ON (aft.idforum = av.idforum) WHERE aft.idtopico = ".$idtopico." AND m.idmatricula <> ".$this->idmatricula;

              $this->executaSql($this->sql);*/
            /* INICIO ASSOCIA AO TOPICO PARA ENVIO DE EMAIL */
            //$this->InsereAssinantesMensagens($idtopico);
            /* FIM ASSOCIA AO TOPICO PARA O ENVIO DE EMAIL */
            
          } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
          }
        } else {
          $this->retorno["sucesso"] = false;
          $this->retorno["erros"] = $erros;     
        }

        return $this->retorno;
    }
  }


    private function enviarEmailAtividade($tipoAtividade, $informacoesEnvio, $enviarProfessor = null, $enviarAluno = null)
    {
        if ($tipoAtividade == 'topicorespondido') {

            $emailDe = $GLOBALS['config']['emailSistema'];
            $nomeDe = utf8_decode($GLOBALS['config']['tituloEmpresa']);                 
            
            $this->campos = "f.*";
            $forum = $this->RetornarForum();

            $this->campos = "nome";
            $topico = $this->RetornarTopico($informacoesEnvio['idtopico']);

            $assunto = 'Atividade No Tópico: '. $topico['nome'];

            $this->campos = "*"; 
            $mensagemTopico = $this->RetornarMensagem($informacoesEnvio['idmensagem']);

            if ($mensagemTopico['idusuario']) {
                $pessoa = $this->retornarPessoa($mensagemTopico['idusuario'], null, null);
            } elseif ($mensagemTopico['idprofessor']) {
                $pessoa = $this->retornarPessoa(null, $mensagemTopico['idprofessor'], null);
            } elseif ($mensagemTopico['idmatricula']) {
                $pessoa = $this->retornarPessoa(null, null, $mensagemTopico['idmatricula']);
            }

            if ($enviarProfessor && $topico['idprofessor'] && $topico['idprofessor'] != $mensagemTopico['idprofessor']) {
                $professor = $this->retornarPessoa(null, $topico['idprofessor'], null);
                $nomePara = utf8_decode($professor['nome']);            
                $emailPara = $professor['email'];

                $mensagem = 'Professor, <br/> o t&oacute;pico <strong>' .utf8_decode($topico['nome']). '</strong>, foi respondido por um <strong>' .$pessoa['tipo']. '</strong> de nome <strong>'.utf8_decode($pessoa['nome']).'</strong>.';
                $mensagem .= '<br/><br/><a href="'.$GLOBALS['config']["urlSistema"].'/professor/academico/foruns/'.$this->id.'/topicos/'.$informacoesEnvio['idtopico'].'/mensagens">Clique aqui para abrir o t&oacute;pico <strong>'.utf8_decode($lnTopicoForum["topico"]).'</strong>, f&oacute;rum <strong>'.utf8_decode($lnTopicoForum["forum"]).'.<a>';      
            }
            $this->EnviarEmail($nomeDe, $emailDe, $assunto, $mensagem, $nomePara, $emailPara);
        }
        
    }
  
  function ModerarTopico($idtopico) {
    if (verificaPermissaoAcesso(true)) {
        $erros = array();
        
        if(!$this->post["moderar"]) {
          $erros[] = "mensagem_vazio";
        }
        
        if(count($erros) <= 0) {
          $this->sql = "SELECT * FROM avas_foruns_topicos WHERE idtopico = ".intval($idtopico);
          $linhaAntiga = $this->retornarLinha($this->sql);
          
          $this->sql = "UPDATE 
                          avas_foruns_topicos
                        SET 
                          moderado = 'S',
                          moderado_quando = now(),
                          moderado_mensagem = '".$this->post["moderar"]."'";
          if($this->modulo == "gestor"){ 
             $this->sql .= ", moderado_idusuario = '".$this->idusuario."'";
          } elseif($this->modulo == "aluno"){ 
             $this->sql .= ", moderado_idmatricula = '".$this->idmatricula."'";
          } elseif($this->modulo == "professor") {
            $this->sql .= ", moderado_idprofessor = '".$this->idprofessor."'";
          }
          $this->sql .= " WHERE idtopico = ".intval($idtopico);
      
          if($this->executaSql($this->sql)){
            
            $this->sql = "SELECT * FROM avas_foruns_topicos WHERE idtopico = ".intval($idtopico);
            $linhaNova = $this->retornarLinha($this->sql);
            
            $this->monitora_oque = 2;
            $this->monitora_onde = "153";
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->monitora_qual = intval($idtopico);
            $this->Monitora();
            $this->retorno["sucesso"] = true;
          } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
          }
        } else {
          $this->retorno["sucesso"] = false;
          $this->retorno["erros"] = $erros;     
        }

        return $this->retorno;
    }
  }
  
  function ModerarMensagem($idmensagem) {
    if (verificaPermissaoAcesso(true)) {
        $erros = array();
        
        if(!$this->post["moderar"]) {
          $erros[] = "mensagem_vazio";
        }
        
        if(count($erros) <= 0) {      
          
          $this->sql = "SELECT * FROM avas_foruns_topicos_mensagens WHERE idmensagem = ".intval($idmensagem);
          $linhaAntiga = $this->retornarLinha($this->sql);
          
          $this->sql = "UPDATE 
                          avas_foruns_topicos_mensagens
                        SET 
                          moderado = 'S',
                          moderado_quando = now(),
                          moderado_mensagem = '".$this->post["moderar"]."'";
          if($this->modulo == "gestor"){ 
            $this->sql .= ", moderado_idusuario = '".$this->idusuario."'";
          } elseif($this->modulo == "aluno"){ 
            $this->sql .= ", moderado_idmatricula = '".$this->idmatricula."'";
          } elseif($this->modulo == "professor") {
            $this->sql .= ", moderado_idprofessor = '".$this->idprofessor."'";
          }
          $this->sql .= " WHERE idmensagem = ".intval($idmensagem);
      
          if($this->executaSql($this->sql)){
            
            $this->sql = "SELECT * FROM avas_foruns_topicos_mensagens WHERE idmensagem = ".intval($idmensagem);
            $linhaNova = $this->retornarLinha($this->sql);
            
            $this->monitora_oque = 2;
            $this->monitora_onde = "154";
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->monitora_qual = intval($idmensagem);
            $this->Monitora();
            $this->retorno["sucesso"] = true;
          } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
          }
        } else {
          $this->retorno["sucesso"] = false;
          $this->retorno["erros"] = $erros;     
        }

        return $this->retorno;
    }
  }
  
  function ocultarTopico($idtopico) {
    if (verificaPermissaoAcesso(true)) {
        $this->sql = "SELECT * FROM avas_foruns_topicos WHERE idtopico = ".intval($idtopico);
        $linhaAntiga = $this->retornarLinha($this->sql);
          
        $this->sql = "UPDATE 
                        avas_foruns_topicos
                      SET 
                        oculto = 'S',
                        oculto_quando = now()";
        if($this->modulo == "gestor"){ 
           $this->sql .= ", oculto_idusuario = '".$this->idusuario."'";
        } elseif($this->modulo == "aluno"){ 
          $this->sql .= ", oculto_idmatricula = '".$this->idmatricula."'";
        } elseif($this->modulo == "professor") {
          $this->sql .= ", oculto_idprofessor = '".$this->idprofessor."'";
        }
        $this->sql .= " WHERE idtopico = ".intval($idtopico);
      
        if($this->executaSql($this->sql)){
          $this->sql = "SELECT * FROM avas_foruns_topicos WHERE idtopico = ".intval($idtopico);
          $linhaNova = $this->retornarLinha($this->sql);
          
          $this->monitora_oque = 2;
          $this->monitora_onde = "153";
          $this->monitora_dadosantigos = $linhaAntiga;
          $this->monitora_dadosnovos = $linhaNova;
          $this->monitora_qual = intval($idtopico);
          $this->Monitora();
          $this->retorno["sucesso"] = true;
        } else {
          $this->retorno["erro"] = true;
          $this->retorno["erros"][] = $this->sql;
          $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }   
  }
  
  function desocultarTopico($idtopico) {
    if (verificaPermissaoAcesso(true)) {
        $this->sql = "SELECT * FROM avas_foruns_topicos WHERE idtopico = ".intval($idtopico);
        $linhaAntiga = $this->retornarLinha($this->sql);
        
        $this->sql = "UPDATE 
                        avas_foruns_topicos
                      SET 
                        oculto = 'N',
                        oculto_quando = null, 
                        oculto_idusuario = null, 
                        oculto_idprofessor = null, 
                        oculto_idmatricula = null
                      WHERE 
                        idtopico = ".intval($idtopico);
        if($this->executaSql($this->sql)){
          $this->sql = "SELECT * FROM avas_foruns_topicos WHERE idtopico = ".intval($idtopico);
          $linhaNova = $this->retornarLinha($this->sql);
          
          $this->monitora_oque = 2;
          $this->monitora_onde = "153";
          $this->monitora_dadosantigos = $linhaAntiga;
          $this->monitora_dadosnovos = $linhaNova;
          $this->monitora_qual = intval($idtopico);
          $this->Monitora();
          $this->retorno["sucesso"] = true;
        } else {
          $this->retorno["erro"] = true;
          $this->retorno["erros"][] = $this->sql;
          $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }
  }
  
  function ocultarMensagem($idmensagem) {
    if (verificaPermissaoAcesso(true)) {
        $this->sql = "SELECT * FROM avas_foruns_topicos_mensagens WHERE idmensagem = ".intval($idmensagem);
        $linhaAntiga = $this->retornarLinha($this->sql);
        
        $this->sql = "UPDATE 
                        avas_foruns_topicos_mensagens
                      SET 
                        oculto = 'S',
                        oculto_quando = now()";
        if($this->modulo == "gestor"){ 
           $this->sql .= ", oculto_idusuario = '".$this->idusuario."'";
        } elseif($this->modulo == "aluno"){ 
          $this->sql .= ", oculto_idmatricula = '".$this->idmatricula."'";
        } elseif($this->modulo == "professor") {
          $this->sql .= ", oculto_idprofessor = '".$this->idprofessor."'";
        }
        $this->sql .= " WHERE idmensagem = ".intval($idmensagem);
      
        if($this->executaSql($this->sql)){
          $this->sql = "SELECT * FROM avas_foruns_topicos_mensagens WHERE idmensagem = ".intval($idtopico);
          $linhaNova = $this->retornarLinha($this->sql);
          
          $this->monitora_oque = 2;
          $this->monitora_onde = "154";
          $this->monitora_dadosantigos = $linhaAntiga;
          $this->monitora_dadosnovos = $linhaNova;
          $this->monitora_qual = intval($idtopico);
          $this->Monitora();
          $this->retorno["sucesso"] = true;
        } else {
          $this->retorno["erro"] = true;
          $this->retorno["erros"][] = $this->sql;
          $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }
  }
  
  function desocultarMensagem($idmensagem)
  {
    if (verificaPermissaoAcesso(true)) {
        $this->sql = "SELECT * FROM avas_foruns_topicos_mensagens WHERE idmensagem = ".intval($idmensagem);
        $linhaAntiga = $this->retornarLinha($this->sql);
        
        $this->sql = "UPDATE 
                        avas_foruns_topicos_mensagens
                      SET 
                        oculto = 'N',
                        oculto_quando = null, 
                        oculto_idusuario = null, 
                        oculto_idprofessor = null, 
                        oculto_idmatricula = null
                      WHERE 
                        idmensagem = ".intval($idmensagem);
        if($this->executaSql($this->sql)){
          $this->sql = "SELECT * FROM avas_foruns_topicos_mensagens WHERE idmensagem = ".intval($idmensagem);
          $linhaNova = $this->retornarLinha($this->sql);
          
          $this->monitora_oque = 2;
          $this->monitora_onde = "154";
          $this->monitora_dadosantigos = $linhaAntiga;
          $this->monitora_dadosnovos = $linhaNova;
          $this->monitora_qual = intval($idmensagem);
          $this->Monitora();
          $this->retorno["sucesso"] = true;
        } else {
          $this->retorno["erro"] = true;
          $this->retorno["erros"][] = $this->sql;
          $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }
  }
  
  function bloquearTopico($idtopico)
  {
    if (verificaPermissaoAcesso(true)) {
        $this->sql = "SELECT * FROM avas_foruns_topicos WHERE idtopico = ".intval($idtopico);
        $linhaAntiga = $this->retornarLinha($this->sql);
        
        $this->sql = "UPDATE 
                        avas_foruns_topicos
                      SET 
                        bloqueado = 'bloqueado',
                        bloqueado_quando = now()";
        if($this->modulo == "gestor"){ 
           $this->sql .= ", bloqueado_idusuario = '".$this->idusuario."'";
        } elseif($this->modulo == "aluno"){ 
          $this->sql .= ", bloqueado_idmatricula = '".$this->idmatricula."'";
        } elseif($this->modulo == "professor") {
          $this->sql .= ", bloqueado_idprofessor = '".$this->idprofessor."'";
        }
        $this->sql .= " WHERE idtopico = ".intval($idtopico);
      
        if($this->executaSql($this->sql)){
          $this->sql = "SELECT * FROM avas_foruns_topicos WHERE idtopico = ".intval($idtopico);
          $linhaNova = $this->retornarLinha($this->sql);
          
          $this->monitora_oque = 2;
          $this->monitora_onde = "153";
          $this->monitora_dadosantigos = $linhaAntiga;
          $this->monitora_dadosnovos = $linhaNova;
          $this->monitora_qual = intval($idtopico);
          $this->Monitora();
          $this->retorno["sucesso"] = true;
        } else {
          $this->retorno["erro"] = true;
          $this->retorno["erros"][] = $this->sql;
          $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }
  }
  
  function desbloquearTopico($idtopico)
  {
    if (verificaPermissaoAcesso(true)) {
        $this->sql = "SELECT * FROM avas_foruns_topicos WHERE idtopico = ".intval($idtopico);
        $linhaAntiga = $this->retornarLinha($this->sql);
        
        $this->sql = "UPDATE 
                        avas_foruns_topicos
                      SET 
                        bloqueado = 'desbloqueado',
                        bloqueado_quando = null, 
                        bloqueado_idusuario = null,
                        bloqueado_idprofessor = null,
                        bloqueado_idmatricula = null
                      WHERE 
                        idtopico = ".intval($idtopico);
      
        if($this->executaSql($this->sql)){
          $this->sql = "SELECT * FROM avas_foruns_topicos WHERE idtopico = ".intval($idtopico);
          $linhaNova = $this->retornarLinha($this->sql);
          
          $this->monitora_oque = 2;
          $this->monitora_onde = "153";
          $this->monitora_dadosantigos = $linhaAntiga;
          $this->monitora_dadosnovos = $linhaNova;
          $this->monitora_qual = intval($idtopico);
          $this->Monitora();
          $this->retorno["sucesso"] = true;
        } else {
          $this->retorno["erro"] = true;
          $this->retorno["erros"][] = $this->sql;
          $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }
  }
  
  function assinarTopico($idtopico)
  {
    if (verificaPermissaoAcesso(true)) {
        $this->sql = "SELECT idassinatura FROM avas_foruns_topicos_assinantes WHERE idtopico = ".intval($idtopico);
        if($this->modulo == "gestor"){ 
           $this->sql .= " and idusuario = ".$this->idusuario;
        } elseif($this->modulo == "aluno"){ 
          $this->sql .= " and idmatricula = ".$this->idmatricula;
        } elseif($this->modulo == "professor") {
          $this->sql .= " and idprofessor = '".$this->idprofessor."'";
        }
        $verifica = $this->retornarLinha($this->sql);
        
        if($verifica["idassinatura"]) {
          $this->sql = "UPDATE  
                          avas_foruns_topicos_assinantes
                        SET 
                          ativo = 'S'
                        WHERE
                          idassinatura = ".$verifica["idassinatura"];
          $this->monitora_qual = $verifica["idassinatura"];
        } else {
          $this->sql = "INSERT INTO 
                          avas_foruns_topicos_assinantes
                        SET 
                          data_cad = now(),
                          idtopico = ".intval($idtopico);
          if($this->modulo == "gestor"){ 
             $this->sql .= ", idusuario = '".$this->idusuario."'";
          } elseif($this->modulo == "aluno"){ 
            $this->sql .= ", idmatricula = '".$this->idmatricula."'";
          } elseif($this->modulo == "professor") {
            $this->sql .= ", idprofessor = '".$this->idprofessor."'";
          }
          $this->monitora_qual = mysql_INSERT_id();
        }
        
        if($this->executaSql($this->sql)){
          
          $this->monitora_oque = 1;
          $this->monitora_onde = "155";
          $this->Monitora();
          $this->retorno["sucesso"] = true;
        } else {
          $this->retorno["erro"] = true;
          $this->retorno["erros"][] = $this->sql;
          $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }   
  }
  
  function desassinarTopico($idassinatura)
  {
    if (verificaPermissaoAcesso(true)) {
        $this->sql = "UPDATE  
                        avas_foruns_topicos_assinantes
                      SET 
                        ativo = 'N'
                      WHERE
                        idassinatura = ".intval($idassinatura);
        if($this->executaSql($this->sql)){
          
          $this->monitora_oque = 3;
          $this->monitora_onde = "155";
          $this->monitora_qual = intval($idassinatura);
          $this->Monitora();
          $this->retorno["sucesso"] = true;
        } else {
          $this->retorno["erro"] = true;
          $this->retorno["erros"][] = $this->sql;
          $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }
  }
  
  function CurtirTopicoMensagem()
  {
    if (verificaPermissaoAcesso(false)) {
        $jaVotou = $this->VerificaVotoTopicoMensagem();
        
        $topico = array();
        
        if(!$jaVotou) {
          if($this->post["tipo"] == "curtir_topico"){
            $tabela = "avas_foruns_topicos";
            $campo = "total_curtiu";
            $id = "idtopico";
            $tipo = "curtiu";
            $icone = "icon-thumbs-up-alt";
          } elseif($this->post["tipo"] == "nao_curtir_topico") {
            $tabela = "avas_foruns_topicos";
            $campo = "total_nao_curtiu";
            $id = "idtopico";
            $tipo = "nao_curtiu";
            $icone = "icon-thumbs-down-alt";
          } elseif($this->post["tipo"] == "curtir_mensagem") {
            $tabela = "avas_foruns_topicos_mensagens";
            $campo = "total_curtiu";
            $id = "idmensagem";
            $tipo = "curtiu";
            $icone = "icon-thumbs-up-alt";
          } elseif($this->post["tipo"] == "nao_curtir_mensagem") {
            $tabela = "avas_foruns_topicos_mensagens";
            $campo = "total_nao_curtiu";
            $id = "idmensagem";
            $tipo = "nao_curtiu";
            $icone = "icon-thumbs-down-alt";
          }
          
          $this->sql = "UPDATE ".$tabela." SET ".$campo." = ".$campo." + 1 WHERE ".$id." = ".$this->post["id"];
          $this->executaSql($this->sql);
        
          $this->sql = "SELECT * FROM ".$tabela." WHERE ".$id." = ".$this->post["id"];
          $topico = $this->retornarLinha($this->sql);
          
          if($this->modulo == "gestor") { 
            $this->sql = "INSERT INTO avas_foruns_topicos_curtidas SET data_cad = now(), ip = inet_aton('".$_SERVER['REMOTE_ADDR']."'), idusuario = ".$this->idusuario;
          } elseif($this->modulo == "professor") { 
            $this->sql = "INSERT INTO avas_foruns_topicos_curtidas SET data_cad = now(), ip = inet_aton('".$_SERVER['REMOTE_ADDR']."'), idprofessor = ".$this->idprofessor;
          } else { 
            $this->sql = "INSERT INTO avas_foruns_topicos_curtidas SET data_cad = now(), ip = inet_aton('".$_SERVER['REMOTE_ADDR']."'), idmatricula = ".$this->idmatricula;
          }
          $this->sql .= ", tipo = '".$tipo."', ".$id." = ".$this->post["id"];   
          $this->executaSql($this->sql);      
          
          $topico["mensagem"] = "voto_computado_sucesso";
          $topico["icone"] = $icone;
          $topico["contador"] = $topico[$campo];
          $topico["background"] = '#638db4';
        } else {
          if($this->post["tipo"] == "curtir_topico"){
            $tabela = "avas_foruns_topicos";
            $campo = "total_curtiu";
            $id = "idtopico";
            $tipo = "curtiu";
            $icone = "icon-thumbs-up-alt";
          } elseif($this->post["tipo"] == "nao_curtir_topico") {
            $tabela = "avas_foruns_topicos";
            $campo = "total_nao_curtiu";
            $id = "idtopico";
            $tipo = "nao_curtiu";
            $icone = "icon-thumbs-down-alt";
          } elseif($this->post["tipo"] == "curtir_mensagem") {
            $tabela = "avas_foruns_topicos_mensagens";
            $campo = "total_curtiu";
            $id = "idmensagem";
            $tipo = "curtiu";
            $icone = "icon-thumbs-up-alt";
          } elseif($this->post["tipo"] == "nao_curtir_mensagem") {
            $tabela = "avas_foruns_topicos_mensagens";
            $campo = "total_nao_curtiu";
            $id = "idmensagem";
            $tipo = "nao_curtiu";
            $icone = "icon-thumbs-down-alt";
          }

          $verifica = $this->VerificaQualTipoVotoTopicoMensagem($this->post["tipo"], $this->post["id"]);
          if($verifica['tipo'] == $tipo) {
            
            $this->sql = "UPDATE ".$tabela." SET ".$campo." = ".$campo." - 1 WHERE ".$id." = ".$this->post["id"];
            $this->executaSql($this->sql);
          
            $this->sql = "SELECT * FROM ".$tabela." WHERE ".$id." = ".$this->post["id"];
            $topico = $this->retornarLinha($this->sql);
            
            if($this->modulo == "gestor") { 
              $this->sql = "DELETE FROM avas_foruns_topicos_curtidas WHERE idusuario = ".$this->idusuario;
            } elseif($this->modulo == "professor") { 
              $this->sql = "DELETE FROM avas_foruns_topicos_curtidas WHERE idprofessor = ".$this->idprofessor;
            } else { 
              $this->sql = "DELETE FROM avas_foruns_topicos_curtidas WHERE idmatricula = ".$this->idmatricula;
            }
            $this->sql .= " AND tipo = '".$tipo."' AND ".$id." = ".$this->post["id"];   
            $this->executaSql($this->sql);      
            
            $topico["mensagem"] = "voto_cancelado_sucesso";
            $topico["icone"] = $icone;
            $topico["contador"] = $topico[$campo];
            $topico["background"] = '#7fa5c8';
          } else {
            $topico["mensagem"] = "ja_votou";
          }
        }
        
        echo json_encode($topico);
    } else {
        $topico['erro_json'] = "sem_permissao";
        return json_encode($topico);
    }
  }
  
  function VerificaVotoTopicoMensagem() {
    if($this->modulo == "gestor") { 
      $this->sql = "SELECT count(*) as total FROM avas_foruns_topicos_curtidas WHERE idusuario = ".$this->idusuario;
    } elseif($this->modulo == "professor") { 
      $this->sql = "SELECT count(*) as total FROM avas_foruns_topicos_curtidas WHERE idprofessor = ".$this->idprofessor;
    } else { 
      $this->sql = "SELECT count(*) as total FROM avas_foruns_topicos_curtidas WHERE idmatricula = ".$this->idmatricula;
    }
    
    if($this->post["tipo"] == "curtir_topico" || $this->post["tipo"] == "nao_curtir_topico") { 
      $this->sql .= " and idtopico = ".$this->post["id"];
    } elseif($this->post["tipo"] == "curtir_mensagem" || $this->post["tipo"] == "nao_curtir_mensagem") {
      $this->sql .= " and idmensagem = ".$this->post["id"];
    }
    
    $verifica = $this->retornarLinha($this->sql);
    
    if($verifica["total"] > 0) {
      return true;        
    } else {
      return false;       
    }     
  }

  function VerificaQualTipoVotoTopicoMensagem($tipo, $id) {
    if($this->modulo == "gestor") { 
      $this->sql = "SELECT tipo FROM avas_foruns_topicos_curtidas WHERE idusuario = ".$this->idusuario;
    } elseif($this->modulo == "professor") { 
      $this->sql = "SELECT tipo FROM avas_foruns_topicos_curtidas WHERE idprofessor = ".$this->idprofessor;
    } else { 
      $this->sql = "SELECT tipo FROM avas_foruns_topicos_curtidas WHERE idmatricula = ".$this->idmatricula;
    }
    
    if($tipo == "curtir_topico" || $tipo == "nao_curtir_topico") { 
      $this->sql .= " and idtopico = ".$id;
    } elseif($tipo == "curtir_mensagem" || $tipo == "nao_curtir_mensagem") {
      $this->sql .= " and idmensagem = ".$id;
    } 

    return $this->retornarLinha($this->sql);
  }
  
  /*function Cadastrar() {
    $erros = array();
    
    if(!$this->post["nome"]) {
      $erros[] = "nome_vazio";
    }
    if(!$this->post["mensagem"]) {
      $erros[] = "financeiro_vazio";
    }
    if($this->post["arquivo"]["name"]) {
      if($this->ValidarArquivo($this->post["arquivo"])) {
        $erros[] = "arquivo_vazio";
      } else {
        $extensao = strtolower(strrchr($this->post["arquivo"]["name"], "."));
        $arquivoServidor = date("YmdHis")."_".uniqid().$extensao;
        if(!move_uploaded_file($this->post["arquivo"]["tmp_name"],$_SERVER["DOCUMENT_ROOT"]."/storage/avas_foruns_topicos_arquivo/".$arquivoServidor)) {
          $erros[] = "arquivo_vazio";
        }
      }
    }
    
    if(count($erros) <= 0) {      
      
      $this->sql = "INSERT INTO 
                      avas_foruns_topicos
                    SET 
                      data_cad = now(), 
                      idforum = ".$this->id.",
                      nome = '".$this->post["nome"]."',  
                      mensagem = '".$this->post["mensagem"]."'";
      if($this->modulo == "gestor"){ 
         $this->sql .= ", idusuario = '".$this->idusuario."'";
      }
      if($this->post["periode_de"]){ 
         $this->sql .= ", periode_de = '".formataData($this->post["periode_de"], "en", 0)."'";
      }
      if($this->post["periode_ate"]){ 
         $this->sql .= ", periode_ate = '".formataData($this->post["periode_ate"], "en", 0)."'";
      }
      if($this->post["arquivo"]["name"]){ 
         $this->sql .= ", arquivo = '".$this->post["arquivo"]["name"]."',
                       arquivo_servidor = '".$arquivoServidor."',
                       arquivo_tipo = '".$this->post["arquivo"]["type"]."',
                       arquivo_tamanho = ".$this->post["arquivo"]["size"];
      }
  
      if($this->executaSql($this->sql)){
        $this->monitora_oque = 1;
        $this->monitora_onde = "153";
        $this->monitora_qual = mysql_INSERT_id();
        $this->Monitora();
        $this->retorno = $this->Retornar();
        $this->retorno["sucesso"] = true;
      } else {
        $this->retorno["erro"] = true;
        $this->retorno["erros"][] = $this->sql;
        $this->retorno["erros"][] = mysql_error();
      }
    } else {
      $this->retorno["sucesso"] = false;
      $this->retorno["erros"] = $erros;     
    }

    return $this->retorno;  
  }*/
    
  /*function ModificarTopico() {
    $this->post["idforum"] = $this->id;
    if($this->modulo == "gestor"){ 
      $this->post["idusuario"] = $this->idusuario;
    }
    
    return $this->SalvarDados();    
  }*/
    
  function RemoverTopico() {
    return $this->RemoverDados();   
  }
  
  function RemoverArquivo($modulo, $pasta, $dados, $idioma) {
    if (verificaPermissaoAcesso(false)) {
        echo $this->ExcluirArquivo($modulo, $pasta, $dados, $idioma);
    }
  }
  
    /*function contabilizar($idtopico) {
        if (verificaPermissaoAcesso(false)) {
            $sql = "SELECT 
                      af.idava, af.idforum 
                    FROM 
                      avas_foruns af
                      inner join avas_foruns_topicos aft on (af.idforum = aft.idforum)
                    WHERE 
                      aft.idtopico = ".$idtopico;
            $forum = $this->retornarLinha($sql);
            
            $sql = "SELECT count(*) as total FROM matriculas_rotas_aprendizagem_objetos WHERE idmatricula = ".$this->idmatricula." and idava = ".$forum['idava']." and idforum = ".$forum['idforum'];
            $verifica = $this->retornarLinha($sql);
            if($verifica["total"] <= 0) {
                $this->executaSql("BEGIN");
                
                $sql = "SELECT porcentagem_forum FROM avas WHERE idava = ".$forum['idava'];
                $porcentagem = $this->retornarLinha($sql);
                if(!$porcentagem['porcentagem_forum']) $porcentagem['porcentagem_forum'] = 0; 
                
                $sql = "INSERT INTO
                        matriculas_rotas_aprendizagem_objetos
                      SET
                        data_cad = now(),
                        idmatricula = ".$this->idmatricula.",
                        idava = ".$forum['idava'].",
                        idforum = ".$forum['idforum'].",
                        porcentagem = ".$porcentagem['porcentagem_forum'];
                if ($this->executaSql($sql)) {
                    $this->sql = 'SELECT 
                                    idmatricula_ava_porcentagem, 
                                    porcentagem,
                                    COUNT(*) AS total 
                                FROM 
                                    matriculas_avas_porcentagem 
                                WHERE 
                                    idmatricula = '.$this->idmatricula.' AND
                                    idava = '.$forum['idava'];
                    $verificaPorcentagem = $this->retornarLinha($this->sql);
                    if (!$verificaPorcentagem['total']) {
                        $this->sql = 'INSERT INTO matriculas_avas_porcentagem SET idmatricula = '.$this->idmatricula.', idava = '.$forum['idava'].', porcentagem = '.$porcentagem['porcentagem_forum'];
                    } else {
                        $this->sql = 'UPDATE 
                                        matriculas_avas_porcentagem 
                                    SET 
                                        porcentagem = porcentagem = IF((porcentagem + '.$porcentagem['porcentagem_forum'].') > 100, 100, (porcentagem + '.$porcentagem['porcentagem_forum'].'))  
                                    WHERE 
                                        idmatricula_ava_porcentagem = '.$verificaPorcentagem['idmatricula_ava_porcentagem'];
                    }
            
                    if ($this->executaSql($this->sql)) {
                        $this->sql = 'UPDATE matriculas SET porcentagem = IF((porcentagem + '.$porcentagem['porcentagem_forum'].') > 100, 100, (porcentagem + '.$porcentagem['porcentagem_forum'].')) WHERE idmatricula = '.$this->idmatricula;
                        if ($this->executaSql($this->sql)) {
                            $this->executaSql("COMMIT");
                        } else {
                            $this->executaSql("ROLLBACK");
                        }
                    } else {
                        $this->executaSql("ROLLBACK");
                    }
                } else {
                    $this->executaSql("ROLLBACK");
                }
            }
        
            return true;
        }
    }*/
  
  function InsereAssinanteTopico($idtopico){
    if (verificaPermissaoAcesso(true)) {
        //retorna o ava do forun
        $this->sql = "SELECT 
                        idava
                      FROM
                        avas_foruns                 
                      WHERE 
                        ativo = 'S' and 
                        idforum = ".$this->id;
                        
        $forum = $this->retornarLinha($this->sql);  
        if($forum["idava"]){
          // Pega todas a matriculas do ava
          $this->sql = "SELECT
                            m.idmatricula
                        FROM matriculas m
                            INNER JOIN ofertas_cursos_escolas ocp ON (m.idoferta = ocp.idoferta AND m.idcurso = ocp.idcurso AND m.idescola = ocp.idescola AND ocp.ativo = 'S')
                            INNER JOIN ofertas_curriculos_avas oca ON (ocp.idcurriculo = oca.idcurriculo AND oca.ativo = 'S' AND oca.idava = ".$forum["idava"].")
                        WHERE m.ativo = 'S'
                        GROUP BY m.idpessoa ";
          $sel2 = $this->executaSql($this->sql);
          while($lnMatriculas = mysql_fetch_assoc($sel2)){      
                  // SE NÃO TIVER INSERE
                  $cod = md5(uniqid(rand(), true));
                  $codToken = hash("sha512", sha1(hash("sha512", addslashes('oracul'.$cod.'oracul')))); 
                  $this->sql = "INSERT INTO 
                                  avas_foruns_topicos_assinantes_mensagens
                                SET 
                                  data_cad = now(),
                                  code = '".$codToken."',
                                  idtopico = ".intval($idtopico);
                 if($this->modulo == "aluno"){ 
                    $this->sql .= ", idmatricula = '".$lnMatriculas["idmatricula"]."' ";
                 }
                  $executa = $this->executaSql($this->sql);    
                  $this->monitora_qual = mysql_INSERT_id();
            }    
                $this->EnviaEmailForum($idtopico);
        }
    }
  }
  
  function InsereAssinantesMensagens($idtopico, $enviarEmail = true){
        if (verificaPermissaoAcesso(false)) {            
            // BUSCA SE EXISTE UMA ASSINATURA PRA O TOPICO PASSADO
            $this->sql = "SELECT idassinatura_mensagem FROM avas_foruns_topicos_assinantes_mensagens WHERE idtopico = ".intval($idtopico);            
            if($this->modulo == "gestor"){ 
               $this->sql .= " and idusuario = ".$this->idusuario;
            } elseif($this->modulo == "aluno"){ 
              $this->sql .= " and idmatricula = ".$this->idmatricula;
            } elseif($this->modulo == "professor") {
              $this->sql .= " and idprofessor = '".$this->idprofessor."' ";
            }
            $verifica = $this->retornarLinha($this->sql);
              // SE EXISTIR A ASSIATURA ATIVA
              if($verifica["idassinatura_mensagem"]) {
                $cod = md5(uniqid(rand(), true));
                $codToken = hash("sha512", sha1(hash("sha512", addslashes('oracul'.$cod.'oracul'))));   
                $this->sql = "UPDATE  
                              avas_foruns_topicos_assinantes_mensagens
                            SET 
                              ativo = 'S',
                              code = '".$codToken."'
                            WHERE
                              idassinatura_mensagem = ".$verifica["idassinatura_mensagem"];
              $executa = $this->executaSql($this->sql);           
              
              $this->monitora_qual = $verifica["idassinatura_mensagem"];
            } else {
              // SE NÃO TIVER INSERE
              $cod = md5(uniqid(rand(), true));
              $codToken = hash("sha512", sha1(hash("sha512", addslashes('oracul'.$cod.'oracul')))); 
              $this->sql = "INSERT INTO 
                              avas_foruns_topicos_assinantes_mensagens
                            SET 
                              data_cad = now(),
                              code = '".$codToken."',
                              idtopico = ".intval($idtopico);
              if($this->modulo == "gestor"){ 
                 $this->sql .= ", idusuario = '".$this->idusuario."'";
              } elseif($this->modulo == "aluno"){ 
                $this->sql .= ", idmatricula = '".$this->idmatricula."'";
              } elseif($this->modulo == "professor") {
                $this->sql .= ", idprofessor = '".$this->idprofessor."' ";
              }
              $executa = $this->executaSql($this->sql);    
              $this->monitora_qual = mysql_INSERT_id();
            }            
            if($executa){
                    $this->EnviaEmailForum($idtopico);          
            }
        }
   }

    function retornaCursosEmail($idemail)
    {
        $sql = 'SELECT 
                            idcurso 
                        FROM 
                            emails_automaticos_cursos eac 
                        WHERE 
                            eac.idcurso = c.idcurso AND 
                            eac.ativo = "S" AND 
                            eac.idemail = "'.$idemail.'" 
                        GROUP BY idcurso
                        ORDER BY idcurso';
        $query = $this->executaSql($sql);
        while ( $linha = mysql_fetch_assoc($query)) {
            $cursos[] = $linha['idcurso'];
        }
        return $cursos;
    }

    function cursoAssociado($idemail) {        
        $this->sql = 'SELECT 
                            m.idcurso 
                        FROM
                        matriculas m 
                        WHERE
                            m.idmatricula = "'.$this->idmatricula.'" AND (
                                EXISTS (
                                    SELECT idcurso FROM emails_automaticos_cursos eac 
                                    WHERE m.idcurso = eac.idcurso AND eac.ativo = "S" 
                                    AND eac.idemail = "'.$idemail.'"
                                )
                                OR
                                NOT EXISTS (
                                    SELECT idcurso FROM emails_automaticos_cursos eac 
                                    WHERE eac.ativo = "S" 
                                    AND eac.idemail = "'.$idemail.'"
                                )  

                            )
                        LIMIT 1 ';
        $query = $this->executaSql($this->sql);
        $associado = mysql_fetch_assoc($query);
        return $associado;
    }
   
   function EnviaEmailForum($idtopico){   
        // INICIO RETORNA O ULTIMO EMAIL DO TIPO ATIVIDADES DO FORUM atifo
        $this->sql = "SELECT idemail,texto,nome FROM emails_automaticos WHERE tipo = 'atifo' AND ativo = 'S' AND ativo_painel = 'S' ORDER BY idemail DESC LIMIT 1 ";
        $sel = $this->executaSql($this->sql);
        $lnEmail = mysql_fetch_assoc($sel);
        $cursoAssociado = $this->cursoAssociado($lnEmail['idemail']);
       
        if (! $cursoAssociado) {
            return;
        }

        $this->sql = 'SELECT 
                            email 
                        FROM 
                            cursos  
                        WHERE  
                            idcurso = ' . $cursoAssociado['idcurso'];
        $curso = $this->retornarLinha($this->sql);
        if ($curso['email']) {
            $emailDe = $curso['email']; 
        } else {
            $emailDe = $GLOBALS['config']['emailSistema'];
        }
        
        $nomeDe = utf8_decode($GLOBALS['config']['tituloEmpresa']);                 
        $assunto = $lnEmail['nome'];
       // FIM RETORNA O ULTIMO EMAIL DO TIPO ATIVIDADES DO FORUM atifo
      
       // PEGA O TOPICO E O FORUM
       $this->sql = "SELECT ft.nome as topico,f.nome as forum 
                        FROM avas_foruns_topicos ft
                        INNER JOIN avas_foruns f ON (ft.idforum = f.idforum)
                        WHERE ft.idtopico = ".intval($idtopico)."
                              and f.enviar_email_automatico = 'S'";
       $sel = $this->executaSql($this->sql);
       $lnTopicoForum = mysql_fetch_assoc($sel);
       
       if( $lnTopicoForum ){
	       	// INICIO RETORNA TODAS AS PESSOAS QUE SÃO ASSINANTES DO TOPICO E ENVIA O EMAIL 
	       // LEFT JOIN professores p ON (aftam.idprofessor = p.idprofessor AND pe.ativo = 'S') , IF(ISNULL(p.nome),pe.nome,p.nome) as nome, IF(ISNULL(p.email),pe.email,p.email) as email
	       $this->sql = "SELECT m.idmatricula, pe.nome,pe.email,aftam.code
	                            FROM avas_foruns_topicos_assinantes_mensagens aftam
	                        INNER JOIN matriculas m ON (aftam.idmatricula = m.idmatricula AND m.ativo = 'S')
	                        INNER JOIN pessoas pe ON (m.idpessoa = pe.idpessoa AND pe.ativo = 'S')                      
	                     WHERE 
	                        aftam.idtopico = ".intval($idtopico)." 
	                        AND aftam.ativo = 'S' 
	                        AND aftam.idmatricula <> '".$this->idmatricula."' 
	                     GROUP BY pe.idpessoa ";
	       $sel = $this->executaSql($this->sql);       
	    
	       while($pessoa = mysql_fetch_assoc($sel)) {
	            $retornoAcessoAva = $this->retornarAcessoAva($pessoa['idmatricula']); 
	            if( $retornoAcessoAva['pode_acessar_ava'] ){
	                $message = $lnEmail['texto'];
	                $message = html_entity_decode($message);
	                $message = str_ireplace('[[ALUNO][NOME]]', utf8_decode($pessoa['nome']), $message);
	                $nomePara = utf8_decode($pessoa['nome']);
	                $emailPara = $pessoa['email'];
	                $message .= 'T&oacute;pico: <strong>'.utf8_decode($lnTopicoForum["topico"]).'</strong>, f&oacute;rum: <strong>'.utf8_decode($lnTopicoForum["forum"]).'</strong><br/>
	                        <p>Caso n&atilde;o queira mais receber esse informativo clicar no link abaixo.<br>
	                         <a href="'.$GLOBALS['config']["urlSistema"].'/api/set/email_forum?tick='.$pessoa["code"].'">Clique aqui para n&atilde;o receber mais email desse t&oacute;pico <strong>'.utf8_decode($lnTopicoForum["topico"]).'</strong>, f&oacute;rum <strong>'.utf8_decode($lnTopicoForum["forum"]).'.<a></p>';
	                
	                $this->EnviarEmail($nomeDe, $emailDe, $assunto, $message, $nomePara, $emailPara);
	            }         
	       }  
	       // FIM RETORNA TODAS AS PESSOAS QUE SÃO ASSINANTES DO TOPICO E ENVIA O EMAIL    
       }
   }
   
   function DesabilitaEmailForum($idassinatura_mensagem){
     $this->sql = "UPDATE  
                          avas_foruns_topicos_assinantes_mensagens
                        SET 
                          ativo = 'N'
                        WHERE
                          idassinatura_mensagem = ".$idassinatura_mensagem;
     if($this->executaSql($this->sql)){
        $retorno["sucesso"] = true;
     }else{
        $retorno["sucesso"] = true;  
     }
     return $retorno; 
   }
   
   function RetornaDadosForumDesativar($tk){
        if($tk){
            $this->sql = "SELECT pe.nome,ta.idassinatura_mensagem,ft.nome as topico,af.nome as forum,ta.ativo
                        FROM avas_foruns_topicos_assinantes_mensagens ta
                      INNER JOIN matriculas m ON (ta.idmatricula= m.idmatricula)
                      INNER JOIN pessoas pe ON (m.idpessoa = pe.idpessoa)
                      INNER JOIN avas_foruns_topicos ft ON (ft.idtopico = ta.idtopico)
                      INNER JOIN avas_foruns af ON (af.idforum = ft.idforum)
                      WHERE ta.code = '".mysql_real_escape_string($tk)."' AND m.ativo = 'S' AND pe.ativo = 'S' AND ft.ativo = 'S' AND af.ativo = 'S' ";
            return $this->retornarLinha($this->sql);        
        }
   }
   
   public function retornarAcessoAva($idMatricula) {
       $retorno = array();
       $retorno['pode_acessar_ava'] = true;
   
       $dataHoje = date('Y-m-d');
   
       $retorno['data_inicio_acesso_ava'] = $dataHoje;
       $retorno['data_limite_acesso_ava'] = $dataHoje;
   
       $this->sql = 'SELECT
                        m.data_matricula,
						m.data_prolongada,
						ocp.data_inicio_ava,
						ocp.dias_para_ava,
                        ocp.dias_para_prova,
                        ocp.data_limite_ava
                    FROM
                        matriculas m
						INNER JOIN ofertas_cursos_escolas ocp ON
                        (
                            m.idoferta = ocp.idoferta AND
                            m.idescola = ocp.idescola AND
                            m.idcurso = ocp.idcurso AND
                            ocp.ativo = "S"
                        )
					WHERE
						m.idmatricula = '.$idMatricula.' AND
						m.ativo = "S"';
   
       $datas = $this->retornarLinha($this->sql);
   
       $retorno['dias_para_prova'] = $datas['dias_para_prova'];
   
       if($datas['data_inicio_ava']) {
           $retorno['data_inicio_acesso_ava'] = $datas['data_inicio_ava'];
           if($retorno['data_inicio_acesso_ava'] > $dataHoje) {
               $retorno['pode_acessar_ava'] = false;
           }
       }
   
       if($datas['data_prolongada']) {
           $retorno['data_limite_acesso_ava'] = $datas['data_prolongada'];
       } elseif($datas['dias_para_ava'] || $datas['data_limite_ava']) {
           	
           $dataDiasParaAva = NULL;
           if ($datas['dias_para_ava']) {
               $dataDiasParaAva = new DateTime($datas['data_matricula']);
               $dataDiasParaAva->modify('+ '.$datas['dias_para_ava'].' days');
           }
           	
           $dataLimiteAva = NULL;
           if ($datas['data_limite_ava']) {
               $dataLimiteAva = new DateTime($datas['data_limite_ava']);
           }
           	
           if ($dataDiasParaAva && $dataLimiteAva) {
               if ($dataDiasParaAva > $dataLimiteAva) {
                   $retorno['data_limite_acesso_ava'] = $dataDiasParaAva->format('Y-m-d');
               } else {
                   $retorno['data_limite_acesso_ava'] = $dataLimiteAva->format('Y-m-d');
               }
           } elseif($dataDiasParaAva) {
               $retorno['data_limite_acesso_ava'] = $dataDiasParaAva->format('Y-m-d');
           } else {
               $retorno['data_limite_acesso_ava'] = $dataLimiteAva->format('Y-m-d');
           }
       }
   
       if($retorno['data_limite_acesso_ava'] < $dataHoje) {
           $retorno['pode_acessar_ava'] = false;
       }
   
       return $retorno;
   }
    
}

?>