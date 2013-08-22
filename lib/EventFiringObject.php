<?php

abstract class EventFiringObject {

	/**
	 * @var WebDriverDispatcher
	 */
	protected $_dispatcher;

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
	 * @param $method
	 */
	protected function _dispatch($method) {

		$arguments = func_get_args();
		unset($arguments[0]);
		$arguments[] = $this;

		if($this->_dispatcher)
			$this->_dispatcher->dispatch($method, $arguments);

	}

}