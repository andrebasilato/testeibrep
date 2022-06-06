<?php
// Autoload
// Não precisamos dar require em classes seguindo o seguinte padrão:
// A classe tem que estar no diretório 'classes', o nome do arquivo deve conter
// apenas letras minusculas e ser precedido de '.class.php'. O sufixo do arquivo
// deverá ser o nome da classe.
//
// - Exemplo: o arquivo File.class.php contem a classe File
//
function autoloadClasses($class) {
    $class_directory = realpath(dirname(__FILE__) .'/..') . DIRECTORY_SEPARATOR . 'classes';
    $class = strtolower($class);

    // classes atípicas
    $routes = array(
        'zend_db_select' => $class_directory . DIRECTORY_SEPARATOR . 'dataaccess' . DIRECTORY_SEPARATOR . 'select.php',
        'zend_db_mysql' => $class_directory . DIRECTORY_SEPARATOR . 'dataaccess' . DIRECTORY_SEPARATOR . 'mysql.php',
        'folhas_registros_diplomas' => $class_directory . DIRECTORY_SEPARATOR . 'folhasregistrosdiplomas.class.php',
        'formulas_notas' => $class_directory . DIRECTORY_SEPARATOR . 'formulasnotas.class.php',
    );

    if (array_key_exists($class, $routes) && file_exists($routes[$class]))
    {
        $routes[$class];
        require $routes[$class];
    }

    $file = $class_directory . DIRECTORY_SEPARATOR . $class . '.class.php';
    if (file_exists($file)) {
        require $file;
    }
}
// Registra o autoload
spl_autoload_register('autoloadClasses');

function verificaModulos($modulo = NULL, $funcionalidade = NULL, $incluilib = true){
    if($modulo && $funcionalidade){
        if(!$GLOBALS["config"]["modulos"]["url"][$modulo][$funcionalidade]){
            if($incluilib) {
                incluirLib("sempermissao_modulos",$GLOBALS["config"]);
                exit();
            } else {
                return false;
            }
        } else {
            return true;
        }
    } else {
        return true;
    }
}

function retornaSOBrowser() {
    include_once("../assets/plugins/browser/browser.php");
    $browser = new Browser();

    $retorno = array();
    $retorno["navegador"] = $browser->getBrowser();
    $retorno["navegador_versao"] = $browser->getVersion();

    $retorno["so"] = retornaSO();

    $retorno["ip"] = $_SERVER['REMOTE_ADDR'];

    $retorno["user_agent"] = $_SERVER['HTTP_USER_AGENT'];

    return $retorno;
}

function retornaSO() {
    $oses = array(
        'Win311' => 'Win16',
        'Win95' => '(Windows 95)|(Win95)|(Windows_95)',
        'WinME' => '(Windows 98)|(Win 9x 4.90)|(Windows ME)',
        'Win98' => '(Windows 98)|(Win98)',
        'Win2000' => '(Windows NT 5.0)|(Windows 2000)',
        'WinXP' => '(Windows NT 5.1)|(Windows XP)',
        'WinServer2003' => '(Windows NT 5.2)',
        'WinVista' => '(Windows NT 6.0)',
        'Windows 7' => '(Windows NT 6.1)',
        'Windows 8' => '(Windows NT 6.2)',
        'WinNT' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
        'OpenBSD' => 'OpenBSD',
        'SunOS' => 'SunOS',
        'Ubuntu' => 'Ubuntu',
        'Android' => 'Android',
        'Linux' => '(Linux)|(X11)',
        'iPhone' => 'iPhone',
        'iPad' => 'iPad',
        'MacOS' => '(Mac_PowerPC)|(Macintosh)',
        'QNX' => 'QNX',
        'BeOS' => 'BeOS',
        'OS2' => 'OS/2',
        'SearchBot' => '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp)|(MSNBot)|(Ask Jeeves/Teoma)|(ia_archiver)'
    );
    $uagent = $_SERVER['HTTP_USER_AGENT']."<br/>";
    $uagent = strtolower($uagent ? $uagent : $_SERVER['HTTP_USER_AGENT']);
    foreach ($oses as $os => $pattern)
        if (preg_match('/'.$pattern.'/i', $uagent))
            return $os;
    return 'Desconhecido';
}

