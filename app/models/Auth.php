<?php

class Auth extends Zend_Db_Table_Abstract {

    protected $_dbAdapter = null;

    public function __construct() {
        parent::__construct();
        $this->_dbAdapter = $this->getDefaultAdapter();
    }

    public function getAdmUser($_userName=''){
        $sql = "SELECT u.*, p.email , p.nameE FROM tbluser u, tblperson p
                            WHERE u.loginName='".$_userName."' AND u.userId = p.userId;";
        $result = $this->_dbAdapter->fetchAll($sql);
        return $result;
    }
}