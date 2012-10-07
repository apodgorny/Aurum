<?
	class DatabaseController extends Controller {
		
		/************** PRIVATE **************/
		private $_nMaxMessageLength = 130;
		private function _formatMessage($s) {
			$aLines = explode("\n", $s);
			$aShortLines = array();
			foreach ($aLines as $sLine) {
				if (strlen($sLine) > $this->_nMaxMessageLength) {
					$aShortLines = array_merge($aShortLines, explode('~~', chunk_split($sLine, $this->_nMaxMessageLength, '~~')));
				} else {
					$aShortLines[] = $sLine;
				}
			}
			return implode("\n", $aShortLines);
		}
		private function _runSQL($sSchemaDir) {
			$this->useView('index');
			$aSQLFiles = array_keys(Files::getFiles($sSchemaDir, array("^\."), array("\.sql$")));
			$aMessages = array();
			DB::connect();
			foreach($aSQLFiles as $sSQLFileName) {
				$sSQLs = file_get_contents($sSchemaDir.$sSQLFileName);
				$aSQL = preg_split("/;[ ]*[\n\r]+/", $sSQLs);
				foreach ($aSQL as $sSQL) {
					if (!trim($sSQL)) { continue; }
					DB::query($sSQL, true);
					$aMessages[] = array(
						'error' => $this->_formatMessage(trim(DB::$error) ? DB::$error : 'Success!'),
						'query' => $sSQL,
						'file'	=> $sSQLFileName
					);
				}
			}
			DB::disconnect();
			Debug::log('DBBBB', $aMessages);
			return array_merge(
				array('messages'=>$aMessages),
				$this->index()
			);
		}
		
		/************** PUBLIC ***************/
		
		/**
		 * Index view
		 * @return array 
		 */
		public function index() {
			
			// Update Schema
			$oFormUpdateSchema = new Form();
			$oFormUpdateSchema->action = Navigation::link('admin', 'database', 'updateSchema');
			$oFormUpdateSchema->method = 'post';
			$oFormUpdateSchema->addElement(new FormElementSubmit(
			    array('value'=>'Update Schema &raquo;')
			));
			
			// Update Data
			$oFormUpdateData = new Form();
			$oFormUpdateData->action = Navigation::link('admin', 'database', 'updateData');
			$oFormUpdateData->method = 'post';
			$oFormUpdateData->addElement(new FormElementSubmit(
			    array('value'=>'Update Data &raquo;')
			));
			
			// Reindex
			$oFormReindex = new Form();
			$oFormReindex->action = Navigation::link('admin', 'database', 'reindex');
			$oFormReindex->method = 'post';
			
			$oFormReindex->addElement(new FormElementSubmit(
			    array('value'=>'Reindex all data &raquo;')
			));
			
			return array(
				'formUpdateSchema'  => $oFormUpdateSchema,
				'formUpdateData'    => $oFormUpdateData,
				'formReindex'       => $oFormReindex
			);
		}
		public function updateSchema() {
			return $this->_runSQL($_ENV['PATH_TO_SCHEMA'].'structure/');
		}
		public function updateData() {
			return $this->_runSQL($_ENV['PATH_TO_SCHEMA'].'data/');
		}
		public function reindex() {
			set_time_limit(0);
		    SearchLogic::recalculateWeightsForAll();
		    $this->useView('index');
		    return array_merge(
				$this->index(), 
				array('messages'=> array(array(
				    'error' => 'Success, Weights recalculated',
    				'query' => '',
    				'file'	=> ''
    			)))
    		);
		}
	}
?>