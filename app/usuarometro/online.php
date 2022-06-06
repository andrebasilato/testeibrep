<html>

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta charset="utf-8">
    <script src="https://cdn.firebase.com/js/client/1.0.15/firebase.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
</head>

<body>
    <div id="presenceDiv" class="l-demo-container example-base"></div>
    <script>
        var listRef = new Firebase("https://oraculoibrep.firebaseio.com/usuarometro/");
        var userRef = listRef.push();

        var presenceRef = new Firebase("https://oraculoibrep.firebaseio.com/.info/connected");
        presenceRef.on("value", function(snap) {
            if (snap.val()) {
                //userRef.set(true);
                userRef.set({
                    nome: 'Manzano',
                    polo: 'Polo'
                });
                userRef.onDisconnect().remove();                
            }
        });

        listRef.on("value", function(snap) {
            console.log("# of online users = " + snap.numChildren());
        });

        function getMessageId(snapshot) {
            return snapshot.name().replace(/[^a-z0-9\-\_]/gi, '');
        }

         // Update our GUI to show someone"s online status.
        listRef.on("child_added", function(snapshot) {
            var user = snapshot.val();

            $("<div/>")
                .attr("id", getMessageId(snapshot))
                .text(user.nome + " do polo " + user.polo + " (ID : " + getMessageId(snapshot) + "  )")
                .appendTo("#presenceDiv");
        });

         // Update our GUI to remove the status of a user who has left.
        listRef.on("child_removed", function(snapshot) {
            $("#presenceDiv").children("#" + getMessageId(snapshot))
                .remove();
        });
    </script>
</body>

</html>
