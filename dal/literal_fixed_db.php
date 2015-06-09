<?php

class Literal_fixed extends Database {

    public $fixedLiteralId = '';
    public $fixedLiteralName = '';
    public $fixedLiteralValueFk = '';

    function Literal_fixed($id = false) {

        $this->addable = TRUE;
        $this->erasable = TRUE;
        $this->editable = TRUE;
        $this->duplicable = TRUE;

        $this->table = 'literal_fixed';
        $this->fieldid = 'fixedLiteralId';
        $this->showField = 'fixedLiteralName';
        $this->publishField = false;

        $this->admin_showFields = array(
            'fixedLiteralId'
            , 'fixedLiteralName'
            , 'fixedLiteralValueFk'
        );
        $this->dateFields = array(
            
        );
        $this->wysiwygFields = array(
            
        );
        $this->booleanFields = array(
            
        );
        $this->hiddenFields = array(
            'fixedLiteralId'
               , 'fixedLiteralName'
        );
        $this->literalFields = array(
            'fixedLiteralValueFk'
        );
        $this->uneditableFields = array(
            'fixedLiteralName'
        );

        parent::Database();
        if($id){
            $this->load($id);
        }
        
    }
    function getFixedLiterals() {
        $sql = 'SELECT fixedLiteralName, fixedLiteralValueFk FROM ' . $this->table;
        $rs = $this->getRecordSet($sql);
        $to_return = array();
        foreach ((array)$rs as $key => $value) {
            $to_return[strtolower($value['fixedLiteralName'])] = $value['fixedLiteralValueFk'];
        }
        return $to_return;
    }
    
    function insertFixedLiteral($key) {
        $value['itemid'] = -1;
        $value['fixedLiteralName'] = $key;
        $langs = new Literal_langs();
        $allLangs = $langs->getAll();
        foreach ((array) $allLangs as $lang) {
            $value['fixedLiteralValueFk'][$lang['langId']] = '';
        }
        $this->saveItem($value);
    }

}
?>