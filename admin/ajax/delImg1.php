<?php
include '../../includes/general_settings.php';
include BASE_PATH.'admin/functions_display.php';
$c = $_POST['id'];
$c++;
$id = $_POST['id'];
$sql = 'SELECT idGaleria_fk FROM galeria_imgs WHERE idImgGaleria = '.$id.'';
$galeriaimgs = new Galeria_imgs();
$idBanner = $galeriaimgs->getValue($sql);
$sql = 'DELETE FROM galeria_imgs WHERE idImgGaleria = '.$id.'';
$galeriaimgs->deleteImg($id,'galeria_img');
$galeriaimgs->Query($sql);
?>
