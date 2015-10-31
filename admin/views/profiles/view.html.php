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
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');


class QuestionsViewProfiles extends QueView
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		$user= JFactory::getUser();
		JToolBarHelper::title(JText::_('COM_QUESTIONS_Profiles'), 'user');
		QuestionsHelper::canDo("core.edit") ?JToolBarHelper::addNew("profiles.EditRank", JText::_('COM_QUESTIONS_EDITRANK')) : NULL ;	
		QuestionsHelper::canDo("core.edit") ?JToolBarHelper::addNew("profiles.EditPoints", JText::_('COM_QUESTIONS_EDITPOINTS')) : NULL ;
		QuestionsHelper::canDo("block.user") ?JToolBarHelper::addNew("profiles.BlockUser", JText::_('COM_QUESTIONS_BLOCKUSER')) : NULL ;
		QuestionsHelper::canDo("block.user") ?JToolBarHelper::addNew("profiles.UnblockUser", JText::_('COM_QUESTIONS_UNBLOCKUSER')) : NULL ;
	}
	protected function getPoints($id){
  
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('points');
                $query->from('#__questions_userprofile');
              	$query->where('userid ='.$id);
				$db->setQuery($query);
				$row = $db->loadObject();
				if(isset($row)){
				return $row->points;	
				}
				else {
					return 0;
				}
	}
	protected function getRank($id){
         
				$db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('rank');
                $query->from('#__questions_userprofile');
              	$query->where('userid ='.$id);
				$db->setQuery($query);
				$row = $db->loadObject();
				if(isset($row)){
				return $row->rank;	
				}
				else {
					return 0;
				}	
	}
}
