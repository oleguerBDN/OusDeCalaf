<?php

require_once 'general_settings.php';

function ms_fridge($theString) {
    if (strpos($theString, '"') === FALSE) {
        $foo = EC2($theString);
    } else {
        $foo = EC($theString);
    }
    return $foo;
}

function EC($theString) {
    $foo = "'" . $theString . "'";

    return $foo;
}

function EC2($theString) {
    $foo = '"' . $theString . '"';

    return $foo;
}

function concatena($primera, $segona, $thechars) {
    if ($primera) {
        if ($segona) {
            $tot = $primera . $thechars . $segona;
        } else {
            $tot = $primera;
        }
    } else {
        if ($segona) {
            $tot = $segona;
        } else {
            $tot = '';
        }
    }
    return $tot;
}

function getSelectFromRecordSet(
$recordSet
, $name
, $traducible = false
, $selected = false
, $isEmpty = false
, $onchange = false
, $id = false
, $class = FALSE
, $disabled = FALSE
, $multiple = false
, $size = false
, $addable = FALSE
) {
    foreach ((array) $recordSet as $record) {
        if ($traducible) {
            $record[1] = translate($record[1]);
        }
        $thearray[$record[0]] = $record[1];
    }
    asort($thearray);
    $foo = getSelectFromArray($thearray, $name, $selected, $isEmpty, $onchange, $id, $class, $disabled, $multiple, $size, $addable);

    return $foo;
}

function getSelectFromArray(
$thearray
, $name
, $selected = false
, $isEmpty = false
, $onchange = false
, $id = false
, $class = FALSE
, $disabled = FALSE
, $multiple = false
, $size = false
, $addable = FALSE
) {
    if ($multiple) {
        $name .= '[]';
    }
    if (!$id) {
        $id = $name;
    }
    $foo = '<select data-rel="chosen" class="' . $class . '" id="' . $id . '" name="' . $name . '"';
    if ($onchange) {
        $foo .= ' onchange="' . $onchange . '"';
    }
    if ($disabled) {
        $foo.=' disabled ';
    }
    if ($multiple) {
        $foo.=' multiple ';
    }
    if ($size) {
        $foo.=' size="' . $size . '" ';
    } else {
        if ($multiple) {
            $foo.=' size="' . count($thearray) . '" ';
        }
    }
    $foo .= '>';

    if ($isEmpty) {
        if (is_numeric($isEmpty)) {
            $foo .= '<option value="' . $isEmpty . '"';
        } else {
            $foo .= '<option value="NULL"';
        }

        if (!$selected) {
            $foo .= ' selected';
        }
        $foo .= '>---------</option>';
    }

    if ($addable) {
        $foo .= '<option value="-1">' . lang('insert_new') . '</option>';
    }

    foreach ((array) $thearray as $id => $value) {

        $foo .= '<option value="' . $id . '"';
        if (is_array($selected)) {
            if (in_array($id, $selected)) {
                $foo .= ' selected';
            }
        } else {
            if ($id == $selected) {
                $foo .= ' selected';
            }
        }
        $foo .= '>' . $value . '</option>';
    }
    $foo .= '</select>';

    return $foo;
}

function getFotos($foto_dir) {
    $dir = @opendir($foto_dir);
    while ($file = @readdir($dir)) {
        $img_size = @getimagesize($foto_dir . $file);
        if ($img_size[0] && $img_size[1]) {
            $lesfotos[] = $file;
        }
    }
    @closedir($dir);
    ksort($lesfotos);

    return $lesfotos;
}

function stripSpaces($theString) {
    $nova = '';
    for ($i = 0; $i < strlen($theString); $i++) {
        $foo = substr($theString, $i, 1);
        if ($foo == ' ') {
            $nova .= '_';
        } else {
            $nova .= $foo;
        }
    }
    return $nova;
}

function saveFile($name, $tmp_name, $uploads_dir = '/userfiles/', $old_file = FALSE, $thumbnail_size = array(), $rename = TRUE) {
    
     /*echo '$name: ' . $name . DEBUG_ENTER;
      echo '$tmp_name: ' . $tmp_name . DEBUG_ENTER;
      echo '$uploads_dir: ' . $uploads_dir . DEBUG_ENTER;
      echo '$old_file: ' . $old_file . DEBUG_ENTER;
      echo '$thumbnail_size: ' . print_r($thumbnail_size) . DEBUG_ENTER;
      echo '$rename: ' . $rename . DEBUG_ENTER;
      die();*/
      if($name == ''){
        return FALSE;
    }
    if (!file_exists($uploads_dir)) {
        mkdir($uploads_dir);
    }
    if ($old_file) {
        @unlink($uploads_dir . $old_file);
    }
    $pathinfo = pathinfo($name);
    if ($rename) {
        $newname = 'file_' . str_replace('.', '', microtime(TRUE)) . '.' . $pathinfo['extension'];
    } else {
        $newname = $name;
    }

    if (move_uploaded_file($tmp_name, $uploads_dir . $newname)) {
        if (count($thumbnail_size) > 0 && $thumbnail_size != false) {
            foreach ((array) $thumbnail_size as $subfolder => $thumb) {
                if (!file_exists($uploads_dir . $subfolder)) {
                    mkdir($uploads_dir . $subfolder);
                }
                if ($old_file) {
                    @unlink($uploads_dir . $subfolder . '/' . $old_file);
                }
                switch ($pathinfo['extension']) {
                    case 'jpg':
                    case 'jpeg':
                    case 'JPG':
                    case 'JPEG':
                        $image = imagecreatefromjpeg($uploads_dir . $newname);
                        $thumb = resizingImage($image, $thumb['width'], $thumb['height'], $thumb['scale']);
                        imagejpeg($thumb, $uploads_dir . $subfolder . '/' . $newname);
                        break;
                    case 'png':
                    case 'PNG':
                        $image = imagecreatefrompng($uploads_dir . $newname);
                        $thumb = resizingImage($image, $thumb['width'], $thumb['height'], $thumb['scale'], $GLOBALS['png_background']);
                        imagepng($thumb, $uploads_dir . $subfolder . '/' . $newname);
                        break;
                    case 'gif':
                    case 'GIF':
                        $image = imagecreatefromgif($uploads_dir . $newname);
                        $thumb = resizingImage($image, $thumb['width'], $thumb['height'], $thumb['scale']);
                        imagegif($thumb, $uploads_dir . $subfolder . '/' . $newname);
                        break;
                }
            }
        }
        return $newname;
    } else {
        //die('mal');
        return false;
    }
}

