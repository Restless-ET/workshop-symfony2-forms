<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DeepSpaceOne\GameBundle\DataFixtures\ORM;

use DeepSpaceOne\GameBundle\Entity\Equipment;
use DeepSpaceOne\GameBundle\Entity\Good;
use DeepSpaceOne\GameBundle\Entity\MountPoint;
use DeepSpaceOne\GameBundle\Entity\Payload;
use DeepSpaceOne\GameBundle\Entity\Ship;
use DeepSpaceOne\GameBundle\Entity\ShipClass;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class LoadData implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $ore = new Good();
        $ore->setName('Ore');
        $ore->setPricePerTon(20);

        $gold = new Good();
        $gold->setName('Gold');
        $gold->setPricePerTon(500);

        $iron = new Good();
        $iron->setName('Iron');
        $iron->setPricePerTon(40);

        $manager->persist($ore);
        $manager->persist($gold);
        $manager->persist($iron);

        $laser = new Equipment();
        $laser->setName('Ion Laser');
        $laser->setPrice(400);

        $torpedo = new Equipment();
        $torpedo->setName('Photon Torpedo');
        $torpedo->setPrice(350);

        $manager->persist($laser);
        $manager->persist($torpedo);

        $corvette = new ShipClass(
            'Corvette',
            8000,
            5,
            10,
            6
        );

        $freighter = new ShipClass(
            'Freighter',
            6000,
            50,
            6,
            2
        );

        $manager->persist($corvette);
        $manager->persist($freighter);

        $liberty = new Ship($corvette);
        $liberty->setName('Liberty');

        if (method_exists($liberty, 'setEquipment')) {
            $liberty->setEquipment(0, $torpedo);
            $liberty->setEquipment(1, $torpedo);
            $liberty->setEquipment(2, $laser);
            $liberty->setEquipment(3, $laser);
            $liberty->setEquipment(4, $torpedo);
            $liberty->setEquipment(5, $torpedo);
        } else {
            $liberty->addMountPoint(new MountPoint($torpedo));
            $liberty->addMountPoint(new MountPoint($torpedo));
            $liberty->addMountPoint(new MountPoint($laser));
            $liberty->addMountPoint(new MountPoint($laser));
            $liberty->addMountPoint(new MountPoint($torpedo));
            $liberty->addMountPoint(new MountPoint($torpedo));
        }

        $goliath = new Ship($freighter);
        $goliath->setName('Goliath');

        if (method_exists($goliath, 'setEquipment')) {
            $goliath->setEquipment(0, $laser);
            $goliath->setEquipment(1, $laser);
        } else {
            $goliath->addMountPoint(new MountPoint($laser));
            $goliath->addMountPoint(new MountPoint($laser));
        }

        $goliath->addPayload(new Payload($gold, 3));
        $goliath->addPayload(new Payload($ore, 47));

        $manager->persist($liberty);
        $manager->persist($goliath);

        $manager->flush();
    }
}
