<?php

class Noticias extends Database {

    public $idNoticia = '';
    public $nombre = '';
    public $texto = '';
    public $noticia = '';
    public $noticia_img = '';
    public $desc_img = '';
    public $parada_img = '';
    

    function Noticias($id = false) {

        $this->addable = TRUE;
        $this->erasable = TRUE;
        $this->editable = TRUE;
        $this->duplicable = TRUE;

        $this->table = 'noticias';
        $this->fieldid = 'idNoticia';
        $this->showField = 'nombre';
        $this->publishField = false;
        $this->imgFolder = 'userfiles/noticias/';
        
        parent::Database();
        if ($id) {
            $this->load($id);
        }
        $this->resize = array(
            'main' => array(
                'height' => '138',
                'width' => '215',
                'scale' => FALSE
            ),
            'thumbs' => array(
                'height' => '20',
                'scale' => FALSE
            ),
            'noticia' => array(
                'height' => '265',
                'width' => '400',
                'scale' => FALSE
            )
        );
        $this->admin_showFields = array(
            'idNoticia'
            , 'nombre'
        );
        $this->hiddenFields = array(
                            'idNoticia'
                        );
        $this->literalFields = array(
            'nombre',
            'texto',
            'alt_img',
            'desc_img',
            'noticia'
        );
        $this->wysiwygFields = array(
            'texto',
            'noticia'
        );
        $this->imageFields = array(
                            'noticia_img'
                        );
    }
    
}

?>