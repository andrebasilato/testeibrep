<?php
class Relatorio extends Core {

  function gerarRelatorio(){

    #VERIFICAÇÃO DO INTERVALO DE DATAS MENOR QUE UM ANO - ##### SEM DIA NA DATA
    $data_aux_de = '01/'.$_GET['competencia_de'];
    $data_aux_ate = '01/'.$_GET['competencia_ate'];

    if (
        ((!$data_aux_de || !$data_aux_ate) )
        ) {
        $retorno['erro'] = true;
        $retorno['erros'][] = 'datas_obrigatorias';
        return $retorno;
    }
    if (
        (dataDiferenca(formataData($data_aux_de, 'en', 0), formataData($data_aux_ate, 'en', 0), 'D') > 365)
        ) {
        $retorno['erro'] = true;
        $retorno['erros'][] = 'intervalo_maior_um_ano';
        return $retorno;
    }
    #VERIFICAÇÃO DO INTERVALO DE DATAS MENOR QUE UM ANO - ##### SEM DIA NA DATA - FIM

    $retorno = array();
    $idSituacoesRelatorios = NULL;

    $de = explode("/",$_GET["competencia_de"]);
    $ate = explode("/",$_GET["competencia_ate"]);
    $dataInicio = date("m/Y",mktime(0,0,0,$de[0],1,$de[1]));
    $dataFim = date("m/Y",mktime(0,0,0,$ate[0]+1,1,$ate[1]));


    $this->sql = "select idmotivo from motivos_cancelamento where retirar_comissao = 'S'";
    $this->limite = -1;
    $this->ordem = "asc";
    $this->ordem_campo = "idmotivo";
    $motivosCancelamento = $this->retornarLinhas();
    $idMotivosRetiraComissao = array();
    foreach($motivosCancelamento as $motivoCancelamento) {
      $idMotivosRetiraComissao[] = $motivoCancelamento["idmotivo"];
    }
    $idMotivosRetiraComissao = join(",",$idMotivosRetiraComissao);

    $idSituacaoCancelada = $this->retornaridSituacaoCancelada();

    /*
    $this->sql = "SELECT * FROM matriculas_workflow WHERE ativo='S'";
    $this->limite = -1;
    $this->ordem = "asc";
    $this->ordem_campo = "idsituacao";
    $situacoes = $this->retornarLinhas();
    $situacoesExcluidasArray = array();
    $situacoesIncluidasArray = array();
    foreach($situacoes as $situacao) {
      if($situacao["inicio"] == "S" || $situacao["aprovado_comercial"] == "S"){
            $situacoesExcluidasArray[] = $situacao["idsituacao"];
      } else {
            $situacoesIncluidasArray[] = $situacao["idsituacao"];
      }
    }
    $idSituacoesExcluidas = join(",",$situacoesExcluidasArray);
    $this->idSituacoesRelatorios = "&idsituacao[]=".join("&idsituacao[]=",$situacoesIncluidasArray);
    */


    /*if($_GET["idgrupo"]) {
      $idvendedor = array();
      $sql = "select idvendedor from grupos_vendedores_vendedores where idgrupo = ".$_GET["idgrupo"];
      $query = $this->executaSql($sql);
      while($vendedor = mysql_fetch_assoc($query)) {
        $idvendedor[] = $vendedor['idvendedor'];
      }
    }*/

    $this->sql = "select idsindicato, nome_abreviado from sindicatos where ativo = 'S' and ativo_painel = 'S'";
    if($_GET["idsindicato"]) {
      $this->sql .= " and idsindicato in (".implode(", ", $_GET["idsindicato"]).")";
    } elseif($_SESSION['adm_gestor_sindicato'] != 'S') {
        $this->sql .= ' and idsindicato in ('.$_SESSION['adm_sindicatos'].')';
    }

    //echo $this->sql;

    $this->limite = -1;
    $this->ordem = "asc";
    $this->ordem_campo = "nome";
    $retorno["sindicatos"] = $this->retornarLinhas();

    foreach($retorno["sindicatos"] as $indSindicato => $sindicato) {
      $this->sql = "select
                      cr.idregra,
                      cr.nome
                    from
                      comissoes_regras cr
                      inner join comissoes_competencias_cursos ccc on (cr.idregra = ccc.idregra and ccc.ativo = 'S')
                      inner join comissoes_competencias cc on (ccc.idcompetencia = cc.idcompetencia and cc.ativo = 'S')
                    where
                      cc.idsindicato = ".$sindicato["idsindicato"]." and
                      cr.ativo = 'S'";
      if($_GET["idregra"]) $this->sql .= " and cr.idregra = ".$_GET["idregra"];
      $this->sql .= " group by cr.idregra";

      $this->limite = -1;
      $this->ordem = "asc";
      $this->ordem_campo = "cr.nome";
      $regras = $this->retornarLinhas();
      if(count($regras) == 0) {
        unset($retorno["sindicatos"][$indSindicato]);
        continue;
      }
      foreach($regras as $indRegra => $regra) {
        $this->sql = "select idcurso from comissoes_competencias_cursos where idregra = ".$regra["idregra"]." and ativo = 'S' group by idcurso";
        $this->limite = -1;
        $this->ordem = false;
        $this->ordem_campo = false;
        $cursos = $this->retornarLinhas();
        $cursosArray = array();
        foreach($cursos as $curso) {
          $cursosArray[] = $curso['idcurso'];
        }
        $cursosArray = implode(',',$cursosArray);
        $regras[$indRegra]["cursos"] = $cursosArray;

        $deMes = $de[0];
        for($data = $dataInicio;$data != $dataFim;$data = date("m/Y",mktime(0,0,0,++$deMes,1,$de[1]))) {

          $this->sql = "select
                          cc.de,
                          cc.ate
                        from
                          comissoes_competencias cc
                          inner join comissoes_competencias_cursos ccc on (cc.idcompetencia = ccc.idcompetencia)
                        where
                          ccc.idregra = ".$regra["idregra"]." and
                          date_format(cc.mes,'%m/%Y') = '".$data."' and
                          cc.ativo = 'S' and
                          ccc.ativo = 'S'
                        order by cc.data_cad desc, ccc.data_cad desc
                        limit 1";
          $regras[$indRegra]["competencias"][$data] = $this->retornarLinha($this->sql);
        }
      }

      $this->sql = "select
                      v.idvendedor,
                      v.nome,
                      vi.idvendedor_sindicato
                    from
                      vendedores v
                      inner join vendedores_sindicatos vi on (v.idvendedor = vi.idvendedor and vi.ativo = 'S')
                    where
                      vi.idsindicato = ".$sindicato["idsindicato"]." and
                      v.ativo = 'S' and
                      v.ativo_login = 'S'";
      if($_GET["idvendedor"]) $this->sql .= " and v.idvendedor = ".$_GET["idvendedor"];
      if(count($idvendedor) > 0) $this->sql .= " and v.idvendedor in (".implode(", ", $idvendedor).")";
      $this->limite = -1;
      $this->ordem = "asc";
      $this->ordem_campo = "v.nome";
      $retorno["sindicatos"][$indSindicato]["vendedores"] = $this->retornarLinhas();
      if(count($retorno["sindicatos"][$indSindicato]["vendedores"]) == 0) {
        unset($retorno["sindicatos"][$indSindicato]);
        continue;
      }
      foreach($retorno["sindicatos"][$indSindicato]["vendedores"] as $indVendedor => $vendedor) {
        foreach($regras as $indRegra => $regra) {
          $competencias = $regra["competencias"];
          $cursos = $regra["cursos"];
          unset($regra["competencias"]);
          unset($regra["cursos"]);
          $retorno["sindicatos"][$indSindicato]["vendedores"][$indVendedor]["regras"][$indRegra] = $regra;
          foreach($competencias as $data => $competencia) {
            //if($competencia["idregra"]) {

              $this->sql = "select
                              ifnull(sum(m.valor_contrato), 0) as total,
                              ifnull(count(1), 0) as quantidade
                            from
                              matriculas m
                              INNER JOIN matriculas_workflow mw ON (mw.idsituacao = m.idsituacao)
                            where
                              m.idvendedor = ".$vendedor["idvendedor"]." and
                              m.idcurso in (".$cursos.") and
                              m.data_comissao >= '".$competencia["de"]."' and
                              m.data_comissao <= '".$competencia["ate"]."' and
                              m.ativo = 'S'";   // idsindicato = '".$sindicato["idsindicato"]."' and
                              // idsituacao not in (".$idSituacoesExcluidas.") and
              if($idMotivosRetiraComissao) $this->sql .= " and (m.idsituacao <> '$idSituacaoCancelada' OR (m.idmotivo_cancelamento not in (".$idMotivosRetiraComissao.") or m.idmotivo_cancelamento is null))";
              if($_GET["idescola"]) $this->sql .= " and m.idescola = ".$_GET["idescola"];

              if ($_GET["idsindicato"]) {
                $this->sql .= " and m.idsindicato IN(".implode(",", $_GET["idsindicato"]).")";
              }

              $total = $this->retornarLinha($this->sql);

              $this->sql = "select valor from comissoes_regras_valores where ativo = 'S' and idregra = ".$regra["idregra"]." order by valor, porcentagem asc limit 1";
              $valorInicial = $this->retornarLinha($this->sql);
              if(!$valorInicial['valor']) {
                $valorInicial['valor'] = 0;
              }

              $valorComissao = $total["total"] - $valorInicial['valor'];
              if($valorComissao < 0) {
                $valorComissao = 0;
              }

              $this->sql = "select porcentagem from comissoes_regras_valores where idregra = ".$regra["idregra"]." and ativo = 'S' and valor <= ".$total["total"]." order by valor desc, porcentagem desc limit 1";
              $porcentagemComissao = $this->retornarLinha($this->sql);
              if(!$porcentagemComissao['porcentagem']) {
                $comissao = 0;
              } else {
                $comissao = ($valorComissao * $porcentagemComissao["porcentagem"]) / 100;
              }
            $retorno["sindicatos"][$indSindicato]["vendedores"][$indVendedor]["regras"][$indRegra]["comissao"][$data]["comissao"] = $comissao;
            $retorno["sindicatos"][$indSindicato]["vendedores"][$indVendedor]["regras"][$indRegra]["comissao"][$data]["quantidade"] = $total["quantidade"];
            $retorno["sindicatos"][$indSindicato]["vendedores"][$indVendedor]["regras"][$indRegra]["comissao"][$data]["total_vendido"] = $total["total"];
            $retorno["sindicatos"][$indSindicato]["vendedores"][$indVendedor]["regras"][$indRegra]["comissao"][$data]["de"] = $competencia["de"];
            $retorno["sindicatos"][$indSindicato]["vendedores"][$indVendedor]["regras"][$indRegra]["comissao"][$data]["ate"] = $competencia["ate"];
          }
        }
      }
    }

    return $retorno;
  }

