<?php

class Banners extends Database {

    public $idBanner = '';
    public $nombre = '';
    

    function Banners($id = false) {

        $this->addable = TRUE;
        $this->erasable = TRUE;
        $this->editable = TRUE;
        $this->duplicable = TRUE;

        $this->table = 'banners';
        $this->fieldid = 'idBanner';
        $this->showField = 'nombre';
        $this->publishField = false;
        
        parent::Database();
        if ($id) {
            $this->load($id);
        }
        
        $this->admin_showFields = array(
            'idBanner'
            , 'nombre'
        );
        $this->hiddenFields = array(
                            'idBanner'
                        );
    }
    
}

?>