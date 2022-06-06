<?php
class Etiquetas extends Core
{
    const CURRENT_TABLE = 'etiquetas';
    const PRIMARY_KEY = 'idetiqueta';

    public $pxCm = 0.02645833333333;//Tamanho em centímetors de 1px

    public function ListarTodas()
    {

        $this->sql = "SELECT {$this->campos} FROM ".self::CURRENT_TABLE." where ativo='S'";
        $this->aplicarFiltrosBasicos()->set('groupby', self::PRIMARY_KEY);
        return $this->retornarLinhas();
    }


    public function Retornar()
    {
        $this->sql = "SELECT ".$this->campos."
                            FROM
                             etiquetas where ativo='S' and idetiqueta='".$this->id."'";
        return $this->retornarLinha($this->sql);
    }

    public function Cadastrar()
    {
        $post['margem_top'] = str_replace(',','.',str_replace('.','',$post['margem_top']));
        $post['margem_bottom'] = str_replace(',','.',str_replace('.','',$post['margem_bottom']));
        $post['margem_left'] = str_replace(',','.',str_replace('.','',$post['margem_left']));
        $post['margem_right'] = str_replace(',','.',str_replace('.','',$post['margem_right']));
        $post['espaco_linhas'] = str_replace(',','.',str_replace('.','',$post['espaco_linhas']));
        $post['espaco_colunas'] = str_replace(',','.',str_replace('.','',$post['espaco_colunas']));
        return $this->SalvarDados();
    }

    public function Modificar()
    {
        return $this->SalvarDados();
    }

    public function Remover()
    {
        return $this->RemoverDados();
    }

