<?php

session_start();
mysql_query("SET NAMES utf8");

//------------------------------------------------ Server config --------------------------------------------------------------
switch ($_SERVER['SERVER_NAME']) {

    case 'ous.proyectosclientes.es' :

        ini_set('display_errors', 1);
        error_reporting(E_ALL & ~E_NOTICE);
//        error_reporting(1);
        ini_set('max_execution_time', 300);
        date_default_timezone_set('Europe/Madrid');

        define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
        define('BASE_URL', 'http://' . $_SERVER['SERVER_NAME'] . '/');

        //MySQL Connection
        define('DBSERVER', 'localhost');
        define('DBUSERNAME', 'ous_u');
        define('DBPASSWORD', 'tecnitek');
        define('DBSCHEMA', 'ous_db');

        define('FUNCTION_DEBUG', false);
        define('SQL_DEBUG', false);

        break;

    default :
  
        ini_set('display_errors', 1);
        error_reporting(E_ALL & ~E_NOTICE);
        //error_reporting(0);
        ini_set('max_execution_time', 300);
        date_default_timezone_set('Europe/Madrid');

        define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/ous/');
        define('BASE_URL', 'http://' . $_SERVER['SERVER_NAME'] . '/ous/');

        //MySQL Connection
        define('DBSERVER', 'localhost');
        define('DBUSERNAME', 'root');
        define('DBPASSWORD', '');
        define('DBSCHEMA', 'ous');

        define('FUNCTION_DEBUG', true);
        define('SQL_DEBUG', true);
        break;
}

// ---------------- constantes para el sendmail -----------------------------//
define('USERNAME', 'rdepedro@tecnitek.com');
define('HOST', 'mail.tecnitek.com');
define('PASSWORD', 'depedror');
define('FROM', 'rdepedro@tecnitek.com');
define('TO', 'rdepedro@tecnitek.com');
define('SUBJECT', 'Contacta con nosotros - web');



//------------------------------------------------ control definitions --------------------------------------------------------------

define('DEBUG_ENTER', "<br/>\n");


//------------------------------------------------ basic definitions ----------------------------------------------------------------
define('PROJECT_NAME', 'ous');

define('PRODUCTS_IN_LINE', 3);

//------------------------------------------------- autoloader dal define------------------------------------------------------------------------------

function mi_autocargador($clase) {
    include BASE_PATH . 'dal/' . strtolower($clase) . '_db.php';
}

spl_autoload_register('mi_autocargador');

//------------------------------------------------ instanciar controlador bbdd ----------------------------------------------------------------------
require_once BASE_PATH . 'includes/functions.php';
require_once BASE_PATH . 'dal/database.php';
//------------------------------------------------ load basics ----------------------------------------------------------------------
/*cleanDebugLog(); 
$class_settings = new Settings();
$settings = $class_settings->getPairs();*/

//------------------------------------------------ pay type Config ----------------------------------------------------------

$type_pay = array(
    'bank_card' => 1,
    'paypal' => 2,
    'transfer' => 3,
    'cash on delivery' => 4
);

//------------------------------------------------ saveFile Config ----------------------------------------------------------
$banner_size = array(
    'main_banner' => array(
        'width' => '960',
        'height' => '360',
        'scale' => FALSE
    )
);

$section_size = array(
    'bigger' => array(
        'width' => '940',
        'scale' => true
    )
);

$sample_size = array(
    'sample' => array(
        'width' => '20',
        'height' => '20',
        'scale' => FALSE
    )
);

$png_background = array(255, 255, 255);

//----------------------------------------------- SERMEPA ----------------------------------------------------
/*define('TPV_URL_TPVV', 'https://sis-t.sermepa.es:25443/sis/realizarPago');
//define('TPV_URL_TPVV', 'https://sis.sermepa.es/sis/realizarPago');
define('TPV_CODE', '322313610');
define('TPV_TERMINAL', '1');
define('TPV_CURRENDY', '978');
define('TPV_CLAVE', 'qwertyasdf0123456789');
//define('TPV_CLAVE','208D059ND1N66U49');*/
//------------------------------------------------- LANG --------------------------------------------------------------

//if (isset($_GET['lang'])) {
//    $language = $_GET['lang'];
//    if (substr($_GET['lang'], -1) == '/') {
//        $language = substr($_GET['lang'], 0, -1);
//    }
//    $langs = new Literal_langs();
//    if ($langs->checkLang($language)) {
//        $_SESSION['lang'] = $language;
//    } else {
//        error404();
//    }
//} elseif (!isset($_SESSION['lang'])) {
//    $_SESSION['lang'] = $GLOBALS['settings']['default_language'];
//}

