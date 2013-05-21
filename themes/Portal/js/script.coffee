###$ ->
	submenu		= $('.cs-header-main-submenu')
	menumore	= $('.cs-header-menu-more')
	user		= $('.cs-header-user')
	active		= false
	$('header')
		.mouseenter ->
			return if active
			active	= true
			submenu.slideDown 'medium'
			menumore.slideDown 'medium'
		.mouseleave ->
			return if not active
			setTimeout ->
					submenu.slideUp 'fast'
					menumore.slideUp 'fast', -> active	= false
				, 100