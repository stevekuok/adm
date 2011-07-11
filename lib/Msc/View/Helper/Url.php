<?php

class Msc_View_Helper_Url extends Zend_View_Helper_Url {

    public function url(array $urlOptions = array(), $name = null, $reset = false, $encode = false) {
        $url =parent::url($urlOptions, $name, $reset, $encode);
        $front = Zend_Controller_Front::getInstance();
        $baseUrl = $front->getBaseUrl();
        $url = str_replace($baseUrl, '', $url);
        if (Zend_Registry::isRegistered('language')) {
            $url = $baseUrl . '/' . Zend_Registry::get('language') . $url;
        } else {
            $url = $baseUrl . $url;
        }
        return $url;
    }
}