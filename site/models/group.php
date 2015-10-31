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

class QuestionsModelGroup extends JModelItem {
	
	protected $id;
	protected $item;
	
	/*
	function getUsers(){
		$username = JRequest::getVar('queryString');
		$db = $this->getDBO();
        $query = "SELECT username FROM #__users WHERE username LIKE '%" . $username . "%' and block = 0 ORDER BY id LIMIT 5";
        $db->setQuery($query);
		$result = $db->loadObjectList();
		//var_dump($result);
		//exit;
		return $result; 
	
	}*/
	function getUsers(){
		$username = JRequest::getVar('term');
		$db = $this->getDBO();
        $user = JFactory::getUser();
        $userid = $user->id;
        $query = "SELECT id,username,name FROM #__users WHERE username LIKE '%" . $username . "%' and block = 0 and id != ".$userid." ORDER BY id LIMIT 5";
        $db->setQuery($query);
		$result = $db->loadObjectList();
		$result = $this->objectToArray($result);
		$result = json_encode($result);
		return $result; 
	
	}
	
	function objectToArray($object)
		{
			if(!is_object($object) && !is_array($object))
			return $object;
		
			$array=array();
			foreach($object as $member=>$data)
			{
				$array[$member]=self::objectToArray($data);
			}
			return $array;
	}
	
	function getUserdetails(){
		$username = JRequest::getVar('queryString');
		$db = $this->getDBO();
        $user = JFactory::getUser();
        $userid = $user->id;
        $query = "SELECT s.id,s.username,c.points,c.rank FROM #__users s INNER JOIN #__questions_userprofile c ON s.id = c.userid WHERE s.username LIKE '%" . $username . "%' and s.block = 0 ORDER BY s.id LIMIT 8";
        $db->setQuery($query);
		$result = $db->loadObjectList();
		//var_dump($result);
		//exit;
		return $result; 
	
	}
	function addnewgroup($arr) {
        $db = $this->getDBO();
        $user = JFactory::getUser();
        $userid = $user->id;
        $date = JFactory::getDate();
        $current_date = $date->format("Y-m-d H:i:s");
        $group_name = $arr['group_name'];

        if (strpos($group_name, '_')) {
            $group_name = str_replace('_', ' & ', $group_name);
        }

        $query = "select id from #__questions_groups where group_name='$group_name' and (userid=$userid )";
        $db->setQuery($query);
        $pin_user_info = $db->loadObjectList();
        if (empty($pin_user_info)) {
            $query = 'INSERT INTO `#__questions_groups` (`id`, `userid`, `group_name`, `published`, `created`, `modified`) VALUES ("", "' . $userid . '", "' . addslashes($group_name) . '", "1",  now() , now())';
            $db->setQuery($query);

            if (!$db->query()) {
                $this->setError($db->getErrorMsg());
                return false;
            }
            $option = $db->insertid();

            return $option;
        } else {
            return;
        }
    }
	
	function deletegroup($id){
		$db = $this->getDBO();
		$user = JFactory::getUser();
		$query = "select userid from #__questions_groups where id=".$id;
        $db->setQuery($query);
		$ownerid = $db->loadResult();
		if($ownerid == $user->id){
		$query = "DELETE FROM #__questions_groups WHERE id=".$id;
        $db->setQuery($query);
		$db->query();
		return true; 
		}else{
		return false;	
		}
	
	}
		
}
