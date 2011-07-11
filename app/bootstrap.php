<?php
define('BP', dirname(dirname(__FILE__)));
error_reporting(E_ALL | E_STRICT);
ini_set('dispaly_errors', 'on');
date_default_timezone_set('Asia/Macau');
set_include_path(get_include_path(). PATH_SEPARATOR . BP . '/lib' . PATH_SEPARATOR . BP . '/app/models');

require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->setFallbackAutoloader(true);
$loader->suppressNotFoundWarnings(false);

$config = new Zend_Config_Ini(BP . '/app/config.ini', 'database');
$authServer = new Zend_Config_Ini(BP . '/app/config.ini', 'login_location');
$db = Zend_Db::factory($config->db->adapter, $config->db->config->toArray());
Zend_Registry::set('authServer', $authServer->srv->config->host);
$db->query('SET NAMES UTF8');
Zend_Db_Table::setDefaultAdapter($db);

$config = array(
    'name'           => 'session',
    'primary'        => 'id',
    'modifiedColumn' => 'modified',
    'dataColumn'     => 'data',
    'lifetimeColumn' => 'lifetime'
);

Zend_Session::setSaveHandler(new Zend_Session_SaveHandler_DbTable($config));
Zend_Session::start();
//print_r($config); die();

Zend_Registry::set('dbAdapter', $db);

$option = new Option();
$opt = $option->getAll();
Zend_Registry::set('option', $opt);
$view = new Zend_View();
$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
$viewRenderer->setView($view); 

$front = Zend_Controller_Front::getInstance();
$front->throwExceptions(FALSE);
//$front->registerPlugin(new Zend_Controller_Plugin_ErrorHandler());
$front->setBaseUrl('/adm');
$uri = $_SERVER['REQUEST_URI'];

//print_r($uri);
$front->setControllerDirectory(array(
    'default'  => BP . '\app\modules\hr\controllers',
    'adm' => BP . '\app\modules\adm\controllers',
    'hr'  => BP . '\app\modules\hr\controllers',
));

if (preg_match('/\/(cht|por|eng|chs|jap|others)/', $uri, $matches)) {
    Zend_Registry::set('language', $matches[1]);
    $uri = str_replace($matches[0], '', $uri);
}
//echo $uri; die();
$request = new Zend_Controller_Request_Http();
$request->setRequestUri($uri);
$front->setRequest($request);
$front->dispatch();



