<?
    /**
     * Class Tabs
     *
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

     class Tabs extends Renderable {

        /****************** PRIVATE *******************/
        
        private $_aData;
        private $_sModuleName;
        
        /****************** PROTECTED *****************/
        /****************** PUBLIC ********************/
        
        public function __construct($sDefinition) {
            parent::__construct($sDefinition);
        }
        public function select($sTabName) {
            $this->_aDefinition['selected'] = $sTabName;
            return $this;
        }
     }
     
    

?>