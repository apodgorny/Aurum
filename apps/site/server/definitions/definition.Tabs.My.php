<?
    $sController = strtolower($_ENV['CONTROLLER']);
    $sAction     = strtolower($_ENV['ACTION']);
    
    $sSelectedItem = null;
    if ($sController == 'home' && $sAction == 'myhome') {
        $sSelectedItem = 'myhome';
    } else if ($sController == 'startup' && $sAction == 'mystartups') {
        $sSelectedItem = 'mystartups';
    } else if ($sController == 'mail') {
        $sSelectedItem = 'mymail';
    } else if ($sController == 'member' && $sAction == 'view') {
        $sSelectedItem = 'myprofile';
    }

    
	$aDefinition = array(
	    'selected' => $sSelectedItem,
		'items'    => array(
			array(
				'label' => 'My home',
				'name'  => 'myhome',
				'link'  => Navigation::link('site', 'home', 'myhome')
			),
			array(
				'label' => 'My startups',
				'name'  => 'mystartups',
				'link'  => Navigation::link('site', 'startup', 'mystartups')
			),
			array(
				'label' => 'My mail',
				'name'  => 'mymail',
				'link'  => Navigation::link('site', 'mail', 'inbox')
			),
			array(
				'label' => 'My profile',
				'name'  => 'myprofile',
				'link'  => Navigation::link('site', 'member', 'view', array(MemberLogic::me('seo_name')))
			)
		)
	);
?>