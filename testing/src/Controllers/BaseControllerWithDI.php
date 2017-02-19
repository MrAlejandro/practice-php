<?php
namespace Acme\Controllers;

use Acme\Interfaces\ControllerInterface;
use duncan3dc\Laravel\BladeInstance;
use Kunststube\CSRFP\SignatureGenerator;
use Acme\Http\Response;
use Acme\Http\Request;
use Acme\Http\Session;

/**
 * Class BaseController
 * @package Acme\Controllers
 */
class BaseControllerWithDI implements ControllerInterface {

    protected $blade;
    protected $signer;
    public $response;
    public $request;

    /**
     * @param string $type
     */
    public function __construct(
        Request $request,
        Response $response,
        Session $session,
        BladeInstance $blade,
        SignatureGenerator $signer,
        $type = "text/html"
    ) {
        $this->signer = $signer;
        $this->blade = $blade;
        $this->request = $request;
        $this->response = $response;
        $this->session = $session;
    }

}