  function GerarTabela($linhas,$q = null,$idioma,$configuracao = "listagem") {
    echo '<table class="zebra-striped">';
      echo '<thead>';
        echo '<tr>';
          echo '<th>Filtro</th>';
          echo '<th>Valor</th>';
        echo '</tr>';
      echo '</thead>';
      foreach($this->config["formulario"] as $ind => $fieldset){
        foreach($fieldset["campos"] as $ind => $campo){
          if($campo["nome"][0] == "q"){
            $campoAux = str_replace(array("q[","]"),"",$campo["nome"]);
            $campoAux = $_GET["q"][$campoAux];
            if($campo["sql_filtro"] && $campoAux) {
              if($campo["sql_filtro"] == "array") {
                $campoAux = $GLOBALS[$campo["sql_filtro_label"]][$GLOBALS["config"]["idioma_padrao"]][$campoAux];
              } else {
                $sql = str_replace("%",$campoAux,$campo["sql_filtro"]);
                $linha = $this->retornarLinha($sql);
                $campoAux = $linha[$campo["sql_filtro_label"]];
              }
            }
          } elseif(is_array($_GET[$campo["nome"]])){
            if($campo["array"]){
              foreach($_GET[$campo["nome"]] as $ind => $val){
                $_GET[$campo["nome"]][$ind] = $GLOBALS[$campo["array"]][$GLOBALS["config"]["idioma_padrao"]][$val];
              }
            } elseif($campo["sql_filtro"]){
              foreach($_GET[$campo["nome"]] as $ind => $val){
                $sql = str_replace("%",$val,$campo["sql_filtro"]);
                $linha = $this->retornarLinha($sql);
                $_GET[$campo["nome"]][$ind] = $linha[$campo["sql_filtro_label"]];
              }
            }

            $campoAux = implode($_GET[$campo["nome"]], ", ");
          } elseif($_GET[$campo["nome"]] && $campo["array"]){
            $campoAux = $GLOBALS[$campo["array"]][$GLOBALS["config"]["idioma_padrao"]][$_GET[$campo["nome"]]];
          } elseif($_GET[$campo["nome"]] && $campo["sql_filtro"]){
            $sql = str_replace("%",$_GET[$campo["nome"]],$campo["sql_filtro"]);
            $linha = $this->retornarLinha($sql);
            $campoAux = $linha[$campo["sql_filtro_label"]];
          } else {
            $campoAux = $_GET[$campo["nome"]];
          }
          if($campoAux <> ""){
            echo '<tr>';
            echo '<td><strong>'.$idioma[$campo["nomeidioma"]].'</strong></td>';
            echo '<td>'.$campoAux.'</td>';
            echo '</tr>';
          }
        }
      }

      /*foreach($this->config["formulario"] as $ind => $fieldset){
        foreach($fieldset["campos"] as $ind => $campo){
          if($_GET[$campo["nome"]] && $campo["sql_filtro"]){
            $sql = str_replace("%",$_GET[$campo["nome"]],$campo["sql_filtro"]);
            $linha = $this->retornarLinha($sql);
            $campoAux = $linha[$campo["sql_filtro_label"]];
          } else {
            $campoAux = $_GET[$campo["nome"]];
          }
          if($campoAux <> ""){
            echo '<tr>';
            echo '<td><strong>'.$idioma[$campo["nomeidioma"]].'</strong></td>';
            echo '<td>'.$campoAux.'</td>';
            echo '</tr>';
          }
        }
      }*/

    echo '</table><br>';

    echo '<table class="zebra-striped">';
      echo '<thead>';
        echo '<tr>';
          echo '<th colspan="3" class="headerSortReloca">';
            echo "<div>&nbsp;</div>";
          echo '</th>';
          $de = explode("/",$_GET["competencia_de"]);
          $ate = explode("/",$_GET["competencia_ate"]);
          $dataInicio = date("m/Y",mktime(0,0,0,$de[0],1,$de[1]));
          $dataFim = date("m/Y",mktime(0,0,0,$ate[0]+1,1,$ate[1]));
          $deMes = $de[0];
          $totalMeses = 0;
          for($data = $dataInicio;$data != $dataFim;$data = date("m/Y",mktime(0,0,0,++$deMes,1,$de[1]))) {
            $totalMeses = $totalMeses + 3;
            echo '<th colspan="3" class="headerSortReloca">';
              echo "<div align='center'>".$data."</div>";
            echo '</th>';
          }
        echo '</tr>';
        echo '<tr>';
          echo '<td class="headerSortReloca">';
            echo "<div><strong>".$idioma["sindicato"]."</strong></div>";
          echo '</td>';
          echo '<td class="headerSortReloca">';
            echo "<div><strong>".$idioma["vendedor"]."</strong></div>";
          echo '</td>';
          echo '<td class="headerSortReloca">';
            echo "<div><strong>".$idioma["regra"]."</strong></div>";
          echo '</td>';
          $de = explode("/",$_GET["competencia_de"]);
          $ate = explode("/",$_GET["competencia_ate"]);
          $dataInicio = date("m/Y",mktime(0,0,0,$de[0],1,$de[1]));
          $dataFim = date("m/Y",mktime(0,0,0,$ate[0]+1,1,$ate[1]));
          $deMes = $de[0];
          for($data = $dataInicio;$data != $dataFim;$data = date("m/Y",mktime(0,0,0,++$deMes,1,$de[1]))) {
            echo '<td class="headerSortReloca">';
              echo "<div><strong>".$idioma["quantidade"]."</strong></div>";
            echo '</td>';
            echo '<td class="headerSortReloca">';
              echo "<div><strong>".$idioma["vendido"]."</strong></div>";
            echo '</td>';
            echo '<td class="headerSortReloca">';
              echo "<div><strong>".$idioma["valor"]."</strong></div>";
            echo '</td>';
          }
        echo '</tr>';
      echo '</thead>';
      echo '<tbody>';
        //print_r2($linhas,true);
        if(count($linhas["sindicatos"]) == 0){
          echo '<tr>';
            echo '<td colspan="'.($totalMeses+3).'">'.$idioma["nenhuma_informacao"].'</td>';
          echo '</tr>';
        } else {
          $totalComissao = array();
          $totalQtdComissao = array();
          $totalVendido = array();
          foreach($linhas["sindicatos"] as $sindicato) {
            $countVendedores = 0;
            foreach($sindicato["vendedores"] as $vendedor) {
              $countVendedores++;
              $countRegras = 0;
              $totalComissaoVendedor = array();
              $totalQtdComissaoVendedor = array();
              $totalVendidoVendedor = array();

              $de_comissao = array();
              $ate_comissao = array();

              foreach($vendedor["regras"] as $regra) {

                $countRegras++;
                echo '<tr>';
                  if($countVendedores == 1 && $countRegras == 1) { echo '<td rowspan="'.((count($sindicato["vendedores"])*count($vendedor["regras"]))+(count($sindicato["vendedores"]))).'">'.$sindicato["nome_abreviado"].'</td>'; }
                  if($countRegras == 1) { echo '<td rowspan="'.(count($vendedor["regras"])+1).'" style="text-transform:uppercase;">'.$vendedor["nome"].'</td>'; }
                  echo '<td style="text-transform:uppercase;">'.$regra["nome"].'</td>';
                  $deMes = $de[0];
                  $contador = 0;
                  for($data = $dataInicio;$data != $dataFim;$data = date("m/Y",mktime(0,0,0,++$deMes,1,$de[1]))) {
                    if($regra["comissao"][$data]["de"])
                        $de_comissao[$data] = $regra["comissao"][$data]["de"];
                    if($regra["comissao"][$data]["ate"])
                        $ate_comissao[$data] = $regra["comissao"][$data]["ate"];

                    if($contador) {
                      $color = "#FFFFFF";
                      $contador--;
                    } else {
                      $color = "#F4F4F4";
                      $contador++;
                    }
                    //$comissaoVendedor = "--";
                    //if(is_float($regra["comissao"][$data])) {
                      $totalComissao[$data] += $regra["comissao"][$data]["comissao"];
                      $totalQtdComissao[$data] += $regra["comissao"][$data]["quantidade"];
                      $totalVendido[$data] += $regra["comissao"][$data]["total_vendido"];

                      $totalComissaoVendedor[$data] += $regra["comissao"][$data]["comissao"];
                      $totalQtdComissaoVendedor[$data] += $regra["comissao"][$data]["quantidade"];
                      $totalVendidoVendedor[$data] += $regra["comissao"][$data]["total_vendido"];

                      $valorTotalVendidoVendedor = number_format($regra["comissao"][$data]["total_vendido"],2,",",".");
                      $comissaoVendedor = number_format($regra["comissao"][$data]["comissao"],2,",",".");
                    //}
                    echo '<td style="background-color: '.$color.';" width="30">'.$regra["comissao"][$data]["quantidade"].'</td>';
                    echo '<td style="background-color: '.$color.';" width="30">'.$valorTotalVendidoVendedor.'</td>';
                    echo '<td style="background-color: '.$color.';" width="60">'.$comissaoVendedor.'</td>';
                  }
                echo '</tr>';
              }
              echo '<tr>';
                echo '<td style="text-align:right;background-color:#E4E4E4;">'.$idioma["subtotal"].'</td>';
                $deMes = $de[0];
                //$contador = 0;
                for($data = $dataInicio;$data != $dataFim;$data = date("m/Y",mktime(0,0,0,++$deMes,1,$de[1]))) {
                  /*if($contador) {
                    $color = "#FFFFFF";
                    $contador--;
                  } else {
                    $color = "#E4E4E4";
                    $contador++;
                  }*/
                  //$comissaoVendedorTotal = "--";
                  //if(array_key_exists($data, $totalComissaoVendedor)) {
                    $valorTotalVendido = number_format($totalVendidoVendedor[$data],2,",",".");
                    $comissaoVendedorTotal = number_format($totalComissaoVendedor[$data],2,",",".");
                  //}
                  echo '<td style="background-color:#E4E4E4;"><strong><a href="/gestor/relatorios/vendas_detalhado/html?de_comissao='.formataData($de_comissao[$data],'br',0).'&ate_comissao='.formataData($ate_comissao[$data],'br',0).'&q[1|ma.idescola]='.$_GET["idescola"].'&q[1|ma.idvendedor]='.$vendedor["idvendedor"].'&colunas[]=1&colunas[]=2&colunas[]=3&colunas[]=4&colunas[]=5&colunas[]=6&colunas[]=7&colunas[]=8&colunas[]=9&colunas[]=10&colunas[]=11&colunas[]=12&colunas[]=13&colunas[]=14&colunas[]=15" target="_blank">'.$totalQtdComissaoVendedor[$data].'</a></strong></td>'; // $this->idSituacoesRelatorios.
                  echo '<td style="background-color:#E4E4E4;"><strong>'.$valorTotalVendido.'</strong></td>';
                  echo '<td style="background-color:#E4E4E4;"><strong>'.$comissaoVendedorTotal.'</strong></td>';
                }
              echo '</tr>';
            }
          }

          /*
          echo '<tr>';
            echo '<td colspan="3" style="text-align:right;background-color:#E4E4E4;">'.$idioma["total"].'</td>';
            $deMes = $de[0];
            //$contador = 0;
            for($data = $dataInicio;$data != $dataFim;$data = date("m/Y",mktime(0,0,0,++$deMes,1,$de[1]))) {
                $valorTotal = number_format($totalVendido[$data],2,",",".");
              echo '<td style="background-color:#E4E4E4;"><strong>'.$totalQtdComissao[$data].'</strong></td>';
              echo '<td style="background-color:#E4E4E4;"><strong>'.$valorTotal.'</strong></td>';
              echo '<td style="background-color:#E4E4E4;"><strong>'.$comissaoTotal.'</strong></td>';
            }
            echo '</tr>';
           */


        }
      echo '</tbody>';
    echo '</table>';
  }

  public function retornaridSituacaoCancelada()
  {
    $sql = "SELECT idsituacao FROM matriculas_workflow WHERE cancelada = 'S'";
    $situacao = $this->retornarLinha($sql);
    return $situacao["idsituacao"];
  }

}

?>