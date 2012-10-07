<?
    /**
     * class RichTextRenderer
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

    class RichTextRenderer extends Renderer {

        public function render($oRichText) {
            $s = '';
            foreach ($oRichText as $mPiece) {
                if ($mPiece instanceof Renderable) {
                    $s .= ' '.$mPiece->render();
                } else {
                    $s .= ' '.$mPiece;
                }
            }
            return $s;
        }
        
    }
    

?>