<?php
namespace Acme\Tests;

use Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;
use PDO;
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

/**
 * Class AcmeBaseIntegrationTest
 * @package Acme\Tests
 */
abstract class AcmeBaseIntegrationTest extends TestCase
{
    use TestCaseTrait;
    public $bootstrapResources;
    public $dbAdapter;
    public $bootstrap;
    public $conn;

    protected $request;
    protected $response;
    protected $blade;
    protected $session;

    public function setUp()
    {
        require __DIR__ . '/../vendor/autoload.php';
        require __DIR__ . '/../bootstrap/functions.php';
        Dotenv::load(__DIR__ . '/../');

        $capsule = new Capsule();

        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'acme_test',
            'username'  => 'root',
            'password'  => 'root',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        $signer = $this->getMockBuilder('Kunststube\CSRFP\SignatureGenerator')
            ->setConstructorArgs(['fdhasflas'])
            ->getMock();

        $this->request = $this->getMockBuilder('Acme\Http\Request')
            ->getMock();

        $this->session = $this->getMockBuilder('Acme\Http\Session')
            ->getMock();

        $this->blade = $this->getMockBuilder('duncan3dc\Laravel\BladeInstance')
            ->setConstructorArgs(['abs123', 'abc'])
            ->getMock();

        $this->response = $this->getMockBuilder('Acme\Http\Response')
            ->setConstructorArgs([$this->request, $signer, $this->blade, $this->session])
            ->getMock();
    }

    public function getDataSet()
    {
        return $this->createMySQLXMLDataSet(__DIR__ . "/acme_db.xml");
    }


    public function getConnection()
    {
        $db = new PDO(
            "mysql:host=localhost;dbname=acme_test",
            "root", "root");

        return $this->createDefaultDBConnection($db, "acme_test");
    }

    /**
     * Use reflection to allow us to run protected methods
     *
     * @param $obj
     * @param $method
     * @param array $args
     * @return mixed
     */
    public function runProtectedMethod($obj, $method, $args = array()) {
        $method = new \ReflectionMethod(get_class($obj), $method);
        $method->setAccessible(true);

        return $method->invokeArgs($obj, $args);
    }

}
