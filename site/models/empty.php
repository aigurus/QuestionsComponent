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

// import Joomla modelitem library
jimport('joomla.application.component.modellist');

class QuestionsModelEmpty extends JModelList {

	public function getItems(){
		
		$rows	= parent::getItems();
		
		$questions = $rows;
		
		foreach ($questions as $question){
			$question->link = JRoute::_( "index.php?option=com_questions&view=question&id=" . $question->id . QuestionsHelper::getActiveViewOptions() ); 
			
			//votes	        
	        $question->votes2 = $question->votes;
			$question->score2 = $question->score;
	        
	        //calculate..
		 	if ($question->votes > 1000){
	        	$v = $question->votes / 1000;
	        	$question->votes2 = round($v,1) . "K";
	        }
	        
		 	if ($question->score > 1000){
	        	$s = $question->score / 1000;
	        	$question->score2 = round($s,1) . "K";
	        }
	        	
	        //tags
	        $question->qtags = json_decode($question->qtags);
	        
		}

		$items = $questions;
		
		return $items;
	}

	function getListQuery(){
		
		$userid = JFactory::getUser()->id;

		$db = JFactory::getDbo();

		$query = $db->getQuery(TRUE);
		$query->select("a.*, a.votes_positive-a.votes_negative as score, a.votes_positive+a.votes_negative as votes, (SELECT COUNT(*) FROM #__questions_core AS b WHERE b.parent=a.id AND b.published=1) as answerscount, c.title AS CategoryName");
		$query->from("#__questions_core AS a");
		$query->leftJoin("#__categories AS c ON c.id=a.catid");

		$show_answers = $this->getState("filter.answers" , 0);
		$show_unpublished = $this->getState("filter.unpublished" , 0);
		
		$where = array();
		
		$where[] = "a.question=1"; // questions only
		
		if ( ! $show_unpublished )
			$where[] = "a.published=1"; // only published items
		
		
		//************* FILTERING - BEGIN ***************
		
		//get state
		$catid = $this->getState("filter.catid" , 0);
		$tag = $this->getState("filter.tag" , 0 );
		$answered = $this->getState("filter.answered" , 0);
		$notanswered = $this->getState("filter.notanswered" , 0);
		$resolved = $this->getState("filter.resolved" , 0);
		$unresolved = $this->getState("filter.unresolved" , 0);
		$myquestions = $this->getState("filter.myquestions" , 0);
		
		
		if ( $catid ) {
			$where[] = "a.catid='$catid'"; // category items
		}
		
			
		if ( $tag ) {
			$where[] = "a.qtags LIKE '%". $tag . "%' "; // tagged items
		}
		
		
		if ( $answered )
			$where[] = "a.id IN (SELECT parent FROM #__questions_core WHERE question=0 AND published=1)"; // answered items
		
		
		if ( $notanswered )
			$where[] = "a.id NOT IN (SELECT parent FROM #__questions_core WHERE question=0 AND published=1) AND a.question=1"; // not answered items. Note: Questions with no published answers are considered as Not Answered	

		
		if ( $resolved )
			$where[] = "a.id in (SELECT parent from #__questions_core where question=0 and chosen=1)"; // resolved questions
		
		
		if ( $unresolved )
			$where[] = "a.id in (select parent from #__questions_core where question=0 and chosen=0) and a.id not in (SELECT parent from #__questions_core where question=0 and chosen=1)"; // answered but not resolved questions
		
		if ( $myquestions )
			$where[] = "a.userid_creator=$userid"; // user's questions
		
		//************* FILTERING - END ***************
		
		//apply filters
		if ( ! empty( $where ) )
			$query->where( $where );
		
		$ordering = $this->getState( "list.ordering" , "submitted" );
		$direction = $this->getState( "list.direction" , "DESC" );
		
		$query->order("$ordering $direction");

		return $query;
	}

