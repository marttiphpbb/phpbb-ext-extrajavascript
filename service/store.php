<?php

/**
* phpBB Extension - marttiphpbb Extra Javascript
* @copyright (c) 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\extrajavascript\service;

use phpbb\config\db_text as config_text;
use phpbb\cache\driver\driver_interface as cache;
use marttiphpbb\extrajavascript\util\cnst;

class store
{
	const KEY = cnst::ID;
	const CACHE_KEY = '_' . self::KEY;

	protected $config_text;
	protected $cache;
	protected $data = [];

	public function __construct(config_text $config_text, cache $cache)
	{
		$this->config_text = $config_text;
		$this->cache = $cache;
	}

	private function load()
	{
		if ($this->data)
		{
			return;
		}

		$this->data = $this->cache->get(self::CACHE_KEY);

		if ($this->data)
		{
			return;
		}

		$data = $this->config_text->get(self::KEY);

		if (!$data)
		{
			$this->data = [
				'files' 	=> [],
				'load'		=> [],
			];

			return;
		}

		$this->data = unserialize($data);
		$this->cache->put(self::CACHE_KEY, $this->data);
	}

	private function write()
	{
		$this->config_text->set(self::KEY, serialize($this->data));
		$this->cache->put(self::CACHE_KEY, $this->data);
	}

	public function get_file_content(string $id, string $version):string
	{
		$this->load();
		$file = $this->data['files'][$id];
		if ($file['version'] === $version)
		{
			return $file['content'];
		}
		return  '';
	}

	public function set_file(string $id, string $version, string $script_names, string $content)
	{
		$this->load();
		$this->data['files'][$id] = [
			'content'	=> $content,
			'version'	=> $version,
			'script_names' => $script_names,
		];
		$this->refresh_script_names();
	}

	public function get_all_files():array
	{
		$this->load();
		return $this->data['files'];
	}

	public function delete_file(string $id)
	{
		$this->load();
		unset($this->data['files'][$id]);
		$this->refresh_script_names();
	}

	public function get_load_files(string $script_name):array
	{
		$this->load();
		return $this->data['load'][$script_name] ?? [];
	}

	public function refresh_script_names()
	{
		$this->load();

		$this->data['load'] = [];

		foreach ($this->data['files'] as $id => $ary)
		{
			$script_names = explode(',', $ary['script_names']);

			foreach ($script_names as $script_name)
			{
				$this->data['load'][$script_name][$id] = $ary['version'];
			}
		}

		$this->write();
	}
}
