<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DeepSpaceOne\GameBundle\Tests;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Base test case that recreates the database.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
abstract class WebTestCase extends \Symfony\Bundle\FrameworkBundle\Test\WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected static $em;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        static::$kernel = static::createKernel();
        static::$kernel->boot();

        static::$em = static::$kernel->getContainer()->get('doctrine')->getManager();

        // remove database
        $filesystem = new Filesystem();
        $filesystem->remove(static::$em->getConnection()->getDatabase());

        // create new database with fresh fixtures
        $fixtureFile = static::$kernel->getRootDir().'/data/test-fixtures.sql';
        static::$em->getConnection()->exec(file_get_contents($fixtureFile));
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();

        static::$em->close();
    }
}
