<?php
/**
 * @package		CleverStyle CMS
 * @subpackage	UPF (Useful PHP Functions)
 * @author		Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright	Copyright (c) 2011-2015, Nazar Mokrynskyi
 * @license		MIT License, see license.txt
 */

/**
 * Special function for files including
 *
 * @param string		$file
 * @param bool			$once
 * @param bool|callable	$show_errors	If bool error will be processed, if callable - only callable will be called
 *
 * @return bool
 */
function _require ($file, $once = false, $show_errors = true) {
	if (file_exists($file)) {
		if ($once) {
			return require_once $file;
		} else {
			return require $file;
		}
	} elseif (is_bool($show_errors) && $show_errors) {
		$data = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
		trigger_error("File $file does not exists in $data[file] on line $data[line]", E_USER_ERROR);
	} elseif (is_callable($show_errors)) {
		return (bool)$show_errors();
	}
	return false;
}
/**
 * Special function for files including
 *
 * @param string		$file
 * @param bool			$once
 * @param bool|callable	$show_errors	If bool error will be processed, if callable - only callable will be called
 *
 * @return bool
 */
function _include ($file, $once = false, $show_errors = true) {
	if (file_exists($file)) {
		if ($once) {
			return include_once $file;
		} else {
			return include $file;
		}
	} elseif (is_bool($show_errors) && $show_errors) {
		$data = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
		trigger_error("File $file does not exists in $data[file] on line $data[line]", E_USER_WARNING);
	} elseif (is_callable($show_errors)) {
			return (bool)$show_errors();
	}
	return false;
}
/**
 * Special function for files including
 *
 * @param string		$file
 * @param bool|callable	$show_errors	If bool error will be processed, if callable - only callable will be called
 *
 * @return bool
 */
function _require_once ($file, $show_errors = true) {
	return _require($file, true, $show_errors);
}
/**
 * Special function for files including
 *
 * @param string		$file
 * @param bool|callable	$show_errors	If bool error will be processed, if callable - only callable will be called
 *
 * @return bool
 */
function _include_once ($file, $show_errors = true) {
	return _include($file, true, $show_errors);
}
/**
 * Temporary disabling of time limit
 *
 * @param bool $pause
 */
function time_limit_pause ($pause = true) {
	static $time_limit;
	if (!isset($time_limit)) {
		$time_limit = ['max_execution_time' => ini_get('max_execution_time'), 'max_input_time' => ini_get('max_input_time')];
	}
	if ($pause) {
		set_time_limit(900);
		@ini_set('max_input_time', 900);
	} else {
		set_time_limit($time_limit['max_execution_time']);
		@ini_set('max_input_time', $time_limit['max_input_time']);
	}
}
/**
 * Function for getting content of a directory
 *
 * @param	string		$dir			Directory for searching
 * @param	bool|string	$mask			Regexp for items
 * @param	string		$mode			Mode of searching<br>
 * 										<b>f</b> - files only (default)<br>
 * 										<b>d</b> - directories only<br>
 * 										<b>fd</b> - both files and directories
 * @param	bool|string	$prefix_path	Path to be added to the beginning of every found item. If <b>true</b> - prefix will
 * 										be absolute path to item on server.
 * @param	bool		$subfolders		Search in subdirectories or not
 * @param	bool		$sort			Sort mode in format <b>mode|order</b>:<br>
 * 										Possible values for mode: <b>name</b> (default), <b>date</b>, <b>size</b>
 * 										Possible values for mode: <b>asc</b> (default), <b>desc</b>
 * @param	bool|string	$exclusion		If specified file exists in scanned directory - it will be excluded from scanning
 * @param	bool		$system_files	Show system files: .htaccess .htpasswd .gitignore
 * @param	callable	$apply			Apply function to each found item, return nothing (sorting will not work, items will be processed as they found)
 * @param	int|null	$limit			If specified - limits total number of found items (if $limit items found - stop further searching)
 *
 * @return	array|bool
 */
