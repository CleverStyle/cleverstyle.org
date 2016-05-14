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
  L = cs.Language('system_admin_users_');
  Polymer({
    'is': 'cs-system-admin-users-add-user-form',
    behaviors: [cs.Polymer.behaviors.Language('system_admin_users_')],
    properties: {
      email: ''
    },
    save: function(){
      $.ajax({
        url: 'api/System/admin/users',
        type: 'post',
        data: {
          email: this.email,
          type: 'user'
        },
        success: function(result){
          cs.ui.alert("<p class=\"cs-block-success cs-text-success\">" + L.user_was_added(result.login, result.password) + "</p>");
        }
      });
    }
  });
}).call(this);
