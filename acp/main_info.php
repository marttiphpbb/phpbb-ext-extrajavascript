<?php
/**
* phpBB Extension - marttiphpbb Extra Javascript
* @copyright (c) 2018 - 2020 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\extrajavascript\acp;

use marttiphpbb\extrajavascript\util\cnst;

class main_info
{
	function module():array
	{
		return [
			'filename'	=> '\marttiphpbb\extrajavascript\acp\main_module',
			'title'		=> cnst::L_ACP,
			'modes'		=> [
				'files'	=> [
					'title'	=> cnst::L_ACP . '_FILES',
					'auth'	=> 'ext_marttiphpbb/extrajavascript && acl_a_board',
					'cat'	=> [cnst::L_ACP],
				],
				'edit'	=> [
					'title'	=> cnst::L_ACP . '_EDIT',
					'auth'	=> 'ext_marttiphpbb/extrajavascript && acl_a_board',
					'cat'	=> [cnst::L_ACP],
				],
			],
		];
	}
}
