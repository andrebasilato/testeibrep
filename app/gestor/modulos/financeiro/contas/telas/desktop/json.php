<?php
if ($url[5] == "subcategoria") {
    echo $linhaObj->RetornarSubcategorias(intval($_GET["idcategoria"]), true);
} else if ($url[6] == "associar_centros_custos") {
    echo $linhaObj->BuscarCentroCusto();
}
