<?php

class Msc_Acl extends Zend_Acl {

    public function __construct() {
        $this->addRole(new Zend_Acl_Role('guest'));
        $this->addRole(new Zend_Acl_Role('author'));
        $this->addRole(new Zend_Acl_Role('editor'), 'author');
        $this->addRole(new Zend_Acl_Role('administrator'));
        
        $this->add(new Zend_Acl_Resource('default'));
        $this->add(new Zend_Acl_Resource('index'));
        $this->add(new Zend_Acl_Resource('article'));
        $this->add(new Zend_Acl_Resource('page'));
        $this->add(new Zend_Acl_Resource('user'));

        $this->allow('author', 'index' , array('index'));
        $this->allow('author', 'article' , array('index', 'view', 'add'));
        $this->allow('author', 'page' , array('index', 'view', 'add'));
        $this->allow('author', 'user' , array('index'));
        $this->allow('editor', 'article' , array('delete'));
        $this->allow('editor', 'page' , array('delete'));
        $this->allow('admin');
    }
}
?>
