<?
    $aDefinition = array(
        'purpose' => Report::PURPOSE_PEOPLE,
        'events' => array(
            EventLogic::EVENT_MEMBER_SIGNUP_COMPLETE => array(
                array(
                    'aggregator' => 'EventMemberSignupCompleteAggregator',
                    'renderer'   => 'EventMemberSignupCompleteRenderer'
                )
            ),
            EventLogic::EVENT_MEMBER_PROFILE_COMMENT_RECEIVED => array(
                array(
                    'aggregator' => 'EventMemberProfileCommentReceivedAggregator',
                    'renderer'   => 'EventMemberProfileCommentReceivedRenderer'
                )
            ),
            EventLogic::EVENT_MEMBER_SUBSCRIBED_TO => array(
                array(
                    'aggregator' => 'EventMemberSubscribedToAggregator',
                    'renderer'   => 'EventMemberSubscribedToRenderer'
                ),
                array(
                    'aggregator' => 'EventMemberSubscribedToCountAggregator',
                    'renderer'   => 'EventMemberSubscribedToCountRenderer'
                )
            ),
            EventLogic::EVENT_MEMBER_PROFILE_UPDATED => array(
                array(
                    'aggregator' => 'EventMemberProfileUpdatedAggregator',
                    'renderer'   => 'EventMemberProfileUpdatedRenderer'
                )
            ),
            EventLogic::EVENT_MEMBER_PROFILE_IMAGE_UPLOADED => array(
                array(
                    'aggregator' => 'EventMemberProfileImageUploadedAggregator',
                    'renderer'   => 'EventMemberProfileImageUploadedRenderer'
                )
            ),
            EventLogic::EVENT_MEMBER_PROFILE_NAME_UPDATED => array(
                array(
                    'aggregator' => 'EventMemberProfileNameUpdatedAggregator',
                    'renderer'   => 'EventMemberProfileNameUpdatedRenderer'
                )
            ),
            EventLogic::EVENT_FOUNDUP_SUBSCRIBED_TO => array(
                array(
                    'aggregator' => 'EventStartupSubscribedToAggregator',
                    'renderer'   => 'EventStartupSubscribedToRenderer'
                )
            ),
            EventLogic::EVENT_FOUNDUP_PROFILE_COMMENT_RECEIVED => array(
                array(
                    'aggregator' => 'EventStartupProfileCommentReceivedCountAggregator',
                    'renderer'   => 'EventStartupProfileCommentReceivedCountRenderer'
                ),
                array(
                    'aggregator' => 'EventStartupProfileCommentReceivedAggregator',
                    'renderer'   => 'EventStartupProfileCommentReceivedRenderer'
                )
            ),
            EventLogic::EVENT_FOUNDUP_MEMBER_ADDED => array(
                array(
                    'aggregator' => 'EventStartupMemberAddedAggregator',
                    'renderer'   => 'EventStartupMemberAddedRenderer'
                )
            ),
            EventLogic::EVENT_FOUNDUP_CREATED => array(
                array(
                    'aggregator' => 'EventStartupCreatedAggregator',
                    'renderer'   => 'EventStartupCreatedRenderer'
                )
            )
        )
    );
?>
