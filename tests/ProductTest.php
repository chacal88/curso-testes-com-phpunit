<?php

declare(strict_types=1);

use Chacal\Model\Product;
use PHPUnit\Framework\TestCase;

/**
 * Class ProductTest
 */
class ProductTest extends TestCase
{
    private $product;

    protected function setUp()
    {
        $pdo = $this->getMockBuilder(\PDO::class)
            ->disableOriginalConstructor()->getMock();
        $this->product = new Product($pdo);
    }

    public function testIfIdIsZero()
    {
        $this->assertEquals(0, $this->product->getId());
    }

    public function testIfTotalIsZero()
    {
        $this->assertEquals(0.0, $this->product->getTotal());
    }

    /**
     * @dataProvider collectionData
     */
    public function testEncapsulate($property, $expected)
    {
        $null = $this->product->{'get' . ucfirst($property)}();

        if (is_float($expected)) {
            $this->assertEquals(0.0, $null);
        }
        if (is_int($expected)) {
            $this->assertEquals(0, $null);
        }
        if (is_string($expected)) {
            $this->assertEquals('',$null);
        }

        $result = $this->product->{'set' . ucfirst($property)}($expected);
        $this->assertInstanceOf(Product::class, $result);
        $actual = $this->product->{'get' . ucfirst($property)}();
        $this->assertEquals($expected, $actual);
    }

    public function collectionData()
    {
        return [
            ['name', 'Produto 1'],
            ['price', 10.11],
            ['quantity', 5],
        ];
    }
}
