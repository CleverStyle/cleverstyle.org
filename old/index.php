<?php
$lang = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2));
if ($lang == 'ua') {
	include 'ua.html';
} elseif ($lang == 'ru') {
	include 'ru.html';
} else {
	include 'en.html';
}
