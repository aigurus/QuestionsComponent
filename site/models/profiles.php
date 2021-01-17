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

// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.modellist' );
jimport( 'joomla.html.parameter' );
require_once 'components/com_questions/helpers/avatar.php';
use Joomla\CMS\Date\Date;
class QuestionsModelProfiles extends JModelList
{
		protected $i;
       function GetUserList()
       {
			 $menus = JFactory::getApplication()->getMenu();
			 $menu =$menus->getActive();
			 
			 //$params = new JParameter($menu->params);
			 $mainframe = JFactory::getApplication();
			 $pathway   = $mainframe->getPathway();	
			 
			 $app = JFactory::getApplication();
			 $params = $app->getParams();
			 
		
			 $db =JFactory::getDBO();
				$query = " SELECT username, asked, answered, email, chosen, rank, userid FROM #__questions_userprofile";
				$db->setQuery($query);
				$rows = $db->loadObjectList();
				//print_r($rows);
			return $rows;
			 /*$html = '<table id="userprofiletable">';
			 $html .= '<tr><th>' . JText::_('COM_QUESTIONS_PROFILE_USER') . '</th>';
			 
			 $html .= '<th>' . JText::_('COM_QUESTIONS_PROFILE_ASKED') . '</th><th>' . JText::_('COM_QUESTIONS_PROFILE_ANSWERED') . '</th><th>' . JText::_('COM_QUESTIONS_PROFILE_CHOSEN') . '</th><th>' . JText::_('COM_QUESTIONS_PROFILE_RANK') . '</th></tr>';
			 foreach($rows as $row)
			 {
				   $html .= '<tr>';
				   $userNameToShow = str_replace("[NAME]",$row->username, str_replace("[USERNAME]", $row->username,$params->get('userMask','[USERNAME]')));
				   if($params->get('showDetail') == 0)
				   {
					 $html .= '<td><a href="' . JRoute::_("index.php?option=com_questions&view=profiles&id=".$row->userid . "%3A" . $row->username). '">'.AvatarHelper::getAvatar($row->email,"questions_gravatar_big",64,0,$row->userid).'<br />'.$userNameToShow.'</a></td>';
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
     
	$html .= '<a href="'.JRoute::_("index.php?option=com_questions&view=profiles&page=".$this->i).'">'.($this->i+1).'</a>';
    return $html;*/
  }
  

  function getUserActivities($id)
  {
      	$db = JFactory::getDBO();
 
	  	$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__questions_userprofile');
		$query->where($db->quoteName('userid') . ' = ' .$id);
		$db->setQuery($query);
		//echo($query->__toString()); exit;
		$user = $db->loadObjectList();
		return $user;
		
  }
	
	
  function getUserQuestions()
  {
      	$db = JFactory::getDBO();
	    $jinput = JFactory::getApplication()->input;
		$uid = $jinput->getInt('id');
	  	$app = JFactory::getApplication();
		$params = $app->getParams();
		$limit =  $params->get('qlimit', 10);

 		$user = JFactory::getUser();
	    
		  if(isset($uid)){
			  $id = $uid;
		  }else{
			  $id = $user->id;
		  }
	  
	  	$query = $db->getQuery(true);
		$query->select(array('id','title','submitted','parent'));
		$query->from('#__questions_core');

	  	$query->where($db->quoteName('userid_creator') . " = " . $db->quote($id), 'AND');
    	$query->where($db->quoteName('question') . " != " . 0);
	    $query->setLimit($limit);
		$db->setQuery($query);
		//echo($query->__toString()); exit;
		$questions = $db->loadObjectList();
	    if($questions != null)
			return $questions;
		else
	  		return null;
  }
	
  function getUserAnswers()
  {
      	$db = JFactory::getDBO();
	    $jinput = JFactory::getApplication()->input;
		$uid = $jinput->getInt('id');
	    $app = JFactory::getApplication();
		$params = $app->getParams();
		$limit =  $params->get('alimit', 10);
 		$user = JFactory::getUser();
	    
		  if(isset($uid)){
			  $id = $uid;
		  }else{
			  $id = $user->id;
		  }
	  
	  	$query = $db->getQuery(true);
		$query->select(array('id','title','submitted','parent'));
		$query->from('#__questions_core');
		//$query->where(($db->quoteName('userid_creator') . ' = ' .$id) AND ($db->quoteName('question') . ' = 0'));
	  	$query->where($db->quoteName('userid_creator') . " = " . $db->quote($id), 'AND');
    	$query->where($db->quoteName('question') . " = " . 0);
	    $query->setLimit($limit);
		$db->setQuery($query);
		//echo($query->__toString()); exit;
		$questions = $db->loadObjectList();
	    if($questions != null)
			return $questions;
		else
	  		return null;
  }
  function getMyGroups(){
	  $user = JFactory::getUser();
	  $db =JFactory::getDBO();
	  $query = "SELECT groups FROM #__questions_userprofile where userid=" . $user->id;
      $db->setQuery( $query );
      $groups = $db->loadResult();
	  if(strlen($groups)>0){
		$groups = unserialize($groups);
	  } else {
		$groups = NULL;
	  }
	  return $groups;
  }
  function getMyGroupDetails($gid){
	  $db =JFactory::getDBO();
	  $query = "SELECT * FROM #__questions_groups where id=" . $gid;
      $db->setQuery( $query );
      $groupdetails = $db->loadObjectList();
	  return $groupdetails;
  }
  
