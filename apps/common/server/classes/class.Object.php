<?
    /**
     * Class Object - every class (non-abstract, non-static) must inherit form Object
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

    class Object {
        
        /**************** PRIVATE ****************/
        
        /**************** PROTECTED **************/
        
        protected static $_aClassProperties = array();
        protected $_aProperties = array();
        
        /**************** STATIC ****************/

        public static function construct() {
            $aArgs = func_get_args();
            $nArgCount = count($aArgs);
            $aArgTokens = array();
            for ($i=0; $i<$nArgCount; $i++) {
                $aArgTokens[] = '$aArgs['.$i.']';
            }
            $sArgTokens = implode(',', $aArgTokens);
            $sEval = 'return new '.get_called_class().'('.$sArgTokens.');';
            return eval($sEval);
        }

        /**************** PUBLIC ****************/
        
        public function __construct() {
            if (!isset(self::$_aClassProperties[get_called_class()])) {
                self::$_aClassProperties[get_called_class()] = array();
            }
            $this->extend(self::$_aClassProperties[get_called_class()]);
        }
        public function __call($sMethod, $aArgs) {
            if (isset($this->_aProperties[$sMethod])) {
                $f = $this->_aProperties[$sMethod];
                $nArgCount = count($aArgs);
                $aArgTokens = array('$this');
                for ($i=0; $i<$nArgCount; $i++) {
                    $aArgTokens[] = '$aArgs['.$i.']';
                }
                $sArgTokens = implode(',', $aArgTokens);
                $sEval = 'return $f('.$sArgTokens.');';
                return eval($sEval);
            } else {
                throw new Exception('Method '.$sMethod.' does not exist in class '.get_class($this));
            }
        }
        public function __get($sProperty) {
            return isset($this->_aProperties[$sProperty]) 
                ? $this->_aProperties[$sProperty] 
                : null;
        }
        public function __set($sProperty, $mValue) {
            $this->_aProperties[$sProperty] = $mValue;
            return $mValue;
        }
        public function extend($mBase) {
            $sClass = get_called_class();
            if (!isset(self::$_aClassProperties[$sClass])) {
                self::$_aClassProperties[$sClass] = array();
            }
            
            if ($this) {                             // object call
                if (!is_array($mBase)) {
                    $mBase = $mBase->_aProperties;
                }
                $this->_aProperties = array_merge(
                    $this->_aProperties, 
                    $mBase
                );
            } else {                                 // static call
                if (is_string($mBase)) {            // class name is supplied
                    $mBase = self::$_aClassProperties[$mBase];
                }
                self::$_aClassProperties[$sClass] = array_merge(
                    self::$_aClassProperties[$sClass],
                    $mBase
                );
            }
        }
    }

?>