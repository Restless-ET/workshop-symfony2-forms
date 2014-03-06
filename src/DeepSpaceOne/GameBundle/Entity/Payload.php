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
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Payload that a ship is carrying.
 *
 * @Entity
 * @Table(indexes={@Index(columns={"ship_id"})})
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class Payload
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
     * The loaded good.
     *
     * @ManyToOne(targetEntity="Good")
     * @JoinColumn(nullable=false)
     *
     * @Assert\NotNull
     *
     * @var Good
     */
    private $good;

    /**
     * The amount of the loaded good in tons.
     *
     * @Column(type="integer", nullable=false)
     *
     * @Assert\NotNull
     *
     * @var integer
     */
    private $mass;

    /**
     * The ship the good is loaded into.
     *
     * @ManyToOne(targetEntity="Ship", inversedBy="payload")
     *
     * @Assert\NotNull
     *
     * @var Ship
     */
    private $ship;

    public function __construct(Good $good = null, $mass = null)
    {
        $this->good = $good;
        $this->mass = $mass;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Good $good
     */
    public function setGood($good)
    {
        $this->good = $good;
    }

    /**
     * @return Good
     */
    public function getGood()
    {
        return $this->good;
    }

    /**
     * @param integer $mass
     */
    public function setMass($mass)
    {
        $this->mass = $mass;
    }

    /**
     * @return integer
     */
    public function getMass()
    {
        return $this->mass;
    }

    /**
     * @param Ship $ship
     */
    public function setShip(Ship $ship = null)
    {
        $this->ship = $ship;
    }

    /**
     * @return Ship
     */
    public function getShip()
    {
        return $this->ship;
    }

}
