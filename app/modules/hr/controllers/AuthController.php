<?php
require_once 'BaseController.php';

class Hr_AuthController extends BaseController {

    private $_user = null;
    private $_loginAuth;

    public function init() {
        parent::init();
        $this->_user = new User();
        $this->_loginAuth = new Zend_Session_Namespace('loginAuth');
    }

    public function loginAction() {
        if ($this->_auth->hasIdentity()) {
            $this->_redirector->gotoSimple('index', 'index', 'hr');
        }
        $this->view->title = 'Login';
    }

    public function logoutAction() {
        $this->_auth->clearIdentity();
        $this->_redirector->gotoSimple('index', 'index', 'hr');
    }

    public function authAction() {
        if ($this->getRequest()->isPost()) {
            $_userName = $this->getRequest()->getParam('userName');
            $_userPassword = $this->getRequest()->getParam('userPassword');
            $authLocation = Zend_Registry::get('authServer');
            $this->_loginAuth = new Auth();
            if ($authLocation == 'local') {
//                if ($this->_request->isPost()) {
//			$email = trim($this->getRequest()->getPost('email'));
//			$password = $this->getRequest()->getPost('password');
                //$referer = $this->getRequest()->getPost('referer');

                if (!empty($_userName)) {
                    $dbAdapter = Zend_Registry::get('dbAdapter');
                    $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
                    $authAdapter->setTableName('tbluser');
                    $authAdapter->setIdentityColumn('loginName');
                    $authAdapter->setCredentialColumn('password');
                    $authAdapter->setCredentialTreatment('SHA1(?)');
                    $authAdapter->setIdentity($_userName);
                    $authAdapter->setCredential($_userPassword);
                    $result = $this->_auth->authenticate($authAdapter);

                    if ($result->isValid()) {
                        $arrUser = $this->_loginAuth->getAdmUser($_userName);
                        $_authSession = new Zend_Session_Namespace('loginAuth');
                        $_authSession->userName = $_userName;
                        $_authSession->userGivenName = $arrUser[0]['nameE'];
                        $_authSession->userId = $arrUser[0]['userId'];
                        $_authSession->userEmail = $arrUser[0]['email'];
                        $_authSession->userHrFunc = $arrUser[0]['hrFunctions'];
                        $_authSession->userAdmFunc = $arrUser[0]['attFunctions'];
                        $_authSession->userLanguage = $arrUser[0]['language'];

                        if (sizeof($arrUser) == 1 ){
                            $this->view->authMessage = "Succeeded";
                        } else {
                            $this->view->authMessage = 'User does not registered in this ADM System.';
                        }
                    } else {
                        $this->view->authMessage = 'Incorrect User Name/Password Pair.';
                    }
                } else {
                    $this->view->authMessage = 'Incorrect Email/Password Pair.';
                }

            } elseif ($authLocation == 'domain') {
                $_options = array(
                    'server1' => array(
                                'host' => '172.16.1.11',
                                'accountDomainName' => 'msc.local',
                                'accountDomainNameShort' => 'msc',
                                'accountCanonicalForm' => 3,
                                'baseDn' => 'CN=staff,DC=msc,DC=local',
                                )
                );
                
    //connect to LDAP
                
                $_adapter = new Zend_Auth_Adapter_Ldap($_options, $_userName, $_userPassword);
                $_result = $this->_auth->authenticate($_adapter);

                if ($this->_auth->hasIdentity()) {
                    $arrUser = $this->_loginAuth->getAdmUser($_userName);
                    $_authSession = new Zend_Session_Namespace('loginAuth');
                    $_authSession->userName = $_userName;
                    $_authSession->userGivenName = $arrUser[0]['nameE'];
                    $_authSession->userId = $arrUser[0]['userId'];
                    $_authSession->userEmail = $arrUser[0]['email'];
                    $_authSession->userHrFunc = $arrUser[0]['hrFunctions'];
                    $_authSession->userAdmFunc = $arrUser[0]['attFunctions'];
                    $_authSession->userLanguage = $arrUser[0]['language'];
                    if (sizeof($arrUser) == 1 ){
                        $this->view->authMessage = "Succeeded";
                    } else {
                        $this->view->authMessage = 'User does not registered in this ADM System.';
                    }
                } else {
                    $this->view->authMessage = 'Incorrect User Name/Password Pair.';
                }                
            }
        } else {
            $this->view->referer = $this->getRequest()->getParam('recall');
        }
    }
}
