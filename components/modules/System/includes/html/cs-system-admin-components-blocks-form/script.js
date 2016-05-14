// Generated by CoffeeScript 1.10.0

/**
 * @package    CleverStyle CMS
 * @subpackage System module
 * @category   modules
 * @author     Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright  Copyright (c) 2015, Nazar Mokrynskyi
 * @license    MIT License, see license.txt
 */

(function() {
  var L, get_active_class;

  get_active_class = function(is_active) {
    if (is_active) {
      return 'uk-active';
    } else {
      return '';
    }
  };

  L = cs.Language;

  Polymer({
    tooltip_animation: '{animation:true,delay:200}',
    L: L,
    created: function() {
      var json;
      json = JSON.parse(this.querySelector('script').innerHTML);
      json.block_data.type = json.block_data.type || json.types[0];
      json.block_data.template = json.block_data.template || json.templates[0];
      if (json.block_data.active === void 0) {
        json.block_data.active = 1;
      }
      this.active_yes_class = get_active_class(json.block_data.active);
      this.active_no_class = get_active_class(!json.block_data.active);
      this.expire_never_class = get_active_class(!json.block_data.expire.state);
      this.expire_as_specified_class = get_active_class(json.block_data.expire.state);
      return this.json = json;
    },
    ready: function() {
      return $(this.shadowRoot).find('textarea').val(this.json.block_data.content);
    },
    domReady: function() {
      $(this.shadowRoot).cs().tooltips_inside().cs().radio_buttons_inside().cs().connect_to_parent_form();
      return $(this.shadowRoot.querySelector('.EDITOR')).after('<content select=".editor-container"/>').appendTo(this).wrap('<div class="editor-container"/>').wrap('<div/>');
    },
    type_change: function() {
      var type;
      type = this.shadowRoot.querySelector("[name='block[type]']").value;
      return $(this.shadowRoot).find('.html, .raw_html').prop('hidden', true).filter('.' + type).prop('hidden', false);
    }
  });

}).call(this);
