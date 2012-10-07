<?
    /**
     * NewsItemHtmlRenderer
     * @author Konstantin Kouptsov <konstantin@kouptsov.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */


    class NewsItemHtmlRenderer extends Renderer {

        public function render($oItem) {
            $sCssClass = $oItem->getCssClass();
            $sGridName = 'ChannelItemHtml-'.$oItem['id'];
            $bDoRender = true;

            switch ($oItem['target_type']) {
                case 'startup':
                    $oTarget        = StartupLogic::getStartup($oItem['target_id']);
                    $sLinkToTarget  = Navigation::link(null, 'startup', 'view', array($oTarget['seo_name']));
                    $sTargetName    = Dom::A(array('href'=>$sLinkToTarget), $oTarget['name']);
                    $sTargetTitle   = 'startup '.$sTargetName;
                    break;
                case 'member':
                    $oTarget        = MemberLogic::getMember($oItem['target_id']);
                    $sLinkToTarget  = Navigation::link(null, 'member', 'view', array($oTarget['seo_name']));
                    $sTargetName    = Dom::A(array('href'=>$sLinkToTarget), $oTarget['first_name'].' '.$oTarget['last_name']);
                    $sTargetTitle   = $sTargetName;
                    break;
            }

            $oChannel =  EventLogic::getSourceChannel($oItem['target_id'], $oItem['target_type']);

            switch ($oItem['type']) {
                /***************************************************************/
                case EventLogic::EVENT_MEMBER_REQUESTED_TO_JOIN_FOUNDUP: /* OK */
                /***************************************************************/
                    $bIsMeMember     = StartupLogic::isStartupMember(MemberLogic::me('id'), $oItem['reference_id_1']);
                    $bIsMeRequester  = MemberLogic::me('id') == $oItem['reference_id_2'];
                    if (!$bIsMeMember && !$bIsMeRequester) { $bDoRender = false; break; }

                    $oStartup        = StartupLogic::getStartup($oItem['reference_id_1']);
                    $oMember         = MemberLogic::getMember($oItem['reference_id_2']);

                    $bIsMeDirector   = StartupLogic::isStartupDirector(MemberLogic::me('id'), $oStartup['id']);

                    $oInvitation     = StartupLogic::getInvitation($oMember['id'], $oStartup['id'], StartupLogic::INVITATION_TYPE_REQUEST);
                    $sLinkToAccept   = Navigation::link(null, 'invitation', 'accept', array('code'=>$oInvitation['access_code']));

                    $sLinkToMember   = Navigation::link(null, 'member', 'view', array($oMember['seo_name']));
                    $sLinkToStartup  = Navigation::link(null, 'startup', 'view', array($oStartup['seo_name']));

                    $sMemberName     = Dom::A(array('href'=>$sLinkToMember),
                        $oMember['first_name'].' '.$oMember['last_name']
                    );
                    $sStartupName    = Dom::A(array('href'=>$sLinkToStartup),
                        $oStartup['name']
                    );

                    if ($bIsMeRequester) {
                        $oItem['title'] = 'You have requested to join startup '.$sStartupName;
                        $oItem['description'] = $oItem['value_new'];
                    } else if ($bIsMeDirector) {
                        $oItem['title'] = $sMemberName.' has requested to join startup '.$sStartupName;
                        $oItem['description'] = $oItem['value_new'];
                        if ($oInvitation['is_accepted']==0) {
                            $oItem['title'] .= Dom::NBSP(2).Dom::A(array(
                                'href'  => $sLinkToAccept,
                                'class' => 'button button-inline-secondary',
                            ), 'Accept &raquo;');
                        }
                    } else {
                        // All other subscribers to member or startup
                        $bDoRender = false;
                    }

                    break;
                /***************************************************************/
                case EventLogic::EVENT_FOUNDUP_INVITED_MEMBER_TO_JOIN: /* OK */
                /***************************************************************/
                    $bIsMeMember    = StartupLogic::isStartupMember(MemberLogic::me('id'), $oItem['reference_id_1']);
                    $bIsMeInvitee   = MemberLogic::me('id') == $oItem['reference_id_2'];
                    if (!$bIsMeMember && !$bIsMeInvitee) { $bDoRender = false; break; }

                    $oStartup       = StartupLogic::getStartup($oItem['reference_id_1']);
                    $oMember        = MemberLogic::getMember($oItem['reference_id_2']);

                    $bIsMeDirector  = StartupLogic::isStartupDirector(MemberLogic::me('id'), $oStartup['id']);

                    $oInvitation    = StartupLogic::getInvitation($oMember['id'], $oStartup['id'], StartupLogic::INVITATION_TYPE_INVITATION);
                    $sLinkToAccept  = Navigation::link(null, 'invitation', 'accept', array('code'=>$oInvitation['access_code']));

                    $sLinkToMember  = Navigation::link(null, 'member', 'view', array($oMember['seo_name']));
                    $sLinkToStartup = Navigation::link(null, 'startup', 'view', array($oStartup['seo_name']));

                    $sMemberName    = Dom::A(array('href'=>$sLinkToMember),
                        $oMember['first_name'].' '.$oMember['last_name']
                    );
                    $sStartupName   = Dom::A(array('href'=>$sLinkToStartup),
                        $oStartup['name']
                    );

                    if ($bIsMeInvitee) {
                        $oItem['title'] = 'You have been invited to join '.$sStartupName;
                        $oItem['description'] = $oItem['value_new'];
                        if ($oInvitation['is_accepted'] == 0) {
                            $oItem['title'] .= Dom::NBSP(2).Dom::A(array(
                                'href'  => $sLinkToAccept,
                                'class' => 'button button-inline-secondary',
                            ), 'Accept &raquo;');
                        }
                    } else if ($bIsMeMember) {
                        $oItem['title'] = 'Startup '.$sStartupName.' has invited '.$sMemberName.' to join';
                        $oItem['description'] = $oItem['value_new'];
                    } else {
                        // All other subscribers to member or startup
                        $bDoRender = false;
                    }

                    break;
                /***************************************************************/
                case EventLogic::EVENT_MEMBER_PROFILE_UPDATED: /* OK */
                /***************************************************************/
                    $oItem['title']       = $sTargetTitle.' has updated profile';
                    $oItem['description'] = $oItem['value_new'];
                    break;
                /***************************************************************/
                case EventLogic::EVENT_MEMBER_PROFILE_IMAGE_UPLOADED: /* OK */
                /***************************************************************/
                    $oItem['title']       = $sTargetTitle.' has updated profile picture';
                    /*
                    $oItem['description'] = Dom::A(array('href'=>$sLinkToActeeProfile),
                        Dom::IMG(array('src'=>ImageLogic::getMemberImage($oItem->oActee['seo_name'], ImageLogic::SIZE_S)))
                    );
                    */
                    break;
                /***************************************************************/
                case EventLogic::EVENT_MEMBER_PROFILE_NAME_UPDATED: /* OK */
                /***************************************************************/
                    $sTargetName          = Dom::A(array('href'=>$sLinkToTarget), $oItem['value_new']);
                    $oItem['title']       = $sTargetName.' has changed name';
                    $oItem['description'] = $oItem['value_new'].' used to go by '.$oItem['value_old'];
                    break;
                /***************************************************************/
                case EventLogic::EVENT_MEMBER_PROFILE_EXTENDED:
                /***************************************************************/
                    $oItem['title']       = $sTargetTitle.' has extended profile';
                    $oItem['description'] = $oItem['value_new'];
                    break;
                /***************************************************************/
                case EventLogic::EVENT_MEMBER_PROFILE_MEDIA_ADDED:
                /***************************************************************/
                    break;
                /***************************************************************/
				case EventLogic::EVENT_FOUNDUP_CREATED: /* OK */
				/***************************************************************/
				    $oItem['title']       = 'Brand new startup "'.$sTargetTitle.'" has been created';
                    $oItem['description'] = $oItem['value_new'];
                    break;
                /***************************************************************/
	        	case EventLogic::EVENT_FOUNDUP_MEMBER_ADDED: /* OK */
	        	/***************************************************************/
	        	    switch ($oItem['target_type']) {
	        	        case 'startup':
                            $oMember              = MemberLogic::getMember($oItem['reference_id_2']);
                            $sLinkToMember        = Navigation::link(null, 'member', 'view', array($oMember['seo_name']));
                            $sMemberName          = Dom::A(array('href'=>$sLinkToMember), $oMember['first_name'].' '.$oMember['last_name']);
	        	            $oItem['title']       = $sTargetTitle.' has a new member '.$sMemberName;
                            $oItem['description'] = $oItem['value_new'];
                            break;
	        	        case 'member':
                            $oStartup             = StartupLogic::getStartup($oItem['reference_id_1']);
                            $sLinkToStartup       = Navigation::link(null, 'startup', 'view', array($oStartup['seo_name']));
                            $sStartupName         = Dom::A(array('href'=>$sLinkToStartup), $oStartup['name']);
	        	            $oItem['title']       = $sTargetTitle.' is now a member of '.$sStartupName;
                            $oItem['description'] = $oItem['value_new'];
                            break;
	        	        default:
	        	            break;
	        	    }
                    break;
                /***************************************************************/
	        	case EventLogic::EVENT_FOUNDUP_PROFILE_UPDATED: /* OK */
	        	/***************************************************************/
                    $oItem['title']       = $sTargetTitle.' has updated profile';
                    $oItem['description'] = $oItem['value_new'];
                    break;
                /***************************************************************/
	        	case EventLogic::EVENT_FOUNDUP_PROFILE_IMAGE_UPLOADED: /* OK */
	        	/***************************************************************/
                    $oItem['title']       = $sTargetTitle.' has updated profile picture';
                    break;
                /***************************************************************/
	        	case EventLogic::EVENT_FOUNDUP_PROFILE_NAME_UPDATED: /* OK */
	        	/***************************************************************/
                    $sTargetName          = Dom::A(array('href'=>$sLinkToTarget), $oItem['value_new']);
                    $sTargetTitle         = 'Startup '.$sTargetName;
                    $oItem['title']       = $sTargetTitle.' has changed name';
                    $oItem['description'] = $oItem['value_new'].' was previously known as '.$oItem['value_old'];
                    break;
                /***************************************************************/
	        	case EventLogic::EVENT_GENERAL_SUBSCRIBED:
	        	/***************************************************************/
	        	    $oSubscriber        = MemberLogic::getMember($oItem['reference_id_2']);
	        	    $sLinkToSubscriber  = Navigation::link(null, 'member', 'view', array($oSubscriber['seo_name']));
	        	    $sSubscriber     = Dom::A(array('href' => $sLinkToSubscriber),
	        	        $oSubscriber['first_name'].' '.$oSubscriber['last_name']
	        	    );
	        	    $nMeId           = MemberLogic::me('id');
	        	    $bIsMeSubscriber = ($oSubscriber['id'] == $nMeId);
	        	    $bDoRender       = false;

	        	    Debug::show('EVENT_GENERAL_SUBSCRIBED $oItem:',$oItem);
                    switch ($oItem['target_type']) {
                        case 'startup':
                            $oStartup        = StartupLogic::getStartup($oItem['reference_id_1']);
                            Debug::show('EVENT_GENERAL_SUBSCRIBED $oStartup:',$oStartup);
                            $sLinkToStartup  = Navigation::link(null, 'startup', 'view', array($oStartup['seo_name']));
                            $sStartup        = Dom::A(array('href' => $sLinkToStartup), $oStartup['name']);
                            $bIsMeDirector   = StartupLogic::isStartupDirector($nMeId, $oStartup['id']);

                            if ($bIsMeSubscriber) {
                                $oItem['title']       = 'You are now following startup '.$sStartup.'.';
                                $bDoRender = true;
                            } else if ($bIsMeDirector) {
                                $oItem['title']       = $sSubscriber.' is now following your startup '.$sStartup.'.';
                                $bDoRender = true;
                            }
                            break;

                        case 'member':
                            $oSubscribee        = MemberLogic::getMember($oItem['reference_id_1']);
                            $sLinkToSubscribee  = Navigation::link(null, 'member', 'view', array($oSubscribee['seo_name']));
                            $sSubscribee        = Dom::A(array('href' => $sLinkToSubscribee),
                                $oSubscribee['first_name'].' '.$oSubscribee['last_name']
                            );
                            $bIsMeSubscribee    = ($oSubscribee['id'] == $nMeId);

                            if ($bIsMeSubscriber) {
                                $oItem['title']       = 'You are now following '.$sSubscribee.'.';
                                $bDoRender = true;
                            } else if ($bIsMeSubscribee) {
                                $oItem['title']       = $sSubscriber.' is now following you.';
                                $bDoRender = true;
                            }
                            break;

                    }
                    $oItem['description'] = $oItem['value_new'];

                    break;
                /***************************************************************/
                case EventLogic::EVENT_GENERAL_UNSUBSCRIBED:
                /***************************************************************/
                    $oSubscriber        = MemberLogic::getMember($oItem['reference_id_2']);
                    $sLinkToSubscriber  = Navigation::link(null, 'member', 'view', array($oSubscriber['seo_name']));
                    $sSubscriber     = Dom::A(array('href' => $sLinkToSubscriber),
                        $oSubscriber['first_name'].' '.$oSubscriber['last_name']
                    );
                    $nMeId           = MemberLogic::me('id');
                    $bIsMeSubscriber = ($oSubscriber['id'] == $nMeId);
                    $bDoRender       = false;

                    Debug::show('EVENT_GENERAL_UNSUBSCRIBED $oItem:',$oItem);
                    switch ($oItem['target_type']) {
                        case 'startup':
                            $oStartup        = StartupLogic::getStartup($oItem['reference_id_1']);
                            Debug::show('EVENT_GENERAL_UNSUBSCRIBED $oStartup:',$oStartup);
                            $sLinkToStartup  = Navigation::link(null, 'startup', 'view', array($oStartup['seo_name']));
                            $sStartup        = Dom::A(array('href' => $sLinkToStartup), $oStartup['name']);
                            $bIsMeDirector   = StartupLogic::isStartupDirector($nMeId, $oStartup['id']);

                            if ($bIsMeSubscriber) {
                                $oItem['title']       = 'You are no longer following startup '.$sStartup.'.';
                                $bDoRender = true;
                            } else if ($bIsMeDirector) {
                                $oItem['title']       = $sSubscriber.' is no longer following your startup '.$sStartup.'.';
                                $bDoRender = true;
                            }
                            break;

                        case 'member':
                            $oSubscribee        = MemberLogic::getMember($oItem['reference_id_1']);
                            $sLinkToSubscribee  = Navigation::link(null, 'member', 'view', array($oSubscribee['seo_name']));
                            $sSubscribee        = Dom::A(array('href' => $sLinkToSubscribee),
                                $oSubscribee['first_name'].' '.$oSubscribee['last_name']
                            );
                            $bIsMeSubscribee    = ($oSubscribee['id'] == $nMeId);

                            if ($bIsMeSubscriber) {
                                $oItem['title']       = 'You are no longer following '.$sSubscribee.'.';
                                $bDoRender = true;
                            } else if ($bIsMeSubscribee) {
                                $oItem['title']       = $sSubscriber.' is no longer following you.';
                                $bDoRender = true;
                            }
                            break;
                    }
                    $oItem['description'] = $oItem['value_new'];

                    break;
                /***************************************************************/
                case EventLogic::EVENT_FOUNDUP_MERGED:
                case EventLogic::EVENT_FOUNDUP_FUNDING_RECEIVED:
                case EventLogic::EVENT_FOUNDUP_MENTIONED_IN_NEWS:
                case EventLogic::EVENT_FOUNDUP_PROFILE_EXTENDED:
	        	case EventLogic::EVENT_FOUNDUP_PROFILE_MEDIA_ADDED:
	        	case EventLogic::EVENT_FOUNDUP_PROFILE_COMMENT_RECEIVED:
	        	//case EventLogic::EVENT_FOUNDUP_PROFILE_RATING_RECEIVED:
	        	    break;

	        	/*************** AGGREGATED EVENTS **************/

                /***************************************************************/
	        	case EventLogic::EVENT_AGGREGATED_MEMBER_PROFILE_VISITED: /* OK */
	        	/***************************************************************/
                    $oItem['title']       = 'Your profile has been visited '.$oItem['value_new'].' time'.
                        ($oItem['value_new'] != 1 ? 's' : '').' since your last login.';
                    $oItem['description'] = '';
                    $bDoRender = $oItem['value_new'] != 0 && (
		                $oItem['target_id'] == MemberLogic::me('id') ||
                        $oItem['target_type'] != 'member');

	        		break;
	        	/***************************************************************/
	        	case EventLogic::EVENT_AGGREGATED_FOUNDUP_PROFILE_VISITED: /* OK */
	        	/***************************************************************/
                    $oItem['title']       = 'Profile of '.$sTargetName.' has been visited '.$oItem['value_new'].' time'.
                        ($oItem['value_new'] != 1 ? 's' : '').' since your last login.';
                    $oItem['description'] = '';
                    $bDoRender = $oItem['value_new'] != 0 && (
                        StartupLogic::isStartupMember(MemberLogic::me('id'), $oItem['target_id']));
                    break;

                /************************************************/

	        	default:
	        		$bDoRender = false;
	        		break;
	        }

	        //Debug::show('$oItem:', $oItem);
	        //Debug::show('$bDoRender:', $bDoRender);

	        if ($bDoRender) {
	            $s = Dom::DIV(
	                array('class' => $sCssClass),
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
                           array('class' => 'title', 'title' => strip_tags($oItem['title'])),
                           ucfirst(trim($oItem['title']))
                        ).
                        Dom::DIV(
                           array('class' => 'description'),
                           $oItem['description']
                        ).
                        Dom::DIV(
                           array('class' => 'date'),
                           $oItem['create_date']
                        ).
		            Grid::END($sGridName)
	            );
	        } else {
	           $s = '';
	        }
            return $s;
        }
    }

?>