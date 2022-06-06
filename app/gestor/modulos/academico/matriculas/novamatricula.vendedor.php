<?php
include ("../classes/ofertas.class.php");

$ofertaObj = new Ofertas();
$escola = $ofertaObj->retornarCursoEscola($url[6]);

if($_GET["vendedor"]){

    if(empty($_SESSION["matricula"]["idescola"]))
    {
        echo "<script>

                setTimeout(function(){ window.location.href = '" . $GLOBALS['urlSistema'] . "/gestor/academico/matriculas' }, 1000);
                alert('Matrícula já cadastrada na nossa base de dados!');
                </script>";
        exit();
    }
	$core = new Core();
	/*
	 *
	 * $core->sql = "select
	 * idsindicato
	 * from
	 * cursos_sindicatos ci
	 * inner join ofertas_cursos oc on (ci.idcurso = oc.idcurso)
	 * where
	 * ci.ativo = 'S' and
	 * oc.idoferta_curso = ".$_SESSION["matricula"]["idoferta_curso"];
	 *
	 * $core->Set("ordem","asc");
	 * $core->Set("ordem_campo","idsindicato");
	 * $core->Set("limite","-1");
	 *
	 * $sindicatos = $core->retornarLinhas();
	 * $idsindicato = array();
	 * foreach($sindicatos as $sindicato) {
	 * $idsindicato[] = $sindicato["idsindicato"];
	 * }
	 *
	 * print_r($_SESSION["matricula"]["idescola"]);
	 */
	// if(count($idsindicato) > 0) {
	$core->sql = "select
					v.*
				  from
					vendedores v
					inner join vendedores_sindicatos vi on (v.idvendedor = vi.idvendedor AND vi.ativo = 'S' )
					inner join vendedores_escolas ve on (v.idvendedor=ve.idvendedor AND ve.ativo = 'S' AND ve.idescola= ".$_SESSION["matricula"]["idescola"].")
					inner join escolas p on (p.idsindicato = vi.idsindicato)
				  where
					v.ativo = 'S' and
					p.idescola = ".$_SESSION["matricula"]["idescola"]." ";

	// if($_SESSION["adm_gestor_sindicato"] == 'N') $core->sql .= " and vi.idsindicato in(".$_SESSION['adm_sindicatos'].")";
	if($_GET["vendedor"]!="%")
		$core->sql .= " and (v.documento = '".mysql_real_escape_string($_GET["vendedor"]).
			"' or v.nome like '%".mysql_real_escape_string($_GET["vendedor"])."%')";

	$core->sql .= " group by idvendedor";

	$core->Set("ordem","asc");
	$core->Set("ordem_campo","v.nome");
	$core->Set("limite","-1");
	$vendedores = $core->retornarLinhas();
	// }
}else{
	unset($_SESSION["matricula"]["pessoa"]);
	$_SESSION["matricula"]["pessoa"] = $_POST;
}


$turma = $ofertaObj->retornarDadosTurma($url[7]);

$core = new Core();
$vendedoresVisitas = array();
if(count($idsindicato)>0){
	$core->sql = "select
					ve.*
				  from
					visitas_vendedores v
					inner join vendedores ve on (v.idvendedor = ve.idvendedor)
					inner join vendedores_sindicatos vi on (v.idvendedor = vi.idvendedor and vi.ativo = 'S')
					left join visitas_vendedores_cursos vvc on v.idvisita = vvc.idvisita
					left outer join pessoas p on (v.idpessoa = p.idpessoa)
				  where
					v.ativo = 'S' and
					(v.email = '".
		$_SESSION["matricula"]["pessoa"]["email"]."' or p.email = '".
		$_SESSION["matricula"]["pessoa"]["email"]."') and
					(vvc.idvisita_curso is null or vvc.idcurso = ".$escola["idcurso"].") and
					vi.idsindicato in(".join(", ",$idsindicato).") ";

	if($_SESSION["adm_gestor_sindicato"]=='N')
		$core->sql .= " and vi.idsindicato in(".$_SESSION['adm_sindicatos'].")";

	$core->sql .= "  group by ve.idvendedor ";

	$core->Set("ordem","asc");
	$core->Set("ordem_campo","ve.nome");
	$core->Set("limite","-1");
	$vendedoresVisitas = $core->retornarLinhas();
}
require ("novamatricula.seguranca.php");

include ("idiomas/".$config["idioma_padrao"]."/novamatricula.vendedor.php");
include ("telas/".$config["tela_padrao"]."/novamatricula.vendedor.php");
exit();
?>