function get_files_list (
	$dir,
	$mask			= false,
	$mode			= 'f',
	$prefix_path	= false,
	$subfolders		= false,
	$sort			= false,
	$exclusion		= false,
	$system_files	= false,
	$apply			= null,
	$limit			= null
) {
	/**
	 * Wrapped in internal closure in order to use &$limit
	 */
	static $func;
	!isset($func) && $func = function ($dir, $mask, $mode, $prefix_path, $subfolders, $sort, $exclusion, $system_files, $apply, &$limit) {
		if ($mode == 'df') {
			$mode = 'fd';
		}
		$dir	= rtrim($dir, '/').'/';
		if (!is_dir($dir) || ($exclusion !== false && file_exists($dir.$exclusion))) {
			return false;
		}
		/**
		 * Default sorting
		 */
		if ($sort === false) {
			$sort	= 'name';
			$sort_x	= ['name', 'asc'];
		} else {
			$sort	= mb_strtolower($sort);
			$sort_x	= explode('|', $sort);
			if (@$sort_x[1] != 'desc') {
				$sort_x[1] = 'asc';
			}
		}
		/**
		 * Sort by
		 */
		if (@$sort_x[0] == 'date') {
			$prepare_for_sort = function (&$list, $tmp, $link) {
				/**
				 * File access or modification time, access time may be unavailable on some configurations
				 */
				$list[fileatime($link) ?: filemtime($link)] = $tmp;
			};
		} elseif (@$sort_x[0] == 'size') {
			$prepare_for_sort = function (&$list, $tmp, $link) {
				$list[filesize($link)] = $tmp;
			};
		} else {
			$prepare_for_sort = function (&$list, $tmp) {
				$list[] = $tmp;
			};
		}
		$list	= [];
		/**
		 * If custom prefix for path was specified
		 */
		if ($prefix_path !== true && $prefix_path) {
			$prefix_path = rtrim($prefix_path, '/').'/';
		}
		$dirc	= opendir($dir);
		if (!is_resource($dirc)) {
			return false;
		}
		while (($file = readdir($dirc)) !== false) {
			/**
			 * Limit of found files reached (updated at the end of iteration before nex iteration or recursive opening of nested directory)
			 */
			if ($limit < 0) {
				break;
			}
			if (
				(
					$mask &&
					!preg_match($mask, $file)
					&&
					(
						!$subfolders || !is_dir($dir.$file)
					)
				) ||
				$file == '.' ||
				$file == '..' ||
				(!$system_files &&
					(
						$file == '.htaccess' ||
						$file == '.htpasswd' ||
						$file == '.gitignore'
					)
				)
			) {
				continue;
			}
			if (
				$mode == 'fd' ||
				(
					is_file($dir.$file) && $mode == 'f'
				) ||
				(
					is_dir($dir.$file) && $mode == 'd'
				)
			) {
				--$limit;
				$item	= $prefix_path === true ? $dir.$file : $prefix_path.$file;
				if (!is_callable($apply)) {
					$prepare_for_sort($list, $item, $dir.$file);
				}
			}
			if ($limit >= 0) {
				if ($subfolders && is_dir($dir.$file)) {
					$list = array_merge(
						$list,
						get_files_list(
							$dir.$file,
							$mask,
							$mode,
							$prefix_path === true || $prefix_path === false ? $prefix_path : $prefix_path.$file,
							$subfolders,
							$sort,
							$exclusion,
							$system_files,
							$apply,
							$limit
						) ?: []
					);
				}
			}
			/**
			 * Apply custom operation to found item without waiting for result returning
			 */
			if (isset($item) && is_callable($apply)) {
				$apply($item);
			}
			unset($item);
			if ($limit < 0) {
				break;
			}
		}
		closedir($dirc);
		unset($prepare_for_sort);
		if ($list && @$sort_x) {
			switch ($sort_x[0]) {
				case 'date':
				case 'size':
					if ($sort_x[1] == 'desc') {
						krsort($list);
					} else {
						ksort($list);
					}
				break;
				case 'name':
					natcasesort($list);
					if ($sort_x[1] == 'desc') {
						$list = array_reverse($list);
					}
				break;
			}
		}
		return array_values($list);
	};
	return $func($dir, $mask, $mode, $prefix_path, $subfolders, $sort, $exclusion, $system_files, $apply, $limit);
}
/**
 * Get file extension from filename
 *
 * @param string	$filename
 *
 * @return string
 */
function file_extension ($filename) {
	return mb_substr(mb_strrchr($filename, '.'), 1);
}
if (!function_exists('is_unicode')) {
	/**
	 * Checks whether string is unicode or not
	 *
	 * @param string $s
	 *
	 * @return bool
	 */
	function is_unicode ($s) {
		return mb_check_encoding($s, 'utf-8');
	}
}
/**
 * Protecting against null byte injection
 *
 * @param string|string[]	$in
 *
 * @return string|string[]
 */
function null_byte_filter ($in) {
	if (is_array($in)) {
		foreach ($in as &$val) {
			$val = null_byte_filter($val);
		}
	} else {
		$in = str_replace(chr(0), '', $in);
	}
	return $in;
}
/**
 * Prepare text to be used as value for html attribute value
 *
 * @param string|string[]	$text
 *
 * @return string|string[]
 */
function prepare_attr_value ($text) {
	if (is_array($text)) {
		foreach ($text as &$val) {
			$val = prepare_attr_value($val);
		}
		return $text;
	}
	return strtr(
		$text,
		[
			'&'		=> '&amp;',
			'"'		=> '&quot;',
			'\''	=> '&apos;',
			'<'		=> '&lt;',
			'>'		=> '&gt;'
		]
	);
}
/**
 * Like system function, but accept arrays of strings
 *
 * @param string|string[]	$str
 *
 * @return string|string[]
 */
function _stripslashes ($str) {
	if (is_array($str)) {
		return array_map('stripslashes', $str);
	}
	return stripslashes($str);
}
/**
 * Like system function, but accept arrays of strings
 *
 * @param string|string[]	$str
 *
 * @return string|string[]
 */
function _addslashes ($str) {
	if (is_array($str)) {
		return array_map('addslashes', $str);
	}
	return addslashes($str);
}
/**
 * Like system function, but accept arrays of strings
 *
 * @param string|string[]	$str
 * @param string			$charlist
 *
 * @return string|string[]
 */
