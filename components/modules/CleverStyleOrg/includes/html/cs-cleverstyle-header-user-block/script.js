// Generated by LiveScript 1.4.0
/**
 * @package   CleverStyleOrg
 * @category  modules
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2016, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
 */
(function(){
  var L;
  L = cs.Language;
  Polymer({
    'is': 'cs-side-user-block',
    behaviors: [cs.Polymer.behaviors.Language('system_profile_')],
    properties: {
      avatar: String,
      guest: Boolean,
      username: String,
      login: String
    },
    ready: function(){
      this.guest = !!cs.is_guest;
    },
    _sign_in: function(){
      cs.ui.simple_modal("<cs-system-sign-in/>");
    },
    _registration: function(){
      cs.ui.simple_modal("<cs-system-registration/>");
    },
    _sign_out: cs.sign_out,
    _change_password: function(){
      cs.ui.simple_modal("<cs-system-change-password/>");
    },
    _general_settings: function(){
      cs.ui.simple_modal("<cs-system-user-settings/>");
    }
  });
}).call(this);
