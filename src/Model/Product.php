<?php

declare(strict_types=1);

namespace Chacal\Model;

/**
 * Class Product
 * @package Chacal\Model
 */
class Product
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var float
     */
    private $price;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @var double
     */
    private $total;

    private $pdo;

    /**
     * Product constructor.
     * @param $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return (int) $this->id;
    }

    /**
     * @param int $id
     * @return Product
     */
    public function setId(int $id): Product
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return (string) $this->name;
    }

    /**
     * @param string $name
     * @return Product
     */
    public function setName(string $name): Product
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): ?float
    {
        return (float) $this->price;
    }

    /**
     * @param float $price
     * @return Product
     */
    public function setPrice(float $price): Product
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): ?int
    {
        return (int) $this->quantity;
    }

    /**
     * @param int $quantity
     * @return Product
     */
    public function setQuantity(int $quantity): Product
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return float
     */
    public function getTotal(): ?float
    {
        return (float) $this->total;
    }

    /**
     * @param float $total
     * @return Product
     */
    public function setTotal(float $total): Product
    {
        $this->total = $total;
        return $this;
    }

    private function hydrate(array $data)
    {
        $this->id = $data['id'];
        $this->setName($data['name'])
             ->setPrice((float) $data['price'])
             ->setQuantity((int) $data['quantity']);
        $this->total = $data['total'];
    }

    public function save(array $data): Product
    {
        if (!isset($data['id'])) {
            $query = "INSERT INTO products (`name`,`price`,`quantity`,`total`) VALUES (:name,:price,:quantity,:total)";
            $stmt = $this->pdo->prepare($query);
        } else {
            $query = "UPDATE products SET `name`=:name,`price`=:price,`quantity`=:quantity,`total`=:total WHERE `id`=:id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(":id", $data['id']);
        }
        $stmt->bindValue(":name", $data['name']);
        $stmt->bindValue(":price", $data['price']);
        $stmt->bindValue(":quantity", $data['quantity']);
        $data['total'] = $data['price'] * $data['quantity'];
        $stmt->bindValue(":total", $data['total']);
        $stmt->execute();
        $data['id'] = $data['id'] ?? $this->pdo->lastInsertId();
        $this->hydrate($data);
        return $this;
    }

    public function delete(int $id): bool
    {
        $query = "DELETE FROM products WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(":id", $id);
        return $stmt->execute();
    }

    public function all(): array
    {
        $query = "SELECT * FROM products";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function find(int $id): Product
    {
        $query = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$data) {
            throw new \Exception('Produto não existente.');
        }
        $this->hydrate($data);
        return $this;
    }

}