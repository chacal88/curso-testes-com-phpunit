<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Chacal\Area;

/**
 * Created by PhpStorm.
 * User: chacal
 * Date: 23/05/17
 * Time: 16:34
 */
class MyFirstTest extends TestCase
{

    public function testArray(): void
    {
        $array = [2];
        $this->assertNotEmpty($array);
    }

    public function testArea(){
        $area = new Area();
        $this->assertEquals(6,$area->getArea(2,3));
    }

}