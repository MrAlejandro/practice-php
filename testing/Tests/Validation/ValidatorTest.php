<?php

namespace Acme\Tests;

use Acme\Http\Response;
use Acme\Http\Request;
use Acme\Validation\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    protected $request;
    protected $response;
    protected $validator;
    protected $testdata;

    protected function setUpRequestResponse()
    {
        if ($this->testdata === null) {
            $this->testdata = [];
        }

        $this->request = new Request($this->testdata);
        $this->response = new Response($this->request);
        $this->validator = new Validator($this->request, $this->response);
    }

    public function testGetIsValidReturnsTrue()
    {
        $this->setupRequestResponse();
        $this->validator->setIsValid(true);
        $this->assertTrue($this->validator->getIsValid());
    }

    public function testGetIsValidReturnsFalse()
    {
        $this->setupRequestResponse();
        $this->validator->setIsValid(false);
        $this->assertFalse($this->validator->getIsValid());
    }

    public function testCheckForMinStringLengthWithValidData()
    {
        $this->testdata = ['mintype' => 'yellow'];
        $this->setupRequestResponse();
        $errors = $this->validator->check(['mintype' => 'min:3']);
        $this->assertCount(0, $errors);
    }

    public function testCheckForMinStringLengthWithInvalidData()
    {
        $this->testdata = ['mintype' => 'x'];
        $this->setupRequestResponse();
        $errors = $this->validator->check(['mintype' => 'min:3']);
        $this->assertCount(1, $errors);
    }

    public function testCheckForEmailForValidData()
    {
        $this->testdata = ['emailtype' => 'alex@test.com'];
        $this->setupRequestResponse();
        $errors = $this->validator->check(['emailtype' => 'email']);
        $this->assertCount(0, $errors);
    }

    public function testCheckForEmailForInvalidData()
    {
        $this->testdata = ['emailtype' => 'notavalidemail'];
        $this->setupRequestResponse();
        $errors = $this->validator->check(['emailtype' => 'email']);
        $this->assertCount(1, $errors);
    }

    /* public function testValidateWithInvalidData() */
    /* { */
    /*     $this->testdata = ['check_field' => 'x']; */
    /*     $this->setupRequestResponse(); */
    /*     $this->validator->validate(['check_field' => 'email'], '/register'); */
    /* } */
}
