<?php

$sql = "SELECT 
            c.data_vencimento , e.idescola 
        FROM 
            escolas e 
        INNER JOIN contas c ON (e.idescola = c.idescola) 
        INNER JOIN contas_workflow cw ON (cw.idsituacao = c.idsituacao) 
        WHERE 
                e.ativo = 'S' AND 
                (e.acesso_bloqueado <> 'S' OR e.acesso_bloqueado IS NULL) AND 
                c.ativo = 'S' AND 
                cw.emaberto = 'S' AND
                cw.cancelada <> 'S' AND
				c.fatura = 'S'                  
        GROUP BY e.idescola";

$query = mysql_query($sql);
while ($escola = mysql_fetch_assoc($query)) {
    $dataAtual = new DateTime();
    $data = new DateTime($escola['data_vencimento']);
    $data->modify('+3 days');

    if($dataAtual->format('Y-m-d') >= $data->format('Y-m-d')){
        mysql_query("BEGIN");
        $sql = "UPDATE escolas SET acesso_bloqueado = 'S' WHERE idescola = ".$escola['idescola'];

        $queryAux = mysql_query($sql);
        if($queryAux){
            $sql = "INSERT INTO 
                        escolas_historico 
                    SET data_cad = NOW() , idusuario = NULL , acesso_bloqueado = 'S' , idescola = " . $escola['idescola'] ;
            $queryAux = mysql_query($sql);
        }
        if(!$queryAux){
            mysql_query("ROLLBACK");
            break;
        }
        mysql_query("COMMIT");
    }
}

$sql = "SELECT
            (
            select
                count(0)
            from
                contas c
            INNER JOIN contas_workflow cw ON
                (cw.idsituacao = c.idsituacao)
            where
                c.fatura = 'S'
                and c.ativo = 'S'
                and cw.emaberto = 'S'
                and e.idescola = c.idescola
                and Now() > c.data_vencimento) as faturas_em_aberto,
            e.idescola
        FROM
            escolas e
        WHERE
            e.ativo = 'S' 
            AND (e.acesso_bloqueado <> 'N'
            OR e.acesso_bloqueado IS NULL)
        GROUP BY
            e.idescola";

$query = mysql_query($sql);

while ($escola = mysql_fetch_assoc($query)) {
    if($escola['faturas_em_aberto'] == 0){
        mysql_query("BEGIN");
        $sql = "UPDATE escolas SET acesso_bloqueado = 'N' WHERE idescola = ".$escola['idescola'];
        $queryAux = mysql_query($sql);
        if($queryAux){
            $sql = "INSERT INTO 
                            escolas_historico 
                        SET data_cad = NOW() , idusuario = NULL , acesso_bloqueado = 'N' , idescola = " . $escola['idescola'] ;
            $queryAux = mysql_query($sql);
        }
        if(!$queryAux){
            mysql_query("ROLLBACK");
            break;
        }
        mysql_query("COMMIT");
    }
}
