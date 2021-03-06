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

// import Joomla view library
jimport('joomla.application.component.view');


class QuestionsViewRank extends QueView
{
        function display($tpl = null) 
        {        	
        	$this->items = $this->get('Items');
			$this->pagination = $this->get('Pagination');
			$this->state = $this->get("State");
			
			$this->addToolBar();
			
			//Calculate parents..
			foreach ($this->items as $item){
				
				$qid = NULL;
				$item->qid = new stdclass;
				if ($item->qid){
					$q = "SELECT * FROM #__questions_ranks";
					$db = JFactory::getDBO();
					$db->setQuery($q);
					$parent = $db->loadObject();
				}
				$item->qid = $qid;
				
		
			}
			
            // Display the template
            parent::display($tpl);
        }
        
		protected function addToolBar() 
        {
        	$user= JFactory::getUser();
        	
            JToolBarHelper::title(JText::_('COM_QUESTIONS_RANK'));
            //QuestionsHelper::canDo("core.save") ? JToolBarHelper::addSaveX('rank.save') : NULL ;
            //QuestionsHelper::canDo("core.create") ? JToolBarHelper::addNew('rankedit.add') : NULL ;
            QuestionsHelper::canDo("core.edit") ? JToolBarHelper::editList('rankedit.edit') : NULL;
            //QuestionsHelper::canDo("rank.delete") ? JToolBarHelper::deleteList(JText::_("COM_QUESTIONS_ITEM_DEL_Q"), 'rankedit.delete') : NULL;
            QuestionsHelper::canDo("core.admin") ? JToolBarHelper::preferences("com_questions") : NULL;
        }
}
