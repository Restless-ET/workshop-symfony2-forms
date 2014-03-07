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
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Index;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Entity
 * @Table(indexes={@Index(columns={"ship_id"})})
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class MountPoint
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
     * @ManyToOne(targetEntity="Ship", inversedBy="mountPoints")
     * @JoinColumn(nullable=false)
     *
     * @Assert\NotNull
     *
     * @var Ship
     */
    private $ship;

    /**
     * @ManyToOne(targetEntity="Equipment")
     *
     * @var Equipment
     */
    private $equipment;

    public function __construct(Equipment $equipment = null)
    {
        $this->equipment = $equipment;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \DeepSpaceOne\GameBundle\Entity\Ship $ship
     */
    public function setShip($ship)
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

    /**
     * @param Equipment $equipment
     */
    public function setEquipment(Equipment $equipment = null)
    {
        $this->equipment = $equipment;
    }

    /**
     * @return Equipment
     */
    public function getEquipment()
    {
        return $this->equipment;
    }
}
