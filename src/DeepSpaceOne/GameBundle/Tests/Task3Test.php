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

/**
 * Validates that all functionality for Task 3 has been implemented.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 * @group task3
 * @group task4
 * @group task5
 * @group task6
 */
class Task3Test extends WebTestCase
{
    public function testCreateGoodFormIsHorizontal()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/goods/new');
        $this->assertRegExp('/form-horizontal/', $crawler->filter('form')->attr('class'));
        $this->assertGreaterThan(0, $crawler->filter('.control-group')->count());
    }

    public function testEditGoodFormIsHorizontal()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/goods/1/edit');
        $this->assertRegExp('/form-horizontal/', $crawler->filter('form')->attr('class'));
        $this->assertGreaterThan(0, $crawler->filter('.control-group')->count());
    }
}
