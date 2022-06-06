<?php
class Atendimentos extends Core
{
    var $idpessoa = NULL;
    var $idgestor = NULL;
    var $gestor_empreendimento = NULL;
    var $pagina_inicial_total = null;

    function ListarTodas() {

        $this->retorno = array();

        $this->sql = '
            SELECT ' . $this->campos . '
            FROM atendimentos ate
            INNER JOIN pessoas p ON ate.idpessoa = p.idpessoa
            INNER JOIN atendimentos_assuntos aa ON ate.idassunto = aa.idassunto
            INNER JOIN atendimentos_workflow aw ON ate.idsituacao = aw.idsituacao
            LEFT JOIN atendimentos_assuntos_subassuntos aas ON ate.idsubassunto = aas.idsubassunto
            LEFT JOIN matriculas m on ate.idmatricula = m.idmatricula
            LEFT JOIN sindicatos i ON m.idsindicato = i.idsindicato
            LEFT JOIN cursos c ON m.idcurso = c.idcurso ';

        if($this->idgestor) {
            $this->sql .= " WHERE ate.ativo = 'S' ";

            if(trim($_SESSION['adm_assuntos']) || trim($_SESSION['adm_subassuntos'])) {
                $this->sql .= ' AND ( ';
                if ($_SESSION['adm_assuntos']){
                    $this->sql .= ' ate.idassunto in (' . $_SESSION['adm_assuntos'] . ') ';
                }
                if ($_SESSION['adm_subassuntos']) {
                    if ($_SESSION['adm_assuntos']){
                        $this->sql .= ' OR ';
                    }
                    $this->sql .= ' ate.idsubassunto in (' . $_SESSION['adm_subassuntos'] . ') ';
                }
                $this->sql .= ' ) ';
            }
            if ($_SESSION['adm_gestor_sindicato'] != 'S') {
                $this->sql .= '
                    AND
                    (
                        ( ate.idmatricula is null and c.idcurso is null )
                        or
                        ( ate.idcurso in (' . $_SESSION['adm_cursos'] . ') )
                        or
                        ( m.idescola in (' . $_SESSION['adm_escolas'] . ') )
                    )       ';
            }

        } else
            $this->sql .= " WHERE ate.ativo = 'S' AND ate.idpessoa = '".$this->idpessoa."' AND ate.cliente_visualiza = 'S' ";

        if(is_array($_GET["q"])) {
            foreach($_GET["q"] as $campo => $valor) {
                $campo = explode("|",$campo);
                $valor = str_replace("'","",$valor);
                if(($valor || $valor === "0") and $valor <> "todos") {
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
                    } elseif($campo[0] == 4)  {
                        if ($valor == 'I')
                            $this->sql .= " and (ar.idusuario is not null and ar.idresposta is not null )";
                        else if ($valor == 'E')
                            $this->sql .= " and (ar.idpessoa is not null and ar.idresposta is not null )";
                    } elseif($campo[0] == 5)  {
                        $this->sql .= " and IF( ( HOUR(TIMEDIFF(NOW(),ate.data_cad)) >= aa.sla ),'S','N' ) = '".$valor."' ";
                    } elseif($campo[0] == 6) {
                        if ($valor == 'N')
                            $this->sql .= " and (".$campo[1]." is null )";
                        else
                            $this->sql .= " and (".$campo[1]." is not null )";
                    }

                }
            }
        }

        if (!$this->pagina_inicial_total)
            $this->sql .= " GROUP BY ate.idatendimento ";

        $this->groupby = "ate.idatendimento";

