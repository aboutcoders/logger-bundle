<?php
/*
* This file is part of the logger-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\LoggerBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class LogController extends FOSRestController
{

    /**
     * Creates a log entry.
     *
     * @ApiDoc(
     *   section="AbcLoggerBundle",
     *   statusCodes = {
     *     200 = "Returned on success",
     *     400 = "Returned in case of a validation error"
     *   }
     * )
     *
     * @Post("/log")
     *
     * @RequestParam(name="level", requirements="(emergency|alert|critical|error|warning|notice|info|debug)", description="The log level", strict=true, nullable=false)
     * @RequestParam(name="message", description="The log message", strict=true, nullable=false)
     * @RequestParam(name="context", description="The context array", strict=true, array=true, nullable=true)
     *
     * @param string  $application
     * @param ParamFetcherInterface $paramFetcher
     */
    public function postAction(ParamFetcherInterface $paramFetcher)
    {
        return;

        $application = 'foo';

        $level   = $paramFetcher->get('level');
        $message = $paramFetcher->get('message');
        $context = $paramFetcher->get('context');

        $this->getLogger($application)->log($level, $message, $context);
    }

    /**
     * @param string $application
     * @return LoggerInterface
     */
    protected function getLogger($application)
    {
        return $this->get('logger');
    }

    /**
     * @return ValidatorInterface
     */
    protected function getValidator()
    {
        return $this->get('validator');
    }
}