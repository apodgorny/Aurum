<?

 $aDefinition = array(
    'elements' => array(
        array(
            'type'  => 'hidden',
            'name'  => 'return_url'
        ),
        array(
            'type'  => 'text',
            'name'  => 'email',
            'id'    => '',
            'label' => 'Email',
            'value' => '',
            'rules' => array(
                array('rule' => Validation::RULE_MIN_SIZE_1),
                array('rule' => Validation::RULE_EMAIL)
            )
        ),
        array(
            'type'  => 'password',
            'name'  => 'password',
            'id'    => '',
            'label' => 'Password',
            'value' => '',
            'rules' => array(
                array('rule' => Validation::RULE_MIN_SIZE_1)
            )
        ),
        array(
            'type'  => 'submit',
            'value' => 'Log in',
        )
    )
 );
 
?>