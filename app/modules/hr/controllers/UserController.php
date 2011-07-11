<?php

require_once 'AdminBaseController.php';

class Admin_UserController extends AdminBaseController {

	public function indexAction() {
        $this->view->title = 'User';
	}

    public function addAction() {

    }

    public function editAction() {
    }

}
