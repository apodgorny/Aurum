<?

    $oController = Controller::getInstance();
    
    $aDefinition = array(
        'elements' => array(
            array(
                'type'  => 'cropper',
                'name'  => 'crop',
            ),
            array(
     		    'name'  => 'submit',
                'type'  => 'submit',
                'group' => 'buttons',
                'value' => 'Crop image',
            ),
            array(
                'type'  => 'button',
                'value' => 'Cancel',
                'group' => 'buttons',
                'name'  => 'cancel',
                'onclick' => ''             // To be added in controller
            )
        )
    );
 
?>