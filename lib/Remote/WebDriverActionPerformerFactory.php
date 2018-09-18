<?php

namespace Facebook\WebDriver\Remote;

use Facebook\WebDriver\Exception\WebDriverException;
use Facebook\WebDriver\Remote\Action\JsonWireProtocolActionPerformer;
use Facebook\WebDriver\Remote\Action\W3CProtocolActionPerformer;

class WebDriverActionPerformerFactory
{
    /**
     * @param WebDriverDialect $dialect
     * @param RemoteExecuteMethod $interactionExecutionMethod
     * @throws WebDriverException
     * @return JsonWireProtocolActionPerformer|W3CProtocolActionPerformer
     */
    public static function create(
        WebDriverDialect $dialect,
        RemoteExecuteMethod $interactionExecutionMethod
    ) {
        if (!$dialect->isW3C()) {
            return new JsonWireProtocolActionPerformer();
        }
        if ($interactionExecutionMethod instanceof BunchActionExecuteMethod) {
            return new W3CProtocolActionPerformer($interactionExecutionMethod);
        }
        throw new WebDriverException('Cannot resolve dialect: ' . $dialect);
    }
}
