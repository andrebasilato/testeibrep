<?php
$sql = "SELECT * FROM matriculas_avas_porcentagem WHERE data_ini IS NULL OR data_fim IS NULL";
$res = mysql_query($sql);
while ($linha = mysql_fetch_assoc($res)) {
    if (
        $linha['porcentagem'] > 0
        && empty($linha['data_ini'])
    ) {
        $sqlIni = "SELECT inicio FROM pessoas_acessos_matriculas WHERE idmatricula = {$linha['idmatricula']} AND idava = {$linha['idava']} ORDER BY data_cad ASC LIMIT 1";
        $resIni = mysql_query($sqlIni);
        if (mysql_num_rows($resIni)) {
            $ini = mysql_fetch_assoc($resIni);
            $sqlUpdateIni = "UPDATE matriculas_avas_porcentagem SET data_ini = '{$ini['inicio']}' WHERE idmatricula_ava_porcentagem = {$linha['idmatricula_ava_porcentagem']}";
            echo "Matrícula: {$linha['idmatricula']}, ava: {$linha['idava']}, data_ini: {$ini['inicio']}; <br>";
            mysql_query($sqlUpdateIni) or die($sqlUpdateIni);
        }
    }
    if (
        $linha['porcentagem'] == 100
        && empty($linha['data_fim'])
    ) {
        $sqlFim = "SELECT fim FROM pessoas_acessos_matriculas WHERE idmatricula = {$linha['idmatricula']} AND idava = {$linha['idava']} ORDER BY data_cad DESC LIMIT 1";
        $resFim = mysql_query($sqlFim);
        if (mysql_num_rows($resFim)) {
            $fim = mysql_fetch_assoc($resFim);
            $sqlUpdateFim = "UPDATE matriculas_avas_porcentagem SET data_fim = '{$fim['fim']}' WHERE idmatricula_ava_porcentagem = {$linha['idmatricula_ava_porcentagem']}";
            echo "Matrícula: {$linha['idmatricula']}, ava: {$linha['idava']}, data_fim: {$fim['fim']}; <br>";
            mysql_query($sqlUpdateFim) or die($sqlUpdateFim);
        }
    }
}
