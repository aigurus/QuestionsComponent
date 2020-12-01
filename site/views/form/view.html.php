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

jimport('joomla.application.component.view');

class QuestionsViewForm extends QueView
{
	protected $form;
	protected $item;

	public function display( $tpl = NULL ){
		
		$this->form = $this->get("Form");
		$this->item = $this->get("Item");
		$model = $this->getModel('form');
        $points = $model->getPoints();
		
		$app = JFactory::getApplication();
		$params = $app->getParams();
		$this->assignRef("params", $params);
		$user = JFactory::getUser();
		
		$session =JFactory::getSession();//Thanks to http://www.tutsforu.com/adding-a-view-to-the-site/75-using-session-in-joomla.html
		$token = $session->getToken();
		
		//$use_redirect = $params->get('use_redirect', 0);
		
     	//authorization
		if (!$user->authorise('core.create', 'com_questions')) { 
		     $app = JFactory::getApplication();
			 $app->redirect('index.php?option=com_users&view=login');
	    }
		
		$app = JFactory::getApplication();
		$params = $app->getParams();
		$minpointsreq = $params->get('minpointsreq',0);
		$reqpoints = $params->get('reqpoints',0);
		if($minpointsreq==1 && $points < $reqpoints){
			// Get a handle to the Joomla! application object
			$app = JFactory::getApplication();
			 
			// Add a message to the message queue
			$app->enqueueMessage(JText::_('COM_QUESTIONS_LESS_POINTS'), 'error');
			$app->enqueueMessage(JText::_('COM_QUESTIONS_ELIGIBLE_FOR_ASKING_POINTS').$reqpoints.JText::_('COM_QUESTIONS_POINTS'), 'error');
			$app->enqueueMessage(JText::_('COM_QUESTIONS_GAIN_POINTS'), 'error');
			$app->redirect('index.php?option=com_questions');
			 
			/** Alternatively you may use chaining */
			//JFactory::getApplication()->enqueueMessage(JText::_('SOME_ERROR_OCCURRED'), 'error');
			
			//http://docs.joomla.org/Display_error_messages_and_notices
		}
		
		if ($user->id){
			$this->form->setFieldAttribute("name", "type", "hidden");
		}
		
		//params
				
		//Page class suffix
		$psfx = htmlspecialchars($params->get('pageclass_sfx'));
		$this->assignRef("pageclass_sfx",$psfx);
		
		$this->assignRef("user", $user);

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}
		
		parent::display($tpl);
	}
	
}