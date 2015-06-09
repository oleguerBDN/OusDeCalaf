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
$seccion = $secciones->getOne(7);
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
<?=getHeadLinks(7);?>
<link rel="canonical" href=""/>
<meta name="viewport" content="width=device-width">
<link rel="stylesheet" href="<?=BASE_URL?>css/main.css">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,300,300italic,400italic,600italic,700,700italic' rel='stylesheet' type='text/css'>
<script type="text/javascript">
    var base_url = "<?=BASE_URL?>";
</script>
</head>
<body>
<header>
  <div class="center">
    <h1 class="logo"><a title="" href="<?=BASE_URL?><?=$_SESSION['lang']?>">Ous de Calaf</a></h1>
    <!--logo-->
    <ul class="languages">
      <?php
        if($_SESSION['lang'] == 'es'){
        $output = '<li class="active"><a title="Castellano" href="'.BASE_URL.'es/contacto">Español</a></li>
               <li><a title="" href="'.BASE_URL.'ca/contacte">Català</a></li>';
        }else{
            $output = '<li><a title="" href="'.BASE_URL.'es/contacto">Español</a></li>
                   <li class="active"><a title="'.BASE_URL.'ca/contacte" href="">Català</a></li>';
        }
        echo $output;
      ?>
    </ul>
    <nav>
      <ul>
        <?=getSections(7);?>
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
    <article class="contact-form">
      <h1><?=traducir($seccion['titulo1'])?></h1>
      <p><?=traducir($seccion['texto1'])?></p>
      <form id="contacto" name="contacto" method="post" enctype="multipart/form-data">
        <table>
          <tr>
            <td><input name="nombre" type="text" placeholder="<?=lang('Nombre*')?>"></td>
            <td><input type="text" name="apellidos" placeholder="<?=lang('Apellidos')?>"></td>
          </tr>
          <tr>
            <td><input name="email" type="email" placeholder="<?=lang('Correo electrónico*')?>"></td>
            <td><input type="text" name="telf" placeholder="<?=lang('Telefono')?>"></td>
          </tr>
          <tr>
            <td colspan="2"><textarea name="consulta" placeholder="Consulta..."></textarea></td>
            </td>
          </tr>
          <tr>
            <td><button class="button" onClick="Enviar();"><?=lang('Enviar consulta')?></button></td>
            <td class="hidden" id="msj"></td>
          </tr>
        </table>
          
      </form>
    </article>
    <!--talk-->
    <article class="location">
      <h3><?=traducir($seccion['titulo2'])?></h3>
      <p><?=traducir($seccion['texto2'])?></p>
      <img title="<?=traducir($seccion['desc_img'])?>" alt="<?=traducir($seccion['alt_img2'])?>" src="<?=BASE_URL?>userfiles/main/<?=$seccion['foto2']?>" width="375" height="265">
      <ul>
        <li class="local"><?=$seccion['direccion1']?></li>
        <li class="hour"><?=traducir($seccion['horario1'])?></li>
		<li class="telf"><?=$seccion['telefonos1']?></li>
        <li class="local"><?=$seccion['direccion2']?></li>
        <li class="hour"><?=traducir($seccion['horario2'])?></li>
        <li class="telf"><?=$seccion['telefonos2']?></li>
        <li class="mail"><a href="mailto:<?=$seccion['email1']?>"><?=$seccion['email1']?></a> / <a href="mailto:<?=$seccion['email2']?>"><?=$seccion['email2']?></a></li>
      </ul>
    </article>
    <!--location--> 
    
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
<script src="<?=BASE_URL?>js/functions.js"></script> 
<script>
$('.bxslider').bxSlider({
  mode: 'fade',
  captions: true
});
</script> 
<script>
            $( "button" ).click(function( event ) {
            event.preventDefault();
            });
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src='//www.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
</body>
</html>
