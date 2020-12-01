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

require_once JPATH_SITE.'/components/com_content/helpers/route.php';
jimport('joomla.application.categories');

class QuestionsViewEmpty extends QueView
{
        // Overwriting JView display method
        function display($tpl = null) 
        {
			 if (count($errors = $this->get('Errors'))) {
                        JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');
                        return false;
            }
             // Display the view
			parent::display($tpl);
        	
        }
		
		function getCategories(){
					$limit = $this->params->get('cat_limit', 1);
					$ordering = $this->params->get('cat_ordering', 'c.id');
					$db = JFactory::getDBO();
					$query = $db->getQuery(true);
					$query->select('c.id, c.parent_id, c.lft, c.rgt, c.level, c.title');
					$query->from('#__categories as c');
					$query->where('c.extension= "com_questions"');
					$query->order($ordering. ' ASC LIMIT '. $limit);
					$db->setQuery((string)$query);
					$categories = $db->loadObjectList();
					return $categories;
					
		}

		function countCat($catid){
					$this->catid = $catid;
					$db = JFactory::getDBO();
					$query = $db->getQuery(true);
					$query->select('count(*) FROM #__questions_core AS c WHERE c.catid='.(int)$catid.' AND c.question=1');
					//echo $query;
					$db->setQuery($query);
					$result = $db->loadResult();
					return $result;
	
		}
		
		function nested($left,$right,$lvl)
		{
					$sublimit = $this->params->get('sub_cat_limit', 1);
					$subordering = $this->params->get('sub_cat_ordering', 'c.id');
					$this->left = $left;
					$this->right = $right;
					$this->lvl = $lvl;
					$db = JFactory::getDBO();
					$query = $db->getQuery(true);
					$query->select('c.id, c.parent_id, c.lft, c.rgt, c.level, c.title');
					$query->from('#__categories as c');
					$query->where('c.extension= "com_questions" AND c.lft >'. $left.' AND c.rgt <'. $right.' AND c.level= '.$lvl);
					$query->order($subordering. ' ASC LIMIT '. $sublimit);
					$db->setQuery((string)$query);
					$nestedcat = $db->loadObjectList();
     				return $nestedcat;
			}	
			
}
