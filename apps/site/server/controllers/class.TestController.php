<?
    /**
     * TestController
     * @author Konstantin Kouptsov <konstantin@geeksome.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

	class TestController extends Controller {

		/************** PRIVATE **************/

		/************** PUBLIC ***************/

		public $sDefaultAction = 'linkedin';

		public function index() {
			return array('foo' => 'bar');
		}

		public function selectTest() {
			$this->useTemplate('empty');
		}

		public function dialogTest() {
			$this->useTemplate('dialog');
		}

		public function dialog() {
		}

	}
?>
