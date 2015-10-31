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

// import the Joomla modellist library
jimport('joomla.application.component.modellist');

class QuestionsModelReports extends JModelList
{
		public function __construct($config = array())
			{
				if (empty($config['filter_fields'])) {
					$config['filter_fields'] = array(
					'id', 'a.userid',
					'userid', 'a.userid',
					'email', 'a.email',
					'title', 'a.title',
					'qareport', 'a.qareport',
					'ip', 'a.ip',
					);
				}
		
				parent::__construct($config);
			}
			
			
		protected function populateState( $ordering = "id" , $direction = "DESC" ){
			
			$app = JFactory::getApplication();
			
			//Ordering
			$this->setState( "list.ordering" , $ordering );
			$this->setState( "list.direction" , $direction );
	
			//Pagination
			$value = JRequest::getInt('limit', $app->getCfg('list_limit', 0));
			$this->setState('list.limit', $value);
			
			//Pagination
			$value = JRequest::getInt('limitstart', 0);
			$this->setState('list.start', $value);
			
			parent::populateState('a.id', 'asc');
		}
		
       	protected function getListQuery()
        {
		
				    $db = JFactory::getDBO();
					$query = $db->getQuery(true);
					$query->select(
						$this->getState(
							'list.select',
							'a.*'
						)
					);
					$query->from('#__questions_reports AS a');
					$state = $this->getState('filter.state');

					// If the model is set to check the activated state, add to the query.
					$active = $this->getState('filter.active');
					
					// Filter by excluded users
					$excluded = $this->getState('filter.excluded');
					if (!empty($excluded)) {
						$query->where('id NOT IN ('.implode(',', $excluded).')');
					}
			
					// Add the list ordering clause.
					$query->order($db->escape($this->getState('list.ordering', 'a.id')).' '.$db->escape($this->getState('list.direction', 'ASC')));
			
					//echo nl2br(str_replace('#__','jos_',$query));
					return $query;
		
					/*$query->SELECT('a.* FROM #__questions_reports As a');
					$query->order($this->getState("list.ordering") . " " . $this->getState("list.direction"));
                   	return $query;*/
        }
        
        function delete(){
        	
        	$cids = implode(",",  JFactory::getApplication()->input->get("cid"));
        	
        }
		
		/*public function getItems()
		{
					$db= JFactory::getDBO();
					$sql= "SELECT a.* FROM #__questions_reports AS a";
					$db->setQuery($sql);
					$items = $db->loadObjectList();   
					return $items;
		}*/
}
