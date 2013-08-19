<?php

class WebDriverDispatcher {

	/**
	 * @var array
	 */
	protected $_listeners = [];

	/**
	 * @var EventFiringWebDriver
	 */
	protected $_driver = null;

	/**
	 * this is needed so that EventFiringWebElement can pass the driver to the exception handling, so you take screenshots et al
	 * @param EventFiringWebDriver $driver
	 * @return $this
	 */
	public function setDefaultDriver(EventFiringWebDriver $driver) {
		$this->_driver = $driver;
		return $this;
	}

	/**
	 * @return null|EventFiringWebDriver
	 */
	public function getDefaultDriver() {
		return $this->_driver;
	}

	/**
	 * @param WebDriverEventListener $listener
	 * @return $this
	 */
	public function register(WebDriverEventListener $listener) {

		$this->_listeners[] = $listener;

		return $this;

	}

	/**
	 * @param WebDriverEventListener $listener
	 * @return $this
	 */
	public function unregister(WebDriverEventListener $listener) {

		$key = array_search($listener, $this->_listeners, true);
		if ($key)
			unset($this->_listeners[$key]);

		return $this;


	}

	/**
	 * @param $method
	 * @param $arguments
	 * @return $this
	 */
	public function dispatch($method, $arguments) {

		foreach ($this->_listeners as $listener)
			call_user_func_array([$listener, $method], $arguments);

		return $this;

	}

}