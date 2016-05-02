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

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class ApplicationNotFoundException extends NotFoundHttpException
{
    /**
     * @var string
     */
    private $application;

    /**
     * @param string $application
     */
    public function __construct($application)
    {
        parent::__construct(sprintf('Application with name "%s" not found', $application));
    }

    /**
     * @return string
     */
    public function getApplication()
    {
        return $this->application;
    }
}