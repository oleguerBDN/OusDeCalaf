<?php

class Literal_langs extends Database {

    public $langId = '';
    public $langName = '';
    public $langIso = '';
    public $langPublish = '';
    public $langFlag = '';

    function Literal_langs($id = false) {

        $this->addable = TRUE;
        $this->erasable = TRUE;
        $this->editable = TRUE;
        $this->duplicable = TRUE;

        $this->table = 'literal_langs';
        $this->fieldid = 'langId';
        $this->showField = 'langName';
        $this->publishField = 'langPublish';
        $this->resize = array(
            'flag' => array(
                'width' => '13',
                'height' => '8',
                'scale' => FALSE
            ),
            'thumbs' => array(
                'width' => '20',
                'height' => '20',
                'scale' => FALSE
            )
        );
        $this->imgFolder = 'userfiles/images/langs/';

        $this->admin_showFields = array(
            'langId'
            , 'langName'
            , 'langIso'
            , 'langFlag'
            , 'langPublish'
        );
        $this->dateFields = array(
        );
        $this->wysiwygFields = array(
        );
        $this->booleanFields = array(
            'langPublish'
        );
        $this->hiddenFields = array(
            'langId'
        );
        $this->imageFields = array(
            'langFlag'
        );

        parent::Database();
        if ($id) {
            $this->load($id);
        }
    }

    function checkLang($iso) {
        $sql = 'SELECT ' . $this->fieldid . ' FROM ' . $this->table . ' WHERE langIso LIKE "' . $iso . '"';
        $rs = $this->getRecord($sql);
        return $rs;
    }

}

?>