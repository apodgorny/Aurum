<?

    $aDefinition = array(
        'elements' => array(
            array(
                'type'  => 'hidden',
                'name'  => 'id'
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
                'type'  => 'text',
                'name'  => 'first_name',
                'label' => 'First name',
                'rules' => array(
                    array('rule' => Validation::RULE_NAME),
                    array('rule' => Validation::RULE_MIN_SIZE_2),
                    array('rule' => Validation::RULE_MAX_SIZE_16)
                )
            ),
            array(
                'type'  => 'text',
                'name'  => 'last_name',
                'label' => 'Last name',
                'rules' => array(
                    array('rule' => Validation::RULE_NAME),
                    array('rule' => Validation::RULE_MIN_SIZE_4),
                    array('rule' => Validation::RULE_MAX_SIZE_16)
                )
            ),
            array(
                'type'  => 'text',
                'name'  => 'headline',
                'label' => 'Headline',
                'rules' => array(
                    array('rule' => Validation::RULE_MAX_SIZE_50)
                )
            ),
            array(
                'type'  => 'textarea',
                'name'  => 'summary',
                'label' => 'Summary',
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