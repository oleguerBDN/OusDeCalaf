<?php
include '../../includes/general_settings.php';
include BASE_PATH.'admin/functions_display.php';

$parent = $_POST['parent'];
$form = $_POST['table'];
$c = intval($_POST['count']);

if($c > -1){
    $arrayCounter = '['.$c.']';
    $idCounter = $c;
} else {
    $idCounter = 0;
}

$newclass = new $parent();
$newclass->describeTable();

$langs = new Literal_langs();

$all_langs = $langs->getAll('langId,langName,langIso');
$extraForm = '<div id="'.$parent.'_'.$idCounter.'" class="inner">'; 
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
            $tabcontent .= getInputBox($newclass, $field, '', $form.$arrayCounter.'['.$parent.']['.$field['Field'].'][' . $all_langs_values['langId'] . ']',$field['Field']. '_' . $all_langs_values['langId'].'_'.$idCounter,false);
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

            <input type="hidden" name="'.$form.$arrayCounter.'['.$parent.']['.$field['Field'].'][itemLiteralId]" value="">
       </div>
    </div>';
    } else {
        $extraForm .= getInputBox($newclass, $field, '',$form.$arrayCounter.'['.$parent.']['.$field['Field'].']',$field['Field'].'_'.$idCounter,true,$parent);
    }
    
}




$extraForm .= '</div>'; 

$extraForm .= '<script src="js/custom.js"></script>'; 
$extraForm .= '<script src="js/jquery-picklist.min.js"></script>'; 

echo $extraForm;
//print_r($value);
//echo '<div>tabla madre: '.$parent.'<br>
//tabla actual: '.$form.'<br>
//contador: '.$idCounter.'<br>
//counter: '.$arrayCounter.'</div>';


?>
