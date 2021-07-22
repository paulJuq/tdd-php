<?php 

namespace Twitter\Controller; 
use Twitter\Http\Response;
use Twitter\Model\TweetModel;

class TweetController{

	protected TweetModel $model;
	protected array $requiredFields = ['author', 'content'];  

	public function __construct(TweetModel $model)
	{
		$this->model = $model; 
	}

	public function saveTweet() : Response
	{
		if ($response = $this->validateFields()) {
			return $response; 
		}

		$this->model->save($_POST['author'], $_POST['content']); 

		return new Response('', 302, ['Location' => '/']); 
	}

	protected function validateFields() : ?Response
	{
		$invalidFields=[]; 

		foreach ($this->requiredFields as $field) {
			# code...
			if(empty($_POST[$field])){
				$invalidFields[] = $field;  
			}
		}	

		if(empty($invalidFields)){
			return null;
		}

		if(count($invalidFields) ===  1){
			$field = $invalidFields[0]; 
			return new Response("Le champ $field est manquant", 400); 
		}

		return new Response(
			sprintf('Les champs %s sont manquants', implode(', ', $invalidFields)), 
			400
		);

	}
}