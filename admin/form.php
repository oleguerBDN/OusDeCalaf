<?php
include '../includes/general_settings.php';
include 'functions_display.php';
if (!$_SESSION['usuario']['id']) {
    header('Location: ' . BASE_URL . 'admin/');
}
if (in_array($_GET['t'], (array) $GLOBALS['hide'])) {
    header('Location: ' . BASE_URL . 'admin/list.php?s=items&t=item_orders');
}
$_SESSION['lang'] = 'es';
$_SESSION['admin']['langcount'] = 0;
$footerIncludes = array();
$db = new Database();
$tables = $db->showTables();
$hide = $GLOBALS['hide'];

$theclass = ucfirst($_GET['t']);

$theclass = new $theclass($_GET['id']);

//echo ('asociado de -> '.$_GET['load'].'<br>');
//echo ('tipo -> '.$theclass->itemTypeFk.'<br>');
//echo ('asociado de -> '.$_GET['load'].'<br>');

$literals = new Literal_groups();


$langs = new Literal_langs();
$all_langs = $langs->getAll('langId,langName,langIso');

$showfield = $theclass->showField;

if (in_array($showfield, $theclass->literalFields)) {
    $h2 = $theclass->$showfield;
} else {
    $h2 = $theclass->$showfield;
}

$theclass->describeTable();

$thefields = '';
$_SESSION['admin']['langcount'] = 0;
//print_r($theclass->fields);
foreach ((array) $theclass->fields as $field) {
    if ($theclass->editable == FALSE) {
        $theclass->uneditableFields[] = $field['Field'];
    }
    if (in_array($field['Field'], $theclass->literalFields)) {

        $tab = '';
        $tabcontent = '';

        foreach ((array) $all_langs as $all_langs_values) {

            $tab .= ' <li';
            if ($_SESSION['lang'] == $all_langs_values['langIso']) {
                $tab .= ' class="active"';
            }
            $tab .= '><a href="#' . $all_langs_values['langName'] . $_SESSION['admin']['langcount'] . '" data-toggle="tab">' . $all_langs_values['langName'] . '</a></li>';


            $tabcontent .= '<div class="tab-pane';
            if ($_SESSION['lang'] == $all_langs_values['langIso']) {
                $tabcontent .= ' active';
            }
            $tabcontent .= '" id="' . $all_langs_values['langName'] . $_SESSION['admin']['langcount'] . '">';
            $tabcontent .= getInputBox($theclass, $field, $literals->getLiteral($theclass->$field['Field'], $all_langs_values['langIso']), $field['Field'] . '[' . $all_langs_values['langId'] . ']', $field['Field'] . '_' . $all_langs_values['langId'], false);
            $tabcontent .= '</div>';
        }
        $_SESSION['admin']['langcount']++;
        $thefields .= '<div class="control-group';
        if (in_array($field['Field'], $theclass->hiddenFields)) {
            $thefields .= ' hidden';
        }
        if ($_GET['id'] > 0) {
            $literalId = $theclass->$field['Field'];
        } else {
            $literalId = '';
        }
        $thefields .= '">
                        <label class="control-label">' . langA($field['Field']) . '</label>
                        <div class="controls">
                            <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                                ' . $tab . '
                            </ul>
                            <div id="my-tab-content" class="tab-content">
                                ' . $tabcontent . '
                            </div>
                            <input type="hidden" name="' . $field['Field'] . '[itemLiteralId]" value="' . $literalId . '">
                       </div>
                    </div>';
    } else {
        $thefields .= getInputBox($theclass, $field, $theclass->$field['Field'], false, false);
    }
}


?>

