<?php
/*
* This file is part of the logger-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\LoggerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class RegisterApplicationsPass implements CompilerPassInterface
{
    /**
     * @var string
     */
    private $registryService;

    /**
     * @var string
     */
    private $applicationsParameter;

    /**
     * Constructor.
     *
     * @param string $registryService The service name of the registry in processed container
     * @param string $applicationsParameter The parameter name of the application config in the processed container
     */
    public function __construct($registryService = 'abc.logger.registry', $applicationsParameter = 'abc.logger.applications')
    {
        $this->registryService       = $registryService;
        $this->applicationsParameter = $applicationsParameter;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition($this->registryService) && !$container->hasAlias($this->registryService)) {
            return;
        }

        if (!$container->hasParameter($this->applicationsParameter) || !is_array($container->getParameter($this->applicationsParameter))) {
            return;
        }

        $registry     = $container->findDefinition($this->registryService);
        $applications = $container->getParameter($this->applicationsParameter);

        foreach ($applications as $application => $appConfig) {

            $logger = $container->findDefinition('monolog.logger');

            // in symfony version <= 2.7 we get an error if class name of definition is not set
            if (version_compare(Kernel::VERSION, '2.8.0', '<')) {
                $logger->setClass('Monolog\Logger');
            }

            $registry->addMethodCall('register', [$application, $logger]);
        }
    }
}