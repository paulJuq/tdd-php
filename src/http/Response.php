<?php 

namespace Twitter\Http;

class Response {
	protected string $content = ''; 
	protected array $headers = []; 
	protected int $responseStatusCode = 200 ;

	public function __construct(string $content = '', int $responseStatusCode = 200, array $headers = ['Content-Type'=>'text/html']){
		$this->content = $content; 
		$this->responseStatusCode = $responseStatusCode; 
		$this->headers = $headers; 
	} 

	public function getContent() : string
	{
		return $this->content; 
	}

	public function setContent(string $content) : self
	{
		$this->content = $content; 
		return $this; 
	}

	public function getHeaders() : array
	{
		return $this->headers; 
	}

	public function setHeaders(array $headers) : self
	{
		$this->headers = $headers; 
		return $this; 
	}

	public function getResponseStatusCode() : int
	{
		return $this->responseStatusCode; 
	}

	public function setResponseStatusCode(int $responseStatusCode) : self
	{
		$this->responseStatusCode = $responseStatusCode; 
		return $this; 
	}

	public function send(){
		foreach ($this->headers as $head => $value) {
					header($head . ': ' . $value); 				# code...
				}		

		http_response_code($this->responseStatusCode);

		echo $this->content; 
	}


}

