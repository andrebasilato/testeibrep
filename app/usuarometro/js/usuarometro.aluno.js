/*

Aplicação: 

<script src="https://cdn.firebase.com/js/client/1.0.15/firebase.js"></script>
<script>
    var usuarometroNome = '<?= ($informacoes["pessoa"]["nome"]) ? $informacoes["pessoa"]["nome"] : $informacoes["nome"]; ?>';
    var usuarometroPolo = '<?php echo $informacoes["nomePolo"]; ?>';
    var usuarometroId = '<?php echo $informacoes["pessoa"]["idpessoa"]; ?>';
    var usuarometroPagina = '<?php echo $_SERVER['REQUEST_URI']; ?>';
</script>
<script type="text/javascript" src="/usuarometro/js/usuarometro.aluno.js"></script>

*/


var usuarometroAlunos = new Firebase("https://oraculoibrep.firebaseio.com/usuarometro/alunos/baseTeste");
var userRef = usuarometroAlunos.push();

var presenceRef = new Firebase("https://oraculoibrep.firebaseio.com/.info/connected");
presenceRef.on("value", function(snap) {
    if (snap.val()) {
        userRef.set({
            id: usuarometroId,
            nome: usuarometroNome,
            polo: usuarometroPolo,
            pagina: usuarometroPagina
        });
        userRef.onDisconnect().remove();
    }
});


/*
usuarometroAlunos.on("value", function(snap) {
    console.log("# of online users = " + snap.numChildren());
});
*/