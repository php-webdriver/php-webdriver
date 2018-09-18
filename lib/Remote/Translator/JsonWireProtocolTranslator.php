<?php

namespace Facebook\WebDriver\Remote\Translator;

use Facebook\WebDriver\Remote\DriverCommand;
use Facebook\WebDriver\Remote\ExecutableWebDriverCommand;
use Facebook\WebDriver\Remote\WebDriverCommand;
use Facebook\WebDriver\Remote\WebDriverDialect;
use Facebook\WebDriver\WebDriverKeys;

class JsonWireProtocolTranslator implements WebDriverProtocolTranslator
{
    /**
     * @see https://github.com/SeleniumHQ/selenium/wiki/JsonWireProtocol#command-reference
     */
    protected static $commands = [
        DriverCommand::ACCEPT_ALERT => ['method' => 'POST', 'url' => '/session/:sessionId/accept_alert'],
        DriverCommand::ADD_COOKIE => ['method' => 'POST', 'url' => '/session/:sessionId/cookie'],
        DriverCommand::CLEAR_ELEMENT => ['method' => 'POST', 'url' => '/session/:sessionId/element/:id/clear'],
        DriverCommand::CLICK_ELEMENT => ['method' => 'POST', 'url' => '/session/:sessionId/element/:id/click'],
        DriverCommand::CLOSE => ['method' => 'DELETE', 'url' => '/session/:sessionId/window'],
        DriverCommand::DELETE_ALL_COOKIES => ['method' => 'DELETE', 'url' => '/session/:sessionId/cookie'],
        DriverCommand::DELETE_COOKIE => ['method' => 'DELETE', 'url' => '/session/:sessionId/cookie/:name'],
        DriverCommand::DISMISS_ALERT => ['method' => 'POST', 'url' => '/session/:sessionId/dismiss_alert'],
        DriverCommand::ELEMENT_EQUALS => ['method' => 'GET', 'url' => '/session/:sessionId/element/:id/equals/:other'],
        DriverCommand::FIND_CHILD_ELEMENT => ['method' => 'POST', 'url' => '/session/:sessionId/element/:id/element'],
        DriverCommand::FIND_CHILD_ELEMENTS => ['method' => 'POST', 'url' => '/session/:sessionId/element/:id/elements'],
        DriverCommand::EXECUTE_SCRIPT => ['method' => 'POST', 'url' => '/session/:sessionId/execute'],
        DriverCommand::EXECUTE_ASYNC_SCRIPT => ['method' => 'POST', 'url' => '/session/:sessionId/execute_async'],
        DriverCommand::FIND_ELEMENT => ['method' => 'POST', 'url' => '/session/:sessionId/element'],
        DriverCommand::FIND_ELEMENTS => ['method' => 'POST', 'url' => '/session/:sessionId/elements'],
        DriverCommand::SWITCH_TO_FRAME => ['method' => 'POST', 'url' => '/session/:sessionId/frame'],
        DriverCommand::SWITCH_TO_WINDOW => ['method' => 'POST', 'url' => '/session/:sessionId/window'],
        DriverCommand::GET => ['method' => 'POST', 'url' => '/session/:sessionId/url'],
        DriverCommand::GET_ACTIVE_ELEMENT => ['method' => 'POST', 'url' => '/session/:sessionId/element/active'],
        DriverCommand::GET_ALERT_TEXT => ['method' => 'GET', 'url' => '/session/:sessionId/alert_text'],
        DriverCommand::GET_ALL_COOKIES => ['method' => 'GET', 'url' => '/session/:sessionId/cookie'],
        DriverCommand::GET_ALL_SESSIONS => ['method' => 'GET', 'url' => '/sessions'],
        DriverCommand::GET_AVAILABLE_LOG_TYPES => ['method' => 'GET', 'url' => '/session/:sessionId/log/types'],
        DriverCommand::GET_CURRENT_URL => ['method' => 'GET', 'url' => '/session/:sessionId/url'],
        DriverCommand::GET_CURRENT_WINDOW_HANDLE => ['method' => 'GET', 'url' => '/session/:sessionId/window_handle'],
        DriverCommand::GET_ELEMENT_ATTRIBUTE => [
            'method' => 'GET',
            'url' => '/session/:sessionId/element/:id/attribute/:name',
        ],
        DriverCommand::GET_ELEMENT_VALUE_OF_CSS_PROPERTY => [
            'method' => 'GET',
            'url' => '/session/:sessionId/element/:id/css/:propertyName',
        ],
        DriverCommand::GET_ELEMENT_LOCATION => [
            'method' => 'GET',
            'url' => '/session/:sessionId/element/:id/location',
        ],
        DriverCommand::GET_ELEMENT_LOCATION_ONCE_SCROLLED_INTO_VIEW => [
            'method' => 'GET',
            'url' => '/session/:sessionId/element/:id/location_in_view',
        ],
        DriverCommand::GET_ELEMENT_SIZE => ['method' => 'GET', 'url' => '/session/:sessionId/element/:id/size'],
        DriverCommand::GET_ELEMENT_TAG_NAME => ['method' => 'GET',  'url' => '/session/:sessionId/element/:id/name'],
        DriverCommand::GET_ELEMENT_TEXT => ['method' => 'GET', 'url' => '/session/:sessionId/element/:id/text'],
        DriverCommand::GET_LOG => ['method' => 'POST', 'url' => '/session/:sessionId/log'],
        DriverCommand::GET_PAGE_SOURCE => ['method' => 'GET', 'url' => '/session/:sessionId/source'],
        DriverCommand::GET_SCREEN_ORIENTATION => ['method' => 'GET', 'url' => '/session/:sessionId/orientation'],
        DriverCommand::GET_CAPABILITIES => ['method' => 'GET', 'url' => '/session/:sessionId'],
        DriverCommand::GET_TITLE => ['method' => 'GET', 'url' => '/session/:sessionId/title'],
        DriverCommand::GET_WINDOW_HANDLES => ['method' => 'GET', 'url' => '/session/:sessionId/window_handles'],
        DriverCommand::GET_WINDOW_POSITION => [
            'method' => 'GET',
            'url' => '/session/:sessionId/window/:windowHandle/position',
        ],
        DriverCommand::GET_WINDOW_SIZE => ['method' => 'GET', 'url' => '/session/:sessionId/window/:windowHandle/size'],
        DriverCommand::GO_BACK => ['method' => 'POST', 'url' => '/session/:sessionId/back'],
        DriverCommand::GO_FORWARD => ['method' => 'POST', 'url' => '/session/:sessionId/forward'],
        DriverCommand::IS_ELEMENT_DISPLAYED => [
            'method' => 'GET',
            'url' => '/session/:sessionId/element/:id/displayed',
        ],
        DriverCommand::IS_ELEMENT_ENABLED => ['method' => 'GET', 'url' => '/session/:sessionId/element/:id/enabled'],
        DriverCommand::IS_ELEMENT_SELECTED => ['method' => 'GET', 'url' => '/session/:sessionId/element/:id/selected'],
        DriverCommand::MAXIMIZE_WINDOW => [
            'method' => 'POST',
            'url' => '/session/:sessionId/window/:windowHandle/maximize',
        ],
        DriverCommand::MOUSE_DOWN => ['method' => 'POST', 'url' => '/session/:sessionId/buttondown'],
        DriverCommand::MOUSE_UP => ['method' => 'POST', 'url' => '/session/:sessionId/buttonup'],
        DriverCommand::CLICK => ['method' => 'POST', 'url' => '/session/:sessionId/click'],
        DriverCommand::DOUBLE_CLICK => ['method' => 'POST', 'url' => '/session/:sessionId/doubleclick'],
        DriverCommand::MOVE_TO => ['method' => 'POST', 'url' => '/session/:sessionId/moveto'],
        DriverCommand::NEW_SESSION => ['method' => 'POST', 'url' => '/session'],
        DriverCommand::QUIT => ['method' => 'DELETE', 'url' => '/session/:sessionId'],
        DriverCommand::REFRESH => ['method' => 'POST', 'url' => '/session/:sessionId/refresh'],
        DriverCommand::UPLOAD_FILE => ['method' => 'POST', 'url' => '/session/:sessionId/file'], // undocumented
        DriverCommand::SEND_KEYS_TO_ACTIVE_ELEMENT => ['method' => 'POST', 'url' => '/session/:sessionId/keys'],
        DriverCommand::SET_ALERT_VALUE => ['method' => 'POST', 'url' => '/session/:sessionId/alert_text'],
        DriverCommand::SEND_KEYS_TO_ELEMENT => ['method' => 'POST', 'url' => '/session/:sessionId/element/:id/value'],
        DriverCommand::IMPLICITLY_WAIT => ['method' => 'POST', 'url' => '/session/:sessionId/timeouts/implicit_wait'],
        DriverCommand::SET_SCREEN_ORIENTATION => ['method' => 'POST', 'url' => '/session/:sessionId/orientation'],
        DriverCommand::SET_TIMEOUT => ['method' => 'POST', 'url' => '/session/:sessionId/timeouts'],
        DriverCommand::SET_SCRIPT_TIMEOUT => ['method' => 'POST', 'url' => '/session/:sessionId/timeouts/async_script'],
        DriverCommand::SET_WINDOW_POSITION => [
            'method' => 'POST',
            'url' => '/session/:sessionId/window/:windowHandle/position',
        ],
        DriverCommand::SET_WINDOW_SIZE => [
            'method' => 'POST',
            'url' => '/session/:sessionId/window/:windowHandle/size',
        ],
        DriverCommand::SUBMIT_ELEMENT => ['method' => 'POST', 'url' => '/session/:sessionId/element/:id/submit'],
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
        return $raw_element['ELEMENT'];
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
        $meta = self::$commands[$command->getName()];
        return new ExecutableWebDriverCommand(
            $meta['url'],
            $meta['method'],
            $command,
            WebDriverDialect::createJsonWireProtocol()
        );
    }

    /**
     * @param string $command_name
     * @param array $params
     * @return array
     */
    public function translateParameters($command_name, $params)
    {
        switch ($command_name) {
            case DriverCommand::SEND_KEYS_TO_ELEMENT:
            case DriverCommand::SEND_KEYS_TO_ACTIVE_ELEMENT:
                $params['value'] = WebDriverKeys::encode($params['value']);
                break;
        }
        return $params;
    }
}