<?php

namespace Facebook\WebDriver\Remote;


use Facebook\WebDriver\Exception\WebDriverException;
use Facebook\WebDriver\Remote\Action\JsonWireProtocolActionPerformer;
use Facebook\WebDriver\Remote\Action\W3CProtocolActionPerformer;
use Facebook\WebDriver\Remote\Action\WebDriverActionPerformer;
use PhpCoveralls\Bundle\CoverallsBundle\Entity\Git\Remote;

class WebDriverActionPerformerFactory
{
    /**
     * @param WebDriverDialect $dialect
     * @param RemoteExecuteMethod $interactionExecutionMethod
     * @return JsonWireProtocolActionPerformer|W3CProtocolActionPerformer
     * @throws WebDriverException
     */
    public static function create(
        WebDriverDialect $dialect,
        RemoteExecuteMethod $interactionExecutionMethod
    ) {
        if (!$dialect->isW3C()) {
            return new JsonWireProtocolActionPerformer();
        } else if ($interactionExecutionMethod instanceof W3CActionExecuteMethod) {
            return new W3CProtocolActionPerformer($interactionExecutionMethod);
        }
        throw new WebDriverException('Cannot resolve dialect: ' . $dialect);
    }
}