function _trim ($str, $charlist = " \t\n\r\0\x0B") {
	if (is_array($str)) {
		foreach ($str as &$s) {
			$s = trim($s, $charlist);
		}
		return $str;
	}
	return trim($str, $charlist);
}
/**
 * Like system function, but accept arrays of strings
 *
 * @param string|string[]	$str
 * @param string			$charlist
 *
 * @return string|string[]
 */
function _ltrim ($str, $charlist = " \t\n\r\0\x0B") {
	if (is_array($str)) {
		foreach ($str as &$s) {
			$s = ltrim($s);
		}
		return $str;
	}
	return ltrim($str, $charlist);
}
/**
 * Like system function, but accept arrays of strings
 *
 * @param string|string[]	$str
 * @param string			$charlist
 *
 * @return string|string[]
 */
function _rtrim ($str, $charlist = " \t\n\r\0\x0B") {
	if (is_array($str)) {
		foreach ($str as &$s) {
			$s = rtrim($s, $charlist);
		}
		return $str;
	}
	return rtrim($str, $charlist);
}
/**
 * Like system function, but accept arrays of strings
 *
 * @param string|string[]	$string
 * @param int				$start
 * @param int				$length
 *
 * @return string|string[]
 */
function _substr ($string, $start, $length = null) {
	if (is_array($string)) {
		foreach ($string as &$s) {
			$s = substr($s, $start, $length);
		}
		return $string;
	}
	return substr($string, $start, $length);
}
/**
 * Like system function, but accept arrays of strings
 *
 * @param string|string[]	$string
 * @param int				$start
 * @param int				$length
 *
 * @return string|string[]
 */
function _mb_substr ($string, $start, $length = null) {
	if (is_array($string)) {
		foreach ($string as &$s) {
			$s = mb_substr($s, $start, $length, 'utf-8');
		}
		return $string;
	}
	return mb_substr($string, $start, $length, 'utf-8');
}
/**
 * Like system function, but accept arrays of strings
 *
 * @param string|string[]	$string
 *
 * @return string|string[]
 */
function _strtolower ($string) {
	if (is_array($string)) {
		return array_map('strtolower', $string);
	}
	return strtolower($string);
}
/**
 * Like system function, but accept arrays of strings
 *
 * @param string|string[]	$string
 *
 * @return string|string[]
 */
function _strtoupper ($string) {
	if (is_array($string)) {
		return array_map('strtoupper', $string);
	}
	return strtoupper($string);
}
/**
 * Like system function, but accept arrays of strings
 *
 * @param string|string[]	$string
 *
 * @return string|string[]
 */
function _mb_strtolower ($string) {
	if (is_array($string)) {
		foreach ($string as &$s) {
			$s = mb_strtolower($s, 'utf-8');
		}
		return $string;
	}
	return mb_strtolower($string, 'utf-8');
}
/**
 * Like system function, but accept arrays of strings
 *
 * @param string|string[]	$string
 *
 * @return string|string[]
 */
function _mb_strtoupper ($string) {
	if (is_array($string)) {
		foreach ($string as &$s) {
			$s = mb_strtoupper($s, 'utf-8');
		}
		return $string;
	}
	return mb_strtoupper($string, 'utf-8');
}
/**
 * Works similar to the system function, but adds JSON_UNESCAPED_UNICODE option
 *
 * @param mixed		$value
 *
 * @return bool|string
 */
function _json_encode ($value) {
	return @json_encode($value, JSON_UNESCAPED_UNICODE);
}
/**
 * Works similar to the system function, but always returns array, not object
 *
 * @param string	$in
 * @param int		$depth
 *
 * @return bool|mixed
 */
function _json_decode ($in, $depth = 512) {
	return @json_decode($in, true, $depth);
}
/**
 * Works similar to _json_decode(), but deletes specific comments
 *
 * @see _json_decode()
 *
 * @param string	$in
 * @param int		$depth
 *
 * @return bool|mixed
 */
function _json_decode_nocomments ($in, $depth = 512) {
	$in	= explode("\n", $in);
	foreach ($in as $i => $string) {
		if (substr(ltrim($string), 0, 2) == '//') {
			unset($in[$i]);
		}
	}
	unset($i, $string);
	return @json_decode(implode('', $in), true, $depth);
}
/**
 * file_put_contents(_json_encode())
 *
 * @param string	$filename	Name of the file to read.
 * @param mixed		$data		The data to write
 * @param int		$flags
 * @param resource	$context
 *
 * @return mixed
 */
function file_put_json ($filename, $data, $flags = null, &$context = null) {
	return file_put_contents(
		$filename,
		_json_encode($data),
		$flags,
		$context
	);
}
/**
 * _json_decode(file_get_contents())
 *
 * @param string	$filename	Name of the file to read.
 *
 * @return mixed
 */
function file_get_json ($filename) {
	return _json_decode(
		file_get_contents($filename)
	);
}
/**
 * _json_decode_nocomments(file_get_contents())
 *
 * @param string	$filename	Name of the file to read.
 *
 * @return mixed
 */
