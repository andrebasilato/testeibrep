<?php
// Temos que abrira a seção aqui para guardarmos as informações dos vídeos
// que serão ultilizadas na hora do cadastro do mesmo ;)
@session_start();

error_log(print_r($_REQUEST, 1), 3, dirname(__FILE__).'/log.txt');
/**
 * upload.php
 *
 * Copyright 2013, Moxiecode Systems AB
 * Released under GPL License.
 *
 * License: http://www.plupload.com/license
 * Contributing: http://www.plupload.com/contributing
 */

// Settings
$targetDir = realpath(dirname(__FILE__).'/../../../storage/temp');
$cleanupTargetDir = true; // Remove old files
$maxFileAge = 5000 * 9000000; // Temp file age in seconds



// converte o vídeo para mp4 nas dimensões solicitadas
// dependendo do formato de video recebido
function convertVideo($video_src, $dir = '', $id = '')
{
    global $targetDir;

    $ffmpeg = $output = `ffmpeg -i "$video_src" 2>&1`;

    error_log("----------------------------------------------------\n", 3, dirname(__FILE__).'/log.txt');
    // informações sobre o vídeo
    error_log('Output -> '. $output, 3, dirname(__FILE__).'/log.txt');

    preg_match('#Duration(.+)#', $output, $match);

    if ($match) {
        $list = explode(',', $match[0]);

        foreach ($list as $value) {
            $content = explode(':', $value, 2);
            $content = array_map('strtolower', $content);
            $collection[trim($content[0])] = $content[1];
        }
    }

    $_SESSION['video_tmp']['info'] = $collection;

    // consegue filtrar essas informações? vai pro log
    error_log(PHP_EOL.'Collection -> '. print_r($collection, 1), 3, dirname(__FILE__).'/log.txt');


    $path =  realpath(dirname(__FILE__).'/../../../storage/videoteca');

    // pega resolução do vídeo
    preg_match('#Stream .+ (\d{1,4}x\d{1,4})#', $ffmpeg, $resolution);
    $resolution = explode('x', $resolution[1]);

    // print_r($resolution);
    if (! isset($resolution[0], $resolution[1])) {
        return false;
    }

    $output_flv = str_replace(end(explode('.', $video_src)), 'mp4', $video_src);
    $hd = str_replace($nametmp = current(explode('.', $video_src)), $nametmp.'_hd', $video_src);
    $nr = str_replace($nametmp = current(explode('.', $video_src)), $nametmp, $video_src);

    $hd = str_replace(end(explode('.', $hd)), 'mp4', $hd);
    $nr = str_replace(end(explode('.', $nr)), 'mp4', $nr);

    error_log(PHP_EOL.'Nome do video -> '. $output_flv, 3, dirname(__FILE__).'/log.txt');

    $_SESSION['video_tmp']['hd'] = $hd;
    $_SESSION['video_tmp']['nr'] = $nr;


    exec("ffmpeg -i '".$video_src."' -ar 22050 -s 720x480 '". $hd."'");
    exec("ffmpeg -i '".$video_src."' -ar 22050 -s 480x320 '". $nr."'");
    error_log(PHP_EOL.'Output hd -> '. $hd, 3, dirname(__FILE__).'/log.txt');
    error_log(PHP_EOL.'Output nr -> '. $nr, 3, dirname(__FILE__).'/log.txt');
    error_log(PHP_EOL.'Comando de converção do vídeo -> '. "ffmpeg -i '".$dir.$video_src."' -ar 22050 -s 720x480 ".str_replace($video_src, 'nr_'.$video_src, $output_flv), 3, dirname(__FILE__).'/log.txt');

    // mkdir($dir.$id);
    $uniq = uniqid();

    exec("ffmpeg -i '".$dir.$video_src."' -an -ss 00:00:5 -an -r 1 -vframes 1 -y {$targetDir}/1.jpg");
    exec("ffmpeg -i '".$dir.$video_src."' -an -ss 00:00:15 -an -r 1 -vframes 1 -y {$targetDir}/2.jpg");
    exec("ffmpeg -i '".$dir.$video_src."' -an -ss 00:00:25 -an -r 1 -vframes 1 -y {$targetDir}/3.jpg");

    $_SESSION['video_tmp']['images'][] = $targetDir.'/1.jpg';
    $_SESSION['video_tmp']['images'][] = $targetDir.'/2.jpg';
    $_SESSION['video_tmp']['images'][] = $targetDir.'/3.jpg';

    // unlink($dir.$video_src);
    return $output_flv;
}

// Make sure file is not cached (as it happens for example on iOS devices)
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


// 5 minutes execution time
@set_time_limit(0);


// Create target dir
if (! file_exists($targetDir)) {
    @mkdir($targetDir, 0777);
}

// Get a file name
if (isset($_REQUEST["name"])) {
    $fileName = $_REQUEST["name"];
} elseif (!empty($_FILES)) {
    $fileName = $_FILES["file"]["name"];
} else {
    $fileName = uniqid("file_");
}

$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

// Chunking might be enabled
$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;


// Remove old temp files
if ($cleanupTargetDir) {
    if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
        die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
    }

    while (($file = readdir($dir)) !== false) {
        $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

        // If temp file is current file proceed to the next
        if ($tmpfilePath == "{$filePath}.part") {
            continue;
        }

        // Remove temp file if it is older than the max age and is not the current file
        if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
            @unlink($tmpfilePath);
        }
    }
    closedir($dir);
}


// Open temp file
if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
    die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
}

if (!empty($_FILES)) {
    if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
        die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
    }

    // Read binary input stream and append it to temp file
    if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
    }
} else {
    if (!$in = @fopen("php://input", "rb")) {
        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
    }
}

while ($buff = fread($in, 4096)) {
    fwrite($out, $buff);
}

@fclose($out);
@fclose($in);

// Check if file has been uploaded
if (! $chunks || $chunk == $chunks - 1) {
    // Strip the temp .part suffix off
    rename("{$filePath}.part", $filePath);

    convertVideo($filePath, '', 2);

}

// Return Success JSON-RPC response
die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');