<?
    $aDefinition = array(
        'purpose' => Report::PURPOSE_FOUNDUPS,
        'events' => array(
            EventLogic::EVENT_MEMBER_REQUESTED_TO_JOIN_FOUNDUP => array(
                array(
                    'aggregator' => 'EventMemberRequestedToJoinStartupAggregator',
                    'renderer'   => 'EventMemberRequestedToJoinStartupRenderer'
                )
            ),
            EventLogic::EVENT_FOUNDUP_INVITED_MEMBER_TO_JOIN => array(
                array(
                    'aggregator' => 'EventStartupInvitedMemberToJoinAggregator',
                    'renderer'   => 'EventStartupInvitedMemberToJoinRenderer'
                )
            ),
            EventLogic::EVENT_FOUNDUP_CREATED => array(
                array(
                    'aggregator' => 'EventStartupCreatedAggregator',
                    'renderer'   => 'EventStartupCreatedRenderer'
                )
            ),
            EventLogic::EVENT_FOUNDUP_MEMBER_ADDED => array(
                array(
                    'aggregator' => 'EventStartupMemberAddedAggregator',
                    'renderer'   => 'EventStartupMemberAddedRenderer'
                )
            ),
            EventLogic::EVENT_FOUNDUP_PROFILE_UPDATED => array(
                array(
                    'aggregator' => 'EventStartupProfileUpdatedAggregator',
                    'renderer'   => 'EventStartupProfileUpdatedRenderer'
                )
            ),
            EventLogic::EVENT_FOUNDUP_PROFILE_IMAGE_UPLOADED => array(
                array(
                    'aggregator' => 'EventStartupProfileImageUploadedAggregator',
                    'renderer'   => 'EventStartupProfileImageUploadedRenderer'
                )
            ),
            EventLogic::EVENT_FOUNDUP_PROFILE_NAME_UPDATED => array(
                array(
                    'aggregator' => 'EventStartupProfileNameUpdatedAggregator',
                    'renderer'   => 'EventStartupProfileNameUpdatedRenderer'
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
            EventLogic::EVENT_FOUNDUP_PROFILE_RATING_RECEIVED => array(
                array(
                    'aggregator' => 'EventStartupProfileRatingReceivedAggregator',
                    'renderer'   => 'EventStartupProfileRatingReceivedRenderer'
                )
            ),
            EventLogic::EVENT_FOUNDUP_PROFILE_VISITED => array(
                array(
                    'aggregator' => 'EventStartupProfileVisitedAggregator',
                    'renderer'   => 'EventStartupProfileVisitedRenderer'
                )
            ),
            EventLogic::EVENT_FOUNDUP_SUBSCRIBED_TO => array(
                array(
                    'aggregator' => 'EventStartupSubscribedToCountAggregator',
                    'renderer'   => 'EventStartupSubscribedToCountRenderer'
                )
            )
        )
    );
?>
