<?php
/*
class Msc_Plugin_Auth extends Zend_Controller_Plugin_Abstract {

    private $_auth;
    private $_acl;

    private $_noAuth = array(
    'module' => 'default',
    'controller' => 'auth',
    'action' => 'login');

    private $_noPrivilege = array(
    'module' => 'default',
    'controller' => 'error',
    'action' => 'index');

    public function __construct($acl)
    {
        $this->_auth = Zend_Auth::getInstance();
        $this->_acl = $acl;
    }

    public function preDispatch($request)
    {
        if ($this->_auth->hasIdentity()) {
            $role = $this->_auth->getIdentity()->role;
        } else {
            $role = 'guest';
        }

        $module = $request->module;
        $controller = $request->controller;
        $action = $request->action;
        $resource = $module;

        if (!$this->_acl->isAllowed($role, $resource, $action)) {
            if (!$this->_auth->hasIdentity()) {
                $module = $this->_noAuth['module'];
                $controller = $this->_noAuth['controller'];
                $action = $this->_noAuth['action'];
            } else {
                $module = $this->_noPrivilege['module'];
                $controller = $this->_noPrivilege['controller'];
                $action = $this->_noPrivilege['action'];
            }
        }

        $request->setModuleName($module);
        $request->setControllerName($controller);
        $request->setActionName($action);
    }
}*/
