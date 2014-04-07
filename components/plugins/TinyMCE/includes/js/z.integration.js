/**
 * @package		TinyMCE
 * @category	plugins
 * @author		Moxiecode Systems AB
 * @author		Nazar Mokrynskyi <nazar@mokrynskyi.com> (integration into CleverStyle CMS)
 * @copyright	Moxiecode Systems AB
 * @license		GNU Lesser General Public License 2.1, see license.txt
 */
tinymce.baseURL	= '/components/plugins/TinyMCE/includes/js';
$(function () {
	var base_config			= {
		doctype					: '<!doctype html>',
		theme					: cs.tinymce && cs.tinymce.theme !== undefined ? cs.tinymce.theme : 'modern',
		skin					: cs.tinymce && cs.tinymce.skin !== undefined ? cs.tinymce.skin : 'lightgray',
		language				: cs.Language.clang !== undefined ? cs.Language.clang : 'en',
		menubar					: false,
		plugins					: 'advlist,anchor,charmap,code,contextmenu,fullscreen,hr,image,link,lists,media,nonbreaking,noneditable,pagebreak,paste,preview,searchreplace,tabfocus,table,textcolor,visualblocks,visualchars,wordcount',
		resize					: 'both',
		toolbar_items_size		: 'small',
		width					: '100%',
		convert_urls			: false,
		remove_script_host		: false,
		relative_urls			: false,
		file_browser_callback	: cs.file_upload ? function (field_name) {
			if (!tinymce.uploader_dialog) {
				tinymce.uploader_dialog		= $('<div title="Uploading..." class="cs-center"></div>')
					.html('<div style="margin-left: -10%; width: 20%;"><div class="uk-progress uk-progress-striped uk-active"><div class="uk-progress-bar"></div></div></div>')
					.appendTo('body')
					.cs().modal()
					.css('z-index', 100000);
			}
			var uploader	= cs.file_upload(
				null,
				function (files) {
					tinymce.uploader_dialog.cs().modal('hide');
					if (files.length) {
						$('#' + field_name).val(files[0]);
					}
				},
				function (error) {
					tinymce.uploader_dialog.cs().modal('hide');
					alert(error);
				},
				function (file) {
					tinymce.uploader_dialog.find('.uk-progress-bar').width((file.percent ? file.percent : 1) + '%');
					tinymce.uploader_dialog.cs().modal('show');
				}
			);
			uploader.browse();
			/**
			 * Destroy uploader instance on upload dialog closing
			 */
			var destroy_uploader = setInterval(function () {
				if (!$('#mce-modal-block').length) {
					uploader.destroy();
					clearInterval(destroy_uploader);
				}
			}, 1000);
		} : null
	};
	tinymce.editor_config	= $.extend(
		{
			toolbar1	: 'styleselect fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bold italic underline strikethrough superscript subscript | forecolor backcolor',
			toolbar2	: 'undo redo | bullist numlist outdent indent blockquote | link unlink anchor image media charmap hr nonbreaking pagebreak | visualchars visualblocks | searchreplace | fullscreen preview code'
		},
		base_config
	);
	tinymce.simple_editor_config	= $.extend(
		{
			toolbar	: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent blockquote | link image media | code'
		},
		base_config
	);
	tinymce.inline_editor_config	= $.extend(
		{
			inline	: true,
			menubar	: false
		},
		tinymce.editor_config
	);
	tinymce.simple_inline_editor_config	= $.extend(
		{
			inline	: true,
			menubar	: false
		},
		tinymce.simple_editor_config
	);
	cs.async_call([
		function () {
			/**
			 * Full editor
			 */
			$('.EDITOR').prop('required', false).tinymce(tinymce.editor_config);
		},
		function () {
			/**
			 * Simple editor
			 */
			$('.SIMPLE_EDITOR').prop('required', false).tinymce(tinymce.simple_editor_config);
		},
		function () {
			/**
			 * Inline editor
			 */
			$('.INLINE_EDITOR').prop('required', false).tinymce(tinymce.inline_editor_config);
		},
		function () {
			/**
			 * Small inline editor
			 */
			$('.SIMPLE_INLINE_EDITOR').prop('required', false).tinymce(tinymce.simple_inline_editor_config);
		}
	]);
});
function editor_deinitialization (id) {
	$('#' + id).tinymce().remove();
}
function editor_reinitialization (id) {
	var	textarea	= $('#' + id);
	if (textarea.hasClass('EDITOR')) {
		textarea.tinymce(tinymce.editor_config).load();
	} else if (textarea.hasClass('SIMPLE_EDITOR')) {
		textarea.tinymce(tinymce.simple_editor_config).load();
	} else if (textarea.hasClass('INLINE_EDITOR')) {
		textarea.tinymce(tinymce.inline_editor_config).load();
	} else if (textarea.hasClass('SIMPLE_INLINE_EDITOR')) {
		textarea.tinymce(tinymce.simple_inline_editor_config).load();
	}
}
function editor_focus (id) {
	$('#' + id).tinymce().focus();
}
