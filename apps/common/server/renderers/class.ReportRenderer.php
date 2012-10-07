<?
    /**
     * ReportRenderer
     * @author Konstantin Kouptsov <konstantin@kouptsov.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */


    class ReportRenderer extends Renderer {

        public function render($oReport) {
            $s = '';
            $aItems = $oReport->getItems();
            foreach ($aItems as $oItem) {
                /*
                 * Assume that item renderer returns: array(title, description, date)
                 */
                $oRenderedItem = $oItem->render($oItem['renderer']);

                if (!$oRenderedItem) {
                    continue;
                }

                if (!isset($oItem['channel_id']) || !isset($oItem['channel_type'])) {
                    $oItem['channel_id']   = $oItem['event']['target_id'];
                    $oItem['channel_type'] = $oItem['event']['target_type'];
                }
                $oChannel =  EventLogic::getSourceChannel($oItem['channel_id'], $oItem['channel_type']);
                $sGridName = 'ReportRenderer-'.md5(microtime(true));

                $s .= Dom::DIV(
                    array('class' => $oReport->getCssClass()),
                    Grid::BEGIN($sGridName, 50).
                        Dom::A(array('href'=>$oChannel['link']),
                            Dom::IMG(
                               array(
                                   'src'   => $oChannel['image'],
                                   'class' => 'userimage-s'
                               )
                            )
                        ).
                    Grid::SPLIT($sGridName, 0, Grid::ALIGN_LEFT).
                        Dom::DIV(
                           array('class' => 'title', 'title' => strip_tags($oRenderedItem['title'])),
                           ucfirst(trim($oRenderedItem['title']))
                        ).
                        Dom::DIV(
                           array('class' => 'description'),
                           $oRenderedItem['description']
                        ).
                        Dom::DIV(
                           array('class' => 'date'),
                           $oRenderedItem['date']
                        ).
                    Grid::END($sGridName)
                );
            }
            return $s;
        }
    }

?>

