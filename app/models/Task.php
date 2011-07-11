<?php

class Task extends Zend_Db_Table_Abstract {

    protected $_dbAdapter = null;
    
    public function __construct() {
        parent::__construct();
        $radiusConfig = new Zend_Config_Ini(BP . '/app/config.ini', 'database');
        $this->_dbAdapter = $this->getDefaultAdapter();
    }

    public function getApply ($_usrId='', $_date, $_language){
        try {
            $apply = "SELECT e.*, s.desc" . $_language . " descr FROM tblevents e, tblwrkflowstatus s
                      WHERE e.fromUserId='".$_usrId."'
                        AND datediff(e.beforeDate,'".date('Y/m/d', strtotime('-2 weeks', strtotime($_date)))."') >=0
                        AND e.status = s.id
                   ORDER BY e.formDate DESC;";
//echo $apply."<br>"; die();
            $_apply = $this->_dbAdapter->fetchAll($apply);
            return $_apply;

        } catch (Exception $e) {
                return $e->getMessage();
        }
    }

    public function getTask ($_usrId='', $_language){
        try {
            $task = "SELECT e.*, s.desc" . $_language . " descr, p.name" . $_language . " sender
                       FROM tblevents e, tblwrkflowstatus s, tblperson p
                      WHERE (e.toUserId='".$_usrId."') AND e.fromUserId = p.userId
                        AND e.status = s.id AND (e.status <> '1' AND e.status <> '9')
                   ORDER BY e.formDate DESC;";
//echo $task."<br>"; die();
            $_task = $this->_dbAdapter->fetchAll($task);
            return $_task;

        } catch (Exception $e) {
                return $e->getMessage();
        }
    }

    public function getAppAction (){
        try {
            $action[] = array ('edit' => 'edit');
            $action[] = array ('delete' => 'delete');
            $action[] = array ('cancel' => 'cancel');
            $action[] = array ('alert' => 'alert');
/*
            $edit = true;
            $delete = true;
            $cancel = true;
            $alert = true;
//print_r(sizeof($_lnkCode));
            
            if (sizeof($_lnkCode) == 1) {
                if ($_lnkCode <> "") {
                    $e = $this->chkAction($_lnkCode, $_usrId);
                    if ($e[0]['status'] == "") {
                        if ($e[0]['status'] > 1 AND $e[0]['status'] < 9) {
                            $edit = false;
                            $cancel = false;
                        } elseif (substr($e[0]['status'], 0, 1) <> 0) {
                            $edit = false;
                            $delete = false;
                            $alert = false;
                        }
                        if ($edit == true)
                            $action[] = array ('edit' => 'edit');
                        if ($edit == true)
                            $action[] = array ('delete' => 'delete');
                        if ($edit == true)
                            $action[] = array ('cancel' => 'cancel');
                        if ($edit == true)
                            $action[] = array ('alert' => 'alert');
                    }
                }
                $_event = $action;
            } elseif (sizeof($_lnkCode) > 1) {
                $edit = true;
                $delete = true;
                $cancel = true;
                $alert = true;
                $iCount = 0;
                foreach ($_lnkCode as $code) {
                    $e = $this->chkAction($code, $_usrId);

                    if ($e[0]['status'] > 1 AND $e[0]['status'] < 9) {
                        $edit = false;
                        $cancel = false;
                    } elseif (substr($e[0]['status'], 0, 1) <> 0) {                        
                        $edit = false;
                        $delete = false;
                        $alert = false;
                    }
                    if ($edit == true)
                        $action[] = array ('edit' => 'edit');
                    if ($edit == true)
                        $action[] = array ('delete' => 'delete');
                    if ($edit == true)
                        $action[] = array ('cancel' => 'cancel');
                    if ($edit == true)
                        $action[] = array ('alert' => 'alert');
                    $_event[$iCount] = $action;
                    $iCount++;
               }
            }
 *
 */
            $_event = $action;
            return $_event;

        } catch (Exception $e) {
                return $e->getMessage();
        }
    }

    public function getApprovedAction (){
        try {
            $action[] = array ('edit' => 'edit');
            $action[] = array ('delete' => 'delete');
            $action[] = array ('cancel' => 'cancel');
            $action[] = array ('alert' => 'alert');
/*
            $edit = true;
            $delete = true;
            $cancel = true;
            $alert = true;
//print_r(sizeof($_lnkCode));

            if (sizeof($_lnkCode) == 1) {
                if ($_lnkCode <> "") {
                    $e = $this->chkAction($_lnkCode, $_usrId);
                    if ($e[0]['status'] == "") {
                        if ($e[0]['status'] > 1 AND $e[0]['status'] < 9) {
                            $edit = false;
                            $cancel = false;
                        } elseif (substr($e[0]['status'], 0, 1) <> 0) {
                            $edit = false;
                            $delete = false;
                            $alert = false;
                        }
                        if ($edit == true)
                            $action[] = array ('edit' => 'edit');
                        if ($edit == true)
                            $action[] = array ('delete' => 'delete');
                        if ($edit == true)
                            $action[] = array ('cancel' => 'cancel');
                        if ($edit == true)
                            $action[] = array ('alert' => 'alert');
                    }
                }
                $_event = $action;
            } elseif (sizeof($_lnkCode) > 1) {
                $edit = true;
                $delete = true;
                $cancel = true;
                $alert = true;
                $iCount = 0;
                foreach ($_lnkCode as $code) {
                    $e = $this->chkAction($code, $_usrId);

                    if ($e[0]['status'] > 1 AND $e[0]['status'] < 9) {
                        $edit = false;
                        $cancel = false;
                    } elseif (substr($e[0]['status'], 0, 1) <> 0) {
                        $edit = false;
                        $delete = false;
                        $alert = false;
                    }
                    if ($edit == true)
                        $action[] = array ('edit' => 'edit');
                    if ($edit == true)
                        $action[] = array ('delete' => 'delete');
                    if ($edit == true)
                        $action[] = array ('cancel' => 'cancel');
                    if ($edit == true)
                        $action[] = array ('alert' => 'alert');
                    $_event[$iCount] = $action;
                    $iCount++;
               }
            }
 *
 */
            $_event = $action;
            return $_event;

        } catch (Exception $e) {
                return $e->getMessage();
        }
    }

    private function chkAction ($_lnkCode='', $_userId='') {
        $event = "SELECT status
                    FROM tblevents
                   WHERE lnkCode = '". $_lnkCode."' AND toUserId = '".$_userId."';";
echo $event."<br>";
        $e = $this->_dbAdapter->fetchAll($event);

        print_r($e);
    }
}