    public function gerarEtiquetas($idioma = null)
    {
        if($this->id){
            //ETIQUETA
            $this->sql = "SELECT * FROM etiquetas WHERE idetiqueta = ".$this->id;
            $etiqueta = $this->retornarLinha($this->sql);
            $documento = $etiqueta["etiqueta"];

			$etiqueta['linha_a_partir'] = 0;
			if ($_POST['linha_a_partir']) {
				$etiqueta['linha_a_partir'] = $_POST['linha_a_partir'];
			}

			$etiqueta['coluna_a_partir'] = 0;
			if ($_POST['coluna_a_partir'])
				$etiqueta['coluna_a_partir'] = $_POST['coluna_a_partir'];

            $this->sql = "SELECT
                                p.idpessoa,
                                p.nome,
                                p.endereco,
                                p.numero,
                                p.bairro,
                                p.complemento,
                                p.cep,
                                e.nome as estado,
                                c.nome as cidade,
                                m.idmatricula,
								m.data_matricula
                        FROM
                            matriculas m
                        INNER JOIN ofertas_cursos_escolas ocp
                        ON
                            m.idoferta = ocp.idoferta AND
                            m.idcurso = ocp.idcurso AND
                            m.idescola = ocp.idescola AND
                            ocp.ativo = 'S'
                        INNER JOIN pessoas p
                        ON m.idpessoa = p.idpessoa
                        LEFT OUTER JOIN estados e
                        ON p.idestado = e.idestado
                        LEFT OUTER JOIN cidades c
                        ON p.idcidade = c.idcidade
                        WHERE m.ativo = 'S' ";


                            if($_POST['tipo_data']) {
                              if($_POST['tipo_data'] == 'HOJ') {
                                  $this->sql .= " AND date_format(m.data_cad,'%Y-%m-%d') = '".date("Y-m-d")."'";
                              } else if($_POST['tipo_data'] == 'SET') {
                                  $this->sql .= " AND date_format(m.data_cad,'%Y-%m-%d') <= '".date("Y-m-d")."'
                                                  AND date_format(m.data_cad,'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")))."'   ";
                              } else if($_POST['tipo_data'] == 'MAT') {
                                  $this->sql .= " AND date_format(m.data_cad,'%Y-%m') = '".date("Y-m")."'";
                              } else if($_POST['tipo_data'] == 'MPR') {
                                  $this->sql .= " AND date_format(m.data_cad,'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")))."'";
                              } else if($_POST['tipo_data'] == 'MAN') {
                                  $this->sql .= " AND date_format(m.data_cad,'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")))."'";
                              } else if($_POST['tipo_data'] == 'PER') {
                                if($_POST['periodo_inicio'])
                                  $this->sql .= " AND DATE_FORMAT(m.data_cad,'%Y-%m-%d') >= '".formataData($_POST['periodo_inicio'],'en',0)."' ";
                                if($_POST['periodo_final'])
                                  $this->sql .= " AND DATE_FORMAT(m.data_cad,'%Y-%m-%d') <= '".formataData($_POST['periodo_final'],'en',0)."'   ";
                              }
                            }

                            /*if ($_POST['idoferta'] || $_POST['idcurso'] || $_POST['pessoas'] || $_POST['matriculas']) {
                                $this->sql .= " AND (";
                                $tem_filtro = true;
                            }*/

                            if($_POST['idoferta']) {
                                $this->sql .= "AND ocp.idoferta = '".$_POST['idoferta']."' ";
                                /*if ($_POST['idcurso'] || $_POST['pessoas'] || $_POST['matriculas'])
                                    $this->sql .= ' OR ';*/
                            }

                            if($_POST['idcurso']) {
                                $this->sql .= "AND ocp.idcurso = '".$_POST['idcurso']."' ";
                                /*if ($_POST['pessoas'] || $_POST['matriculas'])
                                    $this->sql .= ' OR ';*/
                            }

                            /*if ($_POST['pessoas'] && $_POST['matriculas']) {
                                $this->sql .= " ((m.idpessoa IN(".implode(',',$_POST['pessoas']).")) OR (m.idmatricula IN(".implode(',',$_POST['matriculas'])."))) OR ";
                            } else*/
                            if ($_POST['pessoas']) {
                                $this->sql .= "AND m.idpessoa IN(".implode(',',$_POST['pessoas']).") ";
                                /*if ($_POST['matriculas'])
                                    $this->sql .= ' OR ';*/
                            }
                            if ($_POST['matriculas']) {
                                $this->sql .= "AND m.idmatricula IN(".implode(',',$_POST['matriculas']).") ";
                            }

                            /*if ($tem_filtro) {
                                $this->sql .= " )";
                            }*/
                            /*if($_POST['pessoas']) {
                                $this->sql .= " AND m.idpessoa IN(".implode(',',$_POST['pessoas']).") ";
                            }

                            if($_POST['matriculas']) {
                                $this->sql .= " AND m.idmatricula IN(".implode(',',$_POST['matriculas']).") ";

                            }*/
            $this->sql  .=  " GROUP BY m.idmatricula ";
            $this->limite = -1;
            $matriculas = $this->retornarLinhas();
            //echo $this->sql;print_r2($matriculas,true);

            $coluna_controle = 0;
            $numero_colunas = $etiqueta['colunas'];
            if(!$numero_colunas)
                $numero_colunas = 2;

            $linha_controle = 0;
            $numero_linhas = $etiqueta['linhas'];
            if(!$numero_linhas)
                $numero_linhas = 5;

            $numero_linhas = intval($numero_linhas * $numero_colunas);

            //Irá calcular a largura da tabela, baseada na quantidade de colunas, espacos entre as colunas e largura das etiquetas
            if ($etiqueta['largura'] > 0) {
                $largura_tabela = (($numero_colunas * $etiqueta['largura'] / $this->pxCm) + (($numero_colunas - 1) * $etiqueta['espaco_colunas'] / $this->pxCm));
                $largura_tabela = number_format($largura_tabela, 2, '.', '')."px";
            } else {
                $largura_tabela = "100%";
            }

            if ($etiqueta['largura'] > 0) {
                $largura_etiqueta = ($etiqueta['largura'] / $this->pxCm);
                $largura_etiqueta = number_format($largura_etiqueta, 2, '.', '')."px";
            } else {
                $largura_etiqueta = intval(100 / $numero_colunas)."%";
            }

            if ($etiqueta['altura'] > 0) {
                $altura_etiqueta = ($etiqueta['altura'] / $this->pxCm);
                $altura_etiqueta = number_format($altura_etiqueta, 2, '.', '')."px";
            } else {
                $altura_etiqueta = "auto";
            }

            $estiloTd = "position:absolute;";

            $retorno = "<!DOCTYPE html><html><head><title></title></head><body>";

            /*Verifica se tem matrículas
            Irá gerar as linhas em branco apenas caso tenha retornado alguém para gerar as etiquetas*/
            if (count($matriculas) > 0) {
                //Irá gerar as linhas em branco, a apartir do que o usuário informou
                for ($nLinha = 1; $nLinha < $etiqueta['linha_a_partir']; $nLinha++) {

                    for ($coluna = 1; $coluna <= $numero_colunas; $coluna++) {

						//Se tiver gerado a quantidade de linhas por página, irá inserir a quebra de página
                        if($linha_controle++ == $numero_linhas) {
                            $linha_controle = 0;
                            $retorno .= "[[QUEBRA_DE_PAGINA]]";
                        }

                        if(!$coluna_controle) {
                            $retorno .= "<table width='".$largura_tabela."' height='".$altura_etiqueta."' border='0' cellpadding='0' cellspacing='0'><tr>";
                        } else {
                            $retorno .= "<td style='width:".number_format(($etiqueta['espaco_colunas'] / $this->pxCm), 2, '.', '')."px;'>&nbsp;</td>";
                        }

                        $documento_temp = str_replace('color','',$documento_temp);
                        $retorno .= "<td width='".$largura_etiqueta."' height='".$altura_etiqueta."' style='color:#000000; ".$estiloTd."'>
                                        <div style='width: ".$largura_etiqueta."; height: ".$altura_etiqueta.";'>&nbsp;</div>
                                    </td>";

                        if(++$coluna_controle == $numero_colunas) {
                            $coluna_controle = 0;
                            $retorno .= "</tr></table><div style='height:".number_format(($etiqueta['espaco_linhas'] / $this->pxCm), 2, '.', '')."px;'></div>";//<br />
                        }

                    }
                }

                //Caso a pessoa coloque mais colunas do que a configuração da etiqueta, irá receber o valor da configuração
                $apartirColuna = $etiqueta['coluna_a_partir'];
                if ($apartirColuna > $numero_colunas) {
                    $apartirColuna = $numero_colunas;
                }

                //Irá gerar as colunas em branco, a apartir do que o usuário informou
                for ($nColuna = 1; $nColuna < $apartirColuna; $nColuna++) {

					//Se tiver gerado a quantidade de linhas por página, irá inserir a quebra de página
                    if($linha_controle++ == $numero_linhas) {
                        $linha_controle = 0;
                        $retorno .= "[[QUEBRA_DE_PAGINA]]";
                    }

                    if(!$coluna_controle) {
                        $retorno .= "<table width='".$largura_tabela."' height='".$altura_etiqueta."' border='0' cellpadding='0' cellspacing='0'><tr>";
                    } else {
                        $retorno .= "<td style='width:".number_format(($etiqueta['espaco_colunas'] / $this->pxCm), 2, '.', '')."px;'>&nbsp;</td>";
                    }

                    $documento_temp = str_replace('color','',$documento_temp);
                    $retorno .= "<td width='".$largura_etiqueta."' height='".$altura_etiqueta."' style='color:#000000; ".$estiloTd."'>
                                    <div style='width: ".$largura_etiqueta."; height: ".$altura_etiqueta.";'>&nbsp;</div>
                                </td>";

                    if(++$coluna_controle == $numero_colunas) {
                        $coluna_controle = 0;
                        $retorno .= "</tr></table><div style='height:".number_format(($etiqueta['espaco_linhas'] / $this->pxCm), 2, '.', '')."px;'></div>";//<br />
                    }

                }
            }//Fim verifica se tem matrículas

            foreach ($matriculas as $matricula) {

				if($linha_controle++ == $numero_linhas) {
                    $linha_controle = 0;
                    $retorno .= "[[QUEBRA_DE_PAGINA]]";
                }

				$total_colunas_ultima_linha++;

                unset($documento_temp);
                $documento_temp = $documento;

                $documento_temp = str_ireplace("[[cliente][idpessoa]]",$matricula['idpessoa'],$documento_temp);
                $documento_temp = str_ireplace("[[cliente][nome]]",$matricula['nome'],$documento_temp);
                $documento_temp = str_ireplace("[[cliente][endereco]]",$matricula['endereco'],$documento_temp);
                $documento_temp = str_ireplace("[[cliente][numero]]",$matricula['numero'],$documento_temp);
                $documento_temp = str_ireplace("[[cliente][bairro]]",$matricula['bairro'],$documento_temp);
                $documento_temp = str_ireplace("[[cliente][complemento]]",$matricula['complemento'],$documento_temp);
                $documento_temp = str_ireplace("[[cliente][cidade]]",$matricula['cidade'],$documento_temp);
                $documento_temp = str_ireplace("[[cliente][estado]]",$matricula['estado'],$documento_temp);
                $documento_temp = str_ireplace("[[cliente][cep]]",$matricula['cep'],$documento_temp);
                $documento_temp = str_ireplace("[[matricula]]",$matricula['idmatricula'],$documento_temp);
                $documento_temp = str_ireplace("[[data_matricula]]",formataData($matricula['data_matricula'],'pt-br',0),$documento_temp);
                $documento_temp = str_ireplace("[[data_geracao_etiqueta]]",date("d/m/Y"),$documento_temp);

                if(!$coluna_controle) {
                    $retorno .= "<table width='".$largura_tabela."' height='".$altura_etiqueta."' border='0' cellpadding='0' cellspacing='0'><tr>";
                } else {
                    $retorno .= "<td style='width:".number_format(($etiqueta['espaco_colunas'] / $this->pxCm), 2, '.', '')."px;'>&nbsp;</td>";
                }

                $retorno .= "<td width='".$largura_etiqueta."' height='".$altura_etiqueta."' style='".$estiloTd."'>
                                <div style='width: ".$largura_etiqueta."; height: ".$altura_etiqueta.";'>".$documento_temp."</div>
                            </td>";

                if(++$coluna_controle == $numero_colunas) {
                    $coluna_controle = 0;
					$total_colunas_ultima_linha = 0;
                    $retorno .= "</tr></table><div style='height:".number_format(($etiqueta['espaco_linhas'] / $this->pxCm), 2, '.', '')."px;'></div>";//<br />
                }

            }

            //$sobra = count($matriculas) % $numero_colunas;
			$sobra = $numero_colunas - $total_colunas_ultima_linha;

			if ($sobra == $numero_colunas)
				$sobra = 0;

            if($sobra) {
				//$totalSobra = $numero_colunas - $sobra;
				#echo $totalSobra . ' - ' . $numero_colunas . ' - ' . $sobra; exit;
                for ($x = 0; $x < $sobra; $x++) {
					$retorno .= "<td style='width:".number_format(($etiqueta['espaco_colunas'] / $this->pxCm), 2, '.', '')."px;'>&nbsp;</td>";
                    $retorno .= "<td width='".$largura_etiqueta."' height='".$altura_etiqueta."' style='color:#000000; ".$estiloTd."'>
									<div style='width: ".$largura_etiqueta."; height: ".$altura_etiqueta.";'>&nbsp;</div>
								</td>";
				}
                $retorno .= "</tr></table><div style='height:".number_format(($etiqueta['espaco_linhas'] / $this->pxCm), 2, '.', '')."px;'></div>";//<br />
            }

            $retorno .= "</body></html>";

			//echo $retorno;exit;

            if($documento){
                $this->retorno["sucesso"] = true;
                $this->retorno["etiquetas"] = $retorno;
            }else{
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }else{
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = "etiqueta_vazio";
        }

        return $this->retorno;

    }

    public function gerarEtiquetasPreview($idioma = null)
    {
        if($this->id){
            //ETIQUETA
            $this->sql = "SELECT * FROM etiquetas WHERE idetiqueta = ".$this->id;
            $etiqueta = $this->retornarLinha($this->sql);
            $documento = $etiqueta["etiqueta"];

            //echo $this->sql;print_r2($matriculas,true);

            $coluna_controle = 0;
            $numero_colunas = $etiqueta['colunas'];
            if(!$numero_colunas)
                $numero_colunas = 2;

            $linha_controle = 0;
            $numero_linhas = $etiqueta['linhas'];
            if(!$numero_linhas)
                $numero_linhas = 5;

            $numero_linhas = intval($numero_linhas * $numero_colunas);

            //Irá calcular a largura da tabela, baseada na quantidade de colunas, espacos entre as colunas e largura das etiquetas
            if ($etiqueta['largura'] > 0) {
                $largura_tabela = (($numero_colunas * $etiqueta['largura'] / $this->pxCm) + (($numero_colunas - 1) * $etiqueta['espaco_colunas'] / $this->pxCm));
                $largura_tabela = number_format($largura_tabela, 2, '.', '')."px";
            } else {
                $largura_tabela = "100%";
            }

            if ($etiqueta['largura'] > 0) {
                $largura_etiqueta = ($etiqueta['largura'] / $this->pxCm);
                $largura_etiqueta = number_format($largura_etiqueta, 2, '.', '')."px";
            } else {
                $largura_etiqueta = intval(100 / $numero_colunas)."%";
            }

            if ($etiqueta['altura'] > 0) {
                $altura_etiqueta = ($etiqueta['altura'] / $this->pxCm);
                $altura_etiqueta = number_format($altura_etiqueta, 2, '.', '')."px";
            } else {
                $altura_etiqueta = "auto";
            }

            $estiloTd = "position:absolute;";

            $qtde_opcoes = 30;

            $retorno = "<!DOCTYPE html><html><head><title></title></head><body>";

            /*//Irá gerar as linhas em branco, a apartir do que o usuário informou
            for ($nLinha = 1; $nLinha < $etiqueta['linha_a_partir']; $nLinha++) {

                for ($coluna = 1; $coluna <= $numero_colunas; $coluna++) {
                    if(!$coluna_controle) {
                        $retorno .= "<table width='".$largura_tabela."' height='".$altura_etiqueta."' border='0' cellpadding='0' cellspacing='0'><tr>";
                    } else {
                        $retorno .= "<td style='width:".number_format(($etiqueta['espaco_colunas'] / $this->pxCm), 2, '.', '')."px;'>&nbsp;</td>";
                    }

                    $documento_temp = str_replace('color','',$documento_temp);
                    $retorno .= "<td width='".$largura_etiqueta."' height='".$altura_etiqueta."' style='color:#000000; ".$estiloTd."'>
                                    <div style='width: ".$largura_etiqueta."; height: ".$altura_etiqueta.";'>&nbsp;</div>
                                </td>";

                    if(++$coluna_controle == $numero_colunas) {
                        $coluna_controle = 0;
                        $retorno .= "</tr></table><div style='height:".number_format(($etiqueta['espaco_linhas'] / $this->pxCm), 2, '.', '')."px;'></div>";//<br />
                    }

                    //Se tiver gerado a quantidade de linhas por página, irá inserir a quebra de página
                    if(++$linha_controle == $numero_linhas) {
                        $linha_controle = 0;
                        $retorno .= "[[QUEBRA_DE_PAGINA]]";
                    }

                }
            }*/

            /*//Caso a pessoa coloque mais colunas do que a configuração da etiqueta, irá receber o valor da configuração
            $apartirColuna = $etiqueta['coluna_a_partir'];
            if ($apartirColuna > $numero_colunas) {
                $apartirColuna = $numero_colunas;
            }
            //Irá gerar as colunas em branco, a apartir do que o usuário informou
            for ($nColuna = 1; $nColuna < $apartirColuna; $nColuna++) {

                if(!$coluna_controle) {
                    $retorno .= "<table width='".$largura_tabela."' height='".$altura_etiqueta."' border='0' cellpadding='0' cellspacing='0'><tr>";
                } else {
                    $retorno .= "<td style='width:".number_format(($etiqueta['espaco_colunas'] / $this->pxCm), 2, '.', '')."px;'>&nbsp;</td>";
                }

                $documento_temp = str_replace('color','',$documento_temp);
                $retorno .= "<td width='".$largura_etiqueta."' height='".$altura_etiqueta."' style='color:#000000; ".$estiloTd."'>
                                <div style='width: ".$largura_etiqueta."; height: ".$altura_etiqueta.";'>&nbsp;</div>
                            </td>";

                if(++$coluna_controle == $numero_colunas) {
                    $coluna_controle = 0;
                    $retorno .= "</tr></table><div style='height:".number_format(($etiqueta['espaco_linhas'] / $this->pxCm), 2, '.', '')."px;'></div>";//<br />
                }

                //Se tiver gerado a quantidade de linhas por página, irá inserir a quebra de página
                if(++$linha_controle == $numero_linhas) {
                    $linha_controle = 0;
                    $retorno .= "[[QUEBRA_DE_PAGINA]]";
                }
            }*/

            for ($i = 0; $i < $qtde_opcoes; $i++) {

                unset($documento_temp);
                $documento_temp = $documento;

                if(!$coluna_controle) {
                    $retorno .= "<table width='".$largura_tabela."' height='".$altura_etiqueta."' border='0' cellpadding='0' cellspacing='0'><tr>";
                } else {
                    $retorno .= "<td style='width:".number_format(($etiqueta['espaco_colunas'] / $this->pxCm), 2, '.', '')."px;'>&nbsp;</td>";
                }

                $retorno .= "<td width='".$largura_etiqueta."' height='".$altura_etiqueta."' style='".$estiloTd."'>
                                <div style='width: ".$largura_etiqueta."; height: ".$altura_etiqueta.";'>".$documento_temp."</div>
                            </td>";

                if(++$coluna_controle == $numero_colunas) {
                    $coluna_controle = 0;
                    $retorno .= "</tr></table><div style='height:".number_format(($etiqueta['espaco_linhas'] / $this->pxCm), 2, '.', '')."px;'></div>";//<br />
                }

                if(++$linha_controle == $numero_linhas) {
                    $linha_controle = 0;
                    $retorno .= "[[QUEBRA_DE_PAGINA]]";
                }

            }

            $sobra = $qtde_opcoes % $numero_colunas;
            if($sobra) {
				$totalSobra = $numero_colunas - $sobra;
                for ($x = 0; $x < $totalSobra; $x++) {
					$retorno .= "<td style='width:".number_format(($etiqueta['espaco_colunas'] / $this->pxCm), 2, '.', '')."px;'>&nbsp;</td>";
                    $retorno .= "<td width='".$largura_etiqueta."' height='".$altura_etiqueta."' style='color:#000000; ".$estiloTd."'>
									<div style='width: ".$largura_etiqueta."; height: ".$altura_etiqueta.";'>&nbsp;</div>
								</td>";
				}
                $retorno .= "</tr></table><div style='height:".number_format(($etiqueta['espaco_linhas'] / $this->pxCm), 2, '.', '')."px;'></div>";//<br />
            }

            $retorno .= "</body></html>";

            if($documento) {
                $this->retorno["sucesso"] = true;
                $this->retorno["etiquetas"] = $retorno;
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = "etiqueta_vazio";
        }

        return $this->retorno;

    }

    public function BuscarPessoas()
    {
        $this->sql = "select
                        p.idpessoa as 'key',
                        CONCAT(p.idpessoa, ' - ', p.nome) as value
                      from
                        pessoas p
                      where
                        (p.nome LIKE '%".$this->get["tag"]."%' OR p.idpessoa LIKE '%".$this->get["tag"]."%') and
                        p.ativo = 'S' ";
        $this->limite = -1;
        $this->ordem_campo = "p.nome";
        $this->groupby = "p.idpessoa";
        $this->retorno = $this->retornarLinhas();

        return json_encode($this->retorno);
    }

    public function BuscarMatriculas()
    {
        $this->sql = "select
                        m.idmatricula as 'key',
                        CONCAT(m.idmatricula, ' - ', p.nome) as value
                      from
                        pessoas p
                        inner join matriculas m on p.idpessoa = m.idpessoa
                      where
                        (p.nome LIKE '%".$this->get["tag"]."%' OR m.idmatricula LIKE '%".$this->get["tag"]."%') and
                        m.ativo = 'S' ";
        $this->limite = -1;
        $this->ordem_campo = "p.nome";
        $this->groupby = "p.idpessoa";
        $this->retorno = $this->retornarLinhas();

        return json_encode($this->retorno);
    }

    public function RetornarCursos($idoferta, $json = true)
    {
        $this->sql = "SELECT c.idcurso, c.nome FROM ofertas_cursos oc INNER JOIN cursos c ON oc.idcurso = c.idcurso where oc.ativo = 'S' ";
		if ($idoferta)
			$this->sql .= ' and oc.idoferta = "'.$idoferta.'" ';
		$this->sql .= ' group by c.idcurso order by c.nome ';
        /*$this->ordem_campo = "c.nome";
        $this->groupby = "c.idcurso";
        $this->limite = -1;
        $this->ordem = "ASC";*/
		$query = $this->executaSql($this->sql);
		$this->retorno = array();
		while($row = mysql_fetch_assoc($query)){
			$dados[] = $row;
		}
        //$dados = $this->retornarLinhas();

        if ($json) {
            $dadosJson = array();
            $dadosJson["curso"] = $dados;
            return json_encode($dadosJson);
        }
        else
            return $dados;
    }

}
