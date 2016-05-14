// Generated by CoffeeScript 1.10.0

/**
 * @package   Blogs
 * @category  modules
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2015-2016, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
 */

(function() {
  Polymer({
    'is': 'cs-blogs-posts-post',
    'extends': 'article',
    behaviors: [cs.Polymer.behaviors.Language('blogs_')],
    properties: {
      post: {},
      comments_enabled: false
    },
    ready: function() {
      return this.$.short_content.innerHTML = this.post.short_content;
    },
    sections_path: function(index) {
      return this.post.sections_paths[index];
    }
  });

}).call(this);
