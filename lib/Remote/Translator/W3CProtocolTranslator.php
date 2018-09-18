<?php

namespace Facebook\WebDriver\Remote\Translator;

use Facebook\WebDriver\Remote\DriverCommand;
use Facebook\WebDriver\Remote\ExecutableWebDriverCommand;
use Facebook\WebDriver\Remote\WebDriverCommand;
use Facebook\WebDriver\Remote\WebDriverDialect;
use Facebook\WebDriver\Support\W3CKeysEncoder;

class W3CProtocolTranslator implements WebDriverProtocolTranslator
{
    const ELEMENT_FILED = 'element-6066-11e4-a52e-4f735466cecf';

    /**
     * @see https://w3c.github.io/webdriver/#elements
     */
    protected static $commands = [
        DriverCommand::ACTIONS => ['method' => 'POST', 'url' => '/session/:sessionId/actions'],
        DriverCommand::CLEAR_ACTIONS_STATE => ['method' => 'DELETE', 'url' => '/session/:sessionId/actions'],
        DriverCommand::ACCEPT_ALERT => ['method' => 'POST', 'url' => '/session/:sessionId/alert/accept'],
        DriverCommand::ADD_COOKIE => ['method' => 'POST', 'url' => '/session/:sessionId/cookie'],
        DriverCommand::CLEAR_ELEMENT => ['method' => 'POST', 'url' => '/session/:sessionId/element/:id/clear'],
        DriverCommand::CLICK_ELEMENT => ['method' => 'POST', 'url' => '/session/:sessionId/element/:id/click'],
        DriverCommand::CLOSE => ['method' => 'DELETE', 'url' => '/session/:sessionId/window'],
        DriverCommand::DELETE_ALL_COOKIES => ['method' => 'DELETE', 'url' => '/session/:sessionId/cookie'],
        DriverCommand::DELETE_COOKIE => ['method' => 'DELETE', 'url' => '/session/:sessionId/cookie/:name'],
        DriverCommand::DISMISS_ALERT => ['method' => 'POST', 'url' => '/session/:sessionId/alert/dismiss'],
        DriverCommand::FIND_CHILD_ELEMENT => ['method' => 'POST', 'url' => '/session/:sessionId/element/:id/element'],
        DriverCommand::FIND_CHILD_ELEMENTS => ['method' => 'POST', 'url' => '/session/:sessionId/element/:id/elements'],
        DriverCommand::EXECUTE_SCRIPT => ['method' => 'POST', 'url' => '/session/:sessionId/execute/sync'],
        DriverCommand::EXECUTE_ASYNC_SCRIPT => ['method' => 'POST', 'url' => '/session/:sessionId/execute/async'],
        DriverCommand::FIND_ELEMENT => ['method' => 'POST', 'url' => '/session/:sessionId/element'],
        DriverCommand::FIND_ELEMENTS => ['method' => 'POST', 'url' => '/session/:sessionId/elements'],
        DriverCommand::SWITCH_TO_FRAME => ['method' => 'POST', 'url' => '/session/:sessionId/frame'],
        DriverCommand::SWITCH_TO_WINDOW => ['method' => 'POST', 'url' => '/session/:sessionId/window'],
        DriverCommand::GET => ['method' => 'POST', 'url' => '/session/:sessionId/url'],
        DriverCommand::GET_ACTIVE_ELEMENT => ['method' => 'POST', 'url' => '/session/:sessionId/element/active'],
        DriverCommand::GET_ALERT_TEXT => ['method' => 'GET', 'url' => '/session/:sessionId/alert/text'],
        DriverCommand::GET_ALL_COOKIES => ['method' => 'GET', 'url' => '/session/:sessionId/cookie'],
        DriverCommand::GET_AVAILABLE_LOG_TYPES => ['method' => 'GET', 'url' => '/session/:sessionId/log/types'],
        DriverCommand::GET_CURRENT_URL => ['method' => 'GET', 'url' => '/session/:sessionId/url'],
        DriverCommand::GET_CURRENT_WINDOW_HANDLE => ['method' => 'GET', 'url' => '/session/:sessionId/window'],
        DriverCommand::GET_ELEMENT_ATTRIBUTE => [
            'method' => 'GET',
            'url' => '/session/:sessionId/element/:id/attribute/:name',
        ],
        DriverCommand::GET_ELEMENT_VALUE_OF_CSS_PROPERTY => [
            'method' => 'GET',
            'url' => '/session/:sessionId/element/:id/css/:propertyName',
        ],
        DriverCommand::GET_ELEMENT_RECT => ['method' => 'GET', 'url' => '/session/:sessionId/element/:id/rect',],
        DriverCommand::GET_ELEMENT_LOCATION => DriverCommand::GET_ELEMENT_RECT,
        DriverCommand::GET_ELEMENT_LOCATION_ONCE_SCROLLED_INTO_VIEW => DriverCommand::EXECUTE_SCRIPT,
        DriverCommand::GET_ELEMENT_SIZE => DriverCommand::GET_ELEMENT_RECT,
        DriverCommand::GET_ELEMENT_TAG_NAME => ['method' => 'GET',  'url' => '/session/:sessionId/element/:id/name'],
        DriverCommand::GET_ELEMENT_TEXT => ['method' => 'GET', 'url' => '/session/:sessionId/element/:id/text'],
        DriverCommand::GET_LOG => ['method' => 'POST', 'url' => '/session/:sessionId/log'],
        DriverCommand::GET_PAGE_SOURCE => ['method' => 'GET', 'url' => '/session/:sessionId/source'],
        DriverCommand::GET_SCREEN_ORIENTATION => ['method' => 'GET', 'url' => '/session/:sessionId/orientation'],
        DriverCommand::GET_CAPABILITIES => ['method' => 'GET', 'url' => '/session/:sessionId'],
        DriverCommand::GET_TITLE => ['method' => 'GET', 'url' => '/session/:sessionId/title'],
        DriverCommand::GET_WINDOW_HANDLES => ['method' => 'GET', 'url' => '/session/:sessionId/window/handles'],
        DriverCommand::GET_WINDOW_POSITION => [
            'method' => 'GET',
            'url' => '/session/:sessionId/window/:windowHandle/position',
        ],
        DriverCommand::GET_WINDOW_SIZE => ['method' => 'GET', 'url' => '/session/:sessionId/window/:windowHandle/rect'],
        DriverCommand::GO_BACK => ['method' => 'POST', 'url' => '/session/:sessionId/back'],
        DriverCommand::GO_FORWARD => ['method' => 'POST', 'url' => '/session/:sessionId/forward'],
        DriverCommand::IS_ELEMENT_DISPLAYED => DriverCommand::EXECUTE_SCRIPT,
        DriverCommand::IS_ELEMENT_ENABLED => ['method' => 'GET', 'url' => '/session/:sessionId/element/:id/enabled'],
        DriverCommand::IS_ELEMENT_SELECTED => ['method' => 'GET', 'url' => '/session/:sessionId/element/:id/selected'],
        DriverCommand::MAXIMIZE_WINDOW => [
            'method' => 'POST',
            'url' => '/session/:sessionId/window/:windowHandle/maximize',
        ],
        DriverCommand::MOUSE_DOWN => DriverCommand::ACTIONS,
        DriverCommand::MOUSE_UP => DriverCommand::ACTIONS,
        DriverCommand::CLICK => DriverCommand::ACTIONS,
        DriverCommand::DOUBLE_CLICK => DriverCommand::ACTIONS,
        DriverCommand::MOVE_TO => DriverCommand::ACTIONS,
        DriverCommand::NEW_SESSION => ['method' => 'POST', 'url' => '/session'],
        DriverCommand::QUIT => ['method' => 'DELETE', 'url' => '/session/:sessionId'],
        DriverCommand::REFRESH => ['method' => 'POST', 'url' => '/session/:sessionId/refresh'],
        DriverCommand::UPLOAD_FILE => ['method' => 'POST', 'url' => '/session/:sessionId/file'], // undocumented
        DriverCommand::SEND_KEYS_TO_ACTIVE_ELEMENT => ['method' => 'POST', 'url' => '/session/:sessionId/keys'],
        DriverCommand::SET_ALERT_VALUE => ['method' => 'POST', 'url' => '/session/:sessionId/alert/text'],
        DriverCommand::SEND_KEYS_TO_ELEMENT => ['method' => 'POST', 'url' => '/session/:sessionId/element/:id/value'],
        DriverCommand::IMPLICITLY_WAIT => DriverCommand::SET_TIMEOUT,
        DriverCommand::SET_SCREEN_ORIENTATION => ['method' => 'POST', 'url' => '/session/:sessionId/orientation'],
        DriverCommand::SET_TIMEOUT => ['method' => 'POST', 'url' => '/session/:sessionId/timeouts'],
        DriverCommand::SET_SCRIPT_TIMEOUT => ['method' => 'POST', 'url' => '/session/:sessionId/timeouts'],
        DriverCommand::SET_WINDOW_POSITION => [
            'method' => 'POST',
            'url' => '/session/:sessionId/window/:windowHandle/position',
        ],
        DriverCommand::SET_WINDOW_RECT => ['method' => 'POST', 'url' => '/session/:sessionId/window/rect'],
        DriverCommand::SET_WINDOW_SIZE => DriverCommand::SET_WINDOW_RECT,
        DriverCommand::SUBMIT_ELEMENT => DriverCommand::EXECUTE_SCRIPT,
        DriverCommand::SCREENSHOT => ['method' => 'GET', 'url' => '/session/:sessionId/screenshot'],
        DriverCommand::TOUCH_SINGLE_TAP => ['method' => 'POST', 'url' => '/session/:sessionId/touch/click'],
        DriverCommand::TOUCH_DOWN => ['method' => 'POST', 'url' => '/session/:sessionId/touch/down'],
        DriverCommand::TOUCH_DOUBLE_TAP => ['method' => 'POST', 'url' => '/session/:sessionId/touch/doubleclick'],
        DriverCommand::TOUCH_FLICK => ['method' => 'POST', 'url' => '/session/:sessionId/touch/flick'],
        DriverCommand::TOUCH_LONG_PRESS => ['method' => 'POST', 'url' => '/session/:sessionId/touch/longclick'],
        DriverCommand::TOUCH_MOVE => ['method' => 'POST', 'url' => '/session/:sessionId/touch/move'],
        DriverCommand::TOUCH_SCROLL => ['method' => 'POST', 'url' => '/session/:sessionId/touch/scroll'],
        DriverCommand::TOUCH_UP => ['method' => 'POST', 'url' => '/session/:sessionId/touch/up'],
    ];

