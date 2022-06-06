/*
Aplicação:

<script src="https://cdn.firebase.com/js/client/1.0.15/firebase.js"></script>
<script>
    var usuarometroNome = '<?= $informacoes["nome"]; ?>';
    var usuarometroId = '<?= $informacoes["idusuario"]; ?>';
    var usuarometroEndereco = '<?= $informacoes["nome"]; ?>';
</script>
<script type="text/javascript" src="/usuarometro/js/usuarometro.gestor.js"></script>
*/

var usuarometroAlunos = new Firebase("https://oraculoibrep.firebaseio.com/usuarometro/gestores");
var userRef = usuarometroAlunos.push();

var presenceRef = new Firebase("https://oraculoibrep.firebaseio.com/.info/connected");
presenceRef.on("value", function(snap) {
    if (snap.val()) {
        userRef.set({
            nome: usuarometroNome,
            id: usuarometroId,
            pagina: usuarometroEndereco
        });
        userRef.onDisconnect().remove();
    }
});
/*
usuarometroAlunos.on("value", function(snap) {
    console.log("# of online users = " + snap.numChildren());
});
*/