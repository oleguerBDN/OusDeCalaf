<?php
require_once '../includes/general_settings.php';

//print_r($_POST);
if ($_POST['nombre'] != '' && $_POST['email'] != '' && $_POST['apellidos'] != '' && $_POST['telf'] != '' && $_POST['consulta'] != '') 
{
    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $html="Datos del contactante: <br>Nombre: ". $_POST['nombre']."<br>Apellidos: ".$_POST['apellidos']."
                            <br>Correo electronico: ".$_POST['email']."<br>Teléfono: ".$_POST['telf']."<br>Consulta: ".$_POST['consulta'];
            if(sendEmail(TO,$_POST['nombre'],SUBJECT,$html))
                echo 2;
            else
                echo 4;
    }
    else{
        echo 3;
    }
}else{
    echo 1;
}


?>
