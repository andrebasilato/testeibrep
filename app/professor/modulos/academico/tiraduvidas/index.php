<?php
include_once("../classes/professores.class.php");
include_once("../classes/avas.mensagem_instantanea.class.php");

include("config.php");
include("config.listagem.php");

//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");

$linhaObj = new Professores();

$linhaObj->Set("idprofessor",$usu_professor["idprofessor"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);

$mensagemObj = new MensagemInstantanea();
//$mensagemObj->set("idava",intval($url[3]));
$mensagemObj->set("idprofessor",$usu_professor["idprofessor"]);
//Retorna as mensagens instantâneas que o aluno participa e os integrantes
$mensagemObj->Set("pagina",$_GET["pag"]);
if(!$_GET["ordem"]) $_GET["ordem"] = "DESC";
$mensagemObj->Set("ordem",$_GET["ord"]);
if(!$_GET["qtd"]) $_GET["qtd"] = 30;
if(intval($_GET["qtd"]) > 100) $_GET["qtd"] = 100;
$mensagemObj->Set("limite",intval($_GET["qtd"]));
if(!$_GET["cmp"]) $_GET["cmp"] = "sinalizador_professor ASC, ami.idmensagem_instantanea";
$mensagemObj->Set("ordem_campo",$_GET["cmp"]);
$mensagemObj->set("mantem_groupby",true);
$mensagemObj->set("groupby","ami.idmensagem_instantanea");
/*$mensagemObj->Set("campos","a.idava,
                        a.nome as ava,
                        ami.idmensagem_instantanea,
                        ami.data_cad,
                        ami.ultima_interacao,
                        (SELECT 
                                count(amicv2.idmensagem_instantanea_conversas_visualizar) 
                            FROM
                                avas_mensagem_instantanea_conversas amic2
                                INNER JOIN avas_mensagem_instantanea_conversas_visualizar amicv2 ON (amicv2.idmensagem_instantanea_conversa = amic2.idmensagem_instantanea_conversa)
                            WHERE 
                                amic2.idmensagem_instantanea = ami.idmensagem_instantanea AND
                                amicv2.idmensagem_instantanea_integrante = amii.idmensagem_instantanea_integrante
                        ) as qnt_conversas_nao_lidas,
                        (SELECT 
                            p.nome 
                        FROM 
                            avas_mensagem_instantanea_integrantes amiia 
                            INNER JOIN pessoas p ON (amiia.idpessoa = p.idpessoa) 
                        WHERE 
                            amiia.idmensagem_instantanea = ami.idmensagem_instantanea AND 
                            amiia.idpessoa IS NOT NULL 
                        ORDER BY amiia.data_cad ASC LIMIT 1
                        ) as aluno");*/
$mensagemObj->Set("campos","a.idava,
                        a.nome as ava,
                        ami.idmensagem_instantanea,
                        ami.data_cad,
                        ami.ultima_interacao,
                        ami.sinalizador_professor, 
                        p.nome as aluno");
$duvidas = $mensagemObj->ListarMensagensInstantaneasProfessor();

//print_r2($duvidas,true);

include("idiomas/".$config["idioma_padrao"]."/index.php");
include("telas/".$config["tela_padrao"]."/index.php");
exit;