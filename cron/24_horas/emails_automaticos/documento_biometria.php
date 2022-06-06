<?php

$sql = "SELECT p.*, m.idmatricula
        FROM pessoas p
        INNER JOIN matriculas m ON p.idpessoa = m.idpessoa
        WHERE liberacao_temporaria_datavalid = 'S'
        AND email_documento_biometria = 'N'";

$resultado = mysql_query($sql);


