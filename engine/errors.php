<?
 	function exception_handler($oException) {
		error_handler(
			E_USER_ERROR, 
			$oException->getMessage(), 
			str_replace($_ENV['INSTALLATION_PATH'], '', $oException->getFile()),
			$oException->getLine(),
			$oException->getTrace()
		);
	}
	function error_handler($nError, $sError, $sFile=null, $nLine=null, $aTrace=null) {
	    print '<style>body{font-family: arial; margin: 30px;}</style>';
	    print '<div style="text-align: left; clear: both; padding: 10px; background-color: #FFF; border: 10px solid #CCC; z-index: 10000; position: relative; top: 0; left: 0">';
		switch ($nError) {
			default:
		    case E_USER_ERROR:
		        print '<br /><pre style="width:100%; overflow: auto; font-size:22px; font-weight: bold;">';
        		print_r($sError);
        		print '</pre>';
				print '<br /><b>File:</b> '.$sFile;
				print '<br /><b>Line</b> '.$nLine;
		        print '<br /><b>Platform:</b> PHP ' . PHP_VERSION . ' (' . PHP_OS . ')<br /><hr />';
		        if ($aTrace) {
		            foreach ($aTrace as $aTraceStep) {
		            	//Debug::show($aTraceStep);
		                if (!is_array($aTraceStep)) {
		                    break;
	                    }
		                print '<div style="color: #333; font-size: 14px; margin: 5px 0 5px 20px; padding: 5px 20px; width: 800px; border-bottom: 1px dotted #666;">';
	                
		                $sClass = isset($aTraceStep['class']) ? $aTraceStep['class'].'::' : '';
		                $sMethod = isset($aTraceStep['function']) ? $aTraceStep['function'].'()' : '';
		                $aArguments = isset($aTraceStep['args']) ? $aTraceStep['args'] : array(); 
		                if (trim($sClass.$sMethod)) {
		                    print '<b>Called from:</b> '.$sClass.$sMethod;
	                    }
	                    if (isset($aTraceStep['file'])) {
		                    print '<br /><b>File</b> '.str_replace($_ENV['INSTALLATION_PATH'], '', $aTraceStep['file']);
	                    }
	                    if (isset($aTraceStep['line'])) {
		                    print '<br /><b>Line</b> '.$aTraceStep['line'];
	                    }
	                    print '<br /><b>Arguments:</b><ul style="border-left: 2px solid #CCC;">';
	                    foreach ($aArguments as $mArgument) {
	                    	print '<li style="width: 700px; overflow: auto; border-top: 1px dotted #666; padding: 10px;">';
	                    	if (is_string($mArgument)) {
	                    		print '"<i>'.preg_replace('/[\s]+/', ' ', (string)trim(htmlentities($mArgument))).'</i>"';
	                    	} else {
	                    	    if (!$mArgument) {
	                    	        if (is_array($mArgument)) {
	                    	            $mArgument = 'Empty array';
                    	            } else {
                    	                $mArgument = 'null';
                	                }
                    	        }
	                    		print '<pre style="margin:0">';
	                    		print_r($mArgument);
	                    		print '</pre>';
	                    	}
	                    	print '</li>';
	                    }
	                    print '</ul>';
                    
        				print '</div>';
	                }
	                //Debug::show($aTrace);
	            }
		        break;

		    case E_USER_WARNING:
		        echo "<hr /><b>Warning:</b> [$nError] $sError<br />\n";
		        break;

		    case E_USER_NOTICE:
		        echo "<hr /><b>Notice:</b> [$nError] $sError<br />\n";
		        break;
		}
		print '<br /><pre style="width:100%; overflow: auto;">$_REQUEST ';
		print_r($_REQUEST);
		print '</pre>';
    
        print '<br /><pre style="width:100%; overflow: auto;">$_COOKIE ';
		print_r($_COOKIE);
		print '</pre>';
	
		print '<br /><pre style="width:100%; overflow: auto;">$_SERVER ';
		print_r($_SERVER);
		print '</pre>';
	
		print '<br /><pre style="width:100%; overflow: auto;">$_SESSION ';
		print_r($_SESSION);
		print '</pre>';
	
        print '</div>';
		/* Don't execute PHP internal error handler */
		exit();
    	return true;
	}
	
	set_exception_handler('exception_handler');
	set_error_handler('error_handler');

?>