// Generated by CoffeeScript 1.10.0

/**
 * @package		CleverStyle CMS
 * @author		Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright	Copyright (c) 2011-2015, Nazar Mokrynskyi
 * @license		MIT License, see license.txt
 */

(function() {
  $(function() {
    return requestAnimationFrame(function() {
      if (!cs.in_admin) {
        return;
      }
      $('.cs-reload-button').click(function() {
        return location.reload();
      });
      $('#change_active_languages').change(function() {
        return $(this).find("option[value='" + $('#change_language').val() + "']").prop('selected', true);
      });
      $('.cs-permissions-invert').click(function() {
        return $(this).parentsUntil('div').find(':radio:not(:checked)[value!=-1]').prop('checked', true).change();
      });
      $('.cs-permissions-allow-all').click(function() {
        return $(this).parentsUntil('div').find(':radio[value=1]').prop('checked', true).change();
      });
      $('.cs-permissions-deny-all').click(function() {
        return $(this).parentsUntil('div').find(':radio[value=0]').prop('checked', true).change();
      });
      $('.cs-blocks-permissions').click(function() {
        var $block, index, title;
        $block = $(this).closest('[data-index]');
        index = $block.data('index');
        title = cs.Language.permissions_for_block($block.data('block-title'));
        return $.cs.simple_modal("<h2>" + title + "</h2>\n<cs-system-admin-permissions-for-item label=\"" + index + "\" group=\"Block\"/>");
      });
      $('#cs-top-blocks-items, #cs-left-blocks-items, #cs-floating-blocks-items, #cs-right-blocks-items, #cs-bottom-blocks-items').sortable({
        connectWith: '.cs-blocks-items',
        items: 'li:not(:first)'
      }).on('sortupdate', function() {
        return $('#cs-blocks-position').val(JSON.stringify({
          top: $('#cs-top-blocks-items li:not(:first)')
        }.map(function() {
          return $(this).data('id');
        }).get(), {
          left: $('#cs-left-blocks-items li:not(:first)')
        }.map(function() {
          return $(this).data('id');
        }).get(), {
          floating: $('#cs-floating-blocks-items li:not(:first)')
        }.map(function() {
          return $(this).data('id');
        }).get(), {
          right: $('#cs-right-blocks-items li:not(:first)')
        }.map(function() {
          return $(this).data('id');
        }).get(), {
          bottom: $('#cs-bottom-blocks-items li:not(:first)')
        }.map(function() {
          return $(this).data('id');
        }).get()));
      });
      $('#cs-users-groups-list, #cs-users-groups-list-selected').sortable({
        connectWith: '#cs-users-groups-list, #cs-users-groups-list-selected',
        items: 'li:not(:first)'
      }).on('sortupdate', function() {
        var selected;
        $('#cs-users-groups-list').find('.uk-alert-success').removeClass('uk-alert-success').addClass('uk-alert-warning');
        selected = $('#cs-users-groups-list-selected');
        selected.find('.uk-alert-warning').removeClass('uk-alert-warning').addClass('uk-alert-success');
        return $('#cs-user-groups').val(JSON.stringify(selected.children('li:not(:first)').map(function() {
          return $(this).data('id');
        }).get()));
      });
    });
  });

}).call(this);
