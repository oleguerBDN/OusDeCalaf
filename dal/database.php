<?php

class Database {

    var $_dB = '';
    var $_cursor = null;
    var $_stmt = '';
    var $_errorNum = 0;
    var $_errorMsg = '';
    var $_debug = false;
    public $table = '';
    public $fieldid = '';
    public $showField = '';
    public $publishField = '';
    public $urlField = '';
    public $convertToUrlField = '';
    public $imgFolder = '';
    public $fileFolder = '';
    public $resize = array();
    public $fields = array();
    public $counting = 0;
    public $addable = TRUE;
    public $erasable = TRUE;
    public $editable = TRUE;
    public $viewable = FALSE;
    public $admin_showFields = array();
    public $references = array();
    public $dateFields = array();
    public $externalForm = array();
    public $picklists = array();
    public $textareaFields = array();
    public $wysiwygFields = array();
    public $booleanFields = array();
    public $fileFields = array();
    public $passwordFields = array();
    public $imageFields = array();
    public $literalFields = array();
    public $selectFields = array();
    public $md5Fields = array();
    public $hiddenFields = array();
    public $uneditableFields = array();
    public $avoidFields = array();
    public $linkFields = array();

    function Database($dbhost = DBSERVER, $dbuser = DBUSERNAME, $dbpwd = DBPASSWORD, $schema = DBSCHEMA) {

        if (!($this->_dB = mysql_connect($dbhost, $dbuser, $dbpwd, true))) {
            die('No hay conexión!<br/>' . mysql_error());
        }
        if (!mysql_select_db($schema, $this->_dB)) {
            die('No hay esquema!<br/>' . mysql_error());
        }
        @mysql_query("set names utf8");
    }

    /**
     * Permite realizar cualquier consulta con la BBDD
     */
    function query($stmt, &$error = '', $offset = -1, $limit = 0) {
        $this->_stmt = $stmt;

        if (($offset > -1) && ($limit > 0)) {
            $this->_stmt .= " LIMIT $offset, $limit";
        } else {
            if ($limit > 0) {
                $this->_stmt .= " LIMIT $limit";
            }
        }
        $this->_errorNum = 0;
        $this->_cursor = mysql_query($this->_stmt, $this->_dB);
        if (!$this->_cursor) {
            $this->_errorNum = mysql_errno($this->_dB);
            if ($this->_errorMsg != '') {
                $this->_errorMsg .= '<hr/>';
            }
            $this->_errorMsg .= "<b>SQL Error $this->_errorNum: </b>" . mysql_error($this->_dB) . DEBUG_ENTER . DEBUG_ENTER . "<b>SQL= </b>$this->_stmt";
            $this->rollback();
            die($this->_errorMsg);
            return false;
        }
        return $this->_cursor;
    }

    function getValue($stmt) {
        if (!$cur = $this->query($stmt)) {
            return null;
        }
        $r = null;
        if ($row = mysql_fetch_row($cur)) {
            $r = $row[0];
        }
        mysql_free_result($cur);
        return $r;
    }

    function getRecord($stmt) {
        $r = FALSE;
        if (!$this->_cursor = $this->query($stmt, $error)) {
            return null;
        }
        if ($row = mysql_fetch_array($this->_cursor)) {
            $r = $row;
        }
        mysql_free_result($this->_cursor);
        return $r;
    }

    function getRecordSet($stmt) {
        $r = null;
        if (!$this->_cursor = $this->query($stmt, $error)) {
            return null;
        }
        while ($row = mysql_fetch_array($this->_cursor)) {
            $r[] = $row;
        }
        mysql_free_result($this->_cursor);

        return $r;
    }

    function getNamedRecordSet($stmt) {
        $r = null;
        if (!$this->_cursor = $this->query($stmt, $error)) {
            return null;
        }
        while ($row = mysql_fetch_array($this->_cursor, MYSQL_ASSOC)) {
            $r[] = $row;
        }
        mysql_free_result($this->_cursor);

        return $r;
    }

