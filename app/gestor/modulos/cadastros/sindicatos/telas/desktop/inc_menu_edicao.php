<ul class="nav nav-tabs">
	<li <? if($url[4] == 'editar') { ?> class="active"<? } ?>>
		<a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/editar">
			<?= $idioma['tab_editar']; ?>
		</a>
	</li>
	<li <? if($url[4] == 'acesso_ava') { ?> class="active"<? } ?>>
		<a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/acesso_ava">
			<?= $idioma['tab_acesso_ava']; ?>
		</a>
	</li>
	<li <? if($url[4] == 'pastavirtual') { ?> class="active"<? } ?>>
		<a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/pastavirtual">
			<?= $idioma['tab_pastavirtual']; ?>
		</a>
	</li>
    <li <? if($url[4] == 'valores_cursos') { ?> class="active"<? } ?>>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/valores_cursos">
            <?= $idioma['tab_valores_cursos']; ?>
        </a>
    </li>
	<li <? if($url[4] == 'remover') { ?> class="active"<? } ?>>
		<a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/remover">
			<?= $idioma['tab_remover']; ?>
		</a>
	</li>
</ul>