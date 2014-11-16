<?php
/**
 * @package        CleverStyle CMS
 * @subpackage     System module
 * @category       modules
 * @author         Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright      Copyright (c) 2014, Nazar Mokrynskyi
 * @license        MIT License, see license.txt
 */
namespace cs;

$Config = Config::instance();
$core   = &$Config->core;
unset(
	$core['auto_translation'],
	$core['auto_translation_engine']
);
unset(
	$core['show_footer_info'],
	$core['footer_text']
);
Text::instance()->del($Config->module('System')->db('texts'), 'System/Config/core', 'footer_text');
file_put_contents(
	DIR.'/config/main.php',
	str_replace(
		"/**
* If true - language can't be changed, it can be useful if there are several domains,
* every of which must work with fixed language (en.domain.com, ru.domain.com, de.domain.com)
*
* Usually, system care about this automatically and there is no need to change this
*/
//define('FIXED_LANGUAGE',	false);
",
		'',
		file_get_contents(DIR.'/config/main.php')
	)
);
unset($core['og_support']);
unset(
	$core['show_db_queries'],
	$core['show_cookies']
);
unset(
	$core['color_schemes'],
	$core['color_scheme']
);
$Config->save();
