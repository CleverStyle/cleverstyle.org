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

$User = User::instance();
echo h::cs_side_user_block(
	[
		'avatar'   => $User->avatar(),
		'username' => $User->username(),
		'guest'    => $User->guest()
	]
);
