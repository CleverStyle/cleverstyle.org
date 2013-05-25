<?php
/**
 * @package		TopLogo
 * @category	block
 * @author		Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright	Copyright (c) 2011-2013, Nazar Mokrynskyi
 * @license		MIT License, see license.txt
 */
if (!HOME && !ADMIN) {
	echo h::{'h1 a[href=http://cleverstyle.org][style=border-bottom:none;]'}('CleverStyle');
}