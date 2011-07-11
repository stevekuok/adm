<?php

require_once 'SportsBaseController.php';

class Sports_OptionController extends SportsBaseController {

    protected $_option;

    public function init() {
        parent::init();
        require_once 'Option.php';
        $this->_option = new Option();
    }

    public function indexAction() {
        $this->view->title = 'Setting';
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            if ($this->_isValid($post)) {
                $this->_option->updateAll($post);
                $this->_redirector->gotoSimple('index', 'index', 'admin');
            } else {
                $this->_redirector->gotoSimple('index', 'index', 'admin');
            }
            //$this->_redirector->gotoUrl('/admin/category/index');
        } else {
            $this->view->option = $this->_opt;
        }
    }
    
    private function _isValid($data) {
        return true;
    }

}


