<?php

namespace Acme\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;
use Acme\Controllers\PageController;

class PageControllerIntegrationTest extends AcmeBaseIntegrationTest
{
    protected $request;
    protected $response;
    protected $session;
    protected $signer;
    protected $blade;

    public function testGetShowHomePage()
    {
        $response = $this->getMockBuilder('Acme\Http\Response')
            ->setConstructorArgs([$this->request, $this->signer, $this->blade, $this->session])
            ->setMethods(['render'])
            ->getMock();

        $response->method('render')
            ->willReturn(true);

        $controller = new PageController($this->request, $response,
            $this->session, $this->blade, $this->signer);

        $controller->getShowHomePage();

        $expected = 'home';
        $actual = Assert::readAttribute($response, 'view');
        $this->assertEquals($actual, $expected);
    }
}
