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

class QuestionsViewQuestions extends QueView
{
        // Overwriting JViewLegacy display method
        function display($tpl = null) 
        {
        	
        	$app = JFactory::getApplication();
        	$pathway = $app->getPathway();
        	$user = JFactory::getUser();
        	$this->document = JFactory::getDocument();
        	
        	$this->questions = $this->get("Items");
        	$this->pagination = $this->get("Pagination");
        	
        	$this->filteringOptions = $this->get("filteringOptions");
        	$this->sortingOptions = $this->get("sortingOptions");
        	$this->activeFilter = JRequest::getString("filter");
        	
        	//Category View
        	$this->categoryView = FALSE; //Initialization
        	if (JRequest::getInt( "catid" , 0 ))
        		$this->categoryView = TRUE;
        		
        	//Tag View
        	$this->tag = JRequest::getString("tag" , NULL);
        	
        	//Authorizations
			$viewans = $user->authorise("question.viewanswers" , "com_questions");
			$submitans = $user->authorise("question.answer" , "com_questions");
        	$this->assignRef("viewanswers", $viewans);
        	$this->assignRef("submitanswers", $submitans);
        	
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
			/*
			if (isset($appParams->display_sorting)){
        	$this->viewSortingOptions = $appParams->display_sorting;
			}
			*/
			if (isset($appParams->display_gravatars)){
        	$this->viewGravatars = $appParams->display_gravatars;
			}
        	
        	
        	//Add Pathway
        	QuestionsHelper::addPathway();    	
        	       
        	if ( @$this->questions ){ //check for questions, suppressing errors..
	        	
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
	
	
	function getActiveDuration($modified){
		
			$date1 = strtotime(JHtml::date('now', 'Y-m-d H:i:s'));  
			$date2 = strtotime($modified);  
			
			
			$diff = abs($date2 - $date1);  
			$years = floor($diff / (365*60*60*24));  
			$months = floor(($diff - $years * 365*60*60*24) 
										   / (30*60*60*24));  
			$days = floor(($diff - $years * 365*60*60*24 -  
						 $months*30*60*60*24)/ (60*60*24)); 
			$hours = floor(($diff - $years * 365*60*60*24  
				   - $months*30*60*60*24 - $days*60*60*24) 
											   / (60*60));  
			$minutes = floor(($diff - $years * 365*60*60*24  
					 - $months*30*60*60*24 - $days*60*60*24  
									  - $hours*60*60)/ 60);  
			$seconds = floor(($diff - $years * 365*60*60*24  
					 - $months*30*60*60*24 - $days*60*60*24 
							- $hours*60*60 - $minutes*60)); 
			if($years>0 && $months>0 && $days>0 && $hours>0 && $minutes>0 && $seconds>0){
			 	return $years."Y-".$months."M";}
			elseif($years==0 && $months>0 && $days>0 && $hours>0 && $minutes>0 && $seconds>0){
				return $months."M-".$days."D"; }
			elseif($years==0 && $months==0 && $days>0 && $hours>0 && $minutes>0 && $seconds>0){
				return $days."D-".$hours."h";}
			elseif($years==0 && $months==0 && $days==0 && $hours>0 && $minutes>0 && $seconds>0){
				return $hours."h-".$minutes."m";}
			elseif($years==0 && $months==0 && $days==0 && $hours==0 && $minutes>0 && $seconds>0){
				return $minutes."m-".$seconds."s";}
			elseif($years==0 && $months==0 && $days==0 && $hours==0 && $minutes==0 && $seconds>0){
				return $seconds."s";}
			else {return false;};
	}
} 
