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
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

class QuestionsModelQuestion extends JModelItem {
	
	protected $id;
	protected $item;
	
	public function getItem($id = null){
		
		
		if ($this->item){
			return $this->item;
		}
		
		if ( ! $id ) {
			$id = $this->getState("question.id");
		} 
		
		$this->id = $id;
		
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(TRUE);
		$query->select("a.*, c.title AS CategoryName");
		$query->from("#__questions_core AS a");
		$query->leftJoin("#__categories AS c ON c.id=a.catid");
		
		$where = "a.id=$id";
		$query->where($where);
		
	
		$db->setQuery($query);
		$question = $db->loadObject();
		
		if (! $question ){
			return;
		}
	
		$question->answers = $this->getAnswers();
		$question->link = JRoute::_( "index.php?option=com_questions&view=question&id=" . QuestionsHelper::getAlias($question->id) . QuestionsHelper::getActiveViewOptions());
		
		foreach ($question->answers as $a){
			if (!$a->name){
				$a->name = JFactory::getUser($a->userid_creator)->name;
			}
		}
		
		//votes
        $question->votes = $question->votes_positive + $question->votes_negative;
        $question->score = $question->votes_positive - $question->votes_negative;
        
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
		$qtags = $question->qtags;
		$arr=explode(",",$qtags);
		$question->qtags = $arr;

        //$question->qtags = (array)($question->qtags);
        
        //editable?
        $question->editable = $this->isEditable( $question );
        
        //viewable?
        $question->viewable = $this->isViewable( $question );
		
		$this->item = $question;
		
		//hit!
		$this->hit();
		
		return $this->item;	
	}
	
	public function getAnswers(){
		
		
		if ( ! $this->getState("filter.answers") ){
		}
		
		$db = JFactory::getDbo();
		
		$userid = JFactory::getUser()->id;
		
		$query = $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__questions_core");
		
		//Users can view their asnwers even if they're not published
		if ($userid == 0 ) //unregistered user..
			$userid = -1; //prevent unregistered users from handled as questions owners
		$where = "parent=" . $this->id . " AND (published=1 OR userid_creator=$userid)";
		
		$query->where($where);		
		$query->order("chosen DESC, votes_positive-votes_negative DESC, submitted DESC");
		
	
		$db->setQuery($query);
		$answers = $db->loadObjectList();
		
		if ($answers){
			//votes 
	        foreach ($answers as $answer){
		        $answer->votes = $answer->votes_positive + $answer->votes_negative;
		       	$answer->score = $answer->votes_positive - $answer->votes_negative;
		       	
		       	$answer->votes2 = $answer->votes;
		       	$answer->score2 = $answer->score;
		       	
		         //calculate..
			 	if ($answer->votes > 1000){
		        	$v = $answer->votes / 1000;
		        	$answer->votes2 = round($v,1) . "K";
		        }
		        
			 	if ($answer->score > 1000){
		        	$s = $answer->score / 1000;
		        	$answer->score2 = round($s,1) . "K";
		        }
	        }
			return $answers;
		}
		else {
			return array();
		}
	}
	
	protected function populateState(){
		
		
		$app = JFactory::getApplication('site');
		
		// Load state from the request.
		$id = JRequest::getInt('id');
		$this->setState('question.id', $id);
		
		$user = JFactory::getUser();
		
		$this->setState( "filter.unpublished" , $user->authorise("question.unpublished" , "com_questions") );
		$this->setState( "filter.answers" , $user->authorise("question.answers" , "com_questions") );

		
	}	
	
