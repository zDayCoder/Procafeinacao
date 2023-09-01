$.ajax({
    type: 'POST',
    url: 'index.php',
    /*data: {
        user_id: userId,
        action: 'desactiveuser'
    },
    success: function() {
        //Recarrega os usuarios
        loadUsersTable('user_id', 'asc');

    },
    error: function(xhr, status, error) {
        console.log('Erro na requisição AJAX: ' + status + ' ' + error);
    }*/
    success: function() {
        //Recarrega os usuarios
        // alert("sim");

    }
});