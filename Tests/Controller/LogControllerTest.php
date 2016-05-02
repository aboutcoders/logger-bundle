<?php
/*
* This file is part of the logger-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\LoggerBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LogControllerTest extends WebTestCase
{
    /**
     * @param string $application
     * @param array  $parameters
     * @dataProvider provideValidParameters
     */
    public function testLogActionWithValidParameters($application, $parameters)
    {
        $client = static::createClient();

        $url = '/api/log/';

        $client->request(
            'POST',
            $url,
            $parameters,
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            null,
            'json'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEmpty($client->getResponse()->getContent());
    }

    public static function provideValidParameters()
    {
        return [
            ['application-name', ['message' => 'foobar']]
        ];
    }
}