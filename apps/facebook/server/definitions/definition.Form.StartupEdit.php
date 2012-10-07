<?

    $aDefinition = array(
        'elements' => array(
            array(
                'type'  => 'hidden',
                'name'  => 'id'
            ),
            array(
                'type'  => 'text',
                'name'  => 'name',
                'label' => 'Startup name',
                'note'  => 'NOTE: Changing name is highly discouraged. Please see FAQ.',
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
                'rules' => array(
                    array('rule' => Validation::RULE_MAX_SIZE_5000)
                )
            ),
            array(
                'type'  => 'submit',
                'value' => 'Save',
                'group' => 'buttons',
                'name'  => 'submit'
            ),
            array(
                'type'  => 'button',
                'value' => 'Cancel',
                'group' => 'buttons',
                'name'  => 'cancel',
                'onclick' => 'history.back()'
            )
        )
    );
    
?>