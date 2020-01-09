<?php

/**
* phpBB Extension - marttiphpbb Extra Javascript
* @copyright (c) 2018 - 2020 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\extrajavascript\controller;

use phpbb\request\request;
use marttiphpbb\extrajavascript\service\store;
use Symfony\Component\HttpFoundation\Response;

class main
{
	protected $store;
	protected $request;

	public function __construct(
		store $store,
		request $request
	)
	{
		$this->store = $store;
		$this->request = $request;
	}

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
