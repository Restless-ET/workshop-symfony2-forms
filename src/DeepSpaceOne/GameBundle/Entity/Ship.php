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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A space ship.
 *
 * @Entity
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class Ship
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
     * The ship's name.
     *
     * @Column(type="string", nullable=false)
     *
     * @Assert\NotNull
     * @Assert\Length(min=3)
     *
     * @var string
     */
    private $name;

    /**
     * The type of the ship.
     *
     * @ManyToOne(targetEntity="ShipClass")
     *
     * @Assert\NotNull
     *
     * @var ShipClass
     */
    private $class;

    /**
     * The payload the ship is carrying.
     *
     * @OneToMany(targetEntity="Payload", mappedBy="ship", cascade={"persist", "remove"})
     *
     * @Assert\NotNull
     * @Assert\Valid
     *
     * @var \Doctrine\Common\Collections\Collection<Payload>
     */
    private $payload;

    /**
     * The mountPoints for mounting equipment.
     *
     * @OneToMany(targetEntity="MountPoint", mappedBy="ship", cascade={"persist", "remove"})
     *
     * @Assert\NotNull
     * @Assert\Valid
     *
     * @var \Doctrine\Common\Collections\Collection<MountPoint>
     */
    private $mountPoints;

    public function __construct(ShipClass $class = null)
    {
        $this->class = $class;

        $this->clearPayload();
        $this->clearMountPoints();
    }


    /**
     * @return int
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
     * @param ShipClass $class
     */
    public function setClass(ShipClass $class)
    {
        $this->class = $class;
    }

    /**
     * @return ShipClass
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return MountPoint[]
     */
    public function getMountPoints()
    {
        return $this->mountPoints->toArray();
    }

    /**
     * @param MountPoint $mountPoint
     */
    public function addMountPoint(MountPoint $mountPoint)
    {
        $this->mountPoints[] = $mountPoint;

        $mountPoint->setShip($this);
    }

    /**
     * @param MountPoint $mountPoint
     */
    public function removeMountPoint(MountPoint $mountPoint)
    {
        $this->mountPoints->removeElement($mountPoint);

        if ($this === $mountPoint->getShip()) {
            $mountPoint->setShip(null);
        }
    }

    /**
     * Clears the mount points.
     */
    public function clearMountPoints()
    {
        $this->mountPoints = new ArrayCollection();
    }

    /**
     * @return integer
     */
    public function getEquipmentCapacity()
    {
        return $this->class ? $this->class->getEquipmentCapacity() : 0;
    }

    /**
     * @return \SplObjectStorage
     */
    public function getEquipmentSummary()
    {
        $summary = new \SplObjectStorage();

        foreach ($this->mountPoints as $mountPoint) {
            if (null !== $mountPoint->getEquipment()) {
                if (!isset($summary[$mountPoint->getEquipment()])) {
                    $summary[$mountPoint->getEquipment()] = 0;
                }

                $summary[$mountPoint->getEquipment()] += 1;
            }
        }

        return $summary;
    }

    /**
     * @return Payload[]
     */
    public function getPayload()
    {
        return $this->payload->toArray();
    }

    /**
     * @return \SplObjectStorage
     */
    public function getPayloadSummary()
    {
        $summary = new \SplObjectStorage();

        foreach ($this->payload as $payload) {
            if (null !== $payload->getGood() && !isset($summary[$payload->getGood()])) {
                $summary[$payload->getGood()] = 0;
            }

            $summary[$payload->getGood()] += $payload->getMass();
        }

        return $summary;
    }

    /**
     * @return integer
     */
    public function getPayloadCapacity()
    {
        return $this->class ? $this->class->getPayloadCapacity() : 0;
    }

    public function getPayloadMass()
    {
        $mass = 0;

        foreach ($this->payload as $payload) {
            $mass += $payload->getMass();
        }

        return $mass;
    }

    /**
     * @param Payload $payload
     */
    public function addPayload(Payload $payload)
    {
        $this->payload[] = $payload;

        $payload->setShip($this);
    }

    /**
     * @param Payload $payload
     */
    public function removePayload(Payload $payload)
    {
        $this->payload->removeElement($payload);

        if ($this === $payload->getShip()) {
            $payload->setShip(null);
        }
    }

    /**
     * Clears the payload.
     */
    public function clearPayload()
    {
        $this->payload = new ArrayCollection();
    }
}
