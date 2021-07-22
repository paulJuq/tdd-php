<?php 

use PHPUnit\Framework\TestCase;
use Twitter\Controller\TweetController;
use Twitter\Model\TweetModel;

class TweetControllerTest extends TestCase{

	protected TweetController $controller;
	protected PDO $pdo; 
	protected TweetModel $tweetModel; 
	
	protected function setUp(): void
	{

		$_POST = []; 

		$this->pdo = new PDO('mysql:host=localhost;dbname=live_test;charset=utf8', 'root', '', [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		]);

		$this->pdo->query('DELETE FROM tweet');

		$this->tweetModel = new TweetModel($this->pdo); 

		$this->controller = new TweetController($this->tweetModel); 
	}

	public function test_a_user_can_save_a_tweet(){

		$_POST['author'] = 'Paul'; 

		$_POST['content'] = 'Mon premier tweet'; 

		$response = $this->controller->saveTweet(); 

		$this->assertEquals(302, $response->getResponseStatusCode());

		$this->assertArrayHasKey('Location', $response->getHeaders());

		$this->assertEquals('/', $response->getHeaders()['Location']);

		$result = $this->pdo->query('SELECT t.* FROM tweet AS t');

		$this->assertEquals(1, $result->rowCount()); 

		$data = $result->fetch();

		$this->assertEquals('Paul', $data['author']);

		$this->assertEquals('Mon premier tweet', $data['content']);
	}

	public function test_it_cant_save_a_tweet_without_author()
	{
		// Etant donné qu'on a bien un content dans le post mais pas d'author
		$_POST['content'] = "Tweet de test";
		
		//Quand j'appelle mon tweet controller
	
		$response = $this->controller->saveTweet(); 

		// Alors la réponse doit avoir un statut 400
		// Et le contenu de la réponse devrait être "Le champ author est manquant"
		
		$this->assertEquals(400, $response->getResponseStatusCode()); 
		$this->assertEquals("Le champ author est manquant", $response->getContent()); 
	}
	
	public function test_it_cant_save_a_tweet_without_content()
	{
		// Etant donné qu'on a bien un author dans le post mais pas de content
		$_POST['author'] = "Paul";
		
		//Quand j'appelle mon tweet controller
	
		$response = $this->controller->saveTweet(); 

		// Alors la réponse doit avoir un statut 400
		// Et le contenu de la réponse devrait être "Le champ content est manquant"
		
		$this->assertEquals(400, $response->getResponseStatusCode()); 
		$this->assertEquals("Le champ content est manquant", $response->getContent()); 
	}

	public function test_it_cant_save_a_tweet_without_author_and_content()
	{
		// Etant donné que l'on a rien du tout 
		
		//Quand j'appelle mon tweet controller
	
		$response = $this->controller->saveTweet(); 

		// Alors la réponse doit avoir un statut 400
		// Et le contenu de la réponse devrait être "Les champs authors, content sont manquants"
		
		$this->assertEquals(400, $response->getResponseStatusCode()); 
		$this->assertEquals("Les champs author, content sont manquants", $response->getContent()); 
	}
}