function extenso($valor = 0, $maiusculas = false) {
    $singular = array("centavo", "real", "mil", "milhao", "bilhao", "trilhao", "quatrilhao");
    $plural = array("centavos", "reais", "mil", "milhoes", "bilhoes", "trilhoes","quatrilhoes");
    $c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
    $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
    $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezesete", "dezoito", "dezenove");
    $u = array("", "um", "dois", "tres", "quatro", "cinco", "seis","sete", "oito", "nove");

    $z = 0;
    $rt = "";

    $valor = number_format($valor, 2, ".", ".");
    $inteiro = explode(".", $valor);

    for($i=0; $i < count($inteiro); $i++)
        for($ii = strlen($inteiro[$i]); $ii < 3; $ii++)
            $inteiro[$i] = "0".$inteiro[$i];

    $fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
    for ($i=0;$i<count($inteiro);$i++) {
        $valor = $inteiro[$i];
        $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
        $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
        $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

        $r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd &&$ru) ? " e " : "").$ru;
        $t = count($inteiro)-1-$i;
        $r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
        if ($valor == "000")$z++; elseif ($z > 0) $z--;
        if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t];
        if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) &&($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
    }

    if(!$maiusculas){
        return($rt ? $rt : "zero");
    } else {
        if ($rt) $rt=preg_replace(" E "," e ",ucwords($rt));
        return (($rt) ? ($rt) : "Zero");
    }
}

function senhaSegura($senha,$chave){
    $chave = md5($chave);
    $senha_escape = hash("sha512", sha1(hash("sha512", addslashes($chave.$senha.$chave))));
    return $senha_escape;
}

function formataData($datetime, $formato, $horaEx) {
    if($datetime){
        if($formato == "en") {
            if($datetime == "") $datetime = "00/00/0000 00:00:00";

            $separa = explode(" ",$datetime);
            if(array_key_exists(0, $separa))
                $data = explode("/",$separa[0]);
            if(array_key_exists(1, $separa))
                $hora = explode(":",$separa[1]);

            if(array_key_exists(2, $data) and array_key_exists(1, $data) and array_key_exists(0, $data))
                $dataExt = $data[2]."-".$data[1]."-".$data[0];
            else
                $dataExt = $data[0];

            if($horaEx == 1)
                $dataExt .= " at ".$hora[0]."h".$hora[1];
        } else {
            if($datetime == "") $datetime = "0000-00-00 00:00:00";
            $separa = explode(" ",$datetime);
            $data = explode("-",$separa[0]);
            $hora = explode(":",$separa[1]);

            if($data[2] and $data[1] and $data[0])
                $dataExt = $data[2]."/".$data[1]."/".$data[0];
            else
                $dataExt = $data[0];

            if($horaEx == 1){
                $dataExt .= " às ".$hora[0]."h".$hora[1];
            } elseif($horaEx == 2){
                $dataExt = $hora[0].":".$hora[1].":".$hora[2];
            }
        }
    }
    return $dataExt;
}

function salvarLog($nome, $extra = NULL) {
    $retorno = "Data: ".date("d/m/Y H:i:s");
    $retorno .= "


  POST:
  ";
    $retorno .= print_r($_POST, TRUE);
    $retorno .= "


  SERVER:
  ";
    $retorno .= print_r($_SERVER, TRUE);
    $retorno .= "


  EXTRA:
  ";
    $retorno .= print_r($extra, TRUE);
    $hora = uniqid(); //date('Ymd_His');
    file_put_contents("../storage/temp/".$nome. '_' .$hora .'.txt', $retorno);

    return $nome."_".$hora.".txt";
}

function mascara($val, $mask){
    $maskared = '';
    $k = 0;
    $tamanho = strlen($mask) - 1;
    for($i = 0; $i <= $tamanho; $i++){
        if($mask[$i] == '#'){
            if(isset($val[$k])) $maskared .= $val[$k++];
        } else {
            if(isset($mask[$i])) $maskared .= $mask[$i];
        }
    }
    return $maskared;
}

function diferencaDias($age){
    if (!$age) {
        return '--';
    }

    $age = strtotime($age);
    $age = time()-$age;
    $timet = 3600*24;
    $days = floor($age/$timet);
    $hours = floor($age/3600);
    $minutes = floor($age/60);
    $seconds = $age;

    if ($days>0)
        $age ="$days dia".(($days>1)?"s":"")." atr&aacute;s";
    else if ($hours>0)
        $age ="$hours hora".(($hours>1)?"s":"")." atr&aacute;s";
    else if ($minutes>0)
        $age ="$minutes minuto".(($minutes>1)?"s":"")." atr&aacute;s";
    else
        $age ="$seconds segundo".(($seconds>1)?"s":"")." atr&aacute;s";
    return $age;
}

