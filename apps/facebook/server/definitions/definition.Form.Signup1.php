<?

 $aDefinition = array(
    'elements' => array(
        array(
            'type'  => 'text',
            'name'  => 'email',
            'id'    => '',
            'label' => 'Email',
            'value' => '',
            'rules' => array(
                array(
                    'rule'      => Validation::RULE_EMAIL,
                    'message'   => ''
                )
            )
        ),
        array(
            'type'  => 'password',
            'name'  => 'password1',
            'label' => 'Password',
            'rules' => array(
                array(
                    'rule'      => Validation::RULE_PASSWORD,
                    'message'   => ''
                )
            )
        ),
        array(
            'type'  => 'password',
            'name'  => 'password2',
            'label' => 'Confirm password',
        ),
        array(
            'type'  => 'submit',
            'value' => 'Join',
        )
        
    )
 );
 
?>