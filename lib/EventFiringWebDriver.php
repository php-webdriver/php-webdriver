<?php

class EventFiringWebDriver {

	/**
	 * @var WebDriver
	 */
	protected $_webdriver;

	/**
	 * @var WebDriverDispatcher
	 */
	protected $_dispatcher;

	/**
	 * @param WebDriver           $webdriver
	 * @param WebDriverDispatcher $dispatcher
	 */
	public function __construct(WebDriver $webdriver, WebDriverDispatcher $dispatcher = null) {

		$this->_dispatcher = $dispatcher ? $dispatcher : new WebDriverDispatcher;
		if(!$this->_dispatcher->getDefaultDriver())
			$this->_dispatcher->setDefaultDriver($this);

		$this->_webdriver  = $webdriver;

		return $this;

	}

	/**
	 * @return WebDriverDispatcher
	 */
	public function getDispatcher() {
		return $this->_dispatcher;
	}

	/**
	 * @param $method
	 */
	protected function _dispatch($method) {

		if(!$this->_dispatcher)
			return;

		$arguments = func_get_args();
		unset($arguments[0]);
		$this->_dispatcher->dispatch($method, $arguments);

	}

	/**
	 * @return WebDriver
	 */
	public function getWebDriver() {
		return $this->_webdriver;
	}

	/**
	 * @param WebDriverElement $element
	 * @return EventFiringWebElement
	 */
	private function newElement(WebDriverElement $element) {
		return new EventFiringWebElement($element, $this->getDispatcher());
	}

	/**
	 * @param $url
	 * @return $this
	 * @throws WebDriverException
	 */
	public function get($url) {

		$this->_dispatch('beforeNavigateTo', $url, $this);
		try {
			$this->_webdriver->get($url);
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this);
			throw $exception;

		}
		$this->_dispatch('afterNavigateTo', $url, $this);

		return $this;

	}

	/**
	 * @param WebDriverBy $by
	 * @return array
	 * @throws WebDriverException
	 */
	public function findElements(WebDriverBy $by) {

		$this->_dispatch('beforeFindBy', $by, null, $this);

		try {

			$elements = array();
			foreach($this->_webdriver->findElements($by) as $element)
				$elements[] = $this->newElement($element);

		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this);
			throw $exception;

		}

		$this->_dispatch('afterFindBy', $by, null, $this);

		return $elements;

	}

	/**
	 * @param WebDriverBy $by
	 * @return EventFiringWebElement
	 * @throws WebDriverException
	 */
	public function findElement(WebDriverBy $by) {

		$this->_dispatch('beforeFindBy', $by, null, $this);
		try {
			$element = $this->newElement($this->_webdriver->findElement($by));
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this);
			throw $exception;

		}
		$this->_dispatch('afterFindBy', $by, null, $this);

		return $element;

	}

	/**
	 * @param       $script
	 * @param array $arguments
	 * @return mixed
	 * @throws WebDriverException
	 */
	public function executeScript($script, array $arguments = array()) {

		$this->_dispatch('beforeScript', $script, $this);
		try {
			$result = $this->_webdriver->executeScript($script, $arguments);
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this);
			throw $exception;

		}
		$this->_dispatch('afterScript', $script, $this);

		return $result;

	}

	/**
	 * @return $this
	 * @throws WebDriverException
	 */
	public function close() {
		try {
			$this->_webdriver->close();
			return $this;
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this);
			throw $exception;

		}
	}

	/**
	 * @return string
	 * @throws WebDriverException
	 */
	public function getCurrentURL() {
		try {
			return $this->_webdriver->getCurrentURL();
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this);
			throw $exception;

		}
	}

	/**
	 * @return string
	 * @throws WebDriverException
	 */
	public function getPageSource() {
		try {
			return $this->_webdriver->getPageSource();
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this);
			throw $exception;

		}
	}

	/**
	 * @return string
	 * @throws WebDriverException
	 */
	public function getTitle() {
		try {
			return $this->_webdriver->getTitle();
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this);
			throw $exception;

		}
	}

	/**
	 * @return string
	 * @throws WebDriverException
	 */
	public function getWindowHandle() {
		try {
			return $this->_webdriver->getWindowHandle();
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this);
			throw $exception;

		}
	}

	/**
	 * @return array
	 * @throws WebDriverException
	 */
	public function getWindowHandles() {
		try {
			return $this->_webdriver->getWindowHandles();
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this);
			throw $exception;

		}
	}

	/**
	 * @throws WebDriverException
	 */
	public function quit() {
		try {
			$this->_webdriver->quit();
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this);
			throw $exception;

		}
	}

	/**
	 * @param null $save_as
	 * @return string
	 * @throws WebDriverException
	 */
	public function takeScreenshot($save_as = null) {
		try {
			return $this->_webdriver->takeScreenshot($save_as);
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this);
			throw $exception;

		}
	}

	/**
	 * @param int $timeout_in_second
	 * @param int $interval_in_millisecond
	 * @return WebDriverWait
	 * @throws WebDriverException
	 */
	public function wait($timeout_in_second = 30, $interval_in_millisecond = 250) {
		try {
			return $this->_webdriver->wait($timeout_in_second, $interval_in_millisecond);
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this);
			throw $exception;

		}
	}

	/**
	 * @return WebDriverOptions
	 * @throws WebDriverException
	 */
	public function manage() {
		try {
			return $this->_webdriver->manage();
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this);
			throw $exception;

		}
	}

	/**
	 * @return EventFiringWebDriverNavigation
	 * @throws WebDriverException
	 */
	public function navigate() {
		try {
			return new EventFiringWebDriverNavigation($this->_webdriver->navigate(), $this->getDispatcher());
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this);
			throw $exception;

		}
	}

	/**
	 * @return WebDriverTargetLocator
	 * @throws WebDriverException
	 */
	public function switchTo() {
		try {
			return $this->_webdriver->switchTo();
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this);
			throw $exception;

		}
	}

}