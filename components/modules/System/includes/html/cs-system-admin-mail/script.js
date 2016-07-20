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
  var L;
  L = cs.Language('system_admin_mail_');
  Polymer({
    'is': 'cs-system-admin-mail',
    behaviors: [cs.Polymer.behaviors.Language('system_admin_mail_'), cs.Polymer.behaviors.admin.System.settings],
    properties: {
      smtp: {
        computed: '_smtp(settings.smtp)',
        type: Boolean
      },
      smtp_auth: {
        computed: '_smtp_auth(settings.smtp, settings.smtp_auth)',
        type: Boolean
      },
      settings_api_url: 'api/System/admin/mail'
    },
    _smtp: function(smtp){
      return smtp == 1;
    },
    _smtp_auth: function(smtp, smtp_auth){
      return smtp == 1 && smtp_auth == 1;
    },
    _test_email: function(){
      var email;
      email = prompt('Email');
      if (email) {
        cs.api('send_test_email api/System/admin/mail', {
          email: email
        }).then(function(){
          cs.ui.simple_modal("<p class=\"cs-text-center cs-block-success cs-text-success\">" + L.done + "</p>");
        })['catch'](function(o){
          clearTimeout(o.timeout);
          cs.ui.simple_modal("<p class=\"cs-text-center cs-block-error cs-text-error\">" + L.test_email_sending_failed + "</p>");
        });
      }
    }
  });
}).call(this);
