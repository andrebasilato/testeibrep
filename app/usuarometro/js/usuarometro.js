var usuarometro = new Firebase("https://oraculoibrep.firebaseio.com/usuarometro/");
var userRef = usuarometro.push();

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

usuarometro.on("value", function(snap) {
    console.log("# of online users = " + snap.numChildren());
});

function getMessageId(snapshot) {
    return snapshot.name().replace(/[^a-z0-9\-\_]/gi, '');
}

 // Update our GUI to show someone"s online status.
usuarometro.on("child_added", function(snapshot) {
    var user = snapshot.val();

    $("<div/>")
        .attr("id", getMessageId(snapshot))
        .text(user.nome + " do CFC " + user.polo + " (ID : " + getMessageId(snapshot) + "  )")
        .appendTo("#presenceDiv");
});

 // Update our GUI to remove the status of a user who has left.
usuarometro.on("child_removed", function(snapshot) {
    $("#presenceDiv").children("#" + getMessageId(snapshot))
        .remove();
});