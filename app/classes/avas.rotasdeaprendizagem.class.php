<?php
class Rotas_Aprendizagem extends Ava {

    var $idava = NULL;

    /*function ListarTodasRotaAprendizagem() {
      $this->sql = "select
                      ".$this->campos."
                    from
                      avas_rotas_aprendizagem r
                      inner join avas a on (r.idava = a.idava)
                    where
                      r.ativo = 'S' and
                      a.idava = ".$this->idava;

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

      $this->groupby = "r.idrota_aprendizagem";
      return $this->retornarLinhas();
    }*/

    function RetornarRotaAprendizagem() {
        $this->sql = "SELECT
					".$this->campos."
				  FROM
					avas_rotas_aprendizagem r
					inner join avas a on r.idava = a.idava
				  WHERE
					r.ativo = 'S' AND
					r.idrota_aprendizagem = '".$this->id."' AND
					a.idava = ".$this->idava;
        return $this->retornarLinha($this->sql);
    }

    function RetornarRotaAprendizagemAva() {
        $this->sql = "SELECT
					".$this->campos."
				  FROM
					avas_rotas_aprendizagem r
					inner join avas a ON r.idava = a.idava
				  WHERE
					r.ativo = 'S' AND
					a.idava = ".$this->idava;
        return $this->retornarLinha($this->sql);
    }

    /*function CadastrarRotaAprendizagem() {
      $this->post["idava"] = $this->idava;

      return $this->SalvarDados();
    }*/

    function ModificarRotaAprendizagem() {
        $this->post["idava"] = $this->idava;

        return $this->SalvarDados();
    }

    /*function RemoverRotaAprendizagem() {
      return $this->RemoverDados();
    }*/

    function ListarTodasObjetos() {
        $this->sql = "SELECT
					".$this->campos."
				  FROM
					avas_rotas_aprendizagem_objetos arab
					LEFT OUTER JOIN 
						avas_audios aa ON (arab.idaudio = aa.idaudio)
					LEFT OUTER JOIN 
						avas_objetos_divisores od 
						ON (arab.idobjeto_divisor = od.idobjeto_divisor)
					LEFT OUTER JOIN 
						avas_conteudos ac ON (arab.idconteudo = ac.idconteudo)
					LEFT OUTER JOIN 
						avas_downloads ad ON (arab.iddownload = ad.iddownload)
					LEFT OUTER JOIN 
						avas_links al ON (arab.idlink = al.idlink)
					LEFT OUTER JOIN 
						avas_perguntas ap ON (arab.idpergunta = ap.idpergunta)
					LEFT OUTER JOIN 
						videotecas m ON (arab.idvideo = m.idvideo)
					LEFT OUTER JOIN avas_simulados asi ON 
						(arab.idsimulado = asi.idsimulado)
					LEFT OUTER JOIN avas_enquetes ae ON 
						(arab.idenquete = ae.idenquete)
					LEFT OUTER JOIN avas_exercicios aex ON 
						(arab.idexercicio = aex.idexercicio)
					LEFT OUTER JOIN aulas_online ao ON 
						(arab.idaulaonline = ao.idaula)
				  WHERE
					arab.ativo = 'S' AND
					arab.idrota_aprendizagem = '".$this->id."'";

        return $this->retornarLinhas();
    }

