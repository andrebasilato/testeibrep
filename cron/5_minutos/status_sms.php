<?php
include($caminhoApp . '/app/classes/sms.class.php');
$objSms = new Sms();

$objSms->sql = 'SELECT
        			idlog_sms,
        			idapi
                FROM
        			log_sms
                WHERE
        			ativo = "S" AND
        			enviado = "N" AND
                    cron = "N"
                ORDER BY
        	  	    data_cad ASC,
                    idlog_sms ASC
        	    LIMIT 80';
$query = $objSms->executaSql($objSms->sql);

$objSms->Set('url_webservicesms',$config['linkapiSMS']);
$dado_seguro = array(
                    'loginSMS' => $config['loginSMS'],
                    'tokenSMS' => $config['tokenSMS']
                );

while ($smsPendente = mysql_fetch_assoc($query)) {
	$dado_seguro['idapi'] = (int)$smsPendente['idapi'];
	$objSms->Set('dado_seguro',$dado_seguro);
	$objSms->atualizaStatusSMS();
}

//Se não retornar nenhum SMS para verificar seta todos como o cron não rodou, para poder verificar novamente
if (mysql_num_rows($query) == 0) {
    $objSms->sql = 'UPDATE
                        log_sms
                    SET
                        cron = "N"
                    WHERE
                        ativo = "S" AND
                        enviado = "N"';
    $objSms->executaSql($objSms->sql);
}
