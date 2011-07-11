<?php
require_once 'BaseController.php';

class Hr_WifiController extends BaseController {

    protected $_auth;

    public function init() {
        parent::init();
    }

    public function indexAction() {
        $this->_indexFunc = new Option();
        $this->_wifi = new Wifi();
        $_wifiSession = new Zend_Session_Namespace('wifi');
        $_authSession = new Zend_Session_Namespace('loginAuth');
        foreach ($_authSession as $index => $value) {
            $authSession[$index] = $value;
        }
        $this->view->userLogo = $this->_indexFunc->getLogo($this->view->themeBaseUrl);
        $this->view->userTitle = $this->_indexFunc->getTitle($this->view->themeBaseUrl);
        $this->view->userFunc = $this->_indexFunc->getUserFunc($_authSession->userHrFunc, $_authSession->userId, $this->view->themeBaseUrl);        
        $this->view->userStatus = $this->_indexFunc->getUserStatus( $_authSession->userId, $this->view->themeBaseUrl);
        $_wifiSession->Status = '';
        $this->view->authSession = $authSession;
    }

    public function chpasswordAction() {
        $this->_auth = Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session('loginAuth'));
        if (!($this->getRequest()->getControllerName() == 'auth') && (!$this->_auth->hasIdentity())) {
            $this->view->wifiMessage = "No Identity";
        }else {
            if ($this->getRequest()->isPost()) {
                $_userName = $this->getRequest()->getParam('userName');
                $_userPassword = $this->getRequest()->getParam('userPassword');
                $username = str_replace('\\', '', $this->getRequest()->getParam('userName'));
                $password = str_replace('\\', '', $this->getRequest()->getParam('userPassword'));
            }
            $this->_wifi = new Wifi();
            $_wifi = $this->_wifi->upWifPassword($username, $password);
            $this->view->wifiMessage = $_wifi;
        }
    }

    public function statusAction() {
        $_wifiSession = new Zend_Session_Namespace('wifi');
        $this->view->statusMessage = $_wifiSession->Status;
    }

}