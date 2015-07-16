<?php
/**
 * @package		Languages
 * @category	blocks
 * @author		Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright	Copyright (c) 2011-2013, Nazar Mokrynskyi
 * @license		MIT License, see license.txt
 */
namespace	cs;
$address	= current_module() == 'Home' ? '' : '/'.Route::instance()->relative_address;
?>
<nav>
	<a href="/en<?=$address?>" hreflang="en">English</a>
	<a href="/ru<?=$address?>" hreflang="ru">Русский</a>
	<a href="/uk<?=$address?>" hreflang="uk">Українська</a>
</nav>
