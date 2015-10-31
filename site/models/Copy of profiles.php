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

// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.modellist' );
jimport( 'joomla.html.parameter' );

class QuestionsModelProfiles extends JModelList
{
	public function getItems(){
		global $logger;
		$logger->info("QuestionsModelProfiles::getItems()");

		$logger->info("Will retrieve " . $this->getState("list.limit") . " records, starting from " . $this->getState("list.start") );

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
	        $question->tags = json_decode($question->tags);
	        
		}

		$items = $questions;
		
		$logger->info ( "Total rows are " . parent::getTotal() . ". (Retrieved " . count($questions) . " Questions" );
		
		return $items;
	}

	function getListQuery(){
		global $logger;
		$logger->info("QuestionsModelProfiles::getListQuery()");
		
		$userid = JFactory::getUser()->id;

		$db = JFactory::getDbo();

		$query = $db->getQuery(TRUE);
		$query->select("a.*, a.votes_positive-a.votes_negative as score, a.votes_positive+a.votes_negative as votes, (SELECT COUNT(*) 
		FROM #__questions_core AS b WHERE b.parent=a.id AND b.published=1) as answerscount, c.title AS CategoryName");
		$query->from("#__questions_core AS a");
		$query->leftJoin("#__categories AS c ON c.id=a.catid");
		$show_answers = $this->getState("filter.answers" , 0);
		$show_unpublished = $this->getState("filter.unpublished" , 0);
		
		$where = array();
		
		$where[] = "a.question=1"; // questions only
		//$where[] = "a.userid_creator=".$prouser;
		
		if ( ! $show_unpublished )
			$where[] = "a.published=1"; // only published items
		
		//apply filters
		if ( ! empty( $where ) )
			$query->where( $where );
		
		$ordering = $this->getState( "list.ordering" , "submitted" );
		$direction = $this->getState( "list.direction" , "DESC" );
		
		$query->order("$ordering $direction");

		$logger->info( "SQL Query: " . $query);
		
		return $query;
	}

	protected function populateState( $ordering = "submitted" , $direction = "DESC" ){
		global $logger;
		$logger->info("QuestionsModelProfiles::populateState($ordering , $direction)");
		
		$app = JFactory::getApplication();
		
		//$value = $app->getUserStateFromRequest('list.ordering', 'filter_order', $ordering);  
		//$value = $app->getUserStateFromRequest'list.direction', 'filter_order_Dir', $direction);
		
		$this->setState( "list.ordering" , $ordering );
		$this->setState( "list.direction" , $direction );
		$value = 2;
		//$value = JRequest::getInt('limit', $app->getCfg('list_limit', 0));
		$this->setState('list.limit', $value);

		$value = JRequest::getInt('limitstart', 0);
		$this->setState('list.start', $value);

		$user = JFactory::getUser();

		$logger->info("User ID: " . $user->id . " - Username: " . $user->name);

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
		$logger->info("State Populated!");
	}
	
    function GetUserList($page)
    {

     $menu = JSite::getMenu()->getActive();
     $params = new JParameter($menu->params);
     $mainframe = &JFactory::getApplication();
     $pathway   =& $mainframe->getPathway();	
     
    

     $db =JFactory::getDBO();
 		$query = " SELECT username, asked, answered, chosen, rank, userid FROM #__questions_userprofile";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		//print_r($rows);

     $html = '<table id="userprofiletable">';
     $html .= '<tr><th>' . JText::_('COM_QUESTIONS_PROFILE_USER') . '</th>';
     
     $html .= '<th>' . JText::_('COM_QUESTIONS_PROFILE_ANSWERED') . '</th><th>' . JText::_('COM_QUESTIONS_PROFILE_ASKED') . '</th><th>' . JText::_('COM_QUESTIONS_PROFILE_CHOSEN') . '</th><th>' . JText::_('COM_QUESTIONS_PROFILE_RANK') . '</th></tr>';
     foreach($rows as $row)
     {
       $html .= '<tr>';
       $userNameToShow = str_replace("[NAME]",$row->username, str_replace("[USERNAME]", $row->username,$params->get('userMask','[USERNAME]')));
       if($params->get('showDetail') == 0)
       {
         $html .= '<td><a href="' . JRoute::_("index.php?option=com_questions&view=profiles&id=".$row->userid . "%3A" . $row->username). '">'.$userNameToShow.'</a></td>';
       }
       else
       {
         $html .= '<td>'.$userNameToShow.'</td>';
       }
       $html .= '<td>'.$row->asked.'</td>';
       $html .= '<td>'.$row->answered.'</td>';
       $html .= '<td>'.$row->chosen.'</td>';
	   $html .= '<td>'.$row->rank.'</td>';
       $html .= '</tr>';

     }
     $html .= '</table>';

     // region Pages
     
	$html .= '<a href="'.JRoute::_("index.php?option=com_questions&view=profiles&page=".$i).'">'.($i+1).'</a>';
    $html .= '<p  style="text-align:right;"><small>Powered By <a target="_blank" href="http://phpseo.net/">http://phpseo.net/</a></small></p>';
     return $html;
  }

 /* function GetUserDetails($id)
  {
      $menu = JSite::getMenu()->getActive();
      $params = new JParameter($menu->params);
      $mainframe = &JFactory::getApplication();
      $pathway   =& $mainframe->getPathway();	

      $doc = JFactory::getDocument();
	  $id =  &JRequest::getInt('id', 0);

      $db =JFactory::getDBO();
 
      $query = "SELECT username, asked, answered, chosen, rank, userid, email FROM #__questions_userprofile where userid=" . $id;
      $db->setQuery( $query );
      $user = $db->loadObjectList();
	  return $user;
      $user = $user[0];
      $userNameToShow = str_replace("[NAME]",$user->username, str_replace("[USERNAME]", $user->username,$params->get('userMask','[USERNAME]')));
      $doc->setTitle(JText::_('COM_QUESTIONS_PROFILE_DETAILHEADER') . " " . $userNameToShow);
      $pathway->addItem(JText::_('COM_QUESTIONS_PROFILE_DETAILHEADER') . " " . $userNameToShow, '');
      
      $html = '';
      $html .= '<table id="userprofiledetailtable">';
	   $html .= '<tr><td>';
	   $html .= '<img class="questions_gravatar_big" src="http://www.gravatar.com/avatar/'.md5(strtolower(trim($user->email))).'?s=64" style="float:right; border:2px solid #333;" />';
	   $html .= '</td>';
	   $html .= '<td>';
	   $html .= '<table id="userprofiledetailtable">';
      $html .= '<tr class="rowstyle"><th class="userprofilekey">' . JText::_('COM_QUESTIONS_PROFILE_USER') . '</th><td>'.$userNameToShow.'</td></tr>';
      
     $html .= '<tr class="rowstyle"><th class="userprofilekey">' . JText::_('COM_QUESTIONS_PROFILE_ASKED') . '</th><td>'.$user->asked.'</td></tr>';
    $html .= '<tr class="rowstyle"><th class="userprofilekey">' . JText::_('COM_QUESTIONS_PROFILE_ANSWERED') . '</th><td>'.$user->answered.'</td></tr>';
	$html .= '<tr class="rowstyle"><th class="userprofilekey">' . JText::_('COM_QUESTIONS_PROFILE_CHOSEN') . '</th><td>'.$user->chosen.'</td></tr>';
	$html .= '<tr class="rowstyle"><th class="userprofilekey">' . JText::_('COM_QUESTIONS_PROFILE_RANK') . '</th><td>'.$user->rank.'</td></tr>';
	$html .= '</table>';
	$html .= '</td>';
	$html .= '</tr>';
    $html .= '</table>';

    $html .= '<br />';
    return $html;
  }*/
}
