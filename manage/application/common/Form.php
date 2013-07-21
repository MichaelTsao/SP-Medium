<?php

class CX_Common_Form extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->addElementPrefixPaths(array(
            'decorator' => array('My_Decorator' => APPLICATION_PATH . '/views/decorators/')
        ));
	}
	
	public function createElements($elements, $caption='', $value='', $require=array())
	{
		foreach($elements as $element)
		{
			if($element == 'id')
			{
	    		$this->addElement('hidden', 'id', array('decorators' => array('ViewHelper', 'Errors')));
			}
			else
			{
				$required = in_array($element, $require) ? true : false;
				$label = isset($caption[$element]) ? $caption[$element] : $element;
				$label .= ": ";
				if(isset($value[$element]))
				{
					$this->addElement('select', $element, array(
							            'label'      => $label,
							            'required'   => $required,
							            'decorators' => array('ViewHelper', 'Label')
							        ));
					$this->$element->setMultiOptions($value[$element]);  
				}
				else
				{
					$this->addElement('text', $element, array(
							            'label'      => $label,
							            'required'   => $required,
							            'decorators' => array('ViewHelper', 'Label')
							        ));    
				}
			}
		}
		
		$this->addElement('submit', 'sbutton', array(
				            'ignore'   => true,
				            'decorators' => array('ViewHelper', 'Errors')
				        ));   	
	}

}

