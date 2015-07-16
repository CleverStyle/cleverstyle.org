// Generated by CoffeeScript 1.9.3

/**
 * @package    CleverStyle CMS
 * @subpackage System module
 * @category   modules
 * @author     Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright  Copyright (c) 2015, Nazar Mokrynskyi
 * @license    MIT License, see license.txt
 */

(function() {
  var L;

  L = cs.Language;

  Polymer({
    publish: {
      group: '',
      label: ''
    },
    tooltip_animation: '{animation:true,delay:200}',
    L: L,
    permissions: {},
    users: [],
    found_users: [],
    groups: [],
    ready: function() {
      var $search, $shadowRoot;
      $.when($.getJSON('api/System/admin/permissions/for_item', {
        group: this.group,
        label: this.label
      }), $.getJSON('api/System/admin/groups')).done((function(_this) {
        return function(permissions, groups) {
          var user;
          _this.permissions = permissions[0];
          _this.groups = groups[0];
          if (!Object.keys(_this.permissions.users).length) {
            return;
          }
          return $.getJSON('api/System/admin/users', {
            ids: ((function() {
              var results;
              results = [];
              for (user in this.permissions.users) {
                results.push(user);
              }
              return results;
            }).call(_this)).join(',')
          }, function(users) {
            return _this.users = users;
          });
        };
      })(this));
      $shadowRoot = $(this.shadowRoot);
      $search = $(this.$.search);
      $search.keyup((function(_this) {
        return function(event) {
          var text;
          text = $search.val();
          if (event.which !== 13 || !text) {
            return;
          }
          $shadowRoot.find('cs-table-row.changed').removeClass('changed').appendTo(_this.$.users);
          return $.getJSON('api/System/admin/users', {
            search: text
          }, function(found_users) {
            found_users = found_users.filter(function(user) {
              return !$shadowRoot.find("[name='users[" + user + "]']").length;
            });
            if (!found_users.length) {
              UIkit.notify('404 Not Found', {
                status: 'warning'
              });
              return;
            }
            return $.getJSON('api/System/admin/users', {
              ids: found_users.join(',')
            }, function(users) {
              return _this.found_users = users;
            });
          });
        };
      })(this)).keydown((function(_this) {
        return function(event) {
          return event.which !== 13;
        };
      })(this));
      return $(this.$['search-results']).on('change', ':radio', function() {
        return $(this).closest('cs-table-row').addClass('changed');
      });
    },
    domReady: function() {
      this.workarounds(this.shadowRoot);
      return cs.observe_inserts_on(this.shadowRoot, this.workarounds);
    },
    workarounds: function(target) {
      return $(target).cs().tooltips_inside().cs().radio_buttons_inside().cs().tabs_inside();
    },
    save: function() {
      var default_data, key, value;
      default_data = ((function() {
        var ref, results;
        ref = $.ajaxSettings.data;
        results = [];
        for (key in ref) {
          value = ref[key];
          results.push(key + "=" + value);
        }
        return results;
      })()).join('&');
      return $.ajax({
        url: 'api/System/admin/permissions/for_item',
        data: $(this.$.form).serialize() + '&label=' + this.label + '&group=' + this.group + '&' + default_data,
        type: 'post',
        success: function() {
          return UIkit.notify(L.changes_saved.toString(), {
            status: 'success'
          });
        }
      });
    },
    invert: function(event, detail, sender) {
      return $(sender).closest('div').find(':radio:not(:checked)[value!=-1]').parent().click();
    },
    allow_all: function(event, detail, sender) {
      return $(sender).closest('div').find(':radio[value=1]').parent().click();
    },
    deny_all: function(event, detail, sender) {
      return $(sender).closest('div').find(':radio[value=0]').parent().click();
    }
  });

}).call(this);
