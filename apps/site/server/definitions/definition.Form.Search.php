<?

 $aDefinition = array(
    'action' => Navigation::link('site', 'search', 'index'),
    'method' => 'get',
    'elements' => array(
        array(
            'type'  => 'text',
            'name'  => 'q',
            'id'    => '',
            'label' => '',
        	'class' => 'search-query'
        ),
        array(
            'type'  => 'submit',
            'value' => 'Search',
            'class' => 'button-tertiary'
        )
    )
 );

?>