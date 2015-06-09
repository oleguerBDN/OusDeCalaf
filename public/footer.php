<?php
function getFooterSections(){
    $secciones = new Secciones();
    $contacta = $secciones->getOne(8);
    $redes = $secciones->getOne(9);
    $output = '<div class="center">
    <article class="contact">
      <h4>'.traducir($contacta['titulo_seccion']).'</h4>
      <p>'.traducir($contacta['texto1']).'</p>
      <ul>
        <li>
          <p>'.traducir($contacta['titulo2']).'</p>
        </li>
        <li>
          <p>'.traducir($contacta['titulo3']).'</p>
        </li>
        <li>
          <p>'.traducir($contacta['titulo4']).'</p>
        </li>
        <li><a title="" href="'.$contacta['email1'].'">'.$contacta['email1'].'</a></li>
      </ul>
    </article>
    <!--contact-->
    <article class="last-news">
      <h4>'.lang('Últimas noticias').'</h4>
      <ul>';
    $noticias = new Noticias();
    $sql = 'SELECT * FROM noticias ORDER BY idNoticia DESC LIMIT 2';
    foreach((array)$noticias->getRecordSet($sql) as $noticia){
        $output .= '<li>
                    <p class="title"><a href="'.BASE_URL.$_SESSION['lang'].'/blog/'.$noticia['idNoticia'].'">'.traducir($noticia['nombre']).'</a></p>
                  <p>'.traducir($noticia['texto']).'</p>
                </li>';
    }
          $output .= '
      </ul>
    </article>
    <!--last-news-->
    <article class="follow-us">
      <h4>'.traducir($redes['titulo_seccion']).'</h4>
      <ul>
        <li class="twitter">
          <p><a target="_blank" title="'.$redes['twitter'].'" href="'.$redes['twitter'].'">'.lang('Síguenos en Twitter').'</a></p>
        </li>
        <li class="facebook">
          <p><a target="_blank" title="'.$redes['facebook'].'" href="'.$redes['facebook'].'">'.lang('Síguenos en Facebook').'</a></p>
        </li>
      </ul>
      <p>'.traducir($redes['texto3']).'</p>
    </article>
    <!--follow-us--> 
  </div>';
    return $output;
}

?>
