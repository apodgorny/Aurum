<?
    /**
      * class SearchResultRenderer
      * @author Alexander Podgorny <ap.coding@gmail.com>
      * @license http://opensource.org/licenses/gpl-license.php GNU Public License 
      */

    /**
     * Works with StartupModel and MemberModel
     */
    class SearchResultRenderer extends Renderer {
        public function render($oSearchResult) {
            $s = '';
            $sCssClass = $oSearchResult->getCssClass();
            $aKeywords = $oSearchResult->getDefinitionValue('aKeywords');

	    $sClass = get_class($oSearchResult);           
            switch ($sClass) {
                case 'StartupModel':
                    $sImageSrc  = ImageLogic::getStartupImage($oSearchResult['seo_name'], ImageLogic::SIZE_S);
                    $sLink      = Navigation::link(null, 'startup', 'view', array($oSearchResult['seo_name']));
                    $sName      = $oSearchResult['name'];
                    $sTitle     = Text::highlight($oSearchResult['title'], $aKeywords);
                    $sSummary   = Text::highlight(Text::toExcerpt($oSearchResult['description'], 80, $aKeywords), $aKeywords);
                    break;
                case 'MemberModel':
                    $sImageSrc  = ImageLogic::getMemberImage($oSearchResult['seo_name'], ImageLogic::SIZE_S);
                    $sLink      = Navigation::link(null, 'member', 'view', array($oSearchResult['seo_name']));
                    $sName      = $oSearchResult['first_name'].' '.$oSearchResult['last_name'];
                    $sTitle     = $oSearchResult['headline'];
                    $sSummary   = Text::highlight(Text::toExcerpt($oSearchResult['summary'], 80, $aKeywords), $aKeywords);
                    break;
            }
            
            $s .= 
            Dom::DIV(array('class'=>$sCssClass),
                    Dom::A(array('title'=>$sName, 'href'=>$sLink),
                        Dom::IMG(array('src'=>$sImageSrc, 'class'=>'userimage-s image'))
                    ) .
                    Dom::H1(array('title'=>$sName),
            		    Dom::A(array('class'=>'name', 'href'=>$sLink), $sName)
            		),
        			Dom::DIV(array('class'=>'desc'),
        			    Dom::SPAN(array('class'=>'title'), $sTitle).' '.$sSummary
        			)
    		);
            
            return Dom::DIV(
                array('class' => $sCssClass),
                $s
            );
        }
        public function renderReadOnly($oSearchResult) {
            $this->render($oSearchResult);
        }
    }

?>
