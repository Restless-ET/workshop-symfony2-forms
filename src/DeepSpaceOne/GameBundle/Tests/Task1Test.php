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
 * Validates that all functionality for Task 1 has been implemented.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 * @group task1
 * @group task2
 * @group task3
 * @group task4
 * @group task5
 * @group task6
 */
class Task1Test extends WebTestCase
{
    public function testCreateEquipment()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/equipment/');
        $link = $crawler->selectLink('Add equipment')->link();
        $this->assertEquals('GET', $link->getMethod());
        $this->assertStringEndsWith('/equipment/new', $link->getUri());
        $this->assertCount(2, $crawler->filter('.records_list tbody tr'), '2 equipments exist');

        // Form page
        $crawler = $client->click($link);
        $form = $crawler->selectButton('Create')->form();
        $numberOfInlineHelps = $crawler->filter('.help-inline')->count();
        $this->assertEquals('POST', $form->getMethod());
        $this->assertStringEndsWith('/equipment/', $form->getUri());
        $this->assertEquals('', $crawler->filter('input[name="form[name]"]')->attr('value'));
        $this->assertEquals('10', $crawler->filter('input[name="form[price]"]')->attr('value'));

        // Submit empty
        $crawler = $client->submit($form, array(
            'form[name]' => '',
            'form[price]' => '',
        ));
        $this->assertCount($numberOfInlineHelps + 2, $crawler->filter('.help-inline'));
        $form = $crawler->selectButton('Create')->form();

        // Submit invalid
        $crawler = $client->submit($form, array(
            'form[name]' => 'Fo',
            'form[price]' => '-10',
        ));
        $this->assertCount($numberOfInlineHelps + 2, $crawler->filter('.help-inline'));
        $form = $crawler->selectButton('Create')->form();

        // Submit correctly
        $client->submit($form, array(
            'form[name]' => 'Selfseeking Mine',
            'form[price]' => '700',
        ));
        $this->assertStringEndsWith('/equipment/', $client->getResponse()->headers->get('location'));

        // Test that record was added to the table
        $crawler = $client->followRedirect();
        $this->assertCount(3, $crawler->filter('.records_list tbody tr'), 'one equipment was added');
    }

    /**
     * @depends testCreateEquipment
     */
    public function testEditEquipment()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/equipment/');
        $link = $crawler->selectLink('Ion Laser')->link();
        $this->assertEquals('GET', $link->getMethod());
        $this->assertRegExp('~/equipment/\d+/edit$~', $link->getUri());
        $this->assertCount(3, $crawler->filter('.records_list tbody tr'), '3 equipments exist');

        // Edit form
        $crawler = $client->click($link);
        $form = $crawler->selectButton('Update')->form();
        $numberOfInlineHelps = $crawler->filter('.help-inline')->count();
        $this->assertEquals('POST', $form->getMethod());
        $this->assertEquals('PUT', $crawler->filter('form input[name="_method"]')->attr('value'));
        $this->assertRegExp('~/equipment/\d+$~', $form->getUri());
        $this->assertEquals('Ion Laser', $crawler->filter('input[name="form[name]"]')->attr('value'));
        $this->assertEquals('400', $crawler->filter('input[name="form[price]"]')->attr('value'));

        // Submit empty
        $crawler = $client->submit($form, array(
            'form[name]' => '',
            'form[price]' => '',
        ));
        $this->assertCount($numberOfInlineHelps + 2, $crawler->filter('.help-inline'));
        $form = $crawler->selectButton('Update')->form();

        // Submit invalid
        $crawler = $client->submit($form, array(
            'form[name]' => 'Fo',
            'form[price]' => '-10',
        ));
        $this->assertCount($numberOfInlineHelps + 2, $crawler->filter('.help-inline'));
        $form = $crawler->selectButton('Update')->form();

        // Submit correctly
        $client->submit($form, array(
            'form[name]' => 'Plasma Laser',
            'form[price]' => '800',
        ));
        $this->assertStringEndsWith('/equipment/', $client->getResponse()->headers->get('location'));

        // Test that record was added to the table
        $crawler = $client->followRedirect();
        $this->assertCount(3, $crawler->filter('.records_list tbody tr'), 'no equipment was added');
        $this->assertCount(1, $crawler->filter('.records_list td:contains("Plasma Laser")'));
        $this->assertCount(1, $crawler->filter('.records_list td:contains("Photon Torpedo")'));
        $this->assertCount(1, $crawler->filter('.records_list td:contains("Selfseeking Mine")'));
    }
}
