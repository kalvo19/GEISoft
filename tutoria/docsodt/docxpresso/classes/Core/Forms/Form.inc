<?php

/**
 * ODF Forms
 * 
 * @copyright  Copyright(c)2014 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    1.0
 * @since      1.0
 */

namespace Docxpresso\Core\Forms;

use Docxpresso\Commons as commons;

/**
 * This class creates a ODF form and its corersponding control elements
 *
 * @package    Docxpresso
 * @subpackage Forms
 */

class Form
{
    /**
     * form DOM document
     * @var DOMDocument
     * @access private
     */
    private $_dom;
    
    /**
     * form DOM node
     * @var DOMNode
     * @access private
     */
    private $_form;
    
    /**
     * form name
     * @var string
     * @access private
     */
    private $_name;
    
    /**
     * form XPath
     * @var DOMXPath
     * @access private
     */
    private $_xpath;
       
    /**
     * Construct
     *
     * @param DOMDocument $doc
     * @param array $options the avalaible key and values are:
     *  'name' => (string) a unique name that identifies the form (required)
     *  'target' => (string) that can be _blank (default), _self or _top 
     *  or a frame name
     *  'action' => (string) points to the URL (if any) where the data of the
     *  form should be sent
     *  'method' => (string) may be get or post
     * @access public
     */
    public function __construct($doc, $options)
    {
        $this->_dom = $doc;
        $this->_xpath = new \DOMXPath($this->_dom);
        //parse the form options
        if (isset($options['name'])) {
            $this->_name = $options['name'];
        } else {
            $message = 'The name is required to insert controls into the form.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
            $this->name = uniqid();
        }
        if (isset($options['target'])) {
            $target = $options['target'];
        } else {
            $target = '_blank';
        }
        if (isset($options['action'])) {
            $action = $options['action'];
        } else {
            $action = '';
        }
        if (isset($options['method'])) {
            $method = \strtolower($options['method']);
        } else {
            $method = 'get';
        }
        //create the form node that is going to wrap all form elements:
        //generate the form element
        $form = $this->_dom->createElement('form:form');
        $form->setAttribute('form:name', $this->_name);
        $form->setAttribute('form:apply-filter', 'true');
        $form->setAttribute('form:command-type', 'table');
        $form->setAttribute('form:control-implementation', 
                            'ooo:com.sun.star.form.component.Form');
        $form->setAttribute('form:method', $method);
        $form->setAttribute('office:target-frame', $target);
        $form->setAttribute('xlink:href', $action);
        $form->setAttribute('xlink:type', 'simple'); 
        
        $this->_form = $form;
    }
      
    /*Getters and Setters*/
    /**
     * gets the form node
     * 
     * @return DOMNode
     * @access public
     */
    public function getFormNode() 
    {
        return $this->_form;
    }
    
    /**
     * gets the name of the form
     * 
     * @return string
     * @access public
     */
    public function getName() 
    {
        return $this->_name;
    }
    
    /**
     * generates a button
     *
     * @param string $form the name of the form
     * @param string $name
     * @param array $options
     * @return void
     * @access public
     */
    public function addFormButton($options) 
    {
        if (isset($options['name'])) {
            $name = $options['name'];
        } else {
            $name = 'button_' . uniqid();
        }
        //generate the button element
        $button = $this->_dom->createElement('form:button');
        $button->setAttribute('form:name', $name);
        $button->setAttribute('form:control-implementation', 
                            'ooo:com.sun.star.form.component.CommandButton');
        $button->setAttribute('xml:id', $name);
        $button->setAttribute('form:id', $name);    
        if (isset($options['value'])) {
            $button->setAttribute('form:label', $options['value']);
        } else {
            $button->setAttribute('form:label', 'Send');
        }
        $button->setAttribute('form:button-type', 'submit');
        //$button->setAttribute('form:delay-for-repeat', 'PT0.50S');
        $button->setAttribute('form:image-position', 'center');
        //generate the properties subelement
        $properties = $this->_dom->createElement('form:properties');
        //append the required form:property elements
        //DefaultControl
        $property = $this->_dom->createElement('form:property');
        $property->setAttribute('form:property-name', 'DefaultControl');
        $property->setAttribute('office:value-type', 'string');
        $property->setAttribute('office:string-value', 
                                'com.sun.star.form.control.CommandButton');
        $properties->appendChild($property);
        //append the properties element
        $button->appendChild($properties);
        //Now append the form component to the corresponding form element
        $this->_form->appendChild($button);

        return $name;
    
    }
    
