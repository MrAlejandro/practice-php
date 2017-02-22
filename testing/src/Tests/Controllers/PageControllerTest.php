<?php

namespace Acme\Tests;

use PHPUnit\Framework\TestCase;

class PageControllerTest extends TestCase
{
    protected $request;
    protected $response;
    protected $session;
    protected $blade;
    protected $signer;

    public function setUp()
    {
        $this->signer = $this->getMockBuilder('Kunststube\CSRFP\SignatureGenerator')
            ->setConstructorArgs(['abc123'])
            ->getMock();

        $this->request = $this->getMockBuilder('Acme\Http\Request')->getMock();
        $this->session = $this->getMockBuilder('Acme\Http\Session')->getMock();
        $this->blade = $this->getMockBuilder('duncan3dc\Laravel\BladeInstance')
            ->setConstructorArgs(['abs123', 'abc'])
            ->getMock();

        $this->response = $this->getMockBuilder('Acme\Http\Response')
            ->setConstructorArgs([$this->request, $this->signer, $this->blade, $this->session])
            ->getMock();
    }

    /**
     * @dataProvider providerTestMakeSlug
     */
    public function testMakeSlug($original, $expected)
    {
        $controller = $this->getMockBuilder('Acme\Controllers\PageController')
            ->setConstructorArgs([$this->request, $this->response, $this->session,
                $this->blade, $this->signer])
            ->setMethods(null)
            ->getMock();

        $actual = $controller->makeSlug($original);
        $this->assertEquals($expected, $actual);
    }

    public function providerTestMakeSlug()
    {
        return [
            ['Hello world!', 'hello-world'],
            ['Goodbye, cruel world', 'goodbye-cruel-world'],
            ['what about an & and a ?', 'what-about-an-and-a'],
            ['It should get also handle é', 'it-should-get-also-handle-e']
        ];
    }
}

