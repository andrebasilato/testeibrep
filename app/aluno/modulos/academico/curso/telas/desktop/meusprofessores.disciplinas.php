<i class="closed-x" data-dismiss="modal"> <strong><?php echo $idioma['fechar']; ?></strong></i>
<h1><?php echo $idioma['titulo']; ?></h1>
<div class="extra-align l-text">
    <?php foreach ($disciplinas as $disciplina) { ?>
        <i class="icon-book l-align"></i><p>&nbsp<?php echo $disciplina['nome']; ?></p>
    <?php } ?>
</div>