<?

	class Debug extends Object {

	    const SESSION_KEY = '___debug___';

	    /***************** PRIVATE *****************/
	    /***************** PUBLIC ******************/

		public static function show() {
			print '<pre style="border: 1px dashed #CCC; padding: 10px;">';
			foreach (func_get_args() as $mValue) {
			    if (!$mValue) {
        	        if (is_array($mValue)) {
        	            $mValue = 'Empty array';
    	            } else if ($mValue === null) {
    	                $mValue = 'null';
	                } else if ($mValue === false) {
                        $mValue = 'false';
	                }
    	        }
			    if (is_object($mValue)) { $mValue = method_exists($mValue, '__toString') ? (string)$mValue : $mValue; }
			    print (htmlentities(print_r($mValue, 1)));
			    print "<br>";
		    }
			print '</pre>';
		}
		public static function showAndDie() {
		    print '<pre style="border: 1px dashed #CCC; padding: 10px;">';
			foreach (func_get_args() as $mValue) {
			    if (!$mValue) {
        	        if (is_array($mValue)) {
        	            $mValue = 'Empty array';
    	            } else if ($mValue === null) {
    	                $mValue = 'null';
	                } else if ($mValue === false) {
                        $mValue = 'false';
                    }
    	        }
			    if (is_object($mValue)) { $mValue = method_exists($mValue, '__toString') ? (string)$mValue : $mValue; }
			    print (htmlentities(print_r($mValue, 1)));
			    print "<br>";
		    }
			print '</pre>';
			exit();
		}
		public static function log() {
		    $s = '';
			foreach (func_get_args() as $mValue) {
			    if (!$mValue) {
        	        if (is_array($mValue)) {
        	            $mValue = 'Empty array';
    	            } else if ($mValue === null) {
    	                $mValue = 'null';
	                } else if ($mValue === false) {
                        $mValue = 'false';
                    }
    	        }
			    if (is_object($mValue)) { $mValue = method_exists($mValue, '__toString') ? (string)$mValue : $mValue; }
		        $s .= print_r($mValue,1)."\n";
		    }
		    error_log($s);
		}
		public static function trace() {
		    $s = '';
			foreach (func_get_args() as $mValue) {
			    $s .= '<pre style="border: 1px dashed #CCC; padding: 10px;">';
			    if (!$mValue) {
        	        if (is_array($mValue)) {
        	            $mValue = 'Empty array';
    	            } else if ($mValue === null) {
    	                $mValue = 'null';
	                } else if ($mValue === false) {
                        $mValue = 'false';
                    }
    	        }
			    if (is_object($mValue)) { $mValue = method_exists($mValue, '__toString') ? (string)$mValue : $mValue; }
		        $s .= htmlentities(print_r($mValue, 1))."</pre>";
		    }
		    throw new Exception($s);
		}

		public static function profileClear() {
		    if (isset($_SESSION[self::SESSION_KEY])) {
		        unset($_SESSION[self::SESSION_KEY]);
	        }
	    }
		public static function profile($sName) {
		    if (!isset($_SESSION[self::SESSION_KEY])) {
		        $_SESSION[self::SESSION_KEY] = array();
	        }
		    $_SESSION[self::SESSION_KEY][$sName] = microtime(true);
	    }
	    public static function profileShow() {
	        if (!isset($_SESSION[self::SESSION_KEY])) {
		        $_SESSION[self::SESSION_KEY] = array();
	        }
	        $aProfileDeltas = array();
	        $nPrevTime = 0;
	        $sPrevName = '';
	        foreach ($_SESSION[self::SESSION_KEY] as $sName=>$nTime) {
	            if (!$nPrevTime) {
	                $nPrevTime = $nTime;
	                $sPrevName = $sName;
	                continue;
                }
                $aProfileDeltas[$sPrevName.' --> '.$sName] = (($nTime - $nPrevTime) * 1000000) . ' microseconds';
            }
            Debug::log($aProfileDeltas);
        }
	}

?>