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
    'is': 'cs-system-admin-blocks-form',
    behaviors: [cs.Polymer.behaviors.Language('system_admin_blocks_')],
    properties: {
      block: Object,
      index: Number,
      templates: Array,
      types: Array
    },
    observers: ['_type_change(block.type)'],
    ready: function(){
      var this$ = this;
      Promise.all([
        $.ajax({
          url: 'api/System/admin/blocks',
          type: 'types'
        }), $.ajax({
          url: 'api/System/admin/blocks',
          type: 'templates'
        })
      ]).then(function(arg$){
        this$.types = arg$[0], this$.templates = arg$[1];
      });
      if (this.index) {
        $.getJSON('api/System/admin/blocks/' + this.index, function(block){
          block.type = block.type || this$.types[0];
          block.template = block.template || this$.templates[0];
          if (block.active === void 8) {
            block.active = 1;
          } else {
            block.active = parseInt(block.active);
          }
          this$.block = block;
        });
      } else {
        this.block = {
          active: 1,
          content: '',
          type: 'html',
          expire: {
            state: 0
          }
        };
      }
    },
    _type_change: function(type){
      $(this.shadowRoot).find('.html, .raw_html').prop('hidden', true).filter('.' + type).prop('hidden', false);
    },
    _save: function(){
      var index, this$ = this;
      index = this.index;
      $.ajax({
        url: 'api/System/admin/blocks' + (index ? "/" + index : ''),
        type: index ? 'put' : 'post',
        data: this.block,
        success: function(){
          cs.ui.notify(this$.L.changes_saved, 'success', 5);
        }
      });
    }
  });
}).call(this);
