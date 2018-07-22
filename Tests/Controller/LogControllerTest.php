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

use Abc\Bundle\LoggerBundle\Logger\Registry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class LogControllerTest extends WebTestCase
{
    /**
     * @var Registry|\PHPUnit_Framework_MockObject_MockObject
     */
    private $registry;

    /**
     * @var LoggerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $logger;

    public function setUp()
    {
        $this->registry = $this->getMock('Abc\Bundle\LoggerBundle\Logger\Registry');
        $this->logger = $this->getMock(LoggerInterface::class);
    }

    /**
     * @param string $application
     * @param array  $parameters
     * @dataProvider provideValidParameters
     */
    public function testLogActionWithValidParameters($application, $parameters)
    {
        $url    = '/api/log/' . $application;
        $client = static::createClient();

        $this->mockRegistry();

        $this->registry->expects($this->once())
            ->method('get')
            ->with($application)
            ->willReturn($this->logger);

        if(!isset($parameters['context']))
        {
            $this->logger->expects($this->once())
                ->method('log')
                ->with($parameters['level'], $parameters['message']);
        }
        else
        {
            $this->logger->expects($this->once())
                ->method('log')
                ->with($parameters['level'], $parameters['message'], $parameters['context']);
        }

        $client->request(
            'POST',
            $url,
            $parameters,
            [],
            ['CONTENT_TYPE' => 'application/json'],
            null,
            'json'
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
        $this->assertEmpty($client->getResponse()->getContent());
    }

    /**
     * @param string $application
     * @param array  $parameters
     * @dataProvider provideInvalidParameters
     */
    public function testLogActionWithInvalidParameters($application, $parameters)
    {
        $client = static::createClient();

        $this->mockRegistry();

        $this->registry->expects($this->never())
            ->method('get');

        $url = '/api/log/' . $application;

        $client->request(
            'POST',
            $url,
            $parameters,
            [],
            ['CONTENT_TYPE' => 'application/json'],
            null,
            'json'
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    /**
     * @return array
     */
    public static function provideValidParameters()
    {
        return [
            ['application-name', ['level' => 'emergency', 'message' => 'foobar']],
            ['application-name', ['level' => 'alert', 'message' => 'foobar']],
            ['application-name', ['level' => 'critical', 'message' => 'foobar']],
            ['application-name', ['level' => 'error', 'message' => 'foobar']],
            ['application-name', ['level' => 'warning', 'message' => 'foobar']],
            ['application-name', ['level' => 'notice', 'message' => 'foobar']],
            ['application-name', ['level' => 'info', 'message' => 'foobar']],
            ['application-name', ['level' => 'debug', 'message' => 'foobar']],
            ['application-name', ['level' => 'emergency', 'message' => 'foobar', 'context' => ['foo' => 'bar']]]
        ];
    }

    /**
     * @return array
     */
    public static function provideInvalidParameters()
    {
        return [
            ['application-name', ['level' => 'foobar', 'message' => 'foobar']],
        ];
    }

    /**
     * Injects a mock object for the service abc.logger.registry
     *
     * @see http://blog.lyrixx.info/2013/04/12/symfony2-how-to-mock-services-during-functional-tests.html
     */
    private function mockRegistry()
    {
        $registry = $this->registry;

        /**
         * @ignore
         */
        static::$kernel->setKernelModifier(
            function (KernelInterface $kernel) use ($registry) {
                $kernel->getContainer()->set('abc.logger.registry', $registry);
            }
        );
    }
}