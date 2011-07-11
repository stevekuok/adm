<?php

class Menu extends Zend_Db_Table_Abstract {

    protected $_dbAdapter = null;
    private $ROOT_ID = '1';
    private $UNMENU_ID = '2';

    public function __construct() {
        parent::__construct();
        $this->_dbAdapter = $this->getDefaultAdapter();
    }

    public function getNode($id, $langId = null) {
        $where = ($langId == null) ? '' : (' AND `category`.`lang_id`=' . $langId);
        $sql = 'SELECT * FROM `category` LEFT JOIN `category_meta` 
                ON `category`.`cat_meta_id`=`category_meta`.`id` 
                WHERE `category`.`cat_meta_id`>? AND `category`.`cat_meta_id`=?' . $where;
        $result = $this->_dbAdapter->fetchAll($sql, array($this->ROOT_ID, $id));
        return $result;
    }

    public function getAllNodes() {
        $sql = 'SELECT * FROM `category` LEFT JOIN `category_meta` 
                ON `category`.`cat_meta_id`=`category_meta`.`id`';
        $result = $this->_dbAdapter->fetchAll($sql);
        return $result;
    }

    public function deleteNode($id) {
        $this->_dbAdapter->delete('category_meta', 'id=' . $id);
        $this->_dbAdapter->delete('category', 'cat_meta_id=' . $id);
        /*TODO: handle its child nodes & items */
        $childNodes = $this->getChildNodes($id);
        if ($childNodes) {
            foreach ($childNodes as $childNode) {
                $this->changeParent($childNode['id'], $this->ROOT_ID);
            }
        }
        $this->rebuildTree();
    }

    public function rebuildTree($parentId = 1, $left = 1) {
        $right = $left + 1;
        $sql = 'SELECT `id` FROM `category_meta` WHERE `parent_id`=?';
        $result = $this->_dbAdapter->fetchAll($sql, $parentId);
        foreach ($result as $row) {
            $right = $this->rebuildTree($row['id'], $right);
        }
        $data = array(
            'lft' => $left,
            'rgt' => $right,
        );
        /*$sql = 'UPDATE `category_meta` SET `lft`=?, `rgt`=? WHERE `id`=?';*/
        $result = $this->_dbAdapter->update('category_meta', $data, 'id=' . $parentId);
        return $right + 1;
    }

    public function getLeaf() {
        $sql = 'SELECT * FROM `category` 
                LEFT JOIN `category_meta` ON `category`.`cat_meta_id`=`category_meta`.`id` 
                WHERE `category_meta`.`rgt`=`category_meta`.`lft`+1;';

    }

    public function getPath($left) {
        $sql = 'SELECT * FROM `category` LEFT JOIN `category_meta` ON `category`.`cat_meta_id`=`category_meta`.`id` WHERE `category_meta`.`lft`<' . $left . ' AND `category_meta`.`rgt`>' . $left + 1 . ' ORDER BY `category_meta`.`lft` ASC;';
        $path = array();
        return $path;
    }

    public function getChildNodes($parentNode, $langId = null) {
        $where = ($langId == null) ? '' : (' AND `category`.`lang_id` = ' . $langId);
        if (is_numeric($parentNode)) {
            $parent = ' `category_meta`.`parent_id` = ' . (int)$parentNode;
            $sql = 'SELECT * FROM `category`
                    LEFT JOIN `category_meta`
                    ON `category`.`cat_meta_id`=`category_meta`.`id`
                    WHERE ' . $parent . $where .
                    ' ORDER BY `category_meta`.`sort_order` DESC, `category`.`name` DESC';
        } else {
            $sql = 'SELECT * FROM `category`, `category_meta` WHERE `category`.`cat_meta_id` = `category_meta`.`id`
                       AND `category_meta`.`parent_id` IN 
                    (SELECT `category`.`cat_meta_id` FROM `category` WHERE `category`.`name` = \'' . $parentNode . '\'' . $where . ') ' . $where ;
        }
        $result = $this->_dbAdapter->fetchAll($sql);
//echo $sql; die();
//print_r($result); die();
        return $result;
    }

    public function changeParent($id, $newParentId) {
        $temp = array('parent_id' => $newParentId);
        $this->_dbAdapter->update('category_meta', $temp, 'id='. $id);
    }

    public function getTree() {
        $sql = 'SELECT * FROM (SELECT node.id, node.sort_order, (COUNT(parent.id) - 1) AS depth
                FROM `category_meta` AS node, `category_meta` AS parent
                WHERE node.lft BETWEEN parent.lft AND parent.rgt
                GROUP BY node.id
                ORDER BY node.lft) AS tree
                LEFT JOIN category
                ON tree.id=`category`.cat_meta_id';

        $result = $this->_dbAdapter->fetchAll($sql);
        return $result;
    }

