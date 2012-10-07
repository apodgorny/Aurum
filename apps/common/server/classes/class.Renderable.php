<?
    /**
      * Renderable Class
      * @author Alexander Podgorny <ap.coding@gmail.com>
      * @license http://opensource.org/licenses/gpl-license.php GNU Public License
      */

    class Renderable extends Object {

        /************** PRIVATE **************/

        private $_aJSVarNames         = array();
        private $_sJSVarName          = '';
        private $_sRendererClass      = 'Renderer';
        private $_sName               = '';
        private $_bRenderingReadOnly  = false;
        private $_sAdditionalCssClass = '';
        private $_oRendererInstance   = null;

        private function _setRendererClass($sRenderer) {
            if (!preg_match('/Renderer$/', $sRenderer)) {
                $sRenderer .= 'Renderer';
            }
            $this->_sRendererClass = $sRenderer;
        }

        private function _setSpecificName($sName) {
            $sThisClass = get_class($this);
            $this->_sName = Text::toDashedCase(preg_replace('/$'.$sThisClass.'/', '', $sName), '-');
        }

        private function _processConfig() {
            if (isset($this->_aDefinition['name']))     { $this->_setSpecificName($this->_aDefinition['name']); }
            if (isset($this->_aDefinition['renderer'])) { $this->_setRendererClass($this->_aDefinition['renderer']); }
            if (isset($this->_aDefinition['class']))    { $this->_sAdditionalCssClass = $this->_aDefinition['class']; }
        }

        private function _getRendererInstance() {
            $sRendererClass = $this->_sRendererClass;
//Debug::show('$sRendererClass: ', $sRendererClass); // TODO: XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
            if (!$this->_oRendererInstance || get_class($this->_oRendererInstance) != $sRendererClass) {
                try {
                    $sRendererClass = preg_replace('/(ModelList|Model|Record)$/', '', get_class($this)).$sRendererClass;
                    $this->_oRendererInstance = new $sRendererClass();
                } catch (Exception $oException) {
                    try {
                        $sRendererClass = $this->_sRendererClass;
                        $this->_oRendererInstance =  new $sRendererClass();
                    } catch (Exception $oException) {
                        $this->_oRendererInstance = new Renderer();
                    }
                }
            }
            return $this->_oRendererInstance;
        }

        private function _setJSVarName() {
            $sJSVarName = strtolower(Text::toDashedCase(get_class($this), '-')).'-'.$this->_sName;
            $n = 1;
            $sJSVarNameTest = $sJSVarName.$n;
            while (isset($this->_aJSVarNames[$sJSVarNameTest])) {
                $sJSVarNameTest = $sJSVarName.$n;
                $n++;
            }
            $this->_aJSVarNames[$sJSVarNameTest] = 1;
            $this->_sJSVarName = $sJSVarNameTest;
        }

        /************** PROTECTED ************/

        protected $_aDefinition = array();

        /************** PUBLIC ***************/

        public function __construct($aDefinition='') {
            if (is_string($aDefinition)) {
                $sName = $aDefinition;
                $this->_setSpecificName($sName);
                $this->_aDefinition = Definition::get(get_class($this), $sName);
            } else if (is_array($aDefinition)) {
                if (isset($aDefinition['name'])) {
                    $this->_setSpecificName($aDefinition['name']);
                } else {
                    $this->_setSpecificName(get_class($this));
                }
                $this->_aDefinition = $aDefinition;
            }
            $sThisClass = preg_replace('/(Model|Record)$/', '', get_class($this));
            $this->_setRendererClass($sThisClass.'Renderer');
        }

        /**
         * $aConfig = array(
         *   renderer   => sRendererClassName   // to make generic css class
         *   name       => sName                // to make specific css class
         *   media      => nMediaType
         * )
         */
        public function render($aConfig=array()) {
            if (!$aConfig) { $aConfig = array(); }
            if (isset($this->_aDefinition['readonly']) && $this->_aDefinition['readonly']) {
                return $this->renderReadOnly($aConfig);
            }

            if (is_string($aConfig)) { $aConfig = array('renderer'=>$aConfig); }
            $this->_aDefinition = array_merge($this->_aDefinition, $aConfig);
            $this->_bRenderingReadOnly = false;
            $this->_processConfig();
            $this->_setJSVarName();

            return $this->_getRendererInstance()->render($this);
        }

        public function renderReadOnly($aConfig=array()) {
            if (is_string($aConfig)) { $aConfig = array('renderer'=>$aConfig); }
            $this->_aDefinition = array_merge($this->_aDefinition, $aConfig);
            $this->_bRenderingReadOnly = true;
            $this->_processConfig();
            $this->_setJSVarName();

            return $this->_getRendererInstance($aConfig)->renderReadOnly($this);
        }

        public function setDefaultRenderer($sRenderer) {
            $this->_setRendererClass($sRenderer);
        }

        public function getJsId() {
            return $this->_sJSVarName;
        }

        public function getJsClass() {
            return get_class($this);
        }

        public function getCssClass() {
            $sThisClass        = Text::toDashedCase(get_class($this));
            $sGenericCssClass  = Text::toDashedCase(preg_replace('/Renderer$/', '', $this->_sRendererClass), '-');
            $sSpecificCssClass = $this->_sName
                ? $sGenericCssClass . '-' . strtolower($this->_sName)
                : '';

            if ($this->_bRenderingReadOnly) {
                $sGenericCssClass = $sGenericCssClass ? $sGenericCssClass.'-readonly' : '';
                $sSpecificCssClass = $sSpecificCssClass ? $sSpecificCssClass.'-readonly' : '';
            }
            return implode(' ', array_unique(array_filter(array(
                $sThisClass,
                $sGenericCssClass,
                $sSpecificCssClass,
                $this->_sAdditionalCssClass
            ))));
        }

        public function getDefinition() {
            return $this->_aDefinition;
        }

        public function getDefinitionValue($sKey) {
            if (isset($this->_aDefinition[$sKey])) {
                return $this->_aDefinition[$sKey];
            }
            return null;
        }

        public function getName() {
            return $this->_sName;
        }

        public function registerJsComponent() {
            JavaScript::initComponent(
                $this->getJsId(),
                'App.'.$this->getJsClass(),
                array(
                    $this->getJsId(),
                    json_encode($this->_aDefinition)
                )
            );
        }


        /************ ACCESS METHODS *************/
        public function __set($sKey, $mValue) 	{
            $this->_aDefinition[$sKey] = $mValue;
        }

	    public function __get($sKey) {
	        if (isset($this->_aDefinition[$sKey])) {
	            if(is_array($this->_aDefinition[$sKey])) {
	                return (array) $this->_aDefinition[$sKey];
	            }
	            return $this->_aDefinition[$sKey];
	        }
	        return null;
	    }

	    public function __toString() {
	        return 'Class '.get_class($this).'::definition:'."\n".print_r($this->_aDefinition, 1);
        }
    }

?>