  /*Topics*/
  function getTotalTopics(){
		$db = JFactory::getDbo();
		$query = $db
		->getQuery(true)
		->select('COUNT(*)')
		->from($db->quoteName('#__questions_core'))
		->where($db->quoteName('published') . " = 1"." AND ".$db->quoteName('question') . " =1 ");
		$db->setQuery( $query );
		$totalquestions = $db->loadResult();
	 	return $totalquestions;
  }
  
  function getLast7Topics(){
		$db = JFactory::getDbo();
		$Prevdays = new Date('now -7 days'); // Current date and time, -7 days.
		$query = $db
		->getQuery(true)
		->select('COUNT(*)')
		->from($db->quoteName('#__questions_core'))
		->where($db->quoteName('published') . " = 1"." AND ".$db->quoteName('question') . " =1 AND ".$db->quoteName('submitted') . " > " . $db->quote($Prevdays));
		$db->setQuery( $query );
		$totalquestions = $db->loadResult();
	 	return $totalquestions;
  }
  function getLast30Topics(){
		$db = JFactory::getDbo();
		$Prevdays = new Date('now -30 days'); // Current date and time, -7 days.
		$query = $db
		->getQuery(true)
		->select('COUNT(*)')
		->from($db->quoteName('#__questions_core'))
		->where($db->quoteName('published') . " = 1"." AND ".$db->quoteName('question') . " =1 AND ".$db->quoteName('submitted') . " > " . $db->quote($Prevdays));
		$db->setQuery( $query );
		$totalquestions = $db->loadResult();
	 	return $totalquestions;
  }
  
  /*Topics End*/
  /* Post */
  function getTotalPosts(){
		$db = JFactory::getDbo();
		$query = $db
		->getQuery(true)
		->select('COUNT(*)')
		->from($db->quoteName('#__questions_core'))
		->where($db->quoteName('published') . " = 1");
		$db->setQuery( $query );
		$totalquestions = $db->loadResult();
	 	return $totalquestions;
  }
  
  function getLast7Posts(){
		$db = JFactory::getDbo();
		$Prevdays = new Date('now -7 days'); // Current date and time, -7 days.
		$query = $db
		->getQuery(true)
		->select('COUNT(*)')
		->from($db->quoteName('#__questions_core'))
		->where($db->quoteName('published') . " = 1"." AND ".$db->quoteName('submitted') . " > " . $db->quote($Prevdays));
		$db->setQuery( $query );
		$totalquestions = $db->loadResult();
	 	return $totalquestions;
  }
  function getLast30Posts(){
		$db = JFactory::getDbo();
		$Prevdays = new Date('now -30 days'); // Current date and time, -7 days.
		$query = $db
		->getQuery(true)
		->select('COUNT(*)')
		->from($db->quoteName('#__questions_core'))
		->where($db->quoteName('published') . " = 1"." AND ".$db->quoteName('submitted') . " > " . $db->quote($Prevdays));
		$db->setQuery( $query );
		$totalquestions = $db->loadResult();
	 	return $totalquestions;
  }
  
   /* Post End*/
   
   
   /* Users */
  function getTotalUsers(){
		$db = JFactory::getDbo();
		$query = $db
		->getQuery(true)
		->select('COUNT(*)')
		->from($db->quoteName('#__questions_userprofile'))
		->where($db->quoteName('blocked') . " = 0");
		$db->setQuery( $query );
		$totalquestions = $db->loadResult();
	 	return $totalquestions;
  }
  
  function getLast7Users(){
		$db = JFactory::getDbo();
		$Prevdays = new Date('now -7 days'); // Current date and time, -7 days.
		$query = $db
		->getQuery(true)
		->select('COUNT(*)')
		->from($db->quoteName('#__questions_userprofile'))
		->where($db->quoteName('blocked') . " = 0"." AND ".$db->quoteName('logdate') . " > " . $db->quote($Prevdays));
		$db->setQuery( $query );
		$totalquestions = $db->loadResult();
	 	return $totalquestions;
  }
  function getLast30Users(){
		$db = JFactory::getDbo();
		$Prevdays = new Date('now -30 days'); // Current date and time, -7 days.
		$query = $db
		->getQuery(true)
		->select('COUNT(*)')
		->from($db->quoteName('#__questions_userprofile'))
		->where($db->quoteName('blocked') . " = 0"." AND ".$db->quoteName('logdate') . " > " . $db->quote($Prevdays));
		$db->setQuery( $query );
		$totalquestions = $db->loadResult();
	 	return $totalquestions;
  }
  
   /* Users End*/
}
