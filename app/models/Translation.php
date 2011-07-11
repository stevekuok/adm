<?php

class Translation extends Zend_Db_Table_Abstract {

    protected $_dbAdapter = null;
    
    public function __construct() {
        parent::__construct();
        $this->_text = new Lang();
        $this->_authSession = new Zend_Session_Namespace('loginAuth');
        $this->_keyDesc = $this->_text->getTEXT($this->_authSession->userLanguage);
    }


    public  function translate($_array, $_keyOrValue) {
        switch ($_keyOrValue) {
            case "key":
                $retTrans = $this->getKeyTranslation ($_array);
                break;

            case "value":

                $retTrans = $this->getValueTranslation ($_array);
                break;

        }
        return $retTrans;
    }

    public function getKeyTranslation ($_array) {

        foreach ($_array as $key => $values) {
            if (is_array($values)) {
                $translation[$key] = $this->getKeyTranslation($values);
            } else {
                array_key_exists($key, $this->_keyDesc)==TRUE ? $translation[$key."KeyName"] = $this->_keyDesc[$key] : $translation[$key."KeyName"] = "";
                $translation[$key] = $values;

            }
        }
        return $translation;
    }

    public function getValueTranslation ($_array) {

        foreach ($_array as $key => $values) {
            if (is_array($values)) {
                $translation[$key] = $this->getValueTranslation($values);
            } else {
                array_key_exists($values, $this->_keyDesc)==TRUE ? $translation[$key] = $this->_keyDesc[$values] : $translation[$key] = $values;
            }
        }
        return $translation;
    }
}