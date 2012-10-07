<?
    /**
      * Class Model
      * @author Alexander Podgorny <ap.coding@gmail.com>
      * @license http://opensource.org/licenses/gpl-license.php GNU Public License
      */

 	class Model extends Record {

 	    public static function getTableName() {
		    return strtolower(preg_replace('/Model$/', '', get_called_class()));
	    }


		public function __construct($mDataOrId=null, $sTableName=null) {
		    $sTableName = $sTableName ? $sTableName : preg_replace('/Model$/', '', get_class($this));
		    parent::__construct($mDataOrId, $sTableName);
//Debug::show('Model', 'class: '.get_class($this), 'table: '.$this->_sTable, 'root: '.$this->_sClassRoot);
	    }

        // CRUD Access Methods - implement them specifically
        // for each model

        public function canCreate($nMemberId=null) {
            return true;
        }
        public function canRead($nId, $nMemberId=null) {
        	return true;
        }
        public function canUpdate($nId, $nMemberId=null) {
            return true;
        }
        public function canDelete($nId, $nMemberId=null) {
            return true;
        }
	}

?>
