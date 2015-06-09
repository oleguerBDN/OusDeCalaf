<?php

class Galerias extends Database {

    public $idGaleria = '';
    public $nombre = '';
    

    function Galerias($id = false) {

        $this->addable = TRUE;
        $this->erasable = TRUE;
        $this->editable = TRUE;
        $this->duplicable = TRUE;

        $this->table = 'galerias';
        $this->fieldid = 'idGaleria';
        $this->showField = 'nombre';
        $this->publishField = false;
        
        parent::Database();
        if ($id) {
            $this->load($id);
        }
        
        $this->admin_showFields = array(
            'idGaleria'
            , 'nombre'
        );
        $this->hiddenFields = array(
                            'idGaleria'
                        );
        $this->literalFields = array(
                            'nombre'
                        );
    }
    
}

?>