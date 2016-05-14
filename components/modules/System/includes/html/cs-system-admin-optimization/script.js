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
    'is': 'cs-system-admin-optimization',
    behaviors: [cs.Polymer.behaviors.Language, cs.Polymer.behaviors.admin.System.settings],
    properties: {
      path_prefix: '',
      settings_api_url: 'api/System/admin/optimization'
    },
    _clean_cache: function(){
      this._clean_cache_common('clean_cache');
    },
    _clean_pcache: function(){
      this._clean_cache_common('clean_pcache');
    },
    _clean_cache_common: function(method){
      var modal;
      modal = cs.ui.simple_modal("<progress is=\"cs-progress\" infinite></progress>");
      $.ajax({
        url: this.settings_api_url,
        type: method,
        data: {
          path_prefix: this.path_prefix
        },
        success: function(){
          modal.innerHTML = "<p class=\"cs-block-success cs-text-success\">" + L.done + "</p>";
        },
        error: function(){
          modal.innerHTML = "<p class=\"cs-block-error cs-text-error\">" + L.error + "</p>";
        }
      });
    }
  });
}).call(this);
