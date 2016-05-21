// Generated by LiveScript 1.4.0
/**
 * @package    CleverStyle Framework
 * @subpackage System module
 * @category   modules
 * @author     Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright  Copyright (c) 2015-2016, Nazar Mokrynskyi
 * @license    MIT License, see license.txt
 */
(function(){
  Polymer({
    'is': 'cs-system-admin-languages',
    behaviors: [cs.Polymer.behaviors.Language('system_admin_languages_'), cs.Polymer.behaviors.admin.System.settings],
    properties: {
      settings_api_url: 'api/System/admin/languages'
    }
  });
}).call(this);