    /**
     * generates a checkbox
     *
     * @param string $form the name of the form
     * @param string $name
     * @param array $options
     * @return string
     * @access public
     */
    public function addFormCheckbox($options) 
    {        
        if (isset($options['name'])) {
            $name = $options['name'];
        } else {
            $name = 'checkbox_' . uniqid();
        }
        //generate the checkbox element
        $checkbox = $this->_dom->createElement('form:checkbox');
        $checkbox->setAttribute('form:name', $name);
        $checkbox->setAttribute('form:control-implementation', 
                            'ooo:com.sun.star.form.component.CheckBox');
        $checkbox->setAttribute('xml:id', $name);
        $checkbox->setAttribute('form:id', $name);
        if (isset($options['checked']) && $options['checked'] == true) {
            $checkbox->setAttribute('form:current-state', 'checked');
        }
        if (isset($options['value'])) {
            $checkbox->setAttribute('form:value', $options['value']);
        } else {
            $checkbox->setAttribute('form:value', $name);
        }
        $checkbox->setAttribute('form:image-position', 'center');
        //generate the properties subelement
        $properties = $this->_dom->createElement('form:properties');
        //append the required form:property elements
        //ControlTypeinMSO
        $property = $this->_dom->createElement('form:property');
        $property->setAttribute('form:property-name', 'ControlTypeinMSO');
        $property->setAttribute('office:value-type', 'float');
        $property->setAttribute('office:value', '0');
        $properties->appendChild($property);
        //DefaultControl
        $property = $this->_dom->createElement('form:property');
        $property->setAttribute('form:property-name', 'DefaultControl');
        $property->setAttribute('office:value-type', 'string');
        $property->setAttribute('office:string-value', 
                                'com.sun.star.form.control.CheckBox');
        $properties->appendChild($property);
        //ObjIDinMSO
        $property = $this->_dom->createElement('form:property');
        $property->setAttribute('form:property-name', 'ObjIDinMSO');
        $property->setAttribute('office:value-type', 'float');
        $property->setAttribute('office:value', '');
        $properties->appendChild($property);
        //SecondaryRefValue
        $property = $this->_dom->createElement('form:property');
        $property->setAttribute('form:property-name', 'SecondaryRefValue');
        $property->setAttribute('office:value-type', 'string');
        $property->setAttribute('office:value', '');
        $properties->appendChild($property);
        //append the properties element
        $checkbox->appendChild($properties);
        //Now append the form component to the corresponding form element
        $this->_form->appendChild($checkbox);

        return $name;
    }
    
    /**
     * generates a input field
     *
     * @param string $form the name of the form
     * @param string $name
     * @param array $options
     * @return void
     * @access public
     */
    public function addFormInputField($options) 
    {
        if (isset($options['name'])) {
            $name = $options['name'];
        } else {
            $name = 'inputfield_' . uniqid();
        }
        //generate the input element
        $text = $this->_dom->createElement('form:text');
        $text->setAttribute('form:name', $name);
        $text->setAttribute('form:control-implementation', 
                            'ooo:com.sun.star.form.component.TextField');
        $text->setAttribute('xml:id', $name);
        $text->setAttribute('form:id', $name);
        if (isset($options['value'])) {
            $text->setAttribute('form:current-value', $options['value']);
        }
        $text->setAttribute('form:image-position', 'center');
        //generate the properties subelement
        $properties = $this->_dom->createElement('form:properties');
        //append the required form:property elements
        //ControlTypeinMSO
        $property = $this->_dom->createElement('form:property');
        $property->setAttribute('form:property-name', 'ControlTypeinMSO');
        $property->setAttribute('office:value-type', 'float');
        $property->setAttribute('office:value', '0');
        $properties->appendChild($property);
        //DefaultControl
        $property = $this->_dom->createElement('form:property');
        $property->setAttribute('form:property-name', 'DefaultControl');
        $property->setAttribute('office:value-type', 'string');
        $property->setAttribute('office:string-value', 
                                'com.sun.star.form.control.TextField');
        $properties->appendChild($property);
        //ObjIDinMSO
        $property = $this->_dom->createElement('form:property');
        $property->setAttribute('form:property-name', 'ObjIDinMSO');
        $property->setAttribute('office:value-type', 'float');
        $property->setAttribute('office:value', '');
        $properties->appendChild($property);
        //append the properties element
        $text->appendChild($properties);
        //Now append the form component to the corresponding form element
        $this->_form->appendChild($text);

        return $name;

    }
    
