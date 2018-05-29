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

	/** @var config_text */
	private $config_text;

	/** @var cache */
	private $cache;
	
	/** @var array */
	private $data = [];

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
		
		$this->data = unserialize($this->config_text->get(self::KEY));
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

	public function get_file_version(string $id):string 
	{
		return $this->data['files'][$id]['version'] ?? '';
	}

	public function set_file(string $id, string $version, string $script_names, string $content)
	{
		$this->load();
		$this->data['files'][$id] = [
			'content'	=> $content,
			'version'	=> $version,
			'script_names' => $script_names,
		];
		$this->write();
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

		foreach($this->data['load'] as $script_name => $id_ary)
		{
			foreach ($id_ary as $key => $n)
			{
				if ($n === $id)
				{
					unset($this->data['load'][$script_name][$key]);
				}
			}
		}

		$this->write();
	}

	public function get_load_files(string $script_name):array
	{
		$this->load();
		return $this->data['load'][$script_name] ?? [];
	}

	public function set_script_names(string $file_id, array $script_names)
	{
		$this->load();

		foreach ($script_names as $script_name)
		{
			if (isset($this->data['load'][$script_name]))
			{
				foreach($this->data['load'][$script_name] as $f_id)
				{
					if ($f_id === $file_id)
					{
						// already stored.
						continue;
					}
				}

				array_push($this->data['load'][$script_name], $file_id);

				continue;
			}

			$this->data['load'][$script_name] = [$file_id];
		}

		// cleanup
		foreach($this->data['load'] as $script_name => $file_ids)
		{
			if (in_array($file_id, $file_ids))
			{
				if (!in_array($script_name, $script_names))
				{
					$key = array_search($file_id, $file_ids);
					unset($this->data['load'][$script_name][$key]);
				}
			}
		}

		$this->write();
	}
}
