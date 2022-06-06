<?php
class Quadros_Avisos extends Core
{

    var $idmural = NULL;
    var $ind = NULL;
    var $val = NULL;
    var $query = NULL;
    var $get = NULL;
    var $linha_antiga = NULL;
    var $linha_nova = NULL;
    var $files = NULL;
    var $linha = array();

    function ListarTodas()
    {
        $this->sql = "select
						" . $this->campos . "
					  from
						quadros_avisos 
					  where 
					  	ativo = 'S'";

        if (is_array($_GET["q"])) {
            foreach ($_GET["q"] as $campo => $valor) {
                //explode = Retira, ou seja retira a "|" da variavel campo
                $campo = explode("|", $campo);
                $valor = str_replace("'", "", $valor);
                // Listagem se o valor for diferente de Todos ele faz um filtro
                if (($valor || $valor === "0") and $valor <> "todos") {
                    // se campo[0] for = 1 é pq ele tem de ser um valor exato
                    if ($campo[0] == 1) {
                        $this->sql .= " and " . $campo[1] . " = '" . $valor . "' ";
                        // se campo[0] for = 2, faz o filtro pelo comando like
                    } elseif ($campo[0] == 2) {
                        $busca = str_replace("\\'", "", $valor);
                        $busca = str_replace("\\", "", $busca);
                        $busca = explode(" ", $busca);
                        foreach ($busca as $ind => $buscar) {
                            $this->sql .= " and " . $campo[1] . " like '%" . urldecode($buscar) . "%' ";
                        }
                    } elseif ($campo[0] == 3) {
                        $this->sql .= " and date_format(" . $campo[1] . ",'%d/%m/%Y') = '" . $valor . "' ";
                    }
                }
            }
        }

        $this->groupby = "idquadro";
        $murais = $this->retornarLinhas();
        //print_r2($murais,true);
        return $murais;
    }

    function Retornar()
    {
        $this->sql = "SELECT " . $this->campos . " FROM quadros_avisos where ativo='S' and idquadro='" . $this->id . "'";
        return $this->retornarLinha($this->sql);
    }

    function Cadastrar()
    {
        return $this->SalvarDados();
    }

    function Modificar()
    {
        return $this->SalvarDados();
    }

    function Remover()
    {
        return $this->RemoverDados();
    }

    function ListarImagens() {
        $this->sql = "(SELECT ".$this->campos." FROM
                            quadros_avisos_imagens
                        where ativo='S' and idquadro = ".intval($this->id).")";

        $this->groupby = "idquadro_imagem";
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
        $campo = array("pasta" => "quadros_imagens");
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

                    $file['name'] 	  = $this->files['arquivos']['name'][$ind];
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
                        $sql = "insert into quadros_avisos_imagens set
                              idquadro = '".$this->id."',
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
                            $this->monitora_onde = 248;
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
        $this->sql = "SELECT * FROM quadros_avisos_imagens where ativo = 'S' AND idquadro = ".$this->id;

        $this->limite = -1;
        $this->ordem = "asc";
        $this->ordem_campo = "idquadro_imagem";
        $this->groupby = "idquadro_imagem";
        $dados = $this->retornarLinhas();