    /**
     * @param array $raw_element
     * @return string
     */
    public function translateElement($raw_element)
    {
        return $raw_element[self::ELEMENT_FILED];
    }

    /**
     * @param WebDriverCommand $command
     * @return ExecutableWebDriverCommand
     */
    public function translateCommand(WebDriverCommand $command)
    {
        if (!isset(self::$commands[$command->getName()])) {
            throw new \InvalidArgumentException($command->getName() . ' is not a valid command.');
        }
    
        switch ($command->getName()) {
            case DriverCommand::SET_SCRIPT_TIMEOUT:
                $command = $this->translateSetScriptTimeout($command);
                break;
            case DriverCommand::IMPLICITLY_WAIT:
                $command = $this->translateSetImplicitlyWait($command);
                break;
            case DriverCommand::SET_TIMEOUT:
                $command = $this->translateSetTimeout($command);
                break;
            case DriverCommand::GET_ELEMENT_ATTRIBUTE:
                $command = $this->translateGetElementAttribute($command);
                break;
            case DriverCommand::GET_ELEMENT_LOCATION_ONCE_SCROLLED_INTO_VIEW:
                $command = $this->translateGetElementOnceScrolledIntoView($command);
                break;
            case DriverCommand::IS_ELEMENT_DISPLAYED:
                $command = $this->translateIsDisplayed($command);
                break;
            case DriverCommand::SUBMIT_ELEMENT:
                $command = $this->translateSubmitElement($command);
                break;
            case DriverCommand::SEND_KEYS_TO_ELEMENT:
                $command = $this->translateSendKeyToElement($command);
                break;
            case DriverCommand::SWITCH_TO_WINDOW:
                $command = $this->translateSwitchToWindow($command);
                break;
            case DriverCommand::MOUSE_UP:
            case DriverCommand::MOUSE_DOWN:
            case DriverCommand::CLICK:
            case DriverCommand::DOUBLE_CLICK:
            case DriverCommand::MOVE_TO:
                $command = $this->translateAction($command);
                break;
        }

        $meta = $this->loadCommandMeta($command->getName());
        return new ExecutableWebDriverCommand(
            $meta['url'],
            $meta['method'],
            $command,
            WebDriverDialect::createW3C()
        );
    }

