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
/*
if ($form->getName() != "com_questions.categorycom_questions") {
return true;
}

and this onContentAfterSave

if ($context != "com_categories.category")
return true;

$extension =  JFactory::getApplication()->input->get('extension');
if ($extension != "com_com_questions")
return true;

*/
class QuestionsViewQuestions extends QueView
{
		protected $items;

		protected $pagination;
	
		protected $state;
	
        function display($tpl = null) 
        {     
			global $option;   	
			$user 		= JFactory::getUser();
			$document	= JFactory::getDocument();
			$db  		= JFactory::getDBO();
        	$this->items = $this->get('Items');
			$this->pagination = $this->get('Pagination');
			$this->state = $this->get("State");
			
			$app =JFactory::getApplication();
			
			$filter_order		= $app->getUserStateFromRequest( $option.'.questions.filter_order', 'filter_order', 	'a.submitted', 'cmd' );
			$filter_order_Dir	= $app->getUserStateFromRequest( $option.'.questions.filter_order_Dir', 'filter_order_Dir',	'', 'word' );
			$filter_state 		= $app->getUserStateFromRequest( $option.'.questions.filter_state', 'filter_state', 	'*', 'word' );
			$filter 			= $app->getUserStateFromRequest( $option.'.questions.filter', 'filter', '', 'int' );
			
			$search 			= $app->getUserStateFromRequest( $option.'.questions.search', 'search', '', 'string' );
			$search 			= $db->escape( trim(JString::strtolower( $search ) ) );
						
			//publish unpublished filter
			$lists['state']	= JHTML::_('grid.state', $filter_state );
	
			// table ordering
			$lists['order_Dir'] = $filter_order_Dir;
			$lists['order'] = $filter_order;
			
			// Get data from the model
			$rows      	= $this->get( 'Data');
	
			//$total      = $this->get( 'Total');
			$pageNav 	= $this->get( 'Pagination' );
			
			//search filter
		$filters = array();
		$filters[] = JHTML::_('select.option', '1', JText::_( 'COM_QUESTIONS_TITLE' ) );
		$filters[] = JHTML::_('select.option', '4', JText::_( 'COM_QUESTIONS_CATEGORY' ) );
		$lists['filter'] = JHTML::_('select.genericlist', $filters, 'filter', 'size="1" class="inputbox"', 'value', 'text', $filter );

		// search filter
		$lists['search']= $search;
			
			$this->addToolBar();
			
			$this->viewAnswers = JRequest::getInt("answers",0);
			$this->assignRef('lists'      	, $lists);
			$this->assignRef('rows'      	, $rows);
			$this->assignRef('pageNav' 		, $pageNav);
			$this->assignRef('user'			, $user);
			//Calculate parents..
			foreach ($this->rows as $item){
				$parent = NULL;
				if ($item->parent){
					$q = "SELECT * FROM #__questions_core WHERE id=" . (int)$item->parent;
					$db = JFactory::getDBO();
					$db->setQuery($q);
					$parent = $db->loadObject();
				}
				$item->parentData = $parent;
				
				//tags
				//$item->tags = json_decode($item->tags);
				
			}
			
            // Display the template
            parent::display($tpl);
        }
		
		function cleanString($string) {
		  	$clear = strip_tags($string);
			// Clean up things like &amp;
			$clear = html_entity_decode($clear);
			// Strip out any url-encoded stuff
			$clear = urldecode($clear);
			// Replace non-AlNum characters with space
			$clear = preg_replace('/[^A-Za-z0-9]/', ' ', $clear);
			// Replace Multiple spaces with single space
			$clear = preg_replace('/ +/', ' ', $clear);
			// Trim the string of leading/trailing space
			$clear = trim($clear);
			return $clear;
		}
        
		protected function addToolBar() 
        {
        	$user= JFactory::getUser();
        	
            JToolBarHelper::title(JText::_('COM_QUESTIONS_QUESTIONS'));
            
            QuestionsHelper::canDo("core.create") ? JToolBarHelper::addNew('question.add') : NULL ;
            QuestionsHelper::canDo("core.edit") ? JToolBarHelper::editList('question.edit') : NULL;
            QuestionsHelper::canDo("question.publish") ? JToolBarHelper::publishList("questions.publish") : NULL;
            QuestionsHelper::canDo("question.publish") ? JToolBarHelper::unpublishList("questions.unpublish") : NULL;
            QuestionsHelper::canDo("question.delete") ? JToolBarHelper::deleteList(JText::_("COM_QUESTIONS_ITEM_DEL_Q"), 'questions.delete') : NULL;
            QuestionsHelper::canDo("core.admin") ? JToolBarHelper::preferences("com_questions") : NULL;
        }
}
