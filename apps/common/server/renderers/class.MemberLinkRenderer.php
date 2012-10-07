<?
    /**
     * MemberLinkRenderer renderer
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

    class MemberLinkRenderer extends Renderer {

        /****************** PRIVATE *******************/
        /****************** PROTECTED *****************/
        /****************** PUBLIC ********************/

        public function render($oMember) {
            $sLinkToMember = Navigation::link(null, 'member', 'view', array($oMember['seo_name']));
            $sMemberName = $oMember->render('Name');
            return Dom::A(array('href'=>$sLinkToMember, 'title'=>$sMemberName), $sMemberName);
        }

    }
?>
