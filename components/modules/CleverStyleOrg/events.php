<?php
/**
 * @package   CleverStyleOrg
 * @category  modules
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2016, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
 */
namespace cs;

Event::instance()->on(
	'System/Request/routing_replace',
	function ($data) {
		if (in_array(strtolower($data['rc']), ['cms', 'framework'])) {
			Response::instance()->redirect('https://github.com/nazar-pc/CleverStyle-Framework', 302);
			throw new ExitException;
		}
	}
);
