<?php
// Copyright 2004-present Facebook. All Rights Reserved.
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//     http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

class EventFiringWebElement {

	/**
	 * @var WebDriverElement
	 */
	protected $_element;

	/**
	 * @var WebDriverDispatcher
	 */
	protected $_dispatcher;

	/**
	 * @param WebDriverElement    $element
	 * @param WebDriverDispatcher $dispatcher
	 */
	public function __construct(WebDriverElement $element,  WebDriverDispatcher $dispatcher) {

		$this->_element = $element;
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
	 * @return WebDriverElement
	 */
	public function getElement() {
		return $this->_element;
	}

	/**
	 * @param WebDriverElement $element
	 * @return EventFiringWebElement
	 */
	private function newElement(WebDriverElement $element) {
		return new EventFiringWebElement($element, $this->getDispatcher());
	}

	/**
	 * @param $value
	 * @return $this
	 * @throws WebDriverException
	 */
	public function sendKeys($value) {

		$this->_dispatch('beforeChangeValueOf', $this);
		try {
			$this->_element->sendKeys($value);
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this->_dispatcher->getDefaultDriver());
			throw $exception;

		}
		$this->_dispatch('afterChangeValueOf', $this);
		return $this;

	}

	/**
	 * @return $this
	 * @throws WebDriverException
	 */
	public function click() {

		$this->_dispatch('beforeClickOn', $this);
		try {
			$this->_element->click();
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this->_dispatcher->getDefaultDriver());
			throw $exception;

		}
		$this->_dispatch('afterClickOn', $this);
		return $this;

	}

	/**
	 * @param WebDriverBy $by
	 * @return EventFiringWebElement
	 * @throws WebDriverException
	 */
	public function findElement(WebDriverBy $by) {

		$this->_dispatch('beforeFindBy', $by, $this, $this->_dispatcher->getDefaultDriver());
		try {
			$element = $this->newElement($this->_element->findElement($by));
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this->_dispatcher->getDefaultDriver());
			throw $exception;

		}
		$this->_dispatch('afterFindBy', $by, $this, $this->_dispatcher->getDefaultDriver());

		return $element;
	}

	/**
	 * @param WebDriverBy $by
	 * @return array
	 * @throws WebDriverException
	 */
	public function findElements(WebDriverBy $by) {

		$this->_dispatch('beforeFindBy', $by, $this, $this->_dispatcher->getDefaultDriver());

		try {

			$elements = array();
			foreach($this->_element->findElements($by) as $element)
				$elements[] = $this->newElement($element);

		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this->_dispatcher->getDefaultDriver());
			throw $exception;

		}

		$this->_dispatch('afterFindBy', $by, $this, $this->_dispatcher->getDefaultDriver());

		return $elements;
	}

	/**
	 * @return $this
	 * @throws WebDriverException
	 */
	public function clear() {
		try {
			$this->_element->clear();
			return $this;
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this->_dispatcher->getDefaultDriver());
			throw $exception;

		}
	}

	/**
	 * @param $attribute_name
	 * @return string
	 * @throws WebDriverException
	 */
	public function getAttribute($attribute_name) {
		try {
			return $this->_element->getAttribute($attribute_name);
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this->_dispatcher->getDefaultDriver());
			throw $exception;

		}
	}

	/**
	 * @param $css_property_name
	 * @return string
	 * @throws WebDriverException
	 */
	public function getCSSValue($css_property_name) {
		try {
			return $this->_element->getCSSValue($css_property_name);
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this->_dispatcher->getDefaultDriver());
			throw $exception;

		}
	}

	/**
	 * @return WebDriverLocation
	 * @throws WebDriverException
	 */
	public function getLocation() {
		try {
			return $this->_element->getLocation();
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this->_dispatcher->getDefaultDriver());
			throw $exception;

		}
	}

	/**
	 * @return WebDriverLocation
	 * @throws WebDriverException
	 */
	public function getLocationOnScreenOnceScrolledIntoView() {
		try {
			return $this->_element->getLocationOnScreenOnceScrolledIntoView();
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this->_dispatcher->getDefaultDriver());
			throw $exception;

		}
	}

	/**
	 * @return WebDriverDimension
	 * @throws WebDriverException
	 */
	public function getSize() {
		try {
			return $this->_element->getSize();
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this->_dispatcher->getDefaultDriver());
			throw $exception;

		}
	}

	/**
	 * @return string
	 * @throws WebDriverException
	 */
	public function getTagName() {
		try {
			return $this->_element->getTagName();
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this->_dispatcher->getDefaultDriver());
			throw $exception;

		}
	}

	/**
	 * @return string
	 * @throws WebDriverException
	 */
	public function getText() {
		try {
			return $this->_element->getText();
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this->_dispatcher->getDefaultDriver());
			throw $exception;

		}
	}

	/**
	 * @return bool
	 * @throws WebDriverException
	 */
	public function isDisplayed() {
		try {
			return $this->_element->isDisplayed();
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this->_dispatcher->getDefaultDriver());
			throw $exception;

		}
	}

	/**
	 * @return bool
	 * @throws WebDriverException
	 */
	public function isEnabled() {
		try {
			return $this->_element->isEnabled();
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this->_dispatcher->getDefaultDriver());
			throw $exception;

		}
	}

	/**
	 * @return bool
	 * @throws WebDriverException
	 */
	public function isSelected() {
		try {
			return $this->_element->isSelected();
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this->_dispatcher->getDefaultDriver());
			throw $exception;

		}
	}

	/**
	 * @return $this
	 * @throws WebDriverException
	 */
	public function submit() {
		try {
			$this->_element->submit();
			return $this;
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this->_dispatcher->getDefaultDriver());
			throw $exception;

		}
	}

	/**
	 * @return string
	 * @throws WebDriverException
	 */
	public function getID() {
		try {
			return $this->_element->getID();
		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this->_dispatcher->getDefaultDriver());
			throw $exception;

		}
	}

}