<?php
include '../includes/general_settings.php';
include 'functions_display.php';
if ($_SESSION['admin']['user']['id'] < 1) {
    header('Location: ' . BASE_URL . 'admin/');
}
$footerIncludes = array();

$db = new Database();
$tables = $db->showTables();
$hide = $GLOBALS['hide'];
foreach ((array) $tables as $tables_key => $tables_value) {
    $pos = strpos($tables_value['Tables_in_' . DBSCHEMA], 'item_');
    if ($pos === false) {
        $pos = strpos($tables_value['Tables_in_' . DBSCHEMA], 'section_');
        if ($pos === false) {
            $pos = strpos($tables_value['Tables_in_' . DBSCHEMA], 'newsletter_');
            if ($pos === false) {
                $pos = strpos($tables_value['Tables_in_' . DBSCHEMA], 'footer_');
                if ($pos === false) {
                    if (!in_array($tables_value['Tables_in_' . DBSCHEMA], $hide)) {
                        $submenu['config'][] = $tables_value['Tables_in_' . DBSCHEMA];
                    }
                } else {
                    $submenu['footer'][] = $tables_value['Tables_in_' . DBSCHEMA];
                }
            } else {
                $submenu['newsletter'][] = $tables_value['Tables_in_' . DBSCHEMA];
            }
        } else {
            if (!in_array($tables_value['Tables_in_' . DBSCHEMA], $hide)) {
                $submenu['sections'][] = $tables_value['Tables_in_' . DBSCHEMA];
            }
        }
    } else {
        if (!in_array($tables_value['Tables_in_' . DBSCHEMA], $hide)) {
        $submenu['items'][] = $tables_value['Tables_in_' . DBSCHEMA];
        }
    }
}

$theclass = ucfirst($_GET['t']);
$theclass = new $theclass($_GET['id']);
$langs = new Literal_langs();
$literals = new Literal_groups();

$all_langs = $langs->getAll('langId,langName,langIso');

$showfield = $theclass->showField;

if (in_array($showfield, $theclass->literalFields)) {
    $h2 = translate($theclass->$showfield);
} else {
    $h2 = $theclass->$showfield;
}


$theclass->describeTable();

$thefields = '';
$_SESSION['admin']['langcount'] = 0;
foreach ((array) $theclass->fields as $field) {
    if($field['Field'] == 'itemAttributeSetFk' || $field['Field'] == 'itemTypeFk'){
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
                        <?= getSubmenuBox($submenu[$_GET['s']]) ?>
                        <div class="span9" onTablet="span7" onDesktop="span9">
                            <div class="span12">
                                <h1><?= langA('editing') ?> <?= langA($_GET['t']) ?></h1>
                                <h2><?= $h2 ?></h2>
                            </div>
                            <div class="span12">
                            </div>
                            <div class="row-fluid ">
                                <div class="span12">
                                    <ul class="breadcrumb">
                                        <li><a href="<?= BASE_URL ?>admin/list.php?s=<?= $_GET['s'] ?>&t=<?= $_GET['t'] ?>"><?= langA($_GET['t']) ?></a> <span class="divider">/</span></li>
                                        <li class="active"><?= langA('editing') ?></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row-fluid ">
                                <form class="form-horizontal" id="theform" enctype="multipart/form-data">
                                    <div class="row-fluid">
                                        <div class="box span12">
                                            <div class="box-header" data-original-title>
                                                <h2><i class="halflings-icon edit"></i><span class="break"></span>Form Elements</h2>
                                                <div class="box-icon">
                                                    <a class="btn-minimize" href="#"><i class="halflings-icon chevron-up icon-chevron-up"></i></a>
                                                </div>
                                            </div>
                                            <div class="box-content">
                                                <fieldset>
                                                    <?= $thefields ?>
                                                    <input type="hidden" name="itemTaxTypeFk" id="itemTaxTypeFk" value="1"/>
                                                    <input type="hidden" name="t" id="t" value="<?= $_GET['t'] ?>"/>
                                                    <input type="hidden" name="itemid" id="itemid" value="<?= $_GET['id'] ?>"/>
                                                </fieldset>
                                            </div>
                                        </div><!--/span-->
                                    </div><!--/row-->
                                    <div class="buttons">
                                        <button type="button" onclick="saveForm('<?= BASE_URL . 'admin/form.php?s=' . $_GET['s'] . '&t=' . $_GET['t'] . '&id=' ?>','continue')" class="btn btn-primary"><?= langA('continue') ?></button>
                                        <button type="button" onclick="location.href='<?= BASE_URL ?>admin/list.php?s=<?= $_GET['s'] ?>&t=<?= $_GET['t'] ?>'" class="btn"><?= langA('cancel') ?></button>
                                    </div>
                                </form>
                            </div>
                            <!-- end: Content -->
                        </div><!--/span9-->
                    </div><!--/fluid-row-->
                </div><!--/#content-->

                <!--            <div class="modal hide fade" id="myModal">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">×</button>
                                    <h3><?//langA('erase')?></h3>
                                </div>
                                <div class="modal-body">
                                    <p>¿Seguro que quieres eliminar esta entrada?</p>
                                </div> 
                                <div class="modal-footer">
                                    <input type="hidden" id="table" value=""/>
                                    <input type="hidden" id="delID" value="0"/>
                                    <input type="hidden" id="delRow" value="0"/>
                                    <a href="#" class="btn" data-dismiss="modal"><?//langA('cancel')?></a>
                                    <a onclick="deleteInnerItem();" class="btn btn-primary"><?//langA('erase')?></a>
                                </div>
                            </div>-->
                <input type="hidden" id="table" value=""/>
                <input type="hidden" id="delID" value="0"/>
                <input type="hidden" id="delRow" value="0"/>
                <div class="clearfix"></div>
                <?= getFooter() ?>
            </div><!--/.fluid-container-->

            <!-- start: JavaScript-->
            <?= footerIncludes($footerIncludes) ?>
            <!-- end: JavaScript-->       

    </body>
</html>
