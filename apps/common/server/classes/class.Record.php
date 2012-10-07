<?
    /**
      * Class Record
      * @author Alexander Podgorny <ap.coding@gmail.com>
      * @license http://opensource.org/licenses/gpl-license.php GNU Public License
      */

 	abstract class Record extends Renderable implements IteratorAggregate, ArrayAccess, Countable {

		/************* PRIVATE **************/
		/************* PROTECTED ************/

		protected $_aData = array();
		protected $_sTable = '';
		protected $_sClassRoot = '';

		/**
		 * Sanitizes db input using mysql_real_escape_string().
		 * @param array|string $mData Data to sanitize, array keys are also sanitized.
		 */
		protected function sanitize(&$mData) {
			DB::sanitize($mData);
		}

		/************* PUBLIC ***************/

		public static function getTableName() {
		    return strtolower(preg_replace('/Record$/', '', get_called_class()));
	    }

    	/**
		 * Deletes a record from DB
		 * @return null
		 */
		public static function delete($nId=null) {
		    $sTableName = static::getTableName();
		    DB::connect();
		    DB::sanitize($nId);
		    DB::query("DELETE FROM `$sTableName` WHERE id=$nId");
		    DB::disconnect();
	    }

		/**
		 * Constructs a record object linked with database table by default derived from class name
		 * Subclasses may override relation to database, for example when using joins
		 * @param array|string|int|Record $mDataOrId Array|Record of data or string|int of record id
		 */
		public function __construct($mDataOrId=null, $sTableName=null) {
		    parent::__construct();

		    $this->_sClassRoot = $sTableName
                        ? $sTableName
                        : preg_replace('/Record$/', '', get_class($this));

		    $this->_sTable = strtolower($this->_sClassRoot);

//Debug::show('Record', 'class: '.get_class($this), 'table: '.$this->_sTable, 'root: '.$this->_sClassRoot);

	            if (is_string($mDataOrId) || is_numeric($mDataOrId)) {
		        $this->load($mDataOrId);
		    } else {
		        $this->fill($mDataOrId);
	        }
		}

		/**
         * Returns internal representation of a record
         * @return array Associative array of key => value pairs
         */
        public function getValues() {
            return $this->_aData;
        }

        /**
		 * Populates internal record representation.
		 * @param array $_aData Associative array corresponding to a table record
		 */
        public function setValues($aData) {
            if (is_array($aData)) { $this->_aData = array_merge($this->_aData, $aData);	}
            else if ($aData)      { $this->_aData = $aData->_aData;	}

			return $this;
        }

		/**
		 * Calls setValues()
		 */
		public function fill($aData) {
			return $this->setValues($aData);
		}

		/**
		 * Loads first record where id = nId
		 * @param numeric  $nId
		 * @return Record object
		 */
		public function load($nId) {
			return $this->loadBy('id', $nId);
		}

		/**
		 * Loads first record where sFieldName = sValue
		 * @param string   $sFieldName
		 * @param string   $sValue
		 * @return Record object
		 */
		public function loadBy($sFieldName, $sValue) {
		    $this->sanitize($sFieldName);
		    $this->sanitize($sValue);
		    return $this->select("SELECT * FROM $this->_sTable WHERE $sFieldName='$sValue'");
	    }

	    /**
	     * Loads first record selected by sQuery
	     * @param string   $sQuery
	     * @return Record object
	     */
	    public function select($sQuery) {
			$aResult = DB::select($sQuery);
			if ($aResult && isset($aResult[0])) {
			    $this->_aData = array_merge($this->_aData, $aResult[0]);
			}
			return $this;
        }

        /**
         * Saves internal record to database
         * @param boolean  $bConsistent     Reload after save?
         * @return void
         */
		public function save($bConsistent=true) {
			if (!$this->_aData) { return $this; }

			$this->sanitize($this->_aData);

			if (isset($this->_aData['id']) && $this->_aData['id']) {
				DB::updateArray($this->_sTable, $this->_aData['id'], $this->_aData);
			} else {
				$this->_aData['id'] = DB::insertArray($this->_sTable, $this->_aData);
			}

			if ($bConsistent) {
    			// To be consistent with create_data, update_date
    			$this->load($this->_aData['id']);
            }

			return $this;
		}

	    public function isLoaded() {
	        return isset($this->_aData['id']) && $this->_aData['id'];
        }

	    /**
	     * Returns true if all values in aEqPairs exist in table
	     * @param array    $aEqPairs    Key => Value pairs to turn into Key=Value AND ...
	     * @param array    $aExceptIds  List of ids to be ignored when checking if has()
	     * @return boolean
	     */
		public function has($aEqPairs, $aExceptIds=array()) {
            $this->sanitize($aEqPairs);
            $this->sanitize($aExceptIds);

            $aEqExpressions = '';
            foreach ($aEqPairs as $sKey=>$sVal) {
                $aEqExpressions[] = $sKey.'=\''.$sVal.'\'';
            }
            $sEqPairs = implode(' AND ', $aEqExpressions);

            if ($aExceptIds) {
                $sExceptIds = implode(',', $aExceptIds);
                $sQuery =
                    "SELECT COUNT(id) as 'count'
                        FROM $this->_sTable
                        WHERE $sEqPairs
                        AND id NOT IN ($sExceptIds)";
            } else {
                $sQuery =
                    "SELECT COUNT(id) as 'count'
                        FROM $this->_sTable
                        WHERE $sEqPairs";
            }
            $aResult = DB::select($sQuery);
            return $aResult[0]['count'] > 0;
        }

        /******* Access Methods *******/

		public function getIterator()				{ return new ArrayIterator($this->_aData); }
		public function offsetSet($sKey, $sValue) 	{ $this->_aData[$sKey] = $sValue; }
	    public function offsetExists($sKey) 		{ return isset($this->_aData[$sKey]); }
	    public function offsetUnset($sKey) 			{ unset($this->_aData[$sKey]); }
	    public function offsetGet($sKey) 			{ return isset($this->_aData[$sKey]) ? $this->_aData[$sKey] : null; }
	    public function __toString()                { return print_r($this->_aData, 1); }
	    public function count()                     { return count($this->_aData); }

		/********************************/
	}

?>