function file_get_json_nocomments ($filename) {
	return _json_decode_nocomments(
		file_get_contents($filename)
	);
}
/**
 * Similar to system function, but make simple check, whether regexp is correct (actually checks if first symbol is / or #)
 *
 * @param string		$pattern
 * @param string		$subject
 * @param null|mixed	$matches
 * @param int			$flags
 * @param int			$offset
 *
 * @return bool|int
 */
function _preg_match ($pattern, $subject, &$matches = null, $flags = 0, $offset = 0) {
	if (strpos($pattern, '/') === false && strpos($pattern, '#') === false) {
		return false;
	}
	$pattern = trim($pattern);
	return preg_match($pattern, $subject, $matches, $flags, $offset);
}

/**
 * Similar to system function, but make simple check, whether regexp is correct (actually checks if first symbol is / or #)
 *
 * @param string	$pattern
 * @param string	$replacement
 * @param string	$subject
 * @param int		$limit
 * @param null		$count
 *
 * @return bool|mixed
 */
function _preg_replace ($pattern, $replacement, $subject, $limit = -1, &$count = null) {
	if (strpos($pattern, '/') === false && strpos($pattern, '#') === false) {
		return false;
	}
	$pattern = trim($pattern);
	return preg_replace($pattern, $replacement, $subject, $limit, $count);
}
/**
 * XSS Attack Protection. Returns secure string using several types of filters
 *
 * @param string|string[]	$in		HTML code
 * @param bool|string		$html	<b>text</b> - text at output (default)<br>
 * 									<b>true</b> - processed HTML at output<br>
 * 									<b>false</b> - HTML tags will be deleted
 * @param bool				$iframe	Whether to allow iframes without inner content (for example, video from youtube)<br>
 * 									Works only if <i>$html === true</i>
 * @return string|string[]
 */
function xap ($in, $html = 'text', $iframe = false) {
	if (is_array($in)) {
		foreach ($in as &$item) {
			$item = xap($item, $html);
		}
		return $in;
	/**
	 * Make safe HTML
	 */
	} elseif ($html === true) {
		$in = preg_replace(
			'/
				<[^a-z=>]*(link|script|object|applet|embed)[^>]*>?	# Open tag
				(
					.*												# Some content
					<\/[^>]*\\1[^>]*>								# Close tag (with reference for tag name to open tag)
				)?													# Section is optional
			/xims',
			'',
			$in
		);
		/**
		 * Remove iframes (regular expression the same as previous)
		 */
		if (!$iframe) {
			$in = preg_replace(
				'/
					<[^a-z=>]*iframe[^>]*>?		# Open tag
					(
						.*						# Some content
						<\/[^>]*iframe[^>]*>	# Close tag
					)?							# Section is optional
				/xims',
				'',
				$in
			);
		/**
		 * Allow iframes without inner content (for example, video from youtube)
		 */
		} else {
			$in = preg_replace(
				'/
					(<[^a-z=>]*iframe[^>]*>\s*)	# Open tag
					[^<\s]+						# Search if there something that is not space or < character
					(<\/[^>]*iframe[^>]*>)?		# Optional close tag
				/xims',
				'',
				$in
			);
		}
		$in = preg_replace(
			'/(script|data|vbscript):/i',
			'\\1&#58;',
			$in
		);
		$in = preg_replace(
			'/(expression[\s]*)\(/i',
			'\\1&#40;',
			$in
		);
		$in = preg_replace(
			'/<[^>]*\s(on[a-z]+|dynsrc|lowsrc|formaction)=[^>]*>?/ims',
			'',
			$in
		);
		$in = preg_replace(
			'/(href[\s\t\r\n]*=[\s\t\r\n]*["\'])((?:http|https|ftp)\:\/\/.*?["\'])/ims',
			'\\1redirect/\\2',
			$in
		);
		return $in;
	} elseif ($html === false) {
		return strip_tags($in);
	} else {
		return htmlspecialchars($in, ENT_NOQUOTES | ENT_HTML5 | ENT_DISALLOWED | ENT_SUBSTITUTE | ENT_HTML5);
	}
}
/**
 * Function for converting of IPv4 and IPv6 into hex values to store in db
 *
 * @link http://www.php.net/manual/ru/function.ip2long.php#82013
 *
 * @param string		$ip
 *
 * @return bool|string
 */
