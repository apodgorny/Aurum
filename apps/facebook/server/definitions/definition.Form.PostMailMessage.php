<?

 $aDefinition = array(
    'elements' => array(
        // Field used for new message only
        array(
            'type'  => 'hidden',
            'name'  => 'mm'
        ),
        // Field used for reply only
        array(
            'type'  => 'hidden',
            'name'  => 'tid'
        ),
        // Field used for reply only
        array(
            'type'  => 'hidden',
            'name'  => 'reply_to_id'
        ),
        array(
            'type'  => 'text',
            'name'  => 'recipients',
            'label' => 'To',
            'readonly' => true
        ),
        array(
            'type'  => 'text',
            'name'  => 'subject',
            'id'    => '',
            'label' => 'Subject',
            'value' => '',
            'rules' => array(
                array(
                    'rule'      => Validation::RULE_MIN_SIZE_1,
                    'message'   => ''
                ),
                array(
                    'rule'      => Validation::RULE_MAX_SIZE_50,
                    'message'   => ''
                )
            )
        ),
        array(
            'type'  => 'textarea',
            'name'  => 'text',
            'id'    => '',
            'label' => 'Message',
            'value' => '',
            'rules' => array(
                array(
                    'rule'      => Validation::RULE_MIN_SIZE_1,
                    'message'   => ''
                ),
                array(
                    'rule'      => Validation::RULE_MAX_SIZE_400,
                    'message'   => ''
                )
            )
        ),
        array(
            'type'  => 'submit',
            'name'  => 'submit',
            'value' => 'Send',
        )
        
    )
 );
 
?>