function retornaTempo($minutos){
    while($minutos >= 60) {
        if($minutos >= 1440) {
            $dias = floor($minutos / 1440);
            $minutos = $minutos % 1440;
        } else {
            $horas = floor($minutos / 60);
            $minutos = $minutos % 60;
        }
    }

    if ($dias > 0) {
        $tempo = $dias." dia".(($dias > 1) ? "s" : "").(($horas > 0 && $minutos > 0) ? ", " : (($horas > 0 || $minutos > 0) ? " e " : ""));
    }
    if ($horas > 0) {
        $tempo .= $horas." hora".(($horas > 1) ? "s" : "").(($minutos > 0) ? " e " : "");
    }
    if ($minutos > 0) {
        $tempo .= $minutos." minuto".(($minutos > 1) ? "s" : "");
    }

    return $tempo;
}

function tamanhoArquivo($tamanhoArquivo, $medida = "KB") {
    if($tamanhoArquivo >= 1048576) {
        $medida = "MB";
    }
    if($medida == "MB") {
        $tamanhoArquivo = number_format((($tamanhoArquivo/1024)/1024), 2, ',', '.');
    } else {
        $tamanhoArquivo = number_format((($tamanhoArquivo/1024)), 2, ',', '.');
    }

    return $tamanhoArquivo." ".$medida;
}


function tamanhoTexto($maxChar,$texto,$html = NULL,$link = NULL){
    $limite         = $maxChar;
    $novoTexto      = $texto;
    if(!$html)
        $novoTexto  = strip_tags($novoTexto);
    $output         = substr("$novoTexto", 0, $limite);

    $char_total     = strlen($novoTexto);
    $char_delimited = strlen($output);

    if($link)
        $retChar    = ($char_delimited >= $char_total) ? $novoTexto : "$output...<a href='$link'>[mais+]</a>";
    else
        $retChar    = ($char_delimited >= $char_total) ? $novoTexto : "$output...";

    return $retChar;
}

function gerarNovaSenha() {
    $caracteresAceitos = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$&*_-+=.";
    $max = strlen($caracteresAceitos) - 1;
    $novaSenha = NULL;
    for($i = 0; $i < 8; $i++) {
        $novaSenha .= $caracteresAceitos{mt_rand(0, $max)};
    }
    return $novaSenha;
}

function retirar_caracteres($string) {
    $palavra = strtr(html_entity_decode($string), "ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ", "SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy");
    $palavra = strtr(html_entity_decode($string), "ìí", "ii");
    $palavranova = str_replace("_", " ", $palavra);
    return $palavranova;
}

/*
function DataDif($dtInicio, $dtFim, $type){
  switch($type){
	case 'A' : $X = 31536000; break;//ano
	case 'M' : $X = 2592000; break; //mes
	case 'D' : $X = 86400; break;   //dia
	case 'H' : $X = 3600; break;    //hora
	case 'I' : $X = 60; break;      //minuto
	default  : $X = 1; break;       //segundo
  }
  return (strtotime($dtFim) - strtotime($dtInicio)) / $X;
}
*/


function dataDiferenca($dataInicio, $dataFim, $tipoRetorno) {

    if(!$dataInicio) $dataInicio = date("Y-m-d H:i:s");
    if(!$dataFim) $dataFim = date("Y-m-d H:i:s");

    $separaDataHoraInicio = explode(" ",$dataInicio);
    $separaDataInicio = explode("-",$separaDataHoraInicio[0]);
    if($separaDataHoraInicio[1]) {
        $separaHoraInicio = explode(":",$separaDataHoraInicio[1]);
    } else {
        $separaHoraInicio = array(0,0,0);
    }

    $separaDataHoraFim = explode(" ",$dataFim);

    $separaDataFim = explode("-",$separaDataHoraFim[0]);
    if($separaDataHoraFim[1]) {
        $separaHoraFim = explode(":",$separaDataHoraFim[1]);
    } else {
        $separaHoraFim = array(0,0,0);
    }
    $seguntos = mktime($separaHoraFim[0],$separaHoraFim[1],$separaHoraFim[2],$separaDataFim[1],$separaDataFim[2],$separaDataFim[0]) - mktime($separaHoraInicio[0],$separaHoraInicio[1],$separaHoraInicio[2],$separaDataInicio[1],$separaDataInicio[2],$separaDataInicio[0]);

    switch($tipoRetorno){
        case 'A' : $divisor = 31536000; break; //anos
        case 'M' : $divisor = 2592000; break;  //meses
        case 'D' : $divisor = 86400; break;    //dias
        case 'H' : $divisor = 3600; break;     //horas
        case 'I' : $divisor = 60; break;       //minutos
        default  : $divisor = 1; break;        //segundos
    }
    if($seguntos > 0) {
        $diferenca = floor($seguntos / $divisor);
    } else {
        $diferenca = 0;
    }

    return $diferenca;
}

