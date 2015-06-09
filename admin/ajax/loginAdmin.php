<?php
require_once '../../includes/general_settings.php';

$usuarios = new Usuarios();
$where = 'user="'.$_POST['user'].'" AND password="'.$_POST['pass'].'"';
$val = $usuarios->getAll('*',$where);
if(!$_POST['user']){
    if(!$_POST['pass']){
        echo "4";
    }else{
      echo "1";  
    }
}
else{
    if($_POST['user']){
        if(!$_POST['pass']){
            echo "2";
        }
        else {
            if ($val) {
                       echo "ok";
                       $_SESSION['usuario']['id'] = $val[0]['idUser'];
                       $_SESSION['usuario']['user'] = $_POST['user'];
                       $_SESSION['usuario']['pass'] = $_POST['pass'];
                   } else {
                       echo "3";
                   }
        }
    }
}



//echo $admin_users->adminLogin($_GET['username'], $_GET['password']);
