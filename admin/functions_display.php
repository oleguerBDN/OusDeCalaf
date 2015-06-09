<?php



function getInputBox($theclass, $field, $value = false, $name = false, $id = false, $label = true, $table = false) {
    if (!$name) {
        $name = $field['Field'];
    }
    if (!$id) {
        $id = $field['Field'];
    }
    if (!$table) {
        $table = $_GET['t'];
    }
    $asdf = array_search($table, $theclass->references);
    if ($asdf == $field['Field']) {
        $thefields .= '<input id="' . $id . '" name="' . $name . '" type="hidden" value="' . $value . '">';
    } elseif (in_array($field['Field'], $theclass->avoidFields)) {
        return false;
    } elseif (in_array($field['Field'], $theclass->dateFields)) {
        $thefields .= '<div class="control-group">
                            <label class="control-label" for="' . $id . '">' . langA($field['Field']) . '</label>
                            <div class="controls">
                                <input type="text" readonly="readonly" data-date-format="yyyy-mm-dd" class="input-xlarge datepicker" id="' . $id . '" name="' . $name . '" value="' . $value . '">
                            </div>
                        </div>';
    } elseif (in_array($field['Field'], $theclass->linkFields)) {
        $function = strval(array_search($field['Field'], $theclass->linkFields));
        $thefields .= '<div class="control-group">
                            <label class="control-label" for="' . $id . '">' .  langA($field['Field']) . '</label>
                            <div class="controls">
                                ' . $theclass->$function($value) . '
                            </div>
                        </div>';
    } elseif (in_array($field['Field'], $theclass->wysiwygFields)) {
        $thefields .= '<div class="control-group hidden-phone">';
        if (!in_array($field['Field'], $theclass->literalFields)) {
            $thefields .= '<label class="control-label" for="' . $id . '">' . langA($field['Field']) . '</label>';
        }

        $thefields .= '<div class="controls"';
        if (in_array($field['Field'], $theclass->literalFields)) {
            $thefields .= ' style="margin-left:0px;"';
        }
        if($_GET['t'] == 'newsletter_sent'){
            $template = new Newsletter_templates($GLOBALS['settings']['default_template']);
            $value = $template->newsletterTemplateBody;
        }
        $thefields .= '>
                                <textarea class="cleditor" id="' . $id . '" name="' . $name . '" rows="3">' . $value . '</textarea>
                            </div>
                        </div>';
    } elseif (in_array($field['Field'], $theclass->textareaFields)) {
        $thefields .= '<div class="control-group hidden-phone">';
        if (!in_array($field['Field'], $theclass->literalFields)) {
            $thefields .= '<label class="control-label" for="' . $id . '">' .  langA($field['Field']) . '</label>';
        }

        $thefields .= '<div class="controls"';
        if (in_array($field['Field'], $theclass->literalFields)) {
            $thefields .= ' style="margin-left:0px;"';
        }
        $thefields .= ' >
                                <textarea width="500px" height="250px" id="' . $id . '" name="' . $name . '" rows="3">' . $value . '</textarea>
                            </div>
                        </div>';
    } elseif (in_array($field['Field'], $theclass->booleanFields)) {
        if (in_array($field['Field'], $theclass->uneditableFields)) {
            $disabled = 'disabled="disabled"';
        } else {
            $disabled = false;
        }
        $thefields .= '<div class="control-group">
                            <label class="control-label">' .  langA($field['Field'].'LALA') . '</label>
                            <div class="controls">
                                <label class="radio">
                                    <input '.$disabled.' type="radio" name="' . $name . '" id="' . $id . '_yes" value="1" ';
        if ($value == 1) {
            $thefields .= 'checked=""';
        }
        $thefields .= ' >yes
                                </label>
                                <div style="clear:both"></div>
                                <label class="radio">
                                    <input '.$disabled.' type="radio" name="' . $name . '" id="' . $id . '_no" value="0" ';
        if ($value != 1) {
            $thefields .= 'checked=""';
        }
        $thefields .= ' >no
                                </label>
                            </div>
                        </div>';
    } elseif (in_array($field['Field'], $theclass->passwordFields)) {
        $thefields .= '<div class="control-group">
                        <label class="control-label" for="' . $id . '">' .  langA($field['Field']) . '</label>
                        <div class="controls">
                            <input class="input-xlarge focused" id="' . $id . '" name="' . $name . '" value="' . $value . '" type="password">
                        </div>
                    </div>';
    } elseif (in_array($field['Field'], $theclass->fileFields)) {
        $thefields .= '<div class="control-group" >';
        if (!in_array($field['Field'], $theclass->literalFields)) {
            $thefields .= '<label class="control-label" for="' . $id . '">' .  langA($field['Field']) . '</label>';
        }
        $thefields .= '<div class="controls"';
        if (in_array($field['Field'], $theclass->literalFields)) {
            $thefields .= ' style="margin-left:0px;"';
        }
        if (in_array($field['Field'], $theclass->uneditableFields)) {
            $disabled = 'disabled="disabled"';
        } else {
            $disabled = false;
        }
        $thefields .= '><input '.$disabled.' type="file" id="' . $id . '" name="' . $name . '">';
        if ($value) {
            if($GLOBALS['externalForm'] == TRUE){
                $extFormTable = $theclass->table;
                $extFormElementId = $GLOBALS['subelementid'];
            } else {
                $extFormTable = 'undefined';
                $extFormElementId = 'undefined';
            }
            if (in_array($field['Field'], $theclass->uneditableFields)) {
                $thefields .= '<span class="help-inline">' . 'Actual' . ': <a href="' . BASE_URL . $theclass->fileFolder . $value . '">'.$value.'</a> </span>';
            } else {
                $thefields .= '<span class="help-inline">' . 'Actual' . ': <a href="' . BASE_URL . $theclass->fileFolder . $value . '">'.$value.'</a> </span>  
                    <a onclick="deleteFile(\'' . $field['Field'] . '\',\''.$extFormTable.'\',\''.$extFormElementId.'\');" class="btn btn-danger"><i class="fa-icon-trash"></i></a>';
            }
            
            $thefields .= '<input type="hidden" id="' . $id . '_oldfile" name="' . $name . '" value="' . $value . '"/>';
        }
        $thefields .= '</div>
                        </div>';
    } elseif (in_array($field['Field'], $theclass->imageFields)) {
        $thefields .= '<div class="control-group">';
        if (!in_array($field['Field'], $theclass->literalFields)) {
            $thefields .= '<label class="control-label" for="' . $id . '">' . langA($field['Field']) . '</label>';
        }
        $thefields .= '<div class="controls"';
        if (in_array($field['Field'], $theclass->literalFields)) {
            $thefields .= ' style="margin-left:0px;"';
        }
        if (in_array($field['Field'], $theclass->uneditableFields)) {
            $disabled = 'disabled="disabled"';
        } else {
            $disabled = false;
        }
        $thefields .= '><input '.$disabled.' type="file" accept="image/jpeg, image/png, image/gif" id="' . $id . '" name="' . $name . '">';
        if ($value) {
            if($GLOBALS['externalForm'] == TRUE){
                $extFormTable = $theclass->table;
                $extFormElementId = $GLOBALS['subelementid'];
            } else {
                $extFormTable = 'undefined';
                $extFormElementId = 'undefined';
            }
            if (in_array($field['Field'], $theclass->uneditableFields)) {
                $thefields .= '<span class="help-inline">' . 'Actual' . ': <img style="max-width: 50px;min-height: 50px;" src="' . BASE_URL . $theclass->imgFolder . $value . '"/></span></a>';
            } else {
                $thefields .= '<span class="help-inline">' . 'Actual' . ': <img style="max-width: 50px;min-height: 50px;" src="' . BASE_URL . $theclass->imgFolder . $value . '"/></span>
                            <a onclick="deleteImg(\'' . $field['Field'] . '\',\''.$extFormTable.'\',\''.$extFormElementId.'\');" class="btn btn-danger">
                            <i class="fa-icon-trash"></i> 
                        </a>';
            }
            
            $thefields .= '<input type="hidden" id="' . $id . '_oldfile" name="' . $name . '" value="' . $value . '"/>';
        }

        $thefields .= '</div>
                        </div>';
    } elseif (in_array($field['Field'], $theclass->selectFields)) {
        if (in_array($field['Field'], $theclass->hiddenFields)) {
            $class = 'hidden';
        } else {
            $class = '';
        }
        if (in_array($field['Field'], $theclass->uneditableFields)) {
            $disabled = true;
        } else {
            $disabled = false;
        }
        $function = strval(array_search($field['Field'], $theclass->selectFields));
        $thefields .= '<div class="control-group ' . $class . '">
                                <label class="control-label" for="' . $id . '">' .  langA($field['Field']) . '</label>
                                <div class="controls">';
        $parentTable = $theclass->references[$field['Field']];
        if ($parentTable) {
            $addable = true;
            $onchange = 'addNew(this.value,\'' . $parentTable . '\',\'' . $theclass->table . '\',this.id)';
        }

        $thefields .= $theclass->$function($name, $value, $onchange, $id, $disabled, false, $addable);
        $thefields .= '</div>
                            </div>';
    } elseif (in_array($field['Field'], $theclass->hiddenFields)) {
        $thefields .= '<input id="' . $id . '" name="' . $name . '" type="hidden" value="' . htmlspecialchars($value) . '">';
    } elseif (in_array($field['Field'], $theclass->uneditableFields)) {
        $thefields .= '<div class="control-group">
                        <label class="control-label" for="' . $id . '">' .  langA($field['Field']) . '</label>
                        <div class="controls">
                            <input disabled="disabled" class="input-xlarge focused" id="' . $id . '" name="' . $name . '" type="text" value="' . $value . '">
                        </div>
                    </div>';
    }
    else {
        $thefields .= '<div class="control-group">';
        if ($label) {
            $thefields .= '<label class="control-label" for="' . $id . '">' .  langA($field['Field']) . '</label>';
        }
        $thefields .= '<div class="controls"';

        if ($label == false) {
            $thefields .= ' style="margin-left:0px;"';
        }
        $thefields .= '>
                            <input class="input-xlarge focused" id="' . $id . '" name="' . $name . '" type="text" value="' . htmlspecialchars($value) . '">
                        </div>
                    </div>';
    }
    
    return $thefields;
}

