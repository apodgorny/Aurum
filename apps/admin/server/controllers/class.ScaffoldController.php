<?
	class ScaffoldController extends Controller {
		public function index() {
			return array('greeting'=>'Hello from ScaffoldController!');
		}
		/**************************************************************/
		public function application() {
			QA::assertRequestValue('app','Please supply application name: ?app=[myappname]');
			$sAppName = strtolower($_REQUEST['app']);

			$sAurumDir = getcwd();
			$sAppDir = $sAurumDir.'/apps/'.$sAppName;

			$aDirs = array(
				$sAppDir,
				$sAppDir.'/client',
				$sAppDir.'/client/css',
				$sAppDir.'/client/img',
				$sAppDir.'/client/js',
				$sAppDir.'/server',
				$sAppDir.'/server/controllers',
				$sAppDir.'/server/renderers',
				$sAppDir.'/server/templates',
				$sAppDir.'/server/views',
				$sAppDir.'/server/settings',
			);


			$aFiles = array();
			/************************************************/
			$aFiles[$sAurumDir.'/'.$sAppName.'.php'] =
			/************************************************/
<<<STR
<? 
	require_once 'engine/mvc.php';
	MVC::run();
?>
STR;
			/************************************************/
			$aFiles[$sAppDir.'/server/settings/config.php'] = 
			/************************************************/
<<<STR
<?
	/* Application-specific config file - use to override settings in engine/config.php for this application */
?>
STR;

			/************************************************/
			$aFiles[$sAppDir.'/server/settings/cssincludes.php'] =
			/************************************************/
<<<STR
<?
	/** This file is used to include css files into application */

 	\$_ENV['CSS_INCLUDES'] = array(
		'apps/common/client/css/global.reset.css',
		'apps/common/client/css/global.fonts.css',
	);

?>
STR;
			/************************************************/
			$aFiles[$sAppDir.'/server/settings/jsincludes.php'] =
			/************************************************/
<<<STR
<?
	/** This file is used to include js files into application */

 	\$_ENV['JS_INCLUDES'] = array(
		// Example: apps/common/client/js/myjsfile.js
	);

?>
STR;
			/************************************************/
			$aFiles[$sAppDir.'/server/templates/default.phtml'] =
			/************************************************/
<<<STR
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<?= \MVC::getCSSIncludes() ?> 
	</head>
	<body>
		<div class="layout">
<!-- BEGIN VIEW -->
<? \$this->renderView(); ?> 
<!-- END VIEW -->
		</div>
	</body>
</html>
STR;
			/************************************************/
			Files::createDirectories($aDirs, true);
			Files::createFiles($aFiles, true);
			
			if (isset($_REQUEST['redirect'])) {
				Navigation::redirect(urldecode($_REQUEST['redirect']));
			}
		}
		/**************************************************************/
		public function controller() {
		    $sController = strtolower(Request::requireParam('controller'));
			$sAppName    = strtolower($_REQUEST['application_name']);
			$sControllerName = ucfirst($sController);

			$sAurumDir = getcwd();
			$sAppDir   = $sAurumDir.'/apps/'.$sAppName;
			$sViewDir  = $sAppDir.'/server/views/'.$sController;
			$sAuthor   = trim($_ENV['CODE_AUTHOR'])  ? "@author {$_ENV['CODE_AUTHOR']} <{$_ENV['CODE_AUTHOR_EMAIL']}>" : '';
			$sLicense  = trim($_ENV['CODE_LICENSE']) ? "@license {$_ENV['CODE_LICENSE']}" : '';

			$aDirs = array(
				$sViewDir
			);

			$aFiles = array();
			/**********************************/
			$aFiles[$sAppDir.'/server/controllers/class.'.$sControllerName.'Controller.php'] =
			/**********************************/
<<<STR
<?
    /**
     * class {$sControllerName}Controller
     * {$sAuthor}
     * {$sLicense}
     */
     
	class {$sControllerName}Controller extends Controller {
		
		/************** PRIVATE **************/
		
		/************** PUBLIC ***************/
		
		public \$sDefaultAction = 'index';

		public function index() {
			return array('greeting'=>'Hello from {$sControllerName}Controller!');
		}
	}
?>
STR;
			/**********************************/
			$aFiles[$sViewDir.'/index.phtml'] =
			/**********************************/
<<<STR
<?
    /**
     * Index View
     * {$sAuthor}
     * {$sLicense}
     */
?>
<?= \$this->greeting ?>
STR;

			Files::createDirectories($aDirs, true);
			Files::createFiles($aFiles, true);
			
			if (isset($_REQUEST['redirect'])) {
				Navigation::redirect(urldecode($_REQUEST['redirect']));
			}
		}
		/**************************************************************/
		public function view() {
			QA::assertRequestValue('app','Please supply application name: ?app=[myappname]');
			$sAppName = strtolower($_REQUEST['app']);
			QA::assertRequestValue('controller','Please supply controller name: app='.$sAppName.'&controller=[mycontrollername]');
			$sControllerName = ucfirst(strtolower($_REQUEST['controller']));
			QA::assertRequestValue('view','Please supply view name: app='.$sAppName.'&controller='.strtolower($sControllerName).'&view=[myviewname]');
			$sViewName = strtolower($_REQUEST['view']);

			$sAurumDir       = getcwd();
			$sAppDir         = $sAurumDir.'/apps/'.$sAppName;
			$sViewDir        = $sAppDir.'/server/views/'.strtolower($sControllerName);
			$sControllerPath = $sAppDir.'/server/controllers/class.'.$sControllerName.'Controller.php';
			$sAuthor         = trim($_ENV['CODE_AUTHOR'])  ? "@author {$_ENV['CODE_AUTHOR']} <{$_ENV['CODE_AUTHOR_EMAIL']}>" : '';
			$sLicense        = trim($_ENV['CODE_LICENSE']) ? "@license {$_ENV['CODE_LICENSE']}" : '';

			function debug($s) {
				print '<textarea style="width:400px;height: 300px;">'.$s.'</textarea>';
			}
			function addViewToController($sControllerPath, $sViewName) {
				$sContents = Files::openFile($sControllerPath);
				$sAction = "public function $sViewName() {\n\t\t}";
				$sActionMatch = "/public[\s]+function[\s]+{$sViewName}[\s]*\(/";
				if (!preg_match($sActionMatch, $sContents)) {
					debug($sContents);
					$sContents = preg_replace("/[^\}]*\}[\n\r\w]*\?>[\n\r\w]*$/","\n\t\t$sAction\n\t}\n?>", $sContents);
					debug($sContents);
				}
				Files::saveFile($sControllerPath, $sContents);
			}

			$aFiles = array();
			$aFiles[$sViewDir.'/'.$sViewName.'.phtml'] = 
<<<STR
<?
    /**
     * {$sViewName} View
     * {$sAuthor}
     * {$sLicense}
     */
?>
STR;
			
			addViewToController($sControllerPath, $sViewName);
			Files::createFiles($aFiles, true);
			
			if (isset($_REQUEST['redirect'])) {
				Navigation::redirect(urldecode($_REQUEST['redirect']));
			}
		}

	}
?>