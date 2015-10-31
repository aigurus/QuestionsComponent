<?php
//
// @copyright	Copyright (C) JoomlaComponents.nl, Inc. All rights reserved.
// @license		GNU General Public License version 2 or later
//

// no direct access
defined('_JEXEC') or die('Restricted access');

// Import Joomla! libraries
jimport( 'joomla.application.component.view');
class QuestionsViewForwards  extends QueView {
    function display($tpl = null) 
	{
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Set toolbar items for the page
		JToolBarHelper::title( JText::_( 'Profiles'), 'generic.png' );
		JToolBarHelper::deleteList('', 'forwards.delete', JText::_('COM_QUESTIONS_DELETE_FROM_DIRECTADMIN'));
		JToolBarHelper::custom('forwards.update', 'checkin.png', 'checkin_f2.png', JText::_('COM_QUESTIONS_ADD_OR_UPDATE_DIRECTADMIN'), true);
		JToolBarHelper::preferences('com_questions', '460');
		
		parent::display($tpl);
    }
}
?>