<?php

if (strpos($conteudo["conteudo"], '<!DOCTYPE html>') !== false || strpos($conteudo["conteudo"], '<html>') !== false) {
    $save = $conteudo["conteudo"];
}
else {
    $save = "<!DOCTYPE html>
    <html>
    <head>
        <title>Conteudo</title>
        <meta charset=\"utf-8\">
        <meta name=\"viewport\" content=\"width = device-width, initial-scale = 1, minimum-scale = 1, maximum-scale = 1\">
        <link rel=\"stylesheet\" type=\"text/css\" href=\"/assets/min/aplicacao.aluno.min.css\">
        <link href=\"//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css\" rel=\"stylesheet\">
        <link rel=\"stylesheet\" href=\"//cdn.jsdelivr.net/medium-editor/latest/css/medium-editor.min.css\" type=\"text/css\" media=\"screen\" charset=\"utf-8\" id=\"mediumCss0\"><link rel=\"stylesheet\" href=\"/assets/plugins/ibrepbuilder/elements/css/medium-bootstrap.css\" type=\"text/css\" media=\"screen\" charset=\"utf-8\" id=\"mediumCss1\">
    </head>
    <body>
        <div id=\"page\" class=\"page contents\">"
            .$conteudo["conteudo"].
        "</div><!-- /#page -->
        <script type=\"text/javascript\" src=\"/assets/plugins/ibrepbuilder/elements/bundles/original_skeleton.bundle.js\"></script>
    </body>
    </html>";
}

header('Content-Disposition: attachment; filename="'.$linha["idconteudo"].'.html"');
header('Content-Type: text/plain');
header('Content-Length: ' . strlen($save));
header('Connection: close');

echo $save;
exit;