<?
    /**
     * class EventAggregator
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

     class Report extends Renderable {

        const PURPOSE_FOUNDUPS = 'FOUNDUPS';
        const PURPOSE_PEOPLE   = 'PEOPLE';
        const PURPOSE_ME       = 'ME';

        /****************** PRIVATE *******************/

        private $_aAggregators = array(); // keep track for getItems()
        private $_aItems   = array();

        /****************** PROTECTED *****************/
        /****************** PUBLIC ********************/

        public function aggregate($oEvent) {

            $aAggregators = array();
            if (isset($this->_aAggregators[$oEvent['type']])) {
                $aAggregators = $this->_aAggregators[$oEvent['type']];
            } else {
                $aDefinition = $this->getDefinition();
                if (!isset($aDefinition['events'][$oEvent['type']])) {
                    return;
                }
                foreach ($aDefinition['events'][$oEvent['type']] as $aEventHandlers) {
                    $sAggregatorClass = $aEventHandlers['aggregator'];
                    $sRendererClass = $aEventHandlers['renderer'];
                    $oAggregator = $sAggregatorClass::getInstance();
                    $oAggregator->setRenderer($sRendererClass);
                    $aAggregators[] = $oAggregator;
                }
                $this->_aAggregators[$oEvent['type']] = $aAggregators;
            }
            foreach ($aAggregators as $oAggregator) {
                $oAggregator->aggregate($oEvent);
            }
        }

        public function addItem($oItem) {
            $this->_aItems[] = $oItem;
        }

        public function getItems($bAscending = false) {
            $aItems = array();
            $aDefinition = $this->getDefinition();
            foreach ($this->_aAggregators as $aAggregators) {
                foreach ($aAggregators as $oAggregator) {
                    $aAggregatorItems = $oAggregator->getItems();
                    foreach ($aAggregatorItems as $oItem) {
                        $oItem['report_purpose'] = $aDefinition['purpose'];
                        $aItems[] = $oItem;
                    }
                }
            }
            usort(
                $aItems,
                function($m1, $m2) use ($bAscending) {
                    $bResult = $m1['date'] < $m2['date']
                        ? 1
                        : ($m1['date'] > $m2['date']
                           ? -1
                           : 0
                        );
                    return $bResult * ($bAscending ? -1 : 1);
                }
            );
            return $aItems;
        }

        public function isEmpty() {
            foreach ($this->_aAggregators as $aAggregators) {
                foreach ($aAggregators as $oAggregator) {
                    if (!$oAggregator->isEmpty()) {
                        return false;
                    }
                }
            }
            return true;
        }
     }
?>