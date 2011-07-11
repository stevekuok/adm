<?php
require_once 'BaseController.php';

class Hr_EventsController extends BaseController {

    private $_events;
    private $_authSession;

    public function init() {
        parent::init();
        $this->_authSession = new Zend_Session_Namespace('loginAuth');
        $this->_text = new Lang();
        $this->_keyDesc = $this->_text->getTEXT($this->_authSession->userLanguage);
    }

    public function geteventsAction() {
        $this->_events = new Events();
        $this->trans = new Translation();
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
        if (!($this->getRequest()->getControllerName() == 'auth') && (!$this->_auth->hasIdentity())) {
            $this->view->eventsMessage = "No Identity";
        }else {
            if ($this->getRequest()->isPost()) {
                $_lnkCode = $this->getRequest()->getParam('lnkCode');
                $eventsMessage = $this->_events->getEvents($_lnkCode, $lang);

                $retEvent = $eventsMessage;
                $retEvent['dspDateFormat'] = $this->_opt['phpDspDateFormat'];
                $retEvent['storeDateFormat'] = $this->_opt['phpStoreDateFormat'];
                $retEvent["agree"] = "agree";
                $retEvent["disagree"] = "disagree";
                $retEvent["eventType"] = $eventsMessage['eventType'];
                $retEvent = $this->trans->translate($retEvent, 'value');
                $this->view->eventsMessage = $this->trans->translate($retEvent, 'key');
            }
        }
    }

    public function getapprovedAction() {
        $this->_events = new Events();
        $this->trans = new Translation();
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
        if (!($this->getRequest()->getControllerName() == 'auth') && (!$this->_auth->hasIdentity())) {
            $this->view->eventsMessage = "No Identity";
        }else {
            if ($this->getRequest()->isPost()) {
                $_lnkCode = $this->getRequest()->getParam('lnkCode');
                $eventsMessage = $this->_events->getEvents($_lnkCode, $lang);

                $retEvent = $eventsMessage;
                $retEvent['dspDateFormat'] = $this->_opt['phpDspDateFormat'];
                $retEvent['storeDateFormat'] = $this->_opt['phpStoreDateFormat'];
                $retEvent["eventType"] = $eventsMessage['eventType'];
                $retEvent["agree"] = "agree";
                $retEvent["disagree"] = "disagree";
                $retEvent["comment"] = "comment";
                $retEvent["eventType"] = $eventsMessage['eventType'];

                $retEvent = $this->trans->translate($retEvent, 'value');
                $this->view->eventsMessage = $this->trans->translate($retEvent, 'key');
            }
        }
    }

    public function statusAction() {
        $_taskSession = new Zend_Session_Namespace('task');
        $this->view->statusMessage = $_taskSession->Status;
    }
}