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

		$this->_dispatcher = $dispatcher ? $dispatcher : new WebDriverDispatcher();
		if(!$this->_dispatcher->getDefaultDriver())
			$this->_dispatcher->setDefaultDriver($this);

		$this->_webdriver  = $webdriver;

		return $this;

	}

	public function __call($method, array $arguments = array()) {

		try {

			return call_user_func_array([$this, $method], $arguments);

		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this);
			throw $exception;

		}

	}

	/**
	 * @return WebDriverDispatcher
	 */
	public function getDispatcher() {
		return $this->_dispatcher;
	}

	/**
	 * @return WebDriver
	 */
	public function getWebDriver() {
		return $this->_webdriver;
	}

	/**
	 * @param $method
	 */
	protected function _dispatch($method) {

		$arguments = func_get_args();
		unset($arguments[0]);
		$arguments[] = $this;

		$this->getDispatcher()->dispatch($method, $arguments);

	}

	/**
	 * Return the EventFiringWebElement with the given id.
	 *
	 * @param string $id The id of the element to be created.
	 * @return EventFiringWebElement
	 */
	protected function newElement($id) {
		return new EventFiringWebElement($this->_webdriver->getExecutor(), $id, $this->getDispatcher());
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

		$raw_elements = $this->_webdriver->getExecutor()->execute('findElements', [
			'using' => $by->getMechanism(),
			'value' => $by->getValue()
		]);

		$elements = array();
		foreach ($raw_elements as $raw_element)
			$elements[] = $this->newElement($raw_element['ELEMENT']);

		$this->_dispatch('afterFindBy', $by, $elements);

		return $elements;

	}

	/**
	 * @param WebDriverBy $by
	 * @return EventFiringWebDriver
	 */
	protected function findElement(WebDriverBy $by) {

		$this->_dispatch('beforeFindBy', $by);

		$raw_element = $this->_webdriver->getExecutor()->execute('findElement', [
			'using' => $by->getMechanism(),
			'value' => $by->getValue()
		]);
		$element     = $this->newElement($raw_element['ELEMENT']);

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

	/**
	 * Not yet implemented by WebDriver
	 *
	 * @return EventFiringWebDriver
	 */
//	protected function back() {
//
//		$this->_dispatch('beforeNavigateBack');
//		$this->_webdriver->back();
//		$this->_dispatch('afterNavigateBack');
//		return $this;
//
//	}

	/**
	 * Not yet implemented by WebDriver
	 *
	 * @return EventFiringWebDriver
	 */
//	protected function forward() {
//
//		$this->_dispatch('beforeNavigateForward');
//		$this->_webdriver->forward();
//		$this->_dispatch('afterNavigateForward');
//		return $this;
//
//	}

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
		return $this->_webdriver->navigate();
	}

	/**
	 * @return WebDriverTargetLocator
	 */
	protected function switchTo() {
		return $this->_webdriver->switchTo();
	}

}