        return $dados;
    }
	
	function RemoverImagens() {
        $this->sql = "UPDATE quadros_avisos_imagens SET ativo='N' where idquadro_imagem = ".$this->id;
        $dados = $this->executaSql($this->sql);

        if ($dados) {
            $this->retorno["sucesso"] = true;
            $this->monitora_oque = 3;
            $this->monitora_onde = 248;
            $this->monitora_qual = $this->id;
            $this->Monitora();
        }

        return $this->retorno;
    }

    function RetornarImagemDownload() {
        $this->sql = "SELECT * FROM quadros_avisos_imagens WHERE idquadro_imagem = ".$this->id;
        $retorno = $this->retornarLinha($this->sql);

        return $retorno;
    }
	
	
	function RetornarPreviewQuadro() {
        $dadosArray = array();
        $this->sql = "SELECT titulo, descricao FROM quadros_avisos WHERE idquadro = ".$this->id;
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
                $this->sql = "SELECT idmural_imagem, servidor FROM quadros_avisos_imagens WHERE idquadro = ".$this->id." AND idquadro_imagem = ".intval($val)." AND ativo = 'S'";
                $linha = $this->retornarLinha($this->sql);
                $retorno["descricao"] = str_replace("[[I]][[".$val."]]", "<div style=\"text-align:left; max-width:800px; text-align:center\"><img src=\"http://".$_SERVER["SERVER_NAME"]."/storage/quadros_imagens/".$linha["servidor"]."\" border=\"0\" /></div>", $retorno["descricao"]);
            }
        }
        return $retorno;
    }
	
 /*
    function RetornaArquivos() {
        $this->sql = "SELECT * FROM murais_arquivos where ativo = 'S' AND idmural = ".$this->id;

        $this->limite = -1;
        $this->ordem = "asc";
        $this->ordem_campo = "idmural_arquivo";
        $this->groupby = "idmural_arquivo";
        $dados = $this->retornarLinhas();

        return $dados;
    }

    function RetornarArquivoDownload() {
        $this->sql = "SELECT * FROM murais_arquivos WHERE idmural_arquivo = ".$this->id;
        $retorno = $this->retornarLinha($this->sql);

        return $retorno;
    }*/

    /* FIM */

    /* METODOS PARA MOSTRAR O PREVIEW DA PESQUISA */
    /*
    function RetornarPreviewQuadro() {
        $dadosArray = array();
        $this->sql = "SELECT titulo, descricao FROM quadros_avisos WHERE idquadro = ".$this->id;
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

        if(is_array($_GET["q"])) {
            foreach($_GET["q"] as $campo => $valor) {
                //explode = Retira, ou seja retira a "|" da variavel campo
                $campo = explode("|",$campo);
                $valor = str_replace("'","",$valor);
                // Listagem se o valor for diferente de Todos ele faz um filtro
                if(($valor || $valor === "0") and $valor <> "todos") {
                    // se campo[0] for = 1 é pq ele tem de ser um valor exato
                    if($campo[0] == 1) {
                        $this->sql .= " and ".$campo[1]." = '".$valor."' ";
                    // se campo[0] for = 2, faz o filtro pelo comando like
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

                    $file['name'] 	  = $this->files['arquivos']['name'][$ind];
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

    }*/

    function BuscarOferta()
    {
        $this->sql = "select
						o.idoferta as 'key', o.nome as value
					  from
						ofertas o
					  where
					     o.nome like '%" . $_GET["tag"] . "%' AND
						 o.ativo = 'S' AND
						 o.ativo_painel = 'S' AND
						 NOT EXISTS (SELECT qao.idoferta FROM quadros_avisos_ofertas qao WHERE qao.idoferta = o.idoferta AND qao.idquadro = '" . $this->id . "' AND qao.ativo = 'S')";
        $this->limite = -1;
        $this->ordem_campo = "value";
        $this->groupby = "value";

        $dados = $this->retornarLinhas();
        return json_encode($dados);
    }

    function ListarOfertasAss()
    {
        $this->sql = "SELECT
						 " . $this->campos . "
					  FROM
						ofertas o
						inner join ofertas_workflow ow on o.idsituacao = ow.idsituacao
						INNER JOIN quadros_avisos_ofertas qao ON (o.idoferta = qao.idoferta)
					  WHERE
						qao.ativo = 'S' and o.ativo = 'S' and o.ativo_painel = 'S' and
						qao.idquadro = " . intval($this->id);

        $this->groupby = "qao.idquadro_oferta";
        return $this->retornarLinhas();
    }

    function AssociarOfertas($idquadro, $arrayOfertas)
    {
        foreach ($arrayOfertas as $ind => $id) {

            $this->sql = "select count(idquadro_oferta) as total, idquadro_oferta from quadros_avisos_ofertas where idquadro = '" . intval($idquadro) . "' and idoferta = '" . intval($id) . "'";
            $totalAss = $this->retornarLinha($this->sql);
            if ($totalAss["total"] > 0) {
                $this->sql = "update quadros_avisos_ofertas set ativo = 'S' where idquadro_oferta = " . $totalAss["idquadro_oferta"];
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $totalAss["idquadro_oferta"];
            } else {
                $this->sql = "insert into quadros_avisos_ofertas set ativo = 'S', data_cad = now(), idquadro = '" . intval($idquadro) . "', idoferta = '" . intval($id) . "'";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();
            }

            if ($associar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 168;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }

        }
        return $this->retorno;
    }

    function DesassociarOfertas()
    {

        include_once("../includes/validation.php");
        $regras = array(); // stores the validation rules

        //VERIFICANDO SE OPNIAO REMOVER FOI MARCADA
        if (!$this->post["remover"])
            $regras[] = "required,remover,remover_vazio";

        //VALIDANDO FORMULÃRIO
        $erros = validateFields($this->post, $regras);

        //SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
        if (!empty($erros)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        } else {
            $this->sql = "update quadros_avisos_ofertas set ativo = 'N' where idquadro_oferta = " . intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 168;
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

    function BuscarEscola()
    {
        $this->sql = "select
						p.idescola as 'key', p.nome_fantasia as value
					  from
						escolas p
					  where
					     p.nome_fantasia like '%" . $_GET["tag"] . "%' AND
						 p.ativo = 'S' AND
						 p.ativo_painel = 'S' AND
						 NOT EXISTS (SELECT qap.idescola FROM quadros_avisos_escolas qap WHERE qap.idescola = p.idescola AND qap.idquadro = '" . $this->id . "' AND qap.ativo = 'S')";
        $this->limite = -1;
        $this->ordem_campo = "value";
        $this->groupby = "value";

        $dados = $this->retornarLinhas();
        return json_encode($dados);
    }

    function ListarEscolasAss()
    {
        $this->sql = "SELECT
						 " . $this->campos . "
					  FROM
						escolas p
						INNER JOIN quadros_avisos_escolas qap ON (p.idescola = qap.idescola)
					  WHERE
						qap.ativo = 'S' and p.ativo = 'S' and p.ativo_painel = 'S' and
						qap.idquadro = " . intval($this->id);

        $this->groupby = "qap.idquadro_escola";
        return $this->retornarLinhas();
    }

    function AssociarEscolas($idquadro, $arrayEscolas)
    {
        foreach ($arrayEscolas as $ind => $id) {

            $this->sql = "select count(idquadro_escola) as total, idquadro_escola from quadros_avisos_escolas where idquadro = '" . intval($idquadro) . "' and idescola = '" . intval($id) . "'";
            $totalAss = $this->retornarLinha($this->sql);
            if ($totalAss["total"] > 0) {
                $this->sql = "update quadros_avisos_escolas set ativo = 'S' where idquadro_escola = " . $totalAss["idquadro_escola"];
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $totalAss["idquadro_escola"];
            } else {
                $this->sql = "insert into quadros_avisos_escolas set ativo = 'S', data_cad = now(), idquadro = '" . intval($idquadro) . "', idescola = '" . intval($id) . "'";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();
            }

            if ($associar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 169;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }

        }
        return $this->retorno;
    }

    function DesassociarEscolas()
    {

        include_once("../includes/validation.php");
        $regras = array(); // stores the validation rules

        //VERIFICANDO SE OPNIAO REMOVER FOI MARCADA
        if (!$this->post["remover"])
            $regras[] = "required,remover,remover_vazio";

        //VALIDANDO FORMULÃRIO
        $erros = validateFields($this->post, $regras);

        //SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
        if (!empty($erros)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        } else {
            $this->sql = "update quadros_avisos_escolas set ativo = 'N' where idquadro_escola = " . intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 169;
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

    function BuscarCurso()
    {

        $this->sql = "select
						c.idcurso as 'key', 
						c.nome as value
					  from
						cursos c
					  where
					     c.nome like '%" . $_GET["tag"] . "%' AND
						 c.ativo = 'S' AND
						 c.ativo_painel = 'S' AND
						 NOT EXISTS (SELECT qac.idcurso FROM quadros_avisos_cursos qac WHERE qac.idcurso = c.idcurso AND qac.idquadro = '" . $this->id . "' AND qac.ativo = 'S')";
        $this->limite = -1;
        $this->ordem_campo = "value";
        $this->groupby = "value";

        $dados = $this->retornarLinhas();
        return json_encode($dados);

    }

    //FIM função BuscarCurso()

    function ListarCursosAss()
    {

        $this->sql = "SELECT
						 " . $this->campos . "
					  FROM
						cursos c
						INNER JOIN quadros_avisos_cursos qac ON (qac.idcurso = c.idcurso)
					  WHERE
						qac.idquadro = '" . intval($this->id) . "' AND
						qac.ativo = 'S' AND 
						c.ativo = 'S' AND 
						c.ativo_painel = 'S'";

        $this->groupby = "qac.idquadro_curso";
        return $this->retornarLinhas();

    }

    //FIM função ListarCursosAss()

    function AssociarCursos($idquadro, $arrayCursos)
    {

        foreach ($arrayCursos as $ind => $id) {

            $this->sql = "SELECT count(idquadro_curso) as total, idquadro_curso FROM quadros_avisos_cursos WHERE idquadro = '" . intval($idquadro) . "' AND idcurso = '" . intval($id) . "'";
            $totalAss = $this->retornarLinha($this->sql);
            if ($totalAss["total"] > 0) {
                $this->sql = "UPDATE quadros_avisos_cursos SET ativo = 'S' WHERE idquadro_curso = " . $totalAss["idquadro_curso"];
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $totalAss["idquadro_curso"];
            } else {
                $this->sql = "INSERT INTO quadros_avisos_cursos SET ativo = 'S', data_cad = now(), idquadro = '" . intval($idquadro) . "', idcurso = '" . intval($id) . "'";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();
            }

            if ($associar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 168;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }

        }
        return $this->retorno;

    }

    //FIM função AssociarCursos()

    function DesassociarCursos()
    {

        include_once("../includes/validation.php");
        $regras = array(); // stores the validation rules

        //VERIFICANDO SE OPNIAO REMOVER FOI MARCADA
        if (!$this->post["remover"])
            $regras[] = "required,remover,remover_vazio";

        //VALIDANDO FORMULÃRIO
        $erros = validateFields($this->post, $regras);

        //SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
        if (!empty($erros)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        } else {
            $this->sql = "UPDATE quadros_avisos_cursos SET ativo = 'N' WHERE idquadro_curso = '" . intval($this->post["remover"]) . "'";
            $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 168;
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

    //FIM função DesassociarCursos()

    function listarQuadrosCursoMatricula($idmatricula, $quantidade)
    {
        $sql = 'select idoferta, idescola, idcurso from matriculas where idmatricula = ' . $idmatricula;
        $matricula = $this->retornarLinha($sql);

        $this->sql = '
			select
				qa.*
			from
				quadros_avisos qa 
			where
				qa.ativo = "S" and
				qa.tipo_aviso = "cur"	';

        if ($quantidade) {
            $this->limite = $quantidade;
        } else {
            $this->limite = 5;
        }

        $quadros = $this->retornarLinhas();
        foreach ($quadros as $quadro) {
            $ofertas_associadas = array();
            $escolas_associados = array();
            $cursos_associados = array();

            $sql = 'select idoferta from quadros_avisos_ofertas where idquadro = ' . $quadro['idquadro'] . ' and ativo = "S"';
            $resultado = $this->executaSql($sql);
            while ($linha = mysql_fetch_assoc($resultado)) {
                $ofertas_associadas[] = $linha['idoferta'];
            }

            $sql = 'select idescola from quadros_avisos_escolas where idquadro = ' . $quadro['idquadro'] . ' and ativo = "S"';
            $resultado = $this->executaSql($sql);
            while ($linha = mysql_fetch_assoc($resultado)) {
                $escolas_associados[] = $linha['idescola'];
            }

            $sql = 'select idcurso from quadros_avisos_cursos where idquadro = ' . $quadro['idquadro'] . ' and ativo = "S"';
            $resultado = $this->executaSql($sql);
            while ($linha = mysql_fetch_assoc($resultado)) {
                $cursos_associados[] = $linha['idcurso'];
            }

            $tem_oferta = count($ofertas_associadas);
            $tem_escola = count($escolas_associados);
            $tem_curso = count($cursos_associados);
            $tem_oferta_assoc = in_array($matricula['idoferta'], $ofertas_associadas);
            $tem_escola_assoc = in_array($matricula['idescola'], $escolas_associados);
            $tem_curso_assoc = in_array($matricula['idcurso'], $cursos_associados);
            if (
                (!$tem_oferta && !$tem_escola && !$tem_curso) ||
                (!$tem_escola && (($tem_oferta && $tem_oferta_assoc) || !$tem_oferta) && (($tem_curso && $tem_curso_assoc) || !$tem_curso)) ||
                (!$tem_oferta && (($tem_escola && $tem_escola_assoc) || !$tem_escola) && (($tem_curso && $tem_curso_assoc) || !$tem_curso)) ||
                (!$tem_curso && (($tem_oferta && $tem_oferta_assoc) || !$tem_oferta) && (($tem_escola && $tem_escola_assoc) || !$tem_escola)) ||
                ($tem_oferta_assoc && $tem_escola_assoc && $tem_curso_assoc)
            ) {
                $retorno[] = $quadro;
            }

        }

        return $retorno;
    }
	
	function retornarQuadroDeAvisosDaMatricula($idoferta, $idescola, $idcurso, $tipo) {
        $this->sql = 'select
						 '.$this->campos.'
					from
						quadros_avisos qa
						left outer join quadros_avisos_ofertas qao on (qa.idquadro = qao.idquadro and qao.ativo = "S")
						left outer join quadros_avisos_escolas qap on (qa.idquadro = qap.idquadro and qap.ativo = "S")
						left outer join quadros_avisos_cursos qac on (qa.idquadro = qac.idquadro and qac.ativo = "S")
					where
						(qao.idoferta = '.$idoferta.' or qao.idoferta is null) and
						(qap.idescola = '.$idescola.' or qap.idescola is null) and
						(qac.idcurso = '.$idcurso.' or qac.idcurso is null) and
						qa.data_de <= "'.date('Y-m-d').'" and
						(qa.data_ate >= "'.date('Y-m-d').'" or qa.data_ate is null) and
						qa.tipo_aviso = "'.$tipo.'" and
						qa.ativo = "S"';
        $this->groupby = "qa.idquadro";
		
		
		$quadros = $this->retornarLinhas();
		
		if(count($quadros) > 0){
			foreach($quadros as $position => $retorno){
				$variavel = explode("[[I]][[",$retorno["descricao"]);
				if($variavel){
					$indice = array();
					foreach($variavel as $ind => $val){
						$id = explode("]]",$val);
						$indice[] = $id[0];
					}
		
					unset($indice[array_search("", $indice)]);
		
					foreach($indice as $ind => $val){
						$this->sql = "SELECT idquadro_imagem, servidor FROM quadros_avisos_imagens WHERE idquadro = ".$retorno["idquadro"]." AND idquadro_imagem = ".intval($val)." AND ativo = 'S'";
						$linha = $this->retornarLinha($this->sql);
						$retorno["descricao"] = str_replace("[[I]][[".$val."]]", "<img src=\"http://".$_SERVER["SERVER_NAME"]."/storage/quadros_imagens/".$linha["servidor"]."\" border=\"0\" />", $retorno["descricao"]);
					}
					$quadros[$position]["descricao"]=$retorno["descricao"] ;
				}
			}
		}
		
		return $quadros;
    }

}

?>