function ip2hex ($ip) {
	if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false) {
		$isIPv4 = true;
	} elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false) {
		$isIPv4 = false;
	} else {
		return false;
	}
	/**
	 * IPv4 format
	 */
	if($isIPv4) {
		$parts = explode('.', $ip);
		foreach ($parts as &$part) {
			$part = str_pad(dechex($part), 2, '0', STR_PAD_LEFT);
		}
		unset($part);
		$ip			= "::$parts[0]$parts[1]:$parts[2]$parts[3]";
		$hex		= implode('', $parts);
	/**
	 * IPv6 format
	 */
	} else {
		$parts		= explode(':', $ip);
		$last_part	= count($parts) - 1;
		/**
		 * If mixed IPv6/IPv4, convert ending to IPv6
		 */
		if(filter_var($parts[$last_part], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false) {
			$parts[$last_part] = explode('.', $parts[$last_part]);
			foreach ($parts[$last_part] as &$part) {
				$part = str_pad(dechex($part), 2, '0', STR_PAD_LEFT);
			}
			unset($part);
			$parts[]			= $parts[$last_part][2].$parts[$last_part][3];
			$parts[$last_part]	= $parts[$last_part][0].$parts[$last_part][1];
		}
		$numMissing		= 8 - count($parts);
		$expandedParts	= [];
		$expansionDone	= false;
		foreach($parts as $part) {
			if(!$expansionDone && $part == '') {
				for($i = 0; $i <= $numMissing; ++$i) {
					$expandedParts[] = '0000';
				}
				$expansionDone = true;
			} else {
				$expandedParts[] = $part;
			}
		}
		foreach($expandedParts as &$part) {
			$part = str_pad($part, 4, '0', STR_PAD_LEFT);
		}
		$ip = implode(':', $expandedParts);
		$hex = implode('', $expandedParts);
	}
	/**
	 * Check final IP
	 */
	if(filter_var($ip, FILTER_VALIDATE_IP) === false) {
		return false;
	}
	return strtolower(str_pad($hex, 32, '0', STR_PAD_LEFT));
}
/**
 * Returns IP for given hex representation, function reverse to ip2hex()
 *
 * @param string $hex
 * @param int $mode	6	- result IP will be in form of Ipv6<br>
 * 					4	- if possible, result will be in form of Ipv4, otherwise in form of IPv6<br>
 * 					10	- result will be array(IPv6, IPv4)
 *
 * @return array|bool|string
 */
function hex2ip ($hex, $mode = 6) {
	if (!$hex || strlen($hex) != 32) {
		return false;
	}
	$IPv4_range = false;
	if (preg_match('/^0{24}[0-9a-f]{8}$/', $hex)) {
		$IPv4_range = true;
	}
	if ($IPv4_range) {
		$hex = substr($hex, 24, 8);
		switch ($mode) {
			case 4:
				return	hexdec(substr($hex, 0, 2)).'.'.
						hexdec(substr($hex, 2, 2)).'.'.
						hexdec(substr($hex, 4, 2)).'.'.
						hexdec(substr($hex, 6, 2));
			case 10:
				$result = [];
				/**
				 * IPv6
				 */
				$result[] = '0000:0000:0000:0000:0000:0000:'.substr($hex, 0, 4).':'.substr($hex, 4, 4);
				/**
				 * IPv4
				 */
				$result[] =	hexdec(substr($hex, 0, 2)).'.'.
							hexdec(substr($hex, 2, 2)).'.'.
							hexdec(substr($hex, 4, 2)).'.'.
							hexdec(substr($hex, 6, 2));
				return $result;
			default:
				return '0000:0000:0000:0000:0000:0000:'.substr($hex, 0, 4).':'.substr($hex, 4, 4);
		}
	} else {
		$result =	substr($hex, 0, 4).':'.
					substr($hex, 4, 4).':'.
					substr($hex, 8, 4).':'.
					substr($hex, 12, 4).':'.
					substr($hex, 16, 4).':'.
					substr($hex, 20, 4).':'.
					substr($hex, 24, 4).':'.
					substr($hex, 28, 4);
		if ($mode == 10) {
			return [$result, false];
		} else {
			return $result;
		}
	}
}
/**
 * Check password strength
 *
 * @param	string	$password
 * @param	int		$min_length
 *
 * @return	int		In range [0..7]<br><br>
 * 					<b>0</b> - short password<br>
 * 					<b>1</b> - numbers<br>
 *  				<b>2</b> - numbers + letters<br>
 * 					<b>3</b> - numbers + letters in different registers<br>
 * 		 			<b>4</b> - numbers + letters in different registers + special symbol on usual keyboard +=/^ and others<br>
 * 					<b>5</b> - numbers + letters in different registers + special symbols (more than one)<br>
 * 					<b>6</b> - as 5, but + special symbol, which can't be found on usual keyboard or non-latin letter<br>
 * 					<b>7</b> - as 5, but + special symbols, which can't be found on usual keyboard or non-latin letter (more than one symbol)<br>
 */
function password_check ($password, $min_length = 4) {
	$password	= preg_replace('/\s+/', ' ', $password);
	$strength	= 0;
	if(strlen($password) >= $min_length) {
		if(preg_match('/[~!@#\$%\^&\*\(\)\-_=+\|\\/;:,\.\?\[\]\{\}]+/', $password, $match)) {
			$strength = 4;
			if (strlen(implode('', $match)) > 1) {
				++$strength;
			}
		} else {
			if(preg_match('/[A-Z]+/', $password)) {
				++$strength;
			}
			if(preg_match('/[a-z]+/', $password)) {
				++$strength;
			}
			if(preg_match('/[0-9]+/', $password)) {
				++$strength;
			}
		}
		if (preg_match('/[^[0-9a-z~!@#\$%\^&\*\(\)\-_=+\|\\/;:,\.\?\[\]\{\}]]+/i', $password, $match)) {
			++$strength;
			if (strlen(implode('', $match)) > 1) {
				++$strength;
			}
		}
	}
	return $strength;
}
/**
 * Generates passwords till 5th level of strength, 6-7 - only for humans:)
 *
 * @param	int		$length
 * @param	int		$strength	In range [1..5], but it must be smaller, than $length<br><br>
 * 								<b>1</b> - numbers<br>
 * 								<b>2</b> - numbers + letters<br>
 * 								<b>3</b> - numbers + letters in different registers<br>
 * 								<b>4</b> - numbers + letters in different registers + special symbol<br>
 * 								<b>5</b> - numbers + letters in different registers + special symbols (more than one)
 *
 * @return	string
 */
