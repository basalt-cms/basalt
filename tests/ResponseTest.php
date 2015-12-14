<?php

class ResponseTest extends PHPUnit_Framework_TestCase
{
    public function testBland()
    {
        $response = \Basalt\Http\Response::blank();

        $this->assertInstanceOf('\\Basalt\\Http\\Response', $response);
    }

    public function testGetBody()
    {
        $response = new \Basalt\Http\Response('Test content');

        $this->assertEquals($response->getBody(), 'Test content');
    }

    /**
     * @depends testGetBody
     */
    public function testSetBody()
    {
        $response = new \Basalt\Http\Response('First test');
        $response->setBody('Second test');

        $this->assertEquals($response->getBody(), 'Second test');
    }

    public function testGetStatus()
    {
        $response = new \Basalt\Http\Response('', 404);

        $this->assertEquals($response->getStatus(), 404);
    }

    /**
     * @depends testGetStatus
     * @expectedException InvalidArgumentException
     */
    public function testSetStatus()
    {
        $response = new \Basalt\Http\Response;
        $response->setStatus(404);

        $this->assertEquals($response->getStatus(), 404);

        // Throws InvalidArgumentException
        $response->setStatus('404');
    }

    /**
     * @runInSeparateProcess
     */
    public function testSend()
    {
        $response = new \Basalt\Http\Response('Test content');
        $response->send();

        $this->expectOutputString('Test content');
    }
}
