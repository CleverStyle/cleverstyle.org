/**
 * @package    CleverStyle Framework
 * @subpackage System module
 * @category   modules
 * @author     Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright  Copyright (c) 2015-2016, Nazar Mokrynskyi
 * @license    MIT License, see license.txt
 */
Polymer(
	'is'		: 'cs-system-admin-site-info'
	behaviors	: [
		cs.Polymer.behaviors.Language('system_admin_site_info_')
		cs.Polymer.behaviors.admin.System.settings
	]
	properties	:
		settings_api_url	: 'api/System/admin/site_info'
		timezones			: Array
	ready : !->
		cs.api('get api/System/timezones').then (timezones) !~>
			@timezones	=
				for description, timezone of timezones
					{timezone, description}
)