function password_generate ($length = 10, $strength = 5) {
	static $special = [
		'~', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '_',
		'=', '+', '|', '\\', '/', ';', ':', ',', '.', '?', '[', ']', '{', '}'
	];
	static $small, $capital;
	if ($length < 4) {
		$length		= 4;
	}
	if ($strength < 1) {
		$strength	= 1;
	} elseif ($strength > $length) {
		$strength	= $length;
	}
	if ($strength > 5) {
		$strength	= 5;
	}
	if (!isset($small)) {
		$small		= range('a', 'z');
	}
	if (!isset($capital)) {
		$capital	= range('A', 'Z');
	}
	$password	= [];
	$symbols	= range(0, 9);
	if ($strength > 5) {
		$strength	= 5;
	}
	if ($strength > $length) {
		$strength	= $length;
	}
	if ($strength > 3) {
		$symbols	= array_merge($symbols, $special);
	}
	if ($strength > 2) {
		$symbols	= array_merge($symbols, $capital);
	}
	if ($strength > 1) {
		$symbols	= array_merge($symbols, $small);
	}
	$size		= count($symbols)-1;
	while (true) {
		for ($i = 0; $i < $length; ++$i) {
			$password[]	= $symbols[rand(0, $size)];
		}
		shuffle($password);
		if (password_check(implode('', $password)) == $strength) {
			return implode('', $password);
		}
		$password	= [];
	}
	return '';
}
/**
 * Sending request of specified type to the specified host and path with specified data
 *
 * @param string $type	post/put/delete/etc.
 * @param string $host
 * @param string $host
 * @param string $path
 * @param array  $data
 *
 * @return bool|string
 */
function any_request ($type, $host, $path, $data) {
	if (!is_array($data) || empty($data)) {
		return false;
	}
	$host	= explode(':', $host);
	$socket = fsockopen($host[0], isset($host[1]) ? $host[1] : 80);
	if(!is_resource($socket)) {
		return false;
	}
	$data	= http_build_query($data);
	$type	= strtoupper($type);
	fwrite(
		$socket,
		"$type $path HTTP/1.1\r\n".
		'Host: '.implode(':', $host)."\r\n".
		"Content-Type: application/x-www-form-urlencoded\r\n".
		"Content-length:".strlen($data)."\r\n".
		"Accept:*/*\r\n".
		"Connection: Close\r\n".
		"User-agent: CleverStyle CMS\r\n\r\n".
		$data."\r\n\r\n"
	);
	unset($data);
	$return = explode("\r\n\r\n", stream_get_contents($socket), 2)[1];
	fclose($socket);
	return $return;
}
/**
 * Sending POST request to the specified host and path with specified data
 *
 * @param string $host
 * @param string $path
 * @param array  $data
 *
 * @return bool|string
 */
function post_request ($host, $path, $data) {
	return any_request('post', $host, $path, $data);
}
/**
 * Sending PUT request to the specified host and path with specified data
 *
 * @param string $host
 * @param string $path
 * @param array  $data
 *
 * @return bool|string
 */
function put_request ($host, $path, $data) {
	return any_request('put', $host, $path, $data);
}
/**
 * Sending DELETE request to the specified host and path with specified data
 *
 * @param string $host
 * @param string $path
 * @param array  $data
 *
 * @return bool|string
 */
function delete_request ($host, $path, $data) {
	return any_request('delete', $host, $path, $data);
}
/**
 * Sends header with string representation of http status code, for example "404 Not Found" for corresponding server protocol
 *
 * @param int $code Status code number
 *
 * @return null|string String representation of status code code
 */
function code_header ($code) {
	$string_code = null;
	switch ($code) {
		case 201:
			$string_code	= '201 Created';
		break;
		case 202:
			$string_code	= '202 Accepted';
		break;
		case 301:
			$string_code	= '301 Moved Permanently';
		break;
		case 302:
			$string_code	= '302 Found';
		break;
		case 303:
			$string_code	= '303 See Other';
		break;
		case 307:
			$string_code	= '307 Temporary Redirect';
		break;
		case 400:
			$string_code	= '400 Bad Request';
		break;
		case 403:
			$string_code	= '403 Forbidden';
		break;
		case 404:
			$string_code	= '404 Not Found';
		break;
		case 405:
			$string_code	= '405 Method Not Allowed';
		break;
		case 409:
			$string_code	= '409 Conflict';
		break;
		case 429:
			$string_code	= '429 Too Many Requests';
		break;
		case 500:
			$string_code	= '500 Internal Server Error';
		break;
		case 501:
			$string_code	= '501 Not Implemented';
		break;
		case 503:
			$string_code	= '503 Service Unavailable';
		break;
	}
	if ($string_code) {
		header("$_SERVER[SERVER_PROTOCOL] $string_code", true, (int)$code);
	}
	return $string_code;
}
/**
 * Bitwise XOR operation for 2 strings
 *
 * @param string $string1
 * @param string $string2
 *
 * @return string
 */