function formatar($string, $tipo = "") {
    if(!$string)
        return NULL;

    $string = preg_replace("[^0-9]", "", $string);
    if (!$tipo) {
        switch (strlen($string)) {
            case 10: 	$tipo = 'fone'; 	break;
            case 8: 	$tipo = 'cep'; 		break;
            case 11: 	$tipo = 'cpf'; 		break;
            case 14: 	$tipo = 'cnpj'; 	break;
        }
    }
    switch ($tipo) {
        case 'fone':
            $string = '(' . substr($string, 0, 2) . ') ' . substr($string, 2, 4) . '-' . substr($string, 6);
            break;
        case 'cep':
            $string = substr($string, 0, 5) . '-' . substr($string, 5, 3);
            break;
        case 'cpf':
            $string = substr($string, 0, 3) . '.' . substr($string, 3, 3) . '.' . substr($string, 6, 3) . '-' . substr($string, 9, 2);
            break;
        case 'cnpj':
            $string = substr($string, 0, 2) . '.' . substr($string, 2, 3) . '.' . substr($string, 5, 3) . '/' . substr($string, 8, 4) . '-' . substr($string, 12, 2);
            break;
        case 'rg':
            $string = substr($string, 0, 2) . '.' . substr($string, 2, 3) . '.' . substr($string, 5, 3);
            break;
    }
    return $string;
}

function redimensionar($imagem, $largura, $pasta, $nome_arquivo){

    if ($imagem['type'] == "image/jpeg"){
        $img = imagecreatefromjpeg($imagem['tmp_name']);
    } elseif ($imagem['type'] == "image/gif") {
        $img = imagecreatefromgif($imagem['tmp_name']);
    } elseif ($imagem['type'] == "image/png") {
        $img = imagecreatefrompng($imagem['tmp_name']);
        $is_true_color = imageistruecolor($img);
    }

    $x = imagesx($img);
    $y = imagesy($img);
    $altura = ($largura * $y) / $x;

    if ($imagem['type']!="image/png") {
        $nova = imagecreatetruecolor($largura, $altura);
        imagecopyresampled($nova, $img, 0, 0, 0, 0, $largura, $altura, $x, $y);
    } else {
        if ($is_true_color) {
            $nova = imagecreatetruecolor($largura, $altura);
            imagealphablending($nova, false);
            imagesavealpha($nova, true);
        } else {
            $nova = imagecreate($largura, $altura);
            imagealphablending($nova, false);
            $transparencia = imagecolorallocatealpha($nova, 0, 0, 0, 127);
            imagefill($nova, 0, 0, $transparencia);
            imagesavealpha($nova,true);
            imagealphablending($nova, true);
        }
        imagecopyresampled($nova, $img, 0, 0, 0, 0, $largura, $altura, $x, $y);
    }

    if ($imagem['type'] == "image/jpeg") {
        $arquivo_servidor = $pasta.'/'.$nome_arquivo;
        imagejpeg($nova, $arquivo_servidor);
    } elseif ($imagem['type'] == "image/gif") {
        $arquivo_servidor = $pasta.'/'.$nome_arquivo;
        imagegif($nova, $arquivo_servidor);
    } elseif ($imagem['type'] == "image/png") {
        $arquivo_servidor = $pasta.'/'.$nome_arquivo;
        imagepng($nova, $arquivo_servidor);
    }

    imagedestroy($img);
    imagedestroy($nova);

    return filesize($arquivo_servidor);
}

function print_r2($array, $exit = false){
    echo "<pre>";
    print_r($array);
    echo "</pre>";
    if($exit)
        exit;
}

