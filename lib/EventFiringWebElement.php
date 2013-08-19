<?php

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
	 * @var EventFiringWebDriver
	 */
	protected $_driver;

	/**
	 * @param WebDriverCommandExecutor $executor
	 * @param string                   $id
	 * @param WebDriverDispatcher      $dispatcher
	 */
	public function __construct(WebDriverCommandExecutor $executor, $id,  WebDriverDispatcher $dispatcher = null) {

		$this->_element = new WebDriverElement($executor, $id);
		$this->_dispatcher = $dispatcher ?  $dispatcher : new WebDriverDispatcher();
		return $this;

	}

	public function __call($method, array $arguments = array()) {

		try {

			return call_user_func_array([$this, $method], $arguments);

		} catch (WebDriverException $exception) {

			$this->_dispatch('onException', $exception, $this->getDispatcher()->getDefaultDriver());
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
	 * @return WebDriverElement
	 */
	public function getElement() {
		return $this->_element;
	}

	/**
	 * @param $method
	 */
	protected function _dispatch($method) {

		$arguments = func_get_args();
		unset($arguments[0]);
		if($method != 'onException')
			$arguments[] = $this;

		$this->getDispatcher()->dispatch($method, $arguments);

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

		$raw_element = $this->executor->execute('elementFindElement', [
			'using' => $by->getMechanism(),
			'value' => $by->getValue(),
			':id'   => $this->id,
		]);

		$this->_dispatch('afterFindBy', $by);

		return $this->newElement($raw_element['ELEMENT']);
	}

	/**
	 * @param WebDriverBy $by
	 * @return array
	 */
	protected function findElements(WebDriverBy $by) {

		$this->_dispatch('beforeFindBy', $by);

		$raw_elements = $this->executor->execute('elementFindElements', [
			'using' => $by->getMechanism(),
			'value' => $by->getValue(),
			':id'   => $this->id,
		]);

		$elements = array();
		foreach ($raw_elements as $raw_element)
			$elements[] = $this->newElement($raw_element['ELEMENT']);

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