    /**
     * generates a input texarea
     *
     * @param string $form the name of the form
     * @param string $name
     * @param array $options
     * @return void
     * @access public
     */
    public function addFormTextarea($options) 
    {
        if (isset($options['name'])) {
            $name = $options['name'];
        } else {
            $name = 'textarea_' . uniqid();
        }
        if (isset($options['scroll'])) {
            $scroll = $options['scroll'];
        } else {
            $scroll = 'true';
        }
        //generate the textarea element
        $text = $this->_dom->createElement('form:text');
        $text->setAttribute('form:name', $name);
        $text->setAttribute('form:control-implementation', 
                            'ooo:com.sun.star.form.component.TextField');
        $text->setAttribute('xml:id', $name);
        $text->setAttribute('form:id', $name);
        if (isset($options['value'])) {
            $text->setAttribute('form:current-value', $options['value']);
        }
        $text->setAttribute('form:image-position', 'center');
        //generate the properties subelement
        $properties = $this->_dom->createElement('form:properties');
        //append the required form:property elements
        //ControlTypeinMSO
        $property = $this->_dom->createElement('form:property');
        $property->setAttribute('form:property-name', 'ControlTypeinMSO');
        $property->setAttribute('office:value-type', 'float');
        $property->setAttribute('office:value', '0');
        $properties->appendChild($property);
        //DefaultControl
        $property = $this->_dom->createElement('form:property');
        $property->setAttribute('form:property-name', 'DefaultControl');
        $property->setAttribute('office:value-type', 'string');
        $property->setAttribute('office:string-value', 
                                'com.sun.star.form.control.TextField');
        $properties->appendChild($property);
        //Multiline
        $property = $this->_dom->createElement('form:property');
        $property->setAttribute('form:property-name', 'MultiLine');
        $property->setAttribute('office:value-type', 'boolean');
        $property->setAttribute('office:value', 'true');
        $properties->appendChild($property);
        //ObjIDinMSO
        $property = $this->_dom->createElement('form:property');
        $property->setAttribute('form:property-name', 'ObjIDinMSO');
        $property->setAttribute('office:value-type', 'float');
        $property->setAttribute('office:value', '');
        $properties->appendChild($property);
        //VScroll
        $property = $this->_dom->createElement('form:property');
        $property->setAttribute('form:property-name', 'VScroll');
        $property->setAttribute('office:value-type', 'boolean');
        $property->setAttribute('office:value', $scroll);
        $properties->appendChild($property);
        //append the properties element
        $text->appendChild($properties);
        //Now append the form component to the corresponding form element
        $this->_form->appendChild($text);

        return $name;
    
    }
    
