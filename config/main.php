<?php
/**
 * @package		CleverStyle CMS
 * @author		Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright	Copyright (c) 2011-2013, Nazar Mokrynskyi
 * @license		MIT License, see license.txt
 */
namespace	cs;

define('DEBUG',				false);
define('XHTML_TAGS_STYLE',	false);
$Core	= Core::instance();
switch ($l = substr($_SERVER['REQUEST_URI'], 1, 2)) {
	case 'uk':
		define('FIXED_LANGUAGE',	true);
		$Core->set('language', 'Українська');
	break;
	case 'en':
		define('FIXED_LANGUAGE',	true);
		$Core->set('language', 'English');
	break;
	case 'ru':
		define('FIXED_LANGUAGE',	true);
		$Core->set('language', 'Русский');
	break;
}
!defined('FIXED_LANGUAGE') && define('FIXED_LANGUAGE',	false);			//If true - language can't be changed, it can be useful if there are several domains,
																		//every of which must work with fixed language (en.domain.com, ru.domain.com, de.domain.com)
define('CS_ERROR_HANDLER', false);										//Special error handler of CleverStyle CMS