        return $this->retornarLinhasII();
    }

    function retornarQuemVisualiza($idatendimento){

        $sql = "select
                    a.nome
                from
                    usuarios_adm a
                where
                    a.idusuario in
                    (
                        select
                            a1.idusuario
                        from
                            atendimentos ate
                            inner join atendimentos_assuntos aa on (ate.idassunto = aa.idassunto)
                            left outer join atendimentos_assuntos_subassuntos aas on (ate.idsubassunto = aas.idsubassunto)
                            left outer join atendimentos_assuntos_subassuntos_grupos aasg on (ate.idsubassunto = aasg.idsubassunto and aasg.ativo = 'S')
                            left outer join atendimentos_assuntos_grupos aag on (ate.idassunto = aag.idassunto and aag.ativo = 'S')
                            left outer join grupos_usuarios_adm_usuarios guau on ((guau.idgrupo = aasg.idgrupo OR guau.idgrupo = aag.idgrupo) and guau.ativo = 'S')
                            left outer join grupos_usuarios_adm gua on (guau.idgrupo = gua.idgrupo and gua.ativo = 'S')
                            inner join usuarios_adm a1 on (guau.idusuario = a1.idusuario and a1.ativo = 'S')
                        where
                            ate.idatendimento = '".$idatendimento."' and gua.idgrupo is not null
                        group by a1.idusuario
                        order by a1.nome
                    ) or
                    a.idusuario in
                    (
                        select
                            a2.idusuario
                        from
                            atendimentos ate
                            inner join atendimentos_assuntos aa on (ate.idassunto = aa.idassunto)
                            left outer join atendimentos_assuntos_subassuntos aas on (ate.idsubassunto = aas.idsubassunto)
                            left outer join atendimentos_assuntos_subassuntos_grupos aasg on (ate.idsubassunto = aasg.idsubassunto and aasg.ativo = 'S')
                            left outer join atendimentos_assuntos_grupos aag on (ate.idassunto = aag.idassunto and aag.ativo = 'S')
                            left outer join grupos_usuarios_adm_usuarios guau on ((guau.idgrupo = aasg.idgrupo OR guau.idgrupo = aag.idgrupo) and guau.ativo = 'S')
                            left outer join grupos_usuarios_adm gua on (guau.idgrupo = gua.idgrupo and gua.ativo = 'S')
                            inner join usuarios_adm a2 on (guau.idusuario = a2.idusuario and a2.ativo = 'S')
                        where
                            ate.idatendimento = '".$idatendimento."' and gua.idgrupo is not null
                        group by a2.idusuario
                        order by a2.nome
                    )
                group by a.idusuario
                order by a.nome";

        $query = $this->executaSql($sql);
        while($linha = mysql_fetch_assoc($query)) {
          $retorno[] = $linha;
        }
        return $retorno;
    }

    function retornarLinhasII() {
         $this->retorno = array();

         if($this->limite != -1){
              $query = mysql_query($this->sql);
              $this->total = mysql_num_rows($query);

              if(intval($this->limite) <= 0 and intval($this->limite) != -1)
                $this->limite = 1;

              $this->paginas = ceil($this->total/$this->limite);

              if($this->paginas == 0) $this->paginas = 1;

              $this->inicio = ($this->pagina-1) * $this->limite;

              if($this->ordem_campo && $this->ordem) $this->sql .= " order by ".$this->ordem_campo." ".$this->ordem."";
              if($this->limite > 0) $this->sql .= " limit ".$this->inicio.",".$this->limite."";
          }else{
              if($this->ordem_campo && $this->ordem) $this->sql .= " order by ".$this->ordem_campo." ".$this->ordem."";
          }

          $sqlAux = $this->executaSql($this->sql);
          while($linha = mysql_fetch_assoc($sqlAux)){
              $this->retorno[] = $linha;
          }

          if($this->limite == -1){
              $this->total = count($this->retorno);
          }
        return $this->retorno;
    }

    function Retornar() {
        $this->sql = 'SELECT
                        '.$this->campos.',
                        usu_adm.nome as usuario
                    FROM
                        atendimentos ate
                        INNER JOIN pessoas p ON (ate.idpessoa = p.idpessoa)
                        INNER JOIN atendimentos_workflow aw ON (ate.idsituacao = aw.idsituacao)
                        INNER JOIN atendimentos_assuntos ass ON (ate.idassunto = ass.idassunto)
                        LEFT JOIN usuarios_adm usu_adm ON (ate.idusuario = usu_adm.idusuario)
                        LEFT JOIN atendimentos_assuntos_subassuntos sub ON (ate.idsubassunto = sub.idsubassunto)
                        LEFT JOIN atendimentos_respostas ar ON (ate.idatendimento = ar.idatendimento)
                        LEFT JOIN matriculas m ON (ate.idmatricula = m.idmatricula)
                        LEFT JOIN cursos c ON (m.idcurso = c.idcurso)
                    WHERE
                        ate.ativo = "S" ';

        if($this->idgestor) {

            if($_SESSION['adm_assuntos'] || $_SESSION['adm_subassuntos']) {
                $this->sql .= ' AND ( ';
                if ($_SESSION['adm_assuntos']){
                    $this->sql .= ' ate.idassunto IN (' . $_SESSION['adm_assuntos'] . ') ';
                }
                if ($_SESSION['adm_subassuntos']) {
                    if ($_SESSION['adm_assuntos']){
                        $this->sql .= ' OR ';
                    }
                    $this->sql .= ' ate.idsubassunto IN (' . $_SESSION['adm_subassuntos'] . ') ';
                }
                $this->sql .= ' ) ';
            }

            if ($_SESSION['adm_gestor_sindicato'] != 'S') {
                $this->sql .= '
                    AND
                    (
                        ( ate.idmatricula is null and c.idcurso is null )
                        OR
                        ( ate.idcurso in (' . $_SESSION['adm_cursos'] . ') )
                        OR
                        ( m.idescola in (' . $_SESSION['adm_escolas'] . ') )
                    )';
            }

        } else
            $this->sql .= ' AND ate.idpessoa = "'.$this->idpessoa.'" AND ate.cliente_visualiza = "S"';

        $this->sql .= ' AND ate.idatendimento = '.$this->id;
        $this->sql .= ' GROUP BY ate.idatendimento ';

        return $this->retornarLinha($this->sql);
    }

    function retornaRespostas($idatendimento) {
        $sql = 'SELECT
                    ar.*,
                    u.nome as usuario,
                    u.avatar_servidor as avatar_usuario,
                    c.nome as cliente,
                    c.avatar_servidor as avatar_aluno,
                    ara.resposta as automatica
                FROM
                    atendimentos_respostas ar
                    LEFT JOIN usuarios_adm u ON (ar.idusuario = u.idusuario)
                    LEFT JOIN pessoas c ON (ar.idpessoa = c.idpessoa)
                    LEFT JOIN atendimentos_respostas_automaticas ara ON (ar.idresposta_automatica = ara.idresposta)
                WHERE
                    ar.idatendimento = '.$idatendimento.' AND
                    ar.ativo = "S"';
        if ($this->idpessoa)
            $sql .= ' AND ar.publica = "S" ';

        $sql .= ' ORDER BY ar.idresposta ASC ';
        $query = $this->executaSql($sql);
        while ($linha = mysql_fetch_assoc($query)) {
            $linha["arquivos"] = $this->retornaArquivosRespostas($linha["idresposta"]);
            $retorno[] = $linha;
        }

        return $retorno;
    }

    function retornaArquivosRespostas($idresposta) {
        $this->sql = 'SELECT * FROM atendimentos_arquivos where ativo = "S" AND idresposta = '.$idresposta;

        $this->limite = -1;
        $this->ordem = "asc";
        $this->ordem_campo = "idarquivo";
        $this->groupby = "idarquivo";
        $dados = $this->retornarLinhas();

        return $dados;
    }

    function retornaArquivosAtendimentos($idatendimento) {
        $this->sql = "SELECT * FROM atendimentos_arquivos where ativo = 'S' AND idatendimento = ".$idatendimento;

        $this->limite = -1;
        $this->ordem = "asc";
        $this->ordem_campo = "idarquivo";
        $this->groupby = "idarquivo";
        $dados = $this->retornarLinhas();

        return $dados;
    }

    function retornaResposta($idresposta) {
        $this->sql = "SELECT * FROM atendimentos_respostas WHERE idresposta = ".$idresposta;
        $retorno = $this->retornarLinha($this->sql);
        $retorno["arquivos"] = $this->retornaArquivosRespostas($idresposta);

        return $retorno;
    }

    function retornaArquivoResposta($idarquivo) {
        $this->sql = "SELECT * FROM atendimentos_arquivos where idarquivo = ".$idarquivo;
        $retorno = $this->retornarLinha($this->sql);

        return $retorno;
    }

    function retornaArquivoDownload($idatendimento, $idresposta, $idarquivo) {
        $this->sql = "SELECT ara.* FROM atendimentos_arquivos ara
                        inner join atendimentos_respostas ar on (ara.idresposta = ar.idresposta)
                      where
                        ara.idarquivo = ".$idarquivo." and
                        ar.idresposta = ".$idresposta." and
                        ar.idatendimento = ".$idatendimento;
        $retorno = $this->retornarLinha($this->sql);
        return $retorno;
    }

    function retornaArquivoAtendimentoDownload($idatendimento, $idarquivo) {
        $this->sql = "SELECT * FROM atendimentos_arquivos
                      where
                        idarquivo = ".$idarquivo." and
                        idatendimento = ".$idatendimento;
        $retorno = $this->retornarLinha($this->sql);
        return $retorno;
    }

    function ExcluirArquivo($idarquivo, $pasta) {
        $arquivo = $this->retornaArquivoResposta($idarquivo);

        if(unlink($_SERVER["DOCUMENT_ROOT"]."/storage/".$pasta."/".$arquivo["servidor"])) {

              $this->sql = "UPDATE atendimentos_arquivos SET ativo = 'N' where idarquivo = ".$idarquivo;
              $this->executaSql($this->sql);

              $this->monitora_oque = 17;
              $this->monitora_onde = 56;
              $this->monitora_qual = $idarquivo;
              $this->Monitora();

              $info["sucesso"] = true;

        } else {
              $info["sucesso"] = false;
        }
        return json_encode($info);
    }

    function retornaUltimaInteracaoAtendente($idatendimento) {
        $sql = "SELECT ar.*, u.nome as usuario
                    FROM atendimentos_respostas ar
                    LEFT JOIN usuarios_adm u on ar.idusuario = u.idusuario
                    where ar.ativo='S' and ar.idatendimento='".$idatendimento."' and ar.idusuario is not null
                    order by idresposta desc limit 1";
        $query = $this->executaSql($sql);
        $linha = mysql_fetch_assoc($query);
        return $linha;
    }

    function retornaUltimaInteracao($idatendimento) {
        $sql = "SELECT ar.data_cad
                    FROM atendimentos_respostas ar
                    LEFT JOIN usuarios_adm u on ar.idusuario = u.idusuario
                    where ar.ativo='S' and ar.idatendimento='".$idatendimento."'
                    order by idresposta desc limit 1";
        $query = $this->executaSql($sql);
        $linha = mysql_fetch_assoc($query);
        return $linha;
    }

    function Cadastrar()
    {
        if (verificaPermissaoAcesso(true)) {
            $this->retorno = array();

            $erros = $this->BuscarErros();

            if($erros){

                $this->retorno["erro"] = true;
                $this->retorno["erros"] = $erros;

            } else {

                $permissoes = 'jpg|jpeg|gif|png|bmp|zip|rar|tar|gz|doc|docx|xls|xlsx|ppt|pptx|pps|ppsx|txt|pdf';
                $campo = array("pasta" => "atendimentos_arquivos");

                $existe_arquivos = false;
                foreach ($_FILES['arquivo']['name'] as $ind => $arq)
                    if ($arq)
                        $existe_arquivos = true;

                if ($existe_arquivos) {
                    foreach ($_FILES['arquivo']['name'] as $ind => $arquivo) {
                        $file['name'] = $_FILES['arquivo']['name'][$ind];
                        $file['tmp_name'] = $_FILES['arquivo']['tmp_name'][$ind];
                        $file['size'] = $_FILES['arquivo']['size'][$ind];
                        unset($nome_servidor);

                        $file_aux['name'] = $file;
                        $validacao_tamanho = $this->ValidarArquivo($file_aux['name']);

                        if($validacao_tamanho) {
                            $this->retorno["erro"] = true;
                            $this->retorno["erros"][] = $validacao_tamanho;
                            return $this->retorno;
                        }
                    }
                }

                $this->sql = "SELECT idsituacao FROM atendimentos_workflow WHERE ativo = 'S' AND inicio = 'S' LIMIT 1";
                $idsituacao = $this->retornarLinha($this->sql);

                if(!$this->post["idsubassunto"]) $this->post["idsubassunto"] = "NULL";

                if(!$this->post["idusuario"]) {
                    $this->post["idusuario"] = "NULL";
                }
                if(!$this->post["idpessoa"]) {
                    $this->post["idpessoa"] = "NULL";
                }
                if(!$this->post["idcurso"]) {
                    $this->post["idcurso"] = "NULL";
                }
                if(!$this->post["idmatricula"]) {
                    $this->post["idmatricula"] = "NULL";
                }

                $this->sql = "INSERT INTO
                                atendimentos
                              SET
                                idpessoa = ".$this->post["idpessoa"].",
                                idusuario = ".$this->post["idusuario"].",
                                idassunto = ".$this->post["idassunto"].",
                                idsubassunto = ".$this->post["idsubassunto"].",
                                idcurso = ".$this->post["idcurso"].",
                                idmatricula = ".$this->post["idmatricula"].",
                                idsituacao = ".$idsituacao["idsituacao"].",
                                data_cad = NOW(),
                                nome = '".$this->post["titulo"]."',
                                descricao = '".$this->post["descricao"]."'";
                if($this->post['cliente_bloquear'])
                    $this->sql .= ", cliente_visualiza = 'N' ";

                $salvar = $this->executaSql($this->sql);
                $idatendimento = mysql_insert_id();

                $this->addHistorico($idatendimento, NULL, NULL, NULL, $idsituacao["idsituacao"], false, false, "S");
                //$this->adicionarFeed($idatendimento, NULL, NULL, NULL, NULL, "S", NULL, $idsituacao["idsituacao"]);

                if ($idatendimento && $existe_arquivos) {

                    foreach ($_FILES['arquivo']['name'] as $ind => $arquivo) {
                        $file['name'] = $_FILES['arquivo']['name'][$ind];
                        $file['tmp_name'] = $_FILES['arquivo']['tmp_name'][$ind];
                        $file['size'] = $_FILES['arquivo']['size'][$ind];
                        unset($nome_servidor);

                        $file_aux['name'] = $file;
                        $validacao_tamanho = $this->ValidarArquivo($file_aux['name']);

                        if($validacao_tamanho) {
                            $this->retorno["erro"] = true;
                            $this->retorno["erros"][] = $validacao_tamanho;
                            return $this->retorno;
                        }

                        $nome_servidor = $this->uploadFile($file, $campo);

                        if ($nome_servidor) {
                            $sql = "insert into atendimentos_arquivos set
                                  idatendimento = '".$idatendimento."',
                                  ativo = 'S',
                                  data_cad = NOW(),
                                  nome = '".$_FILES['arquivo']['name'][$ind]."',
                                  tipo = '".$_FILES['arquivo']['type'][$ind]."',
                                  tamanho = '".$_FILES['arquivo']['size'][$ind]."',
                                  servidor = '".$nome_servidor."' ";
                            $query_arquivo = $this->executaSql($sql);
                            $idarquivo = mysql_insert_id();
                            if (!$query_arquivo) {
                                $erro = true;
                            } else {
                                $this->monitora_onde = 97;
                                $this->monitora_oque = 1;
                                $this->monitora_qual = $idarquivo;
                                $this->Monitora();
                            }
                        }
                    }

                }

                $this->monitora_onde = 90;
                $this->monitora_oque = 1;
                $this->monitora_qual = $idatendimento;
                $this->Monitora();

                if($salvar){

                    $protocolo = date("ymd").str_pad(substr($idatendimento, -4), 4, "0", STR_PAD_LEFT);

                    $this->sql = "UPDATE atendimentos SET protocolo = ".$protocolo." WHERE idatendimento = ".$idatendimento;
                    $this->executaSql($this->sql);

                    $this->sql = "SELECT * FROM atendimentos WHERE idatendimento = ".$idatendimento;
                    $this->retorno = $this->retornarLinha($this->sql);
                    $this->retorno["sucesso"] = true;
                    $this->retorno["idatendimento"] = $idatendimento;

                } else {
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = $this->sql;
                    $this->retorno["erros"][] = mysql_error();
                }
            }

            return $this->retorno;
        }
    }

    function AddVisualizacoesAssunto($idassunto, $idatendimento) {
        $this->sql = "SELECT
                        gu.idgrupo_usuario,
                        gu.idgrupo,
                        gu.idusuario
                      FROM
                        atendimentos_assuntos_grupos aag
                        INNER JOIN atendimentos_assuntos aa ON (aag.idassunto = aa.idassunto)
                        INNER JOIN grupos_usuarios_adm_usuarios gu ON (gu.idgrupo = aag.idgrupo)
                      WHERE
                        gu.ativo = 'S' AND
                        aag.idassunto = ".$idassunto;
        $this->limite = -1;
        $this->ordem_campo = "gu.idusuario";
        $this->groupby = "gu.idusuario";
        $usuariosAssunto = $this->retornarLinhas();

        foreach($usuariosAssunto as $ind => $usuarioAssunto) {
                $this->sql = "INSERT INTO
                                atendimentos_convidados
                              SET
                                idatendimento = ".$idatendimento." ,
                                idusuario = ".$usuarioAssunto["idusuario"].",
                                data_cad = NOW()";
                $this->executaSql($this->sql);
        }
    }

    function AddVisualizacoesSubassunto($idsubassunto, $idatendimento) {
        $this->sql = "SELECT
                        gu.idgrupo_usuario,
                        gu.idgrupo,
                        gu.idusuario
                      FROM
                        atendimentos_assuntos_subassuntos_grupos asg
                        INNER JOIN atendimentos_assuntos_subassuntos ass ON (asg.idsubassunto = ass.idsubassunto)
                        INNER JOIN grupos_usuarios_adm_usuarios gu ON (gu.idgrupo = asg.idgrupo)
                      WHERE
                        gu.ativo = 'S' AND
                        asg.idsubassunto = ".$idsubassunto;
        $this->limite = -1;
        $this->ordem_campo = "gu.idusuario";
        $this->groupby = "gu.idusuario";
        $usuariosSubassunto = $this->retornarLinhas();

        foreach($usuariosSubassunto as $ind => $usuarioSubassunto) {
                $this->sql = "INSERT INTO
                                atendimentos_convidados
                              SET
                                idatendimento = ".$idatendimento.",
                                idusuario = ".$usuarioSubassunto["idusuario"].",
                                data_cad = NOW()";
                $this->executaSql($this->sql);
        }
    }

    function RetornarSubassuntos($idassunto, $json = true) {
        $this->sql = 'SELECT idsubassunto, nome FROM atendimentos_assuntos_subassuntos where idassunto = "'.$idassunto.'" AND ativo = "S" AND ativo_painel = "S"';
        $this->ordem_campo = 'nome';
        $this->groupby = 'nome';

        if ($json) {
            $sql = 'SELECT nome, subassunto_obrigatorio FROM atendimentos_assuntos where idassunto = "'.$idassunto.'" AND ativo_painel = "S"';
            $assunto = $this->retornarLinha($sql);
        }

        $this->limite = -1;
        $this->ordem = 'ASC';
        $dados = $this->retornarLinhas();

        if ($json) {
            $dadosJson = array();
            $dadosJson['subassunto'] = $dados;
            $dadosJson['assunto'] = $assunto['nome'];
            $dadosJson['subassunto_obrigatorio'] = $assunto['subassunto_obrigatorio'];
            return json_encode($dadosJson);
        } else
            return $dados;
    }

    function AlterarSituacao() {

        $this->retorno = array();

        $this->sql = "SELECT * FROM atendimentos WHERE idatendimento = ".intval($this->id);
        $linhaAntiga = $this->retornarLinha($this->sql);

        if($this->VerificaPreRequesito($linhaAntiga["idsituacao"],$this->post["situacao_para"])){
            $sqlSituacao = "select fim from atendimentos_workflow where idsituacao = ".$this->post["situacao_para"];
            $linhaSituacao = $this->retornarLinha($sqlSituacao);

            $this->sql = "update atendimentos set idsituacao='".$this->post["situacao_para"]."'";
            if($linhaSituacao["fim"] == "S") {
              $tempo = dataDiferenca($linhaAntiga["data_cad"], date("Y-m-d H:i:s"), "I");
              $this->sql .= " ,tempo_finalizado = ".$tempo;
            }
            $this->sql .= " where idatendimento = ".intval($this->id);
            $salvar = $this->executaSql($this->sql);

            $this->sql = "SELECT * FROM atendimentos WHERE idatendimento = ".intval($this->id);
            $linhaNova = $this->retornarLinha($this->sql);

            $this->addHistorico(intval($this->id), intval($this->idusuario), NULL, $linhaAntiga['idsituacao'], $this->post["situacao_para"], false, false, "S");
            //$this->adicionarFeed(intval($this->id), intval($this->idusuario), NULL, NULL, NULL, "S", $linhaAntiga['idsituacao'], $this->post["situacao_para"]);

            $this->ProcessaAcoes($linhaAntiga["idsituacao"],$this->post["situacao_para"]);

            /*$this->sql = "INSERT INTO mensagens_alerta
            SET
            idmatricula = ".$linhaNova["idmatricula"].",
            tipo_alerta = 'atendimento',
            idatendimento = ".intval($this->id).",
            idsituacao_atendimento = ".$this->post["situacao_para"];

            $this->executaSql($this->sql);*/

            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "mensagem_situacao_sucesso";

            $this->monitora_oque = 7;
            $this->monitora_qual = $this->id;
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->Monitora();

        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "mensagem_situacao_erro_prerequesitos";
        }

        return $this->retorno;
    }

    /*function VerificaPreRequesito($de,$para) {

        $this->sql = "select idrelacionamento from atendimentos_workflow_relacionamentos where idsituacao_de = ".$de." and idsituacao_para = ".$para." and ativo = 'S'";
        $relacionamento = $this->retornarLinha($this->sql);

        $this->sql = "select
                        awo.idopcao
                      from
                        atendimentos_workflow_acoes awa
                        inner join atendimentos_workflow_opcoes awo on (awa.idopcao = awo.idopcao)
                      where
                        awa.idrelacionamento = ".$relacionamento["idrelacionamento"]." and
                        awo.tipo = 'prerequisito' and
                        awa.ativo = 'S' and
                        awo.ativo = 'S'";
        $this->limite = -1;
        $this->ordem_campo = "awo.idopcao";
        $preRequisitos = $this->retornarLinhas();

        if(count($preRequisitos) > 0) {
          $this->sql = "select * from atendimentos where idatendimento = ".intval($this->id);
          $atendimento = $this->retornarLinha($this->sql);
          foreach($preRequisitos as $ind => $preRequisito) {
            switch($preRequisito["idopcao"]) {
              //Ter uma resposta pública
              case 5:
                $this->sql = "select count(*) as total from atendimentos_respostas where idatendimento = ".$atendimento["idatendimento"]." and idpessoa is null and publica = 'S' and ativo = 'S'";
                $totalResposta = $this->retornarLinha($this->sql);
                if($totalResposta["total"] <= 0) {
                  return false;
                }
              break;
              //Ter uma resposta do cliente
              case 6:
                $this->sql = "select count(*) as total from atendimentos_respostas where idatendimento = ".$atendimento["idatendimento"]." and idpessoa is not null and ativo = 'S'";
                $totalResposta = $this->retornarLinha($this->sql);
                if($totalResposta["total"] <= 0) {
                  return false;
                }
              break;
            }
          }
        }
        return true;
    }*/

    function VerificaPreRequesito($de,$para) {

        $this->sql = "select idrelacionamento from atendimentos_workflow_relacionamentos where idsituacao_de = ".$de." and idsituacao_para = ".$para." and ativo = 'S'";
        $relacionamento = $this->retornarLinha($this->sql);

        $this->sql = "select
                        awa.idopcao
                      from
                        atendimentos_workflow_acoes awa
                      where
                        awa.idrelacionamento = ".$relacionamento["idrelacionamento"]." and
                        awa.ativo = 'S'";
        $this->limite = -1;
        $this->ordem_campo = "awa.idopcao";
        $resultado = $this->executaSql($this->sql);

        while($acao = mysql_fetch_assoc($resultado)) {
            /*if($GLOBALS['workflow_parametros_comissoes'][$acao['idopcao']]['tipo'] == 'visualizacao')
                $this->retorno["situacao"]["visualizacoes"][$acao["idopcao"]] = $acao;*/
            foreach($GLOBALS['workflow_parametros_atendimentos'] as $op) {
              if($op['idopcao'] == $acao['idopcao'] && $op['tipo'] == "prerequisito") {
                $preRequisitos[] = $acao;
              }
            }
        }

        if(count($preRequisitos) > 0) {
          $this->sql = "select * from atendimentos where idatendimento = ".intval($this->id);
          $atendimento = $this->retornarLinha($this->sql);
          foreach($preRequisitos as $ind => $preRequisito) {
            switch($preRequisito["idopcao"]) {
              //Ter uma resposta pública
              case 5:
                $this->sql = "select count(*) as total from atendimentos_respostas where idatendimento = ".$atendimento["idatendimento"]." and idpessoa is null and publica = 'S' and ativo = 'S'";
                $totalResposta = $this->retornarLinha($this->sql);
                if($totalResposta["total"] <= 0) {
                  return false;
                }
              break;
              //Ter uma resposta do cliente
              case 6:
                $this->sql = "select count(*) as total from atendimentos_respostas where idatendimento = ".$atendimento["idatendimento"]." and idpessoa is not null and ativo = 'S'";
                $totalResposta = $this->retornarLinha($this->sql);
                if($totalResposta["total"] <= 0) {
                  return false;
                }
              break;
              case 8:
                //Ter checklist respondido
                $sql_assunto_check_obr = "select c.obrigatorio
                                        from atendimentos_assuntos aa
                                        inner join checklists c on aa.idchecklist = c.idchecklist
                                        where aa.idassunto = '".$atendimento['idassunto']."' ";
                $assunto_check_obrigatorio = $this->retornarLinha($sql_assunto_check_obr);

                $sql_subassunto_check_obr = "select c.obrigatorio
                                        from atendimentos_assuntos_subassuntos aas
                                        inner join checklists c on aas.idchecklist = c.idchecklist
                                        where aas.idsubassunto = '".$atendimento['idsubassunto']."' ";
                $subassunto_check_obrigatorio = $this->retornarLinha($sql_subassunto_check_obr);

                if($assunto_check_obrigatorio['obrigatorio'] == 'S' || $subassunto_check_obrigatorio['obrigatorio'] == 'S') {
                    $sql_checklist_atendimento = "select count(*) as total from atendimentos_checklists_opcoes_marcados where idatendimento = '".$atendimento['idatendimento']."' and ativo = 'S' ";
                    $checklist_atendimento = $this->retornarLinha($sql_checklist_atendimento);
                    if(!$checklist_atendimento['total'])
                        return false;
                }
                break;
            }
          }
        }
        return true;
    }

    /*function ProcessaAcoes($de,$para){
      $this->sql = "select idrelacionamento from atendimentos_workflow_relacionamentos where idsituacao_de = ".$de." and idsituacao_para = ".$para." and ativo = 'S'";
      $relacionamento = $this->retornarLinha($this->sql);

      $this->sql = "select
                      awo.idopcao,
                      awo.idopcao
                    from
                      atendimentos_workflow_acoes awa
                      inner join atendimentos_workflow_opcoes awo on (awa.idopcao = awo.idopcao)
                    where
                      awa.idrelacionamento = ".$relacionamento["idrelacionamento"]." and
                      awo.tipo = 'acao' and
                      awa.ativo = 'S' and
                      awo.ativo = 'S'";
      $this->limite = -1;
      $this->ordem_campo = "awo.idopcao";
      $preRequisitos = $this->retornarLinhas();
      if(count($preRequisitos) > 0) {
        $this->sql = "select * from atendimentos where idatendimento = ".intval($this->id);
        $atendimento = $this->retornarLinha($this->sql);
        foreach($preRequisitos as $ind => $preRequisito) {
          switch($preRequisito["idopcao"]) {
            //Enviar e-mail para o cliente
            case 3:
              $this->sql = "select nome, email from pessoas where idpessoa = ".$atendimento["idpessoa"];
              $pessoa = $this->retornarLinha($this->sql);

              //Texto do e-mail
              $this->sql = "select idparametro, valor from atendimentos_workflow_acoes_parametros where idacao = ".$preRequisito["idacao"]." and idparametro = 12";
              $parametro = $this->retornarLinha($this->sql);

              if($pessoa["email"] && $parametro["valor"]) {
                $message = $parametro["valor"];
                $nomePara = utf8_decode($pessoa["nome"]);

                $message = str_ireplace("[[cliente][nome]]", $nomePara, $message);
                $message = str_ireplace("[[atendimento][protocolo]]", $atendimento["protocolo"], $message);
                $message = str_ireplace("[[atendimento][idatendimento]]", $atendimento["idatendimento"], $message);

                $emailPara = $pessoa["email"];
                $assunto = utf8_decode("Modificação no atendimento #".$atendimento["protocolo"]);

                $nomeDe = utf8_decode($GLOBALS["config"]["tituloEmpresa"]);
                $emailDe = $GLOBALS["config"]["emailSistemaAtendimento"];

                $this->EnviarEmail($nomeDe,$emailDe,$assunto,$message,$nomePara,$emailPara,"layout");
              }
            break;
            //Enviar e-mail para os usuários administrativos
            case 7:
              $this->sql = "select idparametro, valor from atendimentos_workflow_acoes_parametros where idacao = ".$preRequisito["idacao"]." and idparametro = 10";
              $parametro = $this->retornarLinha($this->sql);
              if($preRequisito["valor"]) {
                $emailsUsuarios = explode(";", $preRequisito["valor"]);
                foreach($emailsUsuarios as $email) {
                  $this->sql = "select nome, email from usuarios_adm where email = '".$email."'";
                  $usuario = $this->retornarLinha($this->sql);

                  //Texto do e-mail
                  $this->sql = "select idparametro, valor from atendimentos_workflow_acoes_parametros where idacao = ".$preRequisito["idacao"]." and idparametro = 11";
                  $parametro = $this->retornarLinha($this->sql);
                  if($usuario["email"] && $parametro["valor"]) {
                    $message = $parametro["valor"];
                    $nomePara = utf8_decode($usuario["nome"]);

                    $message = str_ireplace("[[usuario_adm][nome]]", $nomePara, $message);
                    $message = str_ireplace("[[atendimento][protocolo]]", $atendimento["protocolo"], $message);
                    $message = str_ireplace("[[atendimento][idatendimento]]", $atendimento["idatendimento"], $message);

                    $emailPara = $usuario["email"];
                    $assunto = utf8_decode("Modificação no atendimento #".$atendimento["protocolo"]);

                    $nomeDe = $GLOBALS["config"]["tituloEmpresa"];
                    $emailDe = $GLOBALS["config"]["emailSistemaAtendimento"];

                    $this->EnviarEmail($nomeDe,$emailDe,$assunto,$message,$nomePara,$emailPara,"layout");
                  }
                }
              }
            break;
          }
        }
      }
    }*/

    function ProcessaAcoes($de,$para){
      $this->sql = "select idrelacionamento from atendimentos_workflow_relacionamentos where idsituacao_de = ".$de." and idsituacao_para = ".$para." and ativo = 'S'";
      $relacionamento = $this->retornarLinha($this->sql);

      $this->sql = "select
                      awa.idopcao, awa.idacao
                    from
                      atendimentos_workflow_acoes awa
                    where
                      awa.idrelacionamento = ".$relacionamento["idrelacionamento"]." and
                      awa.ativo = 'S'";
      $this->limite = -1;
      $this->ordem_campo = "awa.idopcao";
      $acoes = $this->retornarLinhas();

      foreach($acoes as $acao) {
        foreach($GLOBALS['workflow_parametros_atendimentos'] as $op) {
          if($op['idopcao'] == $acao['idopcao'] && $op['tipo'] == "acao") {
            $preRequisitos[] = $acao;
          }
        }
      }

      if(count($preRequisitos) > 0) {
        $this->sql = "select * from atendimentos where idatendimento = ".intval($this->id);
        $atendimento = $this->retornarLinha($this->sql);
        foreach($preRequisitos as $ind => $preRequisito) {
          switch($preRequisito["idopcao"]) {
            //Enviar e-mail para o cliente
            case 3:
              $this->sql = "select nome, email from pessoas where idpessoa = ".$atendimento["idpessoa"];
              $pessoa = $this->retornarLinha($this->sql);

              //Texto do e-mail
              $this->sql = "select idparametro, valor from atendimentos_workflow_acoes_parametros where idacao = ".$preRequisito["idacao"]." and idparametro = 12";
              $parametro = $this->retornarLinha($this->sql);
              if($pessoa["email"] && $parametro["valor"]) {
                $message = $parametro["valor"];
                $nomePara = utf8_decode($pessoa["nome"]);

                $message = str_ireplace("[[cliente][nome]]", $nomePara, $message);
                $message = str_ireplace("[[atendimento][protocolo]]", $atendimento["protocolo"], $message);
                $message = str_ireplace("[[atendimento][idatendimento]]", $atendimento["idatendimento"], $message);

                $emailPara = $pessoa["email"];
                $assunto = "Modificação no atendimento #".$atendimento["protocolo"];

                $nomeDe = utf8_decode($GLOBALS["config"]["tituloEmpresa"]);
                $emailDe = $GLOBALS["config"]["emailSistemaAtendimento"];

                $this->EnviarEmail($nomeDe,$emailDe,$assunto,$message,$nomePara,$emailPara,"layout");
              }
            break;
            //Enviar e-mail para os usuários administrativos
            case 7:
              $this->sql = "select idparametro, valor from atendimentos_workflow_acoes_parametros where idacao = ".$preRequisito["idacao"]." and idparametro = 10";
              $parametro = $this->retornarLinha($this->sql);
              if($preRequisito["valor"]) {
                $emailsUsuarios = explode(";", $preRequisito["valor"]);
                foreach($emailsUsuarios as $email) {
                  $this->sql = "select nome, email from usuarios_adm where email = '".$email."'";
                  $usuario = $this->retornarLinha($this->sql);

                  //Texto do e-mail
                  $this->sql = "select idparametro, valor from atendimentos_workflow_acoes_parametros where idacao = ".$preRequisito["idacao"]." and idparametro = 11";
                  $parametro = $this->retornarLinha($this->sql);
                  if($usuario["email"] && $parametro["valor"]) {
                    $message = $parametro["valor"];
                    $nomePara = utf8_decode($usuario["nome"]);

                    $message = str_ireplace("[[usuario_adm][nome]]", $nomePara, $message);
                    $message = str_ireplace("[[atendimento][protocolo]]", $atendimento["protocolo"], $message);
                    $message = str_ireplace("[[atendimento][idatendimento]]", $atendimento["idatendimento"], $message);

                    $emailPara = $usuario["email"];
                    $assunto = "Modificação no atendimento #".$atendimento["protocolo"];

                    $nomeDe = $GLOBALS["config"]["tituloEmpresa"];
                    $emailDe = $GLOBALS["config"]["emailSistemaAtendimento"];

                    $this->EnviarEmail($nomeDe,$emailDe,$assunto,$message,$nomePara,$emailPara,"layout");
                  }
                }
              }
            break;
          }
        }
      }
    }

    function retornarRelacionamentosWorkflow($idsituacao) {
        $this->sql = "select idsituacao_para from atendimentos_workflow_relacionamentos where idsituacao_de = ".mysql_real_escape_string($idsituacao) . " and ativo = 'S' ";
        $this->limite = -1;
        $this->ordem_campo = "idsituacao_para";
        $this->groupby = "idsituacao_para";
        return $this->retornarLinhas();
    }

    function destacarUsuarios($idatendimento, $arrayUsuario) {
        foreach($arrayUsuario as $ind => $idusuario) {
            $this->sql = "select count(iddestaque) as total, iddestaque from atendimentos_destaques where idatendimento = '".intval($idatendimento)."' and idusuario = '".intval($idusuario)."'";
            $totalAss = $this->retornarLinha($this->sql);
            if($totalAss["total"] > 0) {
                $this->sql = "update atendimentos_destaques set ativo = 'S' where iddestaque = ".$totalAss["iddestaque"];
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $totalAss["iddestaque"];
            } else {
                $this->sql = "insert into atendimentos_destaques set ativo = 'S', data_cad = now(), idusuario = '".intval($idusuario)."', idatendimento = '".intval($idatendimento)."' ";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();
            }
            if($associar){

                $this->addHistorico($idatendimento, $this->idusuario, NULL,  false, false, $idusuario, false, "D");
                //$this->adicionarFeed($idatendimento, $this->idusuario, NULL, NULL, NULL, "D", NULL, NULL);
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 8;
                $this->monitora_onde = 52;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }

    function addHistorico($idatendimento, $idusuario = "NULL", $idpessoa = "NULL", $de, $para, $idusuarioConvidado, $idatendimentoClone, $tipo, $id = null) {

        //TIPO DE HISTORICO
        //E = Modificou assunto
        //ES = Modificou subassunto
        //D = Destacou
        //IP =  Modificou prioridade
        //IA =  Modificou proxima ação
        //CU = Convidou
        //CAO = Clonou
        //CAD = Clonado
        //S = Modificou Status
        //CH = Modificou checklists
        //RA = Respondeu
        //UNI = Modif
        //AOC = Adicionou opcao checklist
        //ROC = Removeu opcao checklist

        $this->sql = "insert atendimentos_historicos
                        set
                          idatendimento = ".intval($idatendimento).", ";
                         if ($idusuario)
                            $this->sql .= " idusuario = ".$idusuario.", ";
                         if ($idpessoa)
                            $this->sql .= " idpessoa = ".$idpessoa.", ";
        $this->sql .= " data_cad = NOW(),
                          tipo = '".$tipo."'";
        if($de) $this->sql .= ", de = '".$de."'";
        if($para) $this->sql .= ", para = '".$para."'";
        if($idusuarioConvidado) $this->sql .= ", idusuario_convidado = '".$idusuarioConvidado."'";
        if($idatendimentoClone) $this->sql .= ", idatendimento_clone = '".$idatendimentoClone."'";
        if($id) $this->sql .= ", id = '".$id."'";

        return $executa_monitora_assunto = $this->executaSql($this->sql);
    }

    function adicionarFeed($idatendimento, $idusuario = NULL, $idpessoa = NULL, $idimobiliaria = NULL, $idcorretor = NULL, $tipo, $de, $para) {

      if($idimobiliaria && $tipo != "CI"){
        $painel = "imobiliaria";
        $idusuario = $idimobiliaria;

        $sql = "select nome from usuarios_imobiliarias where idusuario = '".$idusuario."'";
        $usuario = $this->retornarLinha($sql);
      } elseif($idcorretor && $tipo != "CC"){
        $painel = "corretor";
        $idusuario = $idcorretor;

        $sql = "select nome from corretores where idcorretor = '".$idusuario."'";
        $usuario = $this->retornarLinha($sql);
      } elseif($idpessoa){
        $painel = "web";
        $idusuario = $idpessoa;

        $sql = "select nome from pessoas where idpessoa = '".$idusuario."'";
        $usuario = $this->retornarLinha($sql);
      } else {
        $painel = "gestor";
        $usuario["nome"] = "Construtor de vendas";
        if($idusuario) {
          $sql = "select nome from usuarios_adm where idusuario = '".$idusuario."'";
          $usuario = $this->retornarLinha($sql);
        } else {
          $idusuario = "NULL";
        }
      }

      switch ($tipo) {
        case "E":
          $this->sql = "select * from atendimentos_assuntos where idassunto = '".$de."'";
          $assuntoDe = $this->retornarLinha($this->sql);

          $this->sql = "select * from atendimentos_assuntos where idassunto = '".$para."'";
          $assuntoPara = $this->retornarLinha($this->sql);

          $descricao = "Encaminhou / Modificou o assunto.<br />";
          $descricao .= "De <span class=\"status\" style=\"background-color:#EEEEEE;color:#000000\" >".$assuntoDe["nome"]."</span> para <span class=\"status\" style=\"background-color:#EEEEEE;color:#000000\" >".$assuntoPara["nome"]."</span>";
        break;
        case "ES":
          $descricao = "Encaminhou / Modificou o subassunto.<br />";
          $descricao .= "De <span class=\"status\" style=\"background-color:#EEEEEE;color:#000000\" >";
          if ($de) {
            $this->sql = "select * from atendimentos_assuntos_subassuntos where idsubassunto = '".$de."'";
            $subassuntoDe = $this->retornarLinha($this->sql);
            $descricao .= $subassuntoDe["nome"]."</span>";
          } else {
            $descricao .= "--</span> ";
          }
          $descricao .= "Para <span class=\"status\" style=\"background-color:#EEEEEE;color:#000000\" >";
          if ($para) {
            $this->sql = "select * from atendimentos_assuntos_subassuntos where idsubassunto = '".$para."'";
            $subassuntoPara = $this->retornarLinha($this->sql);
            $descricao .= $subassuntoPara["nome"]."</span>";
          } else {
            $descricao .= "--</span>";
          }
        break;
        case "D":
          $descricao = "Destacou um atendimento.";
        break;
        case "IP":
          $descricao = "Modificou a prioridade.";
        break;
        case "IA":
          $descricao = "Modificou a data da proxima ação.";
        break;
        case "CU":
          $descricao = "Convidou um usuário.";
        break;
        case "CI":
          $descricao = "Convidou uma imobiliária.";
        break;
        case "CC":
          $descricao = "Convidou um corretor.";
        break;
        case "CAO":
          $descricao = "Clonou o atendimento.";
        break;
        case "CAD":
          $descricao = "É um atendimento clonado.";
        break;
        case "S":
          $descricao = "Modificou a situação.<br />";
          $this->sql = "select * from atendimentos_workflow where idsituacao = '".$para."'";
          $situacaoPara = $this->retornarLinha($this->sql);
          if($de){
            $this->sql = "select * from atendimentos_workflow where idsituacao = '".$de."'";
            $situacaoDe = $this->retornarLinha($this->sql);
            $descricao .= "De <span class=\"label\" style=\"background-color:#".$situacaoDe["cor_bg"]."; color:#".$situacaoDe["cor_nome"]."\">".$situacaoDe["nome"]."</span> para <span class=\"label\" style=\"background-color:#".$situacaoPara["cor_bg"]."; color:#".$situacaoPara["cor_nome"]."\">".$situacaoPara["nome"]."</span>.";
          } else {
            $descricao .= "Para <span class=\"label\" style=\"background-color:#".$situacaoPara["cor_bg"]."; color:#".$situacaoPara["cor_nome"]."\">".$situacaoPara["nome"]."</span>.";
          }
        break;
        case "A":
          $descricao = "Avaliou o atendimento.";
        break;
        case "CH":
          $descricao = "Marcou o checklist.";
        break;
        case "RA":
          $descricao = "Respondeu o atendimento.";
        break;
        case "ER":
          $descricao = "Editou uma resposta.";
        break;
        case "UNI":
          $descricao = "Modificou a unidade.";
        break;
        case "CL":
          $descricao = "Liberou o atendimento para o cliente.";
        break;
        case "CB":
          $descricao = "Bloqueou o atendimento para o cliente.";
        break;
      }

      $this->sql = "insert
                      feed
                    set
                      painel = '".$painel."',
                      tipo = 'atendimento',
                      id = '".$idatendimento."',
                      data_cad = now(),
                      idusuario = '".$idusuario."',
                      usuario = '".$usuario["nome"]."',
                      descricao = '".$descricao."'";

      if(!$de) $de = uniqid();
      if(!$para) $para = uniqid();

      if($de != $para) {
        $adiciona = $this->executaSql($this->sql);
      } else {
        $adiciona = true;
      }
      return $adiciona;
    }

    function alterarAssunto($idatendimento, $post) {
        $this->retorno = array();
        $erro = false;

        if ($post['idassunto']) {
            $this->sql = "select * from atendimentos where idatendimento = ".intval($idatendimento);
            $linhaAntiga = $this->retornarLinha($this->sql);

            $this->sql = "select * from atendimentos_assuntos where idassunto = ".$post['idassunto'];
            $assuntos = $this->retornarLinha($this->sql);

            if ($assuntos['subassunto_obrigatorio'] == 'S' && !$post['idsubassunto']) {
                $this->retorno['erros'][] = 'subassunto_vazio';
            }

            if (count($this->retorno['erros']) <= 0) {
                if (!$this->idpessoa) $this->idpessoa = $linhaAntiga['idpessoa'];

                $arrayCampos = array();
                if (!$post['idassunto'])$post['idassunto'] = "NULL";
                if (!$post['idsubassunto'])$post['idsubassunto'] = "NULL";

                if($linhaAntiga['idassunto'] != $post['idassunto'])
                    $arrayCampos[] = " idassunto = ".$post['idassunto'];

                if($linhaAntiga['idsubassunto'] != $post['idsubassunto'])
                     $arrayCampos[] = " idsubassunto = ".$post['idsubassunto'];

                $temCampos = (count($arrayCampos) > 0);

                if ($temCampos) {
                    $this->sql = "update atendimentos set ".implode(',',$arrayCampos)." where idatendimento = $idatendimento";
                    $executa = $this->executaSql($this->sql);
                    if (!$executa) {
                        $erro = true;
                    }

                    if ($linhaAntiga['idassunto'] != $post['idassunto']) {
                        $executa_monitora_assunto = $this->addHistorico($idatendimento, $this->idusuario, NULL,  $linhaAntiga['idassunto'], $post['idassunto'], false, false, "E");
                        //$this->adicionarFeed($idatendimento, $this->idusuario, NULL, NULL, NULL, "E", $linhaAntiga['idassunto'], $post['idassunto']);
                        if (!$executa_monitora_assunto) {
                            $erro = true;
                            $this->retorno["erros"][] = "erro_idassunto_historico";
                        }
                    }

                    if ($linhaAntiga['idsubassunto'] != $post['idsubassunto']) {
                        $executa_monitora_subassunto = $this->addHistorico($idatendimento, $this->idusuario, NULL,  $linhaAntiga['idsubassunto'], $post['idsubassunto'], false, false, "ES");
                        //$this->adicionarFeed($idatendimento, $this->idusuario, NULL, NULL, NULL, "ES", $linhaAntiga['idsubassunto'], $post['idsubassunto']);
                        if (!$executa_monitora_subassunto) {
                            $erro = true;
                            $this->retorno["erros"][] = "erro_idsubassunto_historico";
                        }
                    }

                    if ($erro) {
                        $this->retorno["erro"] = true;
                    } else {
                        $this->sql = "select * from atendimentos where idatendimento = '".intval($idatendimento)."'";
                        $linhaNova = $this->retornarLinha($this->sql);

                        $this->monitora_oque = 2;
                        $this->monitora_qual = $idatendimento;
                        $this->monitora_dadosantigos = $linhaAntiga;
                        $this->monitora_dadosnovos = $linhaNova;
                        $this->Monitora();
                        $this->retorno["sucesso"] = true;
                        $this->retorno["msg"] = "alterar_assunto_sucesso";
                    }
                } else {
                    $this->retorno["sucesso"] = true;
                    $this->retorno["msg"] = "msg_sem_atu";
                }
            }

        } else {
            $this->retorno['erro'] = true;
            $this->retorno['erros'][] = 'assunto_vazio';
            return $this->retorno;
        }

        return $this->retorno;
    }

    function alterarInformacoesGerenciais($idatendimento, $post) {
        if ($post["prioridade"] && $post["proxima_acao"]) {

            $this->sql = "select * from atendimentos where idatendimento = '".intval($idatendimento)."'";
            $linhaAntiga = $this->retornarLinha($this->sql);

            $proxima_acao_en = formataData($post['proxima_acao'], "en", 0);

            if ($linhaAntiga['prioridade'] != $post['prioridade'] || $linhaAntiga['proxima_acao'] != $proxima_acao_en) {
                $this->sql = "update atendimentos
                              set
                                prioridade = '".$post['prioridade']."',
                                proxima_acao = '".$proxima_acao_en."'
                              where idatendimento = $idatendimento ";
                $executa = $this->executaSql($this->sql);
                if (!$executa) $erro = true;
                if ($linhaAntiga['prioridade'] != $post['prioridade']) {
                    $executa_monitora_prioridade = $this->addHistorico($idatendimento, $this->idusuario, NULL,  $linhaAntiga['prioridade'], $post['prioridade'], false, false, "IP");
                    //$this->adicionarFeed($idatendimento, $this->idusuario, NULL, NULL, NULL, "IP", $linhaAntiga['prioridade'], $post['prioridade']);
                    if (!$executa_monitora_prioridade) $erro = true;
                }
                if ($linhaAntiga['proxima_acao'] != $proxima_acao_en) {
                    $executa_monitora_proxima_acao = $this->addHistorico($idatendimento, $this->idusuario, NULL,  $linhaAntiga['proxima_acao'], $proxima_acao_en, false, false,  "IA");
                    //$this->adicionarFeed($idatendimento, $this->idusuario, NULL, NULL, NULL, "IA", $linhaAntiga['proxima_acao'], $proxima_acao_en);
                    if (!$executa_monitora_proxima_acao) $erro = true;
                }

            }
            if ($erro) {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            } else {
                $this->sql = "select * from atendimentos where idatendimento = '".intval($idatendimento)."'";
                $linhaNova = $this->retornarLinha($this->sql);

                $this->monitora_oque = 2;
                $this->monitora_qual = $idatendimento;
                $this->monitora_dadosantigos = $linhaAntiga;
                $this->monitora_dadosnovos = $linhaNova;
                $this->Monitora();
                $this->retorno["sucesso"] = true;
            }

        } else {
            return false;
        }

        return $this->retorno;
    }

    function Clonar($idatendimento) {

        $this->retorno = array();

        //clona o atendimento
        $this->sql = "select * from atendimentos where idatendimento = ".intval($idatendimento);
        $clonar = $this->retornarLinha($this->sql);
        if($clonar) {
            if(!$clonar["idsubassunto"]) {
                $clonar["idsubassunto"] = "NULL";
            }

            if(!$clonar["idusuario"]) {
                $clonar["idusuario"] = "NULL";
            }

            if(!$clonar["idpessoa"]) {
                $clonar["idpessoa"] = "NULL";
            }

            if(!$clonar["idcurso"]) {
                $clonar["idcurso"] = "NULL";
            }

            if(!$clonar["idmatricula"]) {
                $clonar["idmatricula"] = "NULL";
            }

            $this->sql = "INSERT INTO
                            atendimentos
                          SET
                            idpessoa = ".$clonar["idpessoa"].",
                            idassunto = ".$clonar["idassunto"].",
                            idsubassunto = ".$clonar["idsubassunto"].",
                            idsituacao = ".$clonar["idsituacao"].",
                            idmatricula = ".$clonar["idmatricula"].",
                            idclone = ".$clonar["idatendimento"].",
                            data_cad = NOW(),
                            nome = '".$clonar["nome"]."',
                            descricao = '".$clonar["descricao"]."',
                            prioridade = '".$clonar["prioridade"]."'";
            if($clonar["proxima_acao"]) $this->sql .= ", proxima_acao = '".$clonar["proxima_acao"]."'";

            $salvar = $this->executaSql($this->sql);
            $idatendimentoClone = mysql_insert_id();

            $protocolo = date("ymd").str_pad(substr($idatendimentoClone, -4), 4, "0", STR_PAD_LEFT);
            $this->sql = "UPDATE atendimentos SET protocolo = ".$protocolo." WHERE idatendimento = ".$idatendimentoClone;
            $this->executaSql($this->sql);

            $this->addHistorico($idatendimento, $this->idusuario, NULL,  false, false, false, $idatendimentoClone, "CAO");
            //$this->adicionarFeed($idatendimento, $this->idusuario, NULL, NULL, NULL, "CAO", NULL, NULL);

            $this->addHistorico($idatendimentoClone, $this->idusuario, NULL, false, false, false, $idatendimento, "CAD");
            //$this->adicionarFeed($idatendimentoClone, $this->idusuario, NULL, NULL, NULL, "CAD", NULL, NULL);

            //clona as respostas
            $this->sql = "select * from atendimentos_respostas where idatendimento = ".intval($idatendimento)." and ativo = 'S'";
            $this->limite = -1;
            $this->ordem_campo = "idresposta";
            $this->ordem = "asc";
            $this->groupby = "idresposta";
            $clonarRespostas = $this->retornarLinhas();

            foreach($clonarRespostas as $ind => $resposta) {
                if(!$resposta["idusuario"]) {
                    $resposta["idusuario"] = "NULL";
                }
                if(!$resposta["idpessoa"]) {
                    $resposta["idpessoa"] = "NULL";
                }
                if(!$resposta["idresposta_automatica"]) {
                    $resposta["idresposta_automatica"] = "NULL";
                }

                $this->sql = "INSERT INTO
                                atendimentos_respostas
                              SET
                                idatendimento = ".$idatendimentoClone.",
                                idusuario = ".$resposta["idusuario"].",
                                idpessoa = ".$resposta["idpessoa"].",
                                idresposta_automatica = ".$resposta["idresposta_automatica"].",
                                data_cad = NOW(),
                                resposta = '".$resposta["resposta"]."',
                                publica = '".$resposta["publica"]."'";
                $salvar = $this->executaSql($this->sql);
                $idrespostaClone = mysql_insert_id();
                if($salvar){
                    //clona os arquivos das respostas
                    $this->sql = "select * from atendimentos_arquivos where idresposta = ".$resposta["idresposta"]." and ativo = 'S'";
                    $this->limite = -1;
                    $this->ordem_campo = "idarquivo";
                    $this->ordem = "asc";
                    $this->groupby = "idarquivo";
                    $clonarRespArq = $this->retornarLinhas();
                    foreach($clonarRespArq as $ind => $respostaArq) {
                        $this->sql = "INSERT INTO
                                        atendimentos_arquivos
                                      SET
                                        idresposta = ".$idrespostaClone.",
                                        data_cad = NOW(),
                                        nome = '".$respostaArq["nome"]."',
                                        servidor = '".$respostaArq["servidor"]."',
                                        tipo = '".$respostaArq["tipo"]."',
                                        tamanho = ".$respostaArq["tamanho"]."";
                        $salvar = $this->executaSql($this->sql);
                    }
                }
            }

            $this->monitora_oque = 7;
            $this->monitora_qual = $idatendimento;
            $this->Monitora();

            $this->monitora_oque = 1;
            $this->monitora_qual = $idatendimentoClone;
            $this->Monitora();

            $this->retorno["id"] = $idatendimentoClone;
            $this->retorno["sucesso"] = true;
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;

    }

    function listarUsuariosNaoVisualizam($idatendimento) {
        $this->sql = "select
                        usu.idusuario as 'key', usu.nome as value
                      from
                        usuarios_adm as usu
                        left outer join
                        (SELECT ua.idusuario
                        FROM atendimentos ate
                        LEFT JOIN atendimentos_assuntos_subassuntos_grupos aasg ON (ate.idsubassunto = aasg.idsubassunto AND aasg.ativo = 'S')
                        LEFT JOIN atendimentos_assuntos_grupos aag ON (ate.idassunto = aag.idassunto AND aag.ativo = 'S')
                        INNER JOIN grupos_usuarios_adm_usuarios guau ON ( (guau.idgrupo = aasg.idgrupo OR guau.idgrupo = aag.idgrupo) AND guau.ativo = 'S' )

                        INNER JOIN usuarios_adm ua ON ( ua.idusuario = guau.idusuario )
                        WHERE ate.ativo = 'S' and ate.idatendimento = '".$idatendimento."'
                        group by ua.idusuario
                        ) as usu_ate on (usu.idusuario = usu_ate.idusuario)
                      where
                        usu.ativo='S' and usu.nome like '%".$_GET["tag"]."%'";

        $this->limite = -1;
        $this->ordem_campo = "usu.nome";
        $this->groupby = "usu.nome";
        $dados = $this->retornarLinhas();

        return json_encode($dados);

    }

    function listarUsuariosNaoDestacados($idatendimento) {

        $this->sql = "select
                        usu.idusuario as 'key', usu.nome as value
                      from
                        usuarios_adm as usu
                        left outer join
                        (SELECT ua.idusuario, ua.nome FROM atendimentos ate
                        LEFT JOIN atendimentos_assuntos_subassuntos_grupos aasg ON (ate.idsubassunto = aasg.idsubassunto AND aasg.ativo = 'S')
                        LEFT JOIN atendimentos_assuntos_grupos aag ON (ate.idassunto = aag.idassunto AND aag.ativo = 'S')
                        LEFT JOIN grupos_usuarios_adm_usuarios guau ON ( (guau.idgrupo = aasg.idgrupo OR guau.idgrupo = aag.idgrupo) AND guau.ativo = 'S' )
                        LEFT JOIN atendimentos_destaques ad ON (ate.idatendimento = ad.idatendimento AND ad.idusuario = guau.idusuario AND ad.ativo = 'S')
                        INNER JOIN usuarios_adm ua ON ( guau.idusuario = ua.idusuario )
                        WHERE ate.ativo = 'S' AND ate.idatendimento = '".$idatendimento."' AND ad.iddestaque IS NULL
                        ) as usu_ate on (usu.idusuario = usu_ate.idusuario)
                      where
                        usu_ate.idusuario IS NOT NULL and usu.nome like '%".$_GET["tag"]."%'";

        $this->limite = -1;
        $this->ordem_campo = "usu.nome";
        $this->groupby = "usu.nome";
        $dados = $this->retornarLinhas();

        return json_encode($dados);

    }

    function listarRespostasAutomaticas($idresposta) {
        $this->sql = "select * from atendimentos_respostas_automaticas where ativo='S' and idresposta = $idresposta ";
        $dados = $this->retornarLinha($this->sql);
        if ($dados) {
            $info['sucesso'] = true;
            $info['resposta'] = $dados['resposta'];
        } else {
            $info['sucesso'] = false;
        }
        return json_encode($info);

    }

    function convidarImobiliaria($idatendimento, $imobiliaria) {

        $this->sql = "update atendimentos set idimobiliaria = '".$imobiliaria."', idcorretor = NULL where idatendimento = '".$idatendimento."'";
        $associar = $this->executaSql($this->sql);
        $this->monitora_qual = $idatendimento;

        if($associar){
            $this->addHistorico($idatendimento, $this->idusuario, NULL,  false, false, $idusuario, false, "CI", $imobiliaria, false);
            //$this->adicionarFeed($idatendimento, $this->idusuario, NULL, NULL, NULL, "CI", NULL, NULL);

            $this->retorno["sucesso"] = true;
            $this->monitora_oque = 33;
            $this->monitora_onde = 52;
            $this->Monitora();
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }
        return $this->retorno;
    }

    function convidarCorretor($idatendimento, $corretor) {

        $this->sql = "update atendimentos set idcorretor = '".$corretor."' where idatendimento = '".$idatendimento."'";
        $associar = $this->executaSql($this->sql);
        $this->monitora_qual = $idatendimento;

        if($associar){
            $this->addHistorico($idatendimento, $this->idusuario, NULL,  false, false, $idusuario, false, "CC", false, $corretor);
            //$this->adicionarFeed($idatendimento, $this->idusuario, NULL, NULL, NULL, "CC", NULL, NULL);

            $this->retorno["sucesso"] = true;
            $this->monitora_oque = 34;
            $this->monitora_onde = 52;
            $this->Monitora();
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }

    function avaliar($avaliacao)
    {
        if (verificaPermissaoAcesso(false)) {
            if(!in_array($avaliacao,array('1','2','3','4','5'))){
               $info['sucesso'] = false;
               $info['avaliar'] = $avaliar;
               return json_encode($info);
            }

            $this->sql = "select * from atendimentos where idatendimento = '".intval($this->id)."'";
            $linhaAntiga = $this->retornarLinha($this->sql);

            $this->sql = "update atendimentos set avaliacao = $avaliacao where idatendimento='".intval($this->id)."' ";
            $executa = $this->executaSql($this->sql);

            $info = array();
            if($executa){
               $this->addHistorico($this->id, $this->idusuario, $this->idpessoa, $linhaAntiga['avaliacao'], $avaliacao, false, false, "A");
               //$this->adicionarFeed($this->id, $this->idusuario, $this->idpessoa, NULL, NULL, "A", $linhaAntiga['avaliacao'], $avaliacao);

               $this->monitora_oque = 8;
               $this->monitora_qual = $this->id;
               $this->Monitora();
               $info['sucesso'] = true;
               $info['avaliar'] = $avaliacao;
            } else {
               $info['sucesso'] = false;
               $info['avaliar'] = $avaliacao;
            }
            return json_encode($info);
        } else {
            $info['erro_json'] = "sem_permissao";
            return json_encode($info);
        }
    }

    function retornarHistorico() {
        //TIPO DE HISTORICO
        //E = Modificou assunto
        //ES = Modificou subassunto
        //D = Destacou
        //IP =  Modificou prioridade
        //IA =  Modificou proxima ação
        //CU = Convidou
        //CAO = Clonou
        //CAD = Clonado
        //S = Modificou Status
        //CH = Modificou checklists
        //RA = Respondeu
        //UNI = Modif
        //AOC = Adicionou opcao checklist
        //ROC = Removeu opcao checklist

        $this->sql = "SELECT
                        ah.idhistorico,
                        ah.idatendimento,
                        ah.data_cad,
                        ah.tipo,
                        ah.de,
                        ah.para,
                        u.nome AS usuario,
                        p.nome AS cliente,
                        aad.nome AS assunto_de,
                        aap.nome AS assunto_para,
                        asd.nome AS subassunto_de,
                        asp.nome AS subassunto_para,
                        uc.nome AS usuario_convidado,
                        ac.protocolo AS protocolo_clone,
                        awd.nome AS status_de,
                        awp.nome AS status_para,
                        awd.cor_bg AS cor_de,
                        awp.cor_bg AS cor_para,
                        CONCAT(co.nome, ' (', che.nome, ')') as opcao
                      FROM
                        atendimentos_historicos ah
                        INNER JOIN atendimentos a ON (ah.idatendimento = a.idatendimento)
                        LEFT OUTER JOIN atendimentos_assuntos aad ON (ah.de = aad.idassunto) AND (ah.tipo = 'E')
                        LEFT OUTER JOIN atendimentos_assuntos aap ON (ah.para = aap.idassunto) AND (ah.tipo = 'E')
                        LEFT OUTER JOIN atendimentos_assuntos_subassuntos asd ON (ah.de = asd.idsubassunto) AND (ah.tipo = 'ES')
                        LEFT OUTER JOIN atendimentos_assuntos_subassuntos asp ON (ah.para = asp.idsubassunto) AND (ah.tipo = 'ES')
                        LEFT OUTER JOIN usuarios_adm uc ON (ah.idusuario_convidado = uc.idusuario) AND (ah.tipo = 'CU')
                        LEFT OUTER JOIN atendimentos ac ON (ah.idatendimento_clone = ac.idatendimento) AND ((ah.tipo = 'CAO') OR (ah.tipo = 'CAD'))
                        LEFT OUTER JOIN atendimentos_workflow awd ON (ah.de = awd.idsituacao) AND (ah.tipo = 'S')
                        LEFT OUTER JOIN atendimentos_workflow awp ON (ah.para = awp.idsituacao) AND (ah.tipo = 'S')
                        LEFT OUTER JOIN usuarios_adm u ON (ah.idusuario = u.idusuario)
                        LEFT OUTER JOIN pessoas p ON (ah.idpessoa = p.idpessoa)
                        LEFT OUTER JOIN checklists_opcoes co ON (ah.id = co.idopcao)
                        LEFT OUTER JOIN checklists che ON (co.idchecklist = che.idchecklist)
                      WHERE
                        ah.idatendimento = ".$this->id;

        $this->limite = -1;
        $this->ordem_campo = "ah.data_cad";
        $this->ordem = "asc";
        $this->groupby = "ah.idhistorico";
        return $this->retornarLinhas();
    }

    function verificaEditarMensagem($idatendimento, $idresposta) {
        $this->retorno = array();

        $this->sql = "select idresposta, idusuario, idpessoa from atendimentos_respostas where idatendimento = ".intval($idatendimento)." ";
            if ($idresposta)
                $this->sql .= " and idresposta = ".intval($idresposta)." ";
        $this->sql .= " order by idresposta desc limit 1";
        $resposta = $this->retornarLinha($this->sql);

        if($resposta["idusuario"] && !$resposta["idpessoa"] && $resposta["idresposta"] == $idresposta) {
            $this->retorno["sucesso"] = true;
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = "proibido_editar";
        }

        return $this->retorno;
    }

    function editarMensagem($idatendimento, $idresposta, $post, $arquivos, $erros = NULL) {
        $this->sql = "select * from atendimentos_respostas where idresposta = ".intval($idresposta);
        $linhaAntiga = $this->retornarLinha($this->sql);

        $permissoes = 'jpg|jpeg|gif|png|bmp|zip|rar|tar|gz|doc|docx|xls|xlsx|ppt|pptx|pps|ppsx|txt|pdf';
        $campo = array("pasta" => "atendimentos_arquivos");

        $existe_arquivos = false;
        foreach ($arquivos['arquivo']['name'] as $ind => $arq)
            if ($arq)
                $existe_arquivos = true;

        if ($existe_arquivos) {
            foreach ($arquivos['arquivo']['name'] as $ind => $arquivo) {
                $file['name'] = $arquivos['arquivo']['name'][$ind];
                $file['tmp_name'] = $arquivos['arquivo']['tmp_name'][$ind];
                $file['size'] = $arquivos['arquivo']['size'][$ind];

                    unset($nome_servidor);

                    $file_aux['name'] = $file;
                    $validacao_tamanho = $this->ValidarArquivo($file_aux['name']);
                    if($validacao_tamanho) {
                        $this->retorno["erro"] = true;
                        $this->retorno["erros"][] = $validacao_tamanho;
                        return $this->retorno;
                    }
            }
        }

        $erro = false;
        if ($idresposta) {
            $this->sql = "update atendimentos_respostas set resposta = '".mysql_real_escape_string($post['resposta'])."' ";
            if ($post['publica']) {
              $sqlDataInicio = "select data_cad from atendimentos_respostas where idatendimento = ".intval($idatendimento)." and idusuario is not null and publica = 'S' and data_cad <= '".$linhaAntiga["data_cad"]."' and idresposta <> ".$linhaAntiga["idresposta"]." order by data_cad desc limit 1";
              $dataInicio = $this->retornarLinha($sqlDataInicio);
              if(!$dataInicio) {
                $sqlAtendimento = "select data_cad from atendimentos where idatendimento = ".intval($idatendimento);
                $atendimentoDataCad = $this->retornarLinha($sqlAtendimento);
                $dataInicio["data_cad"] = $atendimentoDataCad["data_cad"];
              }
              $tempo = dataDiferenca($dataInicio["data_cad"], $linhaAntiga["data_cad"], "I");

              $this->sql .= ", publica = 'S', tempo_resposta = ".$tempo;
            } else {
              $this->sql .= ", publica = 'N', tempo_resposta = NULL";
            }
            $this->sql .= " where idresposta = ".intval($idresposta);

            $associar = $this->executaSql($this->sql);
            if (!$associar) $erro = true;
            $this->monitora_qual = $idresposta;

            if ($this->monitora_qual && $existe_arquivos) {

                foreach ($arquivos['arquivo']['name'] as $ind => $arquivo) {
                    $file['name'] = $arquivos['arquivo']['name'][$ind];
                    $file['tmp_name'] = $arquivos['arquivo']['tmp_name'][$ind];
                    $file['size'] = $arquivos['arquivo']['size'][$ind];

                    unset($nome_servidor);

                    $file_aux['name'] = $file;
                    $validacao_tamanho = $this->ValidarArquivo($file_aux['name']);
                    if($validacao_tamanho) {
                        $this->retorno["erro"] = true;
                        $this->retorno["erros"][] = $validacao_tamanho;
                        return $this->retorno;
                    }

                    $nome_servidor = $this->uploadFile($file, $campo);

                    if ($nome_servidor) {
                        $sql = "insert into atendimentos_arquivos set
                              idresposta = '".$idresposta."',
                              ativo = 'S',
                              data_cad = NOW(),
                              nome = '".$arquivos['arquivo']['name'][$ind]."',
                              tipo = '".$arquivos['arquivo']['type'][$ind]."',
                              tamanho = '".$arquivos['arquivo']['size'][$ind]."',
                              servidor = '".$nome_servidor."' ";
                        $query_arquivo = $this->executaSql($sql);
                        $idarquivo = mysql_insert_id();
                        if (!$query_arquivo) {
                            $erro = true;
                        } else {
                            $this->monitora_onde = 56;
                            $this->monitora_oque = 18;
                            $this->monitora_qual = $idarquivo;
                            $this->Monitora();
                        }
                    }
                }

            }

            if ($post['publica']) {
              $this->Set("idusuario",$usuario["idusuario"]);
              $this->Set("id",intval($idatendimento));
              $this->Set("campos","p.*, ass.nome as assunto, sub.nome as subassunto, aw.nome as situacao, p.nome as cliente, ate.protocolo, ate.idatendimento, ate.data_cad as data_cad_atendimento");
              $linha = $this->Retornar();

              $sqlTempoMedio = "select avg(tempo_resposta) as tempo_medio from atendimentos_respostas where idatendimento = ".intval($idatendimento)." and idusuario is not null and publica = 'S' order by data_cad desc limit 1";
              $tempoMedio = $this->retornarLinha($sqlTempoMedio);
              $sqlAtualizaTempoMedio = "update atendimentos set tempo_resposta = ".floor($tempoMedio["tempo_medio"])." where idatendimento = '".$idatendimento."'";
              $this->executaSql($sqlAtualizaTempoMedio);

              $nomePara = utf8_decode($linha["cliente"]);
              $message  = "Ol&aacute; <strong>".$nomePara."</strong>,
                          <br /><br />
                          Seu atendimento #".$linha["protocolo"]." acaba de ser respondido, para consultar, <a href=\"http://".$_SERVER["SERVER_NAME"]."/web/relacionamento/atendimentos/".$linha["idatendimento"]."/administrar\">clique aqui</a>.
                          <br /><br />";

              $emailPara = $linha["email"];
              $assunto = utf8_decode("Seu atendimento foi respondido");

              $nomeDe = $GLOBALS["config"]["tituloEmpresa"];
              $emailDe = $GLOBALS["config"]["emailSistemaAtendimento"];

              $this->EnviarEmail($nomeDe,$emailDe,$assunto,$message,$nomePara,$emailPara,"layout");

            }

        }

        if( !$erro ){
            $this->addHistorico($idatendimento, $this->idusuario, NULL,  false, false, false, false, "ER");
            //$this->adicionarFeed($idatendimento, $this->idusuario, NULL, NULL, NULL, "ER", NULL, NULL);

            $this->sql = "select * from atendimentos_respostas where idresposta = ".intval($idresposta);
            $linhaNova = $this->retornarLinha($this->sql);

            $this->retorno["sucesso"] = true;
            $this->monitora_onde = 56;
            $this->monitora_oque = 2;
            $this->monitora_qual = $idresposta;
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->Monitora();
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }

    function retornarChecklists($idatendimento) {
        $sql = "select c.nome as checklist, c.idchecklist, co.idopcao, co.nome, acom.idmarcada from atendimentos a
                      left join atendimentos_assuntos aa on a.idassunto = aa.idassunto
                      left join atendimentos_assuntos_subassuntos aas on a.idsubassunto = aas.idsubassunto
                      left join checklists c on (c.idchecklist = aa.idchecklist or c.idchecklist = aas.idchecklist)
                      left join checklists_opcoes co on c.idchecklist = co.idchecklist
                      left join atendimentos_checklists_opcoes_marcados acom on (acom.idatendimento = a.idatendimento
                                                                                 and acom.idchecklist = c.idchecklist and acom.idopcao = co.idopcao and acom.ativo = 'S')
                      where c.ativo = 'S' and c.ativo_painel = 'S' and co.ativo = 'S' and a.idatendimento = ".mysql_real_escape_string($idatendimento);
        $sql .= " group by co.idopcao";

        $query = $this->executaSql($sql);
        while ($linha = mysql_fetch_assoc($query)) {
            $retorno[$linha['idchecklist']][] = $linha;
            $retorno[$linha['idchecklist']]['nome'] = $linha['checklist'];
        }

        return $retorno;
    }

    function responderChecklist($idatendimento, $arrayChecklist) {
        $this->sql = "select idmarcada, idopcao from atendimentos_checklists_opcoes_marcados where idatendimento = ".intval($idatendimento)." and ativo = 'S' ";
        $this->limite = -1;
        $antigos = $this->retornarLinhas();

        $array_nao_apagar = array();
        $erro = false;
        if ($arrayChecklist) {
          foreach($arrayChecklist as $opcao) {
              $array = explode('|',$opcao);

              $this->sql = "select count(idmarcada) as total, idmarcada, ativo from atendimentos_checklists_opcoes_marcados where idatendimento = '".intval($idatendimento)."' and idchecklist = '".intval($array[0])."' and idopcao = '".intval($array[1])."' ";
              $totalAss = $this->retornarLinha($this->sql);
              if($totalAss["total"] > 0) {
                  if ($totalAss['ativo'] == 'N') {
                      $this->sql = "update atendimentos_checklists_opcoes_marcados set ativo = 'S' where idmarcada = ".$totalAss["idmarcada"];
                      $associar = $this->executaSql($this->sql);
                      if (!$associar) $erro = true;
                      else {
                        $this->addHistorico($idatendimento, $this->idusuario, NULL, NULL, NULL, NULL, NULL, "AOC", NULL, NULL, intval($array[1]));
                      }
                      $this->monitora_qual = $totalAss["idmarcada"];
                  }
                  $array_nao_apagar[] = $totalAss["idmarcada"];
              } else {
                  $this->sql = "insert into atendimentos_checklists_opcoes_marcados set ativo = 'S', data_cad = now(), idatendimento = '".intval($idatendimento)."', idchecklist = '".intval($array[0])."', idopcao = '".intval($array[1])."' ";
                  $associar = $this->executaSql($this->sql);
                  if (!$associar) $erro = true;
                  else {
                    $this->monitora_qual = mysql_insert_id();
                    $this->addHistorico($idatendimento, $this->idusuario, null, null, null, null, null, "AOC", null, null, intval($array[1]));
                  }
                  $array_nao_apagar[] = $this->monitora_qual;
              }

              if ($associar && $this->monitora_qual) {
                $this->monitora_oque = 5;
                $this->monitora_onde = 86;
                $this->Monitora();
              } else {
                  $this->retorno["erro"] = true;
                  $this->retorno["erros"][] = $this->sql;
                  $this->retorno["erros"][] = mysql_error();
              }
          }
        }
        if (!$array_nao_apagar) $array_nao_apagar[0] = 0;

        foreach($antigos as $opcao) {
            if(!in_array($opcao['idmarcada'], $array_nao_apagar)) {
                $this->sql = "update atendimentos_checklists_opcoes_marcados set ativo = 'N' where idmarcada = '".$opcao['idmarcada']."' and idatendimento = '".intval($idatendimento)."' ";
                $remover = $this->executaSql($this->sql);
                if($remover)
                    $this->addHistorico($idatendimento, $this->idusuario, null, null, null, null, null, "ROC", null, null, $opcao['idopcao']);
                else
                    $erro = true;
            }
        }

        $this->sql = "select idmarcada from atendimentos_checklists_opcoes_marcados where idmarcada in(".implode(',',$array_nao_apagar).") and idatendimento = '".intval($idatendimento)."' ";
        $this->limite = -1;
        $novos = $this->retornarLinhas();

        //Monitora desmarcados
        $a = array(); $n = array();
        foreach ($antigos as $antigo) $a[] = $antigo['idmarcada'];
        foreach ($novos as $novo) $n[] = $novo['idmarcada'];
        $arr_dif = array_diff($a,$n);

        foreach ($arr_dif as $id) {
            $this->monitora_qual = $id;
            $this->monitora_oque = 6;
            $this->monitora_onde = 86;
            $this->Monitora();
        }

        if( ($arrayChecklist && !$erro) || (!$arrayChecklist && $remover) ){
            $this->addHistorico($idatendimento, $this->idusuario, $this->idpessoa,  false, false, false, false, "CH");
            //$this->adicionarFeed($idatendimento, $this->idusuario, $this->idpessoa, NULL, NULL, "CH", NULL, NULL);
            $this->retorno["sucesso"] = true;
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }

    function retornarNomeCidade($idcidade) {
        $sql = "select nome from cidades where idcidade = '".$idcidade."' ";
        $linha = $this->retornarLinha($sql);
        return $linha['nome'];
    }

    function retornarNomeEstado($idestado) {
        $sql = "select nome from estados where idestado = '".$idestado."' ";
        $linha = $this->retornarLinha($sql);
        return $linha['nome'];
    }

    function responderAtendimento($idatendimento, $post, $arquivos, $erros = NULL)
    {
        if (verificaPermissaoAcesso(true)) {
            $sql_ate = "select idsituacao from atendimentos where idatendimento = '".$idatendimento."' ";
            $ate = $this->retornarLinha($sql_ate);

            $sql_situacao = "select idsituacao, fim from atendimentos_workflow where idsituacao = '".$ate['idsituacao']."' ";
            $chamado_situacao = $this->retornarLinha($sql_situacao);

            if ($chamado_situacao['fim'] == 'S') {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = 'chamado_finalizado';
                return $this->retorno;
            }

            $permissoes = 'jpg|jpeg|gif|png|bmp|zip|rar|tar|gz|doc|docx|xls|xlsx|ppt|pptx|pps|ppsx|txt|pdf';
            $campo = array("pasta" => "atendimentos_arquivos");

            $existe_arquivos = false;
            foreach ($arquivos['arquivos']['name'] as $ind => $arq)
                if ($arq)
                    $existe_arquivos = true;

            if ($existe_arquivos) {
                foreach ($arquivos['arquivos']['name'] as $ind => $arquivo) {
                    $file['name'] = $arquivos['arquivos']['name'][$ind];
                    $file['tmp_name'] = $arquivos['arquivos']['tmp_name'][$ind];
                    $file['size'] = $arquivos['arquivos']['size'][$ind];

                    unset($nome_servidor);

                    $file_aux['name'] = $file;
                    $validacao_tamanho = $this->ValidarArquivo($file_aux['name']);
                    if($validacao_tamanho) {
                        $this->retorno["erro"] = true;
                        $this->retorno["erros"][] = $validacao_tamanho;
                        return $this->retorno;
                    }
                }
            }

            $erro = false;
            if ($idatendimento) {
                $this->Set("idusuario",$usuario["idusuario"]);
                $this->Set("id",intval($idatendimento));
                $this->Set("campos","p.*, ass.nome as assunto, sub.nome as subassunto, aw.nome as situacao, p.nome as cliente, ate.protocolo, ate.data_cad as data_cad_atendimento");
                $linha = $this->Retornar();
                $dataCad = date("Y-m-d H:i:s");
                $this->sql = "insert into atendimentos_respostas set idatendimento = '".intval($idatendimento)."', data_cad = '".$dataCad."' ";
                if(!$this->idpessoa){
                    $this->sql .= ", idusuario = '".intval($this->idusuario)."' ";
                    if ($post['publica']) {
                        $sqlDataInicio = "select data_cad from atendimentos_respostas where idatendimento = ".intval($idatendimento)." and idusuario is not null and publica = 'S' order by data_cad desc limit 1";
                        $dataInicio = $this->retornarLinha($sqlDataInicio);
                        if(!$dataInicio) {
                          $dataInicio["data_cad"] = $linha["data_cad_atendimento"];
                        }
                        $tempo = dataDiferenca($dataInicio["data_cad"], $dataCad, "I");
                        $this->sql .= ", publica = 'S', tempo_resposta = ".$tempo;

                    } else {
                      $this->sql .= ", publica = 'N' ";
                    }
                }else{
                    $this->sql .= ", idpessoa = '".intval($this->idpessoa)."', publica = 'S' ";
                }

                if ($post['resposta_atendimento_auto']) {

                    $post['resposta_atendimento'] = str_ireplace("[[NOME]]",$linha['cliente'],$post['resposta_atendimento']);
                    $post['resposta_atendimento'] = str_ireplace("[[RG]]",$linha['rg'],$post['resposta_atendimento']);
                    $post['resposta_atendimento'] = str_ireplace("[[ENDERECO]]",$linha['endereco'],$post['resposta_atendimento']);
                    $post['resposta_atendimento'] = str_ireplace("[[CEP]]",$linha['cep'],$post['resposta_atendimento']);
                    $post['resposta_atendimento'] = str_ireplace("[[COMPLEMENTO]]",$linha['complemento'],$post['resposta_atendimento']);
                    $post['resposta_atendimento'] = str_ireplace("[[NASCIMENTO]]",formataData($linha['data_nasc'], "br", 0),$post['resposta_atendimento']);
                    $post['resposta_atendimento'] = str_ireplace("[[CPF]]",$linha['documento'],$post['resposta_atendimento']);
                    $post['resposta_atendimento'] = str_ireplace("[[ESTADOCIVIL]]",$GLOBALS['estadocivil'][$this->config["idioma_padrao"]][$linha['estado_civil']],$post['resposta_atendimento']);
                    $post['resposta_atendimento'] = str_ireplace("[[BAIRRO]]",$linha['bairro'],$post['resposta_atendimento']);
                    $post['resposta_atendimento'] = str_ireplace("[[NUMERO]]",$linha['numero'],$post['resposta_atendimento']);
                    $post['resposta_atendimento'] = str_ireplace("[[CIDADE]]",$this->retornarNomeCidade($linha['idcidade']),$post['resposta_atendimento']);
                    $post['resposta_atendimento'] = str_ireplace("[[ESTADO]]",$this->retornarNomeEstado($linha['idestado']),$post['resposta_atendimento']);

                    $this->sql .= ", idresposta_automatica = '".$post['resposta_atendimento_auto']."' ";
                    $this->sql .= ", resposta = '".mysql_real_escape_string($post['resposta_atendimento'])."' ";
                }
                else if ($post['resposta_atendimento'])     $this->sql .= ", resposta = '".mysql_real_escape_string($post['resposta_atendimento'])."' ";

                $associar = $this->executaSql($this->sql);
                $idresposta = mysql_insert_id();
                $this->monitora_qual = $idresposta;
                if (!$associar)
                    $erro = true;
                else {

                    $this->sql = "SELECT * FROM atendimentos WHERE idatendimento = ".$idatendimento;
                    $linhaAntiga = $this->retornarLinha($this->sql);

                    if ($this->idpessoa) {
                        $sql_resposta_cliente = "select idsituacao from atendimentos_workflow where respondido_cliente = 'S' and ativo = 'S' ";
                        $resposta_cliente = $this->retornarLinha($sql_resposta_cliente);

                        if($resposta_cliente['idsituacao']) {
                            $sql_atualiza = "update atendimentos set idsituacao = '".$resposta_cliente['idsituacao']."' where idatendimento = '".$idatendimento."' ";
                            $atualizar = $this->executaSql($sql_atualiza);
                            if($atualizar) {
                              $this->addHistorico($idatendimento, NULL, intval($this->idpessoa), $linhaAntiga['idsituacao'], $resposta_cliente['idsituacao'], false, false, "S");
                              //$this->adicionarFeed($idatendimento, NULL, $this->idpessoa, NULL, NULL, "S", $linhaAntiga['idsituacao'], $resposta_cliente['idsituacao']);
                            } else {
                                $erro = true;
                            }
                        }
                    } elseif($post["marcar_respondido"]) {
                        $sql_resposta_gestor = "select idsituacao from atendimentos_workflow where respondido_gestor = 'S' and ativo = 'S' ";
                        $resposta_gestor = $this->retornarLinha($sql_resposta_gestor);

                        if($resposta_gestor['idsituacao']) {
                            $sql_atualiza = "update atendimentos set idsituacao = '".$resposta_gestor['idsituacao']."' where idatendimento = '".$idatendimento."' ";
                            $atualizar = $this->executaSql($sql_atualiza);

                            if($atualizar) {
                              $this->addHistorico($idatendimento, intval($this->idusuario), NULL, $linhaAntiga['idsituacao'], $resposta_gestor['idsituacao'], false, false, "S");
                              //$this->adicionarFeed($idatendimento, $this->idusuario, NULL, NULL, NULL, "S", $linhaAntiga['idsituacao'], $resposta_gestor['idsituacao']);
                            } else {
                                $erro = true;
                            }
                        }
                    }

                    if ($post['publica']) {
                      $sqlTempoMedio = "select avg(tempo_resposta) as tempo_medio from atendimentos_respostas where idatendimento = ".intval($idatendimento)." and idusuario is not null and publica = 'S' order by data_cad desc limit 1";
                      $tempoMedio = $this->retornarLinha($sqlTempoMedio);
                      $sqlAtualizaTempoMedio = "update atendimentos set tempo_resposta = ".floor($tempoMedio["tempo_medio"])." where idatendimento = '".$idatendimento."'";
                      $this->executaSql($sqlAtualizaTempoMedio);

                      $this->Set("idusuario",$usuario["idusuario"]);
                      $this->Set("id",intval($idatendimento));
                      $this->Set("campos","p.*, ass.nome as assunto, sub.nome as subassunto, aw.nome as situacao, p.nome as cliente, ate.protocolo, ate.idatendimento");
                      $linha = $this->Retornar();

                      //$nomePara = utf8_decode($linha["cliente"]);
                      $nomePara = $linha["cliente"];
                      $message  = "Ol&aacute; <strong>".$nomePara."</strong>,
                                  <br /><br />
                                  Seu atendimento #".$linha["protocolo"]." acaba de ser respondido, para consultar, <a href=\"http://".$_SERVER["SERVER_NAME"]."/aluno/secretaria/atendimento\">clique aqui</a>.
                                  <br /><br />";

                      $emailPara = $linha["email"];
                      $assunto = utf8_decode("Seu atendimento foi respondido");

                      $nomeDe = $GLOBALS["config"]["tituloEmpresa"];
                      $emailDe = $GLOBALS["config"]["emailSistemaAtendimento"];

                      $this->EnviarEmail($nomeDe,$emailDe,$assunto,$message,$nomePara,$emailPara,"layout");
                    }
                }

                if ($idresposta && $existe_arquivos) {

                    foreach ($arquivos['arquivos']['name'] as $ind => $arquivo) {
                        $file['name'] = $arquivos['arquivos']['name'][$ind];
                        $file['tmp_name'] = $arquivos['arquivos']['tmp_name'][$ind];
                        $file['size'] = $arquivos['arquivo']['size'][$ind];

                        unset($nome_servidor);

                        $file_aux['name'] = $file;
                        $validacao_tamanho = $this->ValidarArquivo($file_aux['name']);
                        if($validacao_tamanho) {
                            $this->retorno["erro"] = true;
                            $this->retorno["erros"][] = $validacao_tamanho;
                            return $this->retorno;
                        }

                        $nome_servidor = $this->uploadFile($file, $campo);

                        if ($nome_servidor) {
                            $sql = "insert into atendimentos_arquivos set
                                  idresposta = '".$idresposta."',
                                  ativo = 'S',
                                  data_cad = NOW(),
                                  nome = '".$arquivos['arquivos']['name'][$ind]."',
                                  tipo = '".$arquivos['arquivos']['type'][$ind]."',
                                  tamanho = '".$arquivos['arquivos']['size'][$ind]."',
                                  servidor = '".$nome_servidor."' ";
                            $query_arquivo = $this->executaSql($sql);
                            $idarquivo = mysql_insert_id();
                            if (!$query_arquivo) {
                                $erro = true;
                            } else {
                                $this->monitora_onde = 102;
                                $this->monitora_oque = 1;
                                $this->monitora_qual = $idarquivo;
                                $this->Monitora();
                            }
                        }
                    }

                }

            }

            if ($post['proxima_acao']) {
                $sql_anterior = "select proxima_acao from atendimentos where idatendimento = '".intval($idatendimento)."' ";
                $data_anterior = $this->retornarLinha($sql_anterior);
                $data_array = explode('/',$post['proxima_acao']);
                $data = $data_array[2].'-'.$data_array[1].'-'.$data_array[0];
                $sql_data = "update atendimentos set proxima_acao = '".$data."' where idatendimento = '".intval($idatendimento)."' ";
                $data_alterar = $this->executaSql($sql_data);
                if (!$data_alterar)
                    $erro = true;
                else
                    $this->addHistorico($idatendimento, $this->idusuario, NULL, $data_anterior['proxima_acao'], $data, false, false, "IA");
                    //$this->adicionarFeed($idatendimento, $this->idusuario, NULL, NULL, NULL, "IA", $data_anterior['proxima_acao'], $data);
            }

            if( !$erro ){
                if($this->idpessoa) {
                    $this->addHistorico($idatendimento, NULL, $this->idpessoa, false, false, false, false, "RA");
                    //$this->adicionarFeed($idatendimento, NULL, $this->idpessoa, NULL, NULL, "RA", NULL, NULL);
                } else {
                    $this->addHistorico($idatendimento, $this->idusuario, NULL, false, false, false, false, "RA");
                    //$this->adicionarFeed($idatendimento, $this->idusuario, NULL, NULL, NULL, "RA", NULL, NULL);
                }
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 4;
                $this->monitora_onde = 90;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }

            return $this->retorno;
        }
    }

    function uploadFile($file, $campoAux){
        $extensao = strtolower(strrchr($file["name"], "."));
        $nome_servidor = date("YmdHis")."_".uniqid().$extensao;

        if(move_uploaded_file($file["tmp_name"],$_SERVER["DOCUMENT_ROOT"]."/storage/".$campoAux["pasta"]."/".$nome_servidor)) {
            return $nome_servidor;
        } else
            return false;
    }

    function RetornarSituacoesWorkflow(){
        $this->sql = "SELECT * FROM atendimentos_workflow WHERE ativo = 'S'";
        $this->ordem_campo = "ordem";
        $this->ordem = "asc";
        $this->limite = -1;
        $retorno = $this->retornarLinhas();
        $this->retorno = NULL;

        foreach($retorno as $ind => $var){
            $this->retorno[$var["idsituacao"]] = $var;
        }

        return $this->retorno;
    }

    function retornarWorkflow($idsituacao) {
        $sql = "select * from atendimentos_workflow where idsituacao = '".$idsituacao."' ";
        return $this->retornarLinha($sql);
    }

    function retornaPermissaoAlterarMensagem($idatendimento, $ultimo_view) {
        $sql = "select idresposta from atendimentos_respostas where idatendimento = '".$idatendimento."' and data_cad >= '".$ultimo_view."' and publica = 'S' order by idresposta desc limit 1 ";
        $linha = $this->retornarLinha($sql);
        return $linha['idresposta'];
    }

    function retornaSituacaoRespondidoGestor() {
      $sql = "select idsituacao, nome from atendimentos_workflow where respondido_gestor = 'S' and ativo = 'S'";
      return $this->retornarLinha($sql);
    }

    function alterar_bloqueio_cliente($idatendimento, $situacao) {
        $sql = " update atendimentos set cliente_visualiza = '".$situacao."' where idatendimento = '".$idatendimento."' ";
        $salvou = $this->executaSql($sql);

        if ($situacao == 'S') $acao = "CL"; else $acao = "CB";
        $this->addHistorico($idatendimento, $this->idusuario, false, false, false, false, false, $acao);
        //$this->adicionarFeed($idatendimento, $this->idusuario, NULL, NULL, NULL, $acao, NULL, NULL);

        return $salvou;
    }

    function listarTotalAtendimentos() {
        $this->sql = "select
                        count(a.idatendimento) as total
                      from
                        atendimentos a
                      where
                        a.ativo = 'S' ";
                    /*if ($this->idprofessor)
                        $this->sql .= "and r.idprofessor = '".$this->idprofessor."' ";
                    else if ($this->idpessoa)//aluno
                        $this->sql .= "and r.idpessoa = '".$this->idpessoa."' ";*/
        $dados = $this->retornarLinha($this->sql);
        return $dados['total'];
    }

    function RetornarMatriculasAluno ($idpessoa) {
        $sql = '
            select m.idmatricula, c.nome as curso
            from matriculas m
            inner join cursos c on m.idcurso = c.idcurso
            where m.ativo = "S" and m.idpessoa = ' . $idpessoa;
        $resultado = $this->executaSql($sql);
        while ($linha = mysql_fetch_assoc($resultado)) {
            $retorno[] = $linha;
        }
        return $retorno;
    }

    function ListarTodasMatricula($idmatricula) {
        $this->retorno = array();

        $this->sql = 'SELECT
                        '.$this->campos.'
                    FROM
                        atendimentos a
                        INNER JOIN atendimentos_assuntos aa ON (a.idassunto = aa.idassunto)
                        INNER JOIN atendimentos_workflow aw ON (a.idsituacao = aw.idsituacao)
                        INNER JOIN matriculas m on (a.idmatricula = m.idmatricula)
                        INNER JOIN cursos c ON (m.idcurso = c.idcurso)
                        LEFT OUTER JOIN atendimentos_assuntos_subassuntos aas ON (a.idsubassunto = aas.idsubassunto)
                    WHERE
                        m.idmatricula = '.$idmatricula.' AND
                        a.ativo = "S" AND
                        a.cliente_visualiza = "S"';

        $this->groupby = "a.idatendimento";
        return $this->retornarLinhas();
    }

    function RetornarAtendimentoAluno() {
        $this->sql = 'SELECT
                        '.$this->campos.'
                    FROM
                        atendimentos a
                        INNER JOIN atendimentos_assuntos aa ON (a.idassunto = aa.idassunto)
                        INNER JOIN atendimentos_workflow aw ON (a.idsituacao = aw.idsituacao)
                        INNER JOIN matriculas m on (a.idmatricula = m.idmatricula)
                        INNER JOIN cursos c ON (m.idcurso = c.idcurso)
                        LEFT OUTER JOIN atendimentos_assuntos_subassuntos aas ON (a.idsubassunto = aas.idsubassunto)
                        LEFT OUTER JOIN pessoas p ON (a.idpessoa = p.idpessoa)
                        LEFT OUTER JOIN usuarios_adm u ON (a.idusuario = u.idusuario)
                    WHERE
                        a.idatendimento = '.$this->id.' AND
                        a.cliente_visualiza = "S"';

        return $this->retornarLinha($this->sql);
    }

    function retornarSituacaoFim() {
        $sql = 'SELECT idsituacao FROM atendimentos_workflow WHERE fim = "S"';
        return $this->retornarLinha($sql);
    }



}

?>