    public function insertNewID($stmt) {
        $r = '';
        if ($this->query($stmt)) {
            $r = mysql_insert_id($this->_dB);
            if ($r == FALSE) {
                $r = TRUE;
            }
        }
        return $r;
    }

    function getIDs($idfield, $table, $where = '', $order = '') {
        $foo = array();
        $q = 'SELECT ' . $idfield . ' FROM ' . $table;
        if ($where) {
            $q .= ' WHERE ' . $where;
        }
        if ($order) {
            $q .= ' ORDER BY ' . $order;
        }
        $r = $this->getRecordSet($q);
        if (count($r) > 0) {
            foreach ((array) $r as $rec) {
                $foo[] = $rec[$idfield];
            }
        }

        return $foo;
    }

    function getPairs($idfield = false, $datafield = false, $table = false, $order = '', $where = '', $maketranslations = false) {
        $pairs = array();

        if (!$idfield) {
            $idfield = $this->fieldid;
        }
        if (!$datafield) {
            $datafield = $this->showField;
        }
        if (!$table) {
            $table = $this->table;
        }

        $sql = 'SELECT ' . $idfield . ', ' . $datafield . ' FROM ' . $table;
        if ($where) {
            $sql .= ' WHERE ' . $where;
        }
        if ($order) {
            $sql .= ' ORDER BY ' . $order;
        }
        $rs = $this->getRecordSet($sql);

        if ($maketranslations && in_array($datafield, $this->literalFields)) {
            $translate = true;
        } else {
            $translate = false;
        }

        if (count($rs) > 0) {
            foreach ((array) $rs as $r) {
                if ($translate) {
                    $pairs[$r[0]] = translate($r[1]);
                } else {
                    $pairs[$r[0]] = $r[1];
                }
            }
        }

        return $pairs;
    }

    function getMinRow($table, $field) {
        $q = 'SELECT min(' . $field . ') FROM ' . $table;
        return $this->getValue($q);
    }

    function getMaxRow($table, $field) {
        $q = 'SELECT max(' . $field . ') FROM ' . $table;
        return $this->getValue($q);
    }

    public function begin() {
        @mysql_query('BEGIN', $this->_dB);
    }

    public function commit() {
        @mysql_query('COMMIT', $this->_dB);
    }

    public function rollback() {
        @mysql_query('ROLLBACK', $this->_dB);
    }

    function fridge($foo) {
        $foo = EC(mysql_real_escape_string($foo, $this->_dB));
        return $foo;
    }

    function describeTable() {
        if ($this->table != '') {
            $sql = 'DESCRIBE ' . $this->table;
            $this->fields = $this->getRecordSet($sql);
        }
    }

    function showTables() {
        $sql = 'SHOW FULL TABLES';
        return $this->getRecordSet($sql);
    }

    function getAll($fields = '*', $where = false, $order = FALSE, $join = false, $limit = false, $group = false) {
        //$count = 'SELECT count(*) FROM ' . $this->table;
        $count = 'SELECT count(DISTINCT('.$this->table.'.'.$this->fieldid.')) FROM ' . $this->table;
        $sql = 'SELECT ' . $fields . ' FROM ' . $this->table;
        
        if ($where) {
            $sql .= ' WHERE ' . $where;
            $count .= ' WHERE ' . $where;
        }

        if ($group) {
            $sql .= ' GROUP BY ' . $group;
        }

        if ($order) {
            $sql .= ' ORDER BY ' . $order;
        }
        if ($limit) {
            $sql .= ' LIMIT ' . $limit;
        }
        //echo ($sql).DEBUG_ENTER;
        $rs = $this->getRecordSet($sql);
        $this->counting = $this->getValue($count);
        return $rs;
    }

    function load($id = false) {
        if ($id) {
            $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->fieldid . ' = ' . $this->fridge($id);
            $rs = $this->getRecord($sql);
        }
        $this->describeTable();
        foreach ($this->fields as $fields_key => $fields_value) {
            $field_name = $fields_value['Field'];
            $this->$field_name = $rs[$field_name];
        }
    }

