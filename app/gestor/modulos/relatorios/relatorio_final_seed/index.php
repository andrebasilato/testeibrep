<?php
include ("config.php");
include ("classe.class.php");
include ("../classes/relatorios.class.php");
include ("../classes/matriculas.class.php");

$relatoriosObj = new Relatorios();
$relatoriosObj->Set("idusuario",$usuario["idusuario"]);

$relatorioObj = new Relatorio();
$relatorioObj->Set("idusuario",$usuario["idusuario"]);
$relatorioObj->Set("monitora_onde",1);
$relatorioObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");

if($_POST['acao'] == 'salvar_relatorio') {
 $relatoriosObj->Set("post", $_POST);
 $salvar = $relatoriosObj->salvarRelatorio();
 if($salvar['sucesso']) {
  $mensagem_sucesso = "salvar_relatorio_sucesso";
 } else {
  $mensagem_erro = $salvar['erro_texto'];
 }
}

switch($url[3]) {
 case "ajax_escolas":
  $relatorioObj->Set("id", (int) $_REQUEST['idsindicato']);
  $relatorioObj->retornarEscolas();
  
  break;
 case "ajax_cursos":
  $relatorioObj->Set("id", (int) $_REQUEST['idoferta']);
  $relatorioObj->retornarCursosOferta();
  
  break;
 case "ajax_turmas":
  $relatorioObj->Set("id", (int) $_REQUEST['idoferta']);
  $relatorioObj->retornarTurmasOferta();
  
  break;
 case "html":
  $relatorioObj->Set("pagina", 1);
  $relatorioObj->Set("ordem", "desc");
  $relatorioObj->Set("limite", - 1);
  $relatorioObj->Set("ordem_campo", "ma.idcurso");
  $relatorioObj->Set("campos", "ma.idsindicato,
                                i.nome as sindicato,
                                
                                
                                ma.idcurso,
                                ma.idturma,
                                tu.nome as turma,
                                cu.nome as curso,
                                cu.carga_horaria_total,
                                ma.idescola,
                                po.nome_fantasia as escola,
                                ma.idoferta,
                                o.nome as oferta,
                                o.data_inicio_matricula,
                            	o.data_fim_matricula,
                                c.nome as cidade,
                                m.nome_fantasia as mantenedora,
                                i.nre,
                                ma.idmatricula,
                                a.nome,
                                a.sexo");
  $dados = $relatorioObj->gerarRelatorio();
  
  $hoje = getdate();
  
  $dia = $hoje["mday"];
  
  $mes = $hoje["mon"];
  
  $nomemes = $meses_idioma['pt_br'][$mes];
  
  $ano = $hoje["year"];
  
  $diadasemana = $hoje["wday"];
  
  $nomediadasemana = $dia_semana["pt_br"][$diadasemana];
  
  include ("idiomas/" . $config["idioma_padrao"] . "/html.php");
  include ("telas/" . $config["tela_padrao"] . "/html.php");
  break;
 default:
  include ("idiomas/" . $config["idioma_padrao"] . "/index.php");
  include ("telas/" . $config["tela_padrao"] . "/index.php");
}

?>	
            