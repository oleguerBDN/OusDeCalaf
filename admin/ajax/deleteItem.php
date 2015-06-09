<?php
include '../../includes/general_settings.php';
$class = new $_POST['table']();
if($_POST['table'] == 'banners'){
    $where = 'idBanner_fk = '.$_POST['id'].'';
    $bannerimgs = new Banner_imgs();
    foreach($bannerimgs->getAll('*',$where) as $bimg){
        $bannerimgs->deleteImg($bimg['idImgBanner'],'banner_img');
    }
}elseif($_POST['table'] == 'galerias'){
    $where = 'idGaleria_fk = '.$_POST['id'].'';
    $galeriaimgs = new Galeria_imgs();
    foreach($galeriaimgs->getAll('*',$where) as $bimg){
        $galeriaimgs->deleteImg($bimg['idImgGaleria'],'galeria_img');
    }
}
$class->deleteItem($_POST['id']);


?>
