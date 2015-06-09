<?php

class Banner_imgs extends Database {

    public $idImgBanner = '';
    public $idBanner_fk = '';
    public $alt_img = '';
    public $desc_img = '';
    public $banner_img = '';
    

    function Banner_imgs($id = false) {

        $this->addable = TRUE;
        $this->erasable = TRUE;
        $this->editable = TRUE;
        $this->duplicable = TRUE;

        $this->table = 'banner_imgs';
        $this->fieldid = 'idImgBanner';
        $this->showField = 'alt_img';
        $this->publishField = false;
        $this->imgFolder = 'userfiles/banners_img/';
        
        parent::Database();
        if ($id) {
            $this->load($id);
        }
        
        $this->resize = array(
            'main' => array(
                'width' => '985',
                'height' => '401'
            ),
            'thumbs' => array(
                'height' => '20'
            )
        );
        $this->admin_showFields = array(
            'idImgBanner'
            , 'titulo_fk'
        );
        $this->literalFields = array(
            'alt_img',
            'desc_img'
        );
        $this->hiddenFields = array(
                            'idImgBanner',
                            'idBanner_fk'
                        );
        $this->imageFields = array(
                            'banner_img',
                        );
    }
    function saveItem($values, $_FILES, $fk) {
        /*ORDEN FILE*/
        
        $c = 0;
        foreach($_FILES['banner_img']['name'] as $item){
            $FILES[$c]['name'] = $item['banner_img'];
            $c = $c + 2;
        }
        $c = 0;
        foreach($_FILES['banner_img']['type'] as $item){
            $FILES[$c]['type'] = $item['banner_img'];
            $c = $c + 2;
        }
        $c = 0;
        foreach($_FILES['banner_img']['tmp_name'] as $item){
            $FILES[$c]['tmp_name'] = $item['banner_img'];
            $c = $c + 2;
        }
        $c = 0;
        foreach($_FILES['banner_img']['error'] as $item){
            $FILES[$c]['error'] = $item['banner_img'];
            $c = $c + 2;
        }
        $c = 0;
        foreach($_FILES['banner_img']['size'] as $item){
            $FILES[$c]['size'] = $item['banner_img'];
            $c = $c + 2;
        }
//       print_r($FILES);
        /*SAVE*/
        $c = 0;
        foreach($values as $img){
            $img['idBanner_fk'] = $fk;
//            print_r($img);
//            print_r($FILES[$c]);
            $id = parent::saveItem($img);
            if($FILES[$c]['name'] != ''){
            $old_file = $this->getOne($id);
            $old_file = $old_file['banner_img'];
            $newname = saveFile($FILES[$c]['name'], $FILES[$c]['tmp_name'], BASE_PATH . $this->imgFolder, $old_file, $this->resize, true);
            $sql = 'UPDATE ' . $this->table . ' SET banner_img = ' . $this->fridge($newname) . ' WHERE idImgBanner = ' . $id;
                    $this->query($sql);
            }
            
            $c = $c + 2;
        }
//        return parent::saveItem();
    }
}

?>