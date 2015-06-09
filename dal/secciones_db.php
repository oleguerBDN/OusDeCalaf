<?php

class Secciones extends Database {

    public $idSeccion = '';
    public $idBanner = '';
    public $idGaleria = '';
    public $titulo_seccion = '';
    public $titulo1 = '';
    public $texto1 = '';
    public $foto1 = '';
    public $titulo2 = '';
    public $texto2 = '';
    public $foto2 = '';
    public $titulo3 = '';
    public $texto3 = '';
    public $foto3 = '';
    public $titulo4 = '';
    public $texto4 = '';
    public $foto4 = '';
    public $titulo = '';
    public $keywords = '';
    public $direccion1 = '';
    public $horario1 = '';
    public $telefonos1 = '';
    public $direccion2 = '';
    public $horario2 = '';
    public $telefonos2 = '';
    public $correo1 = '';
    public $correo2 = '';
    public $alt_img1 = '';
    public $desc_img1 = '';
    public $alt_img2 = '';
    public $desc_img2 = '';
    public $alt_img3 = '';
    public $desc_img3 = '';
    public $alt_img4 = '';
    public $desc_img4 = '';
    public $twitter = '';
    public $facebook = '';
    

    function Secciones($id = false) {

        $this->addable = TRUE;
        $this->erasable = TRUE;
        $this->editable = TRUE;
        $this->duplicable = TRUE;

        $this->table = 'secciones';
        $this->fieldid = 'idSeccion';
        $this->showField = 'titulo_seccion';
        $this->publishField = false;
        $this->imgFolder = 'userfiles/';
        
        parent::Database();
        if ($id) {
            $this->load($id);
        }
        
        $this->resize = array(
            'thumbs' => array(
                'height' => '20',
                'scale' => FALSE
            )
        );
        $this->admin_showFields = array(
            'idSeccion'
            , 'titulo_seccion'
        );
        $this->selectFields = array(
            'getTypeSelectBanner' => 'idBanner',
            'getTypeSelectGaleria' => 'idGaleria'
        );
        $this->literalFields = array(
            'titulo_seccion',
            'titulo1',
            'texto1',
            'titulo2',
            'texto2',
            'titulo3',
            'texto3',
            'titulo4',
            'texto4',
            'titulo1',
            'titulo',
            'keywords',
            'description',
            'horario1',
            'horario2',
            'alt_img1',
            'desc_img1',
            'alt_img2',
            'desc_img2',
            'alt_img3',
            'desc_img3',
            'alt_img4',
            'desc_img4'
        );
        $this->wysiwygFields = array(
            'texto1',
            'texto2',
            'texto3',
            'texto4'
        );
        
                
        switch($id){
            case 1: $this->hiddenFields = array(
                            'idSeccion',
                            'direccion1',
                            'horario1',
                            'telefonos1',
                            'direccion2',
                            'horario2',
                            'telefonos2',
                            'email1',
                            'email2',
                            'twitter',
                            'facebook'
                        );
                     $this->imageFields = array(
                            'foto1',
                            'foto2',
                            'foto3',
                            'foto4'
                        );
                     $this->resize = array(
                        'thumbs' => array(
                            'height' => '20',
                            'scale' => FALSE),
                        'info' => array(
                            'height' => '198',
                            'width' => '305',
                            'scale' => FALSE),
                        'noticia' => array(
                            'height' => '130',
                            'width' => '100',
                            'scale' => FALSE
                        )
                    );
                    break;
            case 2: $this->hiddenFields = array(
                            'idSeccion',
                            'idGaleria',
                            'titulo2',
                            'texto2',
                            'foto2',
                            'titulo3',
                            'texto3',
                            'foto3',
                            'titulo4',
                            'texto4',
                            'foto4',
                            'direccion1',
                            'horario1',
                            'telefonos1',
                            'direccion2',
                            'horario2',
                            'telefonos2',
                            'email1',
                            'email2',
                            'alt_img2',
                            'desc_img2',
                            'alt_img3',
                            'desc_img3',
                            'alt_img4',
                            'desc_img4',
                            'twitter',
                            'facebook'
                        );
                     $this->imageFields = array(
                            'foto1'
                        );
                     $this->resize = array(
                        'thumbs' => array(
                            'height' => '20',
                            'scale' => FALSE),
                        'main' => array(
                            'height' => '265',
                            'width' => '400',
                            'scale' => FALSE)
                    );
                    break;
            case 3: $this->hiddenFields = array(
                            'idSeccion',
                            'idGaleria',
                            'twitter',
                            'facebook',
                            'direccion1',
                            'horario1',
                            'telefonos1',
                            'direccion2',
                            'horario2',
                            'telefonos2',
                            'email1',
                            'email2'
                        );
                     $this->imageFields = array(
                            'foto1',
                            'foto2',
                            'foto3',
                            'foto4'
                        );
                     $this->resize = array(
                        'thumbs' => array(
                            'height' => '20',
                            'scale' => FALSE),
                        'main' => array(
                            'height' => '142',
                            'width' => '215',
                            'scale' => FALSE)
                    );
                    break;
            case 4: $this->hiddenFields = array(
                            'idSeccion',
                            'foto2',
                            'foto3',
                            'titulo4',
                            'texto4',
                            'foto4',
                            'direccion1',
                            'horario1',
                            'telefonos1',
                            'direccion2',
                            'horario2',
                            'telefonos2',
                            'email1',
                            'email2',
                            'alt_img2',
                            'desc_img2',
                            'alt_img3',
                            'desc_img3',
                            'alt_img4',
                            'desc_img4',
                            'twitter',
                            'facebook'
                        );
                     $this->imageFields = array(
                            'foto1',
                        );
                     $this->resize = array(
                        'thumbs' => array(
                            'height' => '20',
                            'scale' => FALSE),
                        'main' => array(
                            'height' => '138',
                            'width' => '215',
                            'scale' => FALSE)
                    );
                    break;
            case 5: $this->hiddenFields = array(
                            'idSeccion',
                            'texto1',
                            'foto1',
                            'titulo2',
                            'texto2',
                            'foto2',
                            'titulo3',
                            'texto3',
                            'foto3',
                            'titulo4',
                            'texto4',
                            'foto4',
                            'direccion1',
                            'horario1',
                            'telefonos1',
                            'direccion2',
                            'horario2',
                            'telefonos2',
                            'email1',
                            'email2',
                            'alt_img1',
                            'desc_img1',
                            'alt_img2',
                            'desc_img2',
                            'alt_img3',
                            'desc_img3',
                            'alt_img4',
                            'desc_img4',
                            'twitter',
                            'facebook'
                        );
                    break;
            case 6: $this->hiddenFields = array(
                            'idSeccion',
                            'idGaleria',
                            'texto1',
                            'foto1',
                            'titulo2',
                            'texto2',
                            'foto2',
                            'titulo3',
                            'texto3',
                            'foto3',
                            'titulo4',
                            'texto4',
                            'foto4',
                            'direccion1',
                            'horario1',
                            'telefonos1',
                            'direccion2',
                            'horario2',
                            'telefonos2',
                            'email1',
                            'email2',
                            'alt_img1',
                            'desc_img1',
                            'alt_img2',
                            'desc_img2',
                            'alt_img3',
                            'desc_img3',
                            'alt_img4',
                            'desc_img4',
                            'twitter',
                            'facebook'
                        );
                    break;
            case 7: $this->hiddenFields = array(
                            'idSeccion',
                            'idGaleria',
                            'foto1',
                            'titulo3',
                            'texto3',
                            'foto3',
                            'titulo4',
                            'texto4',
                            'foto4',
                            'alt_img1',
                            'desc_img1',
                            'alt_img3',
                            'desc_img3',
                            'alt_img4',
                            'desc_img4',
                            'twitter',
                            'facebook'
                        );
                    $this->imageFields = array(
                                'foto2',
                            );
                    $this->resize = array(
                        'thumbs' => array(
                            'height' => '20',
                            'scale' => FALSE),
                        'main' => array(
                            'height' => '265',
                            'width' => '375',
                            'scale' => FALSE)
                    );
                    break;
                    break;
            case 8: $this->hiddenFields = array(
                            'idSeccion',
                            'idGaleria',
                            'idBanner',
                            'foto1',
                            'titulo',
                            'titulo1',
                            'foto2',
                            'texto2',
                            'texto3',
                            'texto4',
                            'foto3',
                            'foto4',
                            'direccion1',
                            'horario1',
                            'telefonos1',
                            'direccion2',
                            'horario2',
                            'telefonos2',
                            'email2',
                            'alt_img1',
                            'desc_img1',
                            'alt_img2',
                            'desc_img2',
                            'alt_img3',
                            'desc_img3',
                            'alt_img4',
                            'desc_img4',
                            'keywords',
                            'description',
                            'twitter',
                            'facebook'
                        );
                break;
            case 9: $this->hiddenFields = array(
                            'idSeccion',
                            'idGaleria',
                            'idBanner',
                            'foto1',
                            'titulo',
                            'titulo3',
                            'titulo4',
                            'foto2',
                            'texto2',
                            'texto1',
                            'texto4',
                            'foto3',
                            'foto4',
                            'direccion1',
                            'horario1',
                            'telefonos1',
                            'direccion2',
                            'horario2',
                            'telefonos2',
                            'email2',
                            'email1',
                            'alt_img1',
                            'desc_img1',
                            'alt_img2',
                            'desc_img2',
                            'alt_img3',
                            'desc_img3',
                            'alt_img4',
                            'desc_img4',
                            'keywords',
                            'description'
                        );
                break;
            case 10: $this->hiddenFields = array(
                            'idSeccion',
                            'idGaleria',
                            'foto1',
                            'titulo3',
                            'titulo4',
                            'foto2',
                            'texto2',
                            'texto1',
                            'texto4',
                            'foto3',
                            'foto4',
                            'direccion1',
                            'horario1',
                            'telefonos1',
                            'direccion2',
                            'horario2',
                            'telefonos2',
                            'email2',
                            'email1',
                            'alt_img1',
                            'desc_img1',
                            'alt_img2',
                            'desc_img2',
                            'alt_img3',
                            'desc_img3',
                            'alt_img4',
                            'desc_img4',
                            'titulo1',
                            'titulo2',
                            'texto3',
                            'twitter',
                            'facebook'
                        );
                break;
        }

        
    }
    function getTypeSelectBanner($name, $selected = false, $onchange = '', $id = false, $disabled = false) {
            $banners = new Banners();
            $output = '<select name="'.$name.'" id="'.$id.'"><option';
            if(!$selected)
                $output .= ' selected';
            $output .= '    disabled>Selecciona el banner</option>';
            foreach($banners->getAll() as $banner){
                $output .= '<option';
                if($selected == $banner['idBanner'])
                    $output .= ' selected';
                $output .= ' value="'.$banner['idBanner'].'">'.$banner['nombre'].'</option>';
            }
            $output .= '</select>';
            return $output;
        }
    function getTypeSelectGaleria($name, $selected = false, $onchange = '', $id = false, $disabled = false) {
            $galerias = new Galerias();
            $output = '<select name="'.$name.'" id="'.$id.'"><option';
            if(!$selected)
                $output .= ' selected';
            $output .= '    disabled>Selecciona la galería</option>';
            foreach((array)$galerias->getAll() as $galeria){
                $output .= '<option';
                if($selected == $galeria['idGaleria'])
                    $output .= ' selected';
                $output .= ' value="'.$galeria['idGaleria'].'">'.traducir($galeria['nombre']).'</option>';
            }
            $output .= '</select>';
            return $output;
        }

    

}

?>