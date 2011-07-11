<?php

class Events extends Zend_Db_Table_Abstract {

    protected $_dbAdapter = null;
    
    public function __construct() {
        parent::__construct();
        $this->_dbAdapter = $this->getDefaultAdapter();
    }


    public function getEvents ($_lnkCode, $_language){
        try {
            $events = "SELECT * FROM tblevents WHERE lnkCode ='".$_lnkCode."';";
            $recEvents = $this->_dbAdapter->fetchAll($events);

            if (sizeof($recEvents) == 1) {

                switch($recEvents[0]['tblName']){
                    case "appabsence":
                        $Abs = $this->getAbs($_lnkCode, $_language);
                        $userId = $this->getUserName($Abs[0]['userId'], $_language);
                        $staffId = $this->getUserName($Abs[0]['staffId'], $_language);
                        $supervisorId = $this->getUserName($Abs[0]['supervisorId'], $_language);
                        $approvedUser1 = $this->getUserName($Abs[0]['approvedUser1'], $_language);
                        $approvedUser2 = $this->getUserName($Abs[0]['approvedUser2'], $_language);
                        $approvedUser3 = $this->getUserName($Abs[0]['approvedUser3'], $_language);
                        $approvedUser4 = $this->getUserName($Abs[0]['approvedUser4'], $_language);
                        $approvedUser5 = $this->getUserName($Abs[0]['approvedUser5'], $_language);
//print_r($Abs);
                        $retEvents = $Abs[0];
                        $retEvents['eventType'] = "absence";
                        $retEvents['userId'] = $userId;
                        $retEvents['staffId'] = $staffId;
                        $retEvents['supervisorId'] = $supervisorId;
                        $retEvents['approvedUser1'] = $approvedUser1;
                        $retEvents['approvedUser2'] = $approvedUser2;
                        $retEvents['approvedUser3'] = $approvedUser3;
                        $retEvents['approvedUser4'] = $approvedUser4;
                        $retEvents['approvedUser5'] = $approvedUser5;
                        $appSettleType = $this->getKeyNameDesc('appSettleType'.ucfirst($Abs[0]['appSettleType']), $_language);
                        $retEvents['appSettleType'] = $appSettleType[0]['Descr'];
                        $retEvents['appType'] = $Abs[0]['title'];
//print_r($retEvents);
                        break;

                    case "applate":
                        $Late = $this->getLate($_lnkCode, $_language);
                        $userId = $this->getUserName($Late[0]['userId'], $_language);
                        $staffId = $this->getUserName($Late[0]['staffId'], $_language);
                        $supervisorId = $this->getUserName($Late[0]['supervisorId'], $_language);
                        $approvedUser1 = $this->getUserName($Late[0]['approvedUser1'], $_language);
                        $approvedUser2 = $this->getUserName($Late[0]['approvedUser2'], $_language);
                        $approvedUser3 = $this->getUserName($Late[0]['approvedUser3'], $_language);
                        $approvedUser4 = $this->getUserName($Late[0]['approvedUser4'], $_language);
                        $approvedUser5 = $this->getUserName($Late[0]['approvedUser5'], $_language);

                        $retEvents = $Late[0];
                        $retEvents['eventType'] = "late";
                        $retEvents['userId'] = $userId;
                        $retEvents['staffId'] = $staffId;
                        $retEvents['supervisorId'] = $supervisorId;
                        $retEvents['approvedUser1'] = $approvedUser1;
                        $retEvents['approvedUser2'] = $approvedUser2;
                        $retEvents['approvedUser3'] = $approvedUser3;
                        $retEvents['approvedUser4'] = $approvedUser4;
                        $retEvents['approvedUser5'] = $approvedUser5;
                        $appLateType = $this->getKeyNameDesc('lateType'.ucfirst($Late[0]['type']), $_language);
                        $retEvents['appType'] = $appLateType[0]['Descr'];
                        break;

                    case "appmodulation":
                        $Mod = $this->getMod($_lnkCode, $_language);
                        $userId = $this->getUserName($Mod[0]['userId'], $_language);
                        $staffId = $this->getUserName($Mod[0]['staffId'], $_language);
                        $supervisorId = $this->getUserName($Mod[0]['supervisorId'], $_language);
                        $approvedUser1 = $this->getUserName($Mod[0]['approvedUser1'], $_language);
                        $approvedUser2 = $this->getUserName($Mod[0]['approvedUser2'], $_language);
                        $approvedUser3 = $this->getUserName($Mod[0]['approvedUser3'], $_language);
                        $approvedUser4 = $this->getUserName($Mod[0]['approvedUser4'], $_language);
                        $approvedUser5 = $this->getUserName($Mod[0]['approvedUser5'], $_language);

                        $retEvents = $Mod[0];
                        $retEvents['eventType'] = "modulation";
                        $retEvents['userId'] = $userId;
                        $retEvents['staffId'] = $staffId;
                        $retEvents['supervisorId'] = $supervisorId;
                        $retEvents['approvedUser1'] = $approvedUser1;
                        $retEvents['approvedUser2'] = $approvedUser2;
                        $retEvents['approvedUser3'] = $approvedUser3;
                        $retEvents['approvedUser4'] = $approvedUser4;
                        $retEvents['approvedUser5'] = $approvedUser5;

                        $morningPeriod = $this->getTimePeriod($Mod[0]['morningRuleId']);
                        $noonPeriod = $this->getTimePeriod($Mod[0]['noonRuleId']);
                        $retEvents['morningIn'] = $morningPeriod['fromTime'];
                        $retEvents['morningOut'] = $morningPeriod['toTime'];
                        $retEvents['noonIn'] = $noonPeriod['fromTime'];
                        $retEvents['noonOut'] = $noonPeriod['toTime'];
                        $week2Num = array(1=>'一',2=>'二',3=>'三',4=>'四',5=>'五',6=>'六',7=>'日');
                        $tempweekdays = $Mod[0]['weekdays'];
                        $weekdays = "";
                        for ($iCount = 1; $iCount <= 5; $iCount++){
                            if ((($tempweekdays >> 1) << 1) <> $tempweekdays) $weekdays .= $week2Num[$iCount];
                            $tempweekdays = $tempweekdays >> 1;
                        }
                        $retEvents['weekdays'] = $weekdays;
//print_r($retEvents); die();

                        break;

                    case "apponleave":
                        $OnL = $this->getOnL($_lnkCode, $_language);
                        $userId = $this->getUserName($OnL[0]['userId'], $_language);
                        $staffId = $this->getUserName($OnL[0]['staffId'], $_language);
                        $supervisorId = $this->getUserName($OnL[0]['supervisorId'], $_language);
                        $approvedUser1 = $this->getUserName($OnL[0]['approvedUser1'], $_language);
                        $approvedUser2 = $this->getUserName($OnL[0]['approvedUser2'], $_language);
                        $approvedUser3 = $this->getUserName($OnL[0]['approvedUser3'], $_language);
                        $approvedUser4 = $this->getUserName($OnL[0]['approvedUser4'], $_language);
                        $approvedUser5 = $this->getUserName($OnL[0]['approvedUser5'], $_language);
                        
                        $retEvents = $OnL[0];
                        $retEvents['eventType'] = "onleave";
                        $retEvents['userId'] = $userId;
                        $retEvents['staffId'] = $staffId;
                        $retEvents['supervisorId'] = $supervisorId;
                        $retEvents['approvedUser1'] = $approvedUser1;
                        $retEvents['approvedUser2'] = $approvedUser2;
                        $retEvents['approvedUser3'] = $approvedUser3;
                        $retEvents['approvedUser4'] = $approvedUser4;
                        $retEvents['approvedUser5'] = $approvedUser5;

                        if ($OnL[0]['appFromTime'] == '09:00:00' AND $OnL[0]['appFromDate'] <> $OnL[0]['appToDate'])
                            $fromDayDesc = $this->getKeyNameDesc('fullDay', $_language);
                        elseif ($OnL[0]['appFromTime'] == '09:00:00' AND $OnL[0]['appToTime'] == '13:00:00')
                            $fromDayDesc = $this->getKeyNameDesc('morningDay', $_language);
                        elseif ($OnL[0]['appFromTime'] == '14:30:00')
                            $fromDayDesc = $this->getKeyNameDesc('noonDay', $_language);
                        else
                            $fromDayDesc = $this->getKeyNameDesc('fullDay', $_language);

                        if ($fromDayDesc[0]['Descr'] <> "")
                            $retEvents['appFromDay'] = "(".$fromDayDesc[0]['Descr'].")";
                        else
                            $retEvents['appFromDay'] = "";

                        if ($OnL[0]['appFromDate'] <> $OnL[0]['appToDate']) {
                            if ($OnL[0]['appToTime'] >= '18:30:00')
                                $toDayDesc = $this->getKeyNameDesc('fullDay', $_language);
                            elseif ($OnL[0]['appToTime'] <= '13:30:00')
                                $toDayDesc = $this->getKeyNameDesc('morningDay', $_language);
                        }else {
                            $toDayDesc = $this->getKeyNameDesc('', $_language);
                        }
                        if ($toDayDesc[0]['Descr'] <> "")
                            $retEvents['appToDay'] = "(".$toDayDesc[0]['Descr'].")";
                        else
                            $retEvents['appToDay'] = "";


                        break;

                    case "appovertime":
                        $OT = $this->getOT($_lnkCode, $_language);
                        $userId = $this->getUserName($OT[0]['userId'], $_language);
                        $staffId = $this->getUserName($OT[0]['staffId'], $_language);
                        $supervisorId = $this->getUserName($OT[0]['supervisorId'], $_language);
                        $approvedUser1 = $this->getUserName($OT[0]['approvedUser1'], $_language);
                        $approvedUser2 = $this->getUserName($OT[0]['approvedUser2'], $_language);
                        $approvedUser3 = $this->getUserName($OT[0]['approvedUser3'], $_language);
                        $approvedUser4 = $this->getUserName($OT[0]['approvedUser4'], $_language);
                        $approvedUser5 = $this->getUserName($OT[0]['approvedUser5'], $_language);
//print_r($OT);
                        $retEvents = $OT[0];
                        $retEvents['eventType'] = "overtime";
                        $retEvents['userId'] = $userId;
                        $retEvents['staffId'] = $staffId;
                        $retEvents['supervisorId'] = $supervisorId;
                        $retEvents['approvedUser1'] = $approvedUser1;
                        $retEvents['approvedUser2'] = $approvedUser2;
                        $retEvents['approvedUser3'] = $approvedUser3;
                        $retEvents['approvedUser4'] = $approvedUser4;
                        $retEvents['approvedUser5'] = $approvedUser5;
                        $appType = $this->getKeyNameDesc('otType'.ucfirst($OT[0]['appType']), $_language);
                        $approvedType = $this->getKeyNameDesc('otType'.ucfirst($OT[0]['approvedType']), $_language);
                        $isVolunteer = $this->getKeyNameDesc('isVolunteer'.ucfirst($OT[0]['isVolunteer']), $_language);
                        if ($OT[0]['appType'] <> 'absence' AND ($OT[0]['approvedType'] <> 'absence' OR $OT[0]['approvedType'] <> '')) {
                            $appSettleType = $this->getKeyNameDesc('otSettleType'.ucfirst($OT[0]['appSettleType']), $_language);
                            $approvedSettleType = $this->getKeyNameDesc('otSettleType'.ucfirst($OT[0]['approvedSettleType']), $_language);
                            $retEvents['appSettleType'] = $appSettleType[0]['Descr'];
                            $retEvents['approvedSettleType'] = $approvedSettleType[0]['Descr'];
                            $retEvents['isVolunteer'] = $isVolunteer[0]['Descr'];
                        } else {
                            $retEvents['appSettleType'] = "-";
                            $retEvents['approvedSettleType'] = "-";
                            $retEvents['isVolunteer'] = "-";
                        }
                        $retEvents['appType'] = $appType[0]['Descr'];
                        $retEvents['approvedType'] = $approvedType[0]['Descr'];
                        //$retEvents['appType'] = $OT[0]['title'];
//print_r($retEvents); die();
                        break;
                }
            }            
            return $retEvents;

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    private function getAbs ($_lnkCode, $_lang) {
        $SQL = "SELECT a.userId, d.code dept, a.appDate, a.appFromTime, a.appToTime, a.appReason,
                       a.recordFromTime, a.recordToTime, e.title,
                       a.formDate, a.formId, a.staffId, a.supervisorId, a.appSettleType, a.approvedSettleType,
                       a.approvedUser1, a.approvedUser2, a.approvedUser3, a.approvedUser4, a.approvedUser5,
                       a.comment1, a.comment2, a.comment3, a.comment4, a.comment5, wf.desc".$_lang." appStatus
                  FROM tblabsence a, tbldepartment d, tblperson p, tblwrkflowstatus wf, tblevents e
                 WHERE a.userId = p.userId AND p.deptId = d.id
                   AND e.status = wf.id AND e.lnkCode = a.lnkCode
                   AND a.lnkCode = '".$_lnkCode."';";
//echo $SQL; die();
        $retAbs = $this->_dbAdapter->fetchAll($SQL);
        $retAbs[1] = array("eventType" => "absence");
        return $retAbs;
    }

    private function getLate ($_lnkCode, $_lang) {
        $SQL = "SELECT l.userId, d.code dept, l.appDate, l.arrivedTime, l.appReason,
                       l.formDate, l.formId, l.staffId, l.supervisorId, l.type, 
                       l.approvedUser1, l.approvedUser2, l.approvedUser3, l.approvedUser4, l.approvedUser5,
                       l.comment1, l.comment2, l.comment3, l.comment4, l.comment5, wf.desc".$_lang." appStatus
                  FROM tbllate l, tbldepartment d, tblperson p, tblwrkflowstatus wf, tblevents e
                 WHERE l.userId = p.userId AND p.deptId = d.id
                   AND e.status = wf.id AND e.lnkCode = l.lnkCode
                   AND l.lnkCode = '".$_lnkCode."';";

        $retLate = $this->_dbAdapter->fetchAll($SQL);
        $retLate[1] = array("eventType" => "late");
        return $retLate;
    }

    private function getMod ($_lnkCode, $_lang) {
        $SQL = "SELECT m.userId, d.code dept, m.appFromDate, m.appToDate, m.morningRuleId, m.noonRuleId, m.appReason,
                       m.formDate, m.formId, m.staffId, m.supervisorId, m.weekdays, e.title appType,
                       m.approvedUser1, m.approvedUser2, m.approvedUser3, m.approvedUser4, m.approvedUser5,
                       m.comment1, m.comment2, m.comment3, m.comment4, m.comment5, wf.desc".$_lang." appStatus
                  FROM tblmodulation m, tbldepartment d, tblperson p, tblwrkflowstatus wf, tblevents e
                 WHERE m.userId = p.userId AND p.deptId = d.id
                   AND e.status = wf.id AND e.lnkCode = m.lnkCode
                   AND m.lnkCode = '".$_lnkCode."';";
//echo $SQL."<br>";
        $retMod = $this->_dbAdapter->fetchAll($SQL);
        $retMod[1] = array("eventType" => "modulation");
        return $retMod;
    }

    private function getOnL ($_lnkCode, $_lang) {
        $SQL = "SELECT o.userId, d.code dept, o.appFromDate, o.appToDate, o.appFromTime, o.appToTime, o.appReason,
                       t.desc".$_lang." appType, o.formDate, o.formId, o.staffId, o.supervisorId,
                       o.approvedUser1, o.approvedUser2, o.approvedUser3, o.approvedUser4, o.approvedUser5,
                       o.comment1, o.comment2, o.comment3, o.comment4, o.comment5, wf.desc".$_lang." appStatus
                  FROM tblonleave o, tblholtype t, tbldepartment d, tblperson p, tblwrkflowstatus wf, tblevents e
                 WHERE o.holTypeId = t.id AND o.userId = p.userId AND p.deptId = d.id
                   AND e.status = wf.id AND e.lnkCode = o.lnkCode
                   AND o.lnkCode = '".$_lnkCode."';";
//echo $SQL."<br>";
        $retOnL = $this->_dbAdapter->fetchAll($SQL);
        $retOnL[1] = array("eventType" => "onLeave");
        return $retOnL;
    }


    private function getOT ($_lnkCode, $_lang) {
        $SQL = "SELECT o.userId, d.code dept, o.appDate, o.appFromTime, o.appToTime, o.appReason, o.recordDate,
                       o.recordFromTime, o.recordToTime, o.isVolunteer, o.appType, o.approvedType,
                       o.formDate, o.formId, o.staffId, o.supervisorId, o.appSettleType, o.approvedSettleType,
                       o.approvedUser1, o.approvedUser2, o.approvedUser3, o.approvedUser4, o.approvedUser5,
                       o.comment1, o.comment2, o.comment3, o.comment4, o.comment5, wf.desc".$_lang." appStatus
                  FROM tblovertime o, tbldepartment d, tblperson p, tblwrkflowstatus wf, tblevents e
                 WHERE o.userId = p.userId AND p.deptId = d.id
                   AND e.status = wf.id AND e.lnkCode = o.lnkCode
                   AND o.lnkCode = '".$_lnkCode."';";
//echo $SQL; die();
        $retOT = $this->_dbAdapter->fetchAll($SQL);
        $retOT[1] = array("eventType" => "overtime");
        return $retOT;
    }

    private function getUserName ($_userId, $_lang) {
        $userInfo = "SELECT p.name".$_lang." name
                       FROM tblperson p
                      WHERE p.userId = '".$_userId."';";
        $_userInfo = $this->_dbAdapter->fetchAll($userInfo);
        if (sizeof($_userInfo) == 0) {
            $retUserName = '';
        } else {
            foreach ($_userInfo as $name) {
                $retUserName = $name['name']." (".$_userId.")";
            }
        }

        return $retUserName;
    }

     private function getTimePeriod ($_ruleId) {
        $timePeriod = "SELECT SUBSTR(fromTime, 1, 5) fromTime, SUBSTR(toTime, 1, 5) toTime
                       FROM tblwrkrule
                      WHERE id  = '".$_ruleId."';";
        $_timePeriod = $this->_dbAdapter->fetchAll($timePeriod);
        if (sizeof($_timePeriod) == 0) {
            $retTimePeriod = array("fromTime" => "", "toTime" => "");
        }else {
            $retUserName = $_timePeriod[0];
        }
        return $retUserName;
    }

     private function getKeyNameDesc ($_day, $_lang) {

        $dayDesc = "SELECT desc".$_lang." Descr FROM lang WHERE keyName = '".$_day."';";
//echo $dayDesc;
        $_dayDesc = $this->_dbAdapter->fetchAll($dayDesc);
        if (sizeof($_dayDesc) == 0)
            $_dayDesc[0] = array ("Descr" => '');
        return $_dayDesc;
    }


}
