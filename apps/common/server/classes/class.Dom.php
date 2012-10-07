<?
    /**
     * Dom tags renderer
     * @author Alexander Podgorny
     */

    class Dom extends Object {
	    	/**
	     *  @example Dom::tag('div', 'hello world')                              => <div>hello world</div>
	     *  @example Dom::tag('div', 'hello', 'world')                           => <div>helloworld</div>
	     *  @example Dom::tag('div', array('class'=>'mydiv'), 'hello world')     => <div class="mydiv">hello world</div>
	     *  @example Dom::tag('div', array('class'=>'mydiv'), 'hello', 'world')  => <div class="mydiv">helloworld</div>
	     *
	     *  @param sName (String) Tag name
	     *  @param aAttr (Array) Tag Attributes
	     *  @param sValue (String) Text value
	     */
	    public static function tag() {
	        $aArgs = func_get_args();
	        $sName = $aArgs[0];
	        $aAttr = isset($aArgs[1]) ? $aArgs[1] : array();
	        $sAttr = '';
	        $aChildren = array();
	        $nChildrenOffset = 2;

	        if (!is_array($aAttr)) {
	            $nChildrenOffset = 1;
	        } else {
	            foreach ($aAttr as $sKey=>$sValue) {
	            	if (strlen(trim($sValue)) == 0) {
	            		continue;
	            	}
	                $sAttr .= ' '.$sKey.'="'.$sValue.'"';
	            }
	        }

	        $aChildren = array_splice($aArgs, $nChildrenOffset);
	        $sText = implode('', $aChildren);
            return '<'.$sName.$sAttr.(count($aChildren) > 0
	           ? '>'.$sText.'</'.$sName.'>'
	           : ' />'
	        );
	    }
	    public static function DIV     () { $aArgs = func_get_args(); array_unshift($aArgs, 'div'      ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function B       () { $aArgs = func_get_args(); array_unshift($aArgs, 'b'        ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function I       () { $aArgs = func_get_args(); array_unshift($aArgs, 'i'        ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function U       () { $aArgs = func_get_args(); array_unshift($aArgs, 'u'        ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function P       () { $aArgs = func_get_args(); array_unshift($aArgs, 'p'        ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function UL      () { $aArgs = func_get_args(); array_unshift($aArgs, 'ul'       ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function LI      () { $aArgs = func_get_args(); array_unshift($aArgs, 'li'       ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function TABLE   () { $aArgs = func_get_args(); array_unshift($aArgs, 'table'    ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function TR      () { $aArgs = func_get_args(); array_unshift($aArgs, 'tr'       ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function TD      () { $aArgs = func_get_args(); array_unshift($aArgs, 'td'       ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function TH      () { $aArgs = func_get_args(); array_unshift($aArgs, 'th'       ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function TBODY   () { $aArgs = func_get_args(); array_unshift($aArgs, 'tbody'    ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function THEAD   () { $aArgs = func_get_args(); array_unshift($aArgs, 'thead'    ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function BODY    () { $aArgs = func_get_args(); array_unshift($aArgs, 'body'     ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function HEAD    () { $aArgs = func_get_args(); array_unshift($aArgs, 'head'     ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function HTML    () { $aArgs = func_get_args(); array_unshift($aArgs, 'html'     ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function SCRIPT  () { $aArgs = func_get_args(); array_unshift($aArgs, 'script'   ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
		public static function LINK    () { $aArgs = func_get_args(); array_unshift($aArgs, 'link'     ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function A       () { $aArgs = func_get_args(); array_unshift($aArgs, 'a'        ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function SPAN    () { $aArgs = func_get_args(); array_unshift($aArgs, 'span'     ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function INPUT   () { $aArgs = func_get_args(); array_unshift($aArgs, 'input'    ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
        public static function FORM    () { $aArgs = func_get_args(); array_unshift($aArgs, 'form'     ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function BUTTON  () { $aArgs = func_get_args(); array_unshift($aArgs, 'button'   ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function TEXTAREA() { $aArgs = func_get_args(); array_unshift($aArgs, 'textarea' ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function SELECT  () { $aArgs = func_get_args(); array_unshift($aArgs, 'select'   ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function OPTION  () { $aArgs = func_get_args(); array_unshift($aArgs, 'option'   ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function IMG     () { $aArgs = func_get_args(); array_unshift($aArgs, 'img'      ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
        public static function BR      () { $aArgs = func_get_args(); array_unshift($aArgs, 'br'       ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
        public static function LABEL   () { $aArgs = func_get_args(); array_unshift($aArgs, 'label'    ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
        public static function SMALL   () { $aArgs = func_get_args(); array_unshift($aArgs, 'small'    ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
		public static function PRE     () { $aArgs = func_get_args(); array_unshift($aArgs, 'pre'      ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
		
		public static function OBJECT  () { $aArgs = func_get_args(); array_unshift($aArgs, 'object'   ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
		public static function EMBED   () { $aArgs = func_get_args(); array_unshift($aArgs, 'embed'    ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
		public static function PARAM   () { $aArgs = func_get_args(); array_unshift($aArgs, 'param'    ); return call_user_func_array(array('Dom', 'tag'),$aArgs); }

        public static function H1 () { $aArgs = func_get_args(); array_unshift($aArgs, 'h1'); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function H2 () { $aArgs = func_get_args(); array_unshift($aArgs, 'h2'); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function H3 () { $aArgs = func_get_args(); array_unshift($aArgs, 'h3'); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function H4 () { $aArgs = func_get_args(); array_unshift($aArgs, 'h4'); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function H5 () { $aArgs = func_get_args(); array_unshift($aArgs, 'h5'); return call_user_func_array(array('Dom', 'tag'),$aArgs); }
	    public static function H6 () { $aArgs = func_get_args(); array_unshift($aArgs, 'h6'); return call_user_func_array(array('Dom', 'tag'),$aArgs); }

	    public static function COMMENT($sText) {  return "\n".'<!-- '.$sText.' -->'."\n";   }
	    public static function ACTIONBUTTON($sText, $sCSSClass) { $aAttr = array('class'=>'actionbutton '.$sCSSClass); return Dom::BUTTON($aAttr, $sText); }

	    public static function QUOTE($s) {
	       return '&#147;'.$s.'&#148;';
	    }
        public static function NBSP ($nCount=1) {
        	$s = '';
        	for ($i=0; $i<$nCount; $i++) {
        		$s .= '&nbsp;';
        	}
        	return $s;
        }
        public static function CLEAR () { return '<div style="clear:both; font-size: 0; padding:0px; margin: 0px; border: 0px; height: 0px; width: 0px;"></div>'; }
    }

?>