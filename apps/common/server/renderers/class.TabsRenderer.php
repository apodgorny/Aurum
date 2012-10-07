<?
    /**
     * Tabs renderer
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

    class TabsRenderer extends Renderer {

        /****************** PRIVATE *******************/
        /****************** PROTECTED *****************/
        /****************** PUBLIC ********************/
        
        public function render($oTabs) {
            // This assums, of course, renderable is of type Tabs. 
            // This is ok, Rendrable and Renderer are tightly coupled
            $aDefinition = $oTabs->getDefinition();
			$sJSClass    = $oTabs->getJsClass();
			$sJSId       = $oTabs->getJsId();
			$sCssClass   = $oTabs->getCssClass() . (isset($aDefinition['class']) ? ' '.$aDefinition['class'] : '');
			
            $s = '';
            $bFirst = true;
            foreach ($aDefinition['items'] as $aItem) {
                $sTabCssClass = isset($aDefinition['selected']) && $aItem['name'] == $aDefinition['selected'] 
                    ? 'selected' : 
                    '';
                $sTabCssClass .= $bFirst ? ' first' : '';
                $sTabId       = isset($aItem['id'])    ? $aItem['id']    : '';
                
                $s .= Dom::LI(array(
                        'class' => $sTabCssClass,
                        'id'    => $sTabId
                    ),
                    Dom::A(array('href'=>$aItem['link']),
                        Dom::DIV($aItem['label'])
                    )
                );
                if ($bFirst) { $bFirst = false; }
            }
            $s = Dom::UL(array(
                'id'    => $sJSId,
                'class' => $sCssClass
                ), $s
            );
            foreach ($aDefinition['items'] as $aItem) {
                if (isset($aItem['menu']) && $aItem['menu'] && $aItem['name'] != $aDefinition['selected']) {
                    $s .= Menu::construct($aItem['menu'])->render(array(
                        'parent_id' => $aItem['id']
                    ));
                }
            }
            $oTabs->registerJsComponent();
            return $s."\n";
        }
        public function renderReadOnly($oTabs) {
            return $this->render($oTabs);
        }

    }
    

?>