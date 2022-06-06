<?php

$i = 0;
$tabs = '';
$content = '';

localeconv();
setlocale(LC_CTYPE, 'en_US.UTF-8');

foreach ($tags as $session => $tagCollection) {

    $class = (0 == $i) ? 'active ' : '';

    // Guarda as guias
    $tabs .= '<li class="'.trim($class).'"><a data-toggle="tab" href="#tab'.$i.'">'.$session.'</a></li>';

    // Guarda o conteúdo
    $content .='<div class="tab-pane '.$class.'" id="tab'.$i.'"><p>';

    $content .= '<table width="100%" class="table-striped table-bordered table-condensed">
      <tbody>
      <tr>
        <td width="100%" style="border:0px; border-bottom: 1px solid #DDDDDD; background-color:#F8F8F8" colspan="4">
          <strong>'. mb_strtoupper($session,'UTF-8') .':</strong>
        </td>
      </tr>';

        foreach ($tagCollection as $tagName => $description) {
            $content .= '<tr>
            <td><strong>'. mb_strtolower('[['.str_replace(array('\'', '~'), array('',''), iconv("UTF-8", "us-ascii//TRANSLIT", $session)).']['.$tagName.']]','UTF-8') .'</strong></td>
            <td>'.$description.'</td>
            </tr>';
        }

        $content .= '</tbody></table>';
        $content .= '</p></div>' . PHP_EOL;
        $i++;

} ?>

    <h2>TABELA DE VARIÁVEIS</h2>
    <hr />

    <ul class="nav nav-tabs" id="myTab">
    <?php echo $tabs; ?>
    </ul>

    <div class="tab-content">
    <?php echo $content; ?>
    </div>

    <p>&nbsp;</p>
    </div>
</div>