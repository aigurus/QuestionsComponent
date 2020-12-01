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

class QuestionsModelGroups extends JModelList {

	function getUserGroups() {
		//$uid = JRequest::getInt( 'id' );
		
		$jinput = JFactory::getApplication()->input;
		
		
        $db = $this->getDBO();
		$uid = $db->escape($jinput->getInt('id'));
        //$user = JFactory::getUser();
        //$userid = $user->id;
		$query = $db->getQuery(true);
		
		$query->select($db->quoteName(array('id','group_name','published','created')));
		$query->from($db->quoteName('#__questions_groups'));
		if($uid>0){
			$query->where($db->quoteName('published') . ' =1 AND userid='.$uid);
		} else {
			$query->where($db->quoteName('published').' =1');
		}
		$query->order('created DESC');
		$db->setQuery($query);
		$groups = $db->loadObjectList();
        return $groups;
    }

	function getGroups($userid,$gid) {
        $db = $this->getDBO();
        $user = JFactory::getUser();
		//$jinput = JFactory::getApplication()->input;
		//$gid = $db->escape($jinput->getString('term'));
		//$gid = JRequest::getVar("term");
        //$userid = $user->id;
        $query = "select id,group_name from #__questions_groups WHERE published = 1 and userid=$userid and group_name LIKE '%" . $gid . "%'";
        $db->setQuery($query);
        $groups = $db->loadObjectList();
        return json_encode($groups);
    }
	
	function updateMyGroup($userid){
		$db = $this->getDBO();
		$query = $db->getQuery(true);
		$query
			->select($db->quoteName('groups'))
			->from($db->quoteName('#__questions_userprofile'))
			->where($db->quoteName('userid') . ' = '. $userid);
		$db->setQuery($query);
 		$results = $db->loadResult();
		return $results;
			
	}
	
	function sendNotification ($usergroups) {
		
		$db = $this->getDBO();
		
        $user = $db->escape($usergroups['userid']);
		$users = $db->escape($usergroups['users']);
		
		$sepusers = explode(",",$users);
		/*To do remaining*/
		foreach($sepusers as $sepuser){
			$sep = str_replace("user","",$sepuser);
			$uresult[]=$sep;
		}

		$groups = $db->escape($usergroups['groups']);
		$sepgroups = explode(",",$groups);
		foreach($sepgroups as $sepgroup){
			$sep = str_replace("group","",$sepgroup);
			$gresult[]=$sep;
		}

		$serializedusers = serialize($uresult);
		$serializedgroups = serialize($gresult);
		$date = JFactory::getDate();
		//the loop starts from here
		if(count($gresult)>0){
		foreach($gresult as $groupid){
			
		
		$query = $db->getQuery(true);
		
		$fields = array(
			$db->quoteName('requestsent') . "='".$serializedusers."'",
			$db->quoteName('modified') . "='".$date."'"
		);
		$conditions = array(
			$db->quoteName('userid') . '='.$user, 
			$db->quoteName('id') . '='.$groupid
		);
		$query->update($db->quoteName('#__questions_groups'))->set($fields)->where($conditions);
 		$db->setQuery($query);
		$result = $db->query();
		
		if(count($uresult)>0){
		foreach($uresult as $userid){
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$mygroup = $this->updateMyGroup($userid);
		if(!empty($mygroup) && isset($mygroup)){
			$newgroup = unserialize($mygroup);
			
			if(count($newgroup)>0){
				if(!in_array($groupid,$newgroup)){
				  	$newgroup[] = $groupid;
					$serializedgroup = serialize($newgroup);
					$fields = array(
					$db->quoteName('groups') . "='".$serializedgroup."'"
					);
					$conditions = array(
						$db->quoteName('userid') . '='.$userid
					);
					$query5 = $db->getQuery(true);
					$query5->update($db->quoteName('#__questions_userprofile'))->set($fields)->where($conditions);
					$db->setQuery($query5);
					$result = $db->query(); 
				} 
			}
		}
		else {
			$db = JFactory::getDBO();
			$query2 = $db->getQuery(true);
			$query2
			->update($db->quoteName('#__questions_userprofile'))
			->set($db->quoteName('groups').'='."'".serialize(array($groupid))."'")
			->where($db->quoteName('userid') . ' = '. $userid);
			$db->setQuery($query2);
			$db->query();
		}
		/*
		$query3 = $db->getQuery(true);
		$query3
			->select($db->quoteName(array('to_user', 'from_user', 'reference', 'type')))
			->from($db->quoteName('#__questions_notification'))
			->where($db->quoteName('to_user') . ' = '. $userid .' AND '. $db->quoteName('from_user') . ' = '. JFactory::getUser()->id .' AND '. $db->quoteName('reference') . ' = '. $groupid .' AND '. $db->quoteName('type') . ' = '. '"groupadd"');
		$db->setQuery($query3);
 		$results = $db->loadObjectList();
		if(count($results)<1){
		$date = date('m/d/Y h:i:s a', time());
		$columns = array('to_user', 'from_user', 'reference', 'type', 'timestamp');
		$values = array($userid, JFactory::getUser()->id, $groupid,'"groupadd"', strtotime($date));
		$query4 = $db->getQuery(true);
		$query4
			->insert($db->quoteName('#__questions_notification'))
			->columns($db->quoteName($columns))
			->values(implode(',', $values));
		$db->setQuery($query4);
		$db->query();
		} else {
			return false;
		}
		*/
		}
		
		}
		
		}
		}
	}
	/*
	function membersCount($groupid) {
        $db = $this->getDBO();
        $user = JFactory::getUser();
        $userid = $user->id;
        $query
			->select( 'friendsid' )
			->from($db->quoteName('#__questions_groups'))
			->where($db->quoteName('id')=$groupid);
        $db->setQuery($query);
        $result = $db->loadResult();
		$friends = unserialize($result);
		$friendscount = count($friends);
        return $friendscount;
    }
	*/
}


