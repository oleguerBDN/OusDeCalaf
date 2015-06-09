<?php
include '../../includes/general_settings.php';
include BASE_PATH.'admin/functions_display.php';
$c = $_POST['id'];
$c++;
$id = $_POST['id'];
$sql = 'SELECT idBanner_fk FROM banner_imgs WHERE idImgBanner = '.$id.'';
$bannerimgs = new Banner_imgs();
$idBanner = $bannerimgs->getValue($sql);
$sql = 'DELETE FROM banner_imgs WHERE idImgBanner = '.$id.'';
$bannerimgs->deleteImg($id,'banner_img');
$bannerimgs->Query($sql);
?>
