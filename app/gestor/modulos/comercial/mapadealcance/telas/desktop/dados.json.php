<?php
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

$currentDir = dirname(__FILE__);
$backPath = 0;

for(;$backPath < 6; $backPath++)
    $currentDir = dirname($currentDir);

$classesDirectory = $currentDir . DS . 'classes';

require $classesDirectory . DS . 'http' . DS . 'response.php';

$response = Response::getInstance();

$response->setStatusCode(200)
        ->setMessage('OK')
        ->setContentType('application/javascript')
        ->send();
?>
var data = {"matriculas": <?php echo json_encode($matriculas); ?>}