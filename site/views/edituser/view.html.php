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
 
// import Joomla view library
jimport('joomla.application.component.view');
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Factory;

class QuestionsViewEdituser extends HtmlView
{
        // Overwriting JViewLegacy display method
        function display($tpl = null) 
        {
        	            
        	$user = JFactory::getUser();
        	$app = JFactory::getApplication();

        	//Authorizations
        	$user = JFactory::getUser();
			$ownedit = $user->authorise("profile.edit.own" , "com_questions");
			$alledit = $user->authorise("profile.edit.all" , "com_questions");

        	$this->assignRef("ownedit", $ownedit);
        	$this->assignRef("alledit", $alledit);
        	
        	//params
        	$params = $app->getParams();
        	$this->assignRef("params", $params);
			$pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));
        	$this->assignRef("pageclass_sfx" , $pageclass_sfx);


        	//Add Pathway
        	QuestionsHelper::addPathway(); 
        	$pathway=$app->getPathway();
        	//$pathway->addItem ( $this->question->title );
        	
        	if ( $ownedit || $alledit ){ //check for questions, suppressing errors..

	        		if (!$this->form = $this->get('form'))
					{
						echo "Can't load form<br>";
						return;
					}
					$this->items			= $this->get('Items');
					$this->state			= $this->get('State');
					parent::display($tpl);	// this will include the layout file edit.php

        	}
        	else{
				//$app =JFactory::getApplication();
				//$app->redirect('index.php?option=com_questions&view=form&layout=edit');
				//JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');
        		JError::raiseNotice(404, JText::_("COM_QUESTIONS_ERROR_404"));
        	}
        }
		
}
