<?php
/**
 * @package		CleverStyle CMS
 * @subpackage	System module
 * @category	modules
 * @author		Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright	Copyright (c) 2011-2013, Nazar Mokrynskyi
 * @license		MIT License, see license.txt
 */
namespace	cs;
$User	= User::instance();
if ($User->guest()) {
	define('ERROR_CODE', 403);
	return;
}
if (isset($_POST['logout'])) {
	$User->del_session();
	_setcookie('logout', '1', 0, true, true);
	Page::instance()->json(1);
}