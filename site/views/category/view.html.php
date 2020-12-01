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

//require_once JPATH_SITE.'/components/com_content/helpers/route.php';
jimport('joomla.application.categories');

class QuestionsViewCategory extends QueView
{
        // Overwriting JViewLegacy display method
        function display($tpl = null) 
        {
			//$model = JModelLegacy::getInstance('Categories', 'QuestionsModel');
        	//var_dump($model);  exit;
        	$app = JFactory::getApplication();
        	$pathway = $app->getPathway();
        	$user = JFactory::getUser();
        	$this->document = JFactory::getDocument();
        	
        	$this->categories = $this->get("Items");
        	$this->pagination = $this->get("Pagination");
        	
			//var_dump($this->categories); exit;
    		$this->filteringOptions = $this->get("filteringOptions");
        	//$this->sortingOptions = $this->get("sortingOptions");
        	$this->activeFilter = JRequest::getString("filter");
       	
        	//params
        	$params = $app->getParams();
        	$this->assignRef("params", $params);
			$pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));
        	$this->assignRef("pageclass_sfx" , $pageclass_sfx);
        	
        	//view options
        	$appParams = json_decode(JFactory::getApplication()->getParams());
			if (isset($appParams->display_stats)){
        	$this->viewStats = $appParams->display_stats;
			}
			if (isset($appParams->display_filters)){
        	$this->viewFilteringOptions = $appParams->display_filters;
			}
        	
        	//Add Pathway
        	QuestionsHelper::addPathway();    	
        	       
        	if ( @$this->categories ){ //check for questions, suppressing errors..
	        	
	        	// Add feed links
				$link = '&format=feed&limitstart=';
				$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
				$this->document->addHeadLink(JRoute::_($link . '&type=rss'), 'alternate', 'rel', $attribs);
				$attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
				$this->document->addHeadLink(JRoute::_($link . '&type=atom'), 'alternate', 'rel', $attribs);
        		
	        	parent::display($tpl);
        	}
        	else{
				//$logger->error("No Results..");
        		//JError::raiseNotice(404, JText::_("COM_QUESTIONS_ERROR_404"));
				JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');
				$app =JFactory::getApplication();
				$app->enqueueMessage(JText::_('COM_QUESTIONS_NO_RESULT'));
				$app->redirect(JRoute::_("index.php?option=com_questions&view=empty"));
        	}
        	
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
		
			function getRank($userid){
					$this->userid = $userid;
					$db = JFactory::getDBO();
					$query = $db->getQuery(true);
					$query->select('rank,userid');
					$query->from('#__questions_userprofile');
					$query->where('userid='.$userid);
					$db->setQuery((string)$query);
					$rank = $db->loadResult();
					return $rank;
					
		   }
		   
		   function getId($userid){
					$rank = $this->getRank($userid);
					$db = JFactory::getDBO();
					$query = $db->getQuery(true);
					$query->select('id');
					$query->from('#__questions_ranks');
					$query->where('rank="'.$rank.'"');
					$db->setQuery((string)$query);
					$image = $db->loadResult();
					return $image;
					
		   }
		   
		  function getGroupDetails($gid){
			  $db =JFactory::getDBO();
			  $query = "SELECT group_name FROM #__questions_groups where id=" . $gid;
			  $db->setQuery( $query );
			  $groupdetails = $db->loadResult();
			  //var_dump()
			  return $groupdetails;
		  }
		function getSortingOptions(){
		
			$currentOptions = 
        	"&tag=" . JRequest::getString("tag"). 
        	"&catid=" . JRequest::getInt("catid").
			"&filter=" . JRequest::getString("filter");
	
		?>
		<div style="float:right">
		<select name="menu" onChange="window.document.location.href=this.options[this.selectedIndex].value;" value="Choose">
        
        <option selected="selected"><?php echo JText::_("COM_QUESTIONS_SORT_SORTING_OPTIONS"); ?></option>
        
        <option value="<?php echo JRoute::_("index.php?option=com_questions&view=questions&sort=submitted&dir=desc" . $currentOptions); ?> "><?php echo JText::_("COM_QUESTIONS_SORT_SUBMITTED_DESC"); ?></option>
        <option value="<?php echo JRoute::_("index.php?option=com_questions&view=questions&sort=impressions&dir=desc" . $currentOptions); ?> "><?php echo JText::_("COM_QUESTIONS_SORT_VIEWS_DESC"); ?></option>
        <option value="<?php echo JRoute::_("index.php?option=com_questions&view=questions&sort=votes_positive&dir=desc" . $currentOptions); ?> "><?php echo JText::_("COM_QUESTIONS_SORT_VOTES_DESC"); ?></option>

        <option value="<?php echo JRoute::_("index.php?option=com_questions&view=questions&sort=submitted&dir=asc" . $currentOptions); ?> "><?php echo JText::_("COM_QUESTIONS_SORT_SUBMITTED_ASC"); ?></option>
        <option value="<?php echo JRoute::_("index.php?option=com_questions&view=questions&sort=impressions&dir=asc" . $currentOptions); ?> "><?php echo JText::_("COM_QUESTIONS_SORT_VIEWS_ASC"); ?></option>
        <option value="<?php echo JRoute::_("index.php?option=com_questions&view=questions&sort=votes_positive&dir=asc" . $currentOptions); ?> "><?php echo JText::_("COM_QUESTIONS_SORT_VOTES_ASC"); ?></option>
        
        </select>
        </div>
        <?php
   
		return;
 	}
	function getAlias ( $id ) {
			 if ($id == 0)
				return; 
			 
			$db = JFactory::getDbo();
			
			$query = $db->getQuery(TRUE);
		 
			$query = 'select alias, CASE WHEN CHAR_LENGTH(alias)>0 THEN CONCAT_WS(":", id, alias) ELSE id END as slug from #__categories WHERE id='.$id;
	
			$db->setQuery($query);
			$row = $db->loadObjectList();
			
			return $row[0]->slug;	
		 	
	}	
		   			
} 
