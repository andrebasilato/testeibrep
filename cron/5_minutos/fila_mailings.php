<?php
  $coreObj = new Core();

  $coreObj->sql = "select m.idemail,
                  m.salvar_log,
                  m.nome,
                  m.descricao,
                  m.corpo_email,
                  m.corpo_sms,
                  mf.hash,
                  mf.idemail_pessoa,
                  mf.email,
                  mf.celular,
                  mf.paraemail,
                  mf.parasms,
                  mf.nome as nome_pessoa,
                  mf.*,
                  sindicatos.documento as documento_sindicato,
                  vendedores.documento as documento_vendedor,
                  pessoas.documento as documento_pessoa,
                  escolas.documento as documento_escola
                from mailings m
                  inner join mailings_fila mf on m.idemail = mf.idemail
                  left join sindicatos on sindicatos.idsindicato = mf.idsindicato
                  left join escolas on escolas.idescola = mf.idescola
                  left join vendedores on vendedores.idvendedor = mf.idvendedor
                  left join pessoas on pessoas.idpessoa = mf.idpessoa
                where m.situacao in (0, 1) and m.ativo = 'S' and mf.ativo = 'S' and
                  mf.enviado = 'N'
				group by
				  mf.email 
                order by mf.data_cad,
                  mf.idemail_pessoa
                limit 500";
	//echo $coreObj->sql;exit;
  $coreObj->limite = -1;
  $coreObj->ordem_campo = false;
  $coreObj->ordem = false;
  $fila = $coreObj->retornarLinhas();
	  foreach($fila as $linha) {

		  if($linha["paraemail"] == 'S'){

				$enviarEmail = true;
				if($config['limite_emails_mailing']) {
					$coreObj->sql = 'SELECT COUNT(*) AS total FROM mailings_fila WHERE DATE_FORMAT(data_envio,"%Y%m") = DATE_FORMAT(NOW(),"%Y%m") AND enviado = "S" AND paraemail = "S"';
					$totalEmails = $coreObj->retornarLinha($coreObj->sql);
					if($totalEmails['total'] >= $config['limite_emails_mailing'])
						$enviarEmail = false;

				}
				if($enviarEmail) {
					if(! empty($linha['idusuario_gestor'])){
						$tabela = 'usuarios_adm';
						$primaria = 'idusuario';
						$referencia = $linha['idusuario_gestor'];
					}
		
					if(! empty($linha['idpessoa'])){
						$tabela = 'pessoas';
						$primaria = 'idpessoa';
						$referencia = $linha['idpessoa'];
					}
		
					if(! empty($linha['idescola'])){
						$tabela = 'escolas';
						$primaria = 'idescola';
						$referencia = $linha['idescola'];
					}
		
					if(! empty($linha['idvendedor'])){
						$tabela = 'vendedores';
						$primaria = 'idvendedor';
						$referencia = $linha['idvendedor'];
					}
		
					if(
						! empty($linha['idusuario_gestor']) ||
						! empty($linha['idpessoa']) ||
						! empty($linha['idescola']) ||
						! empty($linha['idvendedor']) 
					){
						$sql = 'SELECT receber_email FROM ' . $tabela . ' WHERE ' . $primaria . ' = ' . $referencia;
						$verifica = $coreObj->retornarLinha($sql);
		
						if(empty($verifica['receber_email']) || $verifica['receber_email'] == 'N'){
							$coreObj->sql = "update mailings_fila set enviado = 'S', data_envio = NOW() where idemail_pessoa = '" . $linha["idemail_pessoa"] . "'";
							$coreObj->executaSql($coreObj->sql);
							continue;
						}
					}

					$nomePara = html_entity_decode($linha["nome_pessoa"]);//echo $nomePara . 'asd';exit;
					$message = $linha["corpo_email"];

					//Substituindo as variáveis de imagens pelas imagens---------
					$variavel = explode("[[I][",$message);
					if($variavel){
					  foreach($variavel as $ind => $val){
						  $id = explode("]]",$val);
						  $indice[] = $id[0];
					  }
					  unset($indice[array_search("", $indice)]);
					  foreach($indice as $ind => $val){
						  $coreObj->sql = "SELECT idemail_imagem, servidor FROM mailings_imagens WHERE ativo = 'S' AND idemail = ".$linha["idemail"]." AND idemail_imagem = ".intval($val)."";
						  $imagem = $coreObj->retornarLinha($coreObj->sql);
						  $message = str_ireplace("[[I][".$val."]]", "<br/><div style=\"text-align:left; text-align:center\"><img src=\"http://" . $config['urlSistemaFixa'] . "/storage/mailings_imagens/".$imagem["servidor"]."\" border=\"0\" /></div><br/>", $message);
					  }
					 }
					 //(END)Substituindo as variáveis de imagens pelas imagens---------

					//Substituir Variáveis-------
					$message = str_ireplace("[[DESTINATARIO]]","<strong>".$nomePara."</strong>",$message);
					$message = str_ireplace("[[DESCRICAO]]",$linha["descricao"],$message);
					$message = str_ireplace("[[TITULO]]",$linha["nome"],$message);

	                $message = utf8_decode($message);

					//echo $message;exit;
					$emailPara = $linha["email"];
					$assunto = $linha["nome"];

					$nomeDe = utf8_decode($config["tituloEmpresa"]);
					$emailDe = $config["emailMailing"];

                    $coreObj->naoSalvarLogEmail = ($linha['salvar_log'] == 'S') ? false : true;

					if(verificaEmail($emailPara)){
						$emailEnviado = $coreObj->EnviarEmail($nomeDe,$emailDe,$assunto,$message,utf8_decode($nomePara),$emailPara,"layout_branco");
						if($emailEnviado) {
						  $coreObj->sql = "update mailings_fila set enviado = 'S', data_envio = NOW() where idemail_pessoa = '".$linha["idemail_pessoa"]."'";
						  $coreObj->executaSql($coreObj->sql);
						}
					}else{
						$coreObj->sql = "update mailings_fila set enviado = 'S', data_envio = NOW() where idemail_pessoa = '".$linha["idemail_pessoa"]."'";
						$coreObj->executaSql($coreObj->sql);
					}
				}
	    }

		 if($linha["parasms"] == 'S' and $linha["corpo_sms"] and $GLOBALS['config']['integrado_com_sms']){
			 $nomePara = ($linha["nome_pessoa"]);
			 $linha["corpo_sms"] = str_ireplace("[[DESTINATARIO]]",$nomePara,$linha["corpo_sms"]);
			 $linha["corpo_sms"] = str_ireplace("[[DESTINATARIO_EMAIL]]",$linha["email"],$linha["corpo_sms"]);
			 if(isset($linha["documento_pessoa"]) && !empty($linha["documento_pessoa"]) && $linha["documento_pessoa"] != null) {
                 $linha["corpo_sms"] = str_ireplace(
                     "[[DESTINATARIO_DOCUMENTO]]",
                     $linha["documento_pessoa"],
                     $linha["corpo_sms"]);
             }
             if(isset($linha["documento_sindicato"]) && !empty($linha["documento_sindicato"]) && $linha["documento_sindicato"] != null) {
                 $linha["corpo_sms"] = str_ireplace(
                     "[[DESTINATARIO_DOCUMENTO]]",
                     $linha["documento_sindicato"],
                     $linha["corpo_sms"]);
             }
             if(isset($linha["documento_vendedor"]) && !empty($linha["documento_vendedor"]) && $linha["documento_vendedor"] != null) {
                 $linha["corpo_sms"] = str_ireplace(
                     "[[DESTINATARIO_DOCUMENTO]]",
                     $linha["documento_vendedor"],
                     $linha["corpo_sms"]);
             }
             if(isset($linha["documento_escola"]) && !empty($linha["documento_escola"]) && $linha["documento_escola"] != null) {
                 $linha["corpo_sms"] = str_ireplace(
                     "[[DESTINATARIO_DOCUMENTO]]",
                     $linha["documento_escola"],
                     $linha["corpo_sms"]);
             }
            include("../../classes/sms.class.php");
            $smsobj = new Sms();

            $smsobj->Set('url_webservicesms',$GLOBALS['config']['linkapiSMS']);
            $dados_gateway = array(
                                  'loginSMS' => $GLOBALS['config']['loginSMS'],
                                  'tokenSMS' => $GLOBALS['config']['tokenSMS'],
                                  'celular' => $linha["celular"],
                                  'nome' => $nomePara,
                                  'mensagem' => $linha["corpo_sms"]
                                  );
            $smsobj->Set('dado_seguro',$dados_gateway);
            $smsobj->Set('idchave',$linha["idemail_pessoa"]);
            $smsobj->Set('origem','M');

            if($smsobj->ExecutaIntegraSMS()){
                $coreObj->sql = "update mailings_fila set enviado = 'S', data_envio = NOW() where idemail_pessoa = '".$linha["idemail_pessoa"]."'";
                $coreObj->executaSql($coreObj->sql);
            }
		 }


	  }

  $coreObj->sql = "select
					idemail
				  from
					mailings
				  where
					ativo = 'S' and situacao <> '3'";
  $coreObj->limite = -1;
  $coreObj->ordem_campo = false;
  $coreObj->ordem = false;
  $mailings = $coreObj->retornarLinhas();
  foreach($mailings as $mailing) {
	  $coreObj->sql = "select count(*) as total from mailings_fila where idemail = ".$mailing["idemail"]." and enviado = 'N' and ativo = 'S'";
	  $verifica = $coreObj->retornarLinha($coreObj->sql);
	  if ($verifica["total"] == 0) {
		$coreObj->sql = "update mailings set situacao = '2' where idemail = ".$mailing["idemail"];
		$coreObj->executaSql($coreObj->sql);
	  } else {
		$coreObj->sql = "update mailings set situacao = '1' where idemail = ".$mailing["idemail"];
		$coreObj->executaSql($coreObj->sql);
	  }
  }

  	/**
  	 * Funcão copiada do PHPMailer para verificar se um email é válido
  	 *
  	 */
	function verificaEmail($address, $patternselect = 'auto'){
	   if (!$patternselect or $patternselect == 'auto') {
	            if (defined('PCRE_VERSION')) { //Check this constant so it works when extension_loaded() is disabled
	                if (version_compare(PCRE_VERSION, '8.0') >= 0) {
	                    $patternselect = 'pcre8';
	                } else {
	                    $patternselect = 'pcre';
	                }
	            } else {
	                //Filter_var appeared in PHP 5.2.0 and does not require the PCRE extension
	                if (version_compare(PHP_VERSION, '5.2.0') >= 0) {
	                    $patternselect = 'php';
	                } else {
	                    $patternselect = 'noregex';
	                }
	            }
	        }
	        switch ($patternselect) {
	            case 'pcre8':
	                /**
	                 * Uses the same RFC5322 regex on which FILTER_VALIDATE_EMAIL is based, but allows dotless domains.
	                 * @link http://squiloople.com/2009/12/20/email-address-validation/
	                 * @copyright 2009-2010 Michael Rushton
	                 * Feel free to use and redistribute this code. But please keep this copyright notice.
	                 */
	                return (boolean)preg_match(
	                    '/^(?!(?>(?1)"?(?>\\\[ -~]|[^"])"?(?1)){255,})(?!(?>(?1)"?(?>\\\[ -~]|[^"])"?(?1)){65,}@)' .
	                    '((?>(?>(?>((?>(?>(?>\x0D\x0A)?[\t ])+|(?>[\t ]*\x0D\x0A)?[\t ]+)?)(\((?>(?2)' .
	                    '(?>[\x01-\x08\x0B\x0C\x0E-\'*-\[\]-\x7F]|\\\[\x00-\x7F]|(?3)))*(?2)\)))+(?2))|(?2))?)' .
	                    '([!#-\'*+\/-9=?^-~-]+|"(?>(?2)(?>[\x01-\x08\x0B\x0C\x0E-!#-\[\]-\x7F]|\\\[\x00-\x7F]))*' .
	                    '(?2)")(?>(?1)\.(?1)(?4))*(?1)@(?!(?1)[a-z0-9-]{64,})(?1)(?>([a-z0-9](?>[a-z0-9-]*[a-z0-9])?)' .
	                    '(?>(?1)\.(?!(?1)[a-z0-9-]{64,})(?1)(?5)){0,126}|\[(?:(?>IPv6:(?>([a-f0-9]{1,4})(?>:(?6)){7}' .
	                    '|(?!(?:.*[a-f0-9][:\]]){8,})((?6)(?>:(?6)){0,6})?::(?7)?))|(?>(?>IPv6:(?>(?6)(?>:(?6)){5}:' .
	                    '|(?!(?:.*[a-f0-9]:){6,})(?8)?::(?>((?6)(?>:(?6)){0,4}):)?))?(25[0-5]|2[0-4][0-9]|1[0-9]{2}' .
	                    '|[1-9]?[0-9])(?>\.(?9)){3}))\])(?1)$/isD',
	                    $address
	                );
	            case 'pcre':
	                //An older regex that doesn't need a recent PCRE
	                return (boolean)preg_match(
	                    '/^(?!(?>"?(?>\\\[ -~]|[^"])"?){255,})(?!(?>"?(?>\\\[ -~]|[^"])"?){65,}@)(?>' .
	                    '[!#-\'*+\/-9=?^-~-]+|"(?>(?>[\x01-\x08\x0B\x0C\x0E-!#-\[\]-\x7F]|\\\[\x00-\xFF]))*")' .
	                    '(?>\.(?>[!#-\'*+\/-9=?^-~-]+|"(?>(?>[\x01-\x08\x0B\x0C\x0E-!#-\[\]-\x7F]|\\\[\x00-\xFF]))*"))*' .
	                    '@(?>(?![a-z0-9-]{64,})(?>[a-z0-9](?>[a-z0-9-]*[a-z0-9])?)(?>\.(?![a-z0-9-]{64,})' .
	                    '(?>[a-z0-9](?>[a-z0-9-]*[a-z0-9])?)){0,126}|\[(?:(?>IPv6:(?>(?>[a-f0-9]{1,4})(?>:' .
	                    '[a-f0-9]{1,4}){7}|(?!(?:.*[a-f0-9][:\]]){8,})(?>[a-f0-9]{1,4}(?>:[a-f0-9]{1,4}){0,6})?' .
	                    '::(?>[a-f0-9]{1,4}(?>:[a-f0-9]{1,4}){0,6})?))|(?>(?>IPv6:(?>[a-f0-9]{1,4}(?>:' .
	                    '[a-f0-9]{1,4}){5}:|(?!(?:.*[a-f0-9]:){6,})(?>[a-f0-9]{1,4}(?>:[a-f0-9]{1,4}){0,4})?' .
	                    '::(?>(?:[a-f0-9]{1,4}(?>:[a-f0-9]{1,4}){0,4}):)?))?(?>25[0-5]|2[0-4][0-9]|1[0-9]{2}' .
	                    '|[1-9]?[0-9])(?>\.(?>25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])){3}))\])$/isD',
	                    $address
	                );
	            case 'html5':
	                /**
	                 * This is the pattern used in the HTML5 spec for validation of 'email' type form input elements.
	                 * @link http://www.whatwg.org/specs/web-apps/current-work/#e-mail-state-(type=email)
	                 */
	                return (boolean)preg_match(
	                    '/^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}' .
	                    '[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/sD',
	                    $address
	                );
	            case 'noregex':
	                //No PCRE! Do something _very_ approximate!
	                //Check the address is 3 chars or longer and contains an @ that's not the first or last char
	                return (strlen($address) >= 3
	                    and strpos($address, '@') >= 1
	                    and strpos($address, '@') != strlen($address) - 1);
	            case 'php':
	            default:
	                return (boolean)filter_var($address, FILTER_VALIDATE_EMAIL);
	        }
		}
?>