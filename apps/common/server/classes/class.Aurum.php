<?

 	class Aurum {
		public static function getApplications() {
			return Files::getDirectories($_ENV['PATH_TO_APPS'], array('^\.', 'common'));
		}
		public static function getControllers($sAppName) {
			return Files::getFiles($_ENV['PATH_TO_APPS'].'/'.$sAppName.'/server/controllers/', array('^\.'));
		}
		public static function getViews($sAppName, $sControllerName) {
			return Files::getFiles($_ENV['PATH_TO_APPS'].'/'.$sAppName.'/server/views/'.$sControllerName.'/', array('^\.'));
		}
	}

?>