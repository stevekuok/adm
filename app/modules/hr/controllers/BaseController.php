<?php

abstract class BaseController extends Zend_Controller_Action {

    protected $_auth;
    protected $_menu = null;
    protected $_item = null;
    protected $_langId;
    protected $_opt;
    protected $_redirector = null;
    protected $_userName;
    protected $_userPassword;
    private   $_text = null;

    public function init() {
        $this->_opt = Zend_Registry::get('option');
        $this->_auth = Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session('loginAuth'));
        if ($this->_auth->hasIdentity()) {
            $this->view->user = $this->_auth->getIdentity();
        }

        $this->_redirector = $this->_helper->getHelper('Redirector');
        $this->view->baseUrl = $this->getRequest()->getBaseUrl();
        $this->view->addScriptPath(BP.'\theme\hr');
        $this->view->baseUrl = $this->_opt['baseUrl'];
        $this->view->themeBaseUrl = $this->_opt['baseUrl'] .'/theme/hr';
        $this->view->addHelperPath('Msc/View/Helper', 'Msc_View_Helper');
        
        if ($this->_auth->hasIdentity()) {
            $this->view->user = $this->_auth->getIdentity();
            $this->view->baseUrl = $this->getRequest()->getBaseUrl();
            $this->_redirector = $this->_helper->getHelper('Redirector');

            if (Zend_Registry::isRegistered('language')) {
                $language = Zend_Registry::get('language');
            } else {
                $language = 'cht';
            }
//print_r($language); die();
            switch ($language) {
                case 'cht':
                    $this->_langId = 1;
                    break;

                case 'por':
                    $this->_langId = 2;
                    break;

                case 'eng':
                    $this->_langId = 3;
                    break;

                case 'chs':
                    $this->_langId = 4;
                    break;

                case 'jap':
                    $this->_langId = 4;
                    break;

                default:
                    $this->_langId = 1;
                    break;
            }            
            $this->view->langCode = $language;
            $this->view->text = $this->_text;
            $this->view->module = $this->getRequest()->module;
            $this->view->controller = $this->getRequest()->controller;
            $this->view->action = $this->getRequest()->action;
            $this->view->langId = $this->_langId;
            $this->view->pathInfo = trim(urldecode($this->getRequest()->getPathInfo()), '/');
        }
    }

    public function preDispatch() {
        if (!($this->getRequest()->getControllerName() == 'auth') && (!$this->_auth->hasIdentity())) {
            $this->_forward('login', 'auth', 'hr', array('recall' => $this->getRequest()->getPathInfo()));
        }
    }

    private function _quotes($content) {
        if (!get_magic_quotes_gpc()) {
            if (is_array($content)) {
                foreach ($content as $key=>$value) {
                    $content[$key] = addslashes($value);
                }
            } else {
                addslashes($content);
            }
        }
        return $content;
    }
}