    function getOne($id, $fields = '*') {
        $sql = 'SELECT ' . $fields . ' FROM ' . $this->table;
        $sql .= ' WHERE ' . $this->fieldid . ' = ' . EC($id);
        $rs = $this->getRecord($sql);
        return $rs;
    }

    function saveItem($value, $files = array()) {
//         print_r($files);
//        print_r($value);
        $this->describeTable();
        $this->begin();
        $literals = new Literal_groups();

        if ($value['itemid'] == '-1') {
            
            $field_names = '';
            $field_values = '';
            foreach ((array) $this->fields as $key => $field) {
                if ($field['Field'] == $this->urlField) {
                    if (in_array($field['Field'], $this->literalFields)) {
                        foreach ((array) $value[$field['Field']] as $langId => $foo) {
                            if ($langId != 'itemLiteralId') {
                                $value[$field['Field']][$langId] = strtourl($value[$this->convertToUrlField][$langId]);
                            }
                        }
                    } else {
                        $value[$field['Field']] = strtourl($value[$this->convertToUrlField]);
                    }
                }
                if (in_array($field['Field'], $this->booleanFields)) {
                    if (isset($value[$field['Field']]) === false) {
                        $value[$field['Field']] == '';
                    }
                } else {
                    if (isset($value[$field['Field']]) === false) {
                        continue;
                    }
                }

                if (in_array($field['Field'], $this->md5Fields)) {
                    $value[$field['Field']] = md5($value[$field['Field']]);
                }
                $field_names = concatena($field_names, $field['Field'], ', ');
                if (is_array($value[$field['Field']])) {
                    $field_values = concatena($field_values, $literals->saveLiteral($value[$field['Field']]), ', ');
                } else {
                    if ($value[$field['Field']] == 'NULL') {
                        $field_values = concatena($field_values, $value[$field['Field']], ', ');
                    } else {
                        $field_values = concatena($field_values, $this->fridge($value[$field['Field']]), ', ');
                    }
                }
            }

            $sql = 'INSERT INTO ' . $this->table . ' (' . $field_names . ')VALUES(' . $field_values . ')';
            //echo $sql.DEBUG_ENTER;
            $ok = $this->insertNewID($sql);
            if ($ok == true) {
                $this->commit();
            } else {
                $this->rollback();
            }
        } else {
            
            $sql = 'UPDATE ' . $this->table . ' SET ';
            $thefields = '';
            foreach ((array) $this->fields as $key => $field) {

                if (in_array($field['Field'], $this->booleanFields)) {
                    if (isset($value[$field['Field']]) === false) {
                        $value[$field['Field']] == '';
                    }
                } else {
                    if (isset($value[$field['Field']]) === false || $key == 0 || $key == $this->fieldid) {
                        continue;
                    }
                }
                if ($old_value = array_search($field['Field'], $this->md5Fields)) {
                    if ($old_value != $value[$field['Field']]) {
                        $value[$field['Field']] = md5($value[$field['Field']]);
                    }
                }
                //if (is_array($value[$field['Field']])) {
                if (in_array($field['Field'], $this->literalFields)) {
                    $thefields = concatena($thefields, $field['Field'] . ' = ' . $literals->saveLiteral($value[$field['Field']]), ', ');
                } else {
                    if ($value[$field['Field']] == 'NULL') {
                        $thefields = concatena($thefields, $field['Field'] . ' = ' . $value[$field['Field']], ', ');
                    } else {
                        $thefields = concatena($thefields, $field['Field'] . ' = ' . $this->fridge($value[$field['Field']]), ', ');
                    }
                }
            }
            $sql .= $thefields;
            
            if ($thefields != '') {
                $sql .= ' WHERE ' . $this->fieldid . ' = ' . EC($value['itemid']);
                //echo $sql.DEBUG_ENTER;
                $ok = $this->query($sql);
                if ($ok == true) {
                    $this->commit();
                    $ok = $value['itemid'];
                } else {
                    $this->rollback();
                }
            } else {
                $this->rollback();
                $ok = $value['itemid'];
            }
        }
        
        foreach ((array) $this->imageFields as $imageField) {
            
            if ($files[$imageField]['name']) {
                if ($newname = saveFile($files[$imageField]['name'], $files[$imageField]['tmp_name'], BASE_PATH . $this->imgFolder, $value[$imageField], $this->resize, true)) {
                    $sql = 'UPDATE ' . $this->table . ' SET ' . $imageField . ' = ' . $this->fridge($newname) . ' WHERE ' . $this->fieldid . ' = ' . $this->fridge($ok);
                    $this->query($sql);
                } else {
                    return 'Guardado ok, error con imagen';
                }
            }
        }
        
        foreach ((array) $this->fileFields as $fileField) {
            
            if ($files[$fileField]['name']) {
                if ($newname = saveFile($files[$fileField]['name'], $files[$fileField]['tmp_name'], BASE_PATH . $this->fileFolder, $value[$fileField], false, true)) {
                    $sql = 'UPDATE ' . $this->table . ' SET ' . $fileField . ' = ' . $this->fridge($newname) . ' WHERE ' . $this->fieldid . ' = ' . $this->fridge($ok);
                    $this->query($sql);
                } else {
                    return 'Guardado ok, error con multimedia';
                }
            }
        }
        
        return $ok;
    }

