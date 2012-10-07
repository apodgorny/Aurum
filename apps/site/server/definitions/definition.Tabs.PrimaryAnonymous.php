<?

    $sController = strtolower($_ENV['CONTROLLER']);
    $sAction     = strtolower($_ENV['ACTION']);

    $sSelectedItem = null;
    if ($sController == 'home') {
        $sSelectedItem = 'home';
    } else if ($sController == 'startup' || ($sController == 'search' && $sAction == 'startups')) {
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
		'items'    => array(
			array(
				'label' => 'Home',
				'name'  => 'home',
				'id'    => 'tabs_primary_anon_home',
				'link'  => Navigation::link('site', 'home', 'index')
			),
			array(
				'label' => 'Startups',
				'name'  => 'startups',
				'id'    => 'tabs_primary_anon_startups',
				'link'  => Navigation::link('site', 'search', 'startups')
			),
			array(
				'label' => 'People',
				'name'  => 'people',
				'id'    => 'tabs_primary_anon_people',
				'link'  => Navigation::link('site', 'search', 'people')
			),
			array(
				'label' => 'Knowledge',
				'name'  => 'knowledge',
				'id'    => 'tabs_primary_anon_knowledge',
				'link'  => Navigation::link('site', 'knowledge', 'index')
			),
			/*
			array(
				'label' => 'News',
				'name'  => 'news',
				'id'    => 'tabs_primary_anon_news',
				'link'  => Navigation::link('site', 'news', 'index')
			),
			*/
			array(
				'label' => 'About',
				'name'  => 'company',
				'id'    => 'tabs_primary_anon_company',
				'link'  => Navigation::link('site', 'company', 'about'),
				'menu' => 'Tabs.Company'
			)
		)
	);
?>