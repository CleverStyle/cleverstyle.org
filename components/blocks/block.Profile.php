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
$content	= uniqid('Profile_block_');
echo $content;
Trigger::instance()->register(
	'System/Page/display',
	function () use ($content) {
		$Page		= Page::instance();
		$Page->Html	= str_replace(
			$content,
			h::{'div.uk-text-center.uk-form'}(
				h::{'img.uk-thumbnail'}([
					'src'	=> $Page->user_avatar_image
				]).
				$Page->header_info
			),
			$Page->Html
		);
	}
);
