<?php
/**
* phpBB Extension - marttiphpbb Extra Javascript
* @copyright (c) 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\extrajavascript\event;

use phpbb\event\data as event;
use phpbb\controller\helper;
use marttiphpbb\extrajavascript\service\store;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	/** @var store */
	protected $store;

	/** @var helper */
	protected $helper;

	/**
	 * @param store
	*/
	public function __construct(helper $helper, store $store)
	{
		$this->helper = $helper;
		$this->store = $store;
	}

	static public function getSubscribedEvents()
	{
		return [
			'core.twig_environment_render_template_before'
				=> 'core_twig_environment_render_template_before',
		];
	}

	public function core_twig_environment_render_template_before(event $event)
	{
		$context = $event['context'];

		if (!isset($context['SCRIPT_NAME']))
		{
			return;
		}

		$file_ids = $this->store->get_load_files($context['SCRIPT_NAME']);
		$files = [];

		foreach ($file_ids as $file_id)
		{
			$params = [
				'id'		=> $file_id,
				'v'			=> $this->store->get_file_version($file_id),	
			];

			$files[] = $this->helper->route('marttiphpbb_extrajavascript_render_controller', $params);
		}

		var_dump($files);

		$context['marttiphpbb_extrajavascript'] = $files;
		$event['context'] = $context;
	}		
}
