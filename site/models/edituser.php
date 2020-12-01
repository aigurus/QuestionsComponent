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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\Utilities\ArrayHelper;
jimport('joomla.application.component.modeladmin');

use Joomla\CMS\MVC\Model\FormModel;

class QuestionsModelEdituser extends FormModel
{

	protected $item;	
	
	/*
	 * Method to return a JTable instance of the question table
	 */
	public function getTable( $type = "Edituser" , $prefix = "QuestionsTable" , $config = array() ){
		return JTable::getInstance($type, $prefix , $config);
	}
	
	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm(
			'com_questions.edituser',  // just a unique name to identify the form
			'edituser',				// the filename of the XML form definition
										// Joomla will look in the models/forms folder for this file
			array(
				'control' => 'jform',	// the name of the array for the POST parameters
				'load_data' => $loadData	// will be TRUE
			)
		);
		//var_dump($form); exit;
		if (empty($form))
		{
            $errors = $this->getErrors();
			throw new Exception(implode("\n", $errors), 500);
		}
		
		if ( $data = $this->loadFormData() ){
			//var_dump($data); exit;
			$user = JFactory::getUser();
			
			if (! $data instanceof JObject)
				$data = JArrayHelper::toObject($data);
			$this->preprocessForm($form, $data);
			$form->bind($data);
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
				$id = $this->getState('edituser.userid');
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
	
    protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState(
			'com_questions.edituser.edituser.userid',	// a unique name to identify the data in the session
			array()	// prefill data if no data found in session
		);
		
		if (empty($data)){
			$data = $this->getData();	
		}

		return $data;
	}
	
	/*public function getItem($pk= NULL){
		
		return parent::getItem();
	}*/
	
	protected function populateState()
	{
		$app = JFactory::getApplication('com_questions');
		$id = JFactory::getApplication()->input->get('id');
		JFactory::getApplication()->setUserState('com_questions.edituser.userid', $id);

		$this->setState('edituser.userid', $id);

		// Load the parameters.
		$params       = $app->getParams();
		$params_array = $params->toArray();

		if (isset($params_array['item_id']))
		{
			$this->setState('edituser.userid', $params_array['item_id']);
		}

		$this->setState('params', $params);
	}

}