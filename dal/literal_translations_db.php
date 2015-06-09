<?php

class Literal_translations extends Database {

    public $literalLangFk = '';
    public $literalGroupFk = '';
    public $literalText = '';

    function Literal_translations($id = false) {

        $this->addable = TRUE;
        $this->erasable = TRUE;
        $this->editable = TRUE;
        $this->duplicable = TRUE;

        $this->table = 'literal_translations';
        $this->fieldid = 'literalLangFk';
        $this->showField = 'literalLangFk';
        $this->publishField = false;

        $this->admin_showFields = array(
            'literalLangFk'
            , 'literalGroupFk'
        );
        $this->dateFields = array(
            
        );
        $this->wysiwygFields = array(
            'literalText'
        );
        $this->booleanFields = array(
            
        );
        $this->hiddenFields = array(
            'literalLangFk'
        );

        parent::Database();
        if($id){
            $this->load($id);
        }
        
    }

}
?>