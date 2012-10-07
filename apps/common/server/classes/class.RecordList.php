<?
    /**
      * Class RecordList
      * @author Alexander Podgorny <ap.coding@gmail.com>
      * @license http://opensource.org/licenses/gpl-license.php GNU Public License
      */

     class RecordList extends Record {

        /************* PRIVATE **************/
        /************* PROTECTED ************/
        /************* PUBLIC ***************/

        /**
         * Deletes records from database
         * @return RecordList
         */
        public static function delete($aIds=array()) {
            foreach ($aIds as $nId) {
                Record::delete($nId);
            }
        }

        /**
         * Constructs a RecordList object linked with database table by default derived from class name
         * @param array $mDataOrId Array of record ids
         * @param string $sTable Override value for default table name
         */
        public function __construct($aIds=null, $sTableName=null) {
            parent::__construct();

            $this->_sClassRoot = $sTableName
                ? $sTableName
                : preg_replace('/RecordList$/', '', get_class($this));
	    $this->_sTable = strtolower($this->_sClassRoot);

//Debug::show('RecordList', 'table: '.$this->_sTable, 'root: '.$this->_sClassRoot);

            if ($aIds && is_array($aIds)) { $this->load($aIds); }
        }
        /**
         * Populates internal record list representation with singular Record objects
         * @param array|RecordList  $aData Array of (Arrays or Record objects)
         * @return RecordList object
         */
        public function fill($aData) {
            if (!$aData) { return $this; }
            $this->_aData = array();
            $sSingularRecord = $this->_sClassRoot.'Record';
            foreach ($aData as $aRecord) {
                $this->_aData[] = new $sSingularRecord($aRecord);
            }
            return $this;
        }

        /* does not cast items unlike fill() */
        public function merge($aData) {
            if (!$aData) { return $this; }
            if (is_a($aData, 'RecordList')) {
                foreach ($aData as $aRecord) {
                    $this->_aData[] = $aRecord;
                }
            } else if (is_a($aData, 'Record')) {
                $this->_aData[] = $aData;
            }
            return $this;
        }

        /**
         * Appends a record to the internal list representation
         * @param array $aRecord Associative array corresponding to a table record
         * @return RecordList
         */
        public function append($aRecord) {
            $this->fill(array($aRecord));
            return $this;
        }
        /**
         * Loads records with corresponding ids from database
         * @param numeric  $nId
         * @return RecordList
         */
        public function load($aIds) {
            return $this->loadBy('id', $aIds);
        }
        /**
         * Loads records where sFieldName = aValues[n]
         * @param array    $aIds
         * @return RecordList
         */
        public function loadBy($sFieldName, $aValues) {
            if (! is_array($aValues)) {
                $aValues = array($aValues);
            }
            $this->sanitize($sFieldName);
            $this->sanitize($aValues);

            $sValues = implode(',', $aValues);
            return $this->select("SELECT * FROM `$this->_sTable` WHERE $sFieldName IN ($sValues)");
        }
        public function loadAll() {
            $this->select("SELECT * FROM `$this->_sTable` WHERE 1");
            return $this;
        }
        /**
         * Saves model data from database.
         * Override this function in order to do joins or other complex queries.
         * @return boolean
         */
        public function save($bConsistent=true) {
            if (!$this->_aData) { return $this; }

            $this->sanitize($this->_aData);

            $nResult = 1;
            foreach ($this->_aData as $oRecord) {
                if ($oRecord instanceof Record) {
                    $oRecord->save($bConsistent);
                }
            }
            $this->fill($this->_aData);

            return $this;
        }

        public function select($sSQL) {
            $aResult = DB::select($sSQL);
            $this->fill($aResult);
            return $this;
        }

        public function getColumn($sColumnName) {
            $aColumn = array();
            foreach ($this as $oRecord) {
               $aColumn[] = $oRecord[$sColumnName];
            }
            return $aColumn;
        }

        public function isEmpty() {
            return count($this->_aData) == 0;
        }
        /******* Access Methods *******/
        public function getIterator()                { return new ArrayIterator($this->_aData); }
        public function offsetSet($sKey, $sValue)     { $this->_aData[$sKey] = $sValue; }
        public function offsetExists($sKey)            { return isset($this->_aData[$sKey]); }
        public function offsetUnset($sKey)             { unset($this->_aData[$sKey]); }
        public function offsetGet($sKey)             {
            if (isset($this->_aData[$sKey])) {
                return $this->_aData[$sKey];
            }
            return null;
        }
        public function __toString() {
            $s = get_class($this)."\n";
            foreach ($this->_aData as $sKey=>$oRecord) {
                $s .= '[' . $sKey .']' . ' => '. (string)$oRecord;
            }
            return $s;
        }
        /********************************/

    }

?>
