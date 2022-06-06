<?php
class Declaracoes extends Core
{

    function ListarTodas() {

        $this->sql = "SELECT ".$this->campos." FROM
                            declaracoes d
                        where
                            d.ativo='S' ";

        if($this->idusuario)
            $this->sql .= " and (
                                    (
                                        (   select count(1)
                                            from declaracoes_sindicatos di
                                            where di.iddeclaracao = d.iddeclaracao and di.ativo = 'S'
                                        ) = 0
                                    )
                                    or
                                    (   select ua.idusuario
                                        from usuarios_adm ua
                                            left join usuarios_adm_sindicatos uai on ua.idusuario = uai.idusuario and uai.ativo = 'S'
                                            left join declaracoes_sindicatos di on di.idsindicato = uai.idsindicato and di.ativo = 'S'
                                        where ua.idusuario = ".$this->idusuario."
                                            and (   ua.gestor_sindicato = 'S'
                                                    or
                                                    (   di.iddeclaracao = d.iddeclaracao and
                                                        uai.idusuario is not null and
                                                        di.idsindicato is not null
                                                    )
                                                )
                                        limit 1
                                    ) is not null
                                )   ";

        $this->aplicarFiltrosBasicos();
        $this->groupby = "d.iddeclaracao";
        return $this->retornarLinhas();
    }


    function Retornar() {

        $this->sql = "SELECT ".$this->campos."
                            FROM
                             declaracoes d where d.ativo='S' and d.iddeclaracao='".$this->id."' ";
        if($this->idusuario)
            $this->sql .= " and (
                                    (
                                        (   select count(1)
                                            from declaracoes_sindicatos di
                                            where di.iddeclaracao = d.iddeclaracao and di.ativo = 'S'
                                        ) = 0
                                    )
                                    or
                                    (   select ua.idusuario
                                        from usuarios_adm ua
                                            left join usuarios_adm_sindicatos uai on ua.idusuario = uai.idusuario and uai.ativo = 'S'
                                            left join declaracoes_sindicatos di on di.idsindicato = uai.idsindicato and di.ativo = 'S'
                                        where ua.idusuario = ".$this->idusuario."
                                            and (   ua.gestor_sindicato = 'S'
                                                    or
                                                    (   di.iddeclaracao = d.iddeclaracao and
                                                        uai.idusuario is not null and
                                                        di.idsindicato is not null
                                                    )
                                                )
                                        limit 1
                                    ) is not null
                                )";

        return $this->retornarLinha($this->sql);
    }

    function Cadastrar() {
        $file_aux['name'] = $_POST['background'];
        $validacao_tamanho = $this->ValidarArquivo($file_aux['name']);
        if($validacao_tamanho) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $validacao_tamanho;
            return $this->retorno;
        }
        return $this->SalvarDados();
    }

    function Modificar() {
        $file_aux['name'] = $_POST['background'];
        $validacao_tamanho = $this->ValidarArquivo($file_aux['name']);
        if($validacao_tamanho) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $validacao_tamanho;
            return $this->retorno;
        }
        return $this->SalvarDados();
    }

    function Remover() {
        return $this->RemoverDados();
    }

    function RemoverArquivo($modulo, $pasta, $dados, $idioma) {
        echo $this->ExcluirArquivo($modulo, $pasta, $dados, $idioma);
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
        $campo = array("pasta" => "declaracoes_imagens");
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
                    $sql = "insert into declaracoes_imagens set
                              iddeclaracao = '".$this->id."',
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
                        $this->monitora_onde = 190;
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

    function RetornarImagens() {
        $this->sql = "SELECT $this->campos FROM declaracoes_imagens
                    WHERE ativo = 'S' AND iddeclaracao = $this->id";
        $dados = $this->retornarLinhas();
        return $dados;
    }

    function RemoverImagens() {
        $this->sql = "UPDATE declaracoes_imagens
                    SET ativo='N' WHERE iddeclaracao_imagem = ".$this->id;
        $dados = $this->executaSql($this->sql);

        if ($dados) {
            $this->retorno["sucesso"] = true;
            $this->monitora_oque = 3;
            $this->monitora_onde = 190;
            $this->monitora_qual = $this->id;
            $this->Monitora();
        }

        return $this->retorno;
    }

    function RetornarImagemDownload() {
        $this->sql = "SELECT * FROM declaracoes_imagens
                      where
                        iddeclaracao_imagem = ".$this->id;
        $retorno = $this->retornarLinha($this->sql);

        return $retorno;
    }

    function RetornarDeclaracoesGrupos() {
        $this->sql = "select
                      ".$this->campos."
                    from
                      declaracoes
                    where
                      ativo = 'S' and
                      iddeclaracao = ".$this->id;
        return $this->retornarLinha($this->sql);
    }

    function AssociarSindicato() {
        foreach($this->post["sindicatos"] as $idsindicato) {
            $this->sql = "select count(iddeclaracao_sindicato) as total, iddeclaracao_sindicato from declaracoes_sindicatos where iddeclaracao = '".$this->id."' and idsindicato = '".intval($idsindicato)."'";
            $totalAssociado = $this->retornarLinha($this->sql);
            if($totalAssociado["total"] > 0) {
                $this->sql = "update declaracoes_sindicatos set ativo = 'S' where iddeclaracao_sindicato = ".$totalAssociado["iddeclaracao_sindicato"];
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $totalAssociado["iddeclaracao_sindicato"];
            } else {
                $this->sql = "insert into declaracoes_sindicatos set ativo = 'S', data_cad = now(), iddeclaracao = '".$this->id."', idsindicato = '".intval($idsindicato)."'";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();
            }
            if($associar){
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 107;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }


    function DesassociarSindicato() {

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
        } else {
            $this->sql = "update declaracoes_sindicatos set ativo = 'N' where iddeclaracao_sindicato = ".intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if($desassociar){
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 107;
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

    function ListarSindicatosAssociadas() {
        $this->sql = "select
                    ".$this->campos."
                  from
                    declaracoes_sindicatos di
                    inner join sindicatos i ON (di.idsindicato = i.idsindicato)
                    inner join usuarios_adm ua on ua.idusuario = ".$this->idusuario."
                    left join usuarios_adm_sindicatos uai on i.idsindicato = uai.idsindicato and uai.ativo = 'S' and uai.idusuario = ua.idusuario
                  where
                    (ua.gestor_sindicato = 'S' or uai.idusuario is not null) and
                    i.ativo = 'S' and
                    di.ativo= 'S' and
                    di.iddeclaracao = ".intval($this->id);

        $this->limite = -1;
        $this->ordem = "asc";
        $this->ordem_campo = "i.nome";
        return $this->retornarLinhas();
    }

    function BuscarSindicatos() {
        $this->sql = "select
                    i.idsindicato as 'key',
                    i.nome_abreviado as value
                  from
                    sindicatos i
                  inner join usuarios_adm ua on ua.idusuario = ".$this->idusuario."
                  left join usuarios_adm_sindicatos uai on i.idsindicato = uai.idsindicato and uai.ativo = 'S' and uai.idusuario = ua.idusuario
                  where
                    (ua.gestor_sindicato = 'S' or uai.idusuario is not null) and
                    i.nome_abreviado LIKE '%".$this->get["tag"]."%' and
                    i.ativo = 'S' and i.ativo_painel = 'S' and
                    not exists (
                      select
                        di.iddeclaracao
                      from
                        declaracoes_sindicatos di
                      where
                        di.idsindicato = i.idsindicato and
                        di.iddeclaracao = '".intval($this->id)."' and
                        di.ativo = 'S'
                    )";

        $this->limite = -1;
        $this->ordem_campo = "i.nome_abreviado";
        $this->groupby = "i.idsindicato";
        $this->retorno = $this->retornarLinhas();

        return json_encode($this->retorno);
    }

    function AssociarCurso() {
        foreach($this->post["cursos"] as $idcurso) {
            $this->sql = "select count(iddeclaracao_curso) as total, iddeclaracao_curso from declaracoes_cursos where iddeclaracao = '".$this->id."' and idcurso = '".intval($idcurso)."'";
            $totalAssociado = $this->retornarLinha($this->sql);
            if($totalAssociado["total"] > 0) {
                $this->sql = "update declaracoes_cursos set ativo = 'S' where iddeclaracao_curso = ".$totalAssociado["iddeclaracao_curso"];
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $totalAssociado["iddeclaracao_curso"];
            } else {
                $this->sql = "insert into declaracoes_cursos set ativo = 'S', data_cad = now(), iddeclaracao = '".$this->id."', idcurso = '".intval($idcurso)."'";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();
            }
            if($associar){
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 208;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }


    function DesassociarCurso() {

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
        } else {
            $this->sql = "update declaracoes_cursos set ativo = 'N' where iddeclaracao_curso = ".intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if($desassociar){
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 208;
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

    function ListarCursosAssociados() {
        $this->sql = "select
                    ".$this->campos."
                  from
                    declaracoes_cursos dc
                    inner join cursos c ON (dc.idcurso = c.idcurso)
                  where
                    c.ativo = 'S' and
                    dc.ativo= 'S' and
                    dc.iddeclaracao = ".intval($this->id);
        /*
        inner join usuarios_adm ua on ua.idusuario = ".$this->idusuario."
        left join usuarios_adm_sindicatos uai on i.idsindicato = uai.idsindicato and uai.ativo = 'S' and uai.idusuario = ua.idusuario
        where
        (ua.gestor_sindicato = 'S' or uai.idusuario is not null) and
        */

        $this->limite = -1;
        $this->ordem = "asc";
        $this->ordem_campo = "c.nome";
        return $this->retornarLinhas();
    }

    function BuscarCursos() {
        $this->sql = "select
                    c.idcurso as 'key',
                    c.nome as value
                  from
                    cursos c
                  where
                    c.nome LIKE '%".$this->get["tag"]."%' and
                    c.ativo = 'S' and c.ativo_painel = 'S' and
                    not exists (
                      select
                        dc.iddeclaracao
                      from
                        declaracoes_cursos dc
                      where
                        dc.idcurso = c.idcurso and
                        dc.iddeclaracao = '".intval($this->id)."' and
                        dc.ativo = 'S'
                    )";
        /*
        inner join usuarios_adm ua on ua.idusuario = ".$this->idusuario."
        left join usuarios_adm_sindicatos uai on i.idsindicato = uai.idsindicato and uai.ativo = 'S' and uai.idusuario = ua.idusuario
        where
        (ua.gestor_sindicato = 'S' or uai.idusuario is not null) and
        */

        $this->limite = -1;
        $this->ordem_campo = "c.nome";
        $this->groupby = "c.idcurso";
        $this->retorno = $this->retornarLinhas();

        return json_encode($this->retorno);
    }
    function RetornarImagemDownloadBackground() {
        $this->sql = "SELECT * FROM declaracoes
					  where
					  	iddeclaracao = ".$this->id;
        $retorno = $this->retornarLinha($this->sql);

        return $retorno;
    }

    function consultarDeclaracarao($iddeclaracao)
    {
        $this->sql = "SELECT difere_automatico FROM declaracoes WHERE iddeclaracao = {$iddeclaracao}";
        return $this->retornarLinha($this->sql);
    }
}
