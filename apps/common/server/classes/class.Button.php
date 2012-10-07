<?
    /**
     * Class Button
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

    class Button extends Renderable {
        public function __construct($aDefinition='') {
            parent::__construct($aDefinition);
            $this->setDefaultRenderer('ButtonTertiary');
        }
    }
    
    class ButtonPrimaryRenderer extends Renderer {
        public function render($oButton) {
            $sValue = $oButton->getDefinitionValue('value');
            $sCssClass = $oButton->getCssClass();
            return Dom::BUTTON(array('class'=>$sCssClass), $sValue);
        }
    }
    class ButtonSecondaryRenderer extends Renderer {
        public function render($oButton) {
            $sValue = $oButton->getDefinitionValue('value');
            $sCssClass = $oButton->getCssClass();
            return Dom::BUTTON(array('class'=>$sCssClass), $sValue);
        }
    }
    class ButtonTertiaryRenderer extends Renderer {
        public function render($oButton) {
            $sValue = $oButton->getDefinitionValue('value');
            $sCssClass = $oButton->getCssClass();
            return Dom::BUTTON(array('class'=>$sCssClass), $sValue);
        }
    }

?>