<?php
require_once  'includes/general_settings.php';
require_once BASE_PATH . 'includes/functions.php';
require_once BASE_PATH . 'public/header.php';
require_once BASE_PATH . 'public/body.php';
require_once BASE_PATH . 'public/footer.php';
if($_GET['lang']){
    $_SESSION['lang'] = $_GET['lang'];
}else{
    $_SESSION['lang'] = 'es';
}
$secciones = new Secciones();
$seccion = $secciones->getOne(6);
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="es-ES">
<!--<![endif]-->
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<?=getHeadLinks(6);?>
<link rel="canonical" href=""/>
<meta name="viewport" content="width=device-width">
<link rel="stylesheet" href="<?=BASE_URL?>css/main.css">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,300,300italic,400italic,600italic,700,700italic' rel='stylesheet' type='text/css'>
</head>
<body>
<header>
  <div class="center">
    <h1 class="logo"><a title="" href="<?=BASE_URL?><?=$_SESSION['lang']?>">Ous de Calaf</a></h1>
    <!--logo-->
    <ul class="languages">
      <?php
        if($_SESSION['lang'] == 'es'){
        $output = '<li class="active"><a title="Castellano" href="'.BASE_URL.'es/blog">Español</a></li>
               <li><a title="" href="'.BASE_URL.'ca/blog">Català</a></li>';
        }else{
            $output = '<li><a title="" href="'.BASE_URL.'es/blog">Español</a></li>
                   <li class="active"><a title="'.BASE_URL.'ca/blog" href="">Català</a></li>';
        }
        echo $output;
      ?>
    </ul>
    <nav>
      <ul>
        <?=getSections(6);?>
      </ul>
    </nav>
  </div>
  <!--center--> 
</header>
<div class="banner">
  <div class="center">
    <ul class="bxslider">
      <?php
        $output = getSlider($seccion['idBanner']);
        echo $output;
      ?>
    </ul>
  </div>
  <!--center--> 
</div>
<!--banner-->
<section>
  <div class="center">
    <article class="blog">
        <?php
            $noticias = new Noticias();
            $output = '<h1>'.traducir($seccion['titulo1']).'</h1>
                            <ul>';
            foreach((array)$noticias->getAll() as $noticia){
                $output .= '<li><figure><img title="'.traducir($noticia['desc_img']).'" src="'.BASE_URL.'userfiles/noticias/main/'.$noticia['noticia_img'].'" alt="'.traducir($noticia['alt_img']).'" width="215" height="138"></figure>
                            <h3><a title="" href="'.BASE_URL.$_SESSION['lang'].'/blog/'.$noticia['idNoticia'].'">'.traducir($noticia['nombre']).'</a></h3>
                            <p>'.traducir($noticia['texto']).'</p>
                          </li><div class="line"></div>';
            }
            $output .= '</ul>';
            echo $output;
          ?>
    </article>
    <!--blog--> 
    
  </div>
  <!--center--> 
</section>
<footer>
<?=getFooterSections();?>
</footer>
<!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]--> 
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script> 
<script src="<?=BASE_URL?>js/jquery.bxslider.min.js"></script> 
<script src="<?=BASE_URL?>js/jquery.colorbox-min.js"></script> 
<script src="<?=BASE_URL?>js/jquery.colorbox.js"></script> 
<script>
$('.bxslider').bxSlider({
  mode: 'fade',
  captions: true
});
</script> 
<script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src='//www.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
            $(".cBox").colorbox({width:"60%", height:"60%", maxwidth:"60%",maxheight:"60%"});
        </script>
</body>
</html>