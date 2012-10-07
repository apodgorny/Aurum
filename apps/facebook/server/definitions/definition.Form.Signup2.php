<?

    $aDefinition = array(
        'elements' => array(
            array(
                'type'  => 'hidden',
                'name'  => 'id'
            ),
            array(
                'type'  => 'text',
                'name'  => 'first_name',
                'label' => 'First name',
                'note'  => 'Will not be displayed',
                'rules' => array(
                    array(
                        'rule'      => Validation::RULE_ALPHA,
                    ),
                    array(
                        'rule'      => Validation::RULE_MIN_SIZE_1,
                    ),
                    array(
                        'rule'      => Validation::RULE_MAX_SIZE_16,
                    )
                )
            ),
            array(
                'type'  => 'text',
                'name'  => 'last_name',
                'label' => 'Last name',
                'note'  => 'Will not be displayed',
                'rules' => array(
                    array(
                        'rule'      => Validation::RULE_ALPHA,
                    ),
                    array(
                        'rule'      => Validation::RULE_MIN_SIZE_1,
                    ),
                    array(
                        'rule'      => Validation::RULE_MAX_SIZE_16,
                    )
                )
            ),
            array(
                'type'  => 'submit',
                'value' => 'Finish!',
            )

        )
    );

?>