<?
    /**
     * class FormElementRater
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

    class FormElementRater extends FormElement {
        /**
         * Constructs rater
         * @param array    $aDefinition = array(
         *      nRating => [1...5]
         * )
         */
        public function __construct($aDefinition='') {
            parent::__construct($aDefinition);
        }
    }
    
    class FormElementRaterRenderer extends Renderer {
        public function render($oRater) {
            $aDefinition = $oRater->getDefinition();
            $oHiddenElement = new FormElementHidden($aDefinition);
            $nRating = ceil($aDefinition['value']);
            $sHtml = '';
            for ($i=1; $i<=5; $i++) {
                $sClass = '';
                if ($i <= $nRating) {
                    $sClass = 'selected';
                }
                $sHtml .= Dom::LI(array('class'=>$sClass), 
                    '&nbsp;'
                );
            }
            $oRater->registerJsComponent();
            return Dom::UL(array(
                    'class' =>  $oRater->getCssClass(),
                    'id'    =>  $oRater->getJsId()
                ),
                $sHtml . $oHiddenElement->render()
            );
        }
        
        public function renderReadOnly($oRater) {
            $aDefinition = $oRater->getDefinition();
            $nRating = $aDefinition['value'];
            $sHtml = '';
            for ($i=1; $i<=5; $i++) {
                $sClass = '';
                if ($i <= $nRating) {
                    $sClass = 'selected';
                } else {
                    continue;
                }
                $sHtml .= Dom::LI(array('class'=>$sClass), 
                    '&nbsp;'
                );
            }
            return Dom::UL(array(
                    'class' =>  $oRater->getCssClass(),
                ),
                $sHtml
            );
        }
    }

?>