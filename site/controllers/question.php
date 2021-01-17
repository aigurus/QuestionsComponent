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
defined('_JEXEC') or die();
class QuestionsControllerQuestion extends QueController {

	 /*$addfav = (int) JRequest::getInt("addfav");
	 $vardata = JFactory::getApplication()->input->get("vardata");
	 $userid = (int) JRequest::getInt("userid");
	 $delfav = (int) JRequest::getInt("delfav");*/

	public function __construct(){
		parent::__construct();
	}

	public function votepositive(){ 
		$this->vote(true);
	}
	
	public function votenegative(){
		$this->vote(false);
	}
	
	private function vote( $positive = TRUE ){
		$id = (int) JRequest::getInt("id");
		//get db instance
		$db = JFactory::getDBO();
		
		//build select query
		$query = "SELECT * FROM #__questions_core WHERE id='$id'";
			
		//fetch data
		$db->setQuery($query);
		$question = $db->loadObject();
		
		if ($question->parent){
			$questionid = $question->parent;
		}
		else {
			$questionid = $id;
		}	
		
		$user = JFactory::getUser();
				
		if (!$question) {
			JError::raiseError(404, JText::_("COM_QUESTIONS_ERROR_404"));
		}

		if( ! $user->authorise("question.vote" , "com_questions")){
			$msg = JText::_("COM_QUESTIONS_ERROR_UNAUTHORIZED");
			//$this->setRedirect(JRoute::_("index.php?option=com_questions&view=question&id=" . $questionid,false) , $msg );
			//$link = JRoute::_("index.php?option=com_questions&view=question&id=" . $questionid,false);
			/*$link = JRoute::_("index.php?option=com_questions");
			$app = JFactory::getApplication();
			$app->enqueueMessage($msg);
			$app->redirect($link);*/
			
			$this->setMessage($msg);
			$this->setRedirect(JRoute::_("index.php?option=com_questions&view=question&id=" . $questionid,false));
			//exit;
		}
		
		
		
		else {			
			//check whether the user has already voted
			$hasUserVoted = FALSE;
			$users_voted = json_decode($question->users_voted);
			if ($user->id){
				$hasUserVoted = in_array((string) $user->id, $users_voted);
				$msg = JText::_("COM_QUESTIONS_VOTE_VOTED");
			}
			$app = JFactory::getApplication();
			$params = $app->getParams();
			$guest = JFactory::getUser()->guest; 
			$votingreq=$params->get('votingreq',0);
			$votinglevel=intval($params->get('votinglevel',0));
			if($votingreq==1 &&($guest || $this->getPoints($user->id)<$votinglevel)){
				$msg = JText::_("COM_QUESTIONS_LEVEL_NOT_REACHED");
				/*$this->setRedirect(JRoute::_("index.php?option=com_questions&view=question&id=" . $questionid,false) , $msg );*/
			}
			if (!$hasUserVoted && $votingreq==0){ //The user has not voted.. proceed updating entry
			/*For level based voting*/
			
			/*Level based voting Ends*/
			
				$votes_positive = $question->votes_positive;
				$votes_negative  = $question->votes_negative;
				if ($positive){
					$votes_positive++;
				}
				else {
					$votes_negative++;
				}
				$users_voted = json_decode($question->users_voted);
				if ($user->id){ //save the user's vote
					$users_voted[] = (string)$user->id;	
				}
				$users_voted = json_encode($users_voted);
				
				//update row
				$query = "UPDATE #__questions_core SET votes_positive='$votes_positive', votes_negative='$votes_negative', users_voted='$users_voted' WHERE id='$id'";
				
				$db->setQuery($query);
				if (!$db->query()){
					JError::raiseError(404, JText::_("COM_QUESTIONS_ERROR"));
				}
				$msg = JText::_("COM_QUESTIONS_VOTE_SAVED");
			}
			if(!$hasUserVoted && $this->getPoints($user->id)<$votinglevel && $votingreq==1){
				$msg = JText::_("COM_QUESTIONS_LEVEL_NOT_REACHED");
			}
			if(!$hasUserVoted && $this->getPoints($user->id)>$votinglevel && $votingreq==1){
				$votes_positive = $question->votes_positive;
				$votes_negative  = $question->votes_negative;
				if ($positive){
					$votes_positive++;
				}
				else {
					$votes_negative++;
				}
				$users_voted = json_decode($question->users_voted);
				if ($user->id){ //save the user's vote
					$users_voted[] = (string)$user->id;	
				}
				$users_voted = json_encode($users_voted);
				
				//update row
				$query = "UPDATE #__questions_core SET votes_positive='$votes_positive', votes_negative='$votes_negative', users_voted='$users_voted' WHERE id='$id'";
				
				$db->setQuery($query);
				if (!$db->query()){
					JError::raiseError(404, JText::_("COM_QUESTIONS_ERROR"));
				}
				$msg = JText::_("COM_QUESTIONS_VOTE_SAVED");
			}
			//Redirect the user accordingly	
			//$this->setRedirect(JRoute::_("index.php?option=com_questions&view=question&id=" . $questionid) , $msg );

			$link = JRoute::_("index.php?option=com_questions&view=question&id=" . $questionid);
			$app = JFactory::getApplication();
			$app->enqueueMessage($msg);
			$app->redirect($link);

		}
	}
	
