<?php
/**
 * @package        Metrics
 * @category       plugins
 * @author         Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright      Copyright (c) 2011-2013, Nazar Mokrynskyi
 * @license        MIT License, see license.txt
 */
global $Core;
$Core->register_trigger(
	'System/Page/pre_display',
	function () {
		global $Page, $Config;
		if (ADMIN || !in_array('Metrics', $Config->components['plugins'])) {
			return;
		}
		$Page->replace	= file_get_contents(__DIR__.'/counters.html');
	}
);