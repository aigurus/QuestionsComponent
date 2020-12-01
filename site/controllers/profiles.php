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
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

class QuestionsControllerProfiles extends QueController 
			{

			public function __construct(){
				$view->setModel( $this->getModel( 'group' ), true );
				$view->display();
				parent::__construct();
				
			}
	
			public function getFavourite($userid){
					
				    $userid = (int) JRequest::getInt("userid");
					$db = JFactory::getDBO();
					$query2 = $db->getQuery(true);
					$query2->select("userfav");
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
   			public function favtable($arrayData,$userid,$inup,$qid){
   					
		   			$serializedData3 = serialize($arrayData);
					$data =new stdClass();
					$data->ansfav = null;
					$data->quesfav = null;
					$data->userfav = $serializedData3;					
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
					//$link  = JURI::current();
					//$qid = (int) JRequest::getInt("qid");
					$link  = JRoute::_("index.php?option=com_questions&view=profiles&id=" . $qid);
				    $msg   = 'Your favourites modified accordingly.';  
				    $app = JFactory::getApplication();
				    $app->redirect($link, $msg);
		   
		    }
   			public function addFavourite($userid,$addfav)
   			{
   					$reguser = JFactory::getUser();   
   					if( !$reguser->authorise("access.favourite" , "com_questions")){
					$msg = JText::_("COM_QUESTIONS_ERROR_UNAUTHORIZED");
					$this->setRedirect("index.php?option=com_users&view=login",$msg );
				    }
					else{
   					$addfav = (int) JRequest::getInt("addfav");
				    $userid = (int) JRequest::getInt("userid");

		   			$favarray = unserialize($this->getFavourite($userid));
					//var_dump($favarray);
				    if ((empty($favarray)) && $this->getusercount($userid)==0){	
					$arrayData =array($addfav);
					//var_dump($arrayData);
					$this->favtable($arrayData,$userid,'in',$addfav);
					}
					elseif (in_array($addfav, $favarray, true))	{
					return false;
					}
					else
					{
					$arrayData = $favarray;
					$arrayData[]=$addfav;
					//var_dump($arrayData);
					$this->favtable($arrayData,$userid,'up',$addfav);
					}
					}
	
		    }
     		public function delFavourite($userid,$delfav){

					$userid = (int) JRequest::getInt("userid");
					$delfav = (int) JRequest::getInt("delfav");
					$reguser = JFactory::getUser();   
   					if( !$reguser->authorise("access.favourite" , "com_questions")){
					$msg = JText::_("COM_QUESTIONS_ERROR_UNAUTHORIZED");
					$this->setRedirect("index.php?option=com_users&view=login",$msg );
				    }
					else{
		   			$favarray = unserialize($this->getFavourite($userid));
					//var_dump($favarray);
					$finalfav = array_search($delfav,$favarray);
					if (false !== $finalfav) {
						unset($favarray[$finalfav]);
					}
					//var_dump( $finalfav);
					$arrayData = $favarray;
					$this->favtable($arrayData,$userid,'up',$delfav);
					}
			}
	
}