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
	Email: admin@phpseo.net
	support: support@phpseo.net
	Website: http://www.phpseo.net
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class QuestionsViewRankedit extends QueView
{
	protected $form;
	protected $item;

	public function display( $tpl = NULL ){
		

		$this->form = $this->get("Form");
		$this->item = $this->get("Item");
		
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		
		//authorization
		if (!$user->authorise('core.create', 'com_questions')) { 
		       return JError::raiseWarning(404, JText::_('COM_QUESTIONS_JERROR_ALERTNOAUTHOR'));
	    }
		/*if (!$user->authorise("core.create" , "com_questions")){
			JError::raiseError(403, JText::_('COM_QUESTIONS_JERROR_ALERTNOAUTHOR'));
			return false;
		}*/
		
		if ($user->id){
			$this->form->setFieldAttribute("name", "type", "hidden");
		}
		
		//params
		$params = JComponentHelper::getParams('com_questions');
        //$imagepath = $params->getValue('data.params.imagepath');
		//$params = $app->getParams();
		$this->assignRef("params", $params);
		
		//Page class suffix
		$pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));
		$this->assignRef("pageclass_sfx", $pageclass_sfx);
		
		$this->assignRef("user", $user);

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}
		
		parent::display($tpl);
	}
	
}