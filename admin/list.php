<?php
include '../includes/general_settings.php';
include 'functions_display.php';
if (!$_SESSION['usuario']['id']) {
    header('Location: ' . BASE_URL . 'admin/');
}
if(in_array($_GET['t'], (array)$GLOBALS['hide'])){
    header('Location: ' . BASE_URL . 'admin/list.php?s=items&t=item_orders');
}
$_SESSION['lang'] = 'es';



$db = new Database();
$tables = $db->showTables();
$hide = $GLOBALS['hide'];

$theclass = new $_GET['t'];
$th = '';
$getAll_fields = '';
foreach ((array) $theclass->admin_showFields as $admin_showFields_key => $admin_showFields_value) {

    $getAll_fields = concatena($getAll_fields, $theclass->table . '.' . $admin_showFields_value, ', ');

    if ($theclass->fieldid == $admin_showFields_value && $_GET['t'] != 'settings' && $_GET['t'] != 'item_orders' && $_GET['t'] != 'user_users') {

        continue;
    }
    $th .= '<th>' .langA($admin_showFields_value) . '</th>';
}

$th .= '<th style="min-width: 125px">options</th>';

$td = '';


/* $array = $theclass->getAll($getAll_fields, false, false, true);
  print_r($array);
  die();
 */
if (count($theclass->selectFields) >= 1) {
    $join = true;
}
foreach ((array) $theclass->getAll($getAll_fields, false, false, $join) as $getAll_key => $getAll_value) {
    $td .= '<tr>';
    foreach ((array) $getAll_value as $getAll_value_key => $getAll_value_value) {
        if ($getAll_value_key > 0) {
            $class = ' class="center" ';
        }
        if ($theclass->fieldid == $getAll_value_key || strpos($getAll_value_key, '_name')) {
            continue;
        }
        if (!is_numeric($getAll_value_key)) {
            if (in_array($getAll_value_key, $theclass->literalFields)) {
                $td .= '<td' . $class . '>' . ucfirst(langA(translate($getAll_value_value))) . '</td>';
            } elseif (in_array($getAll_value_key, $theclass->selectFields)) {
                if (is_numeric($getAll_value[$getAll_value_key])) {
                    $td .= '<td' . $class . '>' . $getAll_value[$getAll_value_key] . '</td>';
                } else {
                    $td .= '<td' . $class . '>' . $getAll_value[$getAll_value_key ] . '</td>';
                }
            } elseif (in_array($getAll_value_key, $theclass->booleanFields)) {
                if ($getAll_value_value == 1) {
                    $td .= '<td' . $class . '>yes</td>';
                } elseif ($getAll_value_value == 0) {
                    $td .= '<td' . $class . '>no</td>';
                }
            } else {
                $td .= '<td' . $class . '>' . $getAll_value_value . '</td>';
            }
        }
    }
    $td .= '<td class="center">';
    if ($theclass->editable) {
        $td .= '<a title="edit" class="btn btn-info" href="' . BASE_URL . 'admin/form.php?t=' . $_GET['t'] . '&id=' . $getAll_value[$theclass->fieldid] . '">
                    <i class="fa-icon-edit"></i>                                            
                </a>';
    }
    if ($theclass->viewable) {
        $td .= ' <a title="view" class="btn btn-warning" href="' . BASE_URL . 'admin/form.php?t=' . $_GET['t'] . '&id=' . $getAll_value[$theclass->fieldid] . '">
                    <i class="fa-icon-eye-open"></i>                                            
                </a>';
    }
    /*if ($theclass->duplicable) {
        $td .= ' <a title="Duplicar" class="btn btn-warning" href="' . BASE_URL . 'admin/form.php?s=' . $_GET['s'] . '&t=' . $_GET['t'] . '&id=' . $getAll_value[$theclass->fieldid] . '">
                    <i class="fa-icon-copy"></i>                                            
                </a>';
    }*/
    if ($theclass->erasable) {
        $td .= ' <a title="erase" class="btn btn-danger" onclick="deleteRow(' . EC($getAll_value[$theclass->fieldid]) . ',' . EC($_GET['t']) . ')" href="#">
                    <i class="fa-icon-trash"></i> 
                </a>';
    }

    $td .= '</td>';
    $td .= '</tr>';
}
?>

