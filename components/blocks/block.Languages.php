<?php
/**
 * @package		Languages
 * @category	block
 * @author		Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright	Copyright (c) 2011-2013, Nazar Mokrynskyi
 * @license		MIT License, see license.txt
 */
namespace	cs;
$Config	= Config::instance();
?>
<nav class="cs-languages">
	<a href="<?='/en'.(MODULE == 'Home' ? '' : '/'.$Config->server['relative_address'])?>" hreflang="en">English</a>
	<a href="<?='/ru'.(MODULE == 'Home' ? '' : '/'.$Config->server['relative_address'])?>" hreflang="ru">Русский</a>
	<a href="<?='/uk'.(MODULE == 'Home' ? '' : '/'.$Config->server['relative_address'])?>" hreflang="uk">Українська</a>
</nav>
