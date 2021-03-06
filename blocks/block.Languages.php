<?php
/**
 * @package   Languages
 * @category  blocks
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2011-2013, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
 */
namespace cs;
$Request = Request::instance();
$address = $Request->current_module == 'Home' ? '' : '/'.$Request->path_normalized;
/**
 * @var array $block
 */
?>
<div class="cs-side-block">
	<h3><?=$block['title']?></h3>
	<nav>
		<a href="/en<?=$address?>" hreflang="en">English</a>
		<a href="/ru<?=$address?>" hreflang="ru">Русский</a>
		<a href="/uk<?=$address?>" hreflang="uk">Українська</a>
	</nav>
</div>
