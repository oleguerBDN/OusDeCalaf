<?php

class Galeria_imgs extends Database {

    public $idImgGaleria = '';
    public $idGaleria_fk = '';
    public $alt_img = '';
    public $desc_img = '';
    public $galeria_img = '';
    

    function Galeria_imgs($id = false) {

        $this->addable = TRUE;
        $this->erasable = TRUE;
        $this->editable = TRUE;
        $this->duplicable = TRUE;

        $this->table = 'galeria_imgs';
        $this->fieldid = 'idImgGaleria';
        $this->showField = 'alt_img';
        $this->publishField = false;
        $this->imgFolder = 'userfiles/galerias_img/';
        
        parent::Database();
        if ($id) {
            $this->load($id);
        }
        
        $this->resize = array(
            'open' => array(
                'width' => '700',
                'height' => '700'
            ),
            'thumbs' => array(
                'height' => '20'
            ),
            'main' => array(
                'height' => '55',
                'width' => '55',
            )
            
        );
        $this->admin_showFields = array(
            'idImgGaleria'
            , 'titulo_fk'
        );
        $this->literalFields = array(
            'alt_img',
            'desc_img'
        );
        $this->hiddenFields = array(
                            'idImgGaleria',
                            'idGaleria_fk'
                        );
        $this->imageFields = array(
                            'galeria_img',
                        );
    }
    function saveItem($values, $_FILES, $fk) {
        /*ORDEN FILE*/
//        print_r($values);
        $c = 0;
        foreach($_FILES['galeria_img']['name'] as $item){
            $FILES[$c]['name'] = $item['galeria_img'];
            $c = $c + 2;
        }
        $c = 0;
        foreach($_FILES['galeria_img']['type'] as $item){
            $FILES[$c]['type'] = $item['galeria_img'];
            $c = $c + 2;
        }
        $c = 0;
        foreach($_FILES['galeria_img']['tmp_name'] as $item){
            $FILES[$c]['tmp_name'] = $item['galeria_img'];
            $c = $c + 2;
        }
        $c = 0;
        foreach($_FILES['galeria_img']['error'] as $item){
            $FILES[$c]['error'] = $item['galeria_img'];
            $c = $c + 2;
        }
        $c = 0;
        foreach($_FILES['galeria_img']['size'] as $item){
            $FILES[$c]['size'] = $item['galeria_img'];
            $c = $c + 2;
        }
//       print_r($FILES);
        /*SAVE*/
        $c = 0;
        foreach($values as $img){
            $img['idGaleria_fk'] = $fk;
//            print_r($img);
//            print_r($FILES[$c]);
            $id = parent::saveItem($img);
            if($FILES[$c]['name'] != ''){
            $old_file = $this->getOne($id);
            $old_file = $old_file['banner_img'];
            $newname = saveFile($FILES[$c]['name'], $FILES[$c]['tmp_name'], BASE_PATH . $this->imgFolder, $old_file, $this->resize, true);
            $sql = 'UPDATE ' . $this->table . ' SET galeria_img = ' . $this->fridge($newname) . ' WHERE idImgGaleria = ' . $id;
                    $this->query($sql);
            }
            
            $c = $c + 2;
        }
//        return parent::saveItem();
    }
}

?>