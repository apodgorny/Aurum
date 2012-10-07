<?
    /**
     * MemberNameRenderer renderer
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

    class MemberNameRenderer extends Renderer {

        /****************** PRIVATE *******************/
        /****************** PROTECTED *****************/
        /****************** PUBLIC ********************/

        public function render($oMember) {
            return $oMember['first_name'].' '.$oMember['last_name'];
        }

    }
?>
