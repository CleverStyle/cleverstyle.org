// Generated by CoffeeScript 1.4.0

/**
 * @package		UI automatic helpers
 * @author		Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright	Copyright (c) 2014-2015, Nazar Mokrynskyi
 * @license		MIT License, see license.txt
*/


(function() {

  $(function() {
    var ui_automatic_helpers_update;
    window.no_ui_selector = '.cs-no-ui';
    ui_automatic_helpers_update = function(element) {
      element.filter('.cs-tabs:not(.uk-tab)').cs().tabs();
      element.find('.cs-tabs:not(.uk-tab)').cs().tabs();
      if (element.is(no_ui_selector) || element.closest(no_ui_selector).length) {
        return;
      }
      element.filter('textarea:not(.cs-no-resize, .autosizejs)').autosize();
      return element.find("textarea:not(" + no_ui_selector + ", .cs-no-resize, .autosizejs)").autosize();
    };
    ui_automatic_helpers_update($('body'));
    return (function() {
      var MutationObserver, eventListenerSupported;
      MutationObserver = window.MutationObserver || window.WebKitMutationObserver;
      eventListenerSupported = window.addEventListener;
      if (MutationObserver) {
        return (new MutationObserver(function(mutations) {
          return mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
              return ui_automatic_helpers_update($(mutation.addedNodes));
            }
          });
        })).observe(document.body, {
          childList: true,
          subtree: true
        });
      } else if (eventListenerSupported) {
        return document.body.addEventListener('DOMNodeInserted', function() {
          return ui_automatic_helpers_update($('body'));
        }, false);
      }
    })();
  });

}).call(this);
