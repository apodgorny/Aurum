<?
    /**
     * Class Definition
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

    class Definition extends Object {
        public static function get($sFileNamePrefix, $sFileNameRoot) {
            $sFileNamePrefix = $sFileNamePrefix;
            if ($sFileNameRoot && preg_match('/\./', $sFileNameRoot)) {
                $sFileName = 'definition.'.$sFileNameRoot.'.php';
            } else {
                $sFileName = 'definition.'.$sFileNamePrefix.'.'.$sFileNameRoot.'.php';
            }
            
            $aPaths = $_ENV['AUTOLOAD_DEFINITION_PATHS'];
            foreach ($aPaths as $sPath) {
            	$sFilePath = $sPath.$sFileName;
	            if (!file_exists($sFilePath)) { break; }
	            include $sFilePath;
	            if (!isset($aDefinition)) {
	                throw new Exception(
	                    Text::getText('Variable').
	                    ' $aDefinition '.
	                    Text::getText('is not defined in').' '.
	                    $sFilePath
	                );
	            }
	            return $aDefinition;
            }
            return array();
        }
    }

?>