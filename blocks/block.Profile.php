<?php
/**
 * @package   Profile
 * @category  blocks
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2014-2016, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
 */
namespace cs;
use
	h;

/**
 * @var array $block
 */
$User = User::instance();
echo h::{'div.cs-side-block'}(
	h::h3($block['title']).
	h::{'div cs-side-user-block'}(
		[
			'avatar'   => $User->avatar(),
			'username' => $User->username(),
			'guest'    => $User->guest()
		]
	)
);
