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
 * Validates that all functionality for Task 5 has been implemented.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 * @group task5
 */
class Task5Test extends WebTestCase
{
    public function testCreateShip()
    {
        if (class_exists('\DeepSpaceOne\GameBundle\Form\ShipType')
            && class_exists('\DeepSpaceOne\GameBundle\Form\CreateShipType')
            && class_exists('\DeepSpaceOne\GameBundle\Form\EditShipType')) {
            $this->markTestSkipped('Task 5 and Task 6 are incompatible.');
        }

        $client = static::createClient();

        $crawler = $client->request('GET', '/ships/');
        $link = $crawler->selectLink('Buy ship')->link();
        $this->assertEquals('GET', $link->getMethod());
        $this->assertStringEndsWith('/ships/new', $link->getUri());
        $this->assertCount(2, $crawler->filter('.records_list tbody tr'), '2 ships exist');

        // Form page
        $crawler = $client->click($link);
        $form = $crawler->selectButton('Buy')->form();
        $numberOfInlineHelps = $crawler->filter('.help-inline')->count();
        $this->assertEquals('POST', $form->getMethod());
        $this->assertStringEndsWith('/ships/', $form->getUri());

        // Submit empty
        $crawler = $client->submit($form, array(
            'deepspaceone_createship[name]' => '',
            // the crawler does not allow to select a non-existing option
            'deepspaceone_createship[class]' => '1',
        ));
        $this->assertCount($numberOfInlineHelps + 1, $crawler->filter('.help-inline'));
        $form = $crawler->selectButton('Buy')->form();

        // Submit correctly
        $client->submit($form, array(
            'deepspaceone_createship[name]' => 'Millenium Falcon',
            'deepspaceone_createship[class]' => '1',
        ));
        $this->assertStringEndsWith('/ships/', $client->getResponse()->headers->get('location'));

        // Test that record was added to the table
        $crawler = $client->followRedirect();
        $this->assertCount(3, $crawler->filter('.records_list tbody tr'), 'one ship was added');
    }

    /**
     * @depends testCreateShip
     */
    public function testEditShip()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/ships/');
        $link = $crawler->selectLink('Liberty')->link();
        $this->assertEquals('GET', $link->getMethod());
        $this->assertRegExp('~/ships/\d+/edit$~', $link->getUri());
        $this->assertCount(3, $crawler->filter('.records_list tbody tr'), '3 ships exist');

        // Edit form
        $crawler = $client->click($link);
        $form = $crawler->selectButton('Update')->form();
        $numberOfInlineHelps = $crawler->filter('.help-inline')->count();
        $this->assertEquals('POST', $form->getMethod());
        $this->assertEquals('PUT', $crawler->filter('form input[name="_method"]')->attr('value'));
        $this->assertRegExp('~/ships/\d+$~', $form->getUri());
        $this->assertCount(6, $crawler->filter('select[name^="deepspaceone_editship[mountPoints]["][name$="][equipment]"]'));
        $this->assertEquals('disabled', $crawler->filter('select[name="deepspaceone_editship[class]"]')->attr('disabled'));

        // Submit empty name and too much mass
        $form->setValues(array(
            'deepspaceone_editship[name]' => '',
            'deepspaceone_editship[mountPoints][0][equipment]' => '1',
            'deepspaceone_editship[mountPoints][1][equipment]' => '1',
            'deepspaceone_editship[mountPoints][2][equipment]' => '2',
            'deepspaceone_editship[mountPoints][3][equipment]' => '2',
            'deepspaceone_editship[mountPoints][4][equipment]' => '1',
            'deepspaceone_editship[mountPoints][5][equipment]' => '1',
        ));
        $values = $form->getPhpValues();
        $values['deepspaceone_editship']['payload']['0']['mass'] = '6';
        $values['deepspaceone_editship']['payload']['0']['good'] = '1';
        $crawler = $client->request($form->getMethod(), $form->getUri(), $values);
        $this->assertCount($numberOfInlineHelps + 2, $crawler->filter('.help-inline'));
        $form = $crawler->selectButton('Update')->form();

        // Submit correctly
        $client->submit($form, array(
            'deepspaceone_editship[name]' => 'Explorer',
            'deepspaceone_editship[payload][0][mass]' => '5',
        ));
        $this->assertStringEndsWith('/ships/', $client->getResponse()->headers->get('location'));

        // Test that record was added to the table
        $crawler = $client->followRedirect();
        $this->assertCount(3, $crawler->filter('.records_list tbody tr'), 'no ship was added');
        $this->assertCount(1, $crawler->filter('.records_list td:contains("Explorer")'));
        $this->assertCount(1, $crawler->filter('.records_list td:contains("Goliath")'));
        $this->assertCount(1, $crawler->filter('.records_list td:contains("Millenium Falcon")'));
    }
}
