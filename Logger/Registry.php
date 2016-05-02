<?php
/*
* This file is part of the logger-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\LoggerBundle\Logger;

use Psr\Log\LoggerInterface;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class Registry
{
    /**
     * @var array
     */
    private $applications = [];

    /**
     * @param string $application
     * @param LoggerInterface $logger
     * @return void
     */
    public function register($application, LoggerInterface $logger)
    {
       $this->applications[$application] = $logger;
    }

    /**
     * @param string $application
     * @return LoggerInterface $logger
     * @throws ApplicationNotFoundException
     */
    public function get($application)
    {
        if(!isset($this->applications[$application]))
        {
            throw new ApplicationNotFoundException($application);
        }

        return $this->applications[$application];
    }
}