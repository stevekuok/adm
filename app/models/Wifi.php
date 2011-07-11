<?php

class Wifi extends Zend_Db_Table_Abstract {

    protected $_dbAdapter = null;
    
    public function __construct() {
        parent::__construct();
        $radiusConfig = new Zend_Config_Ini(BP . '/app/config.ini', 'radiusdb');
        $radiusDB = Zend_Db::factory($radiusConfig->db->adapter, $radiusConfig->db->config->toArray());
        Zend_Db_Table::setDefaultAdapter($radiusDB);
        $radiusDB->query('SET NAMES UTF8');
        Zend_Registry::set('radiusDBAdapter', $radiusDB);
        $this->_dbAdapter = $this->getDefaultAdapter();

    }

    public function upWifPassword($username ='', $password=''){

        try {
            $chkPassword = "SELECT UserName FROM radcheck WHERE Value = '".$password."' AND UserName = '".$username."';";
            $_UserExist = $this->_dbAdapter->fetchAll($chkPassword);
            if (sizeof($_UserExist) > 0){
                return 'Succeeded';
            } else {
                //Update radcheck table
                $chkUserExist = "SELECT UserName FROM radcheck WHERE UserName = '".$username."';";
                $_UserExist = $this->_dbAdapter->fetchAll($chkUserExist);
                if (sizeof($_UserExist) == 1){
                    $_upData = array ('Value' => $password);
                    $_where[] = "UserName = "."'".$username."'";
                    $upPassword = $this->_dbAdapter->update ('radcheck', $_upData, $_where);

                } else if(sizeof($_UserExist) == 0) {
                    $_insertData = array (
                                        'UserName'      => $username,
                                        'Attribute'     => 'User-Password',
                                        'op'            => ':=',
                                        'Value'         => $password,
                                        'expiredDate'   => '2012-12-31 23:59:59');
                    $insertUser = $this->_dbAdapter->insert ('radcheck', $_insertData);

                    //Update usergroup table
                    $chkUserGroupExist = "SELECT UserName FROM usergroup WHERE UserName = '".$username."';";
                    $_UserGroupExist = $this->_dbAdapter->fetchAll($chkUserGroupExist);

                    if(sizeof($_UserGroupExist) == 1) {
                        $_upData = array ('GroupName' => 'staff');
                        $_where[] = "UserName = "."'".$username."'";
                        $upUserGroup = $this->_dbAdapter->update ('usergroup', $_upData, $_where);
                    } else if(sizeof($_UserGroupExist) == 0) {
                        $_insertData = array (
                                            'UserName'      => $username,
                                            'GroupName'     => 'staff');
                        $insertUserGroup = $this->_dbAdapter->insert ('usergroup', $_insertData);
                    }
                }
                return "Succeeded";
            }
        } catch (Exception $e) {
                return $e->getMessage();
        }
    }
}
