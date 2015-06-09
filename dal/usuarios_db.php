<?php

class Usuarios extends Database {

    public $idUser = '';
    public $user = '';
    public $password = '';

    function Usuarios($id = false) {

        $this->addable = TRUE;
        $this->erasable = TRUE;
        $this->editable = TRUE;
        $this->duplicable = TRUE;

        $this->table = 'usuarios';
        $this->fieldid = 'idUser';
        $this->showField = 'user';
        $this->publishField = false;

        $this->admin_showFields = array(
            'idUser'
            , 'user'
            , 'password'
        );
        $this->dateFields = array(
        );
        $this->wysiwygFields = array(
        );
        $this->booleanFields = array(
        );
        $this->hiddenFields = array(
            'idUser'
        );

        parent::Database();
        if ($id) {
            $this->load($id);
        }
    }
}

?>