    /**
     * @param string $commandName
     * @return array
     */
    private function loadCommandMeta($commandName)
    {
        if (!isset(self::$commands[$commandName])) {
            throw new \InvalidArgumentException($commandName . ' is not a valid command.');
        }
        $meta = self::$commands[$commandName];
        if (is_string($meta)) {
            return $this->loadCommandMeta($meta);
        }
        return $meta;
    }

    /**
     * @param string $command_name
     * @param array $params
     * @return array
     */
    public function translateParameters($command_name, $params)
    {
        switch ($command_name) {
            case DriverCommand::FIND_ELEMENT:
            case DriverCommand::FIND_ELEMENTS:
            case DriverCommand::FIND_CHILD_ELEMENT:
            case DriverCommand::FIND_CHILD_ELEMENTS:
                if (!empty($params['using']) && in_array($params['using'], ['id', 'name'], true)) {
                    $params['value'] = sprintf('[%s="%s"]', $params['using'], $params['value']);
                    $params['using'] = 'css selector';
                }
                if ('class name' === $params['using']) {
                    $params['value'] = sprintf('.%s', $params['value']);
                    $params['using'] = 'css selector';
                }
                break;
            case DriverCommand::SEND_KEYS_TO_ELEMENT:
            case DriverCommand::SEND_KEYS_TO_ACTIVE_ELEMENT:
                $params['value'] = W3CKeysEncoder::encode($params['value']);
                break;
            case DriverCommand::MOUSE_UP:
                $params = [$this->translateActionPointerUp($params)];
                break;
            case DriverCommand::MOUSE_DOWN:
                $params = [$this->translateActionPointerDown($params)];
                break;
            case DriverCommand::CLICK:
                $params = [
                    $this->translateActionPointerDown($params),
                    $this->translateActionPointerUp($params)
                ];
                break;
            case DriverCommand::DOUBLE_CLICK:
                $params = [
                    $this->translateActionPointerDown($params),
                    $this->translateActionPointerUp($params),
                    $this->translateActionPointerDown($params),
                    $this->translateActionPointerUp($params)
                ];
                break;
            case DriverCommand::MOVE_TO:
                $params = [$this->translateActionPointerMoveTo($params)];
                break;
        }
        return $params;
    }

