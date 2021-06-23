<?php

namespace PhpWebDriver\WebDriver\Support\Events;

use PhpWebDriver\WebDriver\Exception\WebDriverException;
use PhpWebDriver\WebDriver\WebDriverDispatcher;
use PhpWebDriver\WebDriver\WebDriverNavigationInterface;

class EventFiringWebDriverNavigation implements WebDriverNavigationInterface
{
    /**
     * @var WebDriverNavigationInterface
     */
    protected $navigator;
    /**
     * @var WebDriverDispatcher
     */
    protected $dispatcher;

    /**
     * @param WebDriverNavigationInterface $navigator
     * @param WebDriverDispatcher $dispatcher
     */
    public function __construct(WebDriverNavigationInterface $navigator, WebDriverDispatcher $dispatcher)
    {
        $this->navigator = $navigator;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return WebDriverDispatcher
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * @return WebDriverNavigationInterface
     */
    public function getNavigator()
    {
        return $this->navigator;
    }

    public function back()
    {
        $this->dispatch(
            'beforeNavigateBack',
            $this->getDispatcher()->getDefaultDriver()
        );
        try {
            $this->navigator->back();
        } catch (WebDriverException $exception) {
            $this->dispatchOnException($exception);
        }
        $this->dispatch(
            'afterNavigateBack',
            $this->getDispatcher()->getDefaultDriver()
        );

        return $this;
    }

    public function forward()
    {
        $this->dispatch(
            'beforeNavigateForward',
            $this->getDispatcher()->getDefaultDriver()
        );
        try {
            $this->navigator->forward();
        } catch (WebDriverException $exception) {
            $this->dispatchOnException($exception);
        }
        $this->dispatch(
            'afterNavigateForward',
            $this->getDispatcher()->getDefaultDriver()
        );

        return $this;
    }

    public function refresh()
    {
        try {
            $this->navigator->refresh();

            return $this;
        } catch (WebDriverException $exception) {
            $this->dispatchOnException($exception);
            throw $exception;
        }
    }

    public function to($url)
    {
        $this->dispatch(
            'beforeNavigateTo',
            $url,
            $this->getDispatcher()->getDefaultDriver()
        );

        try {
            $this->navigator->to($url);
        } catch (WebDriverException $exception) {
            $this->dispatchOnException($exception);
            throw $exception;
        }

        $this->dispatch(
            'afterNavigateTo',
            $url,
            $this->getDispatcher()->getDefaultDriver()
        );

        return $this;
    }

    /**
     * @param mixed $method
     * @param mixed ...$arguments
     */
    protected function dispatch($method, ...$arguments)
    {
        if (!$this->dispatcher) {
            return;
        }

        $this->dispatcher->dispatch($method, $arguments);
    }

    /**
     * @param WebDriverException $exception
     */
    protected function dispatchOnException(WebDriverException $exception)
    {
        $this->dispatch('onException', $exception);
    }
}

class_alias(\PhpWebDriver\WebDriver\Support\Events\EventFiringWebDriverNavigation::class, \Facebook\WebDriver\Support\Events\EventFiringWebDriverNavigation::class);
