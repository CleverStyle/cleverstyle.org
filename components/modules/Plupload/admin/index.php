<?php
/**
 * @package		Plupload
 * @category	modules
 * @author		Moxiecode Systems AB
 * @author		Nazar Mokrynskyi <nazar@mokrynskyi.com> (integration with CleverStyle CMS)
 * @copyright	Moxiecode Systems AB
 * @license		GNU GPL v2, see license.txt
 */
namespace	cs;
use			h;
$Index					= Index::instance();
$Index->apply_button	= false;
$Config					= Config::instance();
$L						= Language::instance();
$Index->content(
	h::{'table.cs-table-borderless.cs-left-even.cs-right-odd tr| td'}(
		[
			$L->upload_limit.' (b, kb, mb, gb)',
			h::{'input[name=max_file_size]'}([
				'value'	=> $Config->module('Plupload')->max_file_size
			])
		],
		[
			h::info('plupload_confirmation_time'),
			h::{'input[name=confirmation_time]'}([
				'value'	=> $Config->module('Plupload')->confirmation_time
			]).
			$L->seconds
		]
	)
);
