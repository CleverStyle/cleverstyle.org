// Generated by LiveScript 1.4.0
/**
 * @package    CleverStyle Framework
 * @subpackage System module
 * @category   modules
 * @author     Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright  Copyright (c) 2015-2016, Nazar Mokrynskyi
 * @license    MIT License, see license.txt
 */
(function(){
  var L;
  L = cs.Language('system_admin_storages_');
  Polymer({
    'is': 'cs-system-admin-storages-list',
    behaviors: [cs.Polymer.behaviors.Language('system_admin_storages_')],
    ready: function(){
      this.reload();
    },
    reload: function(){
      var this$ = this;
      cs.api('get api/System/admin/storages').then(function(storages){
        this$.set('storages', storages);
      });
    },
    _add: function(){
      cs.ui.simple_modal("<h3>" + L.adding_of_storage + "</h3>\n<cs-system-admin-storages-form add/>").addEventListener('close', bind$(this, 'reload'));
    },
    _edit: function(e){
      var storage_model, storage, name;
      storage_model = this.$.storages_list.modelForElement(e.target);
      storage = e.model.storage || storage_model.storage;
      name = storage.host + '/' + storage.connection;
      cs.ui.simple_modal("<h3>" + L.editing_of_storage(name) + "</h3>\n<cs-system-admin-storages-form storage-index=\"" + storage.index + "\"/>").addEventListener('close', bind$(this, 'reload'));
    },
    _delete: function(e){
      var storage_model, storage, name, this$ = this;
      storage_model = this.$.storages_list.modelForElement(e.target);
      storage = e.model.storage || storage_model.storage;
      name = storage.host + '/' + storage.connection;
      cs.ui.confirm(L.sure_to_delete(name)).then(function(){
        return cs.api('delete api/System/admin/storages/' + storage.index);
      }).then(function(){
        cs.ui.notify(L.changes_saved, 'success', 5);
        this$.reload();
      });
    }
  });
  function bind$(obj, key, target){
    return function(){ return (target || obj)[key].apply(obj, arguments) };
  }
}).call(this);