	public function hit( $id=NULL ){
		
		if ($id){
			$this->id = $id;
		}
		
		if ( $this->id ){
				
			$db = JFactory::getDbo();
			
			$q = "UPDATE #__questions_core SET impressions = impressions+1 WHERE id=" . (int)$this->id;				
			$db->setQuery($q);
			
			if (!$db->execute()){
				return FALSE;
			}
			
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	public function publish(){
		
		if ($id){
			$this->id = $id;
		}
			
		if ( $this->id ){

			$db = JFactory::getDbo();
			
			if ($this->item){
				$state = $item->published;
			}
			else {
				$q = $db->getQuery ( TRUE );
				$q->select("state");
				$q->from("#__questions_core");
				$q->where("id=" . $this->id );
				
				$db->setQuery($q);
				$row = $db->loadObject();
				
				$state = $row->published;
			}
			
			$state = ($state-1) ^ 2; //Quarter will allways make it positive or 0;
			
			$q = "UPDATE #__questions_core SET published = " . $state . " WHERE id=" . (int)$this->id;				
			$db->setQuery($q);
			
			if (!$db->execute()){
				return FALSE;
			}
			
			return TRUE;
		}
		else {
			return FALSE;
		}
	}			
	
	public function vote( $id = null , $negative = FALSE ){
	
		if ($id){
			$this->id = $id;
		}
			
		if ( $this->id ){
			$db = JFactory::getDbo();
			
			if ($negative){
				$q = "UPDATE #__questions_core SET votes_negative = votes_negative+1 WHERE id=" . (int)$this->id;
			}
			else {
				$q = "UPDATE #__questions_core SET votes_positive = votes_positive+1 WHERE id=" . (int)$this->id;	
			}
			
			$db->setQuery($q);
			
			if (!$db->execute()){
				return FALSE;
			}
			
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	protected function isEditable( $question ) {
		
		
		$user = JFactory::getUser();
		
		$isOwner = ( $question->userid_creator === $user->id );
		
		if ( 	( 	$isOwner 
					&& $user->authorise ( "core.edit" , "com_questions" )
				) 
				|| $user->authorise ( "core.edit_any" , "com_questions" ) )
			return true;
		
		/* else */
		return false;		
	}
	
	
	protected function isViewable ( $question ) {
		
		
		$user = JFactory::getUser();
		
		$isOwner = ( $question->userid_creator === $user->id );
		
		$db = JFactory::getDbo();
		$q = $db->getQuery ( TRUE );
		$q->select("groups");
		$q->from("#__questions_core");
		$q->where("id=" . $question->id );
		
		$db->setQuery($q);
		$row = $db->loadObject();
		
		$groupid = $row->groups;
		if(!empty($groupid)){
		$q2 = $db->getQuery ( TRUE );
		$q2->select("requestsent");
		$q2->from("#__questions_groups");
		$q2->where("id=" . $groupid );
		
		$db->setQuery($q2);
		$row2 = $db->loadObject();
		
		$friendsids = $row2->requestsent;
		$friends = unserialize($friendsids);
		if(!$isOwner){
		if(count(isset($groupid)) && count($friends)>0){
			if((in_array($user->id,$friends,true))){
				return true;
			}
			else
			{
			$app = JFactory::getApplication();
			 
			// Add a message to the message queue
			$app->enqueueMessage(JText::_('COM_QUESTIONS_NOT_IN_THE_GROUP'), 'error');
			$app->redirect('index.php?option=com_questions');
			}
		}
		}
		}
		if ( $question->published)
			return TRUE; else
			
		if ( $isOwner || $user->authorise ( "question.unpublished" , "com_questions" ) )
			return TRUE;
		
		/* else */
		return false;
	}
	/*
	protected function getRelatedProducts($productName)
	{
		$query = "SELECT * FROM #__questions_core WHERE tag = '$productName' LIMIT 0,1";
	
		$relatedProducts = array();
	
		if(mysql_num_rows($productResults) == 1)
		{
			$product = mysql_fetch_array($productResults);
			$tags = explode(",",$product['tags']);
	
			$otherProducts = "SELECT * FROM #__questions_core WHERE productName != '$productName'";
	
			while($otherProduct = $db->query($otherProducts))
			{
				$otherTags = explode(",",$otherProduct['tags']);
				$overlap = array_intersect($tags,$otherTags);
				if(count($overlap > 1)) $relatedProducts[] = $otherProduct;
			}
		}
	
		return $relatedProducts;
	}
		*/
}
