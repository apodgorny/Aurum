<?

 $aDefinition = array(
    'enctype'   => 'multipart/form-data',
    'elements'  => array(
        array(
            'type'  => 'hidden',
            'name'  => 'MAX_FILE_SIZE',
            'value' => 100000000
        ),
        array(
            'type'  => 'hidden',
            'name'  => 'filename',
            'value' => 'uploadedfile'
        ),
        array(
            'type'  => 'file',
            'name'  => 'uploadedfile',
			'label'	=> 'New image'
        ),
        array(
            'type'  => 'submit',
            'value' => 'Upload image',
            'group' => 'buttons'
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