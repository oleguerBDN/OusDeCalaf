<?php

class Literal_groups extends Database {

    public $itemLiteralId = '';
    public $literalGroupId = '';
    public $cdate = '';

    function Literal_groups($id = false) {

        $this->addable = TRUE;
        $this->erasable = TRUE;
        $this->editable = TRUE;
        $this->duplicable = TRUE;

        $this->table = 'literal_groups';
        $this->fieldid = 'itemLiteralId';
        $this->showField = 'itemLiteralId';
        $this->publishField = false;

        $this->admin_showFields = array(
            'itemLiteralId'
            , 'literalGroupId'
            , 'cdate'
        );
        $this->dateFields = array(
        );
        $this->wysiwygFields = array(
        );
        $this->booleanFields = array(
        );
        $this->hiddenFields = array(
            'itemLiteralId'
        );

        parent::Database();
        if ($id) {
            $this->load($id);
        }
    }

    function getGroupFromItem($itemLiteralId) {
        if ($itemLiteralId > 0) {
            $sql = 'SELECT literalGroupId FROM ' . $this->table . ' WHERE itemLiteralId = ' . $itemLiteralId;
            return $this->getValue($sql);
        }
        return FALSE;
    }

    function getLiterals($itemLiteralId) {
        //$group = $this->getGroupFromItem($itemLiteralId);
        $sql = 'SELECT
                langIso,
                literalText
                FROM literal_translations
                LEFT JOIN literal_langs ON literalLangFk = langId
                LEFT JOIN ' . $this->table . ' ON literalGroupId = literalGroupFk
                WHERE itemLiteralId = ' . EC($itemLiteralId);
        $rs = $this->getRecordSet($sql);
        $return = array();
        foreach ((array) $rs as $r) {
            $return[$r['langIso']] = $r['literalText'];
        }
        return $return;
    }

    function getLiteral($itemLiteralId, $langIso) {
        $sql = 'SELECT 
                literalText 
                FROM literal_translations 
                LEFT JOIN literal_langs ON literalLangFk = langId 
                LEFT JOIN ' . $this->table . ' ON literalGroupId = literalGroupFk 
                WHERE itemLiteralId = ' . EC($itemLiteralId) . ' 
                AND langIso = ' . $this->fridge($langIso);
        $return = $this->getRecord($sql);
        return $return['literalText'];
    }

    function saveLiteral($text = array()) {
        /*
          array(
          'itemLiteralId' => '[ITEMLITERALID]',
          'es' => 'Calcetines',
          'en' => 'Socks',
          'ca' => 'Mitjons'
          )
         */
        $ok = FALSE;

        $itemLiteralId = $text['itemLiteralId'];
        if ($itemLiteralId) {
            $group = $this->getGroupFromItem($itemLiteralId);
            $this->begin();
        } else {
            $group = $this->getMaxRow($this->table, 'literalGroupId');
            $group++;
            $this->begin();
            $sql = 'INSERT INTO ' . $this->table . ' (literalGroupId)VALUES(' . $group . ')';
            $itemLiteralId = $this->insertNewID($sql);
        }
        foreach ((array) $text as $lang => $literal) {
            if ($lang == 'itemLiteralId') {
                continue;
            }
            $sql = 'REPLACE INTO literal_translations (literalLangFk, literalGroupFk, literalText)VALUES(' . $this->fridge($lang) . ', ' . $this->fridge($group) . ', ' . $this->fridge($literal) . ')';
            $ok = $this->query($sql);
            if ($ok != TRUE) {
                $this->rollback();
                break;
            }
        }
        if ($ok == TRUE) {
            $this->commit();
        }
        unset($_SESSION['old_lang']);
        return $itemLiteralId;
    }
    
    function saveLiteral2($lang, $itemLiteralId, $literal) {
        /*
          array(
          'itemLiteralId' => '[ITEMLITERALID]',
          'es' => 'Calcetines',
          'en' => 'Socks',
          'ca' => 'Mitjons'
          )
         */
        $ok = FALSE;

        if ($itemLiteralId) {
            $group = $this->getGroupFromItem($itemLiteralId);
            $this->begin();
        } else {
            die('error');
            $group = $this->getMaxRow($this->table, 'literalGroupId');
            $group++;
            $this->begin();
            $sql = 'INSERT INTO ' . $this->table . ' (literalGroupId)VALUES(' . $group . ')';
            $itemLiteralId = $this->insertNewID($sql);
        }
        
        $sql = 'REPLACE INTO literal_translations (literalLangFk, literalGroupFk, literalText)VALUES(' . $this->fridge($lang) . ', ' . $this->fridge($group) . ', ' . $this->fridge($literal) . ')';
        $ok = $this->query($sql);
        if ($ok != TRUE) {
            $this->rollback();
            break;
        }
        
        if ($ok == TRUE) {
            $this->commit();
        }
        unset($_SESSION['old_lang']);
        return $itemLiteralId;
    }

}

?>