<html>

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta charset="utf-8">
    <script src="https://cdn.firebase.com/js/client/1.0.15/firebase.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
</head>

<body>
<script>
var usuarometro = new Firebase("https://oraculoibrep.firebaseio.com/usuarometro/alunos");
var userRef = usuarometro.push();
var presenceRef = new Firebase("https://oraculoibrep.firebaseio.com/.info/connected");
usuarometro.on("value", function(snap) {
    console.log("Alunos online = " + snap.numChildren());
});
</script>


<script>
var usuarometro = new Firebase("https://oraculoibrep.firebaseio.com/usuarometro/gestores");
var userRef = usuarometro.push();
var presenceRef = new Firebase("https://oraculoibrep.firebaseio.com/.info/connected");
usuarometro.on("value", function(snap) {
    console.log("Gestores online = " + snap.numChildren());
});
</script>

</body>

</html>