	public function edit() {
		
		
		$message =  NULL;
		$id = JRequest::getInt("id");
		
		
		//check whether the user is allowed to edit this question
		if (TRUE) {
			JFactory::getApplication()->setUserState("com_questions.edit.form.id" , $id );
			$url = "index.php?option=com_questions&view=form&layout=edit&id=$id";
		}
		else { //Not allowed
			//redirect
		}
		$this->setRedirect( JRoute::_( $url ) , $message );
	}
	public function getFavourite($vardata,$userid){
					
				    $vardata = JFactory::getApplication()->input->get("vardata");
				    $userid = (int) JRequest::getInt("userid");
					$db = JFactory::getDBO();
					$query2 = $db->getQuery(true);
					$query2->select("$vardata");
					$query2->from('#__questions_favourite');
					$query2->where('userid='.$userid);
					//var_dump($query2);
					$db->setQuery((string)$query2);
					$result = $db->loadResult();
					return $result;
					
		   }
	public function getusercount($userid){
				    $userid = (int) JRequest::getInt("userid");
					$db = JFactory::getDBO();
					$query2 = $db->getQuery(true);
					$query2->select("count(*)");
					$query2->from('#__questions_favourite');
					$query2->where('userid='.$userid);
					//var_dump($query2);
					$db->setQuery((string)$query2);
					$result = $db->loadResult();
					return $result;
					
		   }
   public function favtable($arrayData,$userid,$vardata,$inup, $id, $method="add"){
   					
		   			$serializedData3 = serialize($arrayData);
					$data =new stdClass();
					if($vardata =='ansfav'){
					$data->ansfav = $serializedData3;
					$data->quesfav = null;
					$data->userfav = null;					
					}
					elseif($vardata =='quesfav'){
					$data->ansfav = null;
					$data->quesfav = $serializedData3;
					$data->userfav = null;					
					}
					elseif($vardata =='userfav'){
					$data->ansfav = null;
					$data->quesfav = null;
					$data->userfav = $serializedData3;					
					}
					$data->id = null;
					$data->userid = $userid;
					$db = JFactory::getDBO();
					if($inup=='in')
					{
					$db->insertObject( '#__questions_favourite', $data);
					}
					else{
					$db->updateObject( '#__questions_favourite', $data, userid );
					}
					$db = JFactory::getDBO();
					$query = $db->getQuery(true);
					if($method=="add"){
						$query = "UPDATE #__questions_core SET likes=likes+1 WHERE id=".$id;
					} 
					if($method=="del"){
						$query = "UPDATE #__questions_core SET likes=likes-1 WHERE id=".$id;
					}
			
					$db->setQuery($query);
					
					$db->execute();
					
					
					$link  = JRoute::_("index.php?option=com_questions&view=question&id=" . $id);
				    $msg   = 'Your favourites modified accordingly.';  
				    $app = JFactory::getApplication();
				    $app->redirect($link, $msg);
		   
		   }
	   public function addFavourite()
	   {
			$reguser = JFactory::getUser();   
			if( !$reguser->authorise("access.favourite" , "com_questions")){
				$msg = JText::_("COM_QUESTIONS_ERROR_UNAUTHORIZED");
				$this->setRedirect(JRoute::_("index.php?option=com_users&view=login"),$msg );
			}
			else{
				$addfav  =  JFactory::getApplication()->input->get("addfav");
				$vardata =  JFactory::getApplication()->input->get("vardata");
				$userid  =  JFactory::getApplication()->input->get("userid");
	
				$favarray = unserialize($this->getFavourite($vardata,$userid));
				//var_dump($favarray);
				if ((empty($favarray)) && $this->getusercount($userid)==0){	
					$arrayData =array($addfav);
					//var_dump($arrayData);
					$this->favtable($arrayData,$userid,$vardata,'in',$addfav,$method="add");
				}
				elseif (in_array($addfav, $favarray, true))	{
					return false;
				}
				else
				{
					$arrayData = $favarray;
					$arrayData[]=$addfav;
					$this->favtable($arrayData,$userid,$vardata,'up',$addfav,$method="add");
				}
			}
	
			return true;
			exit;
	
		}
     	public function delFavourite(){

					$reguser = JFactory::getUser();   
   					if( !$reguser->authorise("access.favourite" , "com_questions")){
					$msg = JText::_("COM_QUESTIONS_ERROR_UNAUTHORIZED");
					$this->setRedirect(JRoute::_("index.php?option=com_users&view=login"),$msg );
				    }
					else{
					$vardata = JFactory::getApplication()->input->get("vardata");
					$delfav =  JFactory::getApplication()->input->get("delfav");
					$userid  =  JFactory::getApplication()->input->get("userid");

		   			$favarray = unserialize($this->getFavourite($vardata,$userid));
					//var_dump($favarray); exit;
					$finalfav = array_search($delfav,$favarray);
					if (false !== $finalfav) {
						unset($favarray[$finalfav]);
					}
					//var_dump( $finalfav);
					$arrayData = $favarray;

					$this->favtable($arrayData,$userid,$vardata,'up',$delfav,$method="del");
					}
					return true;
					exit;
		}
		public function getPoints($userid){
					$db = JFactory::getDBO();
					$query = $db->getQuery(true);
					$query->select('points');
					$query->from('#__questions_userprofile');
					$query->where('userid='.$userid);
					$db->setQuery($query);
					$points = $db->loadResult();
					return intval($points);
					
		 }
		
}