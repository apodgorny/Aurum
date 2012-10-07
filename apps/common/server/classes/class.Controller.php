<?

	class Controller extends Object {

		/******** PROTECTED **********/

		protected static $_oThis    = null;
		protected $_sView           = null;
		protected $_sPageTitle      = null;
		protected $_aViewVars       = array();
		
		protected function _getTemplateFileName() {
		    if (Request::hasParam('template')) {
		        $sTemplate = $_ENV['PATH_TO_TEMPLATES'].Request::requireParam('template').'.phtml';
	        } else {
    			$sTemplate = $_ENV['PATH_TO_TEMPLATES'].($this->sDefaultTemplate
    				? $this->sDefaultTemplate
    				: $_ENV['DEFAULT_TEMPLATE']
    			).'.phtml';
            }
			QA::assertFileExists(
				$sTemplate,
				TEXT::getText('Template').' '.$sTemplate.' '.TEXT::getText('does not exist')
			);

			return $sTemplate;
		}

		/*********** PUBLIC **********/

		public $sDefaultAction;
		public $sDefaultTemplate;
		/**
		 * Returns an instance of a current controller
		 * @example Controller::getInstance()->link('myview');
		 * @static
		 */
		public static function getInstance() {
			return self::$_oThis;
		}
		/**
		 * Constructs a controller
		 */
		public function __construct() {
			Controller::$_oThis = $this;
		}
	    /**
	     * Specifies which template to use. Called inside of action method.
	     */
		public function useTemplate($sTemplate) {
			$this->sDefaultTemplate = $sTemplate;
		}
		/**
		 * Specifies which view to render. Called inside of action method.
		 */
		public function useView($sView) {
			$this->_sView = $_ENV['PATH_TO_VIEWS'].$_ENV['CONTROLLER'].'/'.$sView.'.phtml';
		}
		public function setExpectedParams($aExpected) {
		    QA::filterRequest($aExpected);
	    }
	    public function setRequiredParams($aRequired) {
	        QA::assertRequestValues($aRequired);
        }
		/**
		 *
		 */
		public function renderView() {
			if ($this->_sView) {
				QA::assertFileExists($this->_sView);
				include $this->_sView;
			} else {
				if (file_exists($_ENV['PATH_TO_VIEW'])) {
					include $_ENV['PATH_TO_VIEW'];
				}
			}
		}
		/**
		 * Can be in one of the following forms:
		 * $this->link(controller, [action], [additional params])
	     * $this->link([action], [additional params]) - current controller is assumed
		 */
		public function link($sParam1, $mParam2=array(), $mParam3=array()) {
			if (is_string($mParam2)) {
				$sController = $sParam1;
				$sAction = $mParam2;
				$aParams = $mParam3;
			} else if (is_array($mParam2)) {
				$sController = $_ENV['CONTROLLER'];
				$sAction = $sParam1;
				$aParams = $mParam2;
			}
			return Navigation::link($_ENV['APP'], $sController, $sAction, $aParams);
		}
		public function externalLink($sParam1, $mParam2=array(), $mParam3=array()) {
			if (is_string($mParam2)) {
				$sController = $sParam1;
				$sAction = $mParam2;
				$aParams = $mParam3;
			} else if (is_array($mParam2)) {
				$sController = $_ENV['CONTROLLER'];
				$sAction = $sParam1;
				$aParams = $mParam2;
			}
			return Navigation::externalLink($_ENV['APP'], $sController, $sAction, $aParams);
		}
		public function redirect($sParam1, $mParam2=array(), $mParam3=array()) {
			if (is_string($mParam2)) {
				$sController = $sParam1;
				$sAction = $mParam2;
				$aParams = $mParam3;
			} else if (is_array($mParam2)) {
				$sController = $_ENV['CONTROLLER'];
				$sAction = $sParam1;
				$aParams = $mParam2;
			}
			return Navigation::redirect($_ENV['APP'], $sController, $sAction, $aParams);
		}
		public function getPageTitle() {
		    return $this->_sPageTitle;
	    }
	    public function setPageTitle($sTitle) {
	        $this->_sPageTitle = $sTitle;
        }
		public function __call($sMethod, $aParams) {
		    // Remove leading underscore
			$sMethod = substr($sMethod, 1);

			// If method is not present, try calling controller's default action
			if (!method_exists($this, $sMethod)) {
			    $sMethod = $this->sDefaultAction;
		    }
		    // If controller's default action method is not present, try calling mvc default action
			if (!method_exists($this, $sMethod)) {
			    $sMethod = $_ENV['DEFAULT_ACTION'];
		    }
		    // If default method is not present - fail miserably
			QA::assertMethodExists($this, $sMethod);

			// Set view by method by default
			$this->useView($sMethod);

			// Call action method
			$aViewVars = call_user_func_array(array($this, $sMethod), $aParams);

			// Add action return values to the controller object
			if (is_array($aViewVars)) {
				foreach ($aViewVars as $sKey=>$mValue) {
					$this->_aViewVars[$sKey] = $mValue;
				}
			}
			// Include template file that calls Controller::renderView()
			include $this->_getTemplateFileName();
		}
		public function __set($sKey, $mValue) {
		    $this->_aViewVars[$sKey] = $mValue;
	    }
		public function __get($sKey) {
		    if (isset($this->_aViewVars[$sKey])) {
		        return $this->_aViewVars[$sKey];
		    }
		    return null;
	    }

		/********* ACTIONS *********/

	}

?>