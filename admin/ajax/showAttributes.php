<?php

require_once '../../includes/general_settings.php';

if ($_POST['attributeId'] != 'NULL') {
    $class = new Item_default_values();

    $sql = 'SELECT defaultValue,defaultValueLiteral FROM item_default_values WHERE defaultAttributeIdFk = ' . $_POST['attributeId'];
    $attribute_default_values = $class->getRecordSet($sql);

    $options = '<option value="NULL">---------</option>';
    foreach ($attribute_default_values as $value) {
        $options .= '<option value="' . $value['defaultValue'] . '">' . translate($value['defaultValueLiteral']) . '</option>';
    }
    
    echo $options;
   
} else {
    echo '<option value="NULL">---------</option>';
}

?>
