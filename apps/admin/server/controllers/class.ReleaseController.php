<?

	/**
	 * class ReleaseController
	 * @author Alexander Podgorny
	 */


	class ReleaseController extends Controller {
		public $sDefaultTemplate = 'default';
		public function index() {
		    
		    /********** BUILD FORM *************/
		    
		    $oBuildForm = new Form();
			$oBuildForm->method = 'post';
			$oBuildForm->addElement(new FormElementHidden(
			    array(
			        'name'  =>  'purpose',
			        'value' =>  'build'
			    )
			));
			$oBuildForm->addElement(new FormElementSubmit(
			    array('value'=>'Build &raquo;')
			));
		    
		    switch (Request::method()) {
		        case Request::METHOD_GET:
		            break;
		        case Request::METHOD_POST:
		            switch (Request::getParam('purpose')) {
		                case 'build':
		                    Release::build();
		                    break;
	                }
	        }
			return array(
			    'sCss'        => '',
			    'oBuildForm' => $oBuildForm
			);
		}
	}

?>