function resizingImage($image, $max_width, $max_height, $scale = true, $bgColour = '') {
    // get the current dimensions of the image
    $src_width = imagesx($image);
    $src_height = imagesy($image);

    // if either max_width or max_height are 0 or null then calculate it proportionally
    if (!$max_width) {
        $max_width = $src_width / ($src_height / $max_height);
    } elseif (!$max_height) {
        $max_height = $src_height / ($src_width / $max_width);
    }

    // initialize some variables
    $thumb_x = $thumb_y = 0; // offset into thumbination image
    // if scaling the image calculate the dest width and height
    $dx = $src_width / $max_width;
    $dy = $src_height / $max_height;
    if ($scale == true) {
        $d = max($dx, $dy);
    }
    // otherwise assume cropping image
    else {
        $d = min($dx, $dy);
    }
    $new_width = $src_width / $d;
    $new_height = $src_height / $d;
    // sanity check to make sure neither is zero
    $new_width = max(1, $new_width);
    $new_height = max(1, $new_height);

    $thumb_width = min($max_width, $new_width);
    $thumb_height = min($max_height, $new_height);

    // if bgColour is an array of rgb values, then we will always create a thumbnail image of exactly
    // max_width x max_height
    if (is_array($bgColour)) {
        $thumb_width = $max_width;
        $thumb_height = $max_height;
        $thumb_x = ($thumb_width - $new_width) / 2;
        $thumb_y = ($thumb_height - $new_height) / 2;
    } else {
        $thumb_x = ($thumb_width - $new_width) / 2;
        $thumb_y = ($thumb_height - $new_height) / 2;
    }

    // create a new image to hold the thumbnail
    $thumb = imagecreatetruecolor($thumb_width, $thumb_height);
    if (is_array($bgColour)) {
        $bg = imagecolorallocate($thumb, $bgColour[0], $bgColour[1], $bgColour[2]);
        imagefill($thumb, 0, 0, $bg);
    }

    // copy from the source to the thumbnail
    imagecopyresampled($thumb, $image, $thumb_x, $thumb_y, 0, 0, $new_width, $new_height, $src_width, $src_height);
    return $thumb;
}

function multi_array_key_exists($needle = '', $haystack = array()) {
    foreach ((array) $haystack as $key => $value) {
        if (strval($needle) == strval($key)) {
            return true;
        }
        if (is_array($value)) {
            if (multi_array_key_exists($needle, $value) === true) {
                return $key;
            }
        } else {
            continue;
        }
    }
    return false;
}

function array_intval_pos($needle, $haystack) {
    $count = 0;
    foreach ((array) $haystack as $key => $value) {
        if ($key == $needle) {
            return $count;
        }
        $count++;
    }
    return false;
}

