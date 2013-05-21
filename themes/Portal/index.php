<!doctype html>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<!--pre_Html--><!--head--><!--pre_Body--><!--debug_info-->
<header>
<!--header-->
	<nav class="cs-header-main-menu">
<!--main-menu-->
	</nav>
	<nav class="cs-header-main-submenu">
<!--main-submenu-->
	</nav>
	<nav class="cs-header-menu-more">
<!--menu-more-->
	</nav>
	<div class="cs-header-user">
		<div class="cs-header-avatar" style="background-image:<!--user_avatar_image-->;"></div>
		<div class="cs-header-user-info">
<!--header_info-->
		</div>
	</div>
</header>
<div id="cs-body">
<?php
global $Page;
if ($Page->Left) {
?>	<aside id="cs-left-blocks">
<!--left_blocks-->
	</aside>
<?php
} else {
	$Page->css(
		'#cs-top-blocks, #cs-bottom-blocks, #cs-main-content {margin-left:0}',
		'code'
	);
}
if ($Page->Right) {
?>
	<aside id="cs-right-blocks">
<!--right_blocks-->
	</aside>
<?php
} else {
	$Page->css(
		'#cs-top-blocks, #cs-bottom-blocks, #cs-main-content {margin-right:0}',
		'code'
	);
}
?>	<aside id="cs-top-blocks">
<!--top_blocks-->
	</aside>
	<div id="cs-main-content">
<!--content-->
	</div>
	<aside id="cs-bottom-blocks">
<!--bottom_blocks-->
	</aside>
</div>
<footer>
<!--footer-->
</footer>
<!--post_Body--><!--post_Html-->