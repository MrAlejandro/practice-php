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


    public function setUp()
    {
        $_SERVER['REQUEST_URI'] = "/about-acme";
        parent::setUp();
    }

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

    public function testGetShowPage()
    {
        $response = $this->getMockBuilder('Acme\Http\Response')
            ->setConstructorArgs([$this->request, $this->signer, $this->blade, $this->session])
            ->setMethods(['render'])
            ->getMock();

        $response->method('render')
            ->willReturn(true);

        $controller = $this->getMockBuilder('Acme\Controllers\PageController')
            ->setConstructorArgs([$this->request, $response,
            $this->session, $this->blade, $this->signer])
            ->setMethods(['getUri'])
            ->getMock();

        $controller->expects($this->once())
            ->method('getUri')
            ->will($this->returnValue('about-acme'));

        $controller->getShowPage();

        $expected = "About Acme";
        $actual = $controller->page->browser_title;
        $this->assertEquals($expected, $actual);

        $expected = "generic-page";
        $actual = Assert::readAttribute($response, 'view');
        $this->assertEquals($expected, $actual);

        $expected = 1;
        $actual = $controller->page->id;
        $this->assertEquals($expected, $actual);
    }

    public function testGetShowPageWithInvalidData()
    {
        // create a mock of Response and make render method a stub
        $resp = $this->getMockBuilder('Acme\Http\Response')
            ->setConstructorArgs([$this->request, $this->signer,
                $this->blade, $this->session])
            ->setMethods(['render'])
            ->getMock();

        // override render method to return true
        $resp->method('render')
            ->willReturn(true);

        // mock the controller and make getUri a stub
        $controller = $this->getMockBuilder('Acme\Controllers\PageController')
            ->setConstructorArgs([$this->request, $resp, $this->session,
                $this->blade, $this->signer])
            ->setMethods(['getUri', 'getShow404'])
            ->getMock();

        // orverride getUri to return just the slug from the uri
        $controller->expects($this->once())
            ->method('getUri')
            ->will($this->returnValue('missing-page'));

        $controller->expects($this->once())
            ->method('getShow404')
            ->will($this->returnValue(true));

        // call the method we want to test
        $result = $controller->getShowPage();
        // should get true back if we called 404

        $this->assertTrue($result);
    }

    public function testGetShow404()
    {
        $resp = $this->getMockBuilder('Acme\Http\Response')
            ->setConstructorArgs([$this->request, $this->signer,
                $this->blade, $this->session])
            ->setMethods(['render'])
            ->getMock();

        $resp->method('render')
            ->willReturn(true);

        $controller = new PageController($this->request, $resp,
            $this->session, $this->blade, $this->signer);

        $controller->getShow404();
        // should have view of page-not-found
        $expected = "page-not-found";
        $actual = Assert::readAttribute($resp, 'view');
        $this->assertEquals($expected, $actual);
    }

    public function testGetUri()
    {
        // create a mock of Response and make render method a stub
        $resp = $this->getMockBuilder('Acme\Http\Response')
            ->setConstructorArgs([$this->request, $this->signer, $this->blade, $this->session])
            ->setMethods(['render'])
            ->getMock();

        // override render method to return true
        $resp->method('render')
            ->willReturn(true);

        $controller = $this->getMockBuilder('Acme\Controllers\PageController')
            ->setConstructorArgs([$this->request, $resp, $this->session,
                $this->blade, $this->signer])
            ->setMethods(null)
            ->getMock();

        // call the method we want to test
        $controller->getShowPage();

        // we expect to get the $page object with browser_title set to "About Acme"
        $expected = "About Acme";
        $actual = $controller->page->browser_title;

        // run assesrtion for browser title/page title
        $this->assertEquals($expected, $actual);

        // should have view of generic-page
        $expected = "generic-page";
        $actual = Assert::readAttribute($resp, 'view');
        $this->assertEquals($expected, $actual);

        // should have page_id of 1
        $expected = 1;
        $actual = $controller->page->id;
        $this->assertEquals($expected, $actual);
    }
}
