<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Recipe
 *
 * @ORM\Table(name="recipe")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RecipeRepository")
 */
class Recipe
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;



    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="stock_products")
     * @ORM\JoinColumn(name="id_product", referencedColumnName="id")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="Stock", inversedBy="stocks")
     * @ORM\JoinColumn(name="id_stock", referencedColumnName="id")
     */
    private $stock;
    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set product
     *
     * @param integer $product
     *
     * @return Recipe
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return int
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set stock
     *
     * @param integer $stock
     *
     * @return Recipe
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return int
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     *
     * @return Recipe
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }
}

