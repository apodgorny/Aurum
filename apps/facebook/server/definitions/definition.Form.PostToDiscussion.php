<?

 $aDefinition = array(
    'elements' => array(
        array(
            'type'  => 'textarea',
            'name'  => 'text',
            'id'    => '',
            'label' => 'Message',
            'value' => LoremIpsum::getSentence(),
            'rules' => array(
                array(
                    'rule'      => Validation::RULE_MIN_SIZE_1,
                    'message'   => ''
                )
            )
        ),
        array(
            'type'  => 'submit',
            'name'  => 'submit',
            'value' => 'Ask a question!',
        )
        
    )
 );
 
?>