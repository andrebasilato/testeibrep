<ul class="nav nav-tabs">
	<li <?= ($url[4] == "editar") ? 'class="active"' : ''; ?> >
		<a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/editar">
			<?= $idioma["tab_editar"]; ?>
		</a>
	</li>
	<li <?= ($url[4] == "professores") ? 'class="active"' : ''; ?> >
		<a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/professores">
			<?= $idioma["tab_professores"]; ?>
		</a>
	</li>
	<li <?= ($url[4] == "remover") ? 'class="active"' : ''; ?> >
		<a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/remover">
			<?= $idioma["tab_remover"]; ?>
		</a>
	</li>
</ul>