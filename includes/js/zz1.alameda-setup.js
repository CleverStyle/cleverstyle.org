// Generated by LiveScript 1.4.0
/**
 * @package   CleverStyle Framework
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2016, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
 */
(function(){
  requirejs.config({
    baseUrl: '/',
    paths: {
      jssha: 'includes/js/modules/jsSHA-2.1.0',
      autosize: 'includes/js/modules/autosize.min',
      html5sortable: 'includes/js/modules/html5sortable.min.0.2.8'
    },
    waitSeconds: 60
  });
  if (window.$) {
    define('jquery', function(){
      return $;
    });
  } else {
    requirejs.config({
      paths: {
        jquery: cs.optimized_includes[0].shift()
      }
    });
  }
  define('sprintf-js', function(){
    return {
      sprintf: sprintf,
      vsprintf: vsprintf
    };
  });
}).call(this);