<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?>">
    <head>

        <!-- start: Meta -->
        <meta charset="utf-8">
        <title><?= PROJECT_NAME ?> ADMIN</title>
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
                            <div class="span12">
                                <h1>Editing <?= $_GET['t'] ?></h1>
                                <!--<h2>?= $h2 ?></h2>-->
                            </div>
                            <div class="span12">
                            </div>
                            <div class="row-fluid ">
                                <div class="span12">
                                    <ul class="breadcrumb">
                                        <li><a href="<?= BASE_URL ?>admin/list.php?t=<?= $_GET['t'] ?>"><?= $_GET['t'] ?></a> <span class="divider">/</span></li>
                                        <li class="active"><?= 'editing' ?></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row-fluid ">
                                <form class="form-horizontal" id="theform" enctype="multipart/form-data">
                                    <div class="row-fluid">
                                        <div class="box span12">
                                            <div class="box-header" data-original-title>
                                                <h2><i class="halflings-icon edit"></i><span class="break"></span><?= 'Editing' ?> <?= $_GET['t'] ?></h2>
                                                <div class="box-icon">
                                                    <a class="btn-minimize" href="#"><i class="halflings-icon chevron-up icon-chevron-up"></i></a>
                                                </div>
                                            </div>
                                            <div class="box-content">
                                                <fieldset>
                                                    <?= $thefields ?>
                                                    <?= $picklist ?>
                                                    <div id="contform">
                                                    <?
                                                    if($_GET['t'] == 'banners'){
                                                        if($_GET['id'] != -1){
                                                            $output = getFormBannerImgs($_GET['id']);
                                                        }
                                                        echo $output;
                                                    }elseif(($_GET['t'] == 'galerias')){
                                                        if($_GET['id'] != -1){
                                                            $output = getFormGaleriaImgs($_GET['id']);
                                                        }
                                                        echo $output;
                                                    }
                                                    ?>
                                                    </div>
                                                    
                                                    <div id="formButt"></div>
                                                </fieldset>
                                            </div>
                                            <input type="hidden" name="t" id="t" value="<?= $_GET['t'] ?>"/>
                                                    <input type="hidden" name="itemid" id="itemid" value="<?= $_GET['id'] ?>"/>
                                        </div><!--/span-->
                                    </div><!--/row-->
                                    <?php
                                        if($_GET['t'] == 'banners'){
                                            $output = '<div id="divAdd" class="control-group">
                                                            <div class="controls">
                                                                <button id="addbtn" type="button" onclick="addImg(1);">Añadir imágen</button>
                                                            </div>
                                                        </div>';
                                            echo $output;
                                        }elseif($_GET['t'] == 'galerias'){
                                            $output = '<div id="divAdd" class="control-group">
                                                            <div class="controls">
                                                                <button id="addbtn" type="button" onclick="addImg1(1);">Añadir imágen</button>
                                                            </div>
                                                        </div>';
                                            echo $output;
                                        }
                                    ?>
                                    
                                    <div class="buttons">
                                        <?
                                        if($_GET['t'] == 'newsletter_sent' ){
                                            $save_text = 'send';
                                        } else {
                                            $save_text = 'save';
                                        }
                                        ?>
                                        <button type="button" id="save" onclick="saveForm('<?= BASE_URL . 'admin/list.php?t=' . $_GET['t'] ?>')" class="btn btn-primary"><?= $save_text ?></button>
                                        
                                        <a href="<?= BASE_URL ?>admin/list.php?t=<?= $_GET['t'] ?>"><button id="cancel" type="button" class="btn"><?= 'cancel' ?></button></a>
                                        <!--<button id="cancel" type="button" onclick="?= $cancel_onclick ?>" class="btn">?= 'cancel' ?></button>-->
                                    </div>
                                </form>
                            </div>
                            <!-- end: Content -->
                        </div><!--/span9-->
                    </div><!--/fluid-row-->

                </div><!--/#content-->
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
                <div class="modal hide fade" id="itemModal">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h3><?= 'erase' ?></h3>
                    </div>
                    <div class="modal-body">
                        <p><?= 'cancel_without_save' ?></p>
                    </div> 
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal"><?= 'cancel' ?></button>
                        <button type="button" onclick="deleteLastAdded();" class="btn btn-primary"><?= 'erase' ?></button>
                    </div>
                </div>
                <input type="hidden" id="hrefTo" value="0"/>
                <input type="hidden" id="deltable" value=""/>
                <input type="hidden" id="delID" value="0"/>
                <input type="hidden" id="delRow" value="0"/>
                <input type="hidden" id="count" value="0"/>
                <div class="clearfix"></div>
                <?= getFooter() ?>
            </div><!--/.fluid-container-->

            <!-- start: JavaScript-->

            <?= footerIncludes($footerIncludes) ?>
            <script>
                $('.datepicker').datepicker({
                    dateFormat: 'yy-mm-dd'
                });
            </script>

            <!-- end: JavaScript-->       

    </body>
</html>
