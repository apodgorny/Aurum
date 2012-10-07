<?
    /**
     * class ReportItem
     * @author Konstantin Kouptsov <konstantin@kouptsov.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

    class ReportItem extends Renderable implements IteratorAggregate, ArrayAccess {

        /****************** PRIVATE *******************/

        private $_aData = array();

        /****************** PUBLIC ********************/

        public function fill($aData) {
            foreach ($aData as $sKey=>$mValue) {
                $this->_aData[$sKey] = $mValue;
            }
            return $this;
        }

        /******* Access Methods *******/

        public function getIterator() {
            return new ArrayIterator($this->_aData);
        }

        public function offsetSet($sKey, $sValue) {
            $this->_aData[$sKey] = $sValue;
        }

        public function offsetExists($sKey) {
            return isset($this->_aData[$sKey]);
        }

        public function offsetUnset($sKey)  {
            unset($this->_aData[$sKey]);
        }

        public function offsetGet($sKey)  {
            return isset($this->_aData[$sKey]) ? $this->_aData[$sKey] : null;
        }

        public function __toString() {
            return 'Class '.get_class($this).
                "\n".' definition:'.print_r($this->_aDefinition, 1).
                "\n".'  data:'.print_r($this->_aData, 1);
        }
    }

?>
