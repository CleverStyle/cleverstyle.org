<?php
/**
 * @package		TopLogo
 * @category	block
 * @author		Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright	Copyright (c) 2011-2013, Nazar Mokrynskyi
 * @license		MIT License, see license.txt
 */
if (MODULE != 'Home' && !ADMIN) {
	echo h::{'h1.header-title a[href=http://cleverstyle.org][title=CleverStyle]'}('CleverStyle');
}