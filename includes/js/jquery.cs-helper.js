// Generated by CoffeeScript 1.10.0

/**
 * @package		UIkit Helper
 * @author		Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright	Copyright (c) 2013-2015, Nazar Mokrynskyi
 * @license		MIT License, see license.txt
 */

(function() {
  (function($, UI) {
    var helpers;
    helpers = {

      /**
      		 * Tabs with UIkit
      		 *
      		 * Required DOM structure *+*, where first element contains list of tabs, and second element content of each tab, plugin must be applied to the first element
       */
      tabs: function() {
        if (!this.length) {
          return this;
        }
        return this.each(function() {
          var $this, content;
          $this = $(this);
          content = $this.next();
          $this.addClass('uk-tab').attr('data-uk-tab', '').children(':not(template)').each(function() {
            var li;
            li = $(this);
            if (!li.children('a').length) {
              return li.wrapInner('<a />');
            }
          }).first().addClass('uk-active');
          $this.data('tab', UI.tab($this, {
            connect: content,
            animation: 'fade'
          }));
          return content.addClass('uk-switcher uk-margin').children(':not(template):first').addClass('uk-active');
        });
      },

      /**
      		 * Dialog with UIkit
      		 *
      		 * Required DOM structure * > *, plugin must be applied to the root element
      		 * If child element is not present - content will be automatically wrapped with <div>
       */
      modal: function(mode) {
        if (!this.length) {
          return this;
        }
        mode = mode || 'init';
        return this.map(function() {
          var $this, content, modal;
          $this = $(this);
          if ($this.hasClass('uk-modal-dialog')) {
            $this = $this.wrap('<div/>').parent();
          }
          if (!$this.data('modal')) {
            content = $this.children();
            if (!content.length) {
              content = $this.wrapInner('<div/>').children();
            }
            content.addClass('uk-modal-dialog');
            if ($this.is('[data-modal-frameless]')) {
              content.addClass('uk-modal-dialog-frameless');
            }
            if ($this.attr('title')) {
              $('<h3/>').html($this.attr('title')).prependTo(content);
            }
            if (content.attr('title')) {
              $('<h3/>').html(content.attr('title')).prependTo(content);
            }
            $this.addClass('uk-modal').data('modal', UI.modal($this));
          }
          modal = $this.data('modal');
          switch (mode) {
            case 'show':
              modal.show();
              break;
            case 'hide':
              modal.hide();
          }
          return $this.get();
        });
      },

      /**
      		 * Enabling tooltips inside ShadowDOM, should be called on element.shadowRoot
       */
      tooltips_inside: function() {
        this.find('[data-uk-tooltip]').add(this.filter('[data-uk-tooltip]')).each(function() {
          return UI.tooltip(this, UI.Utils.options($(this).attr('data-uk-tooltip')));
        });
        return this;
      },

      /**
      		 * Enabling dynamic pagination inside ShadowDOM, should be called on element.shadowRoot
       */
      pagination_inside: function() {
        this.find('[data-uk-pagination]').add(this.filter('[data-uk-pagination]')).each(function() {
          return UI.pagination(this, UI.Utils.options($(this).attr('data-uk-pagination')));
        });
        return this;
      },

      /**
      		 * Enabling radio buttons inside ShadowDOM, should be called on element.shadowRoot
       */
      radio_buttons_inside: function() {
        this.find('[data-uk-button-radio]').add(this.filter('[data-uk-button-radio]')).each(function() {
          return UI.buttonRadio(this, UI.Utils.options($(this).attr('data-uk-button-radio')));
        });
        return this;
      },

      /**
      		 * Enabling tabs inside ShadowDOM, should be called on element.shadowRoot
       */
      tabs_inside: function() {
        this.find('[data-uk-tab]').add(this.filter('[data-uk-tab]')).each(function() {
          return UI.tab(this, UI.Utils.options($(this).attr('data-uk-tab')));
        });
        this.find('.cs-tabs:not(.uk-tab)').cs().tabs();
        return this;
      },

      /**
      		 * Connecting form elements in ShadowDOM to form element higher in DOM tree, should be called on element.shadowRoot
       */
      connect_to_parent_form: function() {
        return this.each(function() {
          var $form, element, results;
          if (WebComponents.flags.shadow) {
            return;
          }
          element = this;
          results = [];
          while (true) {
            if (element.tagName === 'FORM') {
              $form = $(element);
              $form.one('submit', (function(_this) {
                return function(e) {
                  e.preventDefault();
                  e.stopImmediatePropagation();
                  $(_this).find('[name]').each(function() {
                    var $this;
                    $this = $(this);
                    if (this.type === 'file') {
                      $this.clone(true, true).insertAfter($this.hide());
                      return $this.appendTo($form);
                    } else {
                      if ((this.type === 'checkbox' || this.type === 'radio') && !$this.is(':checked')) {
                        return;
                      }
                      return $form.append($('<input type="hidden"/>').attr('name', this.name).val($this.val()));
                    }
                  });
                  return $form.submit();
                };
              })(this));
              break;
            }
            element = element.host || element.parentNode;
            if (!element) {
              break;
            } else {
              results.push(void 0);
            }
          }
          return results;
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
      var func, public_helpers;
      if (name && helper) {
        helpers[name] = helper;
        return this;
      }
      public_helpers = {};
      for (name in helpers) {
        func = helpers[name];
        public_helpers[name] = func.bind(this);
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
        style = width ? ' style="width:' + (/^[0-9]+$/.test(width) ? width + 'px;' : width) + '"' : '';
        close = close ? "<a class=\"uk-modal-close uk-close\"></a>" : '';
        return $("<div>\n	<div class=\"uk-form\"" + style + ">\n		" + close + "\n		" + content + "\n	</div>\n</div>").appendTo('body').cs().modal('show').on('hide.uk.modal', function() {
          return $(this).remove();
        });
      }
    };
  })(jQuery, UIkit);

}).call(this);
