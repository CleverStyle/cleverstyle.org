<?php
/**
 * @package		Home
 * @category	modules
 * @author		Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright	Copyright (c) 2011-2013, Nazar Mokrynskyi
 * @license		MIT License, see license.txt
 */
namespace	cs;
$L			= Language::instance();
$base_url	= Config::instance()->base_url();
if (!preg_match('/\/(en|ru|uk)$/', $base_url)) {
	$base_url	.= '/'.$L->clang;
}
Page::instance()->canonical_url($base_url);
Index::instance()->content(file_get_contents(__DIR__.'/index_'.$L->clang.'.html'));