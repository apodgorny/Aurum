<?

 $aDefinition = array(
    'elements' => array(
        array(
            'type'  => 'hidden',
            'name'  => 'startup_id'
        ), 
        array(
            'type'  => 'text',
            'name'  => 'startup',
            'label' => 'Startup',
            'readonly' => true
        ),
        array(
            'type'  => 'text',
            'name'  => 'subject',
            'label' => 'Subject',
            'value' => 'Join request',
            'readonly'  => true
        ),
        array(
            'type'  => 'textarea',
            'name'  => 'text',
            'label' => 'Message',
            'value' => 'Dear {startup_name}\'s startup,'."\n\n".
                'I would like to join the development of your product.'."\n".
                'Please accept me as a member of your startup.',
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
            'value' => 'Request to join',
        )
        
    )
 );
 
?>