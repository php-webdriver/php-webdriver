<?php

class EventFiringWebDriverNavigation extends EventFiringObject {

	/**
	 * @var WebDriverNavigation
	 */
	protected $_navigator;

	/**
	 * @param WebDriverNavigation $navigator
	 * @param WebDriverDispatcher $dispatcher
	 */
	public function __construct(WebDriverNavigation $navigator, WebDriverDispatcher $dispatcher = null) {

		$this->_navigator  = $navigator;
		$this->_dispatcher = $dispatcher;
		return $this;

	}

	/**
	 * @return WebDriverNavigation
	 */
	public function getNavigator() {
		return $this->_navigator;
	}

	// Implement the following from WebDriverNavigation as protected methods so __call catches exceptions

	/**
	 * @return $this
	 */
	protected function back() {

		$this->_dispatch('beforeNavigateBack', $this->getDispatcher()->getDefaultDriver());
		$this->_navigator->back();
		$this->_dispatch('afterNavigateBack', $this->getDispatcher()->getDefaultDriver());
		return $this;

	}

	/**
	 * @return $this
	 */
	protected function forward() {

		$this->_dispatch('beforeNavigateForward', $this->getDispatcher()->getDefaultDriver());
		$this->_navigator->forward();
		$this->_dispatch('afterNavigateForward', $this->getDispatcher()->getDefaultDriver());
		return $this;

	}

	/**
	 * @return $this
	 */
	protected function refresh() {
		$this->_navigator->refresh();
		return $this;
	}

	/**
	 * @param $url
	 * @return $this
	 */
	protected function to($url) {
		$this->_navigator->to($url);
		return $this;
	}

}