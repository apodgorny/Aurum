<?
    /**
     * class Rater
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

    class Rater extends Object {
        
        private static $_nCounter = 0;
        
        public static function render($nReferenceId, $sValue, $sUrl) {
            $aDefinition = array(
                'ajax'      => true,
                'method'    => 'get',
                'name'      => 'rater'.self::$_nCounter,
                'renderer'  => 'FormPlain',
                'action'    => $sUrl,
                'elements'  => array(
                    array(
                        'type'  => 'hidden',
                        'name'  => 'reference_id',
                        'value' =>  $nReferenceId
                    ),
                    array(
                        'type'  => 'rater',
                        'name'  => 'rating',
                        'value' => $sValue
                    )
                )
            );
            $oForm = new Form($aDefinition);
			self::$_nCounter ++;
			return $oForm->render();
        }
        
        public static function renderReadOnly($sValue) {
            $aDefinition = array(
                'ajax'      => true,
                'method'    => 'get',
                'name'      => 'rater'.self::$_nCounter,
                'renderer'  => 'FormPlain',
                'readonly'  => true,
                'elements'  => array(
                    array(
                        'type'  => 'rater',
                        'name'  => 'rating',
                        'value' => $sValue,
                    )
                )
            );
            $oForm = new Form($aDefinition);
			self::$_nCounter ++;
			return $oForm->render();
        }
    }

?>