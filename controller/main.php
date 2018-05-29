<?php

/**
* phpBB Extension - marttiphpbb Extra Javascript
* @copyright (c) 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\extrajavascript\controller;

use phpbb\request\request;
use phpbb\controller\helper;
use marttiphpbb\extrajavascript\service\store;

use Symfony\Component\HttpFoundation\Response;

class main
{
	/** @var store */
	protected $store;

	/** @var request */
	protected $request;

	/**
	* @param store $store
	*/
	public function __construct(
		store $store,
		request $request
	)
	{
		$this->store = $store;
		$this->request = $request;
	}

	/**
	* @param string   $id
	* @return Response
	*/
	public function render(string $id):Response
	{
		$version = $this->request->variable('v', '');

		$response = new Response();
		$response->headers->set('Content-Type', 'application/javascript');

		if (!$version)
		{
			$response->setStatusCode(Response::HTTP_NOT_FOUND);
			return $response;
		}

		$content = $this->store->get_file_content($id, $version);

		if (!$content)
		{
			$response->setStatusCode(Response::HTTP_NOT_FOUND);
			return $response;
		}

		$response->setStatusCode(Response::HTTP_OK);
		$response->setContent($content);
		$response->setMaxAge(31536000);
		$response->setPublic();
		return $response;
	}
}
