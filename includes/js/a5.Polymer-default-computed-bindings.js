// Generated by LiveScript 1.4.0
/**
 * @package   CleverStyle Framework
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2016, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
 */
(function(){
  var normalize_bool;
  normalize_bool = function(value){
    return value && value !== '0';
  };
  Polymer.Base._addFeature({
    'if': function(condition, then_, otherwise, prefix, postfix){
      otherwise == null && (otherwise = '');
      prefix == null && (prefix = '');
      postfix == null && (postfix = '');
      return '' + prefix + (condition ? then_ : otherwise) + postfix;
    },
    join: function(array, separator){
      return array.join(separator !== undefined ? separator : ',');
    },
    concat: function(thing, another){
      return Array.prototype.slice.call(arguments).join('');
    },
    and: function(x, y, z){
      return !!Array.prototype.slice.call(arguments).reduce(function(x, y){
        return normalize_bool(x) && normalize_bool(y);
      });
    },
    or: function(x, y, z){
      return !!Array.prototype.slice.call(arguments).reduce(function(x, y){
        return normalize_bool(x) || normalize_bool(y);
      });
    },
    xor: function(x, y, z){
      return Array.prototype.slice.call(arguments).reduce(function(x, y){
        return !normalize_bool(x) !== !normalize_bool(y);
      });
    },
    equal: function(a, b, strict){
      if (strict) {
        return a === b;
      } else {
        return a == b;
      }
    }
  });
}).call(this);
