<?php
class Msc_View_Helper_Parse extends Zend_View_Helper_Abstract {

    private $_content = null;
    private $_type = null;
    private $_DELIMITER = '<%|%>';
    private $_SEPARATOR = '|';

    public function parse($content, $type = 'TAG') {
        $this->_content = $content;
        $this->_type = $type;
        return $this->_getParsedContent();
    }
    
    private function _getParsedContent() {
        $pcs = preg_split('/' . $this->_DELIMITER . '/', $this->_content);
        $pcsSize = count($pcs);
        $replacements = array();
        for ($i = 0; $i < $pcsSize; $i++) {
            $i++;
            if ($i < $pcsSize) {
                $result = $this->_doAction($pcs[$i]);
                if ($result) {
                    array_push($replacements, $result);
                }
            }
        }
        if ($this->_type == 'TAG') {
            if (count($replacements) > 0) {
                foreach ($replacements as $replacement) {
                    $pattern = '/<%(image|linked_image|popup|file)\([^(<%)]*\)%>/';
                    $this->_content = preg_replace($pattern, $replacement, $this->_content, 1);
                }
            }
            return $this->_content;
        }
        
        if ($this->_type == 'IMG_PARAM') {
            if (count($replacements) > 0) {
                return $replacements;
            }
        }
    }
    
    private function _doAction($action) {
        if (!$action) {
            return false;
        }
        if (strstr($action, '(')) {
            $paramStartPos = strpos($action, '(');
            $params = substr($action, $paramStartPos + 1, strlen($action) - $paramStartPos - 2);
            $action = substr($action, 0, $paramStartPos);
            $params = explode ($this->_SEPARATOR, $params);
            $params = array_map('trim', $params);
        } else {
            $params = array();
        }
        $action = strtolower($action);
        $option = Zend_Registry::get('option');
        $mediaUrl = $option['mediaBaseUrl'] . '/0/' . $params[0];
        switch ($action) {
            case 'image':
                if ($this->_type == 'TAG') {
                    if (($params[1] == '') || ($params[2] == '')) {
                        return '<img src=\'' . $mediaUrl . '\' title=\'' . $params[3] . '\' alt=\'' . $params[3] . '\' />';
                    } else {
                        return '<img src=\'' . $mediaUrl . '\' title=\'' . $params[3] . '\' alt=\'' . $params[3] . '\' width=\'' . $params[1] . '\' height=\'' . $params[2] . '\' />';
                    }
                }
                if ($this->_type == 'IMG_PARAM') {
                    return array('url' => $mediaUrl, 'description' => $params[3], 'width' => $params[1], 'height' => $params[2]);
                }
                break;
            case 'linked_image':
                if (($params[1] == '') || ($params[2] == '')) {
                    return '<a href=\'' . $params[4] . '\'><img src=\'' . $mediaUrl . '\' title=\'' . $params[3] .  '\' alt=\'' . $params[3] . '\' /></a>';
                } else {
                    return '<a href=\'' . $params[4] . '\'><img src=\'' . $mediaUrl . '\' title=\'' . $params[3] .  '\' alt=\'' . $params[3] . '\' width=\'' . $params[1] . '\' height=\'' . $params[2] . '\' /></a>';
                }
                break;
            case 'popup':
                return '<a href=\'' . $mediaUrl . '\' title=\'' . $params[3] .  '\' alt=\'' . $params[3] . '\' target=\'_blank\'>' . $params[3] . '</a>';
                break;
            case 'file':
                return '<a href=\'' . $mediaUrl . '\' title=\'' . $params[1] . '\'>' . $params[1] . '</a>';
                break;
            default:
                return false;
        }
    }
}
