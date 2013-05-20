<?php
/**
 * @package		CleverStyle CMS
 * @subpackage	System module
 * @category	modules
 * @author		Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright	Copyright (c) 2011-2013, Nazar Mokrynskyi
 * @license		MIT License, see license.txt
 */
namespace	cs\modules\System;
use			h;
function set_core_ml_text ($item, $text) {
	global $Config, $Text;
	return $Text->set($Config->module('System')->db('texts'), 'System/Config/core', $item, $text);
}
function core_input ($item, $type = 'text', $info_item = null, $disabled = false, $min = false, $max = false, $post_text = '') {
	global $Config, $L;
	if ($type != 'radio') {
		switch ($item) {
			default:
				$value	= $Config->core[$item];
			break;
			case 'name':
			case 'keywords':
			case 'description':
			case 'closed_title':
			case 'mail_from_name':
				$value	= get_core_ml_text($item);
		}
		return [
			$info_item !== false ? h::info($info_item ?: $item) : $L->$item,
			h::input([
				'name'		=> "core[$item]",
				'value'		=> $value,
				'min'		=> $min,
				'max'		=> $max,
				'type'		=> $type,
				($disabled ? 'disabled' : '')
			]).
			$post_text
		];
	} else {
		global $L;
		return [
			$info_item !== false ? h::info($info_item ?: $item) : $L->$item,
			h::input([
				'name'		=> "core[$item]",
				'checked'	=> $Config->core[$item],
				'value'		=> [0, 1],
				'in'		=> [$L->off, $L->on],
				'type'		=> $type
			])
		];
	}
}
function core_textarea ($item, $editor = null, $info_item = null) {
	global $Config;
	switch ($item) {
		default:
			$content	= $Config->core[$item];
			break;
		case 'closed_text':
		case 'footer_text':
		case 'mail_signature':
		case 'rules':
			$content	= get_core_ml_text($item);
	}
	return [
		h::info($info_item ?: $item),
		h::textarea(
			$content,
			[
				'name'	=> "core[$item]",
				'class'	=> 'cs-wide-textarea'.($editor ? ' '.$editor : '')
			]
		)
	];
}
function core_select ($items_array, $item, $id = null, $info_item = null, $multiple = false, $size = 5) {
	global $Config;
	return [
		h::info($info_item ?: $item),
		h::select(
			$items_array,
			[
				'name'		=> "core[$item]".($multiple ? '[]' : ''),
				'selected'	=> $Config->core[$item],
				'size'		=> $size,
				'id'		=> $id ?: false,
				$multiple ? 'multiple' : false
			]
		)
	];
}
/**
 * Function for normalization of dependence structure
 *
 * @param array|string	$dependence_structure
 *
 * @return array
 */