function apagar_recursividade($diretorio, $raiz = null) {
    $arquivos = array_diff(scandir($diretorio), array('.','..'));
    foreach ($arquivos as $arquivo)
        is_dir("$diretorio/$arquivo") ? apagar_recursividade("$diretorio/$arquivo") : unlink("$diretorio/$arquivo");
    if($raiz) {
        if ($diretorio != $raiz)
            return rmdir($diretorio);
        else
            return true;
    }
    return rmdir($diretorio);
}

//Função para usar o stripslashes() de forma recursiva em um array
//Referêcia: http://php.net/manual/pt_BR/function.stripslashes.php
function stripslashes_deep($value) {
    $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
    return $value;
}

function calculaMedia(){
    $parametros = func_get_args();
    if(empty($parametros)) {
        $media = 0;
    } else {
        $soma = array_sum($parametros);
        $total = count($parametros);
        $media = $soma / $total;
    }
    return $media;
}

function calculaNotaMaiorMedia($nota, $media){
    if($nota < $media) {
        $nota = 0;
    }
    return $nota;
}

function tempoEmSegundos($tempo) {
    if(!$tempo) $tempo = "00:00:00";
    $tempo = explode(":", $tempo);

    $horas = $tempo[0] * 3600;
    $minutos = $tempo[1] * 60;
    $segundos = $tempo[2] + $minutos + $horas;

    return $segundos;
}

# $ultimo_util = 6 (sem o sábado como dia últi) e 7 (sábado como útil)
function dias_uteis($data_inicial_ingles, $data_final_ingles, $ultimo_util) {
    $data_inicial = new DateTime($data_inicial_ingles);
    $data_final = new DateTime($data_final_ingles);

    $uteis = 0;

    # w - 0 (domingo) e 6 (sábado) -- N - 1 (segunda) e 7 (domingo)
    while ($data_inicial->format('Y-m-d') <= $data_final->format('Y-m-d')) {
        /*if ($ano_anterior != $data_inicial->format('Y')) {
            #FERIADOS - INICIO
            $dia = 86400; #segundos em um dia
            $datas = array();
            $datas['pascoa'] = easter_date($data_inicial->format('Y'));
            $datas['sexta_santa'] = $datas['pascoa'] - (2 * $dia);
            $datas['carnaval'] = $datas['pascoa'] - (47 * $dia);
            $datas['corpus_cristi'] = $datas['pascoa'] + (60 * $dia);
            $feriados = array (
                '01/01',
                date('d/m',$datas['carnaval']),
                date('d/m',$datas['sexta_santa']),
                date('d/m',$datas['pascoa']),
                '21/04',
                '01/05',
                date('d/m',$datas['corpus_cristi']),
                '12/10',
                '02/11',
                '15/11',
                '25/12',
            );

            $ano_anterior = $data_inicial->format('Y');
            #FERIADOS - FIM
        }
        if (in_array($data_inicial->format('d/m'), $feriados)) {
            continue;
        }*/
        if ($data_inicial->format('N') >= 1 && $data_inicial->format('N') < $ultimo_util) {
            $uteis++;
        }

        $data_inicial->modify('+1 day');
    }

    return $uteis;
}

function proximoDiaUtil($data, $idEstado = null, $idCidade = null, $idEscola = null, $idSindicato = null) {
    $data = new DateTime($data);
    $data_final = new DateTime();

    require_once DIR_APP . '/classes/feriados.class.php';
    $feriadosObj = new Feriados();
    $feriados = $feriadosObj->diasFeriados(date('Y'), $idEstado, $idCidade, $idEscola, $idSindicato);

    while ($data->format('N') == 6 || $data->format('N') == 7 || in_array($data->format('Y-m-d'), $feriados)) {
        $data->modify('+1 day');
    }

    return $data;
}

