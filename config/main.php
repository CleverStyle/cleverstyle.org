<?php
/**
 * @package		CleverStyle CMS
 * @author		Nazar Mokrynskyi <nazar@mokrynskyi.com>
 * @copyright	Copyright (c) 2011-2013, Nazar Mokrynskyi
 * @license		MIT License, see license.txt
 */
namespace	cs;

define('DEBUG',				false);
define('XHTML_TAGS_STYLE',	false);
!defined('FIXED_LANGUAGE') && define('FIXED_LANGUAGE',	false);			//If true - language can't be changed, it can be useful if there are several domains,
																		//every of which must work with fixed language (en.domain.com, ru.domain.com, de.domain.com)
define('CS_ERROR_HANDLER', false);										//Special error handler of CleverStyle CMS