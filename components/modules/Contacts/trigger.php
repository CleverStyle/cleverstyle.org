<?php
/**
 * @package		Contacts
 * @category	modules
 * @author		Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright	Copyright (c) 2011-2013, Nazar Mokrynskyi
 * @license		MIT License, see license.txt
 */
namespace	cs;
Trigger::instance()->register(
	'System/Index/mainmenu',
	function ($data) {
		if ($data['path'] == 'Contacts') {
			$data['path']	= path(Language::instance()->Contacts);
		}
	}
)->register(
	'System/Config/routing_replace',
	function ($data) {
		$rc		= explode('/', $data['rc']);
		if ((!Config::instance()->module('Contacts')->active() && substr($data['rc'], 0, 5) != 'admin') || $rc[0] != Language::instance()->Contacts) {
			return;
		}
		$rc[0]		= 'Contacts';
		$data['rc']	= implode('/', $rc);
	}
);