    public function updateNode($data) {
        $temp = array(
                'alias' => $data['catAlias'],
                'parent_id' => $data['catParent']
        );
        $this->_dbAdapter->update('category_meta', $temp, 'id='. $data['catMetaId']);
        for ($i = 1; $i <= 3; $i++) {
            $temp = array(
                    'name' => $data['catName'][$i],
                    'description' => $data['catDesc'][$i],
            );
            $this->_dbAdapter->update('category', $temp, '`cat_meta_id`=' . $data['catMetaId'] . ' AND `lang_id`=' . $i);
        }
        $this->rebuildTree();
    }

    public function insertNode($data) {
        $temp = array(
                'alias' => $data['catAlias'],
                'parent_id' => $data['catParent'],
                'lft' => 0,
                'rgt' => 0,
        );
        $this->_dbAdapter->insert('category_meta', $temp);
        $catMetaId = $this->_dbAdapter->lastInsertId();
        for ($i = 1; $i <= 3; $i++) {
            $temp = array(
                    'cat_meta_id' => $catMetaId,
                    'lang_id' => $i,
                    'name' => $data['catName'][$i],
                    'description' => $data['catDesc'][$i],
            );
            $this->_dbAdapter->insert('category', $temp);
        }
        $this->rebuildTree();
    }

    public function getTopNodes($langId = null) {
        $where = ($langId == null) ? '' : (' AND `category`.`lang_id`=' . $langId);
        $where = ($langId == null) ? '' : (' `menu_lists`.`lang_id`=' . $langId) . ';';
        $sql = 'SELECT * FROM `category` LEFT JOIN `category_meta` 
                ON `category`.`cat_meta_id`=`category_meta`.`id`
                WHERE `category_meta`.`parent_id`=1 AND `category_meta`.`id`>' . $this->UNCATEGORIZED_ID . $where;
        if ($where)
            $sql = 'SELECT * FROM `menu_lists` WHERE ' . $where;
        else
            $sql = 'SELECT * FROM `menu_lists`;';
//echo $sql; die();
        $result = $this->_dbAdapter->fetchAll($sql);
        $data = array();
        foreach ($result as $row) {
            $data[$row['alias']] = $row;
            $data[$row['menu_id']] = $row;
        }
        return $data;
    }

    public function getMenuLists($langId = null) {
        $where = ($langId == null) ? '' : (' `menu_lists`.`lang_id`=' . $langId);
        $where .= ' AND `menu_lists`.`is_active`=1';
        if ($where)
            $sql = 'SELECT * FROM `menu_lists` WHERE ' . $where;
        else
            $sql = 'SELECT * FROM `menu_lists`;';
        $result = $this->_dbAdapter->fetchAll($sql);
        $data = array();
        foreach ($result as $row) {
            $data[$row['menu_id']] = $row;
        }
        return $data;
    }
    
    public function getParentId($id) {
        $sql = 'SELECT * FROM `category_meta`
                WHERE `category_meta`.id=?';
        $result = $this->_dbAdapter->fetchAll($sql, (int)$id);
        return $result[0]['parent_id'];
    }

    public function updateSortOrder($orderedList = array()) {

        $count = count($orderedList);
        if ($count > 0) {
            foreach ($orderedList as $catMetaId) {
                $count--;
                $data = array(
                        'sort_order' => $count
                );
                $this->_dbAdapter->update('category_meta', $data, 'id=' . $catMetaId);
            }
        }
    }

    public function hasChildren($id) {
        $sql = 'SELECT * FROM `category_meta`
                WHERE `category_meta`.`parent_id`=?';
        $result = $this->_dbAdapter->fetchAll($sql, (int)$id);
        return (count($result) > 0) ? true : false;
    }
}
