<?php

class Option extends Zend_Db_Table_Abstract {

    protected $_dbAdapter = null;
    private $_optionKeys = array('mediaBasePath', 'mediaBaseUrl', 'uploadBasePath', 'baseUrl');
    
    public function __construct() {
        parent::__construct();
        $this->_dbAdapter = $this->getDefaultAdapter();
    }

    public function getAll() {
        $sql = "SELECT * FROM `option`;";
        $result = $this->_dbAdapter->fetchAll($sql);
        $data = array();
        foreach ($result as $row) {
            $data[$row['key']] = $row['value'];
        }
        return $data;
    }
    
    public function strnopos($haystack, $needle, $nooftime = 1){
        $retPos = 0;
        $_haystack = explode('/', $haystack);
        if (sizeof($_haystack) < $nooftime)
            $nooftime = sizeof($_haystack);
        for ($iLoop = 0; $iLoop < $nooftime; $iLoop++){
            $retPos = $retPos + strlen($_haystack[$iLoop]) + 1;
        }
        //echo $retPos;
        return $retPos;
    }

    public function getLogo($url){
        $logo = array ("onclick"=>'task();', 'src' => $url.'/images/MSC_logo.jpg');
        return $logo;
    }

    public function getTitle($url){
        $title = "Welcome HR System";
        return $title;
    }

