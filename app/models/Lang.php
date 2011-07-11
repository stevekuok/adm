<?php
/* 
 * TEXT array
 */

/**
 * Description of Lang
 *
 * @author steve
 */
class Lang extends Zend_Db_Table_Abstract {
    
    public function __construct() {
        parent::__construct();
        $this->_dbAdapter = $this->getDefaultAdapter();
    }
    
    public function getTEXT($langId = null) {
        $sql = "";
        switch ($langId)
        {
            case 'cht':
                $sql = 'SELECT keyName, descC lang_desc FROM `lang`;';
                break;

            case "por":
                $sql = 'SELECT keyName, descP lang_desc FROM `lang`;';
                break;

            case 'eng':
                $sql = 'SELECT keyName, desE lang_desc FROM `lang`;';
                break;

            case 'chs':
                $sql = 'SELECT keyName, descS lang_desc FROM `lang`;';
                break;

            case 'jan':
                $sql = 'SELECT keyName, descJ lang_desc FROM `lang`;';
                break;
        }

        $data = array();
        if ($sql){
            $result = $this->_dbAdapter->fetchAll($sql);
            foreach ($result as $row) {
                $data[$row['keyName']] = $row['lang_desc'];
            }
        }
        return $data;
    }
}

?>
