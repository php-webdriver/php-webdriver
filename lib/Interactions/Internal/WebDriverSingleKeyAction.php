<?php

namespace PhpWebDriver\WebDriver\Interactions\Internal;

use PhpWebDriver\WebDriver\Internal\WebDriverLocatable;
use PhpWebDriver\WebDriver\WebDriverAction;
use PhpWebDriver\WebDriver\WebDriverKeyboard;
use PhpWebDriver\WebDriver\WebDriverKeys;
use PhpWebDriver\WebDriver\WebDriverMouse;

abstract class WebDriverSingleKeyAction extends WebDriverKeysRelatedAction implements WebDriverAction
{
    const MODIFIER_KEYS = [
        WebDriverKeys::SHIFT,
        WebDriverKeys::LEFT_SHIFT,
        WebDriverKeys::RIGHT_SHIFT,
        WebDriverKeys::CONTROL,
        WebDriverKeys::LEFT_CONTROL,
        WebDriverKeys::RIGHT_CONTROL,
        WebDriverKeys::ALT,
        WebDriverKeys::LEFT_ALT,
        WebDriverKeys::RIGHT_ALT,
        WebDriverKeys::META,
        WebDriverKeys::RIGHT_META,
        WebDriverKeys::COMMAND,
    ];

    /** @var string */
    protected $key;

    /**
     * @param string $key
     * @todo Remove default $key value in next major version (BC)
     */
    public function __construct(
        WebDriverKeyboard $keyboard,
        WebDriverMouse $mouse,
        WebDriverLocatable $location_provider = null,
        $key = ''
    ) {
        parent::__construct($keyboard, $mouse, $location_provider);

        if (!in_array($key, self::MODIFIER_KEYS, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'keyDown / keyUp actions can only be used for modifier keys, but "%s" was given',
                    $key
                )
            );
        }
        $this->key = $key;
    }
}

class_alias(\PhpWebDriver\WebDriver\Interactions\Internal\WebDriverSingleKeyAction::class, \Facebook\WebDriver\Interactions\Internal\WebDriverSingleKeyAction::class);