     public function getUserFunc($_userFunc='', $usrId=''){
//echo $_userFunc."<br>";
         while(strlen($_userFunc) >0 ){
            $strFunction = substr($_userFunc, 0, strpos($_userFunc,";"));
            if (strlen($strFunction) >0 ){
                $strFns = substr($strFunction, 0, strpos($strFunction, ","));
                $strFnsAtt = substr($strFunction, strpos($strFunction, ",")+1, strlen($strFunction));
                $strFnsAttribRead = substr($strFnsAtt, 0, strpos($strFnsAtt, ","));
                $strFnsAttribWrite = substr($strFnsAtt, strrpos($strFnsAtt, ",")+1, strlen($strFunction));
                $strFunction = '';
//echo $strFns."<br>";
                $fnsHeader = explode("_", $strFns);
//print_r($fnsHeader);
                if (!isset ($userFn[$fnsHeader[0]])) {
                    switch ($fnsHeader[0]) {
                        case "fn":
                            $userFn[$fnsHeader[0]] = array('id' => "fn", 'li' => "假期", 'onclick' => "");
                            break;

                        case "app":
                            $userFn[$fnsHeader[0]] = array('id' => "apply", 'li' => "申請", 'onclick' => "");
                            break;

                        case "rpt":
                            $userFn[$fnsHeader[0]] = array('id' => "report", 'li' => "報表", 'onclick' => "");
                            break;

                        case "wifi":
                            $userFn[$fnsHeader[0]] = array('id' => "wifi", 'li' => "WiFi", 'onclick' => "");
                            break;

                        case "sys":
                            $userFn[$fnsHeader[0]] = array('id' => "system", 'li' => "系統", 'onclick' => "");
                            break;

                        case "gpg":
                            $userFn[$fnsHeader[0]] = array('id' => "gpg", 'li' => "GPG", 'onclick' => "");
                            break;
                    }
                }

                $id = "";
                $li = "";
                $onClick = "";
                switch ($fnsHeader[1]){
                    case 'changepasswd':
                        if ($strFnsAttribRead == "+r"){
                            $id = 'changepasswd';
                            $li = '更改密碼';
                            $onClick = '';
                        }
    //                    if ($strFnsAttribRead == "+w")
                        break;

                    case 'modulation':

                        if ($strFnsAttribRead == "+r"){
                            $id = 'app_modify';
                            $li = '申請調整返工時間';
                            $onClick = '';
                        }
    //                    if ($strFnsAttribRead == "+w")
                        break;

                    case 'downloadkey':
                        $n = '';
                        $strSQLDelPrivGPGKey = "UPDATE tblsyskey SET privateKey = '' WHERE privCount <= 0;";
                        $data = array('privateKey' => '');
                        $where[] = "privCount <= 0";
                        $n = $this->_dbAdapter->update('tblsyskey', $data, $where);

                        $strSQLDelUpLoadGPGKey = "UPDATE tblsyskey SET upoadKey = '' WHERE uploadCount <= 0;";
                        $data = array('uploadKey' => '');
                        $where[] = "uploadCount <= 0";
                        $n = $this->_dbAdapter->update('tblsyskey', $data, $where);

                        $strSQLGPGKey = "SELECT k.privCount, k.uploadCount, SUBSTR(k.publicKey,1,10) PublicKey,
                                                SUBSTR(k.privateKey,1,10) PrivateKey,
                                                SUBSTR(k.uploadKey,1,10) UploadKey
                                           FROM tblsyskey k, tblperson p
                                          WHERE p.userId = '".$usrId."' AND k.email = p.email;";
                        $arrGPGKey = $this->_dbAdapter->fetchAll($strSQLGPGKey);

                        if ($arrGPGKey[0]['privCount'] > 0 OR $arrGPGKey[0]['uploadCount'] > 0){
                            if ($strFnsAttribRead == "+r"){
                                $id = 'downloadkey';
                                $li = '下載GPG Key';
                                $onClick = '';
                            }
        //                    if ($strFnsAttribRead == "+w")
                        }
                        break;

                    case 'onleave':
                        if ($strFnsAttribRead == "+r"){
                            $id = 'app_onleave';
                            $li = '申請假期/公幹/活動';
                            $onClick = '';
                        }
    //                    if ($strFnsAttribRead == "+w")
                        break;

                    case 'late':
                        if ($strFnsAttribRead == "+r"){
                            $id = 'late';
                            $li = '遲到/欠出勤';
                            $onClick = '';
                        }
    //                    if ($strFnsAttribRead == "+w")
                        break;

                    case 'late':
                        if ($strFnsAttribRead == "+r"){
                            $id = 'laterpt';
                            $li = '<<遲/欠解釋>>簽署';
                            $onClick = '';
                        }
    //                    if ($strFnsAttribRead == "+w")
                        break;

                    case 'overtime':
                        if ($strFnsAttribRead == "+r"){
                            $id = 'overtime';
                            $li = '非辦公時間工作';
                            $onClick = '';
                        }
    //                    if ($strFnsAttribRead == "+w")
                        break;

                    case 'absence':
                        if ($strFnsAttribRead == "+r"){
                            $id = 'absence';
                            $li = '因私外出';
                            $onClick = '';
                        }
    //                    if ($strFnsAttribRead == "+w")
                        break;

                    case 'att':
                        if ($strFnsAttribRead == "+r"){
                            $id = 'attrpt';
                            $li = '職員考勤記錄';
                            $onClick = '';
                        }
    //                    if ($strFnsAttribRead == "+w")
                        break;

                    case 'modulation':
                        if ($strFnsAttribRead == "+r"){
                            $id = 'modulation';
                            $li = '更改返工時間';
                            $onClick = '';
                        }
    //                    if ($strFnsAttribRead == "+w")
                        break;

                    case 'onleave':
                        if ($strFnsAttribRead == "+r"){
                            $id = 'onleave';
                            $li = '假期/公幹/活動';
                            $onClick = '';
                        }
    //                    if ($strFnsAttribRead == "+w")
                        break;

                    case 'late':
                        if ($strFnsAttribRead == "+r"){
                            $id = 'late';
                            $li = '遲到/欠出勤記錄';
                            $onClick = '';
                        }
    //                    if ($strFnsAttribRead == "+w")
                        break;

                    case 'excelsummaryrpt':
                        if ($strFnsAttribRead == "+r" and $_SESSION['sub'] == true) {
                            $id = 'excelsummaryrpt';
                            $li = '(簡)Excel遲/缺勤記錄報表';
                            $onClick = '';
                        }
    //                    if ($strFnsAttribRead == "+w")
                        break;

                    case 'excelallrpt':
                        if ($strFnsAttribRead == "+r" and $_SESSION['sub'] == true){
                            $id = 'excelallrpt';
                            $li = '(詳)Excel遲/缺勤記錄報表';
                            $onClick = '';
                        }
    //                    if ($strFnsAttribRead == "+w")
                        break;

                    case 'all':
                        if ($strFnsAttribRead == "+r" and $_SESSION['sub'] == true){
                            $id = 'allrpt';
                            $li = '(詳)遲/缺勤記錄報表';
                            $onClick = '';
                        }
    //                    if ($strFnsAttribRead == "+w")
                        break;

                    case 'summaryrpt':
                        if ($strFnsAttribRead == "+r" and $_SESSION['sub'] == true){
                            $id = 'summaryrpt';
                            $li = '(簡)遲/缺勤記錄報表';
                            $onClick = '';
                        }
    //                    if ($strFnsAttribRead == "+w")
                        break;

                    case 'personalinfo':
                        if ($strFnsAttribRead == "+r") {
                            $id = 'personalinfo';
                            $li = '職員個人資料';
                            $onClick = '';
                        }
    //                    if ($strFnsAttribRead == "+w")
                        break;

                    case 'importatt':
                        if ($strFnsAttribRead == "+r"){
                            $id = 'importatt';
                            $li = '載入返工記錄';
                            $onClick = '';
                        }
    //                    if ($strFnsAttribRead == "+w")
                        break;

                    case 'sendemail':
                        if ($strFnsAttribRead == "+r") {
                            $id = 'sendemail';
                            $li = '電郵考勤記錄';
                            $onClick = '';
                        }
    //                    if ($strFnsAttribRead == "+w")
                        break;

                    case 'holiday':
                        if ($strFnsAttribRead == "+r"){
                            $id = 'holiday';
                            $li = '公司/員工假期一覽';
                            $onClick = 'companyholiday()';
                        }
    //                    if ($strFnsAttribRead == "+w")
                        break;

                    case 'logout':
                        if ($strFnsAttribRead == "+r"){
                            $id = 'logout';
                            $li = '登 出';
                            $onClick = 'logout()';
                        }
    //                    if ($strFnsAttribRead == "+w")
                        break;

                    case 'mainrpt':
                        if ($strFnsAttribRead == "+r"){
                            $id = 'mainrpt';
                            $li = '遲/缺勤記錄報表';
                            $onClick = '';
                        }
    //                    if ($strFnsAttribRead == "+w")
                        break;

                    case 'apply':
                        if ($strFnsAttribRead == "+r"){
                            $id = 'apprpt';
                            $li = '個人申請記錄';
                            $onClick = 'report()';
                        }
    //                    if ($strFnsAttribRead == "+w")
                        break;

                    case 'chpasswd':
                        if ($strFnsAttribRead == "+r"){
                            $id = 'chpasswd_wifi';
                            $li = '更改WiFi密碼';
                            $onClick = 'wifiChPasswd()';
                        }
    //                    if ($strFnsAttribRead == "+w")
                        break;
                }
                $userFn[$fnsHeader[0]][0][] = array('id' => $id, 'li' => $li, 'onclick' => $onClick);
            }
            $_userFunc = substr($_userFunc, strpos($_userFunc, ";")+1, strlen($_userFunc));

        }
        
//print_r($userFn); die();
        return $userFn;
    }

    public function getUserMain($usrId='', $date){
        try {
            $task = "SELECT e.*, s.*
                       FROM tblevents e, tblwrkflowstatus s
                      WHERE e.fromUserId='".$usrId."' AND
                           (datediff(substr(e.toDate,1,10),'".date('Y/m/d', strtotime('-2 weeks', strtotime($date)))."') >=0 OR e.status <> '9') AND
                            e.status = s.id
                   ORDER BY e.fromDate DESC;";
//echo $companyHoliday."<br>";
            $_task = $this->_dbAdapter->fetchAll($task);
            return $_task;

        } catch (Exception $e) {
                return $e->getMessage();
        }
    }

    public function getUserStatus($usrId='', $url){
        $result = '';
        $result .= "\n".'<span class="Status">Status</span>';
        return $result;
    }


}
