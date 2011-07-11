<?php
require_once 'BaseController.php';

class Hr_TaskController extends BaseController {

    public function init() {
        parent::init();
        $this->_authSession = new Zend_Session_Namespace('loginAuth');
        $this->_text = new Lang();
        $this->_keyDesc = $this->_text->getTEXT($this->_authSession->userLanguage);
    }

    public function indexAction() {
    }

    public function applyAction() {
        $this->task = new Task();
        $this->trans = new Translation();

        $this->_opt = Zend_Registry::get('option');
        switch ($this->_authSession->userLanguage) {
            case 'cht':
                $lang = 'C';
                break;

            case 'chs':
                $lang = 'S';
                break;

            case 'por':
                $lang = 'P';
                break;

            case 'eng':
                $lang = 'E';
                break;
        }
        $appAction = $this->task->getAppAction();
        $approvedAction = $this->task->getApprovedAction();
        $appApply['record'] = $this->task->getApply($this->_authSession->userId, date($this->_opt['phpStoreDateFormat']), $lang);
        $taskApprove['record'] = $this->task->getTask($this->_authSession->userId, $lang);
        $appApply['dspDateFormat'] = $this->_opt['phpDspDateFormat'];
        $appApply['storeDateFormat'] = $this->_opt['phpStoreDateFormat'];
        $appApply['2WeeksApplyStatus'] = '2WeeksApplyStatus';
        $taskApprove['dspDateFormat'] = $this->_opt['phpDspDateFormat'];
        $taskApprove['storeDateFormat'] = $this->_opt['phpStoreDateFormat'];
        $taskApprove['wait2Approved'] = 'wait2Approved';
        $this->view->appAction = $this->trans->translate($appAction, 'value');
        $appApply = $this->trans->translate($appApply, 'key');
        $this->view->appApply = $this->trans->translate($appApply, 'value');
        $taskApprove = $this->trans->translate($taskApprove, 'key');
        $this->view->taskApprove = $this->trans->translate($taskApprove, 'value');
    }

    public function statusAction() {
        $_taskSession = new Zend_Session_Namespace('task');
        $this->view->statusMessage = $_taskSession->Status;
    }

}