<?
    /**
     * class RichText
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

    class RichText extends Renderable implements IteratorAggregate, ArrayAccess {
        
        /****************** PRIVATE *******************/
        
        private $_aTextFragments = array();
        /****************** PROTECTED *****************/
        /****************** PUBLIC ********************/
        
        public function parse($sText) {
            $aPieces = array();
            $bDoLoop = true;
            while ($bDoLoop) {
                if (!preg_match('/https?:\/\/[\S]+/i', $sText, $aMatch)) {
                    $bDoLoop = false;
                } else {
                    $sLink                   = $aMatch[0];
                    $aBeforeAndAfter         = explode($sLink, $sText, 2);
                    $this->_aTextFragments[] = trim($aBeforeAndAfter[0]);
                    $this->_aTextFragments[] = Link::construct()->set($sLink);
                    $sText                   = $aBeforeAndAfter[1];
                }
            };
            $this->_aTextFragments[] = trim($sText);
            return $this;
        }
        
        /******* Access Methods *******/
		
		public function getIterator() { 
		    return new ArrayIterator($this->_aTextFragments); 
		}
	    public function offsetSet($sKey, $sValue) {
	        $this->_aTextFragments[$sKey] = $sValue;
	    }
	    public function offsetExists($sKey) {
	        return isset($this->_aTextFragments[$sKey]);
	    }
	    public function offsetUnset($sKey)  { 
	        unset($this->_aTextFragments[$sKey]);
	    }
	    public function offsetGet($sKey)  {
	        if (isset($this->_aTextFragments[$sKey])) {
	            return $this->_aTextFragments[$sKey];
            }
            return null;
        }
    }
    

?>