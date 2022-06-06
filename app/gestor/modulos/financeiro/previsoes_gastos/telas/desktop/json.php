<?php
if ($url[5] == "subcategoria") {
    echo $linhaObj->RetornarSubcategorias(intval($_GET["idcategoria"]), true);
}