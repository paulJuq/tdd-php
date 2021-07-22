<?php

use PHPUnit\Framework\TestCase;
use Twitter\Http\Request;

use function PHPUnit\Framework\assertNull;

class RequestTest extends TestCase
{
    /** @test */
    public function we_can_instantiate_a_request()
    {
        $request = new Request([
            'author' => "Paul", 
            'content' => "Contenu test"
        ]);
        
        $this->assertEquals("Paul", $request->get('author'));
        $this->assertEquals("Contenu test", $request->get('content'));
        $this->assertNull($request->get('inexistant'));
    }
}