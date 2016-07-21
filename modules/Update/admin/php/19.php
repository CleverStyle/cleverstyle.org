<?php
/**
 * @package    CleverStyle CMS
 * @subpackage System module
 * @category   modules
 * @author     Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright  Copyright (c) 2015-2016, Nazar Mokrynskyi
 * @license    MIT License, see license.txt
 */
namespace cs;
$htaccess = file_get_contents(DIR.'/.htaccess');
$htaccess = str_replace(
	'<FilesMatch "\.(css|js|gif|jpg|jpeg|png|ico|eot|ttc|ttf|svg|svgz|woff)$">',
	'<FilesMatch "\.(css|js|gif|jpg|jpeg|png|ico|eot|ttc|ttf|svg|svgz|woff|woff2)$">',
	$htaccess
);
file_put_contents(DIR.'/.htaccess', $htaccess);
$htaccess = file_get_contents(DIR.'/.htaccess');
$htaccess = str_replace(
	'<FilesMatch "\.(css|js|gif|jpg|jpeg|png|ico|eot|ttc|ttf|svg|svgz|woff|woff2)$">',
	'<FilesMatch "\.(css|js|gif|jpg|jpeg|png|ico|svg|svgz|ttc|ttf|otf|woff|woff2|eot)$">',
	$htaccess
);
file_put_contents(DIR.'/.htaccess', $htaccess);
Text::instance()->del(
	Config::instance()->module('System')->db('texts'),
	'System/Config/core',
	'rules'
);
$Config = Config::instance();
if ($Config->core['language'] == 'Українська') {
	$Config->core['language'] = 'Ukrainian';
}
if ($Config->core['language'] == 'Русский') {
	$Config->core['language'] = 'Russian';
}
foreach ($Config->core['active_languages'] as &$language) {
	if ($language == 'Українська') {
		$language = 'Ukrainian';
	}
	if ($language == 'Русский') {
		$language = 'Russian';
	}
}
$Config->save();
DB::instance()->db_prime($Config->module('System')->db('users'))->q(
	"UPDATE `[prefix]users`
	SET `language` = 'Ukrainian'
	WHERE `language` = 'Українська'"
);
DB::instance()->db_prime($Config->module('System')->db('users'))->q(
	"UPDATE `[prefix]users`
	SET `language` = 'Russian'
	WHERE `language` = 'Русский'"
);
$Cache = Cache::instance();
$Cache->del('languages');
$Cache->del('users');