function localizei($cpf) {
    $url = 'http://silu001.lumetec.com.br/silu001/servlet/aconsultahttp?';

    $codigoTransacao = 'SICN010';
    $versao = '04';
    $identificacaoSolicitacao = '0000000000';
    $origemSolicitacao = '00000000';
    $codigoEntidade = 'CLU001';
    $logonUsuario = '13060   ';
    $senhaUsuario = '933141  ';
    $codigoProduto = '025';
    $nsuConsulta = '0000000000';
    $tipoDocumento = '1';
    $documento = '000'.str_replace(array(".","-"),"",$cpf);
    $tipoConsultado = ' ';
    $tipoConsultaCheque = '0';
    $quantidadeCheques = '00';
    $banco = '000';
    $agencia = '0000';
    $contaCorrente = '000000000000000';
    $digitoContaCorrente = '0';
    $numeroChequeInicial = '000000';
    $digitoCheque = ' ';
    $cmc7_1 = '00000000';
    $cmc7_2 = '0000000000';
    $cmc7_3 = '000000000000';
    $ddd = '00';
    $telefone = '00000000';
    $reservado = '                                                                                                   ';
    $ch = curl_init();

    $url = $url.urlencode($codigoTransacao.$versao.$identificacaoSolicitacao.$origemSolicitacao.$codigoEntidade.$logonUsuario.$senhaUsuario.
            $codigoProduto.$nsuConsulta.$tipoDocumento.$documento.$tipoConsultado.$tipoConsultaCheque.$quantidadeCheques.$banco.
            $agencia.$contaCorrente.$digitoContaCorrente.$numeroChequeInicial.$digitoCheque.$cmc7_1.$cmc7_2.$cmc7_3.$ddd.$telefone.$reservado);

    // informar URL e outras funções ao CURL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Acessar a URL e retornar a saída
    $output = curl_exec($ch);

    // \/ Exibe o erro caso exista; \/
    if(curl_exec($ch) === false){
        // echo 'Curl erro: ' . curl_error($ch);
    }

    // liberar
    curl_close($ch);

    $dados = array();
    $dados['retorno'] = $output;

    $dados['codigo_transacao'] = substr($output, 0, 7);
    $dados['versao'] = substr($output, 7, 2);
    $dados['identificacao_solicitacao'] = substr($output, 9, 10);
    $dados['nsu_consulta'] = substr($output, 19, 10);
    $dados['indicativo_continuidade'] = substr($output, 29, 1);
    $dados['tamanho_total_registros'] = substr($output, 30, 4);

    //Tipo Registro => 005
    $dados['tipo_registro_1'] = substr($output, 34, 3);
    $dados['tamanho_registro'] = substr($output, 37, 4);
    $dados['cpf'] = substr($output, 41, 11);
    $dados['nome'] = substr($output, 52, 60);
    $dados['nascimento'] = substr($output, 112, 8);
    $dados['numero_identidade'] = substr($output, 120, 16);
    $dados['uf_identidade'] = substr($output, 136, 2);
    $dados['titulo_eleitor'] = substr($output, 138, 13);
    $dados['sexo'] = substr($output, 151, 1);
    $dados['naturalidade'] = substr($output, 152, 40);
    $dados['uf_naturalidade'] = substr($output, 192, 2);
    $dados['nacionalidade'] = substr($output, 194, 1);
    $dados['estado_civil'] = substr($output, 195, 1);
    $dados['nome_conjuge'] = substr($output, 196, 40);
    $dados['data_nascimento_conjuge'] = substr($output, 236, 8);
    $dados['nome_mae'] = substr($output, 244, 40);
    $dados['nome_pai'] = substr($output, 284, 40);
    $dados['informante_1'] = substr($output, 324, 40);
    $dados['provedor_1'] = substr($output, 364, 20);
    $dados['origem_1'] = substr($output, 384, 20);

    $dados['tipo_registro_2'] = substr($output, 404, 3);
    if($dados['tipo_registro_2'] == '015') {
        //Tipo Registro => 015
        $dados['tamanho_registro_2'] = substr($output, 407, 4);
        $dados['ddd'] = substr($output, 411, 2);
        $dados['telefone'] = substr($output, 413, 8);
        $dados['tipo_documento'] = substr($output, 421, 1);
        $dados['documento'] = substr($output, 422, 14);
        $dados['tipo_assinante'] = substr($output, 436, 2);
        $dados['assinante'] = substr($output, 438, 60);
        $dados['endereco_tipo_logradouro'] = substr($output, 498, 15);
        $dados['endereco_logradouro'] = substr($output, 513, 40);
        $dados['endereco_numero'] = substr($output, 553, 6);
        $dados['endereco_complemento'] = substr($output, 559, 10);
        $dados['endereco_bairro'] = substr($output, 569, 20);
        $dados['endereco_cep'] = substr($output, 589, 8);
        $dados['endereco_cidade'] = substr($output, 597, 40);
        $dados['endereco_estado'] = substr($output, 637, 2);
        $dados['informante_2'] = substr($output, 639, 40);
        $dados['provedor_2'] = substr($output, 679, 20);
        $dados['origem_2'] = substr($output, 699, 20);
    } elseif($dados['tipo_registro_2'] == '900') {
        //Tipo Registro => 900
        $dados['tamanho_registro_2'] = substr($output, 407, 4);
        $dados['codigo_retorno'] = substr($output, 411, 3);
        $dados['mensagem_retorno'] = substr($output, 414, 200);
        $dados['informante_2'] = substr($output, 614, 40);
        $dados['provedor_2'] = substr($output, 654, 20);
        $dados['origem_2'] = substr($output, 674, 20);
    }
    // Imprimir a saída
    return $dados;
}

