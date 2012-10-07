<?
    /**
      * Class ModelList
      * @author Alexander Podgorny <ap.coding@gmail.com>
      * @license http://opensource.org/licenses/gpl-license.php GNU Public License
      */

     class ModelList extends RecordList {

        public function __construct($aIds=null) {
            $sTableName = preg_replace('/ModelList$/', '', get_class($this));
            parent::__construct($aIds, $sTableName);
//Debug::show('ModelList', 'class: '.get_class($this), 'table: '.$this->_sTable, 'root: '.$this->_sClassRoot);

        }
        /**
         * Populates internal record list representation with singular Record objects
         * @param array|RecordList  $aData Array of (Arrays or Model objects)
         * @return ModelList object
         */
        public function fill($aData) {
            if (!$aData) { return $this; }
            $sSingularModel = $this->_sClassRoot.'Model';
            foreach ($aData as $aRecord) {
                $this->_aData[] = new $sSingularModel($aRecord);
            }
            return $this;
        }

    }

?>
