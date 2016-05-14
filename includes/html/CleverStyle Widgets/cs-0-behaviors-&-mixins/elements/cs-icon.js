// Generated by LiveScript 1.4.0
/**
 * @package   CleverStyle Widgets
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2015-2016, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
 */
(function(){
  Polymer.cs.behaviors.csIcon = [
    Polymer.cs.behaviors['this'], Polymer.cs.behaviors.tooltip, {
      hostAttributes: {
        hidden: true
      },
      observers: ['_icon_changed(icon)'],
      properties: {
        icon: {
          reflectToAttribute: true,
          type: String
        },
        flipX: {
          reflectToAttribute: true,
          type: Boolean,
          value: false
        },
        flipY: {
          reflectToAttribute: true,
          type: Boolean,
          value: false
        },
        mono: {
          reflectToAttribute: true,
          type: Boolean,
          value: false
        },
        rotate: {
          reflectToAttribute: true,
          type: Number,
          value: false
        },
        spin: {
          reflectToAttribute: true,
          type: Boolean,
          value: false
        },
        spinStep: {
          reflectToAttribute: true,
          type: Boolean,
          value: false
        },
        multiple_icons: {
          computed: '_multiple_icons(icon, flipX, flipY, mono, rotate, spin, spinStep)',
          type: Array
        },
        single_icon: {
          computed: '_single_icon(icon, flipX, flipY, mono, rotate, spin, spinStep)',
          type: String
        }
      },
      _icon_changed: function(icon){
        if (!icon) {
          this.setAttribute('hidden', '');
        } else {
          this.removeAttribute('hidden');
        }
      },
      _multiple_icons: function(icon, flipX, flipY, mono, rotate, spin, spinStep){
        if (icon.split(' ').length > 1) {
          return this.icon_class(icon, flipX, flipY, mono, rotate, spin, spinStep);
        } else {
          return [];
        }
      },
      _single_icon: function(icon, flipX, flipY, mono, rotate, spin, spinStep){
        if (icon.split(' ').length > 1) {
          return '';
        } else {
          return this.icon_class(icon, flipX, flipY, mono, rotate, spin, spinStep);
        }
      },
      icon_class: function(icon, flipX, flipY, mono, rotate, spin, spinStep){
        var icons, multiple_icons, icons_classes, res$, i$, len$, index, icon_class;
        icons = icon.split(' ');
        multiple_icons = icons.length > 1;
        res$ = [];
        for (i$ = 0, len$ = icons.length; i$ < len$; ++i$) {
          index = i$;
          icon = icons[i$];
          icon_class = ['fa fa-' + icon];
          if (flipX) {
            icon_class.push('fa-flip-horizontal');
          }
          if (flipY) {
            icon_class.push('fa-flip-vertical');
          }
          if (mono) {
            icon_class.push('fa-fw');
          }
          if (rotate) {
            icon_class.push('fa-rotate-' + rotate);
          }
          if (spin) {
            icon_class.push('fa-spin');
          }
          if (spinStep) {
            icon_class.push('fa-pulse');
          }
          if (multiple_icons) {
            icon_class.push(index ? 'fa-stack-1x fa-inverse' : 'fa-stack-2x');
          }
          res$.push(icon_class.join(' '));
        }
        icons_classes = res$;
        if (multiple_icons) {
          return icons_classes;
        } else {
          return icons_classes[0];
        }
      }
    }
  ];
}).call(this);