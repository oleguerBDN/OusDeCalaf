<?php

class Paradas extends Database {

    public $idParada = '';
    public $nombre = '';
    public $paradas = '';
    public $telefono = '';
    public $horario = '';
    public $alt_img = '';
    public $desc_img = '';
    public $parada_img = '';
    

    function Paradas($id = false) {

        $this->addable = TRUE;
        $this->erasable = TRUE;
        $this->editable = TRUE;
        $this->duplicable = TRUE;

        $this->table = 'paradas';
        $this->fieldid = 'idParada';
        $this->showField = 'nombre';
        $this->publishField = false;
        $this->imgFolder = 'userfiles/paradas/';
        
        parent::Database();
        if ($id) {
            $this->load($id);
        }
        $this->resize = array(
            'main' => array(
                'height' => '197',
                'width' => '305',
                'scale' => FALSE
            ),
            'thumbs' => array(
                'height' => '20',
                'scale' => FALSE
            )
        );
        $this->admin_showFields = array(
            'idParada'
            , 'nombre'
        );
        $this->hiddenFields = array(
                            'idParada'
                        );
        $this->literalFields = array(
            'nombre',
            'horario',
            'alt_img',
            'desc_img'
        );
        $this->imageFields = array(
                            'parada_img'
                        );
    }
    
}

?>