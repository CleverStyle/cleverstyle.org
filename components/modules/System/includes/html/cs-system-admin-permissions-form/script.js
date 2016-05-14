// Generated by LiveScript 1.4.0
/**
 * @package    CleverStyle CMS
 * @subpackage System module
 * @category   modules
 * @author     Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright  Copyright (c) 2015-2016, Nazar Mokrynskyi
 * @license    MIT License, see license.txt
 */
(function(){
  var L;
  L = cs.Language;
  Polymer({
    'is': 'cs-system-admin-permissions-form',
    behaviors: [cs.Polymer.behaviors.Language('system_admin_permissions_')],
    properties: {
      permission_id: Number,
      group: '',
      label: ''
    },
    save: function(){
      $.ajax({
        url: 'api/System/admin/permissions' + (this.permission_id ? '/' + this.permission_id : ''),
        type: this.permission_id ? 'put' : 'post',
        data: {
          id: this.permission_id,
          group: this.group,
          label: this.label
        },
        success: function(){
          cs.ui.notify(L.changes_saved, 'success', 5);
        }
      });
    }
  });
}).call(this);
