// Generated by CoffeeScript 1.4.0

/**
 * @package		UIkit Helper
 * @author		Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright	Copyright (c) 2013-2014, Nazar Mokrynskyi
 * @license		MIT License, see license.txt
*/


(function() {
  var __hasProp = {}.hasOwnProperty;

  (function($) {
    var helpers;
    helpers = {
      /**
      		 * Radio buttons with UIkit
      		 *
      		 * Required DOM structure * > label > input:radio, plugin may be applied to any of these elements
      */

      radio: function() {
        var collection;
        if (!this.length) {
          return this;
        }
        collection = [];
        this.each(function() {
          var radio;
          radio = $(this);
          if (!radio.is(':radio')) {
            radio = radio.find(':radio');
          }
          return collection.push(radio.parent().parent().get());
        });
        collection = $($.unique(collection));
        collection.each(function() {
          return $(this).addClass('uk-button-group').attr('data-uk-button-radio', '').children('label').addClass('uk-button').click(function() {
            return $(this).find(':radio').prop('checked', true).change();
          }).find(':radio').change(function() {
            var $this;
            $this = $(this);
            if (!$this.is(':checked')) {
              return;
            }
            return $this.parent().parent().children('.uk-active').removeClass('uk-active').end().end().addClass('uk-active');
          }).filter(':checked').parent().addClass('uk-active');
        });
        return this;
      },
      /**
      		 * Checkboxes with UIkit
      		 *
      		 * Required DOM structure * > label > input:checkbox, plugin may be applied to any of these elements
      */

      checkbox: function() {
        var collection;
        if (!this.length) {
          return this;
        }
        collection = [];
        this.each(function() {
          var checkbox;
          checkbox = $(this);
          if (!checkbox.is(':checkbox')) {
            checkbox = checkbox.find(':checkbox');
          }
          return collection.push(checkbox.parent().parent().get());
        });
        collection = $($.unique(collection));
        collection.each(function() {
          return $(this).addClass('uk-button-group').attr('data-uk-button-checkbox', '').children('label').addClass('uk-button').click(function() {
            return $(this).find(':radio:not(:checked)').prop('checked', true).change();
          }).find(':checkbox').change(function() {
            var $this;
            $this = $(this);
            if (!$this.is(':checked')) {
              return;
            }
            return $this.parent().parent().children('.uk-active').removeClass('uk-active').end().end().addClass('uk-active');
          }).filter(':checked').parent().addClass('uk-active');
        });
        return this;
      },
      /**
      		 * Tabs with UIkit
      		 *
      		 * Required DOM structure *+*, where first element contains list of tabs, and second element content of each tab, plugin must be applied to the first element
      */

      tabs: function() {
        var UI;
        if (!this.length) {
          return this;
        }
        UI = $.UIkit;
        return this.each(function() {
          var $this, content;
          $this = $(this);
          content = $this.next();
          $this.addClass('uk-tab').attr('data-uk-tab', '').children().each(function() {
            var li;
            li = $(this);
            if (!li.children('a').length) {
              return li.wrapInner('<a />');
            }
          }).first().addClass('uk-active');
          $this.data('tab', new UI.tab($this, {
            connect: content
          }));
          content.addClass('uk-switcher uk-margin').children(':first').addClass('uk-active');
          return content.data('switcher', new UI.switcher(content));
        });
      },
      /**
      		 * Tooltip with UIkit
      		 *
      		 * Required title or data-title attribute with some content, optionally support data-pos attribute with desired position of tooltip
      */

      tooltip: function() {
        if (!this.length) {
          return this;
        }
        return this.each(function() {
          var $this, pos;
          $this = $(this);
          if (!$this.attr('title')) {
            $this.attr('title', $this.data('title')).attr('data-title', '');
          }
          pos = $this.data('pos');
          return $this.attr('data-uk-tooltip', cs.json_encode({
            pos: pos ? pos : 'top',
            animation: true,
            delay: 200
          }));
        });
      },
      /**
      		 * Dialog with UIkit
      		 *
      		 * Required DOM structure * > *, plugin must be applied to the root element
      		 * If child element is not present - content will be automatically wrapped with <div>
      */

      modal: function(mode) {
        var UI;
        if (!this.length) {
          return this;
        }
        UI = $.UIkit;
        mode = mode || 'init';
        return this.each(function() {
          var $this, content, modal;
          $this = $(this);
          if (!$this.data('modal')) {
            content = $this.children();
            if (!content.length) {
              content = $this.wrapInner('<div />').children();
            }
            content.addClass('uk-modal-dialog uk-modal-dialog-slide');
            if ($this.data('modal-frameless')) {
              content.addClass('uk-modal-dialog-frameless');
            }
            if ($this.attr('title')) {
              $('<h3 />').html($this.attr('title')).prependTo(content);
            }
            if (content.attr('title')) {
              $('<h3 />').html(content.attr('title')).prependTo(content);
            }
            $this.addClass('uk-modal').data('modal', new UI.modal.Modal($this));
          }
          modal = $this.data('modal');
          switch (mode) {
            case 'show':
              return modal.show();
            case 'hide':
              return modal.hide();
          }
        });
      }
    };
    /**
    	 * cs helper registration or running (if no parameters specified)
    	 *
    	 * @param {string}		name
    	 * @param {function}	helper
    */

    $.fn.cs = function(name, helper) {
      var key, method, public_helpers, this_;
      if (name && helper) {
        helpers[name] = helper;
        return this;
      }
      public_helpers = {};
      this_ = this;
      for (key in helpers) {
        if (!__hasProp.call(helpers, key)) continue;
        method = helpers[key];
        public_helpers[key] = (function(method) {
          return function() {
            return method.apply(this_, arguments);
          };
        })(method);
      }
      return public_helpers;
    };
    return $.cs = {
      /**
      		 * Simple wrapper around $(...).cs().modal() with inner form
      		 *
      		 * All content will be inserted into modal form, optionally it is possible to add close button and set width
      		 *
      		 * @return jQuery Root modal element, it is possible to use .cs().modal() on it and listen for events
      */

      simple_modal: function(content, close, width) {
        var style;
        if (close == null) {
          close = false;
        }
        style = width ? ' style="width:' + width + 'px; margin-left:-' + (width / 2) + 'px"' : '';
        close = close ? "<a class=\"uk-modal-close uk-close\"></a>" : '';
        return $("<div>\n	<div class=\"uk-form\"" + style + ">\n		" + close + "\n		" + content + "\n	</div>\n</div>").appendTo('body').cs().modal('show').on('uk.modal.hide', function() {
          return $(this).remove();
        });
      }
    };
  })(jQuery);

}).call(this);