    function deleteItem($itemid) {
        $ok = false;
        $langs = new Literal_langs();
        $literals = new Literal_groups();
        $all_langs = $langs->getAll('langId,langIso');
        foreach ((array) $this->imageFields as $imageField) {
            $sql = 'SELECT ' . $imageField . ' FROM ' . $this->table . ' WHERE ' . $this->fieldid . ' = ' . $itemid;
            $theImage = $this->getValue($sql);
            if (in_array($imageField, $this->literalFields)) {
                foreach ((array) $all_langs as $all_langs_values) {
                    $imageName = $literals->getLiteral($theImage, $all_langs_values['langIso']);
                    @unlink(BASE_PATH . $this->imgFolder . $imageName);
                    foreach ((array) $this->resize as $subfolder => $thumb) {
                        @unlink(BASE_PATH . $this->imgFolder . $subfolder . '/' . $imageName);
                    }
                }
            } else {
                @unlink(BASE_PATH . $this->imgFolder . $theImage);
                foreach ((array) $this->resize as $subfolder => $thumb) {
                    @unlink(BASE_PATH . $this->imgFolder . $subfolder . '/' . $theImage);
                }
            }
        }

        foreach ((array) $this->literalFields as $literal) {
            $delete = $this->getOne($itemid, $literal);
            $sql = 'SELECT literalGroupId FROM literal_groups WHERE itemLiteralId = ' . $this->fridge($delete[$literal]);
            echo $sql . "\n";
            $id = $this->getValue($sql);
            $sql = 'DELETE FROM literal_groups WHERE itemLiteralId = ' . $this->fridge($delete[$literal]);
            echo $sql . "\n";
            $this->query($sql);
            $sql = 'DELETE FROM literal_translations WHERE literalGroupFk = ' . $this->fridge($id);
            echo $sql . "\n";
            $this->query($sql);
        }

        $sql = 'DELETE FROM ' . $this->table . ' WHERE ' . $this->fieldid . ' = ' . $this->fridge($itemid);
        echo $sql . DEBUG_ENTER;
        $ok = $this->query($sql);
        return $ok;
    }