function xor_string ($string1, $string2) {
	$len1	= mb_strlen($string1);
	$len2	= mb_strlen($string2);
	if ($len2 > $len1) {
		list($string1, $string2, $len1, $len2) = [$string2, $string1, $len2, $len1];
	}
	for ($i = 0; $i < $len1; ++$i) {
		$pos = $i % $len2;
		$string1[$i] = chr(ord($string1[$i]) ^ ord($string2[$pos]));
	}
	return $string1;
}
/**
 * Checks whether string is an md5 hash
 *
 * @param string	$string
 *
 * @return bool
 */
function is_md5 ($string) {
	return is_string($string) && preg_match('/^[0-9a-z]{32}$/', $string);
}
/**
 * Checks associativity of array
 *
 * @param array	$array	Array to be checked
 *
 * @return bool
 */
function is_array_assoc ($array) {
	if (!is_array($array) || empty($array)) {
		return false;
	}
	$count = count($array);
	for ($i = 0; $i < $count; ++$i) {
		if (!isset($array[$i])) {
			return true;
		}
	}
	return false;
}
/**
 * Checks whether array is indexed or not
 *
 * @param array	$array	Array to be checked
 *
 * @return bool
 */
function is_array_indexed ($array) {
	if (!is_array($array) || empty($array)) {
		return false;
	}
	return !is_array_assoc($array);
}
/**
 * Works like <b>array_flip()</b> function, but is used when every item of array is not a string, but may be also array
 *
 * @param array			$array	At least one item must be array, some other items may be strings (or numbers)
 *
 * @return array|bool
 */
function array_flip_3d ($array) {
	if (!is_array($array)) {
		return false;
	}
	$result	= [];
	$size	= 0;
	foreach ($array as $values) {
		$size	= max($size, count((array)$values));
	}
	unset($values);
	foreach ($array as $key => $values) {
		for ($i = 0; $i < $size; ++$i) {
			if (is_array($values)) {
				if (isset($values[$i])) {
					$result[$i][$key] = $values[$i];
				}
			} else {
				$result[$i][$key] = $values;
			}
		}
	}
	return $result;
}
/**
 * Truncates text
 *
 * Cuts a string to the length of <i>$length</i> and replaces the last characters
 * with the ending if the text is longer than length.
 * Function from CakePHP
 *
 * @license Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * @param string	$text			String to truncate
 * @param int		$length			Length of returned string, including ellipsis
 * @param string	$ending			Ending to be appended to the trimmed string
 * @param bool		$exact			If <b>false</b>, $text will not be cut mid-word
 * @param bool		$considerHtml	If <b>true</b>, HTML tags would be handled correctly
 * @return string					Trimmed string
 */
function truncate ($text, $length = 1024, $ending = '...', $exact = false, $considerHtml = true) {
	$open_tags	= [];
	if ($considerHtml) {
		// if the plain text is shorter than the maximum length, return the whole text
		if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
			return $text;
		}
		// splits all html-tags to scanable lines
		preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
		$total_length	= mb_strlen($ending);
		$truncate		= '';
		foreach ($lines as $line_matchings) {
			// if there is any html-tag in this line, handle it and add it (uncounted) to the output
			if (!empty($line_matchings[1])) {
				// if it's an "empty element" with or without xhtml-conform closing slash (f.e. <br/>)
				if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|col|frame|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
					// do nothing
					// if tag is a closing tag (f.e. </b>)
				} else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
					// delete tag from $open_tags list
					$pos = array_search($tag_matchings[1], $open_tags);
					if ($pos !== false) {
						unset($open_tags[$pos]);
					}
					// if tag is an opening tag (f.e. <b>)
				} else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
					// add tag to the beginning of $open_tags list
					array_unshift($open_tags, mb_strtolower($tag_matchings[1]));
				}
				// add html-tag to $truncate'd text
				$truncate .= $line_matchings[1];
			}
			// calculate the length of the plain text part of the line; handle entities as one character
			$content_length = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
			if ($total_length + $content_length > $length) {
				// the number of characters which are left
				$left = $length - $total_length;
				$entities_length = 0;
				// search for html entities
				if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
					// calculate the real length of all entities in the legal range
					foreach ($entities[0] as $entity) {
						if ($entity[1] + 1 - $entities_length <= $left) {
							$left--;
							$entities_length += mb_strlen($entity[0]);
						} else {
							// no more characters left
							break;
						}
					}
				}
				$truncate .= mb_substr($line_matchings[2], 0, $left + $entities_length);
				// maximum length is reached, so get off the loop
				break;
			} else {
				$truncate		.= $line_matchings[2];
				$total_length	+= $content_length;
			}
			// if the maximum length is reached, get off the loop
			if($total_length >= $length) {
				break;
			}
		}
	} else {
		if (mb_strlen($text) <= $length) {
			return $text;
		} else {
			$truncate = mb_substr($text, 0, $length - mb_strlen($ending));
		}
	}
	// if the words shouldn't be cut in the middle...
	if (!$exact) {
		// ...search the last occurrence of a space...
		$spacepos = mb_strrpos($truncate, ' ');
		if (isset($spacepos)) {
			// ...and cut the text in this position
			$truncate = mb_substr($truncate, 0, $spacepos);
		}
	}
	// add the defined ending to the text
	$truncate .= $ending;
	if($considerHtml) {
		// close all unclosed html-tags
		foreach ($open_tags as $tag) {
			$truncate .= "</$tag>";
		}
	}
	return $truncate;
}
/**
 * Search for links inside html attributes
 *
 * @param string	$text
 *
 * @return string[]			Array of found links or empty array otherwise
 */
