<?php
/*
* This file is part of the logger-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class AppKernel extends Kernel
{

    /**
     * @var \Closure
     */
    private $kernelModifier = null;

    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        return array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
            new Abc\Bundle\LoggerBundle\AbcLoggerBundle()
        );
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return $this->rootDir . '/../../../../build/app/cache';
    }

    /**
     * @return string
     */
    public function getLogDir()
    {
        return $this->rootDir . '/../../../../build/app/logs';
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        parent::boot();

        if($kernelModifier = $this->kernelModifier)
        {
            $kernelModifier($this);
            $this->kernelModifier = null;
        };
    }

    /**
     * @param \Closure $kernelModifier
     * @see http://blog.lyrixx.info/2013/04/12/symfony2-how-to-mock-services-during-functional-tests.html
     */
    public function setKernelModifier(\Closure $kernelModifier)
    {
        $this->kernelModifier = $kernelModifier;

        // We force the kernel to shutdown to be sure the next request will boot it
        $this->shutdown();
    }
}