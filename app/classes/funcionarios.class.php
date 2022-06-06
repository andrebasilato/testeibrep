<?php

class Funcionarios extends Core 
{
    function ListarTodas() {
        $this->sql = "select 
                        " . $this->campos . "
                       FROM 
                    funcionarios fun
                    WHERE ativo = 'S'";

        $this->aplicarFiltrosBasicos();
        $this->groupby = "fun.idfuncionario";
        return $this->retornarLinhas();
    }

    function Retornar() {
        $this->sql = "select " . $this->campos ."
            FROM
                funcionarios fun
                INNER JOIN sindicatos i ON (i.idsindicato = fun.idsindicato)
                left outer join cidades c ON (c.idcidade = fun.idcidade)
                left outer join estados e ON (e.idestado = c.idestado)
                WHERE fun.ativo = 'S' AND 
                fun.idfuncionario = '" . $this->id . "'";

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

    function adicionarArquivo() {
        $this->return = array();
        if ($_FILES['documento']['error'] === 0) {
            $pasta = $_SERVER['DOCUMENT_ROOT'] . '/storage/funcionarios_arquivos/' . $this->id;
            $extensao = strtolower(strrchr($_FILES['documento']['name'], '.'));
            $nomeServidor = date('YmdHis') . '_' . uniqid() . $extensao;
            mkdir($pasta, 0777);
            chmod($pasta, 0777);
            $envio = move_uploaded_file($_FILES['documento']['tmp_name'], $pasta . '/' . $nomeServidor);
            chmod($pasta . '/' . $nomeServidor, 0777);
            $db = new Zend_Db_Select(new Zend_Db_MySql);
            if ($envio) {
                $insert = $db->insert('funcionarios_arquivos', array(
                    'data_cad' => 'NOW()',
                    'idfuncionario' => $this->id,
                    'arquivo_nome' => $db->quote($_FILES["documento"]["name"]),
                    'arquivo_servidor' => $db->quote($nomeServidor),
                    'arquivo_tipo' => $db->quote($_FILES["documento"]["type"]),
                    'arquivo_tamanho' => $db->quote($_FILES["documento"]["size"])
                ));
                $salvar = $this->executaSql((string)$insert);
                if ($salvar) {
                    $this->monitora_oque = 1;
                    $this->monitora_onde = 276;
                    $this->monitora_qual = mysql_insert_id();
                    $this->Monitora();
                
                    $this->return["sucesso"] = true;
                    $this->return["mensagem"] = "arquivos_funcionario_envio_sucesso";
                } else {
                    $this->return["sucesso"] = false;
                    $this->return["mensagem"] = "arquivos_funcionario_envio_erro";
                }
            } else {
                $this->return["sucesso"] = false;
                $this->return["mensagem"] = "arquivos_funcionario_envio_erro";
            }
        } else {
            $this->sql = "insert into
            funcionarios_arquivos
            set
            data_cad = now(),
            idfuncionario = " . $this->id . ",
            idtipo = " . $this->post["idtipo"] . ",
            idtipo_associacao = " . $this->post["idtipo_associacao"];
            $salvar = $this->executaSql($this->sql);
            if ($salvar) {
                $this->monitora_oque = 1;
                $this->monitora_onde = 276;
                $this->monitora_qual = mysql_insert_id();
                $this->Monitora();
            
                $this->return["sucesso"] = true;
                $this->return["mensagem"] = "arquivos_funcionario_envio_sucesso";
            } else {
                $this->return["sucesso"] = false;
                $this->return["mensagem"] = "arquivos_funcionario_envio_erro";
            }
        }
        return $this->return;
    }

    function removerArquivo($modulo, $pasta, $dados, $idioma) {
        echo $this->ExcluirArquivo($modulo, $pasta, $dados, $idioma);
    }

    function retornarListaArquivos() {
         $this->sql = "SELECT *,idarquivo as iddocumento FROM funcionarios_arquivos fa
                    WHERE idfuncionario = {$this->id}
                                AND ativo = 'S'";
        $this->ordem = 'ASC';
        $this->ordem_campo = "data_cad";
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    function retornarArquivo() {
        $this->sql = "SELECT *, idarquivo as iddocumento FROM funcionarios_arquivos WHERE idarquivo = " . $this->iddocumento . " and ativo = 'S' and idfuncionario = " . $this->id;
        return $this->retornarLinha($this->sql);
    }
    
    function gerarFicha($funcionario){    
       // leitura das datas automaticamente
        $dia = date('d');
        $mes = date('m');
        $ano = date('Y');
        $semana = date('w');
        $cidade = $funcionario['sindicato']['cidade'];
        $numero = ($funcionario['numero']) ? ', '.$funcionario['numero'] : '';
        $complemento = ($funcionario['complemento']) ? ', '.$funcionario['complemento'] : '';
        $m = $GLOBALS['meses_idioma']['pt_br'][$mes];

        $html = '<style>
        td {
          font-size:10px;
        }
        .sem_quebra {
          white-space: nowrap;
        }
        span.polegar {
          font-size: 12px;
        }
        .espaco_celula {
          width: 150px;
        }
        </style>
        <table width="100%" border="1" cellpadding="3" cellspacing="0"> 
                           <tbody>
                            <tr>
                              <td colspan="9" align="center"><strong>Ficha de Registro de Empregado</strong></td>
                            </tr>
                             <tr>       
                              <td colspan="9">Dados do Empregador</td>
                            </tr>
                            <tr>
                              <td colspan="9">
                                   <table>
                                            <tr>
                                               <td>Empresa:</td>
                                                <td>'.$funcionario['sindicato']['nome'].'</td>
                                               <td>Nº</td>
                                                <td>'.$funcionario['sindicato']['idsindicato'].'</td>
                                       <tr>
                                               <td>CNPJ/CEI:</td>
                                                <td>'.  formatar($funcionario['sindicato']['documento'],'cnpj') .'</td>
                                            </tr>
                                           <tr>
                                               <td>Ativ. Federal:</td>
                                                <td>'.  $funcionario['sindicato']['ativ_federal'] .'</td>
                                            </tr>
                                           <tr>
                                               <td>Endereço:</td>
                                                <td>'.  $funcionario['sindicato']['endereco'] .'</td>
                                            </tr>
                                           <tr>
                                               <td>Bairro:</td>
                                                <td>'.  $funcionario['sindicato']['bairro'] .'</td>
                                               <td>CEP:</td>
                                                <td class="espaco_celula">'.  formatar($funcionario['sindicato']['cep'],'cep') .'</td>
                                                <td>Município:</td>
                                                <td>'.  $funcionario['sindicato']['cidade'] .'</td>                                              
                                            </tr>
                                   </table>   
                               </td>       
                            </tr>
                             <tr>       
                              <td colspan="9">Dados do Empregado</td>
                            </tr>
                            <tr>
                            <td colspan="9">
                               <table width="100%"  border="0" cellpadding="3" cellspacing="1">
                                   <tr>
                                       <td>
                                               <table width="100%"  border="0" cellpadding="3" cellspacing="1">
                                                <tr>
                                                   <td >Nome:</td>
                                                    <td colspan="7" >'.$funcionario['nome'].'</td>
                                                   <td>Código:</td>
                                                    <td>'.$funcionario['idfuncionario'].'</td>
                                                </tr>
                                               <tr>
                                                   <td>Pai:</td>
                                                    <td colspan="3">'.$funcionario['filiacao_pai'].'</td>
                                                </tr>
                                               <tr>
                                                   <td>Mãe:</td>
                                                    <td colspan="3">'.$funcionario['filiacao_mae'].'</td>
                                                </tr>
                                               <tr>
                                               <tr>
                                                   <td>Nascimento:</td>
                                                    <td>'. formataData( $funcionario['data_nasc'], "pt_br",4 ).'</td>
                                                   <td>Sexo:</td>
                                                    <td>'. $GLOBALS['sexo']['pt_br'][$funcionario['sexo']].'</td>
                                                   <td>Est.Civil:</td>
                                                   <td>'.  $GLOBALS['estadocivil']['pt_br'][$funcionario['estado_civil']].'</td>
                                                   <td>Raça/Cor:</td>
                                                   <td>'.$funcionario['raca_cor'].'</td>
                                                </tr>
                                               <tr>
                                                   <td>Naturalidade:</td>
                                                     <td colspan="2">'.$funcionario['naturalidade'].'</td>
                                                    <td>Nacionalidade:</td>
                                                     <td>'.$funcionario['nacionalidade'].'</td>
                                                </tr>
                                                <tr>
                                                   <td>Endereço:</td>
                                                    <td colspan="8">'.$funcionario['endereco'].$numero.$complemento.'</td>                                  
                                                </tr> 
                                               <tr>
                                                   <td>Bairro:</td>
                                                   <td>'.$funcionario['bairro'].'</td>
                                                   <td>CEP:</td>
                                                    <td colspan="2">'.formatar($funcionario['cep'],'cep').'</td>
                                                    <td>Município:</td>
                                                    <td class="sem_quebra">'.$funcionario['cidade'].' - '.$funcionario['est_sigla'].'</td>
                                                </tr>
                                               <tr>
                                                   <td>CPF:</td>
                                                     <td>'.formatar($funcionario['cpf'],'cpf').'</td>
                                                </tr>
                                               <tr>   
                                                   <td>RG:</td>
                                                   <td>'.$funcionario['rg'].'</td>                                         
                                                   <td>Órgão:</td>
                                                   <td>'.$funcionario['rg_orgao_emissor'].'</td>
                                                   <td>Estado:</td>
                                                   <td >'.$funcionario['est_rg'].'</td>
                                                   <td>Emissão RG:</td>                                             
                                                    <td>'. formataData( $funcionario['rg_data_emissao'], "pt_br",4 ).'</td>
                                                </tr>
                                                 <tr>   
                                                   <td>Núm. CTPS:</td>
                                                    <td>'.$funcionario['numero_ctps'].'</td>                     
                                                    <td>Série CTPS:</td>
                                                    <td>'.$funcionario['serie_ctps'].'</td> 
                                                     <td>Estado CTPS:</td>
                                                    <td>'.$funcionario['estado_ctps'].'</td>
                                                    <td>Expedição CTPS:</td>
                                                     <td>'. formataData( $funcionario['expedicao_ctps'], "pt_br",4 ).'</td>    
                                                   </tr>                                                
                                               <tr>   
                                                   <td>PIS:</td>
                                                   <td>'.$funcionario['pis'].'</td>                               
                                                    <td>Cadastro PIS:</td>      
                                                       <td>'. $funcionario['cadastro_pis'].'</td>
                                                </tr>
                                                <tr>   
                                                   <td>Instrução:</td>
                                                   <td colspan="8">'. $GLOBALS['escolaridade']['pt_br'][$funcionario['escolaridade']].'</td>     
                                                </tr>
                                                <tr>   
                                                   <td>CNH:</td>
                                                  <td>'.$funcionario['cnh'].'</td>
                                                    <td>Categoria CNH:</td>
                                                    <td>'.$funcionario['categoria_cnh'].'</td>
                                                    <td>Validade CNH:</td>
                                                    <td>'. formataData( $funcionario['validade_cnh'], "pt_br",4 ).'</td> 
                                                </tr>
                                                 <tr>   
                                                   <td>Reservista:</td>
                                                  <td>'.$funcionario['reservista'].'</td>
                                                    <td>Categoria:</td>
                                                   <td>'.$funcionario['categoria_reservista'].'</td>                                               
                                                </tr>
                                               <tr>
                                                   <td>Tit. Eleitoral:</td>
                                                   <td>'.$funcionario['titulo_eleitoral'].'</td>
                                                   <td>Zona:</td>
                                                    <td>'.$funcionario['zona'].'</td>
                                                    <td>Seção:</td>
                                                    <td>'.$funcionario['secao'].'</td>
                                               </tr>        
                                               <tr>   
                                                   <td>Banco:</td>
                                                    <td colspan="3">'.$funcionario['banco_nome'].'</td>
                                                     <td>Agencia:</td>
                                                    <td>'.$funcionario['banco_agencia'].'</td>
                                                    <td>Conta:</td>
                                                   <td>'.$funcionario['banco_conta'].'</td>
                                                    <td>Dígito:</td>
                                                    <td></td>
                                                </tr>                                          
                                               <tr>   
                                                   <td>Sindicato:</td>
                                                    <td colspan="2">'.$funcionario['sindicato'].'</td>
                                                </tr>
                                               <tr>   
                                                   <td>Cons. Profis:</td>
                                                   <td>'.$funcionario['cons_profis'].'</td>         
                                                    <td>Registro Profis:</td>
                                                   <td>'.$funcionario['registro_profis'].'</td>
                                                    <td>Data Registro:</td>
                                                     <td>'. formataData( $funcionario['data_registro'], "pt_br",4 ).'</td> 
                                                </tr>
                                           </table>
                                       </td>
                                         <td valign="top">
                                                 <img src="/api/get/imagens/funcionarios_avatar/113/150/'.$funcionario['avatar_servidor'].'">
                                          </td>
                                     <tr>
                                   </table>                      
                               </td>
                            </tr>
                           <tr>       
                              <td colspan="9">Cadastro de Estrangeiro</td>
                            </tr>
                            <tr>
                              <td colspan="9">
                                   <table width="100%"  border="0" cellpadding="3" cellspacing="1">
                                            <tr>
                                               <td collspan="2">Data Chegada:  '. formataData( $funcionario['data_chegada'], "pt_br",4 ).'</td>
                                            </tr>
                                           <tr>
                                               <td width="50%">Tipo Visto: '.  $funcionario['tipo_visto'].'</td>
                                                <td width="50%">Número da Portaria: '. $funcionario['numero_portaria'].'</td>
                                            </tr>
                                           <tr>
                                               <td width="50%">Carteira  RNE: '. $funcionario['rne'].'</td>
                                                <td width="50%">Data da Portaria: '. formataData( $funcionario['data_portaria'], "pt_br",4 ).'</td>
                                            </tr>
                                           <tr>
                                               <td>Validade RNE: '. formataData( $funcionario['validade_rne'], "pt_br",4 ).'</td>                                                
                                            </tr>
                                   </table>  
                                </td>
                            </tr>
                            <tr>       
                              <td colspan="9">Cadastro de Trabalho</td>
                            </tr>
                            <tr>
                              <td colspan="10">
                                  <table width="100%"  border="0" cellpadding="3" cellspacing="1">
                                            <tr>
                                               <td collspan="2">Admissão: '. formataData( $funcionario['data_admissao'], "pt_br",4 ).'</td>
                                            </tr>
                                           <tr>
                                               <td>Optante FGTS: </td>
                                                 <td>'.$GLOBALS['sim_nao']['pt_br'][$funcionario['optante_fgts']].'</td>  
                                                <td class="espaco_celula sem_quebra">Data Opção: '. formataData( $funcionario['data_opcao'], "pt_br",4 ).'</td>
                                                  <td class="sem_quebra" colspan="6">Conta FGTS: </td>
                                                 <td class="sem_quebra" colspan="2">'.$funcionario['conta_fgts'].'</td>
                                            </tr>                                                 
                                             <tr>
                                               <td>Cargo: </td>
                                               <td>'.$funcionario['cargo'].'</td>
                                                <td>CBO: '.$funcionario['cbo'].'</td>
                                             </tr>
                                             <tr>
                                               <td>Remuneração: '.$funcionario['remuneracao'].'</td>
                                                <td>Modo Pgto: '.$funcionario['modo_pagt'].'</td>
                                                 <td>Período: '.$funcionario['periodo'].'</td>
                                             </tr>                                                                                                                             
                                       </table>
                               </td>
                            </tr>  
                           <tr>
                                  <td colspan="9">                   
                                  <table width="100%" border="0" cellpadding="8" cellspacing="1">
                                       <tr>
                                           <td colspan="1">'. "$cidade,$dia de $m  de $ano" .'</td>
                                           <td align="center" colspan="5"> 
                                                <br/>______________________________________<br/><br/>Assinatura do Empregador
                                           </td>
                                       </tr>
                                       <tr>
                                             <td colspan="9" align="center">&nbsp;&nbsp;&nbsp;
                                                   Data da dispensa ___________________________________,______,de __________ de __________.
                                              </td> 
                                               <td></td>
                                       </tr>
                                       <tr>
                                             <td colspan="9" align="center">&nbsp;&nbsp;&nbsp;
                                                   <br/>___________________________________________________<br/><br/>
                                                        Assinatura do Empregado
                                              </td> 
                                               <td align="rigth">    
                                                   <table width="100%" height="200px"  border="1" cellpadding="8" cellspacing="1">
                                                        <tr><td width="120px" height="120px" align="rigth">&nbsp;&nbsp;&nbsp;</td></tr>                                                       
                                                    </table>
                                                     &nbsp;&nbsp;<span class="polegar">Polegar Direito</span>
                                                </td>                                                    
                                       </tr>              
                                  </table>
                                 </td>                                
                            </tr>             
                         </tbody>
                       </table>';
        return  $html;
    }
}

?>

