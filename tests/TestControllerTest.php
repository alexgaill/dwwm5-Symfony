<?php
namespace App\Tests;

use App\Controller\TestController;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TestControllerTest extends KernelTestCase {

    public function testAddition ()
    {
        $controller = new TestController;

        $validate = $controller->addition(2, 6);

        $this->assertEquals(8, $validate);
    }
    public function testAdditionLettres ()
    {
        $controller = new TestController;

        $validate = $controller->addition("a", "b");

        $this->assertEquals( "ab", $validate);
    }
}