function getHeader() {
    $output = '<!-- start: Header -->
        <div class="navbar">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="index.html"><span>Acme, Inc</span></a>

                    <!-- start: Header Menu -->
                    <div class="nav-no-collapse header-nav">
                        <ul class="nav pull-right">
                            <li class="dropdown hidden-phone">
                                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="halflings-icon white warning-sign"></i>
                                </a>
                                <ul class="dropdown-menu notifications">
                                    <li>
                                        <span class="dropdown-menu-title">You have 11 notifications</span>
                                    </li>	
                                    <li>
                                        <a href="#">
                                            + <i class="halflings-icon white user"></i> <span class="message">New user registration</span> <span class="time">1 min</span> 
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            + <i class="halflings-icon white comment"></i> <span class="message">New comment</span> <span class="time">7 min</span> 
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            + <i class="halflings-icon white comment"></i> <span class="message">New comment</span> <span class="time">8 min</span> 
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            + <i class="halflings-icon white comment"></i> <span class="message">New comment</span> <span class="time">16 min</span> 
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            + <i class="halflings-icon white user"></i> <span class="message">New user registration</span> <span class="time">36 min</span> 
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            + <i class="halflings-icon white cart"></i> <span class="message">2 items sold</span> <span class="time">1 hour</span> 
                                        </a>
                                    </li>
                                    <li class="warning">
                                        <a href="#">
                                            - <i class="halflings-icon white user"></i> <span class="message">User deleted account</span> <span class="time">2 hour</span> 
                                        </a>
                                    </li>
                                    <li class="warning">
                                        <a href="#">
                                            - <i class="halflings-icon white shopping-cart"></i> <span class="message">Transaction was canceled</span> <span class="time">6 hour</span> 
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            + <i class="halflings-icon white comment"></i> <span class="message">New comment</span> <span class="time">yesterday</span> 
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            + <i class="halflings-icon white user"></i> <span class="message">New user registration</span> <span class="time">yesterday</span> 
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-menu-sub-footer">View all notifications</a>
                                    </li>	
                                </ul>
                            </li>
                            <!-- start: Notifications Dropdown -->
                            <li class="dropdown hidden-phone">
                                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="halflings-icon white tasks"></i>
                                </a>
                                <ul class="dropdown-menu tasks">
                                    <li>
                                        <span class="dropdown-menu-title">You have 17 tasks in progress</span>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="header">
                                                <span class="title">iOS Development</span>
                                                <span class="percent"></span>
                                            </span>
                                            <div class="taskProgress progressSlim progressBlue">80</div> 
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="header">
                                                <span class="title">Android Development</span>
                                                <span class="percent"></span>
                                            </span>
                                            <div class="taskProgress progressSlim progressBlue">47</div> 
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="header">
                                                <span class="title">Django Project For Google</span>
                                                <span class="percent"></span>
                                            </span>
                                            <div class="taskProgress progressSlim progressBlue">32</div> 
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="header">
                                                <span class="title">SEO for new sites</span>
                                                <span class="percent"></span>
                                            </span>
                                            <div class="taskProgress progressSlim progressBlue">63</div> 
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="header">
                                                <span class="title">New blog posts</span>
                                                <span class="percent"></span>
                                            </span>
                                            <div class="taskProgress progressSlim progressBlue">80</div> 
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-menu-sub-footer">View all tasks</a>
                                    </li>	
                                </ul>
                            </li>
                            <!-- end: Notifications Dropdown -->
                            <!-- start: Message Dropdown -->
                            <li class="dropdown hidden-phone">
                                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="halflings-icon white envelope"></i>
                                </a>
                                <ul class="dropdown-menu messages">
                                    <li>
                                        <span class="dropdown-menu-title">You have 9 messages</span>
                                    </li>	
                                    <li>
                                        <a href="#">
                                            <span class="avatar"><img src="img/avatar.jpg" alt="Avatar"></span>
                                            <span class="header">
                                                <span class="from">
                                                    Å�ukasz Holeczek
                                                </span>
                                                <span class="time">
                                                    6 min
                                                </span>
                                            </span>
                                            <span class="message">
                                                Lorem ipsum dolor sit amet consectetur adipiscing elit, et al commore
                                            </span>  
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="avatar"><img src="img/avatar2.jpg" alt="Avatar"></span>
                                            <span class="header">
                                                <span class="from">
                                                    Megan Abott
                                                </span>
                                                <span class="time">
                                                    56 min
                                                </span>
                                            </span>
                                            <span class="message">
                                                Lorem ipsum dolor sit amet consectetur adipiscing elit, et al commore
                                            </span>  
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="avatar"><img src="img/avatar3.jpg" alt="Avatar"></span>
                                            <span class="header">
                                                <span class="from">
                                                    Kate Ross
                                                </span>
                                                <span class="time">
                                                    3 hours
                                                </span>
                                            </span>
                                            <span class="message">
                                                Lorem ipsum dolor sit amet consectetur adipiscing elit, et al commore
                                            </span>  
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="avatar"><img src="img/avatar4.jpg" alt="Avatar"></span>
                                            <span class="header">
                                                <span class="from">
                                                    Julie Blank
                                                </span>
                                                <span class="time">
                                                    yesterday
                                                </span>
                                            </span>
                                            <span class="message">
                                                Lorem ipsum dolor sit amet consectetur adipiscing elit, et al commore
                                            </span>  
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="avatar"><img src="img/avatar5.jpg" alt="Avatar"></span>
                                            <span class="header">
                                                <span class="from">
                                                    Jane Sanders
                                                </span>
                                                <span class="time">
                                                    Jul 25, 2012
                                                </span>
                                            </span>
                                            <span class="message">
                                                Lorem ipsum dolor sit amet consectetur adipiscing elit, et al commore
                                            </span>  
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-menu-sub-footer">View all messages</a>
                                    </li>	
                                </ul>
                            </li>
                            <!-- end: Message Dropdown -->
                            <li>
                                <a class="btn" href="#">
                                    <i class="halflings-icon white wrench"></i>
                                </a>
                            </li>
                            <!-- start: User Dropdown -->
                            <li class="dropdown">
                                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="halflings-icon white user"></i> ' . $_SESSION['usuario']['user'] . '
                                    <span class="caret"></span>
                                </a>
                            <ul class="dropdown-menu">
                                    <li><a href="#"><i class="halflings-icon white user"></i> Profile</a></li>
                                    <li><a href="#" onClick="logout();"><i class="halflings-icon white off"></i> Logout</a></li>
                                </ul>
                            </li>
                            <!-- end: User Dropdown -->
                        </ul>
                    </div>
                    <!-- end: Header Menu -->

                </div>
            </div>
        </div>
        <!-- start: Header -->';
    return $output;
}

