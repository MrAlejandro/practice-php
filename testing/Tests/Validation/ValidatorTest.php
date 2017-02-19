<?php

namespace Acme\Tests;

// use Acme\Http\Response;
// use Acme\Http\Request;
use Acme\Validation\Validator;
use PHPUnit\Framework\TestCase;
use Kunststube\CSRFP\SignatureGenerator;
use Dotenv;

class ValidatorTest extends TestCase
{
    protected $request;
    protected $response;
    protected $validator;

    protected function setUp()
    {
        $signer = $this->getMockBuilder('Kunststube\CSRFP\SignatureGenerator')
            ->setConstructorArgs(['fdhasflas'])
            ->getMock();

        $this->request = $this->getMockBuilder('Acme\Http\Request')
            ->getMock();

        $this->response = $this->getMockBuilder('Acme\Http\Response')
            ->setConstructorArgs([$this->request, $signer])
            ->getMock();
    }

    public function testGetIsValidReturnsTrue()
    {
        $validator = new Validator($this->request, $this->response);
        $validator->setIsValid(true);
        $this->assertTrue($validator->getIsValid());
    }

    public function testGetIsValidReturnsFalse()
    {
        $validator = new Validator($this->request, $this->response);
        $validator->setIsValid(false);
        $this->assertFalse($validator->getIsValid());
    }

    public function testCheckForMinStringLengthWithValidData()
    {
        $request = $this->getMockBuilder('Acme\Http\Request')
            ->getMock();

        $request->expects($this->once())
            ->method('input')
            ->will($this->returnValue('yellow'));

        $validator = new Validator($request, $this->response);
        $errors = $validator->check(['mintype' => 'min:3']);
        $this->assertCount(0, $errors);
    }

    public function testCheckForMinStringLengthWithInvalidData()
    {
        $request = $this->getMockBuilder('Acme\Http\Request')
            ->getMock();

        $request->expects($this->once())
            ->method('input')
            ->will($this->returnValue('x'));

        $validator = new Validator($request, $this->response);
        $errors = $validator->check(['mintype' => 'min:3']);
        $this->assertCount(1, $errors);
    }

    public function testCheckForEmailForValidData()
    {
        $request = $this->getMockBuilder('Acme\Http\Request')
            ->getMock();

        $request->expects($this->once())
            ->method('input')
            ->will($this->returnValue('alex@test.com'));

        $validator = new Validator($request, $this->response);
        $errors = $validator->check(['emailtype' => 'email']);
        $this->assertCount(0, $errors);
    }

    public function testCheckForEmailForInvalidData()
    {
        $request = $this->getMockBuilder('Acme\Http\Request')
            ->getMock();

        $request->expects($this->once())
            ->method('input')
            ->will($this->returnValue('notavalidemail'));

        $validator = new Validator($request, $this->response);
        $errors = $validator->check(['emailtype' => 'email']);
        $this->assertCount(1, $errors);
    }

    /* public function testValidateWithValidData() */
    /* { */
    /*     $this->testdata = ['check_field' => 'alex@test.com']; */
    /*     $this->setupRequestResponse(); */
    /*     $this->assertTrue($this->validator->validate(['check_field' => 'email'], '/register')); */
    /* } */

    /* public function testValidateWithInvalidData() */
    /* { */
    /*     $this->testdata = ['check_field' => 'x']; */
    /*     $this->setupRequestResponse(); */
    /*     $this->assertFalse($this->validator->validate(['check_field' => 'email'], '/register')); */
    /* } */
}
