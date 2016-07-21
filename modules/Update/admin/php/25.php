<?php
/**
 * @package   CleverStyle Framework
 * @category  modules
 * @author    Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright Copyright (c) 2016, Nazar Mokrynskyi
 * @license   MIT License, see license.txt
 */
namespace cs;
$Config = Config::instance();
unset($Config->components['plugins']);
$Config->save();
chmod(DIR.'/cli', 0770);
