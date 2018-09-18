<?php

namespace Facebook\WebDriver\Remote\Translator;

use Facebook\WebDriver\Remote\DriverCommand;
use Facebook\WebDriver\Remote\ExecutableWebDriverCommand;
use Facebook\WebDriver\Remote\WebDriverCommand;
use PHPUnit\Framework\TestCase;

class W3CProtocolTranslatorTest extends TestCase
{
    const ELEMENT_ID = 'element-6066-11e4-a52e-4f735466cecf';

    public function testShouldTranslateElement()
    {
        $expectedElementId = 'uuid-3423dsa-sdfsd';

        $systemUnderTest = new W3CProtocolTranslator();
        $this->assertEquals(
            $expectedElementId,
            $systemUnderTest->translateElement([self::ELEMENT_ID => $expectedElementId])
        );
    }

    /**
     * @dataProvider getCommandDataProvider
     * @param WebDriverCommand $command
     * @param array|callable $expectedParameters
     */
    public function testShouldTranslateCommand(WebDriverCommand $command, $expectedParameters)
    {
        $systemUnderTest = new W3CProtocolTranslator();
        $translatedCommand = $systemUnderTest->translateCommand($command);
        $this->assertInstanceOf(ExecutableWebDriverCommand::class, $translatedCommand);
        if (is_callable($expectedParameters)) {
            $expectedParameters($translatedCommand->getParameters(), $translatedCommand->getName());
        } else {
            $this->assertEquals($translatedCommand->getParameters(), $expectedParameters);
        }
    }

