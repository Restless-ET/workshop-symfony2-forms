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
 * Validates that all functionality for Task 4 has been implemented.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 * @group task4
 * @group task5
 * @group task6
 */
class Task4Test extends WebTestCase
{
    public function testCreateShipClass()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/classes/');
        $link = $crawler->selectLink('Add ship class')->link();
        $this->assertEquals('GET', $link->getMethod());
        $this->assertStringEndsWith('/classes/new', $link->getUri());
        $this->assertCount(2, $crawler->filter('.records_list tbody tr'), '2 classes exist');

        // Form page
        $crawler = $client->click($link);
        $form = $crawler->selectButton('Create')->form();
        $numberOfInlineHelps = $crawler->filter('.help-inline')->count();
        $this->assertEquals('POST', $form->getMethod());
        $this->assertStringEndsWith('/classes/', $form->getUri());

        // Submit empty
        $crawler = $client->submit($form, array(
            'deepspaceone_shipclass[name]' => '',
            'deepspaceone_shipclass[price]' => '',
            'deepspaceone_shipclass[payloadCapacity]' => '',
            'deepspaceone_shipclass[equipmentCapacity]' => '',
            'deepspaceone_shipclass[crewSize]' => '',
        ));
        $this->assertCount($numberOfInlineHelps + 5, $crawler->filter('.help-inline'));
        $form = $crawler->selectButton('Create')->form();

        // Submit correctly
        $client->submit($form, array(
            'deepspaceone_shipclass[name]' => 'Battleship',
            'deepspaceone_shipclass[price]' => '20000',
            'deepspaceone_shipclass[payloadCapacity]' => '20',
            'deepspaceone_shipclass[equipmentCapacity]' => '16',
            'deepspaceone_shipclass[crewSize]' => '200',
        ));
        $this->assertStringEndsWith('/classes/', $client->getResponse()->headers->get('location'));

        // Test that record was added to the table
        $crawler = $client->followRedirect();
        $this->assertCount(3, $crawler->filter('.records_list tbody tr'), 'one class was added');
    }
}
