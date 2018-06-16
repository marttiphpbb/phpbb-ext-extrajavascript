<?php
/**
* phpBB Extension - marttiphpbb Extra Javascript
* @copyright (c) 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\extrajavascript\acp;

use marttiphpbb\extrajavascript\util\cnst;
use marttiphpbb\extrajavascript\model\extrajavascript_directory;

class main_module
{
	public $u_action;

	function main($id, $mode)
	{
		global $phpbb_container;

		$request = $phpbb_container->get('request');
		$template = $phpbb_container->get('template');
		$language = $phpbb_container->get('language');
		$ext_manager = $phpbb_container->get('ext.manager');
		$store = $phpbb_container->get('marttiphpbb.extrajavascript.service.store');

		$language->add_lang('acp', cnst::FOLDER);
		add_form_key(cnst::FOLDER);

		switch($mode)
		{
			case 'edit':

				$this->tpl_name = 'edit';
				$this->page_title = $language->lang(cnst::L_ACP . '_EDIT');

				$request_file_id = $request->variable('file_id', '', true);
				$files = $store->get_all_files();

				if (!$files)
				{
					$u_files = str_replace('mode=edit', 'mode=files', $this->u_action);
					trigger_error($language->lang(cnst::L_ACP . '_NO_FILES', $u_files));
				}

				if ($request_file_id === '')
				{
					end($files);
					$request_file_id = key($files);
				}

				if (!isset($files[$request_file_id]))
				{
					trigger_error($language->lang(
						cnst::L_ACP . '_FILE_DOES_NOT_EXIST',
							$request_file_id),
								E_USER_WARNING);
				}

				if ($request->is_set_post('save'))
				{
					$file_id = $request->variable('file_id', '');
					$file_content = $request->variable('file_content', '');
					$file_content = html_entity_decode($file_content, ENT_COMPAT | ENT_HTML5);
					$script_names = $request->variable('script_names', '');
					$script_names = strtolower($script_names);

					if (confirm_box(true))
					{
						$script_names = str_replace([' ', '.php'], '', $script_names);
						$store->set_file($file_id, crc32($file_content), $script_names, $file_content);
						trigger_error(sprintf($language->lang(cnst::L_ACP . '_FILE_SAVED'), $file) . adm_back_link($this->u_action . '&amp;filename=' . $file));
					}

					$s_hidden_fields = [
						'file_id'		=> $file_id,
						'file_content' 	=> htmlentities($file_content, ENT_COMPAT | ENT_HTML5),
						'script_names'	=> $script_names,
						'mode'			=> 'edit',
						'save'			=> 1,
					];

					confirm_box(false, sprintf($language->lang(cnst::L_ACP . '_SAVE_CONFIRM'), $file_id), build_hidden_fields($s_hidden_fields));
				}

				foreach ($files as $file_id => $data)
				{
					$template->assign_block_vars('files', [
						'S_SELECTED'	=> $request_file_id === $file_id,
						'NAME'			=> $file_id,
					]);
				}

				$codemirror_enabled = $ext_manager->is_enabled('marttiphpbb/codemirror');

				if ($codemirror_enabled)
				{
					$load = $phpbb_container->get('marttiphpbb.codemirror.load');
					$load->set_mode('javascript');
				}

				$query = [];
				parse_str(parse_url(html_entity_decode($this->u_action), PHP_URL_QUERY), $query);

				$s_hidden_fields = [
					'file_id'	=> $request_file_id,
				];

				$template->assign_vars([
					'S_HIDDEN_FIELDS'		=> build_hidden_fields($s_hidden_fields),
					'FILE_NAME'				=> $request_file_id,
					'SCRIPT_NAMES'			=> $files[$request_file_id]['script_names'],
					'FILE_CONTENT'			=> $files[$request_file_id]['content'],
					'S_HIDDEN_FIELDS_GET'	=> build_hidden_fields($query),
				]);

			break;

			case 'files':

				$this->tpl_name = 'files';
				$this->page_title = $language->lang('ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_FILES');

				$files = $store->get_all_files();

				$new_file = $request->variable('new_file', '');
				$file_to_delete = array_keys($request->variable('delete', array('' => '')));
				$file_to_delete = count($file_to_delete) ? $file_to_delete[0] : false;

				if ($request->is_set_post('create'))
				{
					if (!check_form_key(cnst::FOLDER))
					{
						trigger_error('FORM_INVALID');
					}

					if (!$new_file)
					{
						trigger_error(
							$language->lang('ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_FILE_NAME_EMPTY') .
								adm_back_link($this->u_action),
									E_USER_WARNING);
					}

					if (in_array($new_file, array_keys($files)))
					{
						trigger_error(sprintf(
							$language->lang('ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_FILE_NAME_ALREADY_EXISTS'),
								$new_file) .
									adm_back_link($this->u_action), E_USER_WARNING);
					}

					if (preg_match('/^[a-z][a-z0-9-]*[a-z0-9]$/', $new_file) !== 1
						|| strpos($new_file, '--') !== false)
					{
						trigger_error(sprintf(
							$language->lang('ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_FILE_NAME_INVALID_FORMAT'),
								$new_file) .
									adm_back_link($this->u_action), E_USER_WARNING);
					}

					$store->set_file($new_file, crc32(''), '', '');

					$u_edit = str_replace('mode=files', 'mode=edit&file_id=' . $new_file, $this->u_action);
					redirect($u_edit);
				}

				if ($request->is_set_post('delete'))
				{
					if (!in_array($file_to_delete, array_keys($files)))
					{
						trigger_error(sprintf(
							$language->lang('ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_FILE_DOES_NOT_EXIST'),
								$file_to_delete) . adm_back_link($this->u_action),
									E_USER_WARNING);
					}

					if (confirm_box(true))
					{
						$store->delete_file($file_to_delete);

						trigger_error(sprintf(
							$language->lang('ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_FILE_DELETED'),
								$file_to_delete) . adm_back_link($this->u_action));
					}

					$s_hidden_fields = [
						'mode'		=> 'files',
						'delete'	=> [$file_to_delete => 1],
					];

					confirm_box(false,
						sprintf($language->lang('ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_FILE_DELETE_CONFIRM'),
							$file_to_delete), build_hidden_fields($s_hidden_fields));
				}

				foreach ($files as $file_id => $d)
				{
					$template->assign_block_vars('files', [
						'NAME'				=> $file_id,
						'U_EDIT'			=> str_replace('mode=files', 'mode=edit&file_id=' . $file_id, $this->u_action),
						'SIZE'				=> strlen($d['content']),
						'DELETE_FILE_NAME'	=> sprintf($language->lang('ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_FILE_DELETE_NAME'), $file_id),
					]);
				}

			break;
		}

		$template->assign_var('U_ACTION', $this->u_action);
	}
}
