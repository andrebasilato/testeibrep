<?php

include("config.php");
include("classe.class.php");

include("../classes/relatorios.class.php");
$relatoriosObj = new Relatorios();
$relatoriosObj->Set("idusuario",$usuario["idusuario"]);

$relatorioObj = new Relatorio();
$relatorioObj->Set("idusuario",$usuario["idusuario"]);
$relatorioObj->Set("monitora_onde",1);  

$arquivo_nome = "../storage/temp/Calendário de Chats.pdf";

include("../assets/plugins/MPDF54/mpdf.php");

ob_start();

$relatorioObj->Set("pagina",1);
$relatorioObj->Set("ordem","ASC");
$relatorioObj->Set("limite",-1);
$relatorioObj->Set("idpessoa",$usuario['idpessoa']);
$relatorioObj->Set("ordem_campo","c.inicio_entrada_aluno");
$relatorioObj->Set("campos","c.idava, c.nome AS chat,c.exibir_ava, c.descricao, a.nome AS ava,
    DATE_FORMAT(c.inicio_entrada_aluno,'%d/%m/%Y %H:%i') AS inicio,
    DATE_FORMAT(c.fim_entrada_aluno,'%d/%m/%Y %H:%i') AS fim");     
$dadosArray = $relatorioObj->gerarRelatorio();  

include("idiomas/".$config["idioma_padrao"]."/html.php");
include("telas/".$config["tela_padrao"]."/html.php");

//GERA O PDF
$mpdf=new mPDF('c','A4',3,'',15,15,16,16,9,9, 'P'); 
$html = ob_get_clean(); 
$mpdf->WriteHTML($html);

$mpdf->Output($arquivo_nome, "F");

header("Content-type: " . filetype($arquivo_nome));
header('Content-Disposition: attachment; filename="' . basename($arquivo_nome) . '"');
header('Content-Length: ' . filesize($arquivo_nome));
header('Expires: 0');
header('Pragma: no-cache');
readfile($arquivo_nome);
exit;
