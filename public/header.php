<?php
function getHeadLinks($idSec){
    $secciones = new Secciones();
    $seccion = $secciones->getOne($idSec);
    $output = '<title>'.traducir($seccion['titulo']).'</title>';
    if(traducir($seccion['description'])){
        $output = ' <meta name="description" content="'.traducir($seccion['description']).'">';}
    if(traducir($seccion['keywords'])){
    $output = '<meta name="keywords" content="'.traducir($seccion['keywords']).'">';}
    return $output;
}



function getSections($idSec){
    $c=0;
    $secciones = new Secciones();
    $order = 'idSeccion ASC';
    $where = 'idSeccion < 8';
    $hrefs1 = Array('','','historia','trazabilidad','clasificacion','paradas','blog','contacto');
    $hrefs2 = Array('','','historia','tracabilitat','clasificacio','parades','blog','contacte');
    if($_SESSION['lang'] == 'ca'){
        $hrefs = $hrefs2;
    }else{
        $hrefs = $hrefs1;
    }
    foreach($secciones->getAll('*', $where, $order) as $seccion){
        $output .= '<li';
        if($seccion['idSeccion'] == $idSec && $c != 1){
            $output .= ' class="active"';
            $c = 1;
        }
        $output .= '><a title="" href="'.BASE_URL.$_SESSION['lang'].'/'.$hrefs[$seccion['idSeccion']].'">'.traducir($seccion['titulo_seccion']).'</a></li>';
    }
    return $output;
}

function getSlider($id){
    if($id){
        $bannersimg = new Banner_imgs();
        $where = 'idBanner_fk = '.$id.'';
        foreach((array)$bannersimg->getAll('*',$where) as $img){
            $output .= '<li><img alt="'.traducir($img['alt_img']).'" title="'.traducir($img['desc_img']).'" src="'.BASE_URL.'userfiles/banners_img/main/'.$img['banner_img'].'"></li>';
        }
        return $output;
    }
}
?>
