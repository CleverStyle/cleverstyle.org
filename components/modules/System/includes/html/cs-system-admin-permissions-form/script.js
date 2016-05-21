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
    'is': 'cs-system-admin-permissions-form',
    behaviors: [cs.Polymer.behaviors.Language('system_admin_permissions_')],
    properties: {
      permission_id: Number,
      group: '',
      label: ''
    },
    ready: function(){
      var this$ = this;
      if (this.permission_id) {
        $.getJSON('api/System/admin/permissions/' + this.permission_id, function(arg$){
          this$.group = arg$.group, this$.label = arg$.label;
        });
      }
    },
    save: function(){
      var this$ = this;
      $.ajax({
        url: 'api/System/admin/permissions' + (this.permission_id ? '/' + this.permission_id : ''),
        type: this.permission_id ? 'put' : 'post',
        data: {
          group: this.group,
          label: this.label
        },
        success: function(){
          cs.ui.notify(this$.L.changes_saved, 'success', 5);
        }
      });
    }
  });
}).call(this);
