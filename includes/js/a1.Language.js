// Generated by LiveScript 1.4.0
/**
 * @package   CleverStyle Framework
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2015-2016, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
 */
(function(){
  var translations, Language, slice$ = [].slice;
  translations = cs.Language;
  cs.Language = Language = (function(){
    Language.displayName = 'Language';
    var prototype, i$, ref$, constructor = Language;
    prototype = Language;
    function Language(prefix){
      var prefix_length, key, this$ = this instanceof ctor$ ? this : new ctor$;
      prefix_length = prefix.length;
      for (key in constructor) {
        if (key.indexOf(prefix) === 0) {
          this$[key.substr(prefix_length)] = constructor[key];
        }
      }
      return this$;
    } function ctor$(){} ctor$.prototype = prototype;
    prototype.get = function(key){
      return this[key].toString();
    };
    prototype.format = function(key){
      var args;
      args = slice$.call(arguments, 1);
      return this[key].apply(this, args);
    };
    for (i$ in ref$ = cs.Language) {
      (fn$.call(Language, i$));
    }
    return Language;
    function fn$(key){
      prototype[key] = function(){
        return vsprintf(translations[key], slice$.call(arguments));
      };
      prototype[key].toString = function(){
        return translations[key];
      };
    }
  }());
}).call(this);
