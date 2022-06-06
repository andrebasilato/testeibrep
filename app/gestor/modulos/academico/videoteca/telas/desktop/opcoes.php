<section id="global">
  <div class="page-header"><h1><?php echo $idioma["opcoes"]; ?> &nbsp;<small><?php echo $idioma["opcoes_subtitulo"]; ?></small></h1></div>
  <ul class="breadcrumb">
    <li><?php echo $idioma["usuario_selecionado"]; ?></li>
    <li class="active"><strong><?php echo $linha["nome"]; ?></strong></li>
  </ul>
  <ul class="nav nav-tabs nav-stacked">
    <li>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/cadastrar"> <i class="icon-film"></i> <? echo $idioma["cadastrar"]; ?></a>
    </li>
    <li>
          <a href="#myModal" role="button" data-toggle="modal"> <i class="icon-facetime-video"></i> <? echo $idioma["youtube"]; ?></a>
    </li>
    <li>
          <a href="#myModalVimeo" role="button" data-toggle="modal"> <i class="icon-facetime-video"></i> <? echo $idioma["vimeo"]; ?></a>
    </li>
   <!--  <li>
        <a href="#myModalForOther" role="button" data-toggle="modal"> <i class="icon-arrow-down"></i> <? echo $idioma["importar"]; ?></a>
    </li> -->
  </ul>
</section>

     <!-- Modal for youtube video -->
    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

        <h3 id="myModalLabel">Youtube</h3>
      </div>

      <div class="modal-body">
        <!-- <form action="/index.php" method="post"> -->
            <input type="hidden" name="youtube" value="true" />
            <label class="control-label" for="youtube">
                <strong>* Link do vídeo:</strong>
            </label>

            <div class="controls">
                <input class="span6" id="youtube" name="youtube" placeholder="http://youtube.com/v/xxxxxxxx" value="" type="text" />
            </div>

            <label class="control-label" for="youtube">
                <strong>* Pasta:</strong>
            </label>

            <div class="controls">
                <select class="span6" id="pasta">
                    <option value="none"> -- SELECIONE UMA PASTA -- </option>'
                    <?php
                    $sql = mysql_query('SELECT * FROM videotecas_pastas WHERE ativo ="S"');
                    while ($row = mysql_fetch_object($sql)) {
                        echo '<option value="'.$row->idpasta.'">'.$row->nome.'</option>';
                    }
                    ?>
                </select>
            </div>
    </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            <button class="btn btn-primary" id="send">Cadastrar</button>
    <!-- </form> -->
    </div>
    </div>
    <script type="text/javascript">
    $('#send').click(function(){

        var videoUri = document.getElementById('youtube').value;

        console.log('/<?= $url[0];?>/<?= $url[1];?>/<?= $url[2];?>');
        if (! videoUri) {
            window.alert('Por favor! Preencha o campo com a url do vídeo.');
            return false;
        }

        if ('none' == document.getElementById('pasta').value) {
            window.alert('Selecione uma pasta para o vídeo.');
            return false;
        }

        $.post('/<?= $url[0];?>/<?= $url[1];?>/<?= $url[2];?>', {
            type: "youtube",
            url: videoUri,
            pasta: document.getElementById('pasta').value
        }, function (result){
            window.alert(result);
            document.location.href = '/<?= $url[0];?>/<?= $url[1];?>/<?= $url[2];?>';
            return false;
        });


    });
    </script>




<!-- Modal for external video-->
    <div id="myModalForOther" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

        <h3 id="myModalLabel2">Fonte Externa</h3>
      </div>

      <div class="modal-body">
        <form action="/index.php" method="post">
            <input type="hidden" name="htmlcode" value="true" />
            <label class="control-label" for="htmlcode">
                <strong>* Url do vídeo:</strong>
            </label>

            <div class="controls">
                <input type="text" class="span6" id="htmlcode" name="htmlcode" placeholder="Url do vídeo" value="" type="text" />
            </div>

            <label class="control-label" for="pasta2">
                <strong>* Pasta:</strong>
            </label>

            <div class="controls">
                <select class="span6" id="pasta2">
                    <?php
                    $sql = mysql_query('SELECT * FROM videotecas_pastas WHERE ativo ="S"');
                    while ($row = mysql_fetch_object($sql)) {
                        echo '<option value="'.$row->idpasta.'">'.$row->nome.'</option>';
                    }
                    ?>
                </select>
            </div>
    </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            <input type="submit" class="btn btn-primary" id="sendbody" value="Cadastrar" />
    </form>
    </div>
    </div>

    <script type="text/javascript">
    $('#sendbody').click(function(){

        var htmlCode = document.getElementById('htmlcode').value;

        if (! htmlCode) {
            window.alert('Por favor! Preencha o campo com endereço do vídeo.');
            return false;
        }

        $.post(document.location.href, {
            type: "htmlcode",
            url: htmlCode,
            pasta: document.getElementById('pasta').value
        }, function (result){
            window.alert(result);
            document.location.reload();
        });


    });
    </script>
    
    <!-- Modal for vimeo video -->
    <div id="myModalVimeo" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

        <h3 id="myModalLabel">Vimeo</h3>
      </div>

      <div class="modal-body">
        <!-- <form action="/index.php" method="post"> -->
            <input type="hidden" name="vimeo" value="true" />
            
            <label class="control-label" for="vimeo">
                <strong>* Link do vídeo:</strong>
            </label>

            <div class="controls">
                <input class="span6" id="vimeo" name="vimeo" placeholder="xxxxxxxxxxx" value="" type="text" />
            </div>

            <label class="control-label" for="vimeo">
                <strong>* Pasta:</strong>
            </label>

            <div class="controls">
                <select class="span6" id="pastaVimeo">
                    <option value="none"> -- SELECIONE UMA PASTA -- </option>'
                    <?php
                    $sql = mysql_query('SELECT * FROM videotecas_pastas WHERE ativo ="S"');
                    while ($row = mysql_fetch_object($sql)) {
                        echo '<option value="'.$row->idpasta.'">'.$row->nome.'</option>';
                    }
                    ?>
                </select>
            </div>
    </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            <button class="btn btn-primary" id="sendVimeo">Cadastrar</button>
    <!-- </form> -->
    </div>
    </div>
    <script type="text/javascript">
    $('#sendVimeo').click(function(){

        var videoUri = document.getElementById('vimeo').value;

        console.log('/<?= $url[0];?>/<?= $url[1];?>/<?= $url[2];?>');
        if (! videoUri) {
            window.alert('Por favor! Preencha o campo com o código do vídeo.');
            return false;
        }

        if ('none' == document.getElementById('pastaVimeo').value) {
            window.alert('Selecione uma pasta para o vídeo.');
            return false;
        }

        $.post('/<?= $url[0];?>/<?= $url[1];?>/<?= $url[2];?>', {
            type: "vimeo",
            url: videoUri,
            pasta: document.getElementById('pastaVimeo').value
        }, function (result){
            window.alert(result);
            document.location.href = '/<?= $url[0];?>/<?= $url[1];?>/<?= $url[2];?>';
            return false;
        });


    });
    </script>