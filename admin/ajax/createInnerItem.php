<?php
include '../../includes/general_settings.php';
include BASE_PATH.'admin/functions_display.php';

$items = new Item_items();
$langs = new Literal_langs();

$parent = $_POST['parent'];
$parentid = $_POST['parentid'];
$form = $_POST['table'];
$c = intval($_POST['count']);
$row = intval($_POST['row']);

$newclass = new $form();
$newclass->describeTable();

$all_langs = $langs->getAll('langId,langName,langIso');

$extraForm = '<div id="'.$form.'_'.$c.'">'; 
if($c > 0){
    $extraForm .= '<hr>';
}
$extraForm .= '<a href="#" id="cancel_'.$row.'" style="float:right;display:none;margin-left:5px;" onclick="hideButtons(\''.$row.'\')" class="btn btn-danger" title="Cancelar"><i class="fa-icon-remove"></i></a>';
$extraForm .= '<a href="#" id="confirm_'.$row.'" style="float:right;display:none;margin-left:5px;" onclick="deleteInnerItem();" class="btn btn-success" title="Aceptar"><i class="fa-icon-ok"></i></a>';
$extraForm .= '<a href="#" id="delete_'.$row.'" style="float:right" onclick="deleteInnerRow(\'-1\',\''.$form.'\',\''.$row.'\',\''.$c.'\')" class="btn btn-danger" title="Eliminar"><i class="fa-icon-trash"></i></a>';
foreach ((array) $newclass->fields as $field) {
    if (in_array($field['Field'], $newclass->literalFields)) {
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
            $tabcontent .= getInputBox($newclass, $field, '', $form.'['.$c.']['.$field['Field'].'][' . $all_langs_values['langId'] . ']',$field['Field']. '_' . $all_langs_values['langId'].'_'.$c,false);
            $tabcontent .= '</div>';
        }
        $_SESSION['admin']['langcount']++;
        $extraForm .= '<div class="control-group">
        <label class="control-label">' . langA($field['Field']) . '</label>
        <div class="controls">
            <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                ' . $tab . '
            </ul>
            <div id="my-tab-content" class="tab-content">
                ' . $tabcontent . '
            </div>

            <input type="hidden" name="'.$form.'['.$c.']['.$field['Field'].'][itemLiteralId]" value="">
       </div>
    </div>';
    } else {
        $asdf = array_search($parent, $newclass->references);
        if ($asdf == $field['Field']) {
            $value[$field['Field']] = $parentid;
        } elseif($field['Field'] == $newclass->fieldid){
            $value[$field['Field']] = '-1';
        }
        $extraForm .= getInputBox($newclass, $field, $value[$field['Field']],$form.'['.$c.']['.$field['Field'].']',$field['Field'].'_'.$c,true,$parent);
    }
    
}
$c++;
$row++;
$extraForm .= '</div>'; 

$extraForm .= '<script src="js/custom.js"></script>'; 
$extraForm .= '<script src="js/jquery-picklist.min.js"></script>'; 
if($newclass->addable){
    $extraForm .= '<script>updateButton(\''.$parent.'\',\''.$parentid.'\',\''.$form.'\',\''.$c.'\',\''.$_SESSION['admin']['langcount'].'\',\''.$row.'\')</script>'; 
}

echo $extraForm;
//print_r($value);


?>
