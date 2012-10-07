<?
    /**
      * Renderer Class - The default renderer class
      * @author Alexander Podgorny <ap.coding@gmail.com>
      * @license http://opensource.org/licenses/gpl-license.php GNU Public License
      */

    class Renderer extends Object {

        const FORMAT_HTML = 0;
        const FORMAT_PDF  = 1;

        /************** PRIVATE **************/

        private $_sGenericModuleName  = '';
        private $_sGenericCssClass    = '';
        private $_sSpecificCssClass   = '';
        private $_sCssClass           = '';

        /************** PUBLIC ***************/

	public function __construct() {
	    parent::__construct();
	}

        public function render($oRenderable) {
            Debug::show('default renderer: ',$oRenderable);
        }

        public function renderReadOnly($oRenderable) {
            return $this->render($oRenderable);
        }
    }

?>
