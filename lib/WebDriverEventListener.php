<?php

abstract class WebDriverEventListener {

	/**
	 * @param           $url
	 * @param WebDriver $driver
	 */
	public function beforeNavigateTo($url, EventFiringWebDriver $driver) { }

	/**
	 * @param           $url
	 * @param WebDriver $driver
	 */
	public function afterNavigateTo($url, EventFiringWebDriver $driver) { }

	/**
	 * @param WebDriver $driver
	 */
	public function beforeNavigateBack(EventFiringWebDriver $driver) { }

	/**
	 * @param WebDriver $driver
	 */
	public function afterNavigateBack(EventFiringWebDriver $driver) { }

	/**
	 * @param WebDriver $driver
	 */
	public function beforeNavigateForward(EventFiringWebDriver $driver) { }

	/**
	 * @param WebDriver $driver
	 */
	public function afterNavigateForward(EventFiringWebDriver $driver) { }

	/**
	 * @param WebDriverBy $by
	 * @param WebDriver   $driver
	 */
	public function beforeFindBy(WebDriverBy $by, EventFiringWebDriver $driver) { }

	/**
	 * @param WebDriverBy           $by
	 * @param EventFiringWebElement $element
	 * @param WebDriver             $driver
	 */
	public function afterFindBy(WebDriverBy $by, EventFiringWebElement $element, EventFiringWebDriver $driver) { }

	/**
	 * @param           $script
	 * @param WebDriver $driver
	 */
	public function beforeScript($script, EventFiringWebDriver $driver) { }

	/**
	 * @param           $script
	 * @param WebDriver $driver
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