function getMainMenu() {
    $output = '<!-- start: Main Menu -->
                <div id="sidebar-left" class="span1">
                    <div class="nav-collapse sidebar-nav">
                        <ul class="nav nav-tabs nav-stacked main-menu">';

    
        if($_GET['t'] == 'secciones')
            $output .= '<li class="active">';
        else
            $output .= '<li>';
    
        $output .= '<a href="' . BASE_URL . 'admin/list.php?t=secciones"><i class="fa-icon-bar-chart"></i><span class="hidden-tablet"> Secciones</span></a></li>';
        if($_GET['t'] == 'banners')
            $output .= '<li class="active">';
        else
            $output .= '<li>';
    
        $output .= '<a href="' . BASE_URL . 'admin/list.php?t=banners"><i class="fa-icon-bar-chart"></i><span class="hidden-tablet"> Banners</span></a></li>';
        if($_GET['t'] == 'galerias')
            $output .= '<li class="active">';
        else
            $output .= '<li>';
    
        $output .= '<a href="' . BASE_URL . 'admin/list.php?t=galerias"><i class="fa-icon-bar-chart"></i><span class="hidden-tablet"> Galerias</span></a></li>';
        if($_GET['t'] == 'paradas')
            $output .= '<li class="active">';
        else
            $output .= '<li>';
    
        $output .= '<a href="' . BASE_URL . 'admin/list.php?t=paradas"><i class="fa-icon-bar-chart"></i><span class="hidden-tablet"> Paradas</span></a></li>';
        if($_GET['t'] == 'noticias')
            $output .= '<li class="active">';
        else
            $output .= '<li>';
    
        $output .= '<a href="' . BASE_URL . 'admin/list.php?t=noticias"><i class="fa-icon-bar-chart"></i><span class="hidden-tablet"> Noticias</span></a></li>';
        
       $output .= '</ul>
                    </div>
                </div>
                <!-- end: Main Menu -->';
    return $output;
}

