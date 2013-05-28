<?php
if(preg_match('/msie/i',$_SERVER['HTTP_USER_AGENT'])) {
	global $Page;
	$Page->Head	.= '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">';
}