<?php
/**
 * @package        Local_redirect
 * @category       plugins
 * @author         Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright      Copyright (c) 2011-2013, Nazar Mokrynskyi
 * @license        MIT License, see license.txt
 */
namespace	cs;
Trigger::instance()->register(
	'System/User/construct/after',
	function () {
		if (MODULE == 'System' || !in_array('Local_redirect', Config::instance()->components['plugins'])) {
			return;
		}
		if (!in_array(substr($_SERVER['REQUEST_URI'], 1, 2), ['en', 'ru', 'uk'])) {
			header('Location: /'.trim(Language::instance()->clang.'/'.trim($_SERVER['REQUEST_URI'], '/'), '/'), true, 301);
		}
	}
);