function cortar($frase, $quantidade) {
    $tamanho = strlen($frase);
    if ($tamanho > $quantidade) {
        $frase = substr_replace($frase, "...", $quantidade, $tamanho - $quantidade);
    }

    return $frase;
}

function array_sort($array, $on, $order=SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
                break;
            case SORT_DESC:
                arsort($sortable_array);
                break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}


//Verifica se realmente é o aluno acessando. Pois caso seja o professor ou gestor não irá poder alterar nada, nem contar acessos, etc
function verificaPermissaoAcesso($alerta, $msg = false)
{
    if (($GLOBALS["url"][0] == "aluno" || $GLOBALS["url"][0] == "aluno_novo") && ($_SESSION["cliente_gestor"] || $_SESSION["cliente_professor"])) {
        if ($alerta) {
            if (!$msg) {
                $msg = "Você não possui permissão para fazer essa ação!";
            }
            echo "<script>alert('".$msg."');</script>";
        }
        return false;
    } else {
        return true;
    }
}

function geraCupons($tamanho = 7, $numeros = true, $simbolos = false) {
    $lmai = 'ABCDEFGHJKMNOPQRSTUVWXYZ';
    $num = microtime();
    $num = str_replace('.','',$num);
    $num = str_replace(' ','',$num);
    $num = trim($num);
    $simb = '!@$%-)\(/';
    $retorno = '';
    $caracteres = '';

    $caracteres .= $lmai;
    if ($numeros) $caracteres .= $num;
    if ($simbolos) $caracteres .= $simb;

    $len = strlen($caracteres);
    for ($n = 1; $n <= $tamanho; $n++) {
        $rand = mt_rand(1, $len);
        $retorno .= $caracteres[$rand-1];
    }

    $retorno = strtoupper($retorno);

    return $retorno;
}

function porcentagem($valor, $porcento){

    if(!$valor || !$porcento){
        return false;
    }
    $pAux = $porcento / 100;

    return $pAux * $valor;
}

//Gera uma nota conceito(A,B,C,D,E) baseado em uma nota numérica
function notaConceito($nota) {
    if (!is_numeric($nota)) {
        return false;
    }

    $notaConceito = '';
    if ($nota <= 2) {
        $notaConceito = 'E';
    } elseif ($nota <= 4) {
        $notaConceito = 'D';
    } elseif ($nota <= 6) {
        $notaConceito = 'C';
    } elseif ($nota <= 8) {
        $notaConceito = 'B';
    } elseif ($nota <= 10) {
        $notaConceito = 'A';
    }

    return $notaConceito;
}

function diffHoras($horaEntrada,$horaSaida){
    $HoraEntrada = new DateTime($horaEntrada);
    $HoraSaida   = new DateTime($horaSaida);
    $diff = $HoraSaida->diff($HoraEntrada);
    $hours = $diff->h;
    $hours = $hours + ($diff->days*24);
    return abs($hours);
}

function soNumeros($str){
    return preg_replace("/[^0-9]/", "", $str);

}

function getProvaFinal($avaliacoes,$idAva){
    $matAvaliacoes = NULL;
    $matAvaliacoes = $avaliacoes;
    $qtdAvaliacoes = count($matAvaliacoes);
    for( $i = 0; $i < $qtdAvaliacoes; $i++ ){
        if( $idAva == $matAvaliacoes[$i]['idava'] ){
            return $matAvaliacoes[$i];
        }
    }
}

function getAcessos($acesso,$idAva){
    $retAcessos = NULL;
    $matAcessos = NULL;
    $matAcessos = $acesso;
    $qtdAcessos = count($matAcessos);
    for( $i = 0; $i < $qtdAcessos; $i++ ){
        if( $idAva == $matAcessos[$i]['idava'] ){
            $retAcessos[] = $matAcessos[$i];
        }
    }
    return $retAcessos;
}

