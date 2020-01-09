<?php
/**
* phpBB Extension - marttiphpbb Extra Javascript
* @copyright (c) 2018 - 2020 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\extrajavascript\migrations;
use marttiphpbb\extrajavascript\util\cnst;
use marttiphpbb\extrajavascript\service\store;

class v_0_1_0 extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return [
			'\phpbb\db\migration\data\v32x\v321',
		];
	}

	public function update_data()
	{
		$data = [
			'files' 	=> [],
			'load'		=> [],
		];

		return [
			['config_text.add', [store::KEY, serialize($data)]],

			['module.add', [
				'acp',
				'ACP_CAT_DOT_MODS',
				cnst::L_ACP,
			]],

			['module.add', [
				'acp',
				cnst::L_ACP,
				[
					'module_basename'	=> '\marttiphpbb\extrajavascript\acp\main_module',
					'modes'				=> [
						'files',
						'edit',
					],
				],
			]],
		];
	}
}
