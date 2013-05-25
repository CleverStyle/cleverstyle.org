<?php
/**
 * @package		Contacts
 * @category	modules
 * @author		Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright	Copyright (c) 2011-2013, Nazar Mokrynskyi
 * @license		MIT License, see license.txt
 */
global $Index, $L;
$Index->content(file_get_contents(__DIR__.'/index_'.$L->clang.'.html'));