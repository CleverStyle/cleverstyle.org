// Generated by LiveScript 1.4.0
/**
 * @package   CleverStyle CMS
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2014-2016, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
 */
(function(){
  var url_lang, new_url;
  window.WebComponents = window.WebComponents || {};
  window.Polymer = {
    dom: 'shadow',
    lazyRegister: true
  };
  Array.prototype.forEach.call(document.head.querySelectorAll('.cs-config'), function(config){
    var target, data, destination;
    target = config.getAttribute('target').split('.');
    data = JSON.parse(config.innerHTML);
    destination = window;
    target.forEach(function(target_part, i){
      var index, ref$, value;
      if (target_part !== 'window') {
        if (!destination[target_part]) {
          destination[target_part] = {};
        }
        if (i < target.length - 1) {
          destination = destination[target_part];
        } else {
          if (data instanceof Object && !(data instanceof Array)) {
            destination = destination[target_part];
            for (index in ref$ = data) {
              value = ref$[index];
              destination[index] = value;
            }
          } else {
            destination[target_part] = data;
          }
        }
      }
    });
  });
  if (document.URL.indexOf(document.baseURI.substr(0, document.baseURI.length - 1)) !== 0) {
    url_lang = document.baseURI.split('/')[3];
    new_url = location.href.split('/');
    new_url.splice(3, !new_url[3] ? 1 : 0, url_lang);
    new_url = new_url.join('/');
    history.replaceState({}, document.title, new_url);
  }
}).call(this);