	public function populateState( $ordering = "submitted" , $direction = "DESC" ){
		
		$app = JFactory::getApplication();
		
		$this->setState( "list.ordering" , $ordering );
		$this->setState( "list.direction" , $direction );

		$value = JRequest::getInt('limit', $app->getCfg('list_limit', 0));
		$this->setState('list.limit', $value);

		$value = JRequest::getInt('limitstart', 0);
		$this->setState('list.start', $value);

		$user = JFactory::getUser();

    	$view_unpublished = 0;
		$viewanswers = 0;

		//Which questions can the user display?
		if ( $user->authorise("question.unpublished","com_questions") ){
			$view_unpublished = 1;
		}

		//view answers??
		if ($user->authorise("question.viewanswers" , "com_questions")){
			$viewanswers = 1;
		}


		//************ Categories & Tags - BEGIN ********** 
		
		//category
		$catid = JRequest::getInt('catid' , 0);
		$this->setState("filter.catid", $catid);
		
		//tag
		$tag = JRequest::getString("tag" , 0);
		$this->setState("filter.tag" , $tag);
		
		//************ Categories & Tags - END **********
		
		
		
		//************* FILTERING - BEGIN ***************
		
		$filter = JRequest::getString("filter");
		
		//answered
		$this->setState("filter.answered" , (int)($filter=="answered"));
		
		//not answered
		$this->setState("filter.notanswered" , (int)($filter=="notanswered"));
		
		//resolved
		$this->setState("filter.resolved" , (int)($filter=="resolved"));
		
		//unresolved
		$this->setState("filter.unresolved" , (int)($filter=="unresolved"));
		
		//user's questions -- ensure that the myquestions filter is only available to logged users
		$this->setState("filter.myquestions" , (JFactory::getUser()->id ? (int)($filter=="myquestions") : 0) );
		
		//if the 'myquestions' filter is active, user is allowed to diplay his unpublished questions
		if ($filter=="myquestions" &JFactory::getUser()->id )
			$view_unpublished=1;
		
		//************* FILTERING - END ***************
		
		
		
		//************* ORDERING - START ***************
		$appParams = json_decode(JFactory::getApplication()->getParams());
		if (isset($appParams->list_ordering)){
		$ordering = $appParams->list_ordering;
		}
		if (isset($appParams->list_direction)){
		$direction = $appParams->list_direction;
		}
		$this->setState("list.ordering" , $ordering);
		$this->setState("list.direction" , $direction);
		
		//************* ORDERING - END ***************
		
		$this->setState("filter.unpublished" , $view_unpublished );
		$this->setState("filter.answers" , $viewanswers);
	}
	
 	public function getFilteringOptions(){
        	
        $currentOptions = 
        	"&tag=" . JRequest::getString("tag") . 
        	"&catid=" . JRequest::getInt("catid");
        
        $answered = 
        	"<li><a " . (JRequest::getString("filter" , 0)=="answered"?'class=active':'') . " href='" . JRoute::_("index.php?option=com_questions&view=questions&filter=answered" . $currentOptions)  . "'>" . JText::_("COM_QUESTIONS_FILTER_ANSWERED") . "</a></li>";
        
        $notanswered = 
        	"<li><a " . (JRequest::getString("filter" , 0)=="notanswered"?'class=active':'') . " href='" . JRoute::_("index.php?option=com_questions&view=questions&filter=notanswered" . $currentOptions)  . "'>" . JText::_("COM_QUESTIONS_FILTER_NOTANSWERED") . "</a></li>";
        
        $resolved = 
        	"<li><a " . (JRequest::getString("filter" , 0)=="resolved"?'class=active':'') . " href='" . JRoute::_("index.php?option=com_questions&view=questions&filter=resolved" . $currentOptions)  . "'>" . JText::_("COM_QUESTIONS_FILTER_RESOLVED") . "</a></li>";
        
        $unresolved = 
        	"<li><a " . (JRequest::getString("filter", 0)=="unresolved"?'class=active':'') . " href='" . JRoute::_("index.php?option=com_questions&view=questions&filter=unresolved" . $currentOptions)  . "'>" . JText::_("COM_QUESTIONS_FILTER_UNRESOLVED") . "</a></li>";
        
        $myquestions = NULL;
        if ( JFactory::getUser()->id )
        $myquestions = 
        	"<li><a " . (JRequest::getString("filter", 0)=="myquestions"?'class=active':'') . " href='" . JRoute::_("index.php?option=com_questions&view=questions&filter=myquestions" . $currentOptions)  . "'>" . JText::_("COM_QUESTIONS_FILTER_MYQUESTIONS") . "</a></li>";
                
        $options = "<div class='questions_filters'><ul>" . $answered . $notanswered . $resolved . $unresolved . $myquestions . "</ul></div>";
        
        return $options;
 	}

}