function headIncludes($headIncludes = array()) {
    $includes = '<!-- start: CSS -->
        <link id="bootstrap-style" href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.min.css" rel="stylesheet">
        <link id="base-style" href="css/style.css" rel="stylesheet">
        <link id="base-style" href="css/retoques.css" rel="stylesheet">
        <link id="base-style-responsive" href="css/style-responsive.css" rel="stylesheet">
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,latin-ext" rel="stylesheet" type="text/css">
        <link type="text/css" href="css/jquery-picklist.css" rel="stylesheet" />
        <!--end: CSS-->

        <!--The HTML5 shim, for IE6-8 support of HTML5 elements-->
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <link id="ie-style" href="css/ie.css" rel="stylesheet">
        <![endif]-->

        <!--[if IE 9]>
        <link id="ie9style" href="css/ie9.css" rel="stylesheet">
        <![endif]-->';
    foreach ((array) $headIncludes as $headIncludes_key => $headIncludes_value) {
        $includes .= $headIncludes_value;
    }
    $includes .= '<link id="base-style-responsive" href="css/retoques.css" rel="stylesheet">';
    return $includes;
}

function footerIncludes($footerIncludes = array()) {
    /*$includes = '<script>var base_url = "' . BASE_URL . '"</script>
        <script src="js/jquery-1.9.1.min.js"></script>
        <script src="js/jquery-migrate-1.0.0.min.js"></script>
        <script src="js/jquery-ui-1.10.0.custom.min.js"></script>';*/
    $includes = '<script>var base_url = "' . BASE_URL . '"</script>
        <script src="js/jquery-1.9.1.min.js"></script>
        <script src="js/jquery-migrate-1.0.0.min.js"></script>
        <script src="js/jquery-ui-1.10.0.custom.min.js"></script>
        <script src="js/jquery.ui.touch-punch.js"></script>
        <script src="js/modernizr.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.cookie.js"></script>
        <script src="js/fullcalendar.min.js"></script>
        <script src="js/jquery.dataTables.min.js"></script>
        <script src="js/excanvas.js"></script>
        <script src="js/jquery.flot.js"></script>
        <script src="js/jquery.flot.pie.js"></script>
        <script src="js/jquery.flot.stack.js"></script>
        <script src="js/jquery.flot.resize.min.js"></script>
        <script src="js/jquery.chosen.min.js"></script>
        <script src="js/jquery.uniform.min.js"></script>
        <script src="js/jquery.cleditor.min.js"></script>
        <script src="js/jquery.noty.js"></script>
        <script src="js/jquery.elfinder.min.js"></script>
        <script src="js/jquery.raty.min.js"></script>
        <script src="js/jquery.iphone.toggle.js"></script>
        <script src="js/jquery.uploadify-3.1.min.js"></script>
        <script src="js/jquery.gritter.min.js"></script>
        <script src="js/jquery.imagesloaded.js"></script>
        <script src="js/jquery.masonry.min.js"></script>
        <script src="js/jquery.knob.modified.js"></script>
        <script src="js/jquery.sparkline.min.js"></script>
        <script src="js/counter.js"></script>
        <script src="js/retina.js"></script>
        <script src="js/custom.js"></script>
        <script src="js/jquery-picklist.min.js"></script>';
    foreach ((array) $footerIncludes as $footerIncludes_key => $footerIncludes_value) {
        $includes .= $footerIncludes_value;
    }
    $includes .= '<script src="js/functions.js"></script>';
    return $includes;
}

function getFooter() {
    $output = '<footer>
                <p>
                    <span style="text-align:left;float:left">&copy; <a href="" target="_blank">creativeLabs</a> 2013</span>
                    <span class="hidden-phone" style="text-align:right;float:right">Powered by: <a href="#">Acme Dashboard</a></span>
                </p>

            </footer>';
    return $output;
}
