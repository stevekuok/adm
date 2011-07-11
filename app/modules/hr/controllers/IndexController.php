<?php
require_once 'BaseController.php';

class Hr_IndexController extends BaseController {

    private $_index;

    public function init() {
        parent::init();
        $this->_index = new Zend_Session_Namespace('loginAuth');
    }

    public function indexAction() {
        $this->_indexFunc = new Option();
        $loginAuth = new Zend_Session_Namespace('loginAuth');
        $function = $this->_indexFunc->getUserFunc($loginAuth->userHrFunc, $loginAuth->userId, $this->view->themeBaseUrl);
//print_r($function); die();
        $main = $this->_indexFunc->getUserMain( $loginAuth->userId, date('Y/m/d'));
        $this->view->title = 'HR System';
        $this->view->userLogo = $this->_indexFunc->getLogo($this->view->themeBaseUrl);
        $this->view->userTitle = $this->_indexFunc->getTitle($this->view->themeBaseUrl);
        $this->view->userFunc = $function;
        //$this->view->userMain = $main[0];
        $this->view->userStatus = $this->_indexFunc->getUserStatus( $loginAuth->userId, $this->view->themeBaseUrl);
    }
}
