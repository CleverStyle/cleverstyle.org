<?php
namespace	cs;
$Config	= Config::instance();
$Config->core['sign_in_attempts_block_count']	= $Config->core['login_attempts_block_count'];
unset($Config->core['login_attempts_block_count']);
$Config->core['sign_in_attempts_block_time']	= $Config->core['login_attempts_block_time'];
unset($Config->core['login_attempts_block_time']);
$Config->core['auto_sign_in_after_registration']	= $Config->core['autologin_after_registration'];
unset($Config->core['autologin_after_registration']);
$Config	= Config::instance();
unset(
	$Config->core['active_themes'],
	$Config->core['allow_change_theme']
);
$Config->save();