    function CadastrarObjetos() {
        if(!$this->post["ordem"]) {
            $this->post["ordem"] = "NULL";
        }
        if(!$this->post["vencimento"]) {
            $this->post["vencimento"] = "NULL";
        } else {
            $this->post["vencimento"] = "'".formataData($this->post["vencimento"], 'en', 0)."'";
        }
        if(!$this->post["tempo"]) {
            $this->post["tempo"] = "NULL";
        } else {
            $this->post["tempo"] = "'00:".$this->post["tempo"]."'";
        }
        if(!$this->post["porcentagem"]) {
            $this->post["porcentagem"] = "NULL";
        } else {
            $this->post["porcentagem"] = str_replace('.','',$this->post["porcentagem"]);
            $this->post["porcentagem"] = str_replace(',','.',$this->post["porcentagem"]);
        }
        if(!$this->post["pre_requisito"]) {
            $this->post["pre_requisito"] = "NULL";
        }

        if(!$this->post["objeto"]) {
            $erros[] = "objeto_vazio";
        }

        if(!empty($erros)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        } else {
            $objeto = explode("|",$this->post["objeto"]);

            $this->sql = "INSERT INTO
					  avas_rotas_aprendizagem_objetos
					SET
					  data_cad = now(),
					  idrota_aprendizagem = '".$this->id."',
					  tipo = '".$objeto[0]."',
					  id".$objeto[0]." = ".$objeto[1].",
					  ordem = ".$this->post["ordem"].",
					  vencimento = ".$this->post["vencimento"].",
					  tempo = ".$this->post["tempo"].",
					  porcentagem = ".$this->post["porcentagem"].",
					  idobjeto_pre_requisito = ".$this->post["pre_requisito"];
            if($this->executaSql($this->sql)){
                $this->monitora_qual = mysql_insert_id();
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 74;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }

    function ModificarObjetos() {

        //print_r2($this->post["objetos"],true);
        foreach($this->post["objetos"] as $idobjeto => $post) {
            if(!$post["ordem"]) {
                $post["ordem"] = "NULL";
            }

            if(!$post["vencimento"]) {
                $post["vencimento"] = "NULL";
            } else {
                $post["vencimento"] = "'".formataData($post["vencimento"], 'en', 0)."'";
            }

            if(!$post["dias"]) {
                $post["dias"] = "NULL";
            } else {
                $post["dias"] = $post["dias"];
            }

            if(!$post["tempo"]) {
                $post["tempo"] = "NULL";
            } else {
                $post["tempo"] = "'00:".$post["tempo"]."'";
            }

            if(!$post["porcentagem"]) {
                $post["porcentagem"] = "NULL";
            } else {
                $post["porcentagem"] = str_replace('.','',$post["porcentagem"]);
                $post["porcentagem"] = str_replace(',','.',$post["porcentagem"]);
            }
            if(!$post["pre_requisito"]) {
                $post["pre_requisito"] = "NULL";
            }

            $this->sql = "SELECT * FROM 
	  					avas_rotas_aprendizagem_objetos 
	  				WHERE idrota_aprendizagem = '".$this->id."' AND 
	  					idobjeto = ".intval($idobjeto);
            $linhaAntiga = $this->retornarLinha($this->sql);
            $gerar_data_final = 0;
            if(array_key_exists('gerar_data_final', $this->post) && $this->post['gerar_data_final'] == $idobjeto)
                $gerar_data_final = 1;
            $idobjeto = intval($idobjeto);
            $this->sql = "UPDATE
					  avas_rotas_aprendizagem_objetos
					SET
					  ordem = {$post["ordem"]},
					  vencimento = {$post["vencimento"]},
					  tempo = {$post["tempo"]},
					  dias = {$post["dias"]},
					  porcentagem = {$post["porcentagem"]},
					  idobjeto_pre_requisito = {$post["pre_requisito"]},
					  gerar_data_final = {$gerar_data_final}
					WHERE
					  idrota_aprendizagem = {$this->id} AND
					  idobjeto = {$idobjeto}";
            $executa = $this->executaSql($this->sql);

            $this->sql = "SELECT * 
	  				FROM avas_rotas_aprendizagem_objetos 
	  				WHERE idrota_aprendizagem = '".$this->id."' AND 
	  				idobjeto = ".intval($idobjeto);
            $linhaNova = $this->retornarLinha($this->sql);

            if($executa){
                $this->monitora_oque = 2;
                $this->monitora_onde = 74;
                $this->monitora_qual = $idobjeto;
                $this->monitora_dadosantigos = $linhaAntiga;
                $this->monitora_dadosnovos = $linhaNova;
                $this->Monitora();

                $this->retorno["sucesso"] = true;
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }

    function RemoverObjeto() {
        include_once("../includes/validation.php");
        $regras = array(); // stores the validation rules

        //VERIFICANDO SE OPNIAO REMOVER FOI MARCADA
        if(!$this->post["remover"])
            $regras[] = "required,remover,remover_vazio";

        //VALIDANDO FORMULARIO
        $erros = validateFields($this->post, $regras);

        //SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
        if(!empty($erros)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        } else {
            $this->sql = "UPDATE avas_rotas_aprendizagem_objetos 
	  				SET ativo = 'N' 
	  				WHERE idobjeto = ".intval($this->post["remover"]);
            if($this->executaSql($this->sql)){
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 74;
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

}

?>