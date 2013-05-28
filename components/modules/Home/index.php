<?php
/**
 * @package		Home
 * @category	modules
 * @author		Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright	Copyright (c) 2011-2013, Nazar Mokrynskyi
 * @license		MIT License, see license.txt
 */
global $Index, $L, $Page, $Config;
$base_url	= $Config->base_url();
if (!preg_match('/\/(en|ru|uk)$/', $base_url)) {
	$base_url	.= '/'.$L->clang;
}
$Page->canonical_url($base_url);
$Index->content(file_get_contents(__DIR__.'/index_'.$L->clang.'.html'));