<?php
/**
 * @package		CleverStyle CMS
 * @author		Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright	Copyright (c) 2011-2014, Nazar Mokrynskyi
 * @license		MIT License, see license.txt
 */
namespace	cs;
use			h;
/**
 * Core class.
 * Provides loading of base system configuration, API requests sending
 */
class Core {
	use Singleton;

	protected			$constructed		= false,	//Is object constructed
						$config				= [];
	/**
	 * Loading of base system configuration, creating of missing directories
	 */
	protected function construct () {
		if (!file_exists(CONFIG.'/main.json')) {
			error_code(500);
			Page::instance()->error(
				h::p('Config file not found, is system installed properly?').
				h::a(
					'How to install CleverStyle CMS',
					[
						'href'	=> 'https://github.com/nazar-pc/CleverStyle-CMS/wiki/Installation'
					]
				)
			);
			exit;
		}
		$this->config	= file_get_json_nocomments(CONFIG.'/main.json');
		_include_once(CONFIG.'/main.php', false);
		defined('DEBUG') || define('DEBUG', false);
		define('DOMAIN', $this->config['domain']);
		date_default_timezone_set($this->config['timezone']);
		if ($clangs = Cache::instance()->{'languages/clangs'}) {
			if (is_array($clangs) && !empty($clangs)) {
				$clang	= explode('/', trim($_SERVER['REQUEST_URI'], '/'), 2)[0];
				if (in_array($clang, $clangs)) {
					$this->set('language', array_flip($clangs)[$clang]);
					define('FIXED_LANGUAGE', true);
				}
				unset($clang);
			}
		}
		unset($clangs);
		if (!is_dir(STORAGE)) {
			@mkdir(STORAGE, 0755);
			file_put_contents(
				STORAGE.'/.htaccess',
				'Allow From All'
			);
		}
		if (!is_dir(CACHE)) {
			@mkdir(CACHE, 0700);
		}
		if (!is_dir(PCACHE)) {
			@mkdir(PCACHE, 0755);
			file_put_contents(
				PCACHE.'/.htaccess',
				'<FilesMatch "\.(css|js)$">
	Allow From All
</FilesMatch>
<ifModule mod_expires.c>
	ExpiresActive On
	ExpiresDefault "access plus 1 month"
</ifModule>
<ifModule mod_headers.c>
	Header set Cache-Control "max-age=2592000, public"
</ifModule>
AddEncoding gzip .js
AddEncoding gzip .css
'
			);
		}
		if (!is_dir(LOGS)) {
			@mkdir(LOGS, 0700);
		}
		if (!is_dir(TEMP)) {
			@mkdir(TEMP, 0755);
			file_put_contents(
				TEMP.'/.htaccess',
				'Allow From All'
			);
		}
		if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') === 0) {
			$_POST		= _json_decode(@file_get_contents('php://input')) ?: [];
			$_REQUEST	= array_merge($_REQUEST, $_POST);
		} elseif (in_array(strtolower($_SERVER['REQUEST_METHOD']), ['head', 'put', 'delete'])) {
			if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/x-www-form-urlencoded') === 0) {
				@parse_str(file_get_contents('php://input'), $_POST);
				$_REQUEST	= array_merge($_REQUEST, $_POST);
			}
		}
		$this->constructed	= true;
	}
	/**
	 * Getting of base configuration parameter
	 *
	 * @param string		$item
	 *
	 * @return bool|string
	 */
	function get ($item) {
		return isset($this->config[$item]) ? $this->config[$item] : false;
	}
	/**
	 * Setting of base configuration parameter (available only at object construction)
	 *
	 * @param string	$item
	 * @param mixed		$value
	 */
	function set ($item, $value) {
		if (!$this->constructed) {
			$this->config[$item] = $value;
		}
	}
	/**
	 * Getting of base configuration parameter
	 *
	 * @param string		$item
	 *
	 * @return bool|string
	 */
	function __get ($item) {
		return $this->get($item);
	}
	/**
	 * Setting of base configuration parameter (available only at object construction)
	 *
	 * @param string	$item
	 * @param mixed		$value
	 */
	function __set ($item, $value) {
		$this->set($item, $value);
	}
	/**
	 * Sending system api request to all mirrors
	 *
	 * @param string	$path	Path for api request, for example <i>System/admin/setcookie<i>, where
	 * 							<i>System</b> - module name, <i>admin/setcookie</b> - path to action file in current module api structure
	 * @param mixed		$data	Any type of data, will be accessible through <i>$_POST['data']</b>
	 *
	 * @return array	Array <i>[mirror_url => result]</b> in case of successful connection, <i>false</b> on failure
	 */
	function api_request ($path, $data = '') {
		$Config	= Config::instance(true);
		$result	= [];
		if ($Config && $Config->server['mirrors']['count'] > 1) {
			foreach ($Config->server['mirrors']['http'] as $domain) {
				if (!($domain == $Config->server['host'] && $Config->server['protocol'] == 'http')) {
					$result["http://$domain"] = $this->send("http://$domain/api/$path", $data);
				}
			}
			foreach ($Config->server['mirrors']['https'] as $domain) {
				if (!($domain != $Config->server['host'] && $Config->server['protocol'] == 'https')) {
					$result["https://$domain"] = $this->send("https://$domain/api/$path", $data);
				}
			}
		}
		return $result;
	}
	/**
	 * Sending of api request to the specified host
	 *
	 * @param string	$url	With prefix <i>https://</b> (<i>http://</b> can be missed), and (if necessary) with port address
	 * @param mixed		$data	Any type of data, will be accessible through <i>$_POST['data']</b>
	 *
	 * @return bool|string		Result or <i>false</i> at error
	 */
	protected function send ($url, $data) {
		if (!Config::instance(true)) {
			return false;
		}
		$Key				= Key::instance();
		$protocol			= 'http';
		if (mb_strpos($url, '://') !== false) {
			list($protocol,	$url) = explode('://', $url);
		}
		$database			= Config::instance()->module('System')->db('keys');
		$key				= $Key->generate($database);
		$url				.= "/$key";
		$Key->add(
			$database,
			$key,
			[
				'url' => $url
			],
			time()+30
		);
		list($host, $url)	= explode('/', $url, 2);
		$host				= explode(':', $host);
		$socket				= fsockopen($host[0], isset($host[1]) ? $host[1] : $protocol == 'http' ? 80 : 443, $errno, $errstr);
		$host				= implode(':', $host);
		if(!is_resource($socket)) {
			trigger_error("#$errno $errstr", E_USER_WARNING);
			return false;
		}
		$data = 'data='.urlencode(json_encode($data));
		time_limit_pause();
		fwrite(
			$socket,
			"POST /$url HTTP/1.1\r\n".
			"Host: $host\r\n".
			"Content-Type: application/x-www-form-urlencoded\r\n".
			"Content-length:".strlen($data)."\r\n".
			"Accept:*/*\r\n".
			"User-agent: CleverStyle CMS\r\n\r\n".
			"$data\r\n\r\n"
		);
		$return = explode("\r\n\r\n", stream_get_contents($socket), 2);
		time_limit_pause(false);
		fclose($socket);
		return $return[1];
	}
}
