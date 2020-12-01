<?php
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Form\FormHelper;

FormHelper::loadFieldClass('list');

class JFormFieldCity extends JFormFieldList {

	protected $type = 'City';

	public function getOptions() {
		$cities = array(
					array('value' => 1, 'text' => 'New York'),
					array('value' => 2, 'text' => 'Chicago'),
					array('value' => 3, 'text' => 'San Francisco'),
					);
		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $cities);

		// pre-select values 2 and 3 by setting the protected $value property
		$this->value = array(2, 3);

		return $options;
	}
}
?>