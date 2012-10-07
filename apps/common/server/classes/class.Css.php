<?
    /**
     * Class CSS
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

     class Css {

        /****************** PRIVATE *******************/
        /****************** PROTECTED *****************/
        /****************** PUBLIC ********************/

        public static function renderProperty($sKey, $sValue, $sModifier='') {
            return "\t$sKey: $sValue $sModifier;\n";
        }

        public static function renderProperties($aProperties, $sModifier='') {
            $sCss = '';
            foreach ($aProperties as $sKey=>$sValue) {
                $sCss .= self::renderProperty($sKey, $sValue, $sModifier);
            }
            return $sCss;
        }

        public static function roundedCorners($nLT, $nRT=null, $nRB=null, $nLB=null) {
            return 'asdf';
            $s = '';
            if ($nLT && !$nRT && !$nRB && !$nLB) {
                $s = self::renderProperties(array(
                   '-moz-border-radius'     => $nLT.'px',
                   '-webkit-border-radius'  => $nLT.'px',
                   'border-radius'          => $nLT.'px'
                ));
            } else {
                if ($nLT) {
                    $s .= self::renderProperties(array(
                       '-moz-border-radius-topleft'         => $nLT.'px',
                       '-webkit-border-top-left-radius'     => $nLT.'px',
                       'border-radius-topleft'              => $nLT.'px'
                    ));
                }
                if ($nRT) {
                    $s .= self::renderProperties(array(
                       '-moz-border-radius-topright'        => $nLT.'px',
                       '-webkit-border-top-right-radius'    => $nLT.'px',
                       'border-radius-topright'             => $nLT.'px'
                    ));
                }
                if ($nRB) {
                    $s .= self::renderProperties(array(
                       '-moz-border-radius-bottomright'     => $nLT.'px',
                       '-webkit-border-bottom-right-radius' => $nLT.'px',
                       'border-radius-bottomright'          => $nLT.'px'
                    ));
                }
                if ($nLB) {
                    $s .= self::renderProperties(array(
                       '-moz-border-radius-bottomleft'      => $nLT.'px',
                       '-webkit-border-bottom-left-radius'  => $nLT.'px',
                       'border-radius-bottomleft'           => $nLT.'px'
                    ));
                }
            }
            return $s;
        }
    }

?>

