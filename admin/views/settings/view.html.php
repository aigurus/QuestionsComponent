<?php
//
// @copyright	Copyright (C) JoomlaComponents.nl, Inc. All rights reserved.
// @license		GNU General Public License version 2 or later
//

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');


class QuestionsViewSettings extends QueView
{
	function display($tpl = null) 
        {
                // Get data from the model
                $items = $this->get('Items');
                $pagination = $this->get('Pagination');
 
                // Check for errors.
                if (count($errors = $this->get('Errors'))) 
                {
                        JError::raiseError(500, implode('<br />', $errors));
                        return false;
                }
                // Assign data to the view
                $this->items = $items;
                $this->pagination = $pagination;
 
                // Set the toolbar
                $this->addToolBar();
 
                // Display the template
                parent::display($tpl);
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
