<?php
/**
 * @package        Metrics
 * @category       plugins
 * @author         Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright      Copyright (c) 2011-2013, Nazar Mokrynskyi
 * @license        MIT License, see license.txt
 */
global $Core;
$Core->register_trigger(
	'System/Page/pre_display',
	function () {
		global $Page, $Config, $User;
		if ($User->admin() || !in_array('Metrics', $Config->components['plugins'])) {
			return;
		}
		$Page->post_Body	.= '<!-- Yandex.Metrika --><script>window.yandex_metrika_callback = function() {window.yaCounter1250519 = new Ya.Metrika(1250519);};(function(){var i="DOMContentLoaded",g="onreadystatechange",c="doScroll",f="addEventListener",o="attachEvent",d="load",l=false,n=document,e=window,m=n.documentElement,h=l,j;function k(){if(!h){h=!h;j=n.createElement("script");j.type="text/javascript";j.src=((document.location.protocol=="https:")?"https:":"http:")+"//mc.yandex.ru/metrika/watch.js";j.setAttribute("async","true");m.firstChild.appendChild(j)}}if(n[f]){function b(){n.removeEventListener(i,b,l);k()}n[f](i,b,l);n[f](d,k,l)}else{if(n[o]){n[o](g,a);function a(){if(h){return}try{m[c]("left")}catch(p){setTimeout(a,0);return}k()}if(m[c]&&e==e.top){a()}n[o]("on"+d,k)}}})();</script><noscript><img src="//mc.yandex.ru/watch/1250519" style="position:absolute" alt="" /></noscript><!-- /Yandex.Metrika -->';
	}
);