    /**
     * @param WebDriverCommand $command
     * @return WebDriverCommand
     */
    private function translateGetElementAttribute(WebDriverCommand $command)
    {
        return new WebDriverCommand(
            $command->getSessionID(),
            DriverCommand::EXECUTE_SCRIPT,
            [
                'script' => sprintf(
                    'return (%s).apply(null, arguments);',
                    file_get_contents(__DIR__ . '/resources/getAttribute.js')
                ),
                'args' => [
                    [self::ELEMENT_FILED => $command->getParameters()[':id']],
                    $command->getParameters()[':name']
                ]
            ]
        );
    }

    /**
     * @param WebDriverCommand $command
     * @return WebDriverCommand
     */
    private function translateGetElementOnceScrolledIntoView(WebDriverCommand $command)
    {
        return new WebDriverCommand(
            $command->getSessionID(),
            DriverCommand::EXECUTE_SCRIPT,
            [
                'script' => "arguments[0].scrollIntoView(true); return arguments[0].getBoundingClientRect();",
                'args' => [[self::ELEMENT_FILED => $command->getParameters()[':id']]]
            ]
        );
    }

    /**
     * @param WebDriverCommand $command
     * @return WebDriverCommand
     */
    private function translateIsDisplayed(WebDriverCommand $command)
    {
        return new WebDriverCommand(
            $command->getSessionID(),
            DriverCommand::EXECUTE_SCRIPT,
            [
                'script' => sprintf(
                    'return (%s).apply(null, arguments);',
                    file_get_contents(__DIR__ . '/resources/isDisplayed.js')
                ),
                'args' => [
                    [self::ELEMENT_FILED => $command->getParameters()[':id']]
                ]
            ]
        );
    }

