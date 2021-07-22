<?php 

use PHPUnit\Framework\TestCase;
use Twitter\Controller\TweetController;
use Twitter\Http\Request;
use Twitter\Model\TweetModel;
use Twitter\Validation\RequestValidator;

class TweetControllerTest extends TestCase{

	protected TweetController $controller;
	protected PDO $pdo; 
	protected TweetModel $tweetModel; 
	
	protected function setUp(): void
	{

		$this->pdo = new PDO('mysql:host=localhost;dbname=live_test;charset=utf8', 'root', '', [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		]);

		$this->pdo->query('DELETE FROM tweet');

		$this->tweetModel = new TweetModel($this->pdo); 

		$this->controller = new TweetController(
			$this->tweetModel, 
			new RequestValidator
		); 
	}

	public function test_a_user_can_save_a_tweet(){

		$request = new Request([
			'author' => 'Paul', 
			'content' => 'Mon premier tweet'
		]); 

		$response = $this->controller->saveTweet($request); 

		$this->assertEquals(302, $response->getResponseStatusCode());

		$this->assertArrayHasKey('Location', $response->getHeaders());

		$this->assertEquals('/', $response->getHeaders()['Location']);

		$result = $this->pdo->query('SELECT t.* FROM tweet AS t');

		$this->assertEquals(1, $result->rowCount()); 

		$data = $result->fetch();

		$this->assertEquals('Paul', $data['author']);

		$this->assertEquals('Mon premier tweet', $data['content']);
	}
	
	/** 
	 * @test
	 * @dataProvider missingFields 
	 * */
	public function test_it_cant_save_a_tweet_if_fields_are_missing($postData, $errorMessage)
	{
		$request = new Request($postData);
		
		$response = $this->controller->saveTweet($request); 
		
		$this->assertEquals(400, $response->getResponseStatusCode()); 
		$this->assertEquals($errorMessage, $response->getContent()); 
	}
	
	// Data provider : le test utilisant ce data provider va boucler sur chq élément du grand tableau et utiliser ses sous éléments comme paramètre pour le test appelé. 
	public function missingFields(){
		return [
			[
				['content' => "Tweet de test"],
				"Le champ author est manquant",
			],
			[
				['author' => 'Paul'], 
				"Le champ content est manquant",
			],
			[
				[], 
				"Les champs author, content sont manquants",
			],
		];
	}

}
