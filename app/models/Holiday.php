<?php

class Holiday extends Zend_Db_Table_Abstract {

    protected $_dbAdapter = null;
    
    public function __construct() {
        parent::__construct();
        $radiusConfig = new Zend_Config_Ini(BP . '/app/config.ini', 'database');
        $this->_dbAdapter = $this->getDefaultAdapter();

    }

    public function getCompanyHoliday ($_date){
        try {
            $companyHoliday = "SELECT * FROM tblholiday
                                WHERE DATEDIFF(id,".date('Ymd',strtotime('-2 months', strtotime($_date))).") >= 0
                                   OR id like '".date('Y')."%'
                                   OR DATEDIFF(id,".date('Ymd',strtotime('+2 months', strtotime($_date))).") >= 0
                             ORDER BY id;" or die(mysql_error());

            $_compHoliday = $this->_dbAdapter->fetchAll($companyHoliday);
            return $_compHoliday;
            
        } catch (Exception $e) {
                return $e->getMessage();
        }
    }

    public function getUserHoliday ($_userId, $_date){

        try {
            $holidayType = $this->getHolidayType();
            foreach ($holidayType AS $HT) {
                $key = $HT['id'];
                $_onLQuota = $this->getHolidayQuota($_userId, $_date, $HT['id']);
                $_onLQuotaLastYear = $this->getOnLQuotaLastYear($_userId, $_date, $HT['id']);
                $_onLTook = $this->getOnLTook($_userId, $_date, $HT['id']);
                $onLQuota[$key] = $_onLQuota[0];                
                $onLQuotaLastYear[$key] = $_onLQuotaLastYear[0];
                $onLTook[$key] = $_onLTook[0];
            }
            $userHoliday['holidayType'] = $holidayType;

            $userHoliday['onLQuota'] = $onLQuota;
            $userHoliday['onLQuotaLastYear'] = $onLQuotaLastYear;
            $userHoliday['onLTook'] = $onLTook;
            $userHoliday['holidayYear'] = date('Y', strtotime($_date));
            return $userHoliday;
        } catch (Exception $e) {
                return $e->getMessage();
        }
    }

    private function getHolidayType () {
        $holidayType = "SELECT id, descC FROM tblholtype";
        $_holidayType = $this->_dbAdapter->fetchAll($holidayType);
        return $_holidayType;
    }

    private function getHolidayQuota ($_userId, $_date, $_holtype) {
        $onLQuota = "SELECT day, carryday, borrowday
                       FROM tblonlquota
                      WHERE userId = '".$_userId."'
                        AND year = '".date('Y', strtotime($_date))."'
                        AND holTypeId = '".$_holtype."';";
        $_onLQuota = $this->_dbAdapter->fetchAll($onLQuota);
        if (sizeof($_onLQuota) == 0)
            $_onLQuota[0] =  array('day' => 0,
                                   'carryday' => 0,
                                   'borrowday' => 0);

        return $_onLQuota;
    }

    private function getOnLQuotaLastYear ($_userId, $_date, $_holtype) {
        $onLQuotaLastYear = "SELECT day
                               FROM tblonlquota
                              WHERE userId = '".$_userId."'
                                AND year = '".date('Y', strtotime($_date))."'
                                AND holTypeId = '".$_holtype."';";
//echo $onLQuotaLastYear."<br>";
        $_onLQuotaLastYear = $this->_dbAdapter->fetchAll($onLQuotaLastYear);
        if (sizeof($_onLQuotaLastYear) == 0)
            $_onLQuotaLastYear[0] =  array('day' => 0);

        return $_onLQuotaLastYear;
    }

    private function getOnLTook ($_userId, $_date, $_holtype) {
        $onLTook = "SELECT (SUM(ROUND(TIME_TO_SEC(TIMEDIFF(CONCAT(l.appToDate,' ',l.appToTime),
                            CONCAT(l.appFromDate,' ',l.appFromTime)))/86400)+
                            round(TIME_TO_SEC(TIMEDIFF(l.appToTime, l.appFromTime))/14400)*.5)) OnLeaveDays
                       FROM tblonleave l, tblholtype h, tblevents e
                       WHERE l.userId = '".$_userId."' AND l.holTypeId = h.id AND l.selfSigned <> '' AND
                             e.lnkCode = l.lnkCode AND e.status BETWEEN 2 AND 9  AND
                             l.holTypeId = '".$_holtype."' AND YEAR(l.appToDate) = '".date('Y', strtotime($_date))."'
                       GROUP BY l.holTypeId ORDER BY l.holTypeId;";
//echo $onLTook."<br>";
        $_onLTook = $this->_dbAdapter->fetchAll($onLTook);
        if (sizeof($_onLTook) == 0)
            $_onLTook[0] =  array('OnLeaveDays' => 0);
        return $_onLTook;
    }
}
