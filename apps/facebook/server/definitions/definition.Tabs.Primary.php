<?
    $oController = Controller::getInstance();
    $sController = strtolower($_ENV['CONTROLLER']);
    $sAction     = strtolower($_ENV['ACTION']);

    $bMy = preg_match('/^my/', $sAction) ||
        $sController == 'mail' ||
        ($oController->oMember && isset($oController->oMember['id']) && $oController->oMember['id'] == MemberLogic::me('id')) ||
        ($sController == 'startup' && $sAction == 'create') ||
        ($sController == 'member' && $sAction == 'edit') ||
        ($sController == 'image' && $sAction == 'upload') ||
        ($sController == 'image' && $sAction == 'crop');

    $sSelectedItem = null;
    if ($bMy) {
        $sSelectedItem = 'my';
    } else if (($sController == 'startup' && $sAction != 'create') || ($sController == 'search' && $sAction == 'startups')) {
        $sSelectedItem = 'startups';
    } else if ($sController == 'member' || ($sController == 'search' && $sAction == 'people')) {
	    $sSelectedItem = 'people';
    } else if ($sController == 'knowledge') {
        $sSelectedItem = 'knowledge';
    } else if ($sController == 'news') {
        $sSelectedItem = 'news';
    } else if ($sController == 'company') {
        $sSelectedItem = 'company';
    }
	$aDefinition = array(
	    'selected' => $sSelectedItem,
		'items' => array(
		    array(
				'label' => 'My',
				'name'  => 'my',
				'id'    => 'tabs_primary_my',
				'link'  => Navigation::link('site', 'home', 'myhome'),
				'menu'  => 'Tabs.My'
			),
			array(
				'label' => 'Startups',
				'name'  => 'startups',
				'id'    => 'tabs_primary_startups',
				'link'  => Navigation::link('site', 'startup', 'search'),
				'menu'  => 'Tabs.Startups'
			),
			array(
				'label' => 'People',
				'name'  => 'people',
				'id'    => 'tabs_primary_people',
				'link'  => Navigation::link('site', 'member', 'search')
			),
			array(
				'label' => 'Knowledge',
				'name'  => 'knowledge',
				'id'    => 'tabs_primary_knowledge',
				'link'  => Navigation::link('site', 'knowledge', 'index')
			),
			/*
			array(
				'label' => 'News',
				'name'  => 'news',
				'id'    => 'tabs_primary_news',
				'link'  => Navigation::link('site', 'news', 'index')
			),
			*/
			array(
				'label' => 'About',
				'name'  => 'company',
				'id'    => 'tabs_primary_company',
				'link'  => Navigation::link('site', 'company', 'about'),
				'menu'  => 'Tabs.Company'
			)
		)
	);
?>