<?
    /**
     * XML 
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

    class XML extends Object {

        /****************** PRIVATE *******************/
        
        private $_oXML = null;
        
        /****************** PROTECTED *****************/
        /****************** PUBLIC STATIC *************/
        
        public static function fromXMLFile($sXMLFileName) {
            return new XML(simplexml_load_file($sXMLFileName));
        }
        /****************** PUBLIC *******************/
        
        public function __construct($oSimpleXML) {
            $this->_oXML = $oSimpleXML;
        }
        public function toSimpleXML() {
            return $this->_oXML;
        }
        public function toArray($oXML=null) {
            if (!$oXML) {
                $oXML = $this->_oXML;
            }
            if (is_object($oXML) && get_class($oXML) == 'SimpleXMLElement') {
                $oXML = get_object_vars($oXML);
            }
            if (is_array($oXML)) {
                $a = array();
                if (count($oXML) == 0) {
                    return (string)$oXML; // for CDATA
                }
                foreach($oXML as $sKey=>$oValue) {
                    $a[$sKey] = call_user_func_array(array($this, 'toArray'), array($oValue));
                    if (!is_array($a[$sKey])) {
                        $a[$sKey] = utf8_decode($a[$sKey]);
                    }
                }
                return $a;
            }
            return (string) $oXML;
        }
    }
/*
function simplexml_to_array($oXML,$attribsAsElements=0) {
  if (get_class($oXML) == 'SimpleXMLElement') {
     $aAttributes = $oXML->attributes();
     foreach($aAttributes as $k=>$v)
         if ($v) $a[$k] = (string) $v;
     $x = $oXML;
     $oXML = get_object_vars($oXML);
 }
 if (is_array($oXML))
 {
     if (count($oXML) == 0) return (string) $x; // for CDATA
     foreach($oXML as $key=>$value)
     {
         $r[$key] = simplexml_to_array($value,$attribsAsElements);
         if (!is_array($r[$key])) $r[$key] = utf8_decode($r[$key]);
     }
     return $r;
  }
  return (string) $oXML;
}*/    

?>
