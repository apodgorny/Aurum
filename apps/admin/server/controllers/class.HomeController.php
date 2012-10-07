<?
	class HomeController extends Controller {
		
		/************ PRIVATE ***************/
		
		private function _getAddViewForm($sAppName, $sControllerName) {
			$oForm = new Form();
			$oForm->action = Navigation::link('admin', 'scaffold', 'view');
			$oForm->method = 'post';
				
			$oFormElementText = new FormElementText(array('name'=>'view'));
			$oForm->addElement($oFormElementText);
			
			$oForm->addElement(new FormElementSubmit(array('value'=>'Add view')));
			$oForm->addElement(new FormElementHidden(array('name'=>'app', 'value'=>$sAppName)));
			
			$oForm->addElement(new FormElementHidden(array('name'=>'controller', 'value'=>$sControllerName)));
			$oForm->addElement(new FormElementHidden(array('name'=>'redirect', 
				'value' => urlencode(Navigation::link('admin','index','views', array(
					'application' => $sAppName,
					'controller' => $sControllerName
				)))
			)));
			
			return $oForm;
		}
		private function _getAddControllerForm($sAppName) {
			$oForm = new Form();
			$oForm->action = Navigation::link('admin', 'scaffold', 'controller');
			$oForm->method = 'post';
				
			$oFormElementText = new FormElementText(array('name'=>'controller'));
			$oForm->addElement($oFormElementText);
			
			$oForm->addElement(new FormElementSubmit(array('value'=>'Add controller')));
			$oForm->addElement(new FormElementHidden(array('name'=>'application_name', 'value'=>$sAppName)));
			
			$oForm->addElement(new FormElementHidden(array('name'=>'redirect', 
			    'value'=> urlencode(Navigation::link('admin','index','controllers', array(
					'application' => $sAppName
				)))
			)));
			
			return $oForm;
		}
		
		/************ PUBLIC ****************/
		
		public function index() {
	    }
		public function applications() {
			$aApps = array_keys(Aurum::getApplications());
			$aAppsAndForms = array();
			
			foreach ($aApps as $sAppName) {
				$aAppsAndForms[] = array(
					'name' => $sAppName,
					'add_controller_form' => $this->_getAddControllerForm($sAppName)
				);
			}
			return array(
				'apps' => $aAppsAndForms
			);
		}
		public function controllers() {
			$sAppName = QA::assertRequestValue('application');
			$aControllerFileNames = array_keys(Aurum::getControllers($sAppName));
			$aControllers = array();
			$oAddControllerForm = $this->_getAddControllerForm($sAppName);
			
			foreach ($aControllerFileNames as $sControllerFileName) {
				if (preg_match('/^class\.([a-z0-9]+)Controller\.php$/i', $sControllerFileName, $aMatches)) {
					$sControllerName = Text::toDashedCase($aMatches[1]);
					$oAddViewForm = $this->_getAddViewForm($sAppName, $sControllerName);
					$aControllers[] = array(
						'file'=>$sControllerFileName,
						'name'=>$sControllerName,
						'add_view_form'=>$oAddViewForm
					);  
				}
			}
			return array(
				'appName' => $sAppName,
				'controllers' => $aControllers,
				'add_controller_form' => $oAddControllerForm
			);	
		}
		public function views() {
			$sAppName = QA::assertRequestValue('application');
			$sControllerName = QA::assertRequestValue('controller');
			$aViewFileNames = array_keys(Aurum::getViews($sAppName, $sControllerName));
			$oAddViewForm = $this->_getAddViewForm($sAppName, $sControllerName);
			$aViews = array();
			foreach ($aViewFileNames as $sViewFileName) {
				if (preg_match('/^([^\.]+)\.phtml$/i', $sViewFileName, $aMatches)) {
					$aViews[] = array(
						'file'=>$sViewFileName, 
						'name'=>$aMatches[1]
					);  
				}
			}
			return array(
				'appName' => $_REQUEST['application'],
				'controllerName' => $sControllerName,
				'views' => $aViews,
				'add_view_form' => $oAddViewForm
			);
		}
	}
?>