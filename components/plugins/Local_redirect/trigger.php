<?php
/**
 * @package        Local_redirect
 * @category       plugins
 * @author         Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright      Copyright (c) 2011-2013, Nazar Mokrynskyi
 * @license        MIT License, see license.txt
 */
namespace	cs;
use			h;
Trigger::instance()->register(
	'System/User/construct/after',
	function () {
		$Config					= Config::instance();
		if (MODULE == 'System' || !in_array('Local_redirect', $Config->components['plugins'])) {
			return;
		}
		$relative_address		= $Config->server['relative_address'];
		if (!in_array(substr($_SERVER['REQUEST_URI'], 1, 2), ['en', 'ru', 'uk'])) {
			if (!HOME) {
				header('Location: /'.Language::instance()->clang."/$relative_address", true, 301);
			} else {
				header('Location: /'.Language::instance()->clang, true, 301);
			}
		}
		$base_url				= substr($Config->base_url(), 0, -3);
		Page::instance()->Head	.= h::{'link[rel=alternate]'}([
			'hreflang'	=> 'x-default',
			'href'		=> !HOME ? "$base_url/$relative_address" : "$base_url"
		]).
		h::{'link[rel=alternate]|'}(array_map(
				function ($lang) use ($base_url, $relative_address) {
					return [
						'hreflang'	=> $lang,
						'href'		=> "$base_url/$lang/$relative_address"
					];
				},
				['en', 'ru', 'uk']
			)
		);
	}
);