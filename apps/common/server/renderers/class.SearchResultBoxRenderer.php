<?
    /**
      * Discussion Renderer
      * @author Alexander Podgorny <ap.coding@gmail.com>
      * @license http://opensource.org/licenses/gpl-license.php GNU Public License 
      */

    /**
     * Works with StartupModel
     */
    class SearchResultBoxRenderer extends Renderer {
        public function render($oSearchResult) {
            $s = '';
            $sCssClass = $oSearchResult->getCssClass();
            $aKeywords = $oSearchResult->getDefinitionValue('aKeywords');
            switch (get_class($oSearchResult)) {
                case 'StartupModel':
                    $oStartup = $oSearchResult;
                    $sLink = Navigation::link(null, 'startup', 'view', array($oStartup['seo_name']));
                    $sStartupImageUrn = ImageLogic::getStartupImage($oStartup['seo_name'], ImageLogic::SIZE_M);
                    
                    $s .= Dom::DIV(
            		        Dom::DIV(array('class'=>'name'),  Dom::A(array('href'=>$sLink), $oStartup['name'])),
            		        Dom::DIV(array('class'=>'title'), Text::highlight($oStartup['title'], $aKeywords)),
            		        Dom::DIV(array('class'=>'image'),
            		            Dom::A(array('href'=>$sLink),
            		                Dom::IMG(array(
                		                'src'   => $sStartupImageUrn,
                		                'class' => 'userimage-m'
                		            ))
            		            )
            		        ),
            		        Dom::DIV(array('class'=>'excerpt'), 
            		            Text::highlight(Text::toExcerpt($oStartup['description'], 80, $aKeywords), $aKeywords)
            		        ),
            		        Dom::DIV(array('class'=>'rating'),
            		            Rater::renderReadOnly(RatingLogic::getRating($oSearchResult['id'], 'startup'))
            		        ),
            		        Dom::A(array('href'=>$sLink, 'class'=>'more'),
                                'Read more &raquo;'
                		    )
            		);
                    break;
                case 'MemberModel':
                    $oMember = $oSearchResult;
                    $sLink = Navigation::link(null, 'member', 'view', array($oMember['seo_name']));
                    $sMemberImageUrn = ImageLogic::getMemberImage($oMember['seo_name'], ImageLogic::SIZE_M);
                    
                    $s .= Dom::DIV(
            		    Dom::A(array('href'=>$sLink),
            		        Dom::DIV(array('class'=>'name'),  $oMember['first_name'].' '.$oMember['last_name']),
            		        Dom::DIV(array('class'=>'title'), $oMember['email']),
            		        Dom::DIV(array('class'=>'image'),
            		            Dom::IMG(array(
            		                'src'   => $sMemberImageUrn,
            		                'class' => 'userimage-m'
            		            ))
            		        ),
            		        Dom::DIV(array('class'=>'more'),  $oMember['more'])
            			)
            		);
                default:
                    return '';
            }
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