<?php
/**
 * @package		Profile
 * @category	blocks
 * @author		Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright	Copyright (c) 2014, Nazar Mokrynskyi
 * @license		MIT License, see license.txt
 */
namespace	cs;
use			h;
include_once THEMES.'/CleverStyle/functions.php';
$content	= uniqid('Profile_block_');
echo $content;
Trigger::instance()->register(
	'System/Page/display',
	function () use ($content) {
		$Page		= Page::instance();
		$Page->Html	= str_replace(
			$content,
			h::{'div.uk-text-center.uk-form.cs-side-bar-profile'}(
				h::{'img.uk-thumbnail'}([
					'src'	=> User::instance()->avatar(128)
				]).
				\cs\themes\CleverStyle\get_header_info()
			),
			$Page->Html
		);
	}
);
