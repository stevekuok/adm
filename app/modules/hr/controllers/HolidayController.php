<?php
require_once 'BaseController.php';

class Hr_HolidayController extends BaseController {

    public function init() {
        parent::init();
    }

    public function indexAction() {
    }

    public function companyAction() {
        $this->_indexFunc = new Option();
        $this->_holiday = new Holiday();
        $_holidaySession = new Zend_Session_Namespace('holiday');
        $_authSession = new Zend_Session_Namespace('loginAuth');
        foreach ($_authSession as $index => $value) {
            $authSession[$index] = $value;
        }
        $this->view->companyHoliday = $this->_holiday->getCompanyHoliday(date('Y/m/d'));
        $this->view->userHoliday = $this->_holiday->getUserHoliday($authSession['userId'], date('Y/m/d'));
        $_holidaySession->Status = '';
        $this->view->authSession = $authSession;
    }

    public function statusAction() {
        $_holidaySession = new Zend_Session_Namespace('holiday');
        $this->view->statusMessage = $_holidaySession->Status;
    }
}