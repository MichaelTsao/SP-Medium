<?
class Zend_View_Helper_showForm
{
	private $data;
	private $form_name;
	
	function showForm($form, $num=2, $data='')
	{
		$this->form_name = $form->getName();
		$this->data = $data;
		
		$code .= "<table border=0>";
		$code .= sprintf("<form name=\"%s\" id=\"%s\" method=\"%s\" action=\"%s\" enctype=\"%s\">", $this->form_name, $this->form_name, $form->getMethod(), $form->getAction(), $form->getEnctype());
		$i = 1;
		foreach($form->getElements() as $element)
		{
			if($element instanceof Zend_Form_Element_Submit || $element instanceof Zend_Form_Element_Button)
			{
				$buttons[] = $this->showElement($element);
			}
			elseif($element instanceof Zend_Form_Element_Hidden)
			{
				$hiddens[] = $this->showElement($element);
			}
			else
			{
				$i == 1 && $code .= '<tr>';
				$code .= '<td class="no">' . $this->showElement($element) . "</td>";
				if($i == $num)
				{
					$code .= '</tr>';
					$i = 1;
				}
				else
				{
					$i++;
				}
			}
		}
		$i != 1 && $code .= '</tr>';
		$code .= "<tr><td class=\"no\" colspan=$num>" . implode(' ', $hiddens) . implode('&nbsp;&nbsp;', $buttons) . "</td></tr>";
		$code .= "</table>";
		$code .= "</form>";
		
		return $code;
	}
	
	function showElement($element)
	{
		$attrib = $element->getAttribs();
		
		$name = $element->getName();
		$value = isset($this->data[$name]) ? $this->data[$name] : '';
		$label = $element->getLabel();

		$nameAttrib = "name=\"$name\" id=\"" . $this->form_name . "_$name\"";
		$valueAttrib = "value=\"$value\"";
		$defaultAttrib = $nameAttrib . ' ' . $valueAttrib;
		
		$require = $element->isRequired() ? 'class="required"' : '';
		$label_code = '<label for="' . $name. '" ' . $require . '>' . $label . '</label>';
		
		if($element->getDecorator('Multiedit') && !($element instanceof Zend_Form_Element_Hidden || $element instanceof Zend_Form_Element_Submit || $element instanceof Zend_Form_Element_Button))
		{
			$code .= "<input type=\"checkbox\" name=\"check_field[]\" value=\"$name\">";
		}

		if($element instanceof Zend_Form_Element_Text)
		{
			$code .= $label_code . "<input type=\"text\" $defaultAttrib>";
		}
		elseif($element instanceof Zend_Form_Element_Select)
		{
			$options = $element->getMultiOptions();
			$scode .= "<select $nameAttrib>";
			foreach($options as $key => $show)
			{
				$select = strval($value) === strval($key) ? 'selected="selected"' : '';
				$scode .= '<option value="' .$key . '" label="' . $show . '" ' . $select . '>' . $show . '</option>';
			}
			$scode .= '</select>';
			$code .= $label_code . $scode;
		}
		elseif($element instanceof Zend_Form_Element_Hidden)
		{
			$code .= "<input type=\"hidden\" $defaultAttrib>";
		}
		elseif($element instanceof Zend_Form_Element_Submit)
		{
			$action = isset($attrib['onclick']) ? 'onclick=' . $attrib['onclick'] : '';
			$valueAttrib = "value=$label";
			$code .= "<input type=\"submit\" $nameAttrib $valueAttrib $action>";
		}
		elseif($element instanceof Zend_Form_Element_Button)
		{
			$action = isset($attrib['onclick']) ? 'onclick=' . $attrib['onclick'] : '';
			$valueAttrib = "value=$label";
			$code .= "<input type=\"button\" $nameAttrib $valueAttrib $action>";
		}
		else
		{
			$element->setValue($value);
			$code .= $element;
		}
		
		return $code;
	}
}
?>