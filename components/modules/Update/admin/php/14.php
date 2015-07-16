<?php
/**
 * @package    CleverStyle CMS
 * @subpackage System module
 * @category   modules
 * @author     Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright  Copyright (c) 2015, Nazar Mokrynskyi
 * @license    MIT License, see license.txt
 */
namespace cs;

$Config                 = Config::instance();
$core                   = &$Config->core;
$core['db_mirror_mode'] = $core['maindb_for_write'];
unset(
	$core['maindb_for_write']
);
$Config->save();
$config = file_get_contents(DIR.'/config/main.json');
$config = str_replace("//Cache size in MB for FileSystem storage engine\n", '', $config);
$config = preg_replace("/\s*\"cache_size\"\s*:\s*\"[^\"]+\",\n/Uims", "\n", $config);
file_put_contents(DIR.'/config/main.json', $config);
$Config = Config::instance();
$db     = DB::instance();
foreach ($Config->db as $db_index => $db_params) {
	$db_name = $db_index == 0 ? Core::instance()->db_name : $db_params['name'];
	$db->db_prime($db_index)->q(
		"ALTER DATABASE `$db_name` CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci"
	);
}
$db->db_prime(0)->q(
	[
		'ALTER TABLE `[prefix]config` DROP PRIMARY KEY, ADD PRIMARY KEY (`domain`(191))',
		'ALTER TABLE `[prefix]config` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci',
		'REPAIR TABLE `[prefix]users_permissions`',
		'OPTIMIZE TABLE `[prefix]users_permissions`'

	]
);
$config = file_get_contents(DIR.'/config/main.json');
$config = str_replace("//Will be truncated if necessary", '//Default encryption key', $config);
file_put_contents(DIR.'/config/main.json', $config);