    /**
     * generates a listbox or dropdown list
     *
     * @param string $form the name of the form
     * @param string $name
     * @param array $listItems
     * @param array $options
     * @return void
     * @access public
     */
    public function addFormSelect($options) 
    {
        if (isset($options['name'])) {
            $name = $options['name'];
        } else {
            $name = 'select_' . uniqid();
        }
        //generate the listbox element
        $listBox = $this->_dom->createElement('form:listbox');
        $listBox->setAttribute('form:name', $name);
        $listBox->setAttribute('form:control-implementation', 
                            'ooo:com.sun.star.form.component.ListBox');
        $listBox->setAttribute('xml:id', $name);
        $listBox->setAttribute('form:id', $name);
        $listBox->setAttribute('form:dropdown', 'true');
        $listBox->setAttribute('form:size', 10);
        $listBox->setAttribute('form:bound-column', '1');
        //generate the properties subelement
        $properties = $this->_dom->createElement('form:properties');
        //append the required form:property elements
        //ControlTypeinMSO
        $property = $this->_dom->createElement('form:property');
        $property->setAttribute('form:property-name', 'ControlTypeinMSO');
        $property->setAttribute('office:value-type', 'float');
        $property->setAttribute('office:value', '0');
        $properties->appendChild($property);
        //DefaultControl
        $property = $this->_dom->createElement('form:property');
        $property->setAttribute('form:property-name', 'DefaultControl');
        $property->setAttribute('office:value-type', 'string');
        $property->setAttribute('office:string-value', 
                                'com.sun.star.form.control.ListBox');
        $properties->appendChild($property);
        //MouseWheelBehavior
        $property = $this->_dom->createElement('form:property');
        $property->setAttribute('form:property-name', 'MouseWheelBehavior');
        $property->setAttribute('office:value-type', 'float');
        $property->setAttribute('office:value', '0');
        $properties->appendChild($property);
        //ObjIDinMSO
        $property = $this->_dom->createElement('form:property');
        $property->setAttribute('form:property-name', 'ObjIDinMSO');
        $property->setAttribute('office:value-type', 'float');
        $property->setAttribute('office:value', '');
        $properties->appendChild($property);
        //append the properties element
        $listBox->appendChild($properties);
        //insert the list items
        if (!isset($options['items']) || !is_array($options['items'])) {
            $options['items'] = array();
            $message = 'The select options were incorrectly set.';
            commons\DOCXPRESSOLogger::logger($message, 'info');
        }
        foreach ($options['items'] as $key => $value) {
            $item = $this->_dom->createElement('form:option');
            $item->setAttribute('form:label', $key);
            $item->setAttribute('form:value', $value);
            if (isset($options['selected']) && $key == $options['selected'] ) {
                $item->setAttribute('form:current-selected', 'true');
            }
            $listBox->appendChild($item);
        }
        //Now append the form component to the corresponding form element
        $this->_form->appendChild($listBox);

        return $name;
        
        
    }
    
    /**
     * generates a radio button
     *
     * @param string $form the name of the form
     * @param string $name
     * @param array $options
     * @return void
     * @access public
     */
    public function addFormRadioButton($options) 
    {
        if (isset($options['name'])) {
            $name = $options['name'];
        } else {
            $name = 'radiobutton_' . uniqid();
        }
        //generate the radio element
        $radio = $this->_dom->createElement('form:radio');
        $radio->setAttribute('form:name', $name);
        $radio->setAttribute('form:control-implementation', 
                            'ooo:com.sun.star.form.component.RadioButton');
        //the radio buttons should have a unique control name even if sharing
        //the same radio group name
        $control = $name . '_' . rand(1111, 9999999999);
        $radio->setAttribute('xml:id', $control);
        $radio->setAttribute('form:id', $control);
        if (isset($options['selected']) && $options['selected'] == true) {
            $radio->setAttribute('form:current-selected', 'true');
        }
        if (isset($options['value'])) {
            $radio->setAttribute('form:value', $options['value']);
        }
        $radio->setAttribute('form:image-position', 'center');
        //generate the properties subelement
        $properties = $this->_dom->createElement('form:properties');
        //append the required form:property elements
        //ControlTypeinMSO
        $property = $this->_dom->createElement('form:property');
        $property->setAttribute('form:property-name', 'ControlTypeinMSO');
        $property->setAttribute('office:value-type', 'float');
        $property->setAttribute('office:value', '0');
        $properties->appendChild($property);
        //DefaultControl
        $property = $this->_dom->createElement('form:property');
        $property->setAttribute('form:property-name', 'DefaultControl');
        $property->setAttribute('office:value-type', 'string');
        $property->setAttribute('office:string-value', 
                                'com.sun.star.form.control.RadioButton');
        $properties->appendChild($property);
        //ObjIDinMSO
        $property = $this->_dom->createElement('form:property');
        $property->setAttribute('form:property-name', 'ObjIDinMSO');
        $property->setAttribute('office:value-type', 'float');
        $property->setAttribute('office:value', '');
        $properties->appendChild($property);
        //SecondaryRefValue
        $property = $this->_dom->createElement('form:property');
        $property->setAttribute('form:property-name', 'SecondaryRefValue');
        $property->setAttribute('office:value-type', 'string');
        $property->setAttribute('office:value', '');
        $properties->appendChild($property);
        //append the properties element
        $radio->appendChild($properties);
        //Now append the form component to the corresponding form element
        $this->_form->appendChild($radio);
        //the radio buttons should have a unique control name even if sharing
        //the same radio group name
        return $control;
    }
    
}