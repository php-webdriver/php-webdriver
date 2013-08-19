<?php

abstract class WebDriverEventListener {

	/**
	 * @param string               $url
	 * @param EventFiringWebDriver $driver
	 */
	public function beforeNavigateTo($url, EventFiringWebDriver $driver) { }

	/**
	 * @param string               $url
	 * @param EventFiringWebDriver $driver
	 */
	public function afterNavigateTo($url, EventFiringWebDriver $driver) { }

	/**
	 * @param EventFiringWebDriver $driver
	 */
	public function beforeNavigateBack(EventFiringWebDriver $driver) { }

	/**
	 * @param EventFiringWebDriver $driver
	 */
	public function afterNavigateBack(EventFiringWebDriver $driver) { }

	/**
	 * @param EventFiringWebDriver $driver
	 */
	public function beforeNavigateForward(EventFiringWebDriver $driver) { }

	/**
	 * @param EventFiringWebDriver $driver
	 */
	public function afterNavigateForward(EventFiringWebDriver $driver) { }

	/**
	 * @param WebDriverBy          $by
	 * @param EventFiringWebDriver $driver
	 */
	public function beforeFindBy(WebDriverBy $by, EventFiringWebDriver $driver) { }

	/**
	 * @param WebDriverBy           $by
	 * @param EventFiringWebElement $element
	 * @param EventFiringWebDriver  $driver
	 */
	public function afterFindBy(WebDriverBy $by, EventFiringWebElement $element, EventFiringWebDriver $driver) { }

	/**
	 * @param string               $script
	 * @param EventFiringWebDriver $driver
	 */
	public function beforeScript($script, EventFiringWebDriver $driver) { }

	/**
	 * @param string               $script
	 * @param EventFiringWebDriver $driver
	 */
	public function afterScript($script, EventFiringWebDriver $driver) { }

	/**
	 * @param EventFiringWebElement $element
	 */
	public function beforeClickOn(EventFiringWebElement $element) { }

	/**
	 * @param EventFiringWebElement $element
	 */
	public function afterClickOn(EventFiringWebElement $element) { }

	/**
	 * @param EventFiringWebElement $element
	 */
	public function beforeChangeValueOf(EventFiringWebElement $element) { }

	/**
	 * @param EventFiringWebElement $element
	 */
	public function afterChangeValueOf(EventFiringWebElement $element) { }

	/**
	 * @param WebDriverException   $exception
	 * @param EventFiringWebDriver $driver
	 */
	public function onException(WebDriverException $exception, EventFiringWebDriver $driver = null) { }

}