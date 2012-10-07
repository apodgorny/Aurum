<?
    /**
     * class Link
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

    class Link extends Renderable implements IteratorAggregate, ArrayAccess {
        
        /****************** PRIVATE *******************/
        
        private $_aLinkParts = array();
        
        /****************** PROTECTED *****************/
        /****************** PUBLIC ********************/
        
        /**
         * Parses out links from text
         * @return array of link objects
         */

        public function set($sLink) {
            $this->_aLinkParts = parse_url($sLink);
            $this->_aLinkParts['link'] = $sLink;
            // parse parts
            return $this;
        }
        
        /******* Access Methods *******/
		
		public function getIterator() { 
		    return new ArrayIterator($this->_aLinkParts); 
		}
	    public function offsetSet($sKey, $sValue) {
	        $this->_aLinkParts[$sKey] = $sValue;
	    }
	    public function offsetExists($sKey) {
	        return isset($this->_aLinkParts[$sKey]);
	    }
	    public function offsetUnset($sKey) { 
	        unset($this->_aLinkParts[$sKey]);
	    }
	    public function offsetGet($sKey) {
	        if (isset($this->_aLinkParts[$sKey])) {
	            return $this->_aLinkParts[$sKey];
            }
            return null;
        }
    }
    

?>