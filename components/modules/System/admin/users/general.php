<?php
/**
 * @package		CleverStyle CMS
 * @subpackage	System module
 * @category	modules
 * @author		Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright	Copyright (c) 2011-2013, Nazar Mokrynskyi
 * @license		MIT License, see license.txt
 */
namespace	cs\modules\System;
use			h,
			cs\Config,
			cs\Index,
			cs\Language;
$Config	= Config::instance();
$L		= Language::instance();
Index::instance()->content(
	h::{'table.cs-table-borderless.cs-left-even.cs-right-odd tr| td'}(
		core_input('session_expire', 'number', null, false, 1, false, $L->seconds),
		core_input('online_time', 'number', null, false, 1, false, $L->seconds),
		[
			h::info('sign_in_attempts_block_count'),
			h::{'input[type=number]'}([
				'name'		=> 'core[sign_in_attempts_block_count]',
				'value'		=> $Config->core['sign_in_attempts_block_count'],
				'min'		=> 0,
				'onClick'	=> "if ($(this).val() == 0) { $('.cs-sign-in-attempts-block-count').hide(); } else { $('.cs-sign-in-attempts-block-count').show(); }",
				'onChange'	=> "if ($(this).val() == 0) { $('.cs-sign-in-attempts-block-count').hide(); } else { $('.cs-sign-in-attempts-block-count').show(); }"
			])
		],
		[
			core_input('sign_in_attempts_block_time', 'number', null, false, 1, false, $L->seconds),
			[
				'style'	=> $Config->core['sign_in_attempts_block_count'] == 0 ? 'display: none;' : '',
				'class'	=> 'cs-sign-in-attempts-block-count'
			]
		],
		core_input('remember_user_ip', 'radio'),
		core_input('password_min_length', 'number', null, false, 4),
		core_input('password_min_strength', 'range', null, false, 0, 7),
		[
			h::info('allow_user_registration'),
			h::{'input[type=radio]'}([
				'name'		=> 'core[allow_user_registration]',
				'checked'	=> $Config->core['allow_user_registration'],
				'value'		=> [0, 1],
				'in'		=> [$L->off, $L->on],
				'onClick'	=> [
					"$('.cs-allow-user-registration').hide();",
					"$('.cs-allow-user-registration').show();".
						"if (!$('.cs-allow-user-registration input[value=1]').prop('checked')) { $('.cs-require-registration-confirmation').hide(); }"
				]
			])
		],
		[
			[
				h::info('require_registration_confirmation'),
				h::{'input[type=radio]'}([
					'name'			=> 'core[require_registration_confirmation]',
					'checked'		=> $Config->core['require_registration_confirmation'],
					'value'			=> [0, 1],
					'in'			=> [$L->off, $L->on],
					'onClick'		=> [
						"$('.cs-require-registration-confirmation').hide();",
						"$('.cs-require-registration-confirmation').show();"
					]
				])
			],
			[
				'style'	=> $Config->core['allow_user_registration'] == 0 ? 'display: none;' : '',
				'class'	=> 'cs-allow-user-registration'
			]
		],
		[
			core_input('registration_confirmation_time', 'number', null, false, 1, false, $L->days),
			[
				'style'	=>	$Config->core['allow_user_registration'] == 1 && $Config->core['require_registration_confirmation'] == 1 ? '' : 'display: none;',
				'class'	=> 'cs-allow-user-registration cs-require-registration-confirmation'
			]
		],
		[
			core_input('auto_sign_in_after_registration', 'radio'),
			[
				'style'	=>	$Config->core['allow_user_registration'] == 1 && $Config->core['require_registration_confirmation'] == 1 ? '' : 'display: none;',
				'class'	=> 'cs-allow-user-registration cs-require-registration-confirmation'
			]
		],
		core_textarea('rules', 'SIMPLE_EDITOR')
	)
);