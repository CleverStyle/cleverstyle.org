<?php
/**
 * @package   Update
 * @category  modules
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2013, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
 */
namespace cs;
use
	h;
$Config        = Config::instance();
$Route         = Route::instance();
$cdb           = DB::instance()->db_prime(0);
$Index         = Index::instance();
$module_object = $Config->module('Update');
if (!$module_object->version) {
	$module_object->version = 0;
}
if (@$Route->route[0] == 'process') {
	$version = (int)$module_object->version;
	/** @noinspection CallableInLoopTerminationConditionInspection */
	for ($i = 1; file_exists(__DIR__."/sql/$i.sql") || file_exists(__DIR__."/php/$i.php"); ++$i) {
		if ($version < $i) {
			if (file_exists(__DIR__."/sql/$i.sql")) {
				foreach (explode(';', file_get_contents(__DIR__."/sql/$i.sql")) as $s) {
					if ($s) {
						$cdb->q($s);
					}
				}
			}
			if (file_exists(__DIR__."/php/$i.php")) {
				include __DIR__."/php/$i.php";
			}
			$module_object->version = $i;
		}
	}
	$Index->save(true);
}
$Index->buttons = false;
$Index->content(
	h::{'p.cs-center'}("Current revision: $module_object->version").
	h::{'a.uk-button.cs-center'}(
		'Update System structure',
		[
			'href' => 'admin/Update/process'
		]
	)
);
