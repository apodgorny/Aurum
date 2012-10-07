<?
    /**
     * class Youtube
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

    class Youtube {

        /****************** PRIVATE *******************/
        
        private static function _getVideoId($sLink) {
            $aMatches = array();
            $sToken   = '';
            if (preg_match('/v=([a-zA-Z0-9]{11})/', $sLink, $aMatches)) {
                $sToken = $aMatches[1];
            } else if (preg_match('/v\/([a-zA-Z0-9]{11})/', $sLink, $aMatches)) {
                $sToken = $aMatches[1];
            }
            return $sToken;
        }
        /****************** PROTECTED *****************/
        /****************** PUBLIC ********************/
        
        public static function toPlayerLink($sLink) {
            $sVideoId = self::_getVideoId($sLink);
            return 'http://www.youtube.com/v/'.$sVideoId.'&fs=1';
        }
        public static function toThumbnailLink($sLink) {
            $sVideoId = self::_getVideoId($sLink);
            return 'http://i2.ytimg.com/vi/'.$sVideoId.'/default.jpg';
        }

    }
    

?>