function getTotalAcesso($acesso,$idAva){
    $matAcessos = NULL;
    $matAcessos = $acesso;
    $qtdAcessos = count($matAcessos);
    for( $i = 0; $i < $qtdAcessos; $i++ ){
        if( $idAva == $matAcessos[$i]['idava'] ){
            return $matAcessos[$i];
        }
    }
}

function somaHoras($dateIni,$horas){
    $current = new DateTime($dateIni);
    $hoursToAdd = $horas;
    $current->add(new DateInterval("PT{$hoursToAdd}H"));
    $newTime = $current->format('Y-m-d H:i:s');
    return $newTime;
}

function getCurrentDate(){
    $current = new DateTime();
    return $current->format('Y-m-d H:i:s');
}

function getPrimeiroAva($disciplinasAvas){
    foreach($disciplinasAvas as $ava) {
        return $ava['idava'];
    }
}

function getModalOraculo($idModal,$titModal,$msgModal,$textButton=false,$linkButton=false){
    $montaHtml = '<div id="'.$idModal.'" class="modal fade" tabindex="-1" role="dialog">';
    $montaHtml.= '<div class="modal-dialog" role="document">';
    $montaHtml.= '<div class="modal-content">';
    $montaHtml.= '<div class="modal-header">';
    $montaHtml.= '<h5 class="modal-title">'.$titModal.'</h5>';
    $montaHtml.= '<button type="button" class="close" style="margin-top:-25px;" data-dismiss="modal" aria-label="Close">';
    $montaHtml.= '<span aria-hidden="true">&times;</span>';
    $montaHtml.= '</button>';
    $montaHtml.= '</div>';
    $montaHtml.= '<div class="modal-body">';
    $montaHtml.= '<p style="font-size:12px!important;">'.$msgModal.'</p>';
    $montaHtml.= '</div>';
    $montaHtml.= '<div class="modal-footer">';
    /*
    if( $textButton != false ){
        $link = ($linkButton==false)?"javascript:void(0)":$linkButton;
        $montaHtml.= '<a href="'.$linkButton.'" class="btn btn-primary">'.$textButton.'</a>';
    }
    $montaHtml.= '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
    */
    $montaHtml.= '</div>';
    $montaHtml.= '</div>';
    $montaHtml.= '</div>';
    $montaHtml.= '</div>';
    return $montaHtml;
}

function somaDuasHoras($hora1,$hora2) {

    $lista = array($hora1,$hora2);
    $soma = 0;

    foreach($lista as $item) {
        list($horas,$minutos,$segundos) = explode(":",$item);
        $calc = $horas * 3600 + $minutos * 60 + $segundos;
        $soma = $calc + $soma;
    }
    $segundos = $soma;
    $horas = floor($segundos / 3600);
    $minutos = floor($segundos % 3600 / 60);
    $segundos = $segundos % 60;

    return sprintf("%02d:%02d:%02d", $horas, $minutos, $segundos);

}

/**
 * Função para remover os acentos, aspas, sinais de pontuação e espaços duplos
 * @param string $string
 * @return string
 */

function limparString($string) {
    if($string !== mb_convert_encoding(mb_convert_encoding($string, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32'))
        $string = mb_convert_encoding($string, 'UTF-8', mb_detect_encoding($string));
    $string = htmlentities($string, ENT_NOQUOTES, 'UTF-8');
    $string = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $string);
    $string = html_entity_decode($string, ENT_NOQUOTES, 'UTF-8');
    $string = preg_replace(array('`[^a-z0-9]`i','`[-]+`'), ' ', $string);
    $string = preg_replace('/( ){2,}/', '$1', $string);
    $string = strtoupper(trim($string));
    return $string;
}

function tempoExecucao($start = null) {
    // Calcula o microtime atual
    $mtime = microtime(); // Pega o microtime
    $mtime = explode(' ',$mtime); // Quebra o microtime
    $mtime = $mtime[1] + $mtime[0]; // Soma as partes montando um valor inteiro
    if ($start == null) {
        // Se o parametro não for especificado, retorna o mtime atual
        return $mtime;
    }
        // Se o parametro for especificado, retorna o tempo de execução
        return round($mtime - $start, 2);
}

function retornarInterface($slug) {
    foreach ($GLOBALS['orio_interfaces'] as $id => $dados) {
        if ($slug == $dados['slug']) {
            $interface = $dados;
            $interface['id'] = $id;
            return $interface;
        }
    }

    return [];
}