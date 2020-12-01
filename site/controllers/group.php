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
defined('_JEXEC') or die();
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;
use Joomla\CMS\Date\Date;
class QuestionsControllerGroup extends BaseController
{   
    public function save($key = null, $urlVar = null)
    {
		$this->checkToken();
		$data = array();
		$app   = JFactory::getApplication();
		$model = $this->getModel('group');
		$form = $model->getForm($data, false);
		if (!$form)
		{
			$app->enqueueMessage($model->getError(), 'error');
			return false;
		}
		
		// name of array 'jform' must match 'control' => 'jform' line in the model code
		$data  = $this->input->post->get('jform', array(), 'array');
		//$data  = $this->input->get('jform', array(), 'array');
		
		// This is validate() from the FormModel class, not the Form class
		// FormModel::validate() calls both Form::filter() and Form::validate() methods
		$validData = $model->validate($form, $data);

		if ($validData === false)
		{
			$errors = $model->getErrors();

			foreach ($errors as $error)
			{
				if ($error instanceof \Exception)
				{
					$app->enqueueMessage($error->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($error, 'warning');
				}
			}

			// Save the form data in the session, using a unique identifier
			$app->setUserState('com_questions.group', $data);
		}
		else
		{
			$app->enqueueMessage("Data successfully validated", 'notice');
			// Clear the form data in the session
			$app->setUserState('com_questions.group', null);
		}
		//var_dump($data); exit;
		$user = JFactory::getUser();
		//Get all groups
		$groupmodel = JModelLegacy::getInstance('(Groups)','QuestionsModel');
		$usergroups = $groupmodel->getUserGroups();
		//var_dump($usergroups); exit;
		
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);
		$moderators = json_encode($data['moderators']);
		$currentTime = new Date('now'); // Current date and time
		// Insert columns.
		$columns = array('group_name', 'moderators', 'published', 'created', 'modified','userid');

		// Insert values.
		$values = array($db->quote($data['group_name']), $db->quote($moderators),1, $db->quote($currentTime),$db->quote($currentTime),$user->id);

		// Prepare the insert query.
		$query
			->insert($db->quoteName('#__questions_groups'))
			->columns($db->quoteName($columns))
			->values(implode(',', $values));

		// Set the query using our newly populated query object and execute it.
		$db->setQuery($query);
		$db->execute();
		
		
		exit;
		
		
		
		//var_dump($data['moderators']); exit;
		$db = JFactory::getDbo();
		$date = Factory::getDate();
		$currentTime = new Date('now'); // Current date and time
		$moderators = json_encode($data['moderators']);
		$query = "UPDATE `#__questions_groups` SET 
		group_name = ".$db->quote($data['group_name']).
			" , moderators = ".$db->quote($moderators).
			" , created = ".$db->quote($currentTime).
			" , published = 1".
			" WHERE userid = ".$user->id ;
		
		
		$db->setQuery($query);
		//echo $db->getQuery();exit;
		$db->execute();
		
		
		// Redirect back to the form in all cases
		$this->setRedirect(JRoute::_('index.php?option=com_questions&view=profiles&id='.$uid, false));
	}
	
	public function cancel($key = null)
    {
        parent::cancel($key);
        
        // set up the redirect back to the same form
        $this->setRedirect(
            (string)JUri::getInstance(), 
            JText::_('COM_QUESTIONS_FORM_SUBMIT_CANCELLED')
		);
    }
	
	
}