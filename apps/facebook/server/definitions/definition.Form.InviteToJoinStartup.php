<?

 $aDefinition = array(
    'elements' => array(
        array(
            'type'  => 'text',
            'name'  => 'member',
            'label' => 'Member',
            'readonly' => true
        ),
        array(
            'type'  => 'select',
            'name'  => 'startup_id',
            'label' => 'Startup',
            'options' => array()
        ),
        array(
            'type'  => 'text',
            'name'  => 'subject',
            'label' => 'Subject',
            'value' => 'Invitation to join startup',
            'readonly'  => true
        ),
        array(
            'type'  => 'textarea',
            'name'  => 'text',
            'label' => 'Message',
            'value' => 'Dear {member_name},'."\n\n".
                "We would like to invite you to join our startup\n".
                'Would you be interested?',
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
            'value' => 'Invite to join',
        )
        
    )
 );
 
?>