// Generated by LiveScript 1.4.0
/**
 * @package   CleverStyle Widgets
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2015-2016, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
 */
(function(){
  var html;
  html = document.documentElement;
  Polymer.cs.behaviors.csNavDropdown = [
    Polymer.cs.behaviors['this'], {
      hostAttributes: {
        role: 'group'
      },
      properties: {
        align: 'left',
        opened: {
          reflectToAttribute: true,
          type: Boolean
        },
        target: Object
      },
      created: function(){
        var this$ = this;
        document.addEventListener('keydown', function(e){
          if (e.keyCode === 27 && this$.opened) {
            this$.opened = false;
          }
        });
        document.addEventListener('click', function(e){
          var i$, ref$, len$, element;
          if (this$.opened) {
            for (i$ = 0, len$ = (ref$ = e.path).length; i$ < len$; ++i$) {
              element = ref$[i$];
              if (element === this$.target) {
                return;
              }
            }
            this$.close();
          }
        });
      },
      attached: function(){
        if (!this.target && this.previousElementSibling.tagName === 'BUTTON') {
          this.target = this.previousElementSibling;
          this.target.action = 'toggle';
          this.target.bind = this;
        }
      },
      toggle: function(){
        if (!this.opened) {
          this.open();
        } else {
          this.close();
        }
      },
      open: function(){
        var target_position;
        if (this.opened || !this.target) {
          return;
        }
        target_position = this.target.getBoundingClientRect();
        if (this.align === 'left') {
          this.style.left = target_position.left + 'px';
        } else {
          this.style.right = (html.clientWidth - target_position.right - scrollX) + 'px';
        }
        this.style.top = target_position.top + target_position.height + 'px';
        this.opened = true;
        this.fire('open');
      },
      close: function(){
        if (this.opened) {
          this.opened = false;
          this.fire('close');
        }
      }
    }
  ];
}).call(this);