function clone_folder($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ( $file = readdir($dir))) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if (is_dir($src . '/' . $file)) {
                recurse_copy($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

function getRandomPwd() {
    $password = '';
    for ($i = 1; $i <= 8; $i++) {
        $type = rand(1, 3);
        switch ($type) {
            case '1':
                $password .=chr(rand(48, 57));
                break;
            case '2':
                $password .=chr(rand(65, 90));
                break;
            case '3':
                $password .=chr(rand(97, 122));
                break;
        }
    }
    return $password;
}

function getCaptcha($number1, $number2=0, $separator = '', $imput_id = 'codigo') {
    if ($number2 == 0) {
        $image = '<label for="' . $imput_id . '"><img id="siimage" src="' . BASE_URL . 'includes/captcha/securimage_show.php?length=' . $number1 . '&sid=' . md5(time()) . '" /></label>';
    } else {
        $image = '<label for="' . $imput_id . '"><img id="siimage" src="' . BASE_URL . 'includes/captcha/securimage_show.php?rand1=' . $number1 . '&rand2=' . $number2 . '&sid=' . md5(time()) . '" /></label>';
    }

    /* $output .= '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="19" height="19" id="SecurImage_as3" align="middle">
      <param name="allowScriptAccess" value="sameDomain"/>
      <param name="allowFullScreen" value="false"/>
      <param name="movie" value="includes/captcha/securimage_play.swf?audio=includes/captcha/securimage_play.php&bgColor1=#777&bgColor2=#fff&iconColor=#000&roundedCorner=5" />
      <param name="quality" value="high"/>
      <param name="bgcolor" value="#ffffff"/>
      <embed src="includes/captcha/securimage_play.swf?audio=captcha/securimage_play.php&bgColor1=#777&bgColor2=#fff&iconColor=#000&roundedCorner=5" quality="high" bgcolor="#ffffff" width="19" height="19" name="SecurImage_as3" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
      </object><br/>'. */

    if ($number2 == 0) {
        $refresh = '<a href="" title="Cambiar im&aacute;gen" alt="Cambiar im&aacute;gen" onclick="document.getElementById(\'siimage\').src = \'' . BASE_URL . 'includes/captcha/securimage_show.php?length=' . $number1 . '&sid=\' + Math.random(); return false">
                        <img src="' . BASE_URL . 'includes/captcha/images/refresh.gif" onclick="this.blur()"/>
                    </a>';
    } else {
        $refresh = '<a href="" title="Cambiar im&aacute;gen" alt="Cambiar im&aacute;gen" onclick="document.getElementById(\'siimage\').src = \'' . BASE_URL . 'includes/captcha/securimage_show.php?rand1=' . $number1 . '&rand2=' . $number2 . '&sid=\' + Math.random(); return false">
                        <img src="' . BASE_URL . 'includes/captcha/images/refresh.gif" onclick="this.blur()"/>
                    </a>';
    }

    $input = '<input type="text" value="' . lang('Code') . '" id="' . $imput_id . '" name="code"  onfocus="clearInput(this.id, \'' . lang('Code') . '\')" onblur="restoreInput (this.id, \'' . lang('Code') . '\')">';

    switch ($separator) {
        case 'td':
            $output = '<td class="captcha">' . $image . '</td><td>' . $input . '</td><td>' . $refresh . '</td>';
            break;
        default:
            $output = $image . $separator . $refresh . $separator . $input;
            break;
    }

    return $output;
}

function checkCaptcha($code) {
    include 'captcha/securimage.php';
    $img = new Securimage();
    $valid = $img->check($code);
    return $valid;
}

function sendEmail($to, $from, $subject_mail, $html, $text = '', $attachments = array()) {
    require_once BASE_PATH . 'includes/mailer/class.phpmailer.php';
    $php_mailer = new PHPMailer(true);
    $ok = false;
    try {
        $php_mailer->ClearAddresses();
        $php_mailer->CharSet = 'UTF-8';
        $php_mailer->IsSMTP();
        $php_mailer->SMTPAuth = true;
        $php_mailer->Host = HOST;
        $php_mailer->Username = USERNAME;;
        $php_mailer->Password = PASSWORD;
        $php_mailer->From = CORREO;
        $php_mailer->FromName = $from;
        $php_mailer->AddAddress($to);
        $php_mailer->Subject = $subject_mail;

        if ($html) {
            $php_mailer->Body = $html;
            $php_mailer->AltBody = $text;
            $php_mailer->IsHTML(true);
        } else {
            $php_mailer->Body = $html;
        }

        $php_mailer->WordWrap = 80;
        if (count((array) $attachments) > 0) {
            foreach ((array) $attachments as $attachment) {
                $php_mailer->AddAttachment($attachment);
            }
        }
        $ok = $php_mailer->Send();
    } catch (phpmailerException $e) {
        echo $e->errorMessage();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    echo $e;
    return $ok;
}

function changeLang($langIso) {
    $literals = new Literals();
    $branch = explode('/', $_GET['url']);
    foreach ((array) $branch as $page) {
        $sections = new Sections();
        $sectionId = $sections->getSectionId($page);
        if ($sectionId == FALSE) {
            $products = new Shop_itemgroupdetails();
            $productId = $products->getProductId($page);
            if ($productId == FALSE) {
                $packs = new Shop_packs();
                $packId = $packs->getPackId($page);
                $thePack = new Shop_packs($packId);
                $newPage = $literals->getLiteral($thePack->packsUrlFk, $langIso);
            } else {
                $theProduct = new Shop_itemgroupdetails($productId);
                $newPage = $literals->getLiteral($theProduct->detailUrlFk, $langIso);
            }
        } else {
            $theSection = new Sections($sectionId);
            $newPage = $literals->getLiteral($theSection->sectionUrlFk, $langIso);
        }
        $newUrl = concatena($newUrl, $newPage, '/');
    }
    $url = BASE_URL . $langIso . '/' . $newUrl;
    return $url;
}

function getCurrentUrl() {
    $url = (!empty($_SERVER['HTTPS'])) ? "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] : "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    return $url;
}

function checkURL($branch, $iso) {
    $ok = true;
    $langs = new Langs();
    $sectionData = new Sections();
    $itemGroupDetails = new Shop_itemgroupdetails();
    $items = new Shop_items;
    $packsData = new Shop_packs();
    if ($langs->checkLang($iso)) {
        $situacion = $branch[count($branch) - 1];
        $anterior = $branch[count($branch) - 2];
        if ($sectionData->getSectionId($situacion, $anterior) != '') {
            $isSection = TRUE;
        }
        for ($i = (count($branch) - 1); $i >= 0; $i--) {
            $situacion = $branch[$i];
            $anterior = $branch[$i - 1];
            if ($sectionData->getSectionId($situacion, $anterior) != '') {
                $situacion = 'section';
            } elseif ($itemGroupDetails->getProductId($situacion)) {
                $situacion = 'item';
            } elseif ($packsData->getPackId($situacion)) {
                $situacion = 'pack';
            } elseif ($items->checkColorCode($situacion)) {
                $situacion = 'color';
                if ($productId = $itemGroupDetails->getProductId($branch[$i - 1])) {
                    //Es un producto
                    if ($items->checkItemColor($branch[$i], $branch[$i - 1])) {
                        //die('El producto no esta en este color');
                        $ok = false;
                    }
                    if ($items->checkColorPublish($branch[$i], $productId) == FALSE) {
                        //die('El color no esta publicado');
                        $ok = false;
                    }
                    $productMotherSections = $itemGroupDetails->getProductMotherSections($productId);
                    $motherSection = $sectionData->getSectionId($branch[$i - 2], $branch[$i - 3]);
                    if (!in_array($motherSection, $productMotherSections)) {
                        //die('El producto no pertenece a la seccion');
                        $ok = false;
                    }
                    $itemGroupDetails->loadItemgroupdetails($productId);
                    if ($itemGroupDetails->detailPublish != 1) {
                        //die('El producto no esta publicado');
                        $ok = false;
                    }
                } else {
                    //die('El producto no esta publicado');
                    $ok = false;
                }
            } else {
                $situacion = 'error';
            }
            //die('La pagina es un/a '.$situacion);
            if ($situacion != 'color') {
                if ($langs->checkUrlLang($branch[$i], $iso)) {
                    switch ($situacion) {
                        case 'section':
                            $sectionId = $sectionData->getSectionId($branch[$i], $branch[$i - 1]);
                            if ($i == 0) {
                                $motherSection = $sectionId;
                            } else {
                                $motherSection = $sectionData->getSectionId($branch[$i - 1], $branch[$i - 2]);
                            }
                            if (!$sectionData->getMotherSection($sectionId, $motherSection)) {
                                //die('La seccion madre no es correcta: seccion =>' . $sectionId . ', madre =>' . $motherSection);
                                $ok = false;
                            }
                            $sectionData->loadSections($sectionId);
                            if ($isSection == TRUE && $sectionData->sectionPublish != 1) {
                                //die('La seccion no esta publicada');
                                $ok = false;
                            }
                            break;
                        case 'item':
                            $productId = $itemGroupDetails->getProductId($branch[$i]);
                            $productMotherSections = $itemGroupDetails->getProductMotherSections($productId);
                            $motherSection = $sectionData->getSectionId($branch[$i - 1], $branch[$i - 2]);
                            if (!in_array($motherSection, $productMotherSections)) {
                                //die('El producto no pertenece a la seccion');
                                $ok = false;
                            }
                            $itemGroupDetails->loadItemgroupdetails($productId);
                            if ($itemGroupDetails->detailPublish != 1) {
                                //die('El producto no esta publicado');
                                $ok = false;
                            }
                            break;
                        case 'pack':
                            $packId = $packsData->getPackId($branch[$i]);
                            if ($packId == '') {
                                //die('No es una seccion, ni un producto, ni un pack. URL incorrecta');
                                $ok = false;
                            } else {
                                $packsData->loadPacks($packId);
                                $packmotherSection = $packsData->packsSectionFk;
                                $motherSection = $sectionData->getSectionId($branch[$i - 1], $branch[$i - 2]);
                                if ($packmotherSection != $motherSection) {
                                    //die('El pack no pertenece a la seccion');
                                    $ok = false;
                                }
                                if ($packsData->packsPublish != 1) {
                                    //die('El pack no esta publicado');
                                    $ok = false;
                                }
                            }
                            break;
                        case 'error':
                            //die('El idioma y la URL no coinciden o la seccion, item, pack o color no existe');
                            $ok = false;
                            break;
                    }
                } else {
                    //die('El idioma y la URL no coinciden o el producto no existe');
                    $ok = false;
                }
            }
        }
    } else {
        //die('El idioma no existe');
        $ok = false;
    }
    return $ok;
}

function generateUrl($id, $section = false) {

    if ($section) {
        //seccion
        $tree = $GLOBALS['sections']->getMothersArray($id);

        krsort($tree);

        $url = false;
        foreach ($tree as $key => $value) {
            $url = concatena($url, $value, '/');
        }
    } else {
        //producto

        $item_data = $GLOBALS['items']->getOne($id, 'itemUrlFk, itemItemFk');
        if($item_data['itemItemFk'] > 0
                && $item_data['itemItemFk'] != $id){
            $id = $item_data['itemItemFk'];
        }
        $url = strtolower(lang('products')) . '/' . $id . '/' . translate($item_data['itemUrlFk']);
    }

    $url = BASE_URL . $_SESSION['lang'] . '/' . $url;
    return $url;
}

function error404() {
    require_once BASE_PATH . '404.html';
    die();
}

function customFileSize($file, $setup = null) {
    $FZ = ($file && @is_file($file)) ? filesize($file) : NULL;
    $FS = array("B", "kB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB");

    if (!$setup && $setup !== 0) {
        return number_format($FZ / pow(1024, $I = floor(log($FZ, 1024))), ($i >= 1) ? 2 : 0) . ' ' . $FS[$I];
    } elseif ($setup == 'INT')
        return number_format($FZ);
    else
        return number_format($FZ / pow(1024, $setup), ($setup >= 1) ? 2 : 0) . ' ' . $FS[$setup];
}

function shareFacebook($url, $image, $title) {
    $output = '<div id="fb-root"></div>
                <script>(function(d, s, id) {
                  var js, fjs = d.getElementsByTagName(s)[0];
                  if (d.getElementById(id)) return;
                  js = d.createElement(s); js.id = id;
                  js.src = "//connect.facebook.net/es_ES/all.js#xfbml=1&appId=169912653088134";
                  fjs.parentNode.insertBefore(js, fjs);
                }(document, \'script\', \'facebook-jssdk\'));</script>

                <div class="fb-like" 
                    data-href="' . $url . '"
                    data-send="false"
                    data-layout="button_count"
                    data-width="90"
                    data-show-faces="true"
                    data-action="recommend"
                    data-font="arial">
                </div>
                <meta property="og:site_name" content="' . PROJECT_NAME . '"/>
                <meta property="og:title" content="' . $title . '" />
                <meta property="og:type" content="product" />
                <meta property="og:url" content="' . $url . '" />
                <meta property="og:image" content="' . $image . '" />
                <link rel="image_src" href="' . $image . '"/> 
                <meta property="fb:admins" content="1393354164" />
                ';
    return $output;
}

function tweetButton() {
    if ($GLOBALS['settings']['account_twitter']) {
        $output = '<a href="https://twitter.com/share" 
            class="twitter-share-button" 
            data-via="' . $GLOBALS['settings']['account_twitter'] . '" 
            data-lang="' . $_SESSION['lang'] . '" 
            data-hashtags="neoprenos"
            >' . lang('twittear') . '</a>

            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
        return $output;
    }
}

function sendToAFriend($windowId, $url, $titleFk, $bodyFk) {
    $output = '<script type="text/javascript">
            $(function() {
                $( "#dialog:ui-dialog" ).dialog( "destroy" );
                $( "#' . $windowId . '" ).dialog({
                    autoOpen: false,
                    height: 400,
                    width: 400,
                    modal: true,
                    buttons: {
                        "' . lang('submit') . '": function() {
                            var name = document.getElementById("send_your_name_' . $windowId . '");
                            var email = document.getElementById("send_your_email_' . $windowId . '");
                            var to = document.getElementById("send_friend_email_' . $windowId . '");
                            var text = document.getElementById("send_text_' . $windowId . '");
                            $.ajax({
                                type: "GET",
                                url: "' . BASE_URL . 'ajax/sendToAFriend.php",
                                data: "name="+name.value+"&email="+email.value+"&to="+to.value+"&text="+text.value+"&url=' . $url . '&titlefk=' . $titleFk . '&bodyfk=' . $bodyFk . '",
                                success: function(msg){
                                    alert("' . lang('mail_enviado') . '");
                                    name.value = "";
                                    email.value = "";
                                    to.value = "";
                                    text.value = "";
                                }
                            });
                            $( this ).dialog( "close" );
                        },
                        "' . lang('close') . '": function() {
                            $( this ).dialog( "close" );
                        }
                    },
                    close: function() {
                    }
                });
            });
        </script>
        <div id="' . $windowId . '" title="' . lang('send_to_friend') . '">
            <table class="contacto_thickbox">
                <tr>
                    <td class="label">' . lang('your_name') . '</td>
                    <td><input type="text" id="send_your_name_' . $windowId . '"/></td>
                </tr>
                <tr>
                    <td class="label">' . lang('your_email') . '</td>
                    <td><input type="text" id="send_your_email_' . $windowId . '"/></td>
                </tr>
                <tr>
                    <td class="label">' . lang('friend_email') . '</td>
                    <td><input type="text" id="send_friend_email_' . $windowId . '"/></td>
                </tr>
                <tr>
                    <td colspan="2"><textarea rows="10" id="send_text_' . $windowId . '"></textarea></td>
                </tr>
            </table>
        </div>
        <input type="button" class="send-friend" onclick="openModalWindow(' . EC($itemData->itemId) . ', ' . EC($windowId) . ')" value="' . lang('send_to_friend') . '"/>';
    return $output;
}

function getShippingCost($subtotal, $impuestofinal) {
    $without_ship = $subtotal + $impuestofinal;
    if ($without_ship > 0) {
        if ($without_ship >= $GLOBALS['settings']['send_free']) {
            $shipping = 0;
        } elseif ($_SESSION['public_user']['shippingCountry'] == $GLOBALS['settings']['default_country'] || !$_SESSION['public_user']['shippingCountry']) {
            $shipping = $GLOBALS['settings']['send_spain'];
        } else {
            $shipping = $GLOBALS['settings']['send_europe'];
        }
    }
    return $shipping;
}

function sermepa($order_id, $amount, $url_tpvv, $code, $terminal, $currency, $clave) {

    $token = getToken();
    $order = new Shop_orders();
    $order->addToken($order_id, $token);

    $amount = round($amount, 2);
    $amount = $amount * 100;
    $order = date('y') . sprintf('%06s', $order_id) . sprintf('%04s', $_SESSION['public_user']['PublicUserId']);
    $transactionType = '0';
    $urlMerchant = BASE_URL;
    $urlOK = BASE_URL . '?p=1&o=' . $token . '&so=' . $order;
    $urlKO = BASE_URL . '?p=0&o=' . $token;
    $producto = 'Pago pedigo Nº:' . $order;

    $message = $amount . $order . $code . $currency . $transactionType . $urlMerchant . $clave;
    $signature = strtoupper(sha1($message));

    $fields = array(
        'Ds_Merchant_Amount' => $amount,
        'Ds_Merchant_Currency' => $currency,
        'Ds_Merchant_Order ' => $order,
        'Ds_Merchant_MerchantCode' => $code,
        'Ds_Merchant_Terminal' => $terminal,
        'Ds_Merchant_TransactionType' => $transactionType,
        'Ds_Merchant_MerchantURL' => $urlMerchant,
        'Ds_Merchant_MerchantSignature' => $signature,
        'Ds_Merchant_UrlOK' => $urlOK,
        'Ds_Merchant_UrlKO' => $urlKO,
        'Ds_Merchant_ProductDescription' => $producto,
        'Ds_Merchant_PayMethods' => 'T'
    );

    foreach ($fields as $n => $v) {
        $form .= '<input type=hidden name="' . $n . '" value="' . $v . '"/>';
    }

    $output = '<html>' .
            '<body onload="document.theform.submit();">' .
            '<form name="theform" action="' . $url_tpvv . '" method="post">' . $form . '</form>' .
            lang('connection_sermepa') .
            '</body></html>';

    return $output;
}

function paypal($order_id, $session_cart, $shipping) {

    $token = getToken();
    $order = new Shop_orders();
    $order->addToken($order_id, $token);

    $form = '<input type="hidden" name="cmd" value="_cart">
            <input type="hidden" name="notify_url" value="' . BASE_URL . '?p=1&o=' . $token . '">
            <input type="hidden" name="upload" value="1">
            <input type="hidden" name="business" value="' . $GLOBALS['settings']['account_paypal'] . '">
            <input type="hidden" name="currency_code" value="EUR">
            <input type="hidden" name="weight_unit" value="kgs">';

    $counter = 0;
    if ($shipping > 0) {
        $form .= '<input type="hidden" name="item_name_1" value="' . lang('shipping') . '">
            <input type="hidden" name="amount_1" value="' . $shipping . '">';
        $counter++;
    }
    $tax = new Shop_tax();
    $shop_item = new Shop_items();
    $packitems = new Shop_packitem();
    $pack = new Shop_packs();
    $itemgroup = new Shop_itemgroupdetails();

    foreach ((array) $session_cart as $key => $product) {
        if (intval($key)) {
            $counter++;
            $shop_item->loadItem($key);
            $total = implementTax($product['price'], $tax->getTaxPercent($product['tax'], $_SESSION['public_user']['shippingCountry']));
            $form .= '
                <input type="hidden" name="item_name_' . $counter . '" value="' . translate($shop_item->itemModelFk) . '">
                <input type="hidden" name="amount_' . $counter . '" value="' . round($total, 2) . '">
                <input type="hidden" name="discount_rate_' . $counter . '" value="' . $product['discount'] . '">
                <input type="hidden" name="quantity_' . $counter . '" value="' . $product['quantity'] . '">';
        } else {

            foreach ((array) $product as $key => $pack_in_cart) {
                $item_price = 0;
                foreach ($pack_in_cart['productos'] as $detail => $item_chosed) {
                    foreach ($item_chosed as $the_itemid => $the_discount) {
                        $item_chosed_price = $packitems->getPackItemFinalPrice($pack_in_cart['packId'], $the_itemid);
                        $item_price = $item_price + $item_chosed_price;
                    }
                }
                $pack->loadPacks($pack_in_cart['packId']);

                $counter++;
                $form .= '
                    <input type="hidden" name="item_name_' . $counter . '" value="' . translate($pack->packsTitleFk) . '">
                    <input type="hidden" name="amount_' . $counter . '" value="' . round($item_price, 2) . '">
                    <input type="hidden" name="discount_rate_' . $counter . '" value="0">
                    <input type="hidden" name="quantity_' . $counter . '" value="1">';
            }
        }
    }


    $output = '<html>' .
            '<body onload="document.theform.submit();">' .
            //'<body>' .
            '<form name="theform" action="https://www.paypal.com/cgi-bin/webscr" method="post">' . $form . '</form>' .
            lang('connection_paypal') .
            '</body></html>';

    $order->closeConnection();
    $tax->closeConnection();
    $shop_item->closeConnection();
    $packitems->closeConnection();
    $pack->closeConnection();
    $itemgroup->closeConnection();

    return $output;
}

function orderMultiDimensionalArray($toOrderArray, $field, $inverse = false) {
    $position = array();
    $newRow = array();
    foreach ((array) $toOrderArray as $key => $row) {
        $position[$key] = $row[$field];
        $newRow[$key] = $row;
    }
    if ($inverse) {
        arsort($position);
    } else {
        asort($position);
    }
    $returnArray = array();
    foreach ((array) $position as $key => $pos) {
        $returnArray[] = $newRow[$key];
    }
    return $returnArray;
}

function ITR($string) {
    $output = '<tr><td>' . $string . '</td></tr>';
    return $output;
}

function getToken() {
    return md5(time());
}

function strtourl($string) {
    $string = utf8_encode($string);
    $string = strtolower($string);
    $string = trim($string);
    $a = array("ã", "À", "Á", "Ä", "Â", "È", "É", "Ë", "Ê", "Ì", "Í", "Ï", "Î", "Ò", "Ó", "Ö", "Ô", "Ù", "Ú", "Ü", "Û", "á", "é", "í", "ó", "ú", "à", "è", "ì", "ò", "ù", "ä", "ë", "ï", "ö", "ü", "â", "ê", "î", "ô", "û", "ñ", "ç", " ", "/", "'");
    $b = array("a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "u", "u", "u", "u", "a", "e", "i", "o", "u", "a", "e", "i", "o", "u", "a", "e", "i", "o", "u", "a", "e", "i", "o", "u", "n", "c", "-", "-", "");
    $string = str_replace($a, $b, $string);
    $string = utf8_decode($string);
    $string = preg_replace('/[^a-z0-9-]/', '', $string);
    return $string;
}

function urlAvailable($url, $mother, $literalGroup) {

    $section = new Sections();
    $rsection = $section->getUrlAvailability($url, $mother, $literalGroup);

    $item = new Shop_itemgroupdetails();
    $ritem = $item->getUrlAvailability($url, $mother, $literalGroup);

    $packs = new Shop_packs();
    $rpacks = $packs->getUrlAvailability($url, $mother, $literalGroup);

    if (count($rsection) < 1 && count($ritem) < 1 && count($rpacks) < 1) {
        return 'ok';
    } else {
        return 'ko';
    }
}

function putSpaces($count, $string) {
    $spaces = '';
    for ($index = 0; $index < $count; $index++) {
        $spaces .= '&nbsp;&nbsp;&nbsp;';
    }

    return $spaces . $string;
}

function margin($count) {
    $margin = 4;
    for ($index = 0; $index < $count; $index++) {
        $margin = $margin + 16;
    }

    return $margin;
}

function getMonthName($monthNumber) {
    switch ($monthNumber) {
        case 01:
            $monthName = lang('ene');
            break;
        case 02:
            $monthName = lang('feb');
            break;
        case 03:
            $monthName = lang('mar');
            break;
        case 04:
            $monthName = lang('abr');
            break;
        case 05:
            $monthName = lang('may');
            break;
        case 06:
            $monthName = lang('jun');
            break;
        case 07:
            $monthName = lang('jul');
            break;
        case 08:
            $monthName = lang('ago');
            break;
        case 09:
            $monthName = lang('sep');
            break;
        case 10:
            $monthName = lang('oct');
            break;
        case 11:
            $monthName = lang('nov');
            break;
        case 12:
            $monthName = lang('dic');
            break;
    }
    return $monthName;
}

function makeOff($original_num, $discount_percent) {
    return $original_num * ((100 - $discount_percent) / 100);
}

function implementTax($original_num, $tax_type) {
    $tax = new Item_tax_percents();

    if ($_SESSION['public_user']['userSendCountryFk'] < 1) {
        $country = $GLOBALS['settings']['default_country'];
    } else {
        $country = $_SESSION['public_user']['userSendCountryFk'];
    }

    $rs = $tax->getAll('taxNumPercent', 'taxNumCountryFk = ' . $country . ' AND taxNumTypeFk = ' . EC($tax_type));
    
    return $original_num * (($rs[0]['taxNumPercent'] / 100) + 1);
}

function couponMakeOff($original_num, $manufacturer = false, $item = false) {
    if ($_SESSION['public_user']['couponItemFk'] > 0) {
        if ($item == $_SESSION['public_user']['couponItemFk']) {
            return makeOff($original_num, $_SESSION['public_user']['coupon_discount']);
        }
    } elseif ($_SESSION['public_user']['couponManufacturerFk'] > 0) {
        if ($manufacturer == $_SESSION['public_user']['couponManufacturerFk']) {
            return makeOff($original_num, $_SESSION['public_user']['coupon_discount']);
        }
    } elseif ($_SESSION['public_user']['coupon_discount'] > 0) {
        return makeOff($original_num, $_SESSION['public_user']['coupon_discount']);
    }
    return $original_num;
}

function getHourSelect($nom, $selected = false, $onchange = '', $slotMinutes = 30, $ini_hour = false) {

    $start_hour = strtotime('00:00');
    $finish_hour = $start_hour + (24 * 60 * 60);

    if ($ini_hour) {
        $start_hour = strtotime($ini_hour);
    }

    $thearray = array();
    $hour = $start_hour;

    while ($hour < $finish_hour) {
        $thearray[date("H:i", $hour)] = date("H:i", $hour);
        $hour = $hour + ($slotMinutes * 60);
    }

    return getSelectFromArray($thearray, $nom, $selected, $onchange);
}

function microseconds() {
    list($usec, $sec) = explode(" ", microtime());
    list($zero, $microsecs) = explode(".", $usec);
    return $microsecs;
}

function insertDebugLog($text) {
    if (FUNCTION_DEBUG) {
        $date = date('H:i:s:') . microseconds() . ' => ';
        $gestor = fopen(BASE_PATH . 'logs/debug_log.txt', 'a+');
        fwrite($gestor, $date . $text . ";\n");
        fclose($gestor);

        if (SQL_DEBUG) {
            $date = date('H:i:s:') . microseconds() . ' => ';
            $gestor = fopen(BASE_PATH . 'logs/combined_log.txt', 'a+');
            fwrite($gestor, "\n\n" . $date . $text . ";\n\n");
            fclose($gestor);
        }
    }
}



function cleanDebugLog() {
    if (FUNCTION_DEBUG) {
        $gestor = fopen(BASE_PATH . 'logs/debug_log.txt', 'w+');
        fclose($gestor);
    }
    if (SQL_DEBUG) {
        $gestor = fopen(BASE_PATH . 'logs/sql_log.txt', 'w+');
        fclose($gestor);
    }
    if (SQL_DEBUG && FUNCTION_DEBUG) {
        $gestor = fopen(BASE_PATH . 'logs/combined_log.txt', 'w+');
        fclose($gestor);
    }
}

function getLangBar(){
        if($_SESSION['lang'] == 'ca'){
                $def_lang_ca = 'class="active"';
            }
            else{
                $def_lang_es= 'class="active"';
                $_SESSION['lang'] == 'es';
            }
        $output = '<li '.$def_lang_ca.'><a title="" href="'.BASE_URL.'index.php?lang=ca">CAT</a></li>
                    <li>|</li>
                    <li '.$def_lang_es.'><a title="" href="'.BASE_URL.'index.php?lang=es">ES</a></li>';
    
    return $output;
}
function getSeccionLinks($num){
        $secciones = new Secciones();
        $sections = $secciones->getAll();
        if ($num == 23){
            $num = $sections[0]['idSeccion'];
        }
        $output = '<ul>
        <li';
        if($num == $sections[0]['idSeccion'])
            $output .= ' class="active"';
        $output .= '><a title="" href="'.BASE_URL.''.$_SESSION['lang'].'"><span>'.traducir($sections[0]['mintitulo_fk']).'</span><i>'.traducir($sections[0]['mindescripcion_fk']).'</i></a></li>
        <li';
        if($num == $sections[1]['idSeccion'])
            $output .= ' class="active"';
        $output .= '><a title="" href="'.BASE_URL.''.$_SESSION['lang'].'/servicios"><span>'.traducir($sections[1]['mintitulo_fk']).'</span><i>'.traducir($sections[1]['mindescripcion_fk']).'</i></a></li>
        <li';
        if($num == $sections[2]['idSeccion'])
            $output .= ' class="active"';
        $output .= '><a title="" href="'.BASE_URL.''.$_SESSION['lang'].'/equipo"><span>'.traducir($sections[2]['mintitulo_fk']).'</span><i>'.traducir($sections[2]['mindescripcion_fk']).'</i></a></li>
        <li';
        if($num == $sections[3]['idSeccion'])
            $output .= ' class="active"';
        $output .= '><a title="" href="'.BASE_URL.''.$_SESSION['lang'].'/abogado-online"><span>'.traducir($sections[3]['mintitulo_fk']).'</span><i>'.traducir($sections[3]['mindescripcion_fk']).'</i></a></li>
        <li';
        if($num == $sections[4]['idSeccion'])
            $output .= ' class="active"';
        $output .= '><a title="" target="_blank" href="http://ibisum.blogspot.com.es/"><span>'.traducir($sections[4]['mintitulo_fk']).'</span><i>'.traducir($sections[4]['mindescripcion_fk']).'</i></a></li>
        <li';
        if($num == $sections[5]['idSeccion'])
            $output .= ' class="active"';
        $output .= '><a title="" href="'.BASE_URL.''.$_SESSION['lang'].'/contacto"><span>'.traducir($sections[5]['mintitulo_fk']).'</span><i>'.traducir($sections[5]['mindescripcion_fk']).'</i></a></li>
        </ul>';
    
    return $output;
}
function getLangUrl(){
    if($_GET['lang'])
    switch ($_GET['lang']){
        case 'ca': $_SESSION['lang'] = 'ca';
                break;
        case 'es': $_SESSION['lang'] = 'es';
                break;
    }
    else{
        $_SESSION['lang'] = 'es'; 
    }
}

function getSocialLinks(){
    $redes = new Redessociales();
    $sql = 'SELECT * FROM redessociales';
    $redes = $redes->getRecord($sql);
    $output = '<li class="rss"><a title="" href="" target="_blank">RSS de ous</a></li>
      <li class="twitter"><a title="" href="'.$redes['twitter'].'" target="_blank">Ous en Twitter</a></li>
      <li class="facebook"><a title="" href="'.$redes['facebook'].'" target="_blank">Ous en Facebook</a></li>
      <li class="youtube"><a title="" href="'.$redes['youtube'].'" target="_blank">Ous en Youtube</a></li>
      <li class="linkedin"><a title="" href="'.$redes['linkedin'].'" target="_blank">Ous en Linkedin</a></li>';
    return $output;
}

function getFooterLinks(){
    $redes = new Redessociales();
    $sql = 'SELECT * FROM redessociales';
    $redes = $redes->getRecord($sql);
    $output = '<li><a title="" href="">IBISUM</a></li>
      <li>© 2013</li>
      <li>-</li>
      <li><a title="'.ucfirst(lang('Aviso legal')).'" class="cbox" rel="nofollow" href="'.BASE_URL.'ajax/aviso_'.$_SESSION['lang'].'.php">'.ucfirst(lang('Aviso legal')).'</a></li>
      <li>-</li>
      <li><a title="'.ucfirst(lang('Contacta con nosotros')).'" href="'.BASE_URL.$_SESSION['lang'].'/contacto">'.ucfirst(lang('Contacta con nosotros')).'</a></li>';
    return $output;
}
function extension($filename){
    return substr(strrchr($filename, '.'), 1);
}
function getBannerImgs($img,$c){
    $output .= '<div id="banner_'.$c.'"><fieldset><legend></legend>
                <div><a class="btn btn-danger" onClick="delImg('.$img['idImgBanner'].',-1);">
                            <i class="fa-icon-trash"></i> 
                            </a></div>';
    $output .=  '<div class="control-group">
                       <label class="control-label" for="df">Alt img</label>
                       <div class="controls">
                            <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                                <li class="active"><a href="#Castellano'.$c.'" data-toggle="tab">Castellano</a></li>
                                <li><a href="#Catalan'.$c.'" data-toggle="tab">Catalan</a></li>
                            </ul>
                            <div id="my-tab-content" class="tab-content">
                                <div class="tab-pane active" id="Castellano'.$c.'">
                                    <div class="control-group">
                                    <div class="controls" style="margin-left:0px;">
                                    <input class="input-xlarge focused" id="alt_img_1" name="banner_img['.$c.'][alt_img][1]" type="text" value="'.traducires($img['alt_img']).'">
                                </div></div></div>
                                <div class="tab-pane" id="Catalan'.$c.'">
                                    <div class="control-group">
                                    <div class="controls" style="margin-left:0px;">
                                    <input class="input-xlarge focused" id="alt_img_2" name="banner_img['.$c.'][alt_img][2]" type="text" value="'.traducirca($img['alt_img']).'">
                                </div></div></div>
                             </div>   
                            <input value="'.$img['alt_img'].'" type="hidden" name="banner_img['.$c.'][alt_img][itemLiteralId]" type="text">
                       </div>
                   </div>';
    
    $output .=     '<div class="control-group">
                       <label class="control-label" for="df">Desc img</label>
                       <div class="controls">
                            <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                                <li class="active"><a href="#Castellano'.($c + 1).'" data-toggle="tab">Castellano</a></li>
                                <li><a href="#Catalan'.($c + 1).'" data-toggle="tab">Catalan</a></li>
                            </ul>
                            <div id="my-tab-content" class="tab-content">
                                <div class="tab-pane active" id="Castellano'.($c + 1).'">
                                    <div class="control-group">
                                    <div class="controls" style="margin-left:0px;">
                                    <input class="input-xlarge focused" id="desc_img_1" name="banner_img['.$c.'][desc_img][1]" type="text" value="'.traducires($img['desc_img']).'">
                                </div></div></div>
                                <div class="tab-pane" id="Catalan'.($c + 1).'">
                                    <div class="control-group">
                                    <div class="controls" style="margin-left:0px;">
                                    <input class="input-xlarge focused" id="desc_img_2" name="banner_img['.$c.'][desc_img][2]" type="text" value="'.traducirca($img['desc_img']).'">
                                </div></div></div>
                             </div>   
                            <input value="'.$img['desc_img'].'" type="hidden" name="banner_img['.$c.'][desc_img][itemLiteralId]" type="text">
                       </div>
                   </div>
                   <div class="control-group">
                       <label class="control-label" for="df">Img</label>
                       <div class="controls">
                           <input accept="image/jpeg, image/png, image/gif value="'.$img['banner_img'].'" class="input-xlarge focused" id="" name="banner_img['.$c.'][banner_img]" type="file">
                           <span class="help-inline">' . 'Actual' . ': <img style="max-width: 50px;min-height: 50px;" src="' . BASE_URL . 'userfiles/banners_img/thumbs/' . $img['banner_img'] . '"/></span>
                            
                            <input type="hidden" name="banner_img['.$c.'][itemid]" value="'.$img['idImgBanner'].'">
                        </div>
                   </div>
                   </fieldset></div>';
   echo $output;
}
function getGaleriaImgs($img,$c){
    $output .= '<div id="banner_'.$c.'"><fieldset><legend></legend>
                <div><a class="btn btn-danger" onClick="delImg1('.$img['idImgGaleria'].',-1);">
                            <i class="fa-icon-trash"></i> 
                            </a></div>';
    $output .=  '<div class="control-group">
                       <label class="control-label" for="df">Alt img</label>
                       <div class="controls">
                            <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                                <li class="active"><a href="#Castellano'.$c.'" data-toggle="tab">Castellano</a></li>
                                <li><a href="#Catalan'.$c.'" data-toggle="tab">Catalan</a></li>
                            </ul>
                            <div id="my-tab-content" class="tab-content">
                                <div class="tab-pane active" id="Castellano'.$c.'">
                                    <div class="control-group">
                                    <div class="controls" style="margin-left:0px;">
                                    <input class="input-xlarge focused" id="alt_img_1" name="galeria_img['.$c.'][alt_img][1]" type="text" value="'.traducires($img['alt_img']).'">
                                </div></div></div>
                                <div class="tab-pane" id="Catalan'.$c.'">
                                    <div class="control-group">
                                    <div class="controls" style="margin-left:0px;">
                                    <input class="input-xlarge focused" id="alt_img_2" name="galeria_img['.$c.'][alt_img][2]" type="text" value="'.traducirca($img['alt_img']).'">
                                </div></div></div>
                             </div>   
                            <input value="'.$img['alt_img'].'" type="hidden" name="galeria_img['.$c.'][alt_img][itemLiteralId]" type="text">
                       </div>
                   </div>';
    
    $output .=     '<div class="control-group">
                       <label class="control-label" for="df">Desc img</label>
                       <div class="controls">
                            <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                                <li class="active"><a href="#Castellano'.($c + 1).'" data-toggle="tab">Castellano</a></li>
                                <li><a href="#Catalan'.($c + 1).'" data-toggle="tab">Catalan</a></li>
                            </ul>
                            <div id="my-tab-content" class="tab-content">
                                <div class="tab-pane active" id="Castellano'.($c + 1).'">
                                    <div class="control-group">
                                    <div class="controls" style="margin-left:0px;">
                                    <input class="input-xlarge focused" id="desc_img_1" name="galeria_img['.$c.'][desc_img][1]" type="text" value="'.traducires($img['desc_img']).'">
                                </div></div></div>
                                <div class="tab-pane" id="Catalan'.($c + 1).'">
                                    <div class="control-group">
                                    <div class="controls" style="margin-left:0px;">
                                    <input class="input-xlarge focused" id="desc_img_2" name="galeria_img['.$c.'][desc_img][2]" type="text" value="'.traducirca($img['desc_img']).'">
                                </div></div></div>
                             </div>   
                            <input value="'.$img['desc_img'].'" type="hidden" name="galeria_img['.$c.'][desc_img][itemLiteralId]" type="text">
                       </div>
                   </div>
                   <div class="control-group">
                       <label class="control-label" for="df">Img</label>
                       <div class="controls">
                           <input accept="image/jpeg, image/png, image/gif value="'.$img['galeria_img'].'" class="input-xlarge focused" id="" name="galeria_img['.$c.'][galeria_img]" type="file">
                           <span class="help-inline">' . 'Actual' . ': <img style="max-width: 50px;min-height: 50px;" src="' . BASE_URL . 'userfiles/galerias_img/thumbs/' . $img['galeria_img'] . '"/></span>
                            
                            <input type="hidden" name="galeria_img['.$c.'][itemid]" value="'.$img['idImgGaleria'].'">
                        </div>
                   </div>
                   </fieldset></div>';
   echo $output;
}
function getFormBannerImgs($id){
    $banner_imgs = new Banner_imgs();
    $sql = 'SELECT * FROM banner_imgs WHERE idBanner_fk = '.$id.'';
    $check = $banner_imgs->getRecord($sql);
    if($check){
        $where = 'idBanner_fk = '.$id.'';
        $c = 0;
        foreach((array)$banner_imgs->getAll('*',$where) as $img){
            $output .= getBannerImgs($img, $c);
                $c = $c + 2;
            

        }
        $c--;
    }
    return $output;
}
function getFormGaleriaImgs($id){
    $galeria_imgs = new Galeria_imgs();
    $sql = 'SELECT * FROM galeria_imgs WHERE idGaleria_fk = '.$id.'';
    $check = $galeria_imgs->getRecord($sql);
    if($check){
        $where = 'idGaleria_fk = '.$id.'';
        $c = 0;
        foreach((array)$galeria_imgs->getAll('*',$where) as $img){
            $output .= getGaleriaImgs($img, $c);
                $c = $c + 2;
            

        }
        $c--;
    }
    return $output;
}



?>