<?
    /**
     * class Aggregator
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

    abstract class Aggregator extends Object {

        /****************** PRIVATE *******************/

        protected static $_oThis = null;

        protected $_aItems = array();
        protected $_sRenderer = null;

        /****************** PUBLIC ********************/

        public static function getInstance() {
            if (! static::$_oThis) {
                $sClassName = get_called_class();
                $_oThis = new $sClassName();
            }
            return $_oThis;
        }

        public abstract function aggregate($oEvent);

        /**
         * Returns aggregated items of the following form:
         * $aItems = array(
         *      'date'          => date evnet occurred,
         *      'event_type'    => event type,
         *      'target_id'     => event source id
         *      'target_type'   => event source type
         *      'data'          => result of aggregation
         *      'renderer'      => renderer class name
         * )
         * @return Array
         */
        public function getItems() {
            $aItems = array();
            foreach ($this->_aItems as $oItem) {
                $oItem['renderer'] = $this->_sRenderer;
                $aItems[] = $oItem;
            }
            return $aItems;
        }

        public function setRenderer($sRenderer) {
            $this->_sRenderer = $sRenderer;
        }

        public function getRenderer() {
            return $this->_sRenderer;
        }

        public function isEmpty() {
            return count($this->_aItems) == 0;
        }
    }


?>