function dep_normal ($dependence_structure) {
	$return	= [];
	foreach ((array)$dependence_structure as $d) {
		if (!is_array($d)) {
			$d	= preg_match('/^([^<=>!]+)([<=>!]*)(.*)$/', $d);
		}
		$return[$d[0]]	= [
			isset($d[1]) ? $d[1] : 0,
			isset($d[2]) ? $d[2] : (isset($d[1]) ? '=' : '=>')
		];
	}
	return $return;
}
function check_dependencies ($name, $type = 'module', $dir = false) {
	if (!$dir) {
		switch ($type) {
			case 'module':
				$dir	= MODULES.'/'.$name;
			break;
			case 'plugin':
				$dir	= PLUGINS.'/'.$name;
			break;
			default:
				return false;
		}
	}
	if (!file_exists($dir.'/meta.json')) {
		return true;
	}
	$meta		= _json_decode(file_get_contents($dir.'/meta.json'));
	global $Config, $Page, $L, $Core;
	if (isset($meta['db_support']) && !empty($meta['db_support'])) {
		$return		= false;
		if (in_array($Core->db_type, $meta['db_support'])) {
			$return	= true;
		} else {
			foreach ($Config->db as $database) {
				if (isset($database['type']) && in_array($database['type'], $meta['db_support'])) {
					$return	= true;
					break;
				}
			}
			unset($database);
		}
		if (!$return) {
			$Page->warning(
				$L->compatible_databases_not_found(
					implode('", "', $meta['db_support'])
				)
			);
		} elseif (!$Config->core['simple_admin_mode']) {
			$Page->notice(
				$L->compatible_databases(
					implode('", "', $meta['db_support'])
				)
			);
		}
	} else {
		$return		= true;
	}
	if (isset($meta['storage_support']) && !empty($meta['storage_support'])) {
		$return_s	= false;
		if (in_array($Core->storage_type, $meta['storage_support'])) {
			$return_s	= true;
		} else {
			foreach ($Config->storage as $storage) {
				if (in_array($storage['connection'], $meta['storage_support'])) {
					$return_s	= true;
					break;
				}
			}
		}
		if (!$return_s) {
			$Page->warning(
				$L->compatible_storages_not_found(
					implode('", "', $meta['storage_support'])
				)
			);
		} elseif (!$Config->core['simple_admin_mode']) {
			$Page->notice(
				$L->compatible_storages(
					implode('", "', $meta['storage_support'])
				)
			);
		}
		$return = $return && $return_s;
		unset($storage, $return_s);
	}
	$provide	= [];
	$require	= [];
	$conflict	= [];
	if (isset($meta['provide'])) {
		$provide	= (array)$meta['provide'];
	}
	if (isset($meta['require']) && !empty($meta['require'])) {
		$require	= dep_normal((array)$meta['require']);
	}
	if (isset($meta['conflict']) && !empty($meta['conflict'])) {
		$conflict	= dep_normal((array)$meta['conflict']);
	}
	unset($meta);
	/**
	 * Checking for compatibility with modules
	 */
	foreach ($Config->components['modules'] as $module => $module_data) {
		if (
			$module_data['active'] == -1 ||
			(
				$module == $name && $type == 'module'
			) ||
			!file_exists(MODULES.'/'.$module.'/meta.json')
		) {
			continue;
		}
		$module_meta	= _json_decode(file_get_contents(MODULES.'/'.$module.'/meta.json'));
		/**
		 * If some module already provides the same functionality
		 */
		if (
			!empty($provide) &&
			isset($module_meta['provide']) &&
			is_array($module_meta['provide']) &&
			!empty($module_meta['provide']) &&
			$intersect = array_intersect($provide, $module_meta['provide'])
		) {
			if ($return) {
				$Page->warning($L->dependencies_not_satisfied);
			}
			$return	= false;
			$Page->warning(
				$L->module_already_provides_functionality(
					$module,
					implode('", "', $intersect)
				)
			);
		}
		unset($intersect);
		/**
		 * Checking for required packages
		 */
		if (isset($require[$module_meta['package']])) {
			if (
				version_compare(
					$require[$module_meta['package']][0],
					$module_meta['version'],
					$require[$module_meta['package']][1]
				)
			) {
				unset($require[$module_meta['package']]);
			} else {
				if ($return) {
					$Page->warning($L->dependencies_not_satisfied);
				}
				$return	= false;
				$Page->warning(
					$L->unsatisfactory_version_of_the_module_package(
						$module_meta['package'],
						$module,
						$require[$module_meta['package']][1].' '.$require[$module_meta['package']][0],
						$module_meta['version']
					)
				);
			}
		}
		/**
		 * Checking for conflict packages
		 */
		if (isset($module_meta['conflict']) && is_array($module_meta['conflict']) && !empty($module_meta['conflict'])) {
			if (
				version_compare(
					$conflict[$module_meta['package']][0],
					$module_meta['version'],
					$conflict[$module_meta['package']][1]
				)
			) {
				if ($return) {
					$Page->warning($L->dependencies_not_satisfied);
				}
				$return	= false;
				$Page->warning(
					$L->conflict_module_package(
						$module_meta['package'],
						$module
					).
					(
						$conflict[$module_meta['package']][0] != 0 ? $L->compatible_package_versions(
							$require[$module_meta['package']][1].' '.$require[$module_meta['package']][0]
						) : $L->package_is_incompatible(
							$module_meta['package']
						)
					)
				);
			}
		}
	}
	unset($module, $module_data, $module_meta);
	/**
	 * Checking for compatibility with plugins
	 */
	foreach ($Config->components['plugins'] as $plugin) {
		if (
			(
				$plugin == $name && $type == 'plugin'
			) ||
			!file_exists(PLUGINS.'/'.$plugin.'/meta.json')
		) {
			continue;
		}
		$plugin_meta	= _json_decode(file_get_contents(PLUGINS.'/'.$plugin.'/meta.json'));
		/**
		 * If some plugin already provides the same functionality
		 */
		if (
			!empty($provide) &&
			isset($plugin_meta['provide']) &&
			is_array($plugin_meta['provide']) &&
			!empty($plugin_meta['provide']) &&
			$intersect = array_intersect($provide, $plugin_meta['provide'])
		) {
			if ($return) {
				$Page->warning($L->dependencies_not_satisfied);
			}
			$return	= false;
			$Page->warning(
				$L->plugin_already_provides_functionality(
					$plugin,
					implode('", "', $intersect)
				)
			);
		}
		unset($intersect);
		/**
		 * Checking for required packages
		 */
		if (isset($require[$plugin_meta['package']])) {
			if (
				version_compare(
					$require[$plugin_meta['package']][0],
					$plugin_meta['version'],
					$require[$plugin_meta['package']][1]
				)
			) {
				unset($require[$plugin_meta['package']]);
			} else {
				if ($return) {
					$Page->warning($L->dependencies_not_satisfied);
				}
				$return	= false;
				$Page->warning(
					$L->unsatisfactory_version_of_the_plugin_package(
						$plugin_meta['package'],
						$plugin,
						$require[$plugin_meta['package']][1].' '.$require[$plugin_meta['package']][0],
						$plugin_meta['version']
					)
				);
			}
		}
		/**
		 * Checking for conflict packages
		 */
		if (isset($plugin_meta['conflict']) && is_array($plugin_meta['conflict']) && !empty($plugin_meta['conflict'])) {
			if (
				version_compare(
					$conflict[$plugin_meta['package']][0],
					$plugin_meta['version'],
					$conflict[$plugin_meta['package']][1]
				)
			) {
				if ($return) {
					$Page->warning($L->dependencies_not_satisfied);
				}
				$return	= false;
				$Page->warning(
					$L->conflict_plugin_package(
						$plugin_meta['package'],
						$plugin
					).
					(
						$conflict[$plugin_meta['package']][0] != 0 ? $L->compatible_package_versions(
							$require[$plugin_meta['package']][1].' '.$require[$plugin_meta['package']][0]
						) : $L->package_is_incompatible(
							$plugin_meta['package']
						)
					)
				);
			}
		}
	}
	unset($plugin, $plugin_meta, $provide, $conflict);
	/**
	 * If some required packages missing
	 */
	if (!empty($require)) {
		foreach ($require as $package => $details) {
			if ($return) {
				$Page->warning($L->dependencies_not_satisfied);
			}
			$return					= false;
			$Page->warning(
				$L->package_not_found(
					$package.' '.$details[1].' '.$details[0]
				)
			);
		}
	}
	return $return;
}
function check_backward_dependencies ($name, $type = 'module') {
	switch ($type) {
		case 'module':
			$dir	= MODULES.'/'.$name;
			break;
		case 'plugin':
			$dir	= PLUGINS.'/'.$name;
			break;
		default:
			return false;
	}
	if (!file_exists($dir.'/meta.json')) {
		return true;
	}
	$meta		= _json_decode(file_get_contents($dir.'/meta.json'));
	$meta		= [
		'package'	=> $meta['package'],
		'version'	=> $meta['version']
	];
	$return		= true;
	/**
	 * Checking for backward dependencies of modules
	 */
	global $Config, $Page, $L;
	foreach ($Config->components['modules'] as $module => $module_data) {
		if (
			$module_data['active'] == -1 ||
			(
				$module == $name && $type == 'module'
			) ||
			!file_exists(MODULES.'/'.$module.'/meta.json')
		) {
			continue;
		}
		$module_require	= _json_decode(file_get_contents(MODULES.'/'.$module.'/meta.json'));
		if (!isset($module_require['require'])) {
			continue;
		}
		$module_require	= dep_normal($module_require['require']);
		if (isset($module_require[$meta['package']])) {
			if ($return) {
				$Page->warning($L->dependencies_not_satisfied);
			}
			$return	= false;
			$Page->warning($L->this_package_is_used_by_module($module));
		}
	}
	unset($module, $module_data, $module_require);
	/**
	 * Checking for backward dependencies of plugins
	 */
	foreach ($Config->components['plugins'] as $plugin) {
		if (
			(
				$plugin == $name && $type == 'plugin'
			) ||
			!file_exists(PLUGINS.'/'.$plugin.'/meta.json')
		) {
			continue;
		}
		$plugin_require	= _json_decode(file_get_contents(PLUGINS.'/'.$plugin.'/meta.json'));
		if (!isset($plugin_require['require'])) {
			continue;
		}
		$plugin_require	= dep_normal($plugin_require['require']);
		if (isset($plugin_require[$meta['package']])) {
			if ($return) {
				$Page->warning($L->dependencies_not_satisfied);
			}
			$return	= false;
			$Page->warning($L->this_package_is_used_by_plugin($plugin));
		}
	}
	return $return;
}
/**
 * @param array[]	$rows
 * @param int	$cols
 *
 * @return array
 */
function form_rows_to_cols ($rows, $cols = 2) {
	foreach ($rows[0] as $i => &$r) {
		$r	.= $rows[1][$i];
	}
	unset($i, $r);
	$rows	= $rows[0];
	$return	= [];
	$row	= 0;
	foreach ($rows as $i => $r) {
		if (!isset($return[$row])) {
			$return[$row]	= '';
		}
		$return[$row]	.= $r;
		if (++$i % $cols === 0) {
			++$row;
		}
	}
	if (isset($i) && ($i % $cols != 0)) {
		$return[$row]	.= str_repeat(h::th().h::td(), $cols - ($i % $cols));
	}
	return $return;
}