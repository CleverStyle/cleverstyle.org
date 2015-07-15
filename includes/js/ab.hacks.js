// Generated by CoffeeScript 1.9.3

/**
 * @package		CleverStyle CMS
 * @author		Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright	Copyright (c) 2014-2015, Nazar Mokrynskyi
 * @license		MIT License, see license.txt
 */


/*
  * Fix for jQuery "ready" event, trigger it after "WebComponentsReady" event triggered by Polymer Platform
 */

(function() {
  (function($) {
    var functions, ready, ready_original;
    ready_original = $.fn.ready;
    functions = [];
    ready = false;
    $.fn.ready = function(fn) {
      return functions.push(fn);
    };
    return document.addEventListener('WebComponentsReady', function() {
      if (!ready) {
        ready = true;
        $.fn.ready = ready_original;
        functions.forEach(function(fn) {
          return $(fn);
        });
        return functions = [];
      }
    });
  })(jQuery);

}).call(this);
