<?php
/*
    
    Copyright (C)  2012 Sweta ray.
    Permission is granted to copy, distribute and/or modify this document
    under the terms of the GNU Free Documentation License, Version 1.3
    or any later version published by the Free Software Foundation;
    with no Invariant Sections, no Front-Cover Texts, and no Back-Cover Texts.
    A copy of the license is included in the section entitled "GNU
    Free Documentation License"
	@license GNU/GPL http://www.gnu.org/copyleft/gpl.html
    Questions for Joomla
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>
   
	Questions for Joomla
	Version 0.0.1
	Created date: Sept 2012
	Creator: Sweta Ray
	Email: admin@extensiondeveloper.com
	support: support@extensiondeveloper.com
	Website: http://www.extensiondeveloper.com
*/

defined('_JEXEC') or die('Restricted access');

use Joomla\Utilities\ArrayHelper;
jimport('joomla.application.component.modeladmin');

use Joomla\CMS\MVC\Model\FormModel;

class QuestionsModelGroup extends FormModel
{

	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $type    The table name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable  A JTable object
	 *
	 * @since   1.6
	 */
	protected $item;
	public function getTable($type = 'Group', $prefix = 'QuestionsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

    /**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed    A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm(
			'com_questions.group',
			'group',
			array(
				'control' => 'jform',
				'load_data' => $loadData
			)
		);
		//var_dump($form); exit;
		if (empty($form))
		{
			$errors = $this->getErrors();
			throw new Exception(implode("\n", $errors), 500);
		}

		return $form;
	}
	
	public function &getData($id = null)
	{
		if ($this->item === null)
		{
			$this->item = false;

			if (empty($id))
			{
				$id = $this->getState('group.userid');
			}

			$table = $this->getTable();

			// Attempt to load the row.
			if ($table !== false && $table->load($id))
			{
				$user = JFactory::getUser();
				$id   = $table->userid;
				
				// Convert the JTable to a clean JObject.
				$properties  = $table->getProperties(1);
				$this->item = ArrayHelper::toObject($properties, 'JObject');
			}
		}

		return $this->item;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 * As this form is for add, we're not prefilling the form with an existing record
	 * But if the user has previously hit submit and the validation has found an error,
	 *   then we inject what was previously entered.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState(
			'com_questions.group.group.userid',
			array()
		);

		if (empty($data)){
			$data = $this->getData();	
		}

		return $data;
	}
	
	protected function populateState()
	{
		$app = JFactory::getApplication('com_questions');
		$id = JFactory::getApplication()->input->get('id');
		JFactory::getApplication()->setUserState('com_questions.group.userid', $id);

		$this->setState('group.userid', $id);

		// Load the parameters.
		$params       = $app->getParams();
		$params_array = $params->toArray();

		if (isset($params_array['item_id']))
		{
			$this->setState('group.userid', $params_array['item_id']);
		}

		$this->setState('params', $params);
	}
    
	
}