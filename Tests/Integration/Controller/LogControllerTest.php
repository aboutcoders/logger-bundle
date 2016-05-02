<?php
/*
* This file is part of the logger-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\LoggerBundle\Tests\Integration\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Kernel;

class LogControllerTest extends WebTestCase
{
    public function testLog()
    {
        $application = 'foobar';
        $parameters =  ['level' => 'info', 'message' => 'This is a log message'];
        
        $client = static::createClient();

        $url = '/api/log/' . $application;

        $client->request(
            'POST',
            $url,
            $parameters,
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            null,
            'json'
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
        $this->assertEmpty($client->getResponse()->getContent());

        $filename = static::$kernel->getLogDir().'/foobar.log';

        $this->assertFileExists($filename);
        $this->assertContains('This is a log message', file_get_contents($filename));
    }

    public function testLogActionWithInvalidApplication()
    {
        $url    = '/api/log/undefined';
        $client = static::createClient();
        $parameters = ['level' => 'emergency', 'message' => 'foobar'];

        $client->request(
            'POST',
            $url,
            $parameters,
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            null,
            'json'
        );

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}