<?
    /**
     * NewsHtmlRenderer
     * @author Konstantin Kouptsov <konstantin@kouptsov.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */


    class NewsHtmlRenderer extends Renderer {

    	/* $oNews is EventModelList */
        public function render($oNews) {
            $s = '';

            foreach ($oNews as $oItem) {
                $s .= $oItem->render('NewsItemHtml');
            }
            return $s;
        }
    }

?>
