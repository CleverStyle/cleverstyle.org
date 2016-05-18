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
		if ($data['rc'] == 'cms') {
			Response::instance()->redirect('CMS', 302);
		}
		if (strtolower($data['rc']) == 'framework') {
			Response::instance()->redirect('CMS', 302);
		}
	}
);
