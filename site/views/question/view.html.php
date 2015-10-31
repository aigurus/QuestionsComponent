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

class QuestionsViewQuestion extends QueView
{
        // Overwriting JViewLegacy display method
        function display($tpl = null) 
        {
        	            
        	$user = JFactory::getUser();
        	$app = JFactory::getApplication();
        	
        	$this->question = $this->get("Item");

        	//Authorizations
        	$user = JFactory::getUser();
			$authview = $user->authorise("question.viewanswers" , "com_questions");
			$authans = $user->authorise("question.answer" , "com_questions");
			$authfav = $user->authorise("access.favourite" , "com_questions");
        	$this->assignRef("viewanswers", $authview);
        	$this->assignRef("submitanswers", $authans);
			$this->assignRef("addfavorites", $authfav);
        	
        	//params
        	$params = $app->getParams();
        	$this->assignRef("params", $params);
			$pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));
        	$this->assignRef("pageclass_sfx" , $pageclass_sfx);
        	//var_dump($this->question); exit;
        	//check ownership
        	$isOwner = (bool)($user->id == $this->question->userid_creator && $user->id != 0 );
        	$this->assignRef("isOwner", $isOwner);  

        	//Add Pathway
        	QuestionsHelper::addPathway(); 
        	$pathway=$app->getPathway();
        	$pathway->addItem ( $this->question->title );
        	
        	if ( @$this->question && $this->question->viewable ){ //check for questions, suppressing errors..
	        		parent::display($tpl);

        	}
        	else{
				//$app =JFactory::getApplication();
				//$app->redirect('index.php?option=com_questions&view=form&layout=edit');
				JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');
        		JError::raiseNotice(404, JText::_("COM_QUESTIONS_ERROR_404"));
        	}
        }
		
		function cleanString($string) {
					// Strip HTML Tags
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
		   function getChosen($questionid){
					$db = JFactory::getDBO();
					$query = $db->getQuery(true);
					$query->select('count(*)');
					$query->from('#__questions_core');
					$query->where('parent="'.$questionid.'" AND chosen=1');
					$db->setQuery((string)$query);
					$count = $db->loadResult();
					return $count;
					
		   }
		   function getBestAnswerid($questionid){
					$db = JFactory::getDBO();
					$query = $db->getQuery(true);
					$query->select('id');
					$query->from('#__questions_core');
					$query->where('parent="'.$questionid.'" AND chosen=1');
					$db->setQuery((string)$query);
					$count = $db->loadResult();
					return $count;
					
		   }
		   public function getFavourite2($vardata,$userid){
					$db = JFactory::getDBO();
					$query2 = $db->getQuery(true);
					$query2->select("$vardata");
					$query2->from('#__questions_favourite');
					$query2->where('userid='.$userid);
					$db->setQuery((string)$query2);
					$result = $db->loadResult();
					return $result;
					
		   }
		   
		   function smarttrim($string){
				if(strlen($string)>40){
					$separator = '...';
					$separatorlength = strlen($separator) ;
					$maxlength = 40 - $separatorlength;
					$start = $maxlength / 2 ;
					$trunc =  strlen($string) - $maxlength;
					
					return substr_replace($string, $separator, $start, $trunc);
				}
				else{
					return $string;	
				}
			}
			function addhttp($domain){
				if (strpos($domain, "http://") !== false) {
				return $domain;
				} else {
				return "http://" . $domain;
				}
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

}
