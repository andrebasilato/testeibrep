<div vw class="enabled">
    <div vw-access-button class="active"></div>
    <div vw-plugin-wrapper>
      <div class="vw-plugin-top-wrapper"></div>
    </div>
  </div>
  <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
  <script>
    new window.VLibras.Widget('https://vlibras.gov.br/app');
  </script>
<div id="footer">
	<div class="content">
		<p><?php echo $idioma['ambiente_ensino']; ?></p>
		<a target="_blank" href="/<?php echo $url[0]; ?>"><img src="/assets/aluno_novo/img/marca_mini.png" alt="Marca"></a>
	</div>
</div>

<!-- <script src="/assets/min/aplicacao.aluno.min.js"></script> -->

<script src="/assets/aluno_novo/js/prefixfree.min.js"></script>
<script src="/assets/aluno_novo/js/respond.min.js"></script>
<script src="/assets/aluno_novo/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/aluno_novo/js/main.js"></script>
<script src="/assets/aluno_novo/js/svgcheckbx.js"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-61Q7DH8YXN"></script>
<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());
	gtag('config', 'G-61Q7DH8YXN');
</script>

<?php
if(empty($_SESSION['adm_idusuario']) && empty($_SESSION['cliente_gestor'])) {

    //Validação de multiplas Sessões/Logins
    include_once '../classes/gestaoacessos.class.php';
    $gestaoAcessosObjeto = new GestaoAcessos();
    $gestaoAcessosObjeto->verificaSessao($_SESSION['cliente_idpessoa'], $_SESSION['idsessao']);

}
?>
<!--<script src="rjminimize.php?name=jstotal.js&type=js&path=_js_scripts.php"></script>-->
<!--<script src="/assets/aluno_novo/js/respond.min.js"></script>-->
<!--<script src="/assets/aluno_novo/js/plugins.js"></script>-->
