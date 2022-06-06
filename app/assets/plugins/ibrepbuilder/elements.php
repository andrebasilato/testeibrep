<?php
header('Content-Type: application/json');

$elements = ["elements" =>
                ["Texto" =>
                    [["url" => "/assets/plugins/ibrepbuilder/elements/original/texto_simples.html", "height" => 78, "thumbnail" => "/assets/plugins/ibrepbuilder/elements/thumbs/texto_simples.PNG"],
                    ["url" => "/assets/plugins/ibrepbuilder/elements/original/duas_colunas.html", "height" => 95, "thumbnail" => "/assets/plugins/ibrepbuilder/elements/thumbs/duas_colunas.PNG"],
                    ["url" => "/assets/plugins/ibrepbuilder/elements/original/tres_colunas.html", "height" => 128, "thumbnail" => "/assets/plugins/ibrepbuilder/elements/thumbs/tres_colunas.png"]],
                "Imagens" =>
                    [["url" => "/assets/plugins/ibrepbuilder/elements/original/uma_imagem.html", "height" => 409, "thumbnail" => "/assets/plugins/ibrepbuilder/elements/thumbs/uma_imagem.PNG"],
                    ["url" => "/assets/plugins/ibrepbuilder/elements/original/duas_imagens.html", "height" => 292, "thumbnail" => "/assets/plugins/ibrepbuilder/elements/thumbs/duas_imagens.PNG"],
                    ["url" => "/assets/plugins/ibrepbuilder/elements/original/uma_imagem_esquerda.html", "height" => 211, "thumbnail" => "/assets/plugins/ibrepbuilder/elements/thumbs/uma_imagem_esquerda.PNG"],
                    ["url" => "/assets/plugins/ibrepbuilder/elements/original/uma_imagem_direita.html", "height" => 211, "thumbnail" => "/assets/plugins/ibrepbuilder/elements/thumbs/uma_imagem_direita.PNG"]],
                "Mídia" =>
                    [["url" => "/assets/plugins/ibrepbuilder/elements/original/video_individual.html", "height" => 420, "thumbnail" => "/assets/plugins/ibrepbuilder/elements/thumbs/video_individual.PNG"],
                    ["url" => "/assets/plugins/ibrepbuilder/elements/original/audio_individual.html", "height" => 105, "thumbnail" => "/assets/plugins/ibrepbuilder/elements/thumbs/audio_individual.PNG"]],
                ],
                
            ];
echo json_encode($elements);