if ($_SESSION['old_lang'] != $_SESSION['lang']) {
    $db = new Database();
    $_SESSION['old_lang'] = $_SESSION['lang'];
    $sql = 'SELECT 
            itemLiteralId,
            literalText
            FROM literal_translations 
            LEFT JOIN literal_langs ON literalLangFk = langId 
            LEFT JOIN literal_groups ON literalGroupId = literalGroupFk 
            WHERE langIso = ' . EC($_SESSION['lang']);
    $rs = $db->getRecordSet($sql);
    foreach ((array)$rs as $key => $value) {
        $return[$value['itemLiteralId']] = $value['literalText'];
    }
    $_SESSION['all_literals'] = $return;
}
function cargar_trans(){
    $db = new Database();
    $sql = 'SELECT 
            itemLiteralId,
            literalText
            FROM literal_translations 
            LEFT JOIN literal_langs ON literalLangFk = langId 
            LEFT JOIN literal_groups ON literalGroupId = literalGroupFk 
            WHERE langIso = "'.$_SESSION['lang'].'"';
    $rs = $db->getRecordSet($sql);
    foreach ((array)$rs as $key => $value) {
        $return[$value['itemLiteralId']] = $value['literalText'];
    }
    $_SESSION['all_literals'] = $return;
}

function translate($itemLiteralId) {
    cargar_trans();
    return $_SESSION['all_literals'][$itemLiteralId];
}
function traducires($itemLiteralId){
    
    $literal_groups = new Literal_groups();
    $literal_translations = new Literal_translations();
    $gid = $literal_groups->getOne($itemLiteralId);
    $gid = $gid['literalGroupId'];
    $sql = 'SELECT literalText FROM literal_translations WHERE literalLangFk="1" AND literalGroupFk="'.$gid.'"';
    $res = $literal_translations->getRecord($sql);
    return $res['literalText'];
}
function traducirca($itemLiteralId){
    $literal_groups = new Literal_groups();
    $literal_translations = new Literal_translations();
    $gid = $literal_groups->getOne($itemLiteralId);
    $literal_langs = new Literal_langs();
    
    $gid = $gid['literalGroupId'];
    
    $sql = 'SELECT literalText FROM literal_translations WHERE literalLangFk="2" AND literalGroupFk="'.$gid.'"';
    $res = $literal_translations->getRecord($sql);
    return $res['literalText'];
}
$literal_groups = new Literal_groups();
$literal_translations = new Literal_translations();
$literal_langs = new Literal_langs();

function traducir($itemLiteralId){
    $literal_groups = new Literal_groups();
    $literal_translations = new Literal_translations();
    $gid = $literal_groups->getOne($itemLiteralId);
    $literal_langs = new Literal_langs();
    $lang = $_SESSION['lang'];
    
    $sql = 'SELECT * FROM literal_langs WHERE langIso="'.$lang.'"';
    $lang = $literal_langs->getRecord($sql);
    $lang = $lang['langId'];
    $gid = $gid['literalGroupId'];
    
    $sql = 'SELECT literalText FROM literal_translations WHERE literalLangFk="'.$lang.'" AND literalGroupFk="'.$gid.'"';
    $res = $literal_translations->getRecord($sql);
    return $res['literalText'];
}
function traducirA($itemLiteralId){
    $literal_groups = new Literal_groups();
    $literal_translations = new Literal_translations();
    $gid = $literal_groups->getOne($itemLiteralId);
    $literal_langs = new Literal_langs();
    $lang = $_SESSION['lang'];
    
    $sql = 'SELECT * FROM literal_langs WHERE langIso="es"';
    $lang = $literal_langs->getRecord($sql);
    $lang = $lang['langId'];
    $gid = $gid['literalGroupId'];
    
    $sql = 'SELECT literalText FROM literal_translations WHERE literalLangFk="'.$lang.'" AND literalGroupFk="'.$gid.'"';
    $res = $literal_translations->getRecord($sql);
    return $res['literalText'];
}
$literal_fixed = new Literal_fixed();
$all_fixed_literals = $literal_fixed->getFixedLiterals();


function lang($key) {

    $key = strtolower($key);
    if (array_key_exists($key, (array)$GLOBALS['all_fixed_literals'])) {
        $literal = traducir($GLOBALS['all_fixed_literals'][$key]);
        if ($literal != '') {
            return $literal;
//                return 1;
        } else {
            return $key;
//                return 2;
        }
    } else {
        $GLOBALS['literal_fixed']->insertFixedLiteral($key);
        $GLOBALS['all_fixed_literals'][$key] = '';
        return $key;
//        return 3;
    }
    
}
function langA($key) {

    $key = strtolower($key);
    if (array_key_exists($key, (array)$GLOBALS['all_fixed_literals'])) {
        $literal = traducirA($GLOBALS['all_fixed_literals'][$key]);
        if ($literal != '') {
            return $literal;
//                return 1;
        } else {
            return $key;
//                return 2;
        }
    } else {
        $GLOBALS['literal_fixed']->insertFixedLiteral($key);
        $GLOBALS['all_fixed_literals'][$key] = '';
        return $key;
//        return 3;
    }
    
}
