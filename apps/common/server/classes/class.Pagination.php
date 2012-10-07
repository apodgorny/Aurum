<?
    /**
     * class Pagination
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

    class Pagination extends Renderable {

        /**
         * Renders pagination
         * @param array    $aConfig {
         *      nCurrentPage
         *      nTotalPages
         *      sBaseLink
         *      sPageUrlParamName
         * }
         * @return void
         */
        public function render($aConfig=array()) {
            $aConfig['bRenderLabel'] = 0;
            return parent::render($aConfig);
        }
        public function renderLabel($aConfig=array()) {
            $aConfig['bRenderLabel'] = 1;
            return parent::render($aConfig);
        }
    }
     
    class PaginationRenderer extends Renderer {
    
        /************ PRIVATE ************/
	
    	private $_sBaseLink = '';
    	private $_nCurrentPage = 1;
    	private $_nTotalPages = 1;
    	private $_nPaginationLength = 5;
    	private $_sPageUrlParamName = 'p';
    	
    	private function _getURLForPage($nPage) {
            return Navigation::setUrlParams($this->_sBaseLink, array($this->_sPageUrlParamName => $nPage));
    	}
        private function _getURLForPreviousPage() {
        	$nPreviousPage = $this->_nCurrentPage > 1 
        	   ? $this->_nCurrentPage - 1 
        	   : 1;
            return $this->_getURLForPage($nPreviousPage);
        }
        private function _getURLForNextPage() {
            $nNextPage = $this->_nCurrentPage < $this->_nTotalPages 
                ? $this->_nCurrentPage + 1 
                : $this->_nTotalPages;
            return $this->_getURLForPage($nNextPage);
        }
	
        /************ PUBLIC *************/
    
        public function render($oPagination) {
            $this->_nTotalPages = $oPagination->getDefinitionValue('nTotalPages');
            
            if ($this->_nTotalPages <= 1) { return ''; }
            
            $this->_sPageUrlParamName = $oPagination->getDefinitionValue('sPageUrlParamName')
                ? $oPagination->getDefinitionValue('sPageUrlParamName')
                : $this->_sPageUrlParamName;
                
            $this->_sBaseLink = $oPagination->getDefinitionValue('sBaseLink') 
                ? $oPagination->getDefinitionValue('sBaseLink')
                : Navigation::linkHere();
                
            $this->_nCurrentPage = $oPagination->getDefinitionValue('nCurrentPage')
                ? $oPagination->getDefinitionValue('nCurrentPage')
                : isset($_REQUEST[$this->_sPageUrlParamName]) && $_REQUEST[$this->_sPageUrlParamName]
                    ? $_REQUEST[$this->_sPageUrlParamName]
                    : 1;
            
            if ($oPagination->getDefinitionValue('bRenderLabel')) {
                return $this->renderLabel($oPagination);
            }
            $sCssClass = $oPagination->getCssClass();
            $aItems = array();
        
            if ($this->_nTotalPages <= 0) { return ''; }
            
            /**********************/
            if ($this->_nCurrentPage > 1) {
                $aItems[] = array('label'=>'Previous', 'link'=>$this->_getURLForPreviousPage());
            }
            $nStart = min($this->_nCurrentPage - round($this->_nPaginationLength/2), $this->_nTotalPages - $this->_nPaginationLength);
            while ($nStart < $this->_nCurrentPage) { 
            	$nStart++;
            	if ($nStart > 0) { break; } 
            }
            $nEnd = max($this->_nCurrentPage + round($this->_nPaginationLength/2), $nStart + $this->_nPaginationLength);
            while ($nEnd > $this->_nCurrentPage) {
            	$nEnd--;
            	if ($nEnd <= $this->_nTotalPages) { break; }
            }
            for ($i=$nStart; $i<=$nEnd; $i++) {
                $aItems[] = array('label'=>$i, 'link'=>$this->_getURLForPage($i));
            }
            if ($this->_nCurrentPage < $this->_nTotalPages) {
                $aItems[] = array('label'=>'Next', 'link'=>$this->_getURLForNextPage());
            }
            /**********************/
            
            $sHtml = '';
            foreach ($aItems as $aItem) {
                $sHtml .= Dom::LI(array('class' => ((string)$this->_nCurrentPage == $aItem['label']) ? 'selected' : ''),
                    Dom::A(array('href'  => $aItem['link']),
                        $aItem['label']
                    )
                );
            }
            
            $sHtml = Dom::UL(array('class'=>$sCssClass), $sHtml);
            return $sHtml;
        }
        public function renderLabel($oPagination) {
            $sCssClass = $oPagination->getCssClass();
        	return Dom::SPAN(array('class'=>$sCssClass),
        	   Dom::SPAN(array('class'=>'label'), 
        	       'Page '.Dom::B($this->_nCurrentPage).' of '.Dom::B($this->_nTotalPages)
        	   )
        	);
        }
    }

?>