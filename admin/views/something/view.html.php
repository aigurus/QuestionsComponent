<?php
//
// @copyright	Copyright (C) JoomlaComponents.nl, Inc. All rights reserved.
// @license		GNU General Public License version 2 or later
//

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
jimport('joomla.application.component.helper');

class QuestionsViewSomething extends QueView
{
	function display($tpl = null) 
        {
                // Set the toolbar
				$dashboardID = JComponentHelper::getParams('com_questions')->get('dashboardID');
                $this->addToolBar();
				parent::display($tpl);
				
                // Display the template
                //parent::display($tpl);
        }
        /**
         * Setting the toolbar
         */
        protected function addToolBar() 
        {
                JToolBarHelper::title(JText::_('COM_QUESTIONS_SETTINGS'));
                //JToolBarHelper::deleteList('', 'settings.delete');
                //JToolBarHelper::editList('settings.edit');
                //JToolBarHelper::addNew('settings.add');
        }
}
