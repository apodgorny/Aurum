<?
    /**
     * Notification Utility
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

    class Notification extends Renderable {
		const SESSION_KEY = '___notification___';
		
        public static function set($sValue) {
            $_SESSION[self::SESSION_KEY] = $sValue;
        }
        public static function get() {
            $sNotification = '';
            if (isset($_SESSION[self::SESSION_KEY])) {
                $sNotification = $_SESSION[self::SESSION_KEY];
                unset($_SESSION[self::SESSION_KEY]);
            }
            return $sNotification;
        }
    }
    
    class NotificationRenderer extends Renderer {
        public function render($oNotification) {
            $sMessage = $oNotification->get();
            $sId      = 'notification';
            $sClass   = $oNotification->getCssClass();
            return Dom::DIV(
                array(
                    'id'    => $sId,
                    'class' => $sClass
                ),
                $sMessage
            ).($sMessage 
                ? Dom::SCRIPT('$(function(){App.Notification.flash(\''.$sMessage.'\')});') 
                : ''
            );
        }
        public function renderReadOnly($oNotification) {
            return $this->render($oNotification);
        }
    }

?>