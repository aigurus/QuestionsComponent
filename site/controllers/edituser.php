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
 
// import Joomla controllerform library
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;
require_once 'components/com_questions/helpers/mail.php';
jimport('joomla.application.component.controllerform');

class QuestionsControllerEdituser extends JControllerForm {
	
	public function save($key = null, $urlVar = null)
	{
		
		$this->checkToken();
		$data = array();
		$app   = JFactory::getApplication();
		$model = $this->getModel('edituser');
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
			$app->setUserState('com_questions.edituser', $data);
		}
		else
		{
			$app->enqueueMessage("Data successfully validated", 'notice');
			// Clear the form data in the session
			$app->setUserState('com_questions.edituser', null);
		}
		//var_dump($data); exit;
		$user = JFactory::getUser();
		//$id = $user->id;
		/*Enter into Database*/
		$jinput = JFactory::getApplication()->input;
		$uid = $jinput->getInt('userid');
		//var_dump($uid); exit;
		$db = JFactory::getDbo();
		$query = "UPDATE `#__questions_userprofile` SET 
		description = ".$db->quote($data['description']).
			" , url1 = ".$db->quote($data['url1']).
			" , url2 = ".$db->quote($data['url2']).
			" , url3 = ".$db->quote($data['url3']).
			" , location = ".$db->quote($data['location']).
			" , company = ".$db->quote($data['company']).
			" , position = ".$db->quote($data['position']).
			" , workno = ".$db->quote($data['workno']).
			" , mobno = ".$db->quote($data['mobno']).
			" , workaddress = ".$db->quote($data['workaddress']).
			" WHERE userid = ".$uid ;
		$db->setQuery($query);
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