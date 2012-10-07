<?
    /**
     * class LinkRenderer
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

    class LinkRenderer extends Renderer {

        /****************** PRIVATE *******************/
        
        private static $_bLightboxInitialized = false;
        
        /****************** PROTECTED *****************/
        /****************** PUBLIC ********************/

        public function render($oLink) {
            $sHtml = '';
            switch ($oLink['host']) {
                case 'youtube.com':
                case 'www.youtube.com':
                    
                    $sThumbnailLink = Youtube::toThumbnailLink($oLink['link']);
                    $sPlayerLink    = Youtube::toPlayerLink($oLink['link']);
                    $sCssClass      = $oLink->getCssClass();
                    
                    $nW = 100;
                    $nH = 100;
                    
                    $sHtml = 
                        Dom::BR().
                        Dom::A(array(
                                'href'=>$sPlayerLink, 
                                'class'=>$sCssClass, 
                                'rel'=>'lightvideo'
                            ), 
                            Dom::IMG(array('src'=>$sThumbnailLink))
                        ).Dom::BR();
                    
                    if (!self::$_bLightboxInitialized) {
                        $sHtml .= Dom::SCRIPT(
                            "$(function() {
                                $('.$sCssClass').MediaBox();
                            });"
                        );
                        
                        self::$_bLightboxInitialized = true;
                    }
                    
                    /*$sHtml = Dom::OBJECT(array('width'=>$nW, 'height'=>$nH),
                        Dom::PARAM(array('name'=>'movie', 'value'=>$sPlayerLink)),
                        Dom::PARAM(array('name'=>'allowFullScreen', 'value'=>'true')),
                        Dom::PARAM(array('name'=>'allowScriptAccess', 'value'=>'always')),
                        Dom::EMBED(array(
                            'src'               => $sPlayerLink,
                            'type'              => 'application/x-shockwave-flash',                            
                            'allowfullscreen'   => 'true',
                            'allowscriptaccess' => 'always',
                            'width'             => $nW,
                            'height'            => $nH
                        ))
                    );*/
                    break;
                default:
                    $sHtml = Dom::A(array('href'=>$oLink['link'], 'nofollow'=>'true'), $oLink['link']); 
            }
            return $sHtml;
        }
    }
    
?>