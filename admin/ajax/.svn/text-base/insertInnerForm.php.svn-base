<?php

include '../../includes/general_settings.php';
include BASE_PATH . 'admin/functions_display.php';


$formId = $_POST['id'];
$section = $_POST['section'];
$extraForm = '';
if($formId!='NULL'){
    $newclass = new Section_form_values();
    $newclass->describeTable();

    $langs = new Literal_langs();
    $literals = new Literal_groups();

    $all_langs = $langs->getAll('langId,langName,langIso');


    $sql = 'SELECT * FROM section_form_fields WHERE fieldFormFk = ' . $formId . ' ORDER BY fieldOrder';
    $fields = $newclass->getRecordSet($sql);
    $counter = 0;
    $extraForm .= '<div id="section_form_values_formfields">';
    foreach ((array) $fields as $field) {
        $tab = '';
        $tabcontent = '';
        $sql = 'SELECT * FROM section_form_values WHERE valueSectionIdFk = "' . $section . '" AND valueFormIdFk = "' . $formId . '" AND valueFieldIdFk = "' . $field['fieldId'] . '"';
        $formValues = $newclass->getRecord($sql);
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
            $tabcontent .= '<div class="control-group">
                            <div class="controls" style="margin-left:0px;">';
            $tabcontent .= '<input class="input-xlarge" id="valueLiteralIdFk_' . $all_langs_values['langId'] . '_' . $counter . '" name="section_form_values[' . $counter . '][valueLiteralFk][' . $all_langs_values['langId'] . ']" type="text" value="' . $literals->getLiteral($formValues['valueLiteralFk'], $all_langs_values['langIso']) . '">';
            $tabcontent .= '</div>
                                </div>';
            $tabcontent .= '</div>';
        }
        $_SESSION['admin']['langcount']++;
        $formValueId = $formValues['valueId'];
        if (!$formValueId) {
            $formValueId = -1;
        }
        $extraForm .= '<div class="control-group">
                        <label class="control-label">' . translate($field['fieldNameFk']) . '</label>
                        <div class="controls">
                            <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                                ' . $tab . '
                            </ul>
                            <div id="my-tab-content" class="tab-content">
                                ' . $tabcontent . '
                            </div>
                            <input type="hidden" name="section_form_values[' . $counter . '][valueLiteralFk][itemLiteralId]" value="' . $formValues['valueLiteralFk'] . '">
                            <input type="hidden" class="input-xlarge" id="valueId_' . $counter . '" name="section_form_values[' . $counter . '][valueId]" value="' . $formValueId . '">
                            <input type="hidden" class="input-xlarge" id="valueSectionIdFk_' . $counter . '" name="section_form_values[' . $counter . '][valueSectionIdFk]" value="' . $section . '">
                            <input type="hidden" class="input-xlarge" id="valueFormIdFk_' . $counter . '" name="section_form_values[' . $counter . '][valueFormIdFk]" value="' . $formId . '">
                            <input type="hidden" class="input-xlarge" id="valueFieldIdFk_' . $counter . '" name="section_form_values[' . $counter . '][valueFieldIdFk]" value="' . $field['fieldId'] . '">

                        </div>
                    </div>';
        $counter++;
    }
    $extraForm .= '</div>';
}

echo $extraForm;
?>