    function deleteImg($itemid, $field) {
//        die('Eliminar "'.$field.'" del elemento con id '.$itemid);
        $ok = false;
        $sql = 'SELECT ' . $field . ' FROM ' . $this->table . ' WHERE ' . $this->fieldid . ' = ' . $this->fridge($itemid);
        $imgName = $this->getValue($sql);
        $ok1 = @unlink(BASE_PATH . $this->imgFolder . $imgName);
        foreach ($this->resize as $subfolder => $thumb) {
            echo $this->imgFolder . $subfolder . '/' . $imgName;
            $ok2 = @unlink(BASE_PATH . $this->imgFolder . $subfolder . '/' . $imgName);
        }
        if ($ok1 && $ok2) {
            $sql = 'UPDATE ' . $this->table . ' SET ' . $field . ' = NULL WHERE ' . $this->fieldid . ' = ' . $this->fridge($itemid);
            $ok = $this->query($sql);
        }
        return $ok;
    }

    function deleteFile($itemid, $field) {
        echo $itemid .' -- '. $field;
        $ok = false;
        $sql = 'SELECT ' . $field . ' FROM ' . $this->table . ' WHERE ' . $this->fieldid . ' = ' . $this->fridge($itemid);
        $fileName = $this->getValue($sql);
        $ok1 = unlink(BASE_PATH . $this->fileFolder . $fileName);
        if ($ok1) {
            $sql = 'UPDATE ' . $this->table . ' SET ' . $field . ' = NULL WHERE ' . $this->fieldid . ' = ' . $this->fridge($itemid);
            $ok = $this->query($sql);
        }
        return $ok;
    }

    function getSelect($name, $selected = false, $isEmpty = false, $onchange = '', $id = false, $disabled = false, $where = false, $addable = false) {
        $thearray = $this->getPairs($this->fieldid, $this->showField, $this->table, $this->showField, $where, true);
        return getSelectFromArray($thearray, $name, $selected, $isEmpty, $onchange, $id, false, $disabled, false, false, $addable);
    }

    function getPickList($parentId, $relations_table, $selectable_items) {
        $class = new $relations_table();
        $parentField = $class->showField;
        return $this->printPickList($parentId, $parentField, $referedField, $relations_table, $selectable_items, $where);
    }

    function printPickList($parentId, $parentField, $referedField, $relations_table, $selectable_items, $where = false, $order = false) {
        $id = $relations_table;
        $name = $relations_table;
        $GLOBALS['footerIncludes'][] = '<script type="text/javascript">
        $(function(){
            $("#' . $id . '").pickList({
                sourceListLabel:	"' . lang('disponibles') . '",
                targetListLabel:	"' . lang('seleccionados') . '",
                addAllClass:            "btn btn-info",
                addClass:               "btn btn-info",
                removeAllClass:         "btn btn-info",
                removeClass:            "btn btn-info",
            });
        });
    </script>';
        $table = new $selectable_items();
        $foo = new $relations_table();
        $allItems = $table->getAll($table->fieldid . ', ' . $table->showField, $where, $order);
        $selectedItems = $foo->getAll('*', $parentField . ' = ' . $parentId);
        //die('FROM '.$foo->table.' WHERE '.$parentField.' = ' . $parentId);
        $selectedItemsIds = array();
        foreach ((array) $selectedItems as $value) {
            $selectedItemsIds[] = $value[$referedField];
        }
        $options = '';
        foreach ((array) $allItems as $option) {
            $options .= '<option value="' . $option[0] . '"';
            if (in_array($option[0], $selectedItemsIds)) {
                $options .= 'selected';
            }
            $options .= '>';
            if (in_array($table->showField, $table->literalFields)) {
                $options .= translate($option[1]);
            } else {
                $options .= $option[1];
            }
            $options .= '</option>';
        }
        $picklist = '<div class="control-group">
                        <input id="' . $id . '_group" name="' . $name . '[group]" type="hidden" value="' . $_GET['id'] . '">
                        <label class="control-label" for="' . $id . '">' . lang($selectable_items) . '</label>
                        <select id="' . $id . '" name="' . $name . '[items][]" multiple="multiple">' . $options . '</select>
                    </div>';
        return $picklist;
    }

}

?>
