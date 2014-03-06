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
 * Validates that all functionality for Task 2 has been implemented.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 * @group task2
 * @group task3
 * @group task4
 * @group task5
 * @group task6
 */
class Task2Test extends WebTestCase
{
    public function testCreateGood()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/goods/');
        $link = $crawler->selectLink('Add good')->link();
        $this->assertEquals('GET', $link->getMethod());
        $this->assertStringEndsWith('/goods/new', $link->getUri());
        $this->assertCount(3, $crawler->filter('.records_list tbody tr'), '3 goods exist');

        // Form page
        $crawler = $client->click($link);
        $form = $crawler->selectButton('Create')->form();
        $numberOfInlineHelps = $crawler->filter('.help-inline')->count();
        $this->assertEquals('POST', $form->getMethod());
        $this->assertStringEndsWith('/goods/', $form->getUri());
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
            'form[name]' => 'F',
            'form[price]' => '-10',
        ));
        $this->assertCount($numberOfInlineHelps + 2, $crawler->filter('.help-inline'));
        $form = $crawler->selectButton('Create')->form();

        // Submit correctly
        $client->submit($form, array(
            'form[name]' => 'Jewels',
            'form[price]' => '1000',
        ));
        $this->assertStringEndsWith('/goods/', $client->getResponse()->headers->get('location'));

        // Test that record was added to the table
        $crawler = $client->followRedirect();
        $this->assertCount(4, $crawler->filter('.records_list tbody tr'), 'one good was added');
    }

    /**
     * @depends testCreateGood
     */
    public function testEditGood()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/goods/');
        $link = $crawler->selectLink('Ore')->link();
        $this->assertEquals('GET', $link->getMethod());
        $this->assertRegExp('~/goods/\d+/edit$~', $link->getUri());
        $this->assertCount(4, $crawler->filter('.records_list tbody tr'), '4 goods exist');

        // Edit form
        $crawler = $client->click($link);
        $form = $crawler->selectButton('Update')->form();
        $numberOfInlineHelps = $crawler->filter('.help-inline')->count();
        $this->assertEquals('POST', $form->getMethod());
        $this->assertEquals('PUT', $crawler->filter('form input[name="_method"]')->attr('value'));
        $this->assertRegExp('~/goods/\d+$~', $form->getUri());
        $this->assertEquals('Ore', $crawler->filter('input[name="form[name]"]')->attr('value'));
        $this->assertEquals('20', $crawler->filter('input[name="form[price]"]')->attr('value'));

        // Submit empty
        $crawler = $client->submit($form, array(
            'form[name]' => '',
            'form[price]' => '',
        ));
        $this->assertCount($numberOfInlineHelps + 2, $crawler->filter('.help-inline'));
        $form = $crawler->selectButton('Update')->form();

        // Submit invalid
        $crawler = $client->submit($form, array(
            'form[name]' => 'F',
            'form[price]' => '-10',
        ));
        $this->assertCount($numberOfInlineHelps + 2, $crawler->filter('.help-inline'));
        $form = $crawler->selectButton('Update')->form();

        // Submit correctly
        $client->submit($form, array(
            'form[name]' => 'Marble',
            'form[price]' => '40',
        ));
        $this->assertStringEndsWith('/goods/', $client->getResponse()->headers->get('location'));

        // Test that record was added to the table
        $crawler = $client->followRedirect();
        $this->assertCount(4, $crawler->filter('.records_list tbody tr'), 'no good was added');
        $this->assertCount(1, $crawler->filter('.records_list td:contains("Marble")'));
        $this->assertCount(1, $crawler->filter('.records_list td:contains("Gold")'));
        $this->assertCount(1, $crawler->filter('.records_list td:contains("Iron")'));
        $this->assertCount(1, $crawler->filter('.records_list td:contains("Jewels")'));
    }
}
