<?php

class EventFiringWebElement extends EventFiringObject {

	/**
	 * @var WebDriverElement
	 */
	protected $_element;

	/**
	 * @param WebDriverElement    $element
	 * @param WebDriverDispatcher $dispatcher
	 */
	public function __construct(WebDriverElement $element,  WebDriverDispatcher $dispatcher = null) {

		$this->_element = $element;
		$this->_dispatcher = $dispatcher;
		return $this;

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
	 */
	protected function sendKeys($value) {

		$this->_dispatch('beforeChangeValueOf');
		$this->_element->sendKeys($value);
		$this->_dispatch('afterChangeValueOf');
		return $this;

	}

	/**
	 * @return $this
	 */
	protected function click() {

		$this->_dispatch('beforeClickOn');
		$this->_element->click();
		$this->_dispatch('afterClickOn');
		return $this;

	}

	/**
	 * @param WebDriverBy $by
	 * @return mixed
	 */
	protected function findElement(WebDriverBy $by) {

		$this->_dispatch('beforeFindBy', $by);
		$element = $this->newElement($this->_element->findElement($by));
		$this->_dispatch('afterFindBy', $by);

		return $element;
	}

	/**
	 * @param WebDriverBy $by
	 * @return array
	 */
	protected function findElements(WebDriverBy $by) {

		$this->_dispatch('beforeFindBy', $by);

		$elements = array();
		foreach($this->_element->findElements($by) as $element)
			$elements[] = $this->newElement($element);

		$this->_dispatch('afterFindBy', $by);

		return $elements;
	}

	/**
	 * @return EventFiringWebElement
	 */
	protected function clear() {
		$this->_element->clear();
		return $this;
	}

	/**
	 * @param $attribute_name
	 * @return string
	 */
	protected function getAttribute($attribute_name) {
		return $this->_element->getAttribute($attribute_name);
	}

	/**
	 * @param $css_property_name
	 * @return string
	 */
	protected function getCSSValue($css_property_name) {
		return $this->_element->getCSSValue($css_property_name);
	}

	/**
	 * @return WebDriverLocation
	 */
	protected function getLocation() {
		return $this->_element->getLocation();
	}

	/**
	 * @return WebDriverLocation
	 */
	protected function getLocationOnScreenOnceScrolledIntoView() {
		return $this->_element->getLocationOnScreenOnceScrolledIntoView();
	}

	/**
	 * @return WebDriverDimension
	 */
	protected function getSize() {
		return $this->_element->getSize();
	}

	/**
	 * @return string
	 */
	protected function getTagName() {
		return $this->_element->getTagName();
	}

	/**
	 * @return string
	 */
	protected function getText() {
		return $this->_element->getText();
	}

	/**
	 * @return bool
	 */
	protected function isDisplayed() {
		return $this->_element->isDisplayed();
	}

	/**
	 * @return bool
	 */
	protected function isEnabled() {
		return $this->_element->isEnabled();
	}

	/**
	 * @return bool
	 */
	protected function isSelected() {
		return $this->_element->isSelected();
	}

	/**
	 * @return EventFiringWebElement
	 */
	protected function submit() {
		$this->_element->submit();
		return $this;
	}

	/**
	 * @return string
	 */
	protected function getID() {
		return $this->_element->getID();
	}

}