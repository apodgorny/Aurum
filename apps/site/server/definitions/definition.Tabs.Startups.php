<?
    $sController = strtolower($_ENV['CONTROLLER']);
    $sAction     = strtolower($_ENV['ACTION']);
    
    $sSelectedItem = null;
    if ($sController == 'search') {
        $sSelectedItem = 'startups';
    }

    
	$aDefinition = array(
	    'selected' => $sSelectedItem,
		'items'    => array(
			array(
				'label' => 'All startups',
				'name'  => 'startups',
				'link'  => Navigation::link('site', 'search', 'startups')
			),
			array(
				'label' => 'My startups',
				'name'  => 'mystartups',
				'link'  => Navigation::link('site', 'startup', 'mystartups')
			)
		)
	);
?>