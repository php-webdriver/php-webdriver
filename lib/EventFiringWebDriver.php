<?php

class EventFiringWebDriver extends EventFiringObject {

	/**
	 * @var WebDriver
	 */
	protected $_webdriver;

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
	 * @return WebDriver
	 */
	public function getWebDriver() {
		return $this->_webdriver;
	}

	/**
	 * @param WebDriverElement $element
	 * @return EventFiringWebElement
	 */
	protected function newElement(WebDriverElement $element) {
		return new EventFiringWebElement($element, $this->getDispatcher());
	}

	/**
	 * @param string $url
	 * @return EventFiringWebDriver $this
	 */
	protected function get($url) {

		$this->_dispatch('beforeNavigateTo', $url);
		$this->_webdriver->get($url);
		$this->_dispatch('afterNavigateTo', $url);

		return $this;

	}

	/**
	 * @param WebDriverBy $by
	 * @return array A list of EventFiringWebDriver, or empty
	 */
	protected function findElements(WebDriverBy $by) {

		$this->_dispatch('beforeFindBy', $by);

		$elements = array();
		foreach($this->_webdriver->findElements($by) as $element)
			$elements[] = $this->newElement($element);

		$this->_dispatch('afterFindBy', $by, $elements);

		return $elements;

	}

	/**
	 * @param WebDriverBy $by
	 * @return EventFiringWebDriver
	 */
	protected function findElement(WebDriverBy $by) {

		$this->_dispatch('beforeFindBy', $by);
		$element = $this->newElement($this->_webdriver->findElement($by));
		$this->_dispatch('afterFindBy', $by, $element);

		return $element;

	}

	/**
	 * @param string $script
	 * @param array  $arguments
	 * @return mixed
	 */
	protected function executeScript($script, array $arguments = array()) {

		$this->_dispatch('beforeScript', $script);
		$result = $this->_webdriver->executeScript($script, $arguments);
		$this->_dispatch('afterScript', $script);

		return $result;

	}

	// Implement the following from WebDriver as protected methods so __call catches exceptions

	/**
	 * @return EventFiringWebDriver
	 */
	protected function close() {
		$this->_webdriver->close();

		return $this;
	}

	/**
	 * @return string
	 */
	protected function getCurrentURL() {
		return $this->_webdriver->getCurrentURL();
	}

	/**
	 * @return string
	 */
	protected function getPageSource() {
		return $this->_webdriver->getPageSource();
	}

	/**
	 * @return string
	 */
	protected function getTitle() {
		return $this->_webdriver->getTitle();
	}

	/**
	 * @return string
	 */
	protected function getWindowHandle() {
		return $this->_webdriver->getWindowHandle();
	}

	/**
	 * @return array
	 */
	protected function getWindowHandles() {
		return $this->_webdriver->getWindowHandles();
	}

	/**
	 * @return void
	 */
	protected function quit() {
		$this->_webdriver->quit();
	}

	/**
	 * @param string|null $save_as
	 * @return string
	 */
	protected function takeScreenshot($save_as = null) {
		return $this->_webdriver->takeScreenshot($save_as);
	}

	/**
	 * @param int $timeout_in_second
	 * @param int $interval_in_millisecond
	 * @return WebDriverWait
	 */
	protected function wait($timeout_in_second = 30, $interval_in_millisecond = 250) {
		return $this->_webdriver->wait($timeout_in_second, $interval_in_millisecond);
	}

	/**
	 * @return WebDriverOptions
	 */
	protected function manage() {
		return $this->_webdriver->manage();
	}

	/**
	 * @return WebDriverNavigation
	 */
	protected function navigate() {
		return new EventFiringWebDriverNavigation($this->_webdriver->navigate(), $this->getDispatcher());
	}

	/**
	 * @return WebDriverTargetLocator
	 */
	protected function switchTo() {
		return $this->_webdriver->switchTo();
	}

}