<?php

$config["formulario"] = [
    [
        "fieldsetid" => "dadosdoobjeto",
        "legendaidioma" => "legendadadosdados",
        "campos" => [
            [
                "id" => "form_idmatricula",
                "nome" => "q[1|m.idmatricula]",
                "nomeidioma" => "form_idmatricula",
                "tipo" => "input",
                "class" => "span2",
            ],
            [
                "id" => "nome_aluno",
                "nome" => "matricula_nome_aluno",
                "nomeidioma" => "nome_aluno",
                "tipo" => "input",
                "valor" => "idmatricula"
            ],
            [
                "id" => "cpf_aluno",
                "nome" => "matricula_cpf_aluno",
                "nomeidioma" => "cpf_aluno",
                "tipo" => "input",
                "valor" => "idmatricula"
            ]
        ]
    ]
];