    public function getCommandDataProvider()
    {
        $sessionId = 'Session-Id';
        return [
            DriverCommand::SET_SCRIPT_TIMEOUT => [
                new WebDriverCommand(
                    $sessionId,
                    DriverCommand::SET_SCRIPT_TIMEOUT,
                    ['ms' => 3600]
                ),
                ['script' => 3600],
            ],
            DriverCommand::IMPLICITLY_WAIT => [
                new WebDriverCommand(
                    $sessionId,
                    DriverCommand::IMPLICITLY_WAIT,
                    ['ms' => 3600]
                ),
                ['implicit' => 3600],
            ],
            DriverCommand::SET_TIMEOUT => [
                new WebDriverCommand(
                    $sessionId,
                    DriverCommand::SET_TIMEOUT,
                    ['ms' => 3600]
                ),
                ['pageLoad' => 3600],
            ],
            DriverCommand::GET_ELEMENT_ATTRIBUTE => [
                new WebDriverCommand(
                    $sessionId,
                    DriverCommand::GET_ELEMENT_ATTRIBUTE,
                    [':name' => 'title', ':id' => 'element-ID']
                ),
                function (array $parameters) {
                    $this->assertRegExp('/\.apply\(null, arguments\)\;\Z/', $parameters['script']);
                    $this->assertEquals([[self::ELEMENT_ID => 'element-ID'], 'title'], $parameters['args']);
                },
            ],
            DriverCommand::GET_ELEMENT_LOCATION_ONCE_SCROLLED_INTO_VIEW => [
                new WebDriverCommand(
                    $sessionId,
                    DriverCommand::GET_ELEMENT_LOCATION_ONCE_SCROLLED_INTO_VIEW,
                    [':id' => 'element-ID']
                ),
                function (array $parameters) {
                    $this->assertRegExp('/getBoundingClientRect\(\)\;\Z/', $parameters['script']);
                    $this->assertEquals([[self::ELEMENT_ID => 'element-ID']], $parameters['args']);
                },
            ],
            DriverCommand::IS_ELEMENT_DISPLAYED => [
                new WebDriverCommand(
                    $sessionId,
                    DriverCommand::IS_ELEMENT_DISPLAYED,
                    [':id' => 'element-ID']
                ),
                function (array $parameters) {
                    $this->assertRegExp('/\.apply\(null, arguments\)\;\Z/', $parameters['script']);
                    $this->assertEquals([[self::ELEMENT_ID => 'element-ID']], $parameters['args']);
                },
            ],
            DriverCommand::SUBMIT_ELEMENT => [
                new WebDriverCommand(
                    $sessionId,
                    DriverCommand::SUBMIT_ELEMENT,
                    [':id' => 'element-ID']
                ),
                function (array $parameters) {
                    $this->assertRegExp('/\Avar form = arguments\[0\]\;/', $parameters['script']);
                    $this->assertEquals([[self::ELEMENT_ID => 'element-ID']], $parameters['args']);
                },
            ],
            DriverCommand::SEND_KEYS_TO_ELEMENT => [
                new WebDriverCommand(
                    $sessionId,
                    DriverCommand::SEND_KEYS_TO_ELEMENT,
                    ['value' => ['Test message'], ':id' => 'element-ID']
                ),
                function (array $parameters) {
                    $this->assertEquals(
                        [
                            'text' => 'Test message',
                            'value' => ['T', 'e', 's', 't', ' ', 'm', 'e', 's', 's', 'a', 'g', 'e'],
                            ':id' => 'element-ID',
                        ],
                        $parameters
                    );
                },
            ],
            DriverCommand::SWITCH_TO_WINDOW => [
                new WebDriverCommand(
                    $sessionId,
                    DriverCommand::SWITCH_TO_WINDOW,
                    ['name' => 'window-ID']
                ),
                ['handle' => 'window-ID'],
            ],
            DriverCommand::MOUSE_UP => [
                new WebDriverCommand(
                    $sessionId,
                    DriverCommand::MOUSE_UP,
                    []
                ),
                function (array $parameters, $commandName) {
                    $this->assertEquals(DriverCommand::ACTIONS, $commandName);
                    if (!empty($parameters['actions'][0]['id'])) {
                        $parameters['actions'][0]['id'] = 'mouse-ID';
                    }
                    $this->assertEquals(
                        [
                            'actions' => [
                                [
                                    'type' => 'pointer',
                                    'parameters' => [
                                        'pointerType' => 'mouse',
                                    ],
                                    'id' => 'mouse-ID',
                                    'actions' => [
                                        [
                                            'type' => 'pointerUp',
                                            'duration' => 0,
                                            'button' => 0,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        $parameters
                    );
                },
            ],
            DriverCommand::MOUSE_DOWN => [
                new WebDriverCommand(
                    $sessionId,
                    DriverCommand::MOUSE_DOWN,
                    []
                ),
                function (array $parameters, $commandName) {
                    $this->assertEquals(DriverCommand::ACTIONS, $commandName);
                    if (!empty($parameters['actions'][0]['id'])) {
                        $parameters['actions'][0]['id'] = 'mouse-ID';
                    }
                    $this->assertEquals(
                        [
                            'actions' => [
                                [
                                    'type' => 'pointer',
                                    'parameters' => [
                                        'pointerType' => 'mouse',
                                    ],
                                    'id' => 'mouse-ID',
                                    'actions' => [
                                        [
                                            'type' => 'pointerDown',
                                            'duration' => 0,
                                            'button' => 0,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        $parameters
                    );
                },
            ],
            DriverCommand::CLICK => [
                new WebDriverCommand(
                    $sessionId,
                    DriverCommand::CLICK,
                    []
                ),
                function (array $parameters, $commandName) {
                    $this->assertEquals(DriverCommand::ACTIONS, $commandName);
                    $this->assertEquals('pointerDown', $parameters['actions'][0]['actions'][0]['type']);
                    $this->assertEquals('pointerUp', $parameters['actions'][0]['actions'][1]['type']);
                },
            ],
            DriverCommand::DOUBLE_CLICK => [
                new WebDriverCommand(
                    $sessionId,
                    DriverCommand::DOUBLE_CLICK,
                    []
                ),
                function (array $parameters, $commandName) {
                    $this->assertEquals(DriverCommand::ACTIONS, $commandName);
                    $this->assertEquals('pointerDown', $parameters['actions'][0]['actions'][0]['type']);
                    $this->assertEquals('pointerUp', $parameters['actions'][0]['actions'][1]['type']);
                    $this->assertEquals('pointerDown', $parameters['actions'][0]['actions'][2]['type']);
                    $this->assertEquals('pointerUp', $parameters['actions'][0]['actions'][3]['type']);
                },
            ],
            DriverCommand::MOVE_TO => [
                new WebDriverCommand(
                    $sessionId,
                    DriverCommand::MOVE_TO,
                    []
                ),
                function (array $parameters, $commandName) {
                    $this->assertEquals(DriverCommand::ACTIONS, $commandName);
                    $this->assertEquals('pointerMove', $parameters['actions'][0]['actions'][0]['type']);
                },
            ],
        ];
    }

    public function testShouldThrowExceptionForNotValidCommand()
    {
        $this->expectException(\InvalidArgumentException::class);

        $command = new WebDriverCommand(
            $sessionId = 'session-ID-890',
            $name = 'some_not_valid_command',
            $parameters = []
        );

        $systemUnderTest = new W3CProtocolTranslator();
        $systemUnderTest->translateCommand($command);
    }

    /**
     * @dataProvider getParametersDataProvider
     * @param string $commandName
     * @param array $params
     */
    public function testShouldTranslateParameters($commandName, $params)
    {
        $systemUnderTest = new W3CProtocolTranslator();
        $this->assertNotEquals($params, $systemUnderTest->translateParameters($commandName, $params));
    }

    /**
     * @return array
     */
    public function getParametersDataProvider()
    {
        return [
            DriverCommand::FIND_ELEMENT . ' using[id] to "css_selector"' => [
                DriverCommand::FIND_ELEMENT,
                [
                    'using' => 'id',
                    'value' => 'element-Id',
                ],
                [
                    'using' => 'css selector',
                    'value' => '[id="element-Id"]',
                ],
            ],
            DriverCommand::FIND_ELEMENT . ' using[name] to "css_selector"' => [
                DriverCommand::FIND_ELEMENT,
                [
                    'using' => 'name',
                    'value' => 'element-Name',
                ],
                [
                    'using' => 'css selector',
                    'value' => '[name="element-Name"]',
                ],
            ],
            DriverCommand::FIND_ELEMENTS . ' using[id] to "css_selector"' => [
                DriverCommand::FIND_ELEMENTS,
                [
                    'using' => 'id',
                    'value' => 'element-Id',
                ],
                [
                    'using' => 'css selector',
                    'value' => '[id="element-Id"]',
                ],
            ],
            DriverCommand::FIND_ELEMENTS . ' using[name] to "css_selector"' => [
                DriverCommand::FIND_ELEMENTS,
                [
                    'using' => 'name',
                    'value' => 'element-Name',
                ],
                [
                    'using' => 'css selector',
                    'value' => '[name="element-Name"]',
                ],
            ],
            DriverCommand::FIND_CHILD_ELEMENT . ' using[id] to "css_selector"' => [
                DriverCommand::FIND_CHILD_ELEMENT,
                [
                    'using' => 'id',
                    'value' => 'element-Id',
                    ':id' => 'parent-Element-Id',
                ],
                [
                    'using' => 'css selector',
                    'value' => '[id="element-Id"]',
                    ':id' => 'parent-Element-Id',
                ],
            ],
            DriverCommand::FIND_CHILD_ELEMENT . ' using[name] to "css_selector"' => [
                DriverCommand::FIND_CHILD_ELEMENT,
                [
                    'using' => 'name',
                    'value' => 'element-Name',
                    ':id' => 'parent-Element-Id',
                ],
                [
                    'using' => 'css selector',
                    'value' => '[name="element-Name"]',
                    ':id' => 'parent-Element-Id',
                ],
            ],
            DriverCommand::FIND_CHILD_ELEMENTS . ' using[id] to "css_selector"' => [
                DriverCommand::FIND_CHILD_ELEMENTS,
                [
                    'using' => 'id',
                    'value' => 'element-Id',
                    ':id' => 'parent-Element-Id',
                ],
                [
                    'using' => 'css selector',
                    'value' => '[id="element-Id"]',
                    ':id' => 'parent-Element-Id',
                ],
            ],
            DriverCommand::FIND_CHILD_ELEMENTS . ' using[name] to "css_selector"' => [
                DriverCommand::FIND_CHILD_ELEMENTS,
                [
                    'using' => 'name',
                    'value' => 'element-Name',
                    ':id' => 'parent-Element-Id',
                ],
                [
                    'using' => 'css selector',
                    'value' => '[name="element-Name"]',
                    ':id' => 'parent-Element-Id',
                ],
            ],
            DriverCommand::SEND_KEYS_TO_ELEMENT => [
                DriverCommand::SEND_KEYS_TO_ELEMENT,
                [
                    'value' => ['Test'],
                ],
                [
                    'value' => ['T', 'e', 's', 't']
                ],
            ],
            DriverCommand::SEND_KEYS_TO_ACTIVE_ELEMENT => [
                DriverCommand::SEND_KEYS_TO_ACTIVE_ELEMENT,
                [
                    'value' => ['Test'],
                ],
                [
                    'value' => ['T', 'e', 's', 't']
                ],
            ],
            DriverCommand::MOUSE_UP => [
                DriverCommand::MOUSE_UP,
                [],
                [
                    'type' => 'pointerUp',
                    'duration' => 0,
                    'button' => 0,
                ],
            ],
            DriverCommand::MOUSE_DOWN => [
                DriverCommand::MOUSE_DOWN,
                [
                    'button' => 2
                ],
                [
                    'type' => 'pointerDown',
                    'duration' => 0,
                    'button' => 2,
                ],
            ],
            DriverCommand::CLICK => [
                DriverCommand::CLICK,
                [],
                [
                    [
                        'type' => 'pointerDown',
                        'duration' => 0,
                        'button' => 0,
                    ],
                    [
                        'type' => 'pointerUp',
                        'duration' => 0,
                        'button' => 0,
                    ],
                    [
                        'type' => 'pointerDown',
                        'duration' => 0,
                        'button' => 0,
                    ],
                    [
                        'type' => 'pointerUp',
                        'duration' => 0,
                        'button' => 0,
                    ],
                ],
            ],
            DriverCommand::DOUBLE_CLICK => [
                DriverCommand::DOUBLE_CLICK,
                [
                    'button' => 2
                ],
                [
                    [
                        'type' => 'pointerDown',
                        'duration' => 0,
                        'button' => 2,
                    ],
                    [
                        'type' => 'pointerUp',
                        'duration' => 0,
                        'button' => 2,
                    ],
                    [
                        'type' => 'pointerDown',
                        'duration' => 0,
                        'button' => 2,
                    ],
                    [
                        'type' => 'pointerUp',
                        'duration' => 0,
                        'button' => 2,
                    ],
                    [
                        'type' => 'pointerDown',
                        'duration' => 0,
                        'button' => 2,
                    ],
                    [
                        'type' => 'pointerUp',
                        'duration' => 0,
                        'button' => 2,
                    ],
                    [
                        'type' => 'pointerDown',
                        'duration' => 0,
                        'button' => 2,
                    ],
                    [
                        'type' => 'pointerUp',
                        'duration' => 0,
                        'button' => 2,
                    ],
                ],
            ]
        ];
    }
}
