function Enviar(){
    for (i = 0; i < $('form').length; i++) {
        if($('form')[i].id == 'contacto'){
            var datos = new FormData($('form')[i]);
        }
    }
    $.ajax({
        type: "POST",
        url: base_url+"ajax/enviar.php",
        data:  datos,
        contentType: false,
        async: false,
        processData: false,
        cache: false,
        success: function(msg) {
//            alert(msg);
            if(msg == 1){
                $('#msj').html('Faltan datos');
                $('#msj').removeClass('hidden');
            } else if(msg == 2){
                $('#msj').html('Email enviado');
                $('#msj').removeClass('hidden');
            }
            else if(msg == 3){
                $('#msj').html('Email incorrecto');
                $('#msj').removeClass('hidden');
            }
            else if(msg == 4){
                $('#msj').html('Error durante el envio');
                $('#msj').removeClass('hidden');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert('Error ' + xhr.status + ': ' + thrownError);
        }
    });
}


