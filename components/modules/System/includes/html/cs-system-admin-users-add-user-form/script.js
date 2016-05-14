// Generated by CoffeeScript 1.9.3

/**
 * @package    CleverStyle CMS
 * @subpackage System module
 * @category   modules
 * @author     Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright  Copyright (c) 2015-2016, Nazar Mokrynskyi
 * @license    MIT License, see license.txt
 */

(function() {
  var L;

  L = cs.Language;

  Polymer({
    'is': 'cs-system-admin-users-add-user-form',
    behaviors: [cs.Polymer.behaviors.Language],
    properties: {
      email: ''
    },
    save: function() {
      return $.ajax({
        url: 'api/System/admin/users',
        type: 'post',
        data: {
          email: this.email,
          type: 'user'
        },
        success: function(result) {
          return cs.ui.alert("<p class=\"cs-block-success cs-text-success\">" + (L.user_was_added(result.login, result.password)) + "</p>");
        }
      });
    }
  });

}).call(this);