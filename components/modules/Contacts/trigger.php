<?php
/**
 * @package		Contacts
 * @category	modules
 * @author		Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright	Copyright (c) 2011-2013, Nazar Mokrynskyi
 * @license		MIT License, see license.txt
 */
global $Core;
$Core->register_trigger(
	'System/Index/mainmenu',
	function ($data) {
		global $L;
		if ($data['path'] == 'Contacts') {
			$data['path']	= path($L->Contacts);
		}
	}
);
$Core->register_trigger(
	'System/Config/routing_replace',
	function ($data) {
		global $L, $Config;
		$rc		= explode('/', $data['rc']);
		if ((!$Config->module('Contacts')->active() && substr($data['rc'], 0, 5) != 'admin') || $rc[0] != $L->Contacts) {
			return;
		}
		$rc[0]		= 'Contacts';
		$data['rc']	= implode('/', $rc);
	}
);