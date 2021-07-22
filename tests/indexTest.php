<?php

use PHPUnit\Framework\TestCase;
use Twitter\Controller\HelloController;

class indexTest extends TestCase
{
	protected HelloController $controller;

	protected function setUp(): void
	{
		$this->controller = new HelloController;
	}

	public function test_index_shows_client_name()
	{

		$_GET['name'] = 'Paul';

		$response = $this->controller->hello();

		$this->assertEquals('Bonjour Paul', $response->getContent());
		$this->assertEquals(200, $response->getResponseStatusCode());

		$contentHeader = $response->getHeaders()['Content-Type'] ?? null;

		$this->assertEquals('text/html', $contentHeader);
	}

	public function test_it_works_even_if_there_is_no_name_in_get()
	{
		$_GET = [];

		$response = $this->controller->hello();

		$this->assertEquals('Bonjour tout le monde', $response->getContent());
	}
}
