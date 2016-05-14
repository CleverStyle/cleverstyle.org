<?php
/**
 * @package   Update
 * @category  modules
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2013-2016, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
 */
namespace cs;
use
	h;

$Config        = Config::instance();
$Request       = Request::instance();
$cdb           = DB::instance()->db_prime(0);
$Page          = Page::instance();
$module_object = $Config->module('Update');
if (!$module_object->version) {
	$module_object->version = 0;
}
if ($Request->route(0) == 'process') {
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
	if ($Config->save()) {
		$Page->success('Success');
	} else {
		$Page->error('Error');
	}
}
$Page->content(
	h::{'p.cs-center'}("Current revision: $module_object->version").
	h::{'a.uk-button.cs-center'}(
		'Update System structure',
		[
			'href' => 'admin/Update/process'
		]
	)
);
