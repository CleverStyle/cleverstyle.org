// Generated by LiveScript 1.4.0
/**
 * @package    CleverStyle CMS
 * @subpackage System module
 * @category   modules
 * @author     Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright  Copyright (c) 2015-2016, Nazar Mokrynskyi
 * @license    MIT License, see license.txt
 */
(function(){
  var L;
  L = cs.Language('system_admin_databases_');
  Polymer({
    'is': 'cs-system-admin-databases-form',
    behaviors: [cs.Polymer.behaviors.Language('system_admin_databases_')],
    properties: {
      add: Boolean,
      databaseIndex: Number,
      mirrorIndex: Number,
      databases: Array,
      database: {
        type: Object,
        value: {
          mirror: -1,
          host: '',
          type: 'MySQLi',
          prefix: '',
          name: '',
          user: '',
          password: '',
          charset: ''
        }
      },
      engines: Array
    },
    ready: function(){
      var this$ = this;
      Promise.all([
        $.getJSON('api/System/admin/databases'), $.ajax({
          url: 'api/System/admin/databases',
          type: 'engines'
        })
      ]).then(function(arg$){
        this$.databases = arg$[0], this$.engines = arg$[1];
        if (this$.add) {
          if (!isNaN(this$.databaseIndex)) {
            this$.set('database.mirror', this$.databaseIndex);
          }
        } else {
          this$.databases.forEach(function(database){
            if (this$.databaseIndex == database.index) {
              if (isNaN(this$.mirrorIndex)) {
                this$.set('database', database);
              } else {
                database.mirrors.forEach(function(mirror){
                  if (this$.mirrorIndex == mirror.index) {
                    this$.set('database', mirror);
                  }
                });
              }
            }
          });
        }
      });
    },
    _save: function(){
      $.ajax({
        url: 'api/System/admin/databases' + (!isNaN(this.databaseIndex) ? '/' + this.databaseIndex + (!isNaN(this.mirrorIndex) ? '/' + this.mirrorIndex : '') : ''),
        type: this.add ? 'post' : 'patch',
        data: {
          mirror: this.database.mirror,
          host: this.database.host,
          type: this.database.type,
          prefix: this.database.prefix,
          name: this.database.name,
          user: this.database.user,
          password: this.database.password,
          charset: this.database.charset
        },
        success: function(){
          cs.ui.notify(L.changes_saved, 'success', 5);
        }
      });
    },
    _db_name: function(index, host, name){
      if (index) {
        return host + "/" + name;
      } else {
        return L.core_db;
      }
    },
    _test_connection: function(e){
      var $modal;
      $modal = $(cs.ui.simple_modal("<div>\n	<h3 class=\"cs-text-center\">" + L.test_connection + "</h3>\n	<progress is=\"cs-progress\" infinite></progress>\n</div>"));
      $.ajax({
        url: 'api/System/admin/databases',
        data: this.database,
        type: 'test',
        success: function(result){
          $modal.find('progress').replaceWith("<p class=\"cs-text-center cs-block-success cs-text-success\" style=text-transform:capitalize;\">" + L.success + "</p>");
        },
        error: function(){
          $modal.find('progress').replaceWith("<p class=\"cs-text-center cs-block-error cs-text-error\" style=text-transform:capitalize;\">" + L.failed + "</p>");
        }
      });
    }
  });
}).call(this);
