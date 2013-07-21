<?php
class My_Decorator_Multiedit extends Zend_Form_Decorator_Abstract
{
	public function render($content)
	{
		$element = $this->getElement();
		if (!$element instanceof Zend_Form_Element || $element instanceof Zend_Form_Element_Submit || $element instanceof Zend_Form_Element_Hidden) {
		    return $content;
		}
		if (null === $element->getView()) {
		    return $content;
		}
		
		$name = $element->getName();
		return "<input type=checkbox name=check_field[] value=$name>" . $content;
	}
}
