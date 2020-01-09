<?php

/**
* phpBB Extension - marttiphpbb Extra Javascript
* @copyright (c) 2018 - 2020 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

$lang = array_merge($lang, [

	'ACP_MARTTIPHPBB_EXTRAJAVASCRIPT'					=> 'Extra Javascript',
	'ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_EDIT'				=> 'Edit',
	'ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_FILES'				=> 'Files',
]);
