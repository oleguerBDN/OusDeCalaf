<?php

require_once '../../includes/general_settings.php';
$table = $_POST['t'];
$class = new $table($_POST['itemid']);

//print_r($_POST);
//print_r($_FILES);

$ok = $class->saveItem($_POST,$_FILES);
if($_POST['banner_img']){
    $banners = new Banner_imgs();
    $fk = $ok;
    $banners->saveItem($_POST['banner_img'], $_FILES,$fk);
}elseif($_POST['galeria_img']){
    $galeria = new Galeria_imgs();
    $fk = $ok;
    $galeria->saveItem($_POST['galeria_img'], $_FILES,$fk);
  
}
//print_r($_POST);
echo $ok;
?>