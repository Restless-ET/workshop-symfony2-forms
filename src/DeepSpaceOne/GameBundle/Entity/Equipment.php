<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DeepSpaceOne\GameBundle\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Equipment that can be mounted onto a ship.
 *
 * @Entity
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class Equipment
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     *
     * @var integer
     */
    private $id;

    /**
     * The name of the equipment.
     *
     * @Column(type="string", nullable=false)
     *
     * @var string
     */
    private $name;

    /**
     * The price of the equipment in credits.
     *
     * @Column(type="integer", nullable=false)
     *
     * @var integer
     */
    private $price;

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param integer $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }
}
