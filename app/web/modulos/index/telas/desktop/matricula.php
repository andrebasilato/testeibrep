<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/2000/svg">
  <head>
    <?php
      incluirLib('head', $config, $usuario);
      $imgPage = "http://".$_SERVER["SERVER_NAME"].'/assets/img/social.jpg'; //500x250px
    ?>
    <link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="<?= $ofertaCurso['nome']; ?>'/>">
    <meta name="author" content="Alfama Web">

    <meta name="theme-color" content="#3F88EB">

    <!-- Social: Twitter -->
    <meta name='twitter:card' content='summary_large_image'>
    <meta name='twitter:title' content='<?= $config["tituloEmpresa"]; ?> - <?= $url[0]; ?>'>
    <meta name='twitter:description' content='<?= $ofertaCurso['nome']; ?>'>
    <meta name='twitter:image:src' content='<?= $imgPage; ?>'>

    <!-- Social: Facebook / Open Graph -->
    <meta property='og:url' content='<?= "http://".$_SERVER["SERVER_NAME"].strtok($_SERVER ["REQUEST_URI"],"?"); ?>'>
    <meta property='og:type' content='website'>
    <meta property='og:title' content='<?= $config["tituloEmpresa"]; ?> - <?= $url[0]; ?>'>
    <meta property='og:image' content='<?= $imgPage; ?>'/>
    <meta property='og:description' content='<?= $ofertaCurso['nome']; ?>'>
    <meta property='og:site_name' content='<?= $config["tituloEmpresa"]; ?>'>

    <!-- ICONS -->
    <link rel="apple-touch-icon" sizes="57x57" href="/assets/img/metas/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/assets/img/metas/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/assets/img/metas/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/assets/img/metas/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/assets/img/metas/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/assets/img/metas/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/assets/img/metas/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/assets/img/metas/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/metas/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/assets/img/metas/android-icon-192x192.png">
    <!-- <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/metas/favicon-32x32.png"> -->
    <link rel="icon" type="image/png" sizes="96x96" href="/assets/img/metas/favicon-96x96.png">
    <link rel="shortcut icon" type="image/png" sizes="16x16" href="/assets/img/metas/favicon-16x16.png">
    <link rel="manifest" href="/assets/img/metas/manifest.json">
    <meta name="msapplication-TileColor" content="#36b8ea">
    <meta name="msapplication-TileImage" content="/assets/img/metas/ms-icon-144x144.png">
    <link rel="stylesheet" href="/assets/loja/css/matricula.css">
    <style type="text/css">
      .btAccordion {
        cursor: pointer;
      }

      div.lince-input div ul{
        height: 130px;
        overflow: auto;
      }
    </style>

    <title><?= $config["tituloEmpresa"]; ?> - <?= $url[0]; ?></title>
  </head>
  <body style="background-color: #3F88EB; height: auto;">
    <nav>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12 marcas">
            <div class="marcaCliente">
              <img src="/especifico/img/logo_loja_peq.png" title="Marca <?= $config["tituloEmpresa"] ?>" alt="marca-oraculo" class='img-fluid'>
            </div>

          </div>
        </div>
      </div>
    </nav>

    <div class="wrap">
      <div class="loader"></div>
      <form id="form-matricula" name="form_matricula" method="post" enctype='multipart/form-data' data-sucess='Mensagem enviada com sucesso' data-error='Problemas ao enviar a mensagem'>
        <!-- <input type='hidden' name='sessao_pagseguro' value='<?= (!empty($sessaoPagSeguro)) ? $sessaoPagSeguro : ""; ?>'> -->
        <input type='hidden' name='financeiro' value='<?= ($ofertaCurso['possui_financeiro'] == "S") ? 1 : 0; ?>'>
        <input type='hidden' name='idoferta' value='<?= $ofertaCurso["idoferta"]; ?>'>
        <!-- <input type='hidden' name='idcontato' value='<?= $ofertaCurso["idcontrato"]; ?>'> -->
        <input type='hidden' name='idpessoa' value=''>
        <input type='hidden' name='valor' value='<?= $ofertaCurso["valor"] ?>'>
        <input type='hidden' name='valor_avista' value='<?= $curso["avista"] ?>'>
        <input type='hidden' name='valor_aprazo' value='<?= $curso["aprazo"] ?>'>
        <input type='hidden' name='qtd_parcelas' value='<?= $curso["parcelas"] ?>'>
        <!-- <input type='hidden' name='valor_parcela' value='<?= $ofertaCurso["valor_parcela"] ?>'> -->
        <!-- <input type='hidden' name='valor_parcela_original' value='<?= $ofertaCurso["valor_parcela"] ?>'> -->
         <input type='hidden' name='cfc' value='<?= $curso["idcfc"] ?>'>
        <!--<input type='hidden' name='idcupom' value=''>
        <input type='hidden' name='codigo_cupom' value=''>
        <input type='hidden' name='senderHash' value=''>-->
        <input type='hidden' name='forma_pagamento' value='11'> 
        <input type='hidden' name='tipo_pagamento' value='FC'> 
        <input type='hidden' name='brand' value=''>
        <input type='hidden' name='token' value=''>

        <div class="container">
          <div class="row">
              <div class="col-lg-8 col-md-12">
                <div class="titulo">
                  <h1><?= $idioma['pagina_titulo']; ?></h1>
                  <p><?= $idioma['pagina_descricao']; ?></p>
                </div>
              </div>
          </div>
          <div class="row">
            <div class="col-lg-8 col-md-12 conteudoForm">
              <div class="bg">
                <div class="itemForm" id="dados-comprador">
                  <div class="row">
                    <div class="col-md-12">
                      <h2><?= $idioma['dados_comprador']; ?></h2>
                    </div>
                    <div class="col-md-8">
                      <div class='lince-input'>
                        <label for='input-email'><?= $idioma['email']; ?></label>
                        <input type='text' name='email' placeholder='<?= $idioma['email']; ?>' id='input-email' maxlength='50' class='input-email' required>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class='lince-input'>
                        <label for='input-cpf'><?= $idioma['cpf']; ?></label>
                        <input type='text' name='documento' placeholder='<?= $idioma['cpf']; ?>' id='input-cpf' maxlength='20' class='input-cpf' required>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class='lince-input'>
                        <label for='input-nome'><?= $idioma['nome']; ?></label>
                        <input type='text' name='nome' placeholder='<?= $idioma['nome']; ?>' id='input-nome' maxlength='100' class='' required>
                      </div>
                    </div>
                    <div class="col-md-4">
                        <label for='input-sexo'><?= $idioma['sexo']; ?></label>
                        <select id="input-sexo" name="sexo" class="input-select">
                            <option value="" class="select-placeholder"><?= $idioma['sexo']; ?></option>
                            <option value="F">Feminino</option>
                            <option value="M">Masculino</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                      <div class='lince-input'>
                        <label for='input-data_nasc'><?= $idioma['data_nasc']; ?></label>
                        <input type='text' name='data_nasc' placeholder='<?= $idioma['data_nasc']; ?>' id='input-data_nasc' maxlength='50' class='input-datebr'>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class='lince-input'>
                        <label for='input-rg'><?= $idioma['rg']; ?></label>
                        <input type='text' name='rg' placeholder='<?= $idioma['rg']; ?>' id='input-rg' maxlength='20' class='input-rg'>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class='lince-input'>
                        <label for='input-rg_orgao_emissor'><?= $idioma['rg_orgao_emissor']; ?></label>
                        <input type='text' name='rg_orgao_emissor' placeholder='<?= $idioma['rg_orgao_emissor']; ?>' id='input-rg_orgao_emissor' maxlength='20' class='input-rg_orgao_emissor'>
                      </div>
                    </div>
                    
                    <div class="col-md-4">
                      <div class='lince-input'>
                        <label for='input-cnh'><?= $idioma['cnh']; ?></label>
                        <input type='text' name='cnh' placeholder='<?= $idioma['cnh']; ?>' id='input-cnh' maxlength='100' class='input-cnh' required>
                      </div>
                    </div>
                      <div class="col-md-4">
                          <div class='lince-input'>
                              <label for='categoria'><?= $idioma['categoria']; ?></label>
                              <select id="input-categoria" name="categoria" class="input-select">
                                  <option value="" class="select-placeholder"><?= $idioma['categoria']; ?></option>
                                  <?php
                                  foreach ($categorias as $ind => $valor) {
                                      ?>
                                      <option value="<?= $valor; ?>">
                                          <?= $valor; ?>
                                      </option>
                                      <?php
                                  }
                                  ?>
                              </select>
                          </div>
                      </div>
                    <div class="col-md-4">
                      <div class='lince-input'>
                        <label for='input-celular'><?= $idioma['celular']; ?></label>
                        <input type='text' name='celular' placeholder='<?= $idioma['celular']; ?>' id='input-celular' maxlength='50' class='input-tel' required>
                      </div>
                    </div>
                    <div class="col-md-8">
                      <div class='lince-input'>
                        <label for='input-cnh'><?= $idioma['ato_punitivo']; ?></label>
                        <input type='text' name='ato_punitivo' placeholder='<?= $idioma['ato_punitivo']; ?>' id='input-ato_punitivo' maxlength='100' class='input-ato_punitivo' required>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <p>O preenchimento do campo acima é importante para vincular corretamente as informações da matrícula ao sistema do DETRAN. Neste local, preencha conforme o pré-requisito <b>correspondente ao estado onde seu processo de suspensão está vinculado</b>:
                        <ul>
                          <li><b>Santa Catarina:</b> Nº do processo presente no Termo de Apreensão</li>
                          <li><b>Paraná:</b> Nº do processo</li>
                          <li><b>Rio de Janeiro:</b> Nº do RENACH emitido após a coleta da biometria no DETRAN/RJ</li>
                          <li><b>Mato Grosso:</b> Nº do RENACH emitido após a coleta da biometria no DETRAN/MT</li>
                          <li><b>Sergipe:</b> Nº do processo</li>
                          <li><b>Demais estados:</b> não há necessidade do preenchimento. </li>
                        </ul>
                      </p>
                    </div>
                  </div>
                </div>
                <div class="itemForm" id="dados-resindencial">
                  <div class="row">
                    <div class="col-md-12">
                      <h2><?= $idioma['endereco_residencial']; ?></h2>
                    </div>
                    <div class="col-md-4">
                      <div class='lince-input'>
                        <label for='cep'><?= $idioma['cep']; ?></label>
                         <input type='text' name='cep' placeholder='<?= $idioma['cep']; ?>' id='cep' maxlength='100' class='input-cep' required value="<?= $_POST['cep']; ?>">
                      </div>
                    </div>
                    <div class="col-md-8">
                      <div class='lince-input'>
                        <label for='endereco'><?= $idioma['endereco']; ?></label>
                        <input type='text' name='endereco' placeholder='<?= $idioma['endereco']; ?>' id='endereco' maxlength='100' class='input-alpha' required value="<?= $_POST['endereco']; ?>">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class='lince-input'>
                        <label for='numero'><?= $idioma['numero']; ?></label>
                        <input type='text' name='numero' placeholder='<?= $idioma['numero']; ?>' id='numero' maxlength='100' class='input-numeric' required value="<?= $_POST['numero']; ?>">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class='lince-input'>
                        <label for='bairro'><?= $idioma['bairro']; ?></label>
                        <input type='text' name='bairro' placeholder='<?= $idioma['bairro']; ?>' id='bairro' maxlength='100' class='input-alpha' required value="<?= $_POST['bairro']; ?>">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class='lince-input'>
                        <label for='complemento'><?= $idioma['complemento']; ?></label>
                        <input type='text' name='complemento' placeholder='<?= $idioma['complemento']; ?>' id='complemento' maxlength='100' class='input-alpha' value="<?= $_POST['complemento']; ?>">
                      </div>
                    </div>
                    <!-- <div class="col-md-5">
                      <div class='lince-input' >
                        <label for='input-pais'><?= $idioma['pais']; ?></label>
                        <select id="input-pais" name="idpais" class="input-select">
                          <option value="" class="select-placeholder"><?= $idioma['pais']; ?></option>
                          <?php
                          foreach ($paises as $ind => $valor) {
                            ?>
                            <option value="<?= $valor['idpais']; ?>" <?= ($valor['idpais'] == $_POST['idpais']) ? 'selected' : ''; ?> >
                              <?= $valor['nome']; ?>
                            </option>
                            <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div> -->
                    <div class="col-md-7">
                      <div class='lince-input'>
                        <label for='idestado'><?= $idioma['estado']; ?></label>
                        <select id="idestado" name="idestado" class="input-select">
                          <option value="" class="select-placeholder"><?= $idioma['estado']; ?></option>
                          <?php 
                          foreach ($estados as $ind => $valor) {
                            ?>
                            <option value="<?= $valor['idestado']; ?>" <?= ($valor['idestado'] == $_POST['idestado']) ? 'selected' : ''; ?> >
                              <?= $valor['nome']; ?>
                            </option>
                            <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-5">
                      <div class='lince-input'>
                        <label for='idcidade'><?= $idioma['cidade']; ?></label>
                        <select id="idcidade" name="idcidade" class="input-select" placeholder="<?= $idioma['cidade']; ?>">
                          <option value="" class="select-placeholder"><?= $idioma['cidade']; ?></option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
               
                <?php
                  if ($ofertaCurso['possui_financeiro'] == 'S') {
                ?>
                <div class="itemForm" id="formas-pagamento">
                  <h2><?= $idioma['formas_pagamento']; ?></h2>
                  <div id="accordion">
                    <div class="itemAccordion">
                      <a class="btAccordion collapsed" data-toggle="collapse" href="#cartao" role="button" aria-expanded="false" aria-controls="cartao">
                        <span><?= $idioma['cartao_credito']; ?></span>
                        <img src="/assets/loja/img/cartoes.png" title="Cartões" alt="cartoes">
                      </a>
                      <div id="cartao" class="collapse conteudo" data-parent="#accordion">
                        <div class="card-wrapper"></div>
                        <div class="row">
                          <div class="col-md-6">
                            <div class='lince-input'>
                              <label for="number"><?= $idioma['numero_cartao']; ?></label>
                              <input placeholder="XXXX  XXXX  XXXX  XXXX" type="text" name="number" value="<?= $_POST['number']; ?>">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class='lince-input'>
                              <label for="name"><?= $idioma['nome_impresso']; ?></label>
                              <input placeholder="Nome Impresso" type="text" maxlength='25' name="name" value="<?= $_POST['name']; ?>" class="input-alpha">
                            </div>
                        </div>
                        <div class="col-md-3">
                          <div class='lince-input'>
                            <label for="expiry"><?= $idioma['validade']; ?></label>
                            <input placeholder="MM/AA" type="text" name="expiry" maxlength="7" class="secondRow input-card-validate" value="<?= $_POST['expiry']; ?>">
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class='lince-input'>
                            <label for="cvv"><?= $idioma['cvv']; ?></label>
                            <input placeholder="XXX" type="text" maxlength='3' name="cvv" class="secondRow" value="<?= $_POST['cvv']; ?>">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class='lince-input'>
                            <label for='input-parcela'><?= $idioma['parcelamento']; ?></label>
                            <select name="input_parcela" id="input-parcela" class="select">
                              <option value="1">1 Parcela</option>
                              <?php for ($i=1; $i < $curso['parcelas']; $i++) { ?>
                                <option value="<?=$i + 1; ?>"><?=$i + 1;?> Parcelas</option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12 text-center">
                          <button id="pag_cartao" type="button" class="matricular pagamento"><?= $idioma['btn_pagamento']; ?></button>
                          <!-- <p><?= $idioma['confirmacao_termos_cartao']; ?> <a href="#" target="_blank"><?= $idioma['termos_contrato']; ?></a>.</p> -->
                          <p><i class="fas fa-lock"></i> <?= $idioma['pagina_titulo']; ?></p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="itemAccordion">
                    <a class="btAccordion collapsed" data-toggle="collapse" href="#boleto" role="button" aria-expanded="false" aria-controls="boleto">
                      <span><?= $idioma['boleto']; ?></span>
                      <img src="/assets/loja/img/boleto.png" title="Boleto" alt="boleto">
                    </a>
                    <div id="boleto" class="collapse conteudo" data-parent="#accordion">
                      <div class="row">
                        <div class="col-md-12 text-center">
                          <button id="gerar_boleto" type="button" class="matricular pagamento"><?= $idioma['btn_boleto']; ?></button>
                          <!-- <p><?= $idioma['confirmacao_termos_boleto']; ?> <a href="#" target="_blank"><?= $idioma['termos_contrato']; ?></a>.</p> -->
                          <p><i class="fas fa-lock"></i> <?= $idioma['pagina_titulo']; ?></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php
                }
              ?>
              <div id="padrao" class="itemForm" <?= ($ofertaCurso['possui_financeiro'] == 'S') ? 'style="display: none"' : ''; ?>>
                <div class="row">
                  <div class="col-md-12 text-center">
                    <button type="button" class="matricular"><?= $idioma['btn_matricula']; ?></button>
                    <p><?= $idioma['confirmacao_termos']; ?> <a href="#" target="_blank"><?= $idioma['termos_contrato']; ?></a>.</p>
                    <p><i class="fas fa-lock"></i> <?= $idioma['pagina_titulo']; ?></p>
                  </div>
                </div>
              </div>

            </div>
          </div>

      <div class="col-lg-4 col-md-12 sidebarForm sideBar">
      <div class="conteudoSideBar">
        <?php if (!empty($ofertaCurso['arquivo_servidor'])): ?>
        <div class="foto" style="background: url(/api/get/imagens/cursos_imagem_exibicao/370/150/<?= $curso['imagem_exibicao_servidor']; ?>) no-repeat center center;"></div>
        <?php endif; ?>
        <div class="info">
        <div class="itemForm tituloXs">
          <h2><?= $ofertaCurso['nome']; ?></h2>
        </div>
        <div class="itemForm <?= ($ofertaCurso['possui_financeiro'] == 'N') ? 'last' : ''; ?>">
          <div class="d-none d-lg-block">
          <div class="valor">
          <?php
            if ($ofertaCurso['possui_financeiro'] == 'N') {
              echo '<span><strike>' . $idioma['subtotal'] . '</strike></span>' .
                    '<span><strike>R$ ' . number_format($ofertaCurso['valor'], 2, ',', '.') . '</strike></span>';
            } else {
              echo '<span>' . $idioma['subtotal'] . '</span>' .
                    '<span>R$ ' . number_format($curso['avista'], 2, ',', '.') . '</span>';
            }
          ?>
          </div>
          <!-- <div class="valor dados-desconto" style="display: none;">
            <span><?= $idioma['desconto']; ?></span>
            <span id="valor-desconto">R$ 0.00</span>
          </div> -->
          <div class="valor">
          <?php
            if ($ofertaCurso['possui_financeiro'] == 'N') {
          ?>
            <span class="total"><?= $idioma['gratuito']; ?></span>
          <?php
            } else {
          ?>
            <span><b><?= $idioma['total']; ?></b></span>
            <span class="total">
            <?php
              if ($curso['parcelas'] > 1) {
                  echo '<sub>' . $curso['parcelas'] . 'x</sub> R$ ' . number_format(($curso['aprazo'] / $curso['parcelas']), 2, ',', '.');
              } else {
                  echo '<sub>1x</sub> R$ ' . number_format($curso['avista'], 2, ',', '.');
              }
            ?>
            </span>
          <?php
            }
          ?>
          </div>
        </div>
      </div>
      <?php
        // if ($ofertaCurso['possui_financeiro'] == 'S') {
      ?>
        <!-- <div class="itemForm last">
          <a href="javascript:void(0)" data-toggle="collapse" class="btDesconto" data-target="#desconto" title="<?= $idioma['tenho_cupom']; ?>"><?= $idioma['tenho_cupom']; ?></a><br>
          <div id="desconto" class="collapse">
            <div class='lince-input'>
              <label for='input-cupom'></label>
              <div id="cupom_alerta" class="hide text-center text-uppercase alert alert-danger" role="alert"></div>
              <input type='text' name='cupom' placeholder='Cupom de Desconto' id='input-cupom' maxlength='20' class='input-alpha text-center'>
              <br><br>
              <button type="button" class="validar-cupom btn-small btn-block"
                data-id="<?= $ofertaCurso['idoferta']; ?>"
                data-polo="<?= $ofertaCurso['idpolo']; ?>"
                data-cidade="<?= $ofertaCurso['idcidade']; ?>">
                <?= $idioma['validar_cupom'] ?>
              </button>
            </div>
          </div>
        </div> -->
        <?php
          // }
        ?>
        </div>
        <a href="mailto:<?= $config["emailSistema"]; ?>" class="ajuda" title="<?= $idioma['ajuda'] . $config["emailSistema"]; ?>"><?= $idioma['ajuda'] . $config["emailSistema"]; ?></a>
        <!-- <div class="pagseguro text-center mt-5">
          <img src="/especifico/img/logo_pagseguro200x41.png" title="PagSeguro UOL" alt="marca-pagseguro-uol" class='img-fluid'>
        </div> -->
      </div>
      </div>
    </div>
    </div>
  </form>
  </div>

    <footer>
      <div class="container">
        <div class="row apoio">
          <div class="col-sm-9 copyright">
            <?= $idioma['copyrigth'] .  $config["tituloEmpresa"]; ?>
          </div>
          <div class="col-sm-3 marcaAlfama">
            <a href="https://alfamaoraculo.com.br" class="logo" target="_blank">
            <img src="/assets/img/logo_pequena.png" title="Marca <?= $config["tituloEmpresa"] ?>" alt="marca-oraculo" class='img-fluid'>
            </a>
          </div>
        </div>
      </div>
    </footer>


    <form action="/<?= $url[0] . '/' . $ofertaCurso['idoferta']; ?>" method="post" name="formProcessa" id="formProcessa">
    </form>

    <?php require_once('matricula.js.php'); ?>
    <?= $config['loja']['script_google_analytics'] ? $config['loja']['script_google_analytics'] : '' ;?>
    <?= $config['scriptFacebook'] ? $config['scriptFacebook'] : '' ;?>

  </body>
</html>