    /**
     * @param WebDriverCommand $command
     * @return WebDriverCommand
     */
    private function translateSubmitElement(WebDriverCommand $command)
    {
        return new WebDriverCommand(
            $command->getSessionID(),
            DriverCommand::EXECUTE_SCRIPT,
            [
                'script' => "var form = arguments[0];\n" .
                    "while (form.nodeName != \"FORM\" && form.parentNode) {\n" .
                    "  form = form.parentNode;\n" .
                    "}\n" .
                    "if (!form) { throw Error('Unable to find containing form element'); }\n" .
                    "if (!form.ownerDocument) { throw Error('Unable to find owning document'); }\n" .
                    "var e = form.ownerDocument.createEvent('Event');\n" .
                    "e.initEvent('submit', true, true);\n" .
                    "if (form.dispatchEvent(e)) { HTMLFormElement.prototype.submit.call(form) }\n",
                'args' => [[self::ELEMENT_FILED => $command->getParameters()[':id']]]
            ]
        );
    }

    /**
     * @param WebDriverCommand $command
     * @return WebDriverCommand
     */
    private function translateSendKeyToElement(WebDriverCommand $command)
    {
        return new WebDriverCommand(
            $command->getSessionID(),
            $command->getName(),
            [
                'text' => implode($command->getParameters()['value']),
                'value' => W3CKeysEncoder::encode($command->getParameters()['value']),
                ':id' => $command->getParameters()[':id']
            ]
        );
    }

    /**
     * @param WebDriverCommand $command
     * @return WebDriverCommand
     */
    private function translateSwitchToWindow(WebDriverCommand $command)
    {
        return new WebDriverCommand(
            $command->getSessionID(),
            $command->getName(),
            ['handle' => $command->getParameters()['name']]
        );
    }

    /**
     * @param WebDriverCommand $command
     * @return WebDriverCommand
     */
    private function translateSetScriptTimeout(WebDriverCommand $command)
    {
        return new WebDriverCommand(
            $command->getSessionID(),
            $command->getName(),
            ['script' => $command->getParameters()['ms']]
        );
    }

    /**
     * @param WebDriverCommand $command
     * @return WebDriverCommand
     */
    private function translateSetImplicitlyWait(WebDriverCommand $command)
    {
        return new WebDriverCommand(
            $command->getSessionID(),
            $command->getName(),
            ['implicit' => $command->getParameters()['ms']]
        );
    }

    /**
     * @param WebDriverCommand $command
     * @return WebDriverCommand
     */
    private function translateSetTimeout(WebDriverCommand $command)
    {
        return new WebDriverCommand(
            $command->getSessionID(),
            $command->getName(),
            ['pageLoad' => $command->getParameters()['ms']]
        );
    }

    /**
     * @param WebDriverCommand $command
     * @return WebDriverCommand
     */
    private function translateAction(WebDriverCommand $command)
    {
        return new WebDriverCommand(
            $command->getSessionID(),
            DriverCommand::ACTIONS,
            ['actions' => [$this->encodeActions(
                $this->translateParameters($command->getName(), $command->getParameters())
            )
            ]]
        );
    }

    /**
     * @param array $params
     * @return array
     */
    private function translateActionPointerMoveTo(array $params)
    {
        $w3cParams = [
            'type' => 'pointerMove',
            'duration' => 250
        ];
        $w3cParams['x'] = !empty($params['xoffset']) ? (int) $params['xoffset'] : 0;
        $w3cParams['y'] = !empty($params['yoffset']) ? (int) $params['yoffset'] : 0;
        if (!empty($params['element'])) {
            $w3cParams['origin'] = [self::ELEMENT_FILED => $params['element']];
        } else {
            $w3cParams['origin'] = 'viewport';
        }
        return $w3cParams;
    }

    /**
     * @param array $params
     * @return array
     */
    private function translateActionPointerDown(array $params)
    {
        return [
            'type' => 'pointerDown',
            'duration' => 0,
            'button' => isset($params['button']) ? $params['button'] : 0
        ];
    }

    /**
     * @param array $params
     * @return array
     */
    private function translateActionPointerUp(array $params)
    {
        return [
            'type' => 'pointerUp',
            'duration' => 0,
            'button' => isset($params['button']) ? $params['button'] : 0
        ];
    }

    /**
     * @param array $params
     * @return array
     */
    private function pause(array $params)
    {
        return [
            'type' => 'pause',
            'duration' => $params['duration'] * 1000
        ];
    }

    /**
     * @param array $actions
     * @return array
     */
    public function encodeActions($actions)
    {
        return [
            'type' => 'pointer',
            'parameters' => ['pointerType' => 'mouse'],
            'id' => uniqid('mouse_', true),
            'actions' => $actions
        ];
    }
}
