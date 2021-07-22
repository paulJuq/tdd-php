<?php 
namespace Twitter\Model; 
use PDO; 

class TweetModel
{
	protected PDO $pdo; 

	public function __construct(PDO $pdo)
	{
		$this->pdo = $pdo; 
	}

	public function save(string $author, string $content)
	{
		$query = $this->pdo->prepare('INSERT INTO tweet SET content=:content, author=:author, created_at= NOW()'); 

		$query->execute([
			'content' => $content, 
			'author' => $author,
		]);
	}
}