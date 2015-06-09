<?php

function menu() {
    $langs = new Idiomas();
    $rs = $langs->getAll();
    $output = '<div class="navbar">
    <div class="navbar-inner">
        <div class="container-fluid">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="home.php"><span>';
    if ($_SESSION['admin']['session_type'] == 'client') {
        $output .= lang('titulo-clientes');
    } else {
        $output .= lang('titulo-administracion');
    }
    $output .= '</span></a>

            <div class="nav-no-collapse header-nav">
                <ul class="nav pull-right">

                    <!-- start: User Dropdown -->
                    <li class="dropdown">
                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="halflings-icon white user"></i>  ';
    if ($_SESSION['admin']['session_type'] == 'client') {
        $output .= $_SESSION['admin']['client_name'];
    } else {
        $output .= $_SESSION['admin']['s_usuario'];
    }
    $output .= ' <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">';
    if ($_SESSION['admin']['session_type'] == 'client') {
        $output .= '<li><a href="' . BASE_URL . 'clientes/profile.php"><i class="halflings-icon white user"></i> ' . lang('profile') . '</a></li>';
    } else {
        $output .= '<li><a href="' . BASE_URL . 'admin/mensajes.php"><i class="halflings-icon white envelope"></i> ' . lang('messages') . '</a></li>';
    }
    $output.= '<li><a href="#" onclick="logout(\'' . BASE_URL . '\');"><i class="halflings-icon white off"></i>' . lang('logout') . '</a></li>
                        </ul>
                    </li>
                    <!-- end: User Dropdown -->
                </ul>
            </div>

        </div>
    </div>
</div>';
    return $output;
}

function menu_left() {
    if ($_SESSION['admin']['session_type'] == 'client') {
        $output = '<div id="sidebar-left" class="span1">
    <div class="nav-collapse sidebar-nav">
        <ul class="nav nav-tabs nav-stacked main-menu">
         <li><a href="profile.php"><i class="fa-icon-user"></i><span class="hidden-tablet">' . lang('profile') . '</span></a></li>
            <li><a href="list.php?list=amarres"><i class="fa-icon-hdd"></i><span class="hidden-tablet">' . lang('amarres') . '</span></a></li>      
</ul>
    </div>
</div>';
    } else {


        $output = '<div id="sidebar-left" class="span1">
    <div class="nav-collapse sidebar-nav">
        <ul class="nav nav-tabs nav-stacked main-menu">
        <li><a href="list.php?list=headers"><i class="fa-icon-user"></i><span class="hidden-tablet">' . lang('headers') . '</span></a></li>
        <li><a href="list.php?list=metas"><i class="fa-icon-user"></i><span class="hidden-tablet">' . lang('metas') . '</span></a></li>
            
            <!--<li><a href="home.php"><i class="fa-icon-bar-chart"></i><span class="hidden-tablet">' . lang('inicio') . '</span></a></li>-->	
            <li><a href="list.php?list=clientes"><i class="fa-icon-user"></i><span class="hidden-tablet">' . lang('clientes') . '</span></a></li>
            <li><a href="list.php?list=amarres"><i class="fa-icon-hdd"></i><span class="hidden-tablet">' . lang('amarres') . '</span></a></li>
            <li><a href="list.php?list=noticia"><i class="fa-icon-tags"></i><span class="hidden-tablet">' . lang('noticias') . '</span></a></li>
            <li><a href="list.php?list=puertos"><i class="fa-icon-tags"></i><span class="hidden-tablet">' . lang('puertos') . '</span></a></li>
            <li><a href="list.php?list=banners_posiciones"><i class="fa-icon-tags"></i><span class="hidden-tablet">' . lang('bannersPosiciones') . '</span></a></li>            
            <li><a href="list.php?list=banners"><i class="fa-icon-tags"></i><span class="hidden-tablet">' . lang('banners') . '</span></a></li>
            <li><a href="list.php?list=usuario"><i class="fa-icon-tags"></i><span class="hidden-tablet">' . lang('usuarios') . '</span></a></li>
            <li><a href="list.php?list=localizacion_' . LANGUAGE . '"><i class="fa-icon-tags"></i><span class="hidden-tablet">' . lang('localizacion') . '</span></a></li>
                        
<li><a href="list.php?list=idiomas"><i class="fa-icon-tags"></i><span class="hidden-tablet">' . lang('idiomas') . '</span></a></li>
            
</ul>
    </div>
</div>';
    }
    return $output;
}

function footer() {
    $output = ' <footer>
                    <p>
                        <span style="text-align:left;float:left">&copy; <a href="" target="_blank">Internet Brainstorm Solutions</a> 2013</span>
                        <span class="hidden-phone" style="text-align:right;float:right">' . lang('titulo-web') . '</span>
                    </p>

                </footer>';
    return $output;
}

?>
