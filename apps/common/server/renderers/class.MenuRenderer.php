<?
    /**
     * Menu renderer
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

    class MenuRenderer extends Renderer {

        /****************** PRIVATE *******************/
        /****************** PROTECTED *****************/
        /****************** PUBLIC ********************/
        
        public function render($oMenu) {
            $aDefinition = $oMenu->getDefinition();
			$sJsId       = $oMenu->getJsId();
			$sCssClass   = $oMenu->getCssClass();
			
            $s = '';
            if (isset($aDefinition['items'])) {
                foreach ($aDefinition['items'] as $aItem) {
                    $s .= Dom::LI(array('class'=>isset($aItem['class']) ? $aItem['class'] : ''),
                        Dom::A(array('href'=>$aItem['link']),
                            str_replace('&nbsp;', '', $aItem['label'])
                        )
                    );
                }
            }
            $s = Dom::UL(array(
                    'class' => $sCssClass,
                    'id'    => $sJsId
                ), $s
            );
            $oMenu->registerJsComponent();
            return $s."\n";
        }
        public function renderReadOnly($oMenu) {
            return $this->render($oMenu);
        }

    }
    

?>