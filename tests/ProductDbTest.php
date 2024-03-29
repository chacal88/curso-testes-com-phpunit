<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * Class ProductTest
 */
class ProductDbTest extends TestCase
{
    private $db;

    protected function setUp()
    {
        $this->db = getPDO();
    }

    public function testIfProductIsSaved(): int
    {
        $result = $this->createProduct();
        $this->assertEquals(1, $result->getId());
        $this->assertEquals('Produto 1', $result->getName());
        $this->assertEquals(200.20, $result->getPrice());
        $this->assertEquals(10, $result->getQuantity());
        $this->assertEquals(200.20 * 10, $result->getTotal());
        return $result->getId();
    }

    public function testIfListProducts(): void
    {
        $db = $this->db;
        $product = new \Chacal\Model\Product($db);
        $this->createProduct();
        $this->createProduct();
        $products = $product->all();
        $this->assertCount(2, $products);
    }

    /**
     * @depends testIfProductIsSaved
     */
    public function testIfProductIsUpdated(int $id): int
    {
        $db = $this->db;
        $product = new \Chacal\Model\Product($db);
        $result = $product->save([
            'id' => $id,
            'name' => 'Produto Alterado',
            'price' => 300.20,
            'quantity' => 20
        ]);
        $this->assertEquals($id, $result->getId());
        $this->assertEquals('Produto Alterado', $result->getName());
        $this->assertEquals(300.20, $result->getPrice());
        $this->assertEquals(20, $result->getQuantity());
        $this->assertEquals(300.20 * 20, $result->getTotal());
        return $id;
    }
    /*
     * @depends testIfProductIsUpdated
     */
    //public function testIfProductCanBeRecovered($id)
    public function testIfProductCanBeRecovered(): void
    {
        $this->createProduct();
        $db = $this->db;
        $product = new \Chacal\Model\Product($db);
        $result = $product->find(1);
        $this->assertEquals(1, $result->getId());
        $this->assertEquals('Produto 1', $result->getName());
        $this->assertEquals(200.20, $result->getPrice());
        $this->assertEquals(10, $result->getQuantity());
        $this->assertEquals(200.20 * 10, $result->getTotal());
    }
    /*
     * @depends testIfProductIsUpdated
     */
    //public function testIfProductCanDeleted($id)
    public function testIfProductCanDeleted(): void
    {
        $this->createProduct();
        $db = $this->db;
        $product = new \Chacal\Model\Product($db);
        $result = $product->delete(1);
        $this->assertTrue($result);
        $products = $product->all();
        $this->assertCount(0, $products);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Produto não existente.
     */
    public function testIfProductNotFound(): void
    {
        $db = $this->db;
        $product = new \Chacal\Model\Product($db);
        $product->find(99999);
    }

    private function createProduct()
    {
        $db = $this->db;
        $product = new \Chacal\Model\Product($db);
        return $product->save([
            'name' => 'Produto 1',
            'price' => 200.20,
            'quantity' => 10
        ]);
    }

}