<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?>">
    <head>

        <!-- start: Meta -->
        <meta charset="utf-8">
        <title>ACME Dashboard - Perfect Bootstrap Admin Template</title>
        <!-- end: Meta -->

        <!-- start: Mobile Specific -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- end: Mobile Specific -->

        <?= headIncludes($headIncludes) ?>

        <!--start: Favicon-->
        <link rel="shortcut icon" href="img/favicon.ico">
        <!--end: Favicon-->

    </head>

    <body>
        <?= getHeader() ?>
        <div class="container-fluid">
            <div class="row-fluid">
                <?= getMainMenu() ?>
                <noscript>
                <div class="alert alert-block span11">
                    <h4 class="alert-heading">Warning!</h4>
                    <p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
                </div>
                </noscript>

                <!-- start: Content -->
                <div id="content" class="span11">


                    <div class="row-fluid">
                        
                        
                        <div class="span9" onTablet="span7" onDesktop="span9">
                            <div class="row-fluid">
                                <div class="span12">
                                    <h1><?= ucfirst($_GET['t']) ?></h1>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="box span12">
                                    <div class="box-header">
                                        <h2><i class="halflings-icon align-justify"></i><span class="break"></span>Listado de <?= $_GET['t'] ?></h2>
                                        <?
                                        if ($theclass->addable) {


                                            echo '<div class="box-icon" title="Añadir">
                                                <a href="' . BASE_URL . 'admin/form.php?t=' . $_GET['t'] . '&id=-1" ><i class="halflings-icon plus"></i></a>
                                                </div>';
                                        }
                                        ?>
                                    </div>
                                    <div class="box-content">
                                        <table class="table table-striped table-condensed bootstrap-datatable datatable">
                                            <thead>
                                                <tr>
                                                    <?= $th ?>                                          
                                                </tr>
                                            </thead>   
                                            <tbody>
                                                <?= $td ?>
                                            </tbody>
                                        </table>  
                                        <input type="hidden" id="delID" value="0"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>	

                    <!-- end: Content -->
                </div><!--/#content.span10-->
            </div><!--/fluid-row-->

            <div class="modal hide fade" id="myModal">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3><?= 'erase' ?></h3>
                </div>
                <div class="modal-body">
                    <p><?= 'confirm_delete' ?></p>
                </div> 
                <div class="modal-footer">
                    <input type="hidden" id="table" value="<?= $_GET['t'] ?>"/>
                    <a href="#" class="btn" data-dismiss="modal"><?= 'cancel' ?></a>
                    <a onclick="deleteItem();" class="btn btn-primary"><?= 'erase' ?></a>
                </div>
            </div>
            <?
            if($_GET['t'] == 'item_items'){
            ?>
            <div class="modal hide fade" id="myModal2">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3><?= 'erase' ?></h3>
                </div>
                <div class="modal-body">
                    <p><?= 'confirm_delete_complex_item' ?></p>
                </div> 
                <div class="modal-footer">
                    <input type="hidden" id="table" value="<?= $_GET['t'] ?>"/>
                    <a href="#" class="btn" data-dismiss="modal"><?= 'cancel' ?></a>
                    <a onclick="deleteItem();" class="btn btn-primary"><?= 'erase' ?></a>
                </div>
            </div>
            <?
            }
            ?>
            <div class="clearfix"></div>
            <?= getFooter() ?>
        </div><!--/.fluid-container-->

        <!-- start: JavaScript-->
        <?= footerIncludes($footerIncludes);
        ?>
        <!-- end: JavaScript-->

    </body>
</html>
