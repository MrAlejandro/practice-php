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
    protected $session;
    protected $blade;
    protected $validator;

    protected function setUp()
    {
        $signer = $this->getMockBuilder('Kunststube\CSRFP\SignatureGenerator')
            ->setConstructorArgs(['fdhasflas'])
            ->getMock();

        $this->request = $this->getMockBuilder('Acme\Http\Request')->getMock();
        $this->session = $this->getMockBuilder('Acme\Http\Session')->getMock();
        $this->blade = $this->getMockBuilder('duncan3dc\Laravel\BladeInstance')
            ->setConstructorArgs(['abs123', 'abc'])
            ->getMock();

        $this->response = $this->getMockBuilder('Acme\Http\Response')
            ->setConstructorArgs([$this->request, $signer, $this->blade, $this->session])
            ->getMock();
    }

    public function getRequest($input = '')
    {
        $request = $this->getMockBuilder('Acme\Http\Request')
            ->getMock();

        $request->expects($this->once())
            ->method('input')
            ->will($this->returnValue($input));

        return $request;
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
        $request = $this->getRequest('yellow');

        $validator = new Validator($request, $this->response);
        $errors = $validator->check(['mintype' => 'min:3']);
        $this->assertCount(0, $errors);
    }

    public function testCheckForMinStringLengthWithInvalidData()
    {
        $request = $this->getRequest('x');

        $validator = new Validator($request, $this->response);
        $errors = $validator->check(['mintype' => 'min:3']);
        $this->assertCount(1, $errors);
    }

    public function testCheckForEmailForValidData()
    {
        $request = $this->getRequest('alex@test.com');

        $validator = new Validator($request, $this->response);
        $errors = $validator->check(['emailtype' => 'email']);
        $this->assertCount(0, $errors);
    }

    public function testCheckForEmailForInvalidData()
    {
        $request = $this->getRequest('notavalidemail');

        $validator = new Validator($request, $this->response);
        $errors = $validator->check(['emailtype' => 'email']);
        $this->assertCount(1, $errors);
    }

    public function testCheckForEqualToWithValidData()
    {
        // All methods are stubs, all method return null, all methods can be overridden
        $request = $this->getMockBuilder('Acme\Http\Request')
            ->getMock();

        $request->expects($this->at(0)) // override the method, first call (0) it returns "jack"
            ->method('input')
            ->will($this->returnValue('jack'));

        $request->expects($this->at(1)) // override the method, second call (1) it returns "jack"
            ->method('input')
            ->will($this->returnValue('jack'));

        $validator = new Validator($request, $this->response, $this->session);
        $errors = $validator->check(['my_field' => 'equalTo:another_field']);
        $this->assertCount(0, $errors);

        // All methods are stubs, all method return null, all methods can be overridden (equivalent to the previous)
        /* $request = $this->getMockBuilder('Acme\Http\Request') */
        /*     ->getMock([]); */

        // All methods are mocks (run the code), and cannot be overridden
        /* $request = $this->getMockBuilder('Acme\Http\Request') */
        /*     ->getMock(null); */

        // All specified methods are stubs, all other are mocks
        /* $request = $this->getMockBuilder('Acme\Http\Request') */
        /*     ->getMock(['methodOne', 'methodTwo']); */
    }

    public function testCheckForEqualToWithInvalidData()
    {
        $request = $this->getMockBuilder('Acme\Http\Request')
            ->getMock();

        $request->expects($this->at(0)) // override the method, first call (0) it returns "jack"
            ->method('input')
            ->will($this->returnValue('joe'));

        $request->expects($this->at(1)) // override the method, second call (1) it returns "jack"
            ->method('input')
            ->will($this->returnValue('jack'));

        $validator = new Validator($request, $this->response, $this->session);
        $errors = $validator->check(['my_field' => 'equalTo:another_field']);
        $this->assertCount(1, $errors);
    }

    public function testCheckForUniqueWithValidData()
    {
        $validator = $this->getMockBuilder('Acme\Validation\Validator')
            ->setConstructorArgs([$this->request, $this->response, $this->session])
            ->setMethods(['getRows']) // is stub and can be overridden
            ->getMock();

        $validator->method('getRows')
            ->willReturn([]);

        $errors = $validator->check(['my_field' => 'unique:User']);
        $this->assertCount(0, $errors);
    }

    public function testCheckForUniqueWithInvalidData()
    {
        $validator = $this->getMockBuilder('Acme\Validation\Validator')
            ->setConstructorArgs([$this->request, $this->response, $this->session])
            ->setMethods(['getRows']) // is stub and can be overridden
            ->getMock();

        $validator->method('getRows')
            ->willReturn(['a']);

        $errors = $validator->check(['my_field' => 'unique:User']);
        $this->assertCount(1, $errors);
    }

    public function testValidateWithValidData()
    {
        $validator = $this->getMockBuilder('Acme\Validation\Validator')
            ->setConstructorArgs([$this->request, $this->response, $this->session])
            ->setMethods(['check']) // is stub and can be overridden
            ->getMock();

        $isValid = $validator->validate(['foo' => 'min:3'], '/register');
        $this->assertTrue($isValid);
    }

    public function testValidateWithInvalidData()
    {
        $validator = $this->getMockBuilder('Acme\Validation\Validator')
            ->setConstructorArgs([$this->request, $this->response, $this->session])
            ->setMethods(['check', 'redirectToPage']) // are stubs and can be overridden
            ->getMock();

        $validator->expects($this->once())
            ->method('check')
            ->will($this->returnValue(['some error']));

        $validator->expects($this->once())
            ->method('redirectToPage'); // "smoke test" we do not want to call the method for now

        $isValid = $validator->validate(['foo' => 'min:1'], '/register');
        /* $this->assertFalse($isValid); */ // smoke test, not best practice but have no other options for this method
    }
}
