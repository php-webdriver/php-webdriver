<?php

class EventFiringWebDriverNavigation {

	/**
	 * @var WebDriverNavigation
	 */
	protected $_navigator;

	/**
	 * @var WebDriverDispatcher
	 */
	protected $_dispatcher;

	/**
	 * @param WebDriverNavigation $navigator
	 * @param WebDriverDispatcher $dispatcher
	 */
	public function __construct(WebDriverNavigation $navigator, WebDriverDispatcher $dispatcher) {

		$this->_navigator  = $navigator;
		$this->_dispatcher = $dispatcher;
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
	 * @return WebDriverNavigation
	 */
	public function getNavigator() {
		return $this->_navigator;
	}

	/**
	 * @return $this
	 * @throws Exception|WebDriverException
	 */
	public function back() {

		$this->_dispatch('beforeNavigateBack', $this->getDispatcher()->getDefaultDriver());
		try {
			$this->_navigator->back();
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception);
			throw $exception;

		}
		$this->_dispatch('afterNavigateBack', $this->getDispatcher()->getDefaultDriver());
		return $this;

	}

	/**
	 * @return $this
	 * @throws Exception|WebDriverException
	 */
	public function forward() {

		$this->_dispatch('beforeNavigateForward', $this->getDispatcher()->getDefaultDriver());
		try {
			$this->_navigator->forward();
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception);
			throw $exception;

		}
		$this->_dispatch('afterNavigateForward', $this->getDispatcher()->getDefaultDriver());
		return $this;

	}

	/**
	 * @return $this
	 * @throws Exception|WebDriverException
	 */
	public function refresh() {
		try {
			$this->_navigator->refresh();
			return $this;
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception);
			throw $exception;

		}
	}

	/**
	 * @param $url
	 * @return $this
	 * @throws Exception|WebDriverException
	 */
	public function to($url) {

		$this->_dispatch('beforeNavigateTo', $url, $this->getDispatcher()->getDefaultDriver());
		try {
			$this->_navigator->to($url);
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception);
			throw $exception;

		}
		$this->_dispatch('afterNavigateTo', $url, $this->getDispatcher()->getDefaultDriver());
		return $this;

	}

}