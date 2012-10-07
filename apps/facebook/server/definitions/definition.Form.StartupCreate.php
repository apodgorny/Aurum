<?

    $aDefinition = array(
        'elements' => array(
            array(
                'type'  => 'text',
                'name'  => 'name',
                'label' => 'Startup name',
                'note'  => 'NOTE: Changing names in the future is discouraged!',
                'value' => LoremIpsum::getWord(),
                'rules' => array(
                    array('rule' => Validation::RULE_TITLE),
                    array('rule' => Validation::RULE_MIN_SIZE_2),
                    array('rule' => Validation::RULE_MAX_SIZE_30)
                )
            ),
            array(
                'type'  => 'text',
                'name'  => 'title',
                'label' => 'Idea snapshot',
                'note'  => 'One-line summary of your idea',
                'value' => substr(LoremIpsum::getSentence(), 0, 50),
                'rules' => array(
                    array('rule' => Validation::RULE_MIN_SIZE_10),
                    array('rule' => Validation::RULE_MAX_SIZE_50)
                )
            ),
			array(
	            'type'  => 'select',
	            'name'  => 'category',
	            'label' => 'Industry',
	            'rules' => array(
	                array('rule' => Validation::RULE_MIN_SIZE_4),
	                array('rule' => Validation::RULE_MAX_SIZE_400)
	            )
	        ),
            array(
                'type'  => 'textarea',
                'name'  => 'description',
                'label' => 'Description',
                'note'  => 'What is your idea?',
                'value' => substr(LoremIpsum::getParagraph(), 0, 400),
                'rules' => array(
                    array('rule' => Validation::RULE_MAX_SIZE_400)
                )
            ),
            array(
                'type'  => 'submit',
                'value' => 'Create startup',
                'name'  => 'submit'
            ),
        )
    );
    
?>