function find_links ($text) {
	preg_match_all('/"(http[s]?:\/\/.*)"/Uims', $text, $links);
	return $links[1] ?: [];
}
/**
 * Prepare string to use as url path
 *
 * @param string	$text
 *
 * @return string
 */
function path ($text) {
	$text	= preg_replace('/[\s\(\)\/\\#]+/', '_', $text);
	$text	= preg_replace('/_+/', '_', $text);
	return trim($text, '_');
}
/**
 * Prepare string to use in keywords meta tag
 *
 * @param string	$text
 *
 * @return string
 */
function keywords ($text) {
	return implode(
		', ',
		_trim(
			explode(
				' ',
				str_replace([',', '.', '!', '?', '-', '–', '&'], '', $text)
			)
		)
	);
}
/**
 * Prepare string to use in description meta tag
 *
 * @param string	$text
 *
 * @return string
 */
function description ($text) {
	return trim(str_replace(
		["\r\n", "\n", "\r", "&nbsp;"],
		' ',
		truncate(
			strip_tags($text),
			512,
			'...',
			false,
			false
		)
	));
}
/**
 * Returns of direct output of given function
 *
 * @param callable	$callback
 *
 * @return string
 */
function ob_wrapper ($callback) {
	ob_start();
	$callback();
	return ob_get_clean();
}
/**
 * Uppercase the first character of each word in a string.
 *
 * Works with utf8, before processing string will be transformed to lowercase, and then to ucwords.
 *
 * @param string	$str
 *
 * @return string
 */
function mb_ucwords ($str) {
	return mb_convert_case($str, MB_CASE_TITLE);
}
/**
 * Convert input to int type. Accepts arrays.
 *
 * @param mixed|mixed[]	$in
 *
 * @return int|int[]
 */
function _int ($in) {
	if (is_array($in)) {
		return array_map(
			function ($in) {
				return (int)$in;
			},
			$in
		);
	}
	return (int)$in;
}
/**
 * Convert input to float type. Accepts arrays.
 *
 * @param mixed|mixed[]	$in
 *
 * @return float|float[]
 */
function _float ($in) {
	if (is_array($in)) {
		return array_map(
			function ($in) {
				return (float)$in;
			},
			$in
		);
	}
	return (float)$in;
}
/**
 * Convert input to string type. Accepts arrays.
 *
 * @param mixed|mixed[]	$in
 *
 * @return string|string[]
 */
function _string ($in) {
	if (is_array($in)) {
		return array_map(
			function ($in) {
				return (string)$in;
			},
			$in
		);
	}
	return (string)$in;
}
/**
 * Convert input to array type. Accepts arrays.
 *
 * @param mixed|mixed[]	$in
 *
 * @return array|array[]
 */
function _array ($in) {
	if (is_array($in)) {
		return array_map(
			function ($in) {
				return (array)$in;
			},
			$in
		);
	}
	return (array)$in;
}
if (!function_exists('array_column')) {
	/**
	 * Returns the values from a single column of the input array, identified by the columnKey.
	 *
	 * Optionally, you may provide an indexKey to index the values in the returned array by the values from the indexKey column in the input array.
	 *
	 * @param array[]		$input		A multi-dimensional array (record set) from which to pull a column of values.
	 * @param int|string	$columnKey	The column of values to return. This value may be the integer key of the column you wish to retrieve,
	 * 									or it may be the string key name for an associative array.
	 * @param int|string	$indexKey	The column to use as the index/keys for the returned array.
	 * 									This value may be the integer key of the column, or it may be the string key name.
	 *
	 * @return mixed[]
	 */
	function array_column ($input, $columnKey, $indexKey = null) {
		if (!is_array($input)) {
			return false;
		}
		if ($indexKey === null) {
			foreach ($input as $i => &$in) {
				if (is_array($in) && isset($in[$columnKey])) {
					$in	= $in[$columnKey];
				} else {
					unset($input[$i]);
				}
			}
		} else {
			$result	= [];
			foreach ($input as $i => $in) {
				if (is_array($in) && isset($in[$columnKey])) {
					if (isset($in[$indexKey])) {
						$result[$in[$indexKey]]	= $in[$columnKey];
					} else {
						$result[]	= $in[$columnKey];
					}
					unset($input[$i]);
				}
			}
			$input	= &$result;
		}
		return $input;
	}
}
