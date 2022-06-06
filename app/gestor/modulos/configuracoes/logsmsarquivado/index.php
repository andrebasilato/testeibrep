<?

	include("../classes/sms.class.php");
	include("config.php");
	include("config.formulario.php");
	include("config.listagem.php");
	
	
	//Incluimos o arquivo com variaveis padrão do sistema.
	include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");
	
	$linhaObj = new Sms();
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");	
	
	$linhaObj->Set("idusuario",$usuario["idusuario"]);
	$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);
	
	//ALTERADA CONEXÃO PARA O BANCOS SEPARADO DE LOG DE EMAILS
	$conexao = $linhaObj->iniciaConexao($config["host_log"],
	                                    $config["usuario_log"],
	                                    $config["senha_log"],
	                                    $config["database_log"]);

	$linhaObj->Set("pagina",$_GET["pag"]);
	if(!$_GET["ordem"]) $_GET["ordem"] = "desc";
	$linhaObj->Set("ordem",$_GET["ord"]);
	if(!$_GET["qtd"]) $_GET["qtd"] = 30;
	$linhaObj->Set("limite",intval($_GET["qtd"]));
	if(!$_GET["cmp"]) $_GET["cmp"] = $config["banco"]["primaria"];
	$linhaObj->Set("ordem_campo",$_GET["cmp"]);
	$linhaObj->Set("campos","*");	
	$dadosArray = $linhaObj->ListarTodas();		
	include("idiomas/".$config["idioma_padrao"]."/index.php");
	include("telas/".$config["tela_padrao"]."/index.php");

	//FECHA A CONEXAO COM O BANCO DE EMAILS
	$linhaObj->fechaConexao($conexao);

?>