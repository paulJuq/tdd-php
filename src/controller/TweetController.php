<?php 

namespace Twitter\Controller;

use Twitter\Http\Request;
use Twitter\Http\Response;
use Twitter\Model\TweetModel;

class TweetController{

	protected TweetModel $model;
	protected array $requiredFields = ['author', 'content'];  

	public function __construct(TweetModel $model)
	{
		$this->model = $model; 
	}

	public function saveTweet(Request $request) : Response
	{
		if ($response = $this->validateFields($request)) {
			return $response; 
		}

		$this->model->save($request->get('author'), $request->get('content')); 

		return new Response('', 302, ['Location' => '/']); 
	}

	protected function validateFields(Request $request) : ?Response
	{
		$invalidFields=[]; 

		foreach ($this->requiredFields as $field) {
			# code...

			if(!$request->get($field)){
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