<?

    /**
     * System class
     * Provides methods to access OS API
     *
     * @author Konstantin Kouptsov <konstantin.kouptsov@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

     class System extends Object {

        /****************** PRIVATE *******************/
        
        /****************** PROTECTED *****************/
        /****************** PUBLIC ********************/
        
        public function __construct($sModuleName) {
            parent::__construct($sModuleName);
        }
        
        public static function getTempDirectory() {
    		//http://www.php.net/manual/en/function.sys-get-temp-dir.php
     		if ( !function_exists('sys_get_temp_dir') )
			{
		    	if ( !empty($_ENV['TMP'])) {
            		return realpath( $_ENV['TMP'] );
        		} else if ( !empty($_ENV['TMPDIR'])) {
            		return realpath( $_ENV['TMPDIR'] );
        		} else if ( !empty($_ENV['TEMP'])) {
            		return realpath( $_ENV['TEMP'] );
        		} else {
            		$temp_file = tempnam( md5(uniqid(rand(), TRUE)), '' );
            		if ( $temp_file ) {
                		$temp_dir = realpath( dirname($temp_file) );
                		unlink( $temp_file );
                		return $temp_dir;
           			} else {
                		return FALSE;
            		}
        		}
        	} else {
        		return sys_get_temp_